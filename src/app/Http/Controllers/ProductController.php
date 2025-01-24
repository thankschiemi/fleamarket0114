<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');

        if ($tab === 'mylist') {
            if (Auth::check()) {
                $products = Auth::user()->favorites;
                Log::info('マイリストの商品データ:', $products->toArray());
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
}
