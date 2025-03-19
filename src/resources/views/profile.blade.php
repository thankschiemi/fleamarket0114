@extends('layouts.app_with_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="profile__header">
        <img src="{{ $user->profile_image_path ? asset($user->profile_image_path) : asset('images/default-avatar.jpg') }}"
            alt="プロフィール画像" class="profile__image">
        <p class="profile__name">{{ $user->name }}</p>
        <div class="profile__rating">
            ★★★★★
        </div>
        <a href="{{ route('mypage.profile') }}" class="profile__edit-button">プロフィールを編集</a>
    </div>
    <div class="profile__tabs">
        <a href="?tab=sell" class="profile__tab {{ $tab === 'sell' ? 'profile__tab--active' : '' }}">出品した商品</a>
        <a href="?tab=buy" class="profile__tab {{ $tab === 'buy' ? 'profile__tab--active' : '' }}">購入した商品</a>
        <a href="?tab=trade" class="profile__tab {{ $tab === 'trade' ? 'profile__tab--active' : '' }}">取引中の商品</a>
    </div>
</div>
@if ($tab === 'sell')
<div class="profile__products">
    @forelse ($sellingProducts as $product)
    <a href="{{ route('product.show', ['item_id' => $product->id]) }}" class="profile__product-link">
        <div class="profile__product-card">
            <img src="{{ $product->image_path }}" alt="商品画像" class="profile__product-image">
            <p class="profile__product-name">{{ $product->name }}</p>
        </div>
    </a>
    @empty
    <p class="profile__empty">商品がありません。</p>
    @endforelse
</div>
@endif
@if ($tab === 'buy')
<div class="profile__products">
    @forelse ($purchasedProducts as $purchase)
    @if ($purchase['product'])
    <a href="{{ route('product.show', ['item_id' => $purchase['product']['id']]) }}" class="profile__product-link">
        <div class="profile__product-card">
            <img src="{{ $purchase['product']['image_path'] }}" alt="商品画像" class="profile__product-image">
            <p class="profile__product-name">{{ $purchase['product']['name'] }}</p>
        </div>
    </a>
    @endif
    @empty
    <p class="profile__empty">商品がありません。</p>
    @endforelse
</div>
@endif
@if ($tab === 'trade')
<div class="profile__products">
    @forelse ($tradingProducts as $trade)
    @if ($trade->product)
    <a href="{{ route('trade.show', ['trade_id' => $trade->id]) }}" class="profile__product-link">
        <div class="profile__product-card">
            <img src="{{ $trade['product']['image_path'] }}" alt="商品画像" class="profile__product-image">
            <p class="profile__product-name">{{ $trade->product->name }}</p>
        </div>
    </a>
    @endif
    @empty
    <p class="profile__empty">取引中の商品がありません。</p>
    @endforelse
</div>
@endif

@endsection