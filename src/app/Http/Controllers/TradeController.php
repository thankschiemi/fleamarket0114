<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Rating;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreMessageRequest;

class TradeController extends Controller
{
    public function show($tradeId)
    {
        $trade = Purchase::with(['product', 'product.user', 'messages.user'])
            ->where('id', $tradeId)
            ->where(function ($query) {
                $query->where('user_id', Auth::id())
                    ->orWhereHas('product', function ($q) {
                        $q->where('user_id', Auth::id());
                    });
            })
            ->firstOrFail();

        $messages = $trade->messages->sortBy('created_at');

        $tradeUser = Auth::id() === $trade->user_id
            ? $trade->product->user
            : $trade->user;

        // ▼ 出品者がログインしている場合は、他の取引も取得
        $otherTrades = collect();
        if (Auth::id() === $trade->product->user_id) {
            $otherTrades = Purchase::whereHas('product', function ($query) {
                $query->where('user_id', Auth::id());
            })
                ->where('id', '!=', $trade->id)
                ->with('product')
                ->get();
        }

        // ⭐ 出品者がまだ評価していない場合、モーダルを表示する
        $shouldShowRatingModal = false;
        if (Auth::id() === $trade->product->user_id && $trade->is_rated) {
            $alreadyRated = Rating::where('purchase_id', $trade->id)
                ->where('reviewer_id', Auth::id())
                ->exists();

            if (!$alreadyRated) {
                $shouldShowRatingModal = true;
            }
        }

        // ビューの分岐（購入者 or 出品者）
        if (Auth::id() === $trade->user_id) {
            return view('trade-chat-buyer', compact('trade', 'tradeUser', 'messages'));
        } else {
            return view('trade-chat-seller', compact(
                'trade',
                'tradeUser',
                'messages',
                'otherTrades',
                'shouldShowRatingModal' // ← 追加！
            ));
        }
    }


    public function sendMessage(StoreMessageRequest $request, $tradeId)
    {
        // 取引データを取得（出品者 or 購入者のみ送信可能）
        $trade = Purchase::where('id', $tradeId)
            ->where(function ($query) {
                $query->where('user_id', Auth::id()) // 購入者
                    ->orWhereHas('product', function ($q) {
                        $q->where('user_id', Auth::id()); // 出品者
                    });
            })
            ->firstOrFail();

        // 画像があれば保存
        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('message_images', 'public');
        }

        // メッセージ保存
        Message::create([
            'purchase_id' => $trade->id,
            'user_id' => Auth::id(),
            'content' => $request->message,
            'image_path' => $path,
        ]);

        return redirect()->route('trade.show', ['trade_id' => $trade->id])
            ->with('success', 'メッセージを送信しました');
    }
    public function complete(Request $request, $tradeId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $trade = Purchase::with('product')
            ->where('id', $tradeId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // 重複評価防止（1回だけ）
        if ($trade->is_rated) {
            return redirect()->route('trade.show', ['trade_id' => $trade->id])
                ->with('error', 'すでに評価済みです');
        }

        // 評価を保存
        Rating::create([
            'purchase_id' => $trade->id,
            'reviewer_id' => Auth::id(), // ← 評価する側（購入者）
            'reviewed_user_id' => $trade->product->user_id, // ← 評価される側（出品者）
            'score' => $request->rating,
        ]);


        // 取引の評価済みフラグを更新
        $trade->is_rated = true;
        $trade->status = 'completed';
        $trade->save();

        return redirect()->route('products.index')
            ->with('success', '取引を完了し、評価を送信しました');
    }
    public function submitSellerRating(Request $request, $tradeId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $trade = Purchase::with('product')
            ->where('id', $tradeId)
            ->whereHas('product', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->firstOrFail();

        // すでに評価済みならスキップ
        if ($trade->is_rated_by_seller) {
            return redirect()->route('trade.show', ['trade_id' => $trade->id])
                ->with('error', 'すでに評価済みです');
        }

        Rating::create([
            'purchase_id' => $trade->id,
            'reviewer_id' => Auth::id(), // 出品者
            'reviewed_user_id' => $trade->user_id, // 購入者
            'score' => $request->rating,
        ]);

        $trade->is_rated_by_seller = true;
        $trade->save();

        return redirect()->route('products.index')->with('success', '評価を送信しました');
    }
}
