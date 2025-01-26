<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * コメントを保存する
     */
    public function store(Request $request, Product $product)
    {
        // バリデーション
        $request->validate([
            'comment' => 'nullable|string|max:500', // コメントは任意
        ]);

        // コメントを保存
        $review = new Review();
        $review->user_id = Auth::id(); // ログインユーザーのID
        $review->product_id = $product->id; // コメント対象の商品
        $review->comment = $request->input('comment');
        $review->save();

        return redirect()->back()->with('status', 'コメントを投稿しました！');
    }
}
