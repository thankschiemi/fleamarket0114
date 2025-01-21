<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // タブの切り替え: ?tab=mylist の場合は「マイリスト」を表示
        $tab = $request->query('tab', 'recommend'); // デフォルトは "recommend"

        if ($tab === 'mylist') {
            if (Auth::check()) {
                $products = Auth::user()->favorites; // お気に入りの商品を取得

            } else {
                return redirect()->route('login'); // ログインしていない場合はログイン画面へ
            }
        } else {
            $products = Product::all(); // 全商品を取得
        }

        return view('products.product-list', [
            'products' => $products,
            'tab' => $tab,
        ]);
    }
}
