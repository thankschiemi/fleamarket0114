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
        <img
            src="{{ $user->profile_image_path ? asset($user->profile_image_path) : asset('images/default-avatar.jpg') }}"
            alt="プロフィール画像"
            class="profile__image profile__image--lg">

        <div class="profile__namewrap">
            <p class="profile__name">{{ $user->name }}</p>

            @if(!empty($ratingCount) && $ratingCount > 0)
            <div class="profile__stars" role="img" aria-label="評価 {{ $ratingAvg }}/5（{{ $ratingCount }}件）">
                @for($i = 1; $i <= 5; $i++)
                    <span class="star {{ $i <= $ratingAvg ? 'is-filled' : '' }}">★</span>
                    @endfor
            </div>
            @endif
        </div>

        <a href="{{ route('mypage.profile') }}" class="profile__edit-button">プロフィールを編集</a>
    </div>



    <div class="profile__tabs">
        <a href="?tab=sell" class="profile__tab {{ $tab === 'sell' ? 'profile__tab--active' : '' }}">出品した商品</a>
        <a href="?tab=buy" class="profile__tab {{ $tab === 'buy' ? 'profile__tab--active' : '' }}">購入した商品</a>
        <a href="?tab=trade" class="profile__tab {{ $tab === 'trade' ? 'profile__tab--active' : '' }} position-relative">
            取引中の商品
            @if ($unreadCountTotal > 0)
            <span class="notification-badge-tab">{{ $unreadCountTotal }}</span>
            @endif
        </a>
    </div>


</div>

{{-- 出品した商品 --}}
@if ($tab === 'sell')
<div class="profile__products">
    @forelse ($sellingProducts as $product)
    @if (in_array($product->status, ['available', 'sold', 'completed']))
    {{-- ★リンクを出さずに div で包む＝無反応 --}}
    <div class="profile__product-link profile__product-link--disabled" aria-disabled="true" tabindex="-1">
        <div class="profile__product-card">
            <img src="{{ $product->image_path }}" alt="商品画像" class="profile__product-image">
            <p class="profile__product-name">{{ $product->name }}</p>

            @if ($product->status === 'sold')
            <span class="profile__product-status">Sold</span>
            @elseif ($product->status === 'completed')
            <span class="profile__product-status">completed</span>
            @endif
        </div>
    </div>
    @endif
    @empty
    <p class="profile__empty">商品がありません。</p>
    @endforelse
</div>
@endif



{{-- 購入した商品 --}}
@if ($tab === 'buy')
<div class="profile__products">
    @forelse ($purchasedProducts as $purchase)
    @if ($purchase->product && in_array($purchase->product->status, ['sold','completed']))

    {{-- ★購入済みは常に無反応（リンク無し） --}}
    <div class="profile__product-link profile__product-link--disabled" aria-disabled="true" tabindex="-1">
        <div class="profile__product-card">
            <img src="{{ $purchase->product->image_path }}" alt="商品画像" class="profile__product-image">
            <p class="profile__product-name">{{ $purchase->product->name }}</p>
            <span class="profile__product-status">
                {{ $purchase->product->status === 'sold' ? 'Sold' : 'completed' }}
            </span>
        </div>
    </div>

    @endif
    @empty
    <p class="profile__empty">商品がありません。</p>
    @endforelse
</div>
@endif


@if ($tab === 'trade')
<div class="profile__products">
    @forelse ($tradingProducts as $trade)
    @if ($trade->product && in_array($trade->status, ['pending', 'sold', 'completed']))

    <a href="{{ route('trade.show', ['trade_id' => $trade->id]) }}" class="profile__product-link">
        <div class="profile__product-card">
            {{-- ⭐ 通知バッジ（未読メッセージ数） --}}
            @if (!empty($trade->unread_count) && $trade->unread_count > 0)
            <span class="notification-badge">{{ $trade->unread_count }}</span>
            @endif
            <img src="{{ $trade->product->image_path }}" alt="商品画像" class="profile__product-image">
            <p class="profile__product-name">{{ $trade->product->name }}</p>

            @if ($trade->product->status === 'sold')
            @if ($trade->is_seller)
            <span class="profile__product-status">SOLD</span>
            @else
            <span class="profile__product-status">購入済</span>
            @endif
            @elseif ($trade->product->status === 'trading' || $trade->status === 'pending')
            <span class="profile__product-status">取引中</span>
            @endif



        </div>
    </a>
    @endif
    @empty
    <p class="profile__empty">取引中の商品がありません。</p>
    @endforelse
</div>
@endif







@endsection