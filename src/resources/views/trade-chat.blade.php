@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/trade-chat.css') }}">
@endsection

@section('content')
<div class="trade-chat">
    <!-- サイドバー -->
    <div class="trade-chat__sidebar">
        <div class="trade-chat__sidebar-item">商品名</div>
        <div class="trade-chat__sidebar-item">商品名</div>
        <div class="trade-chat__sidebar-item">商品名</div>
    </div>

    <!-- メインコンテンツ -->
    <div class="trade-chat__main">
        <header class="trade-chat__header">
            <h1 class="trade-chat__title">「{{ $tradeUser->name }}」さんとの取引画面</h1>
            <button class="trade-chat__complete-button">取引を完了する</button>
        </header>

        <div class="trade-chat__info">
            <img src="{{ asset($product->img_url) }}" alt="商品画像" class="trade-chat__product-image">
            <div class="trade-chat__product-details">
                <h2 class="trade-chat__product-name">{{ $product->name }}</h2>
                <p class="trade-chat__product-price">価格: ¥{{ number_format($product->price) }}</p>
            </div>
        </div>

        <div class="trade-chat__messages">
            @foreach ($messages as $message)
            <div class="trade-chat__message {{ $message->user_id == auth()->id() ? 'trade-chat__message--self' : 'trade-chat__message--other' }}">
                <img src="{{ asset($message->user->profile_image_path) }}" alt="プロフィール画像" class="trade-chat__profile-image">
                <div class="trade-chat__message-content">
                    <p class="trade-chat__message-text">{{ $message->content }}</p>
                    <span class="trade-chat__message-time">{{ $message->created_at->format('H:i') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="trade-chat__input">
            <form action="{{ route('trade.message.send', ['trade_id' => $trade->id]) }}" method="POST" class="trade-chat__form">
                @csrf
                <input type="text" name="message" class="trade-chat__input-field" placeholder="取引メッセージを記入してください" required>
                <button type="submit" class="trade-chat__send-button">送信</button>
            </form>
            <button class="trade-chat__upload-button">画像を追加</button>
        </div>
    </div>
</div>
@endsection