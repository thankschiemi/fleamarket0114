<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Rating;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreMessageRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\SellerTradeCompletedMail;

class TradeController extends Controller
{
    public function show($tradeId)
    {
        $userId = Auth::id();

        // ① 表示中の取引（買い手 or 売り手のみ閲覧可）
        $trade = Purchase::with(['product', 'product.user', 'messages.user'])
            ->where('id', $tradeId)
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId) // 自分が買い手
                    ->orWhereHas('product', fn($q2) => $q2->where('user_id', $userId)); // 自分が売り手
            })
            ->firstOrFail();

        // ② メッセージ（古い→新しい）
        $messages = $trade->messages->sortBy('created_at');

        // ③ 既読化（相手からの未読だけ）
        Message::where('purchase_id', $trade->id)
            ->where('user_id', '!=', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // ④ 相手ユーザー
        $tradeUser = $userId === $trade->user_id ? $trade->product->user : $trade->user;

        // ⑤ その他の取引（買い手・売り手の両方を全件、今見ている取引を除外）
        $activeStatuses = ['pending', 'sold', 'completed']; // 必要なら調整

        $otherTrades = Purchase::query()
            ->where('id', '!=', $trade->id)
            ->whereIn('status', $activeStatuses)
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId) // 自分が買い手の取引
                    ->orWhereHas('product', fn($q2) => $q2->where('user_id', $userId)); // 自分が売り手の取引
            })
            ->with('product')
            ->latest('updated_at') // 並びはお好みで
            ->get();

        // ⑥ 未読件数（まとめて取得）
        $unreadCounts = Message::selectRaw('purchase_id, COUNT(*) as cnt')
            ->whereIn('purchase_id', $otherTrades->pluck('id'))
            ->where('user_id', '!=', $userId)
            ->where('is_read', false)
            ->groupBy('purchase_id')
            ->pluck('cnt', 'purchase_id')
            ->toArray();

        // ⑦ 出品者側モーダル（出品者がまだ評価していない場合のみ表示）
        $shouldShowRatingModal = false;
        if ($userId === $trade->product->user_id && $trade->is_rated) {
            $alreadyRated = Rating::where('purchase_id', $trade->id)
                ->where('reviewer_id', $userId)
                ->exists();
            if (!$alreadyRated) $shouldShowRatingModal = true;
        }

        // ⑧ ビュー分岐（買い手/売り手）。※ otherTrades はどちらにも渡す
        if ($userId === $trade->user_id) {
            return view('trade-chat-buyer', [
                'trade'         => $trade,
                'tradeUser'     => $tradeUser,
                'messages'      => $messages,
                'otherTrades'   => $otherTrades,
                'unreadCounts'  => $unreadCounts,
            ]);
        } else {
            return view('trade-chat-seller', [
                'trade'                 => $trade,
                'tradeUser'             => $tradeUser,
                'messages'              => $messages,
                'otherTrades'           => $otherTrades,
                'unreadCounts'          => $unreadCounts,
                'shouldShowRatingModal' => $shouldShowRatingModal,
            ]);
        }
    }

    public function sendMessage(StoreMessageRequest $request, $tradeId)
    {
        $trade = Purchase::where('id', $tradeId)
            ->where(function ($q) {
                $q->where('user_id', Auth::id())
                    ->orWhereHas('product', fn($q2) => $q2->where('user_id', Auth::id()));
            })
            ->firstOrFail();

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('message_images', 'public');
        }

        Message::create([
            'purchase_id' => $trade->id,
            'user_id'     => Auth::id(),
            'content'     => $request->message,
            'image_path'  => $path,
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

        if ($trade->is_rated) {
            return redirect()->route('trade.show', ['trade_id' => $trade->id])
                ->with('error', 'すでに評価済みです');
        }

        Rating::create([
            'purchase_id'     => $trade->id,
            'reviewer_id'     => Auth::id(),
            'reviewed_user_id' => $trade->product->user_id,
            'score'           => $request->rating,
        ]);

        $trade->is_rated = true;
        $trade->status   = 'completed';
        $trade->save();

        // ★ 出品者に通知メール（保存後・リダイレクト前）
        $sellerEmail = $trade->product?->user?->email;
        if (!empty($sellerEmail)) {
            Mail::to($sellerEmail)->send(new SellerTradeCompletedMail($trade));
            // キュー化したい場合は ->queue(new ...);
        }

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
            ->whereHas('product', fn($q) => $q->where('user_id', Auth::id()))
            ->firstOrFail();

        if ($trade->is_rated_by_seller) {
            return redirect()->route('trade.show', ['trade_id' => $trade->id])
                ->with('error', 'すでに評価済みです');
        }

        Rating::create([
            'purchase_id'      => $trade->id,
            'reviewer_id'      => Auth::id(),
            'reviewed_user_id' => $trade->user_id,
            'score'            => $request->rating,
        ]);

        $trade->is_rated_by_seller = true;
        $trade->save();

        return redirect()->route('products.index')->with('success', '評価を送信しました');
    }
}
