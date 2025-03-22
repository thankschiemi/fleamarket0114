<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Http\Requests\ExhibitionRequest;

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
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $tab = $request->query('tab', 'sell');

        // 出品した商品（すべて）
        $sellingProducts = $user->products()->latest()->get() ?? collect([]);

        // 購入した商品（status = sold のみ）
        $purchasedProducts = $user->purchases()
            ->whereHas('product', function ($query) {
                $query->where('status', 'sold');
            })
            ->with('product')
            ->get();

        // ▼ 購入者としての取引中 or 売却済み商品（trading, sold）
        $buyingTrades = $user->purchases()
            ->whereHas('product', function ($query) {
                $query->whereIn('status', ['trading', 'sold']);
            })
            ->with('product')
            ->get();

        // ▼ 出品者としての取引中 or 売却済み商品（trading, sold）
        $sellingTrades = $user->products()
            ->whereIn('status', ['trading', 'sold'])
            ->get();

        // ▼ 両方統合（ビューで出品者か購入者か判別できるように）
        $tradingProducts = collect();

        foreach ($buyingTrades as $trade) {
            $tradingProducts->push((object)[
                'product' => $trade->product,
                'id' => $trade->id, // ← これを追加
                'is_seller' => false,
            ]);
        }

        foreach ($sellingTrades as $product) {
            $relatedPurchase = $product->purchases()->whereIn('status', ['trading', 'sold'])->first();

            $tradingProducts->push((object)[
                'product' => $product,
                'purchase_id' => $relatedPurchase ? $relatedPurchase->id : null,
                'is_seller' => true,
            ]);
        }


        return view('profile', compact(
            'user',
            'tab',
            'sellingProducts',
            'purchasedProducts',
            'tradingProducts'
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
