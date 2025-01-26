@extends('layouts.app_with_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
@endsection

@section('content')
<div class="product-detail">
    <!-- 商品画像 -->
    <div class="product-detail__image">
        <img src="{{ $product->image_path ?? asset('images/default-product.jpg') }}" alt="商品画像"
            class="product-detail__image--main">
    </div>

    <!-- 商品情報 -->
    <div class="product-detail__info">
        <h1 class="product-detail__title">{{ $product->name }}</h1>
        <p class="product-detail__brand">ブランド名</p>
        <p class="product-detail__price">¥{{ number_format($product->price) }}（税込）</p>

        <div class="product-detail__actions">
            <div class="product-detail__icon-group">
                <!-- いいね機能 -->
                <div class="product-detail__icon">
                    <form method="POST" action="{{ route('favorites.toggle', $product->id) }}">
                        @csrf
                        <button type="submit" class="product-detail__icon-star">
                            @if (Auth::check() && Auth::user()->favorites->contains($product->id))
                            ★
                            @else
                            ☆
                            @endif
                        </button>
                    </form>
                    <span class="product-detail__icon-count">{{ $product->favoritedByUsers->count() }}</span>
                </div>

                <!-- コメント数表示 -->
                <div class="product-detail__icon">
                    <span class="product-detail__icon-comment">💬</span>
                    <span class="product-detail__icon-count">{{ $product->reviews->count() }}</span>
                </div>
            </div>

            <!-- ボタン -->
            <button class="product-detail__buy-button">購入手続きへ</button>
        </div>

        <!-- 商品説明 -->
        <div class="product-detail__description">
            <h2 class="product-detail__description-title">商品説明</h2>
            <p class="product-detail__description-text">{{ $product->description }}</p>
        </div>

        <!-- 商品情報 -->
        <div class="product-detail__information">
            <h2 class="product-detail__information-title">商品の情報</h2>
            <ul class="product-detail__information-list">
                <li class="product-detail__information-item">
                    カテゴリ：
                    @foreach ($product->categories as $category)
                    <span class="category-tag">{{ $category->name }}</span>
                    @endforeach
                </li>
                <li class="product-detail__information-item">商品の状態：{{ $product->condition }}</li>
            </ul>
        </div>

        <!-- コメントセクション -->
        <div class="product-detail__comments">
            <h3 class="product-detail__comments-title">コメント ({{ $product->reviews->count() }})</h3>

            @if ($product->reviews->isNotEmpty())
            @foreach ($product->reviews as $review)
            <div class="comment">
                <img src="{{ $review->user->profile_image_path ?? asset('images/default-avatar.png') }}" alt="{{ $review->user->name }}" class="profile-image">
                <p><strong>{{ $review->user->name }}</strong>（{{ $review->created_at->format('Y-m-d H:i') }}）</p>
                <p>{{ $review->comment }}</p>
            </div>

            <p><strong>{{ $review->user->name }}</strong>（{{ $review->created_at->format('Y-m-d H:i') }}）</p>
            <p>{{ $review->comment }}</p>
        </div>
        @endforeach
        @else
        <p class="product-detail__no-comments">コメントはまだありません。</p>
        @endif

        <!-- コメント投稿フォーム -->
        <form method="POST" action="{{ route('reviews.store', $product->id) }}">
            @csrf
            <textarea name="comment" placeholder="コメントを入力してください" rows="3"></textarea>
            <button type="submit">コメントを送信する</button>
        </form>
    </div>

</div>
</div>
@endsection