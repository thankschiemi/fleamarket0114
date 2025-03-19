<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Message;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class TradeController extends Controller
{
    /**
     * 取引チャット画面を表示
     */
    public function show($tradeId)
    {
        // 取引データを取得
        $trade = Purchase::where('id', $tradeId)
            ->where('user_id', Auth::id())
            ->with(['product', 'product.user'])
            ->firstOrFail();

        $messages = Message::where('purchase_id', $trade->id)
            ->with('user')
            ->orderBy('created_at')
            ->get();

        return view('trade-chat', [
            'trade' => $trade,
            'product' => $trade->product,
            'tradeUser' => $trade->product->user,
            'messages' => $messages,
        ]);
    }
    public function sendMessage(Request $request, $tradeId)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        // 取引データを取得
        $trade = Purchase::where('id', $tradeId)
            ->where('user_id', Auth::id()) // 購入者のみがメッセージを送れるようにする
            ->firstOrFail();

        // メッセージを作成
        Message::create([
            'purchase_id' => $trade->id, // ここを追加
            'user_id' => Auth::id(),
            'content' => $request->message,
        ]);

        return redirect()->route('trade.show', ['trade_id' => $trade->id])
            ->with('success', 'メッセージを送信しました');
    }
}
