<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Product $product)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // `favoritedByProducts()` を使用して「いいね」の追加・削除
        if ($user->favoritedByProducts()->where('product_id', $product->id)->exists()) {
            $user->favoritedByProducts()->detach($product->id);
        } else {
            $user->favoritedByProducts()->attach($product->id);
        }

        return redirect()->back()->with('status', 'いいねを更新しました！');
    }
}
