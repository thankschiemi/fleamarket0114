@extends('layouts.app_with_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile">
    <div class="profile__header">
        <img src="{{ $user->profile_image_path ? asset($user->profile_image_path) : asset('images/default-avatar.jpg') }}"
            alt="プロフィール画像" class="profile__image">
        <p class="profile__name">{{ $user->name }}</p>
        <a href="{{ route('mypage.profile') }}" class="profile__edit-button">プロフィールを編集</a>
    </div>
    <div class="profile__tabs">
        <a href="?tab=selling" class="profile__tab {{ $tab === 'selling' ? 'profile__tab--active' : '' }}">出品した商品</a>
        <a href="?tab=purchased" class="profile__tab {{ $tab === 'purchased' ? 'profile__tab--active' : '' }}">購入した商品</a>
    </div>
</div>
@if ($tab === 'selling')
<div class="profile__products">
    @forelse ($sellingProducts as $product)
    <a href="{{ route('product.show', ['item_id' => $product->id]) }}" class="profile__product-link">
        <div class="profile__product-card">
            <img src="{{ $product->image_path ?? asset('images/default-product.jpg') }}" alt="商品画像"
                class="profile__product-image">
            <p class="profile__product-name">{{ $product->name }}</p>
        </div>
    </a>
    @empty
    <p class="profile__empty">商品がありません。</p>
    @endforelse
</div>
@endif
@if ($tab === 'purchased')
<div class="profile__products">
    @forelse ($purchasedProducts as $product)
    <a href="{{ route('product.show', ['item_id' => $product->id]) }}" class="profile__product-link">
        <div class="profile__product-card">
            <img src="{{ $product->image_path ?? asset('images/default-product.jpg') }}" alt="商品画像"
                class="profile__product-image">
            <p class="profile__product-name">{{ $product->name }}</p>
        </div>
    </a>
    @empty
    <p class="profile__empty">商品がありません。</p>
    @endforelse
</div>
@endif
</div>
@endsection