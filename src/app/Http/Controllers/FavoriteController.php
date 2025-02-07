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

        // `favoritedByProducts()` を使用して「いいね」の追加・削除
        if ($user->favoritedByProducts()->where('product_id', $product->id)->exists()) {
            $user->favoritedByProducts()->detach($product->id); // いいねを解除
        } else {
            $user->favoritedByProducts()->attach($product->id); // いいねを追加
        }

        return redirect()->back()->with('status', 'いいねを更新しました！');
    }
}
