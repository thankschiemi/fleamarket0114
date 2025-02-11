<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

class ReviewController extends Controller
{
    public function store(CommentRequest $request, Product $product)
    {
        $data = $request->validated();

        $review = new Review();
        $review->user_id = Auth::id();
        $review->product_id = $product->id;
        $review->comment = $request->input('comment');
        $review->save();

        return redirect()->back()->with('status', 'コメントを投稿しました！');
    }
}
