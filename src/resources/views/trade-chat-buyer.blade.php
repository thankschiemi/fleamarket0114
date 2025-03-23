@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/trade-chat.css') }}">
@endsection

@section('content')
<div class="trade-chat">
    <!-- サイドバー -->
    <div class="trade-chat__sidebar">
        <h2 class="trade-chat__sidebar-title">その他の取引</h2>
        <div class="trade-chat__sidebar-item">商品名</div>
        <div class="trade-chat__sidebar-item">商品名</div>
        <div class="trade-chat__sidebar-item">商品名</div>
    </div>
    <!-- メインコンテンツ -->
    <div class="trade-chat__main">
        <header class="trade-chat__header">
            <div class="trade-chat__user">
                <img src="{{ $tradeUser->profile_image_path ? asset($tradeUser->profile_image_path) : asset('images/default-avatar.jpg') }}"
                    alt="プロフィール画像" class="trade-chat__user-image">
                <h1 class="trade-chat__title">「{{ $tradeUser->name }}」さんとの取引画面</h1>
            </div>
        </header>

        <div class="trade-chat__info">
            <img src="{{ $trade->product->image_path }}" alt="商品画像" class="trade-chat__product-image">
            <div class="trade-chat__product-details">
                <h2 class="trade-chat__product-name">{{ $trade->product->name }}</h2>
                <p class="trade-chat__product-price">価格: ¥{{ number_format($trade->product->price) }}</p>
            </div>
        </div>

        <div class="trade-chat__messages">
            @foreach ($messages as $message)
            <div class="trade-chat__message {{ $message->user_id == auth()->id() ? 'trade-chat__message--buyer' : 'trade-chat__message--seller' }}">
                <img src="{{ asset($message->user->profile_image_path) }}" alt="プロフィール画像" class="trade-chat__message-icon">
                <div class="trade-chat__message-body">
                    <p class="trade-chat__message-sender">{{ $message->user->name }}</p>
                    <p class="trade-chat__message-text">{{ $message->content }}</p>
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