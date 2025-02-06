<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');

        if ($tab === 'mylist') {
            if (Auth::check()) {
                $products = Auth::user()->favorites;
            } else {
                return redirect()->route('login');
            }
        } else {
            $products = Product::query()
                ->when(Auth::check(), function ($query) {
                    $query->where('user_id', '!=', Auth::id());
                })
                ->get();
        }

        return view('products.product-list', [
            'products' => $products,
            'tab' => $tab,
        ]);
    }


    public function show($id)
    {
        // 商品データを取得
        $product = Product::findOrFail($id);

        // 商品詳細ページを表示
        return view('products.product-detail', compact('product'));
    }
    public function showProfile(Request $request)
    {
        /** @var \App\Models\User $user */

        $user = auth()->user();
        $tab = $request->query('tab', 'selling'); // デフォルトは 'selling'

        $sellingProducts = $user->products()->latest()->get() ?? collect([]);
        // 出品した商品
        $purchasedProducts = $user->purchases->map->product ?? collect([]); // 購入した商品


        return view('profile', compact('user', 'tab', 'sellingProducts', 'purchasedProducts'));
    }
    public function create()
    {
        $categories = Category::all(); // `categories` テーブルから全て取得
        return view('products.product-exhibit', compact('categories'));
    }

    public function store(Request $request)
    {


        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:1',
            'condition' => 'required|string',
            'category' => 'required|array', // ✅ 配列として受け取るように変更
            'category.*' => 'exists:categories,id', // ✅ 各カテゴリがDBに存在するかチェック
            'image' => 'nullable|image|max:2048',
        ]);

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

        return redirect()->route('mypage', ['tab' => 'selling'])->with('success', '商品を出品しました！');
    }
}
