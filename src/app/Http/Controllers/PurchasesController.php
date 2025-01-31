<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

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
        $session_id = $request->query('session_id');
        if (!$session_id) {
            return redirect()->route('purchase.show', ['item_id' => $item_id])
                ->with('error', '決済セッションが見つかりませんでした。');
        }

        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $session = $stripe->checkout->sessions->retrieve($session_id);

        // 支払い方法を取得
        $paymentMethod = $session->payment_method_types[0] ?? 'card';

        // ✅ コンビニ払いの場合は「支払い待ち」として処理
        if ($paymentMethod === 'konbini') {
            Purchase::create([
                'user_id' => Auth::id(),
                'product_id' => $item_id,
                'payment_method' => 'convenience_store',
                'status' => 'completed', // ✅ すぐに「購入完了」にする
                'purchase_date' => now(),
            ]);

            return redirect()->route('products.index')->with('success', 'コンビニ払いの購入が完了しました！（テスト用）');
        }

        // ✅ カード決済は即時完了
        if ($session->payment_status === 'paid') {
            Purchase::create([
                'user_id' => Auth::id(),
                'product_id' => $item_id,
                'payment_method' => 'credit_card',
                'status' => 'completed', // ⬅️ カード支払いは即完了
                'purchase_date' => now(),
            ]);

            return redirect()->route('products.index')->with('success', '購入が完了しました！');
        }

        return redirect()->route('purchase.show', ['item_id' => $item_id])
            ->with('error', '支払いが確認できませんでした。');
    }



    public function checkout(Request $request, $item_id)
    {
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
}
