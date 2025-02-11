<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;

class PurchasesController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['user', 'product'])->get();
        return view('purchases.index', compact('purchases'));
    }

    public function show($item_id)
    {
        $product = Product::findOrFail($item_id);
        return view('products.product-purchase', compact('product'));
    }

    public function complete(Request $request, $item_id)
    {
        // テスト環境での処理
        if (app()->environment('testing')) {
            Purchase::create([
                'user_id' => Auth::id(),
                'product_id' => $item_id,
                'payment_method' => 'credit_card',
                'status' => 'completed',
                'purchase_date' => now(),
            ]);

            // 商品を「SOLD」にする
            $product = Product::find($item_id);
            if ($product) {
                $product->update(['is_sold' => true]);
            }

            return redirect()->route('products.index')->with('success', '購入が完了しました！（テスト環境）');
        }

        // 本番環境での処理
        $session_id = $request->query('session_id');
        if (!$session_id) {
            return redirect()->route('purchase.show', ['item_id' => $item_id])
                ->with('error', '決済セッションが見つかりませんでした。');
        }

        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $session = $stripe->checkout->sessions->retrieve($session_id);

        // 支払い方法を取得
        $paymentMethod = $session->payment_method_types[0] ?? 'card';

        // コンビニ払いの場合は「支払い待ち」にする
        if ($paymentMethod === 'konbini') {
            Purchase::create([
                'user_id' => Auth::id(),
                'product_id' => $item_id,
                'payment_method' => 'convenience_store',
                'status' => 'pending', // 支払い待ち
                'purchase_date' => now(),
            ]);

            return view('purchase.pending', compact('item_id')); // 支払い待ち画面へ
        }

        // カード決済は即時完了
        if ($session->payment_status === 'paid') {
            Purchase::create([
                'user_id' => Auth::id(),
                'product_id' => $item_id,
                'payment_method' => 'credit_card',
                'status' => 'completed',
                'purchase_date' => now(),
            ]);

            // 商品を「SOLD」にする
            $product = Product::find($item_id);
            if ($product) {
                $product->update(['is_sold' => true]);
            }

            return redirect()->route('products.index')->with('success', '購入が完了しました！');
        }

        return redirect()->route('purchase.show', ['item_id' => $item_id])
            ->with('error', '支払いが確認できませんでした。');
    }



    public function checkout(PurchaseRequest $request, $item_id)

    {

        $validatedData = $request->validated();


        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $product = Product::findOrFail($item_id);

        $maxPrice = 300000; // Stripeの上限額（セント単位）
        $priceInYen = intval($product->price); // ここでは *100 しない
        $unitAmount = $product->price;

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card', 'konbini'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $unitAmount, // ここが正しく設定されているか
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.complete', ['item_id' => $product->id]) . '?session_id={CHECKOUT_SESSION_ID}',
        ]);

        return redirect($session->url);
    }
    public function editAddress($item_id)
    {
        $product = Product::findOrFail($item_id);
        $user = auth()->user(); // ログインユーザー情報
        return view('products.product-shipping-edit', compact('product', 'user'));
    }
    public function updateAddress(Request $request, $item_id)
    {
        $request->validate([
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->building_name = $request->building;
        $user->save();
        return redirect()->route('purchase.show', ['item_id' => $item_id])->with('success', '住所が更新されました！');
    }
}
