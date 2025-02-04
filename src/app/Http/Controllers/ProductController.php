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
            $products = Product::when(Auth::check(), function ($query) {
                $query->where('user_id', '!=', Auth::id());
            })
                ->get()
                ->map(function ($product) {
                    $product->is_sold = (bool) $product->is_sold;
                    return $product;
                });
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
        $user = auth()->user();
        $tab = $request->query('tab', 'selling'); // デフォルトは 'selling'

        $sellingProducts = $user->products ?? collect([]); // 出品した商品
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
            'category' => 'required|array',
            'category' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        // 画像アップロード処理
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // 商品の保存
        $product = Product::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'image_path' => $imagePath,
        ]);

        if ($request->has('category')) {
            $product->categories()->attach($request->category);
        }

        return redirect()->route('mypage')->with('success', '商品を出品しました！');
    }
}
