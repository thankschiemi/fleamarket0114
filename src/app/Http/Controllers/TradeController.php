<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
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
                ->where('id', '!=', $trade->id) // 現在の取引は除外
                ->with('product')
                ->get();
        }

        if (Auth::id() === $trade->user_id) {
            return view('trade-chat-buyer', compact('trade', 'tradeUser', 'messages'));
        } else {
            return view('trade-chat-seller', compact('trade', 'tradeUser', 'messages', 'otherTrades'));
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
}
