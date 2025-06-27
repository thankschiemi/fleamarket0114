<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Message;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');
        $query = $request->query('query', '');

        if ($tab === 'mylist') {
            if (!Auth::check()) {
                return redirect()->route('login')->with('redirect_to', request()->fullUrl());
            }

            $products = Product::whereHas('favoritedByUsers', function ($queryBuilder) {
                $queryBuilder->where('users.id', Auth::id());
            })
                ->when($query, function ($queryBuilder) use ($query) {
                    return $queryBuilder->where('name', 'LIKE', "%{$query}%");
                })
                ->get();
        } else {
            $products = Product::query()
                ->when(Auth::check(), function ($queryBuilder) {
                    return $queryBuilder->where('user_id', '!=', Auth::id()); // 自分の商品は非表示
                })
                ->when($query, function ($queryBuilder) use ($query) {
                    return $queryBuilder->where('name', 'LIKE', "%{$query}%");
                })
                ->orderByRaw("FIELD(status, 'available', 'trading', 'sold')") // 並び順を適用
                ->get();
        }

        return view('products.product-list', [
            'products' => $products,
            'tab' => $tab,
            'searchQuery' => $query,
        ]);
    }


    public function show($id)
    {
        $product = Product::with(['favoritedByUsers', 'reviews.user', 'categories'])->findOrFail($id);

        return view('products.product-detail', compact('product'));
    }


    public function showProfile(Request $request)
    {
        $user = auth()->user();
        $tab = $request->query('tab', 'sell');

        // 出品した商品（すべて）
        $sellingProducts = \App\Models\Product::where('user_id', $user->id)->latest()->get();

        // 購入した商品
        $purchasedProducts = \App\Models\Purchase::where('user_id', $user->id)
            ->whereHas('product', function ($query) {
                $query->whereIn('status', ['sold', 'completed']);
            })
            ->with('product')
            ->get();

        // 購入者としての取引中 or 完了
        $buyingTrades = \App\Models\Purchase::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'sold', 'completed'])
            ->with('product')
            ->get();

        // 出品者としての取引中 or 完了
        $sellingTrades = \App\Models\Product::where('user_id', $user->id)
            ->whereHas('purchases', function ($q) {
                $q->whereIn('status', ['pending', 'completed']);
            })
            ->with(['purchases' => function ($q) {
                $q->whereIn('status', ['pending', 'completed']);
            }])
            ->get();

        // 統合
        $tradingProducts = collect();
        $unreadCounts = [];

        foreach ($buyingTrades as $trade) {
            $unreadCount = Message::where('purchase_id', $trade->id)
                ->where('user_id', '!=', $user->id)
                ->where('is_read', false)
                ->count();

            $unreadCounts[$trade->id] = $unreadCount;

            $tradingProducts->push((object)[
                'product' => $trade->product,
                'id' => $trade->id,
                'status' => $trade->status,
                'is_seller' => false,
                'unread_count' => $unreadCount,
            ]);
        }

        foreach ($sellingTrades as $product) {
            $relatedPurchase = $product->purchases->first();
            if ($relatedPurchase) {
                $unreadCount = Message::where('purchase_id', $relatedPurchase->id)
                    ->where('user_id', '!=', $user->id)
                    ->where('is_read', false)
                    ->count();

                $unreadCounts[$relatedPurchase->id] = $unreadCount;

                $tradingProducts->push((object)[
                    'product' => $product,
                    'id' => $relatedPurchase->id,
                    'status' => $relatedPurchase->status,
                    'is_seller' => true,
                    'unread_count' => $unreadCount,
                ]);
            }
        }
        $relatedPurchaseIds = \App\Models\Purchase::where('user_id', $user->id)
            ->orWhereHas('product', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->pluck('id');

        $unreadCountTotal = \App\Models\Message::where('is_read', false)
            ->where('user_id', '!=', $user->id)
            ->whereIn('purchase_id', $relatedPurchaseIds)
            ->count();



        return view('profile', compact(
            'user',
            'tab',
            'sellingProducts',
            'purchasedProducts',
            'tradingProducts',
            'unreadCounts',
            'unreadCountTotal'
        ));
    }






    public function create()
    {

        if (!auth()->check()) {
            return redirect()->route('login')->with('redirect_to', request()->fullUrl());
        }
        $categories = Category::all();
        return view('products.product-exhibit', compact('categories'));
    }








    public function store(ExhibitionRequest $request)
    {
        $data = $request->validated();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // 拡張子が取得できない場合の対応
            $originalName = $file->getClientOriginalName();
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);

            if (!$extension) {
                $extension = 'jpg'; // 拡張子が取得できない場合はデフォルトを設定
            }

            // ランダムなファイル名を生成
            $filename = time() . '_' . uniqid() . '.' . $extension;

            // public/storage/products に保存
            $file->storeAs('public/products', $filename);

            // データベースには 'products/filename.jpg' の形で保存
            $imagePath = 'products/' . $filename;
        }



        // 商品の保存
        $product = Product::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'img_url' => $imagePath,
        ]);



        if ($request->has('category')) {
            $product->categories()->attach($request->category);
        }

        return redirect()->route('mypage', ['tab' => 'sell'])->with('success', '商品を出品しました！');
    }
}
