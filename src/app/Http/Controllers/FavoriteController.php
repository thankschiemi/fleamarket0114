<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Product $product)
    {
        // 現在の認証済みユーザー
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // いいねをトグルする（存在すれば削除、存在しなければ追加）
        $user->favorites()->toggle($product->id);
        return redirect()->back()->with('status', 'いいねを更新しました！');
    }
}
