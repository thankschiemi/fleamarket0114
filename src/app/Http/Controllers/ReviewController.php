<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

class ReviewController extends Controller
{
    /**
     * コメントを保存する
     */
    public function store(CommentRequest $request, Product $product)
    {
        $data = $request->validated();

        // コメントを保存
        $review = new Review();
        $review->user_id = Auth::id(); // ログインユーザーのID
        $review->product_id = $product->id; // コメント対象の商品
        $review->comment = $request->input('comment');
        $review->save();

        return redirect()->back()->with('status', 'コメントを投稿しました！');
    }
}
