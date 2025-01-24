@extends('layouts.app_with_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
@endsection

@section('content')
<div class="product-detail">
    <!-- 商品画像 -->
    <div class="product-detail__image">
        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="product-detail__image--main">
    </div>

    <!-- 商品情報 -->
    <div class="product-detail__info">
        <h1 class="product-detail__title">{{ $product->name }}</h1>
        <p class="product-detail__brand">ブランド名</p>
        <p class="product-detail__price">¥{{ number_format($product->price) }}（税込）</p>

        <div class="product-detail__actions">
            <div class="product-detail__icons">
                <div class="product-detail__icon">
                    <span class="product-detail__icon-star">★</span>
                    <span class="product-detail__icon-count">3</span>
                </div>
                <div class="product-detail__icon">
                    <span class="product-detail__icon-comment">💬</span>
                    <span class="product-detail__icon-count">1</span>
                </div>
            </div>


            <!-- ボタン -->
            <button class="product-detail__buy-button">購入手続きへ</button>

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
                <h2 class="product-detail__comments-title">レビュー</h2>
                @if ($product->reviews && $product->reviews->isNotEmpty())
                @foreach ($product->reviews as $review)
                <div class="product-detail__comment">
                    <p class="product-detail__comment-username">{{ $review->user->name }}</p>
                    <p class="product-detail__comment-text">{{ $review->content }}</p>
                </div>
                @endforeach
                @else
                <p class="product-detail__no-comments">レビューはまだありません。</p>
                @endif

                @auth
                <form action="{{ route('reviews.store', ['product_id' => $product->id]) }}" method="POST" class="product-detail__comment-form">
                    @csrf
                    <textarea name="review" class="product-detail__comment-input" placeholder="商品のレビューを入力"></textarea>
                    <button type="submit" class="product-detail__comment-submit">レビューを送信する</button>
                </form>
                @else
                <p class="product-detail__login-reminder">レビューを投稿するには<a href="{{ route('login') }}">ログイン</a>してください。</p>
                @endauth
            </div>

        </div>
    </div>

    @endsection