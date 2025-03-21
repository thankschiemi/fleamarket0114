<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class TradeController extends Controller
{
    /**
     * 取引チャット画面を表示
     */
    public function show($tradeId)
    {
        // 取引データを取得（購入者 or 出品者がアクセス可能）
        $trade = Purchase::with(['product', 'product.user', 'messages.user'])
            ->where('id', $tradeId)
            ->where(function ($query) {
                $query->where('user_id', Auth::id()) // 購入者
                    ->orWhereHas('product', function ($q) {
                        $q->where('user_id', Auth::id()); // 出品者
                    });
            })
            ->firstOrFail();

        $messages = $trade->messages->sortBy('created_at'); // メッセージを時系列で取得

        // 取引相手を取得
        $tradeUser = Auth::id() === $trade->user_id
            ? $trade->product->user // 自分が購入者なら相手は出品者
            : $trade->user; // 自分が出品者なら相手は購入者

        // 出品者か購入者かでビューを分ける
        if (Auth::id() === $trade->user_id) {
            return view('trade-chat-buyer', compact('trade', 'tradeUser', 'messages'));
        } else {
            return view('trade-chat-seller', compact('trade', 'tradeUser', 'messages'));
        }
    }

    /**
     * メッセージ送信
     */
    public function sendMessage(Request $request, $tradeId)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        // 取引データを取得（出品者 or 購入者のみ送信可能）
        $trade = Purchase::where('id', $tradeId)
            ->where(function ($query) {
                $query->where('user_id', Auth::id()) // 購入者
                    ->orWhereHas('product', function ($q) {
                        $q->where('user_id', Auth::id()); // 出品者
                    });
            })
            ->firstOrFail();

        // メッセージを作成
        Message::create([
            'purchase_id' => $trade->id,
            'user_id' => Auth::id(),
            'content' => $request->message,
        ]);

        return redirect()->route('trade.show', ['trade_id' => $trade->id])
            ->with('success', 'メッセージを送信しました');
    }
}
