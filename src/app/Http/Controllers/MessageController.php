<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function edit(Message $message)
    {
        $this->authorize('update', $message);

        return view('messages.edit', compact('message'));
    }

    public function update(Request $request, Message $message)
    {
        $this->authorize('update', $message);

        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $message->content = $request->content;
        $message->save();

        return redirect()->route('trade.show', ['trade_id' => $message->purchase_id])
            ->with('success', 'メッセージを更新しました');
    }

    public function destroy(Message $message)
    {
        $this->authorize('delete', $message);

        $tradeId = $message->purchase_id;
        $message->delete();

        return redirect()->route('trade.show', ['trade_id' => $tradeId])
            ->with('success', 'メッセージを削除しました');
    }
}
