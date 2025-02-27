@extends('layouts.app_with_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
@endsection

@section('content')
<div class="product-detail">
    <div class="product-detail__image">
        <img src="{{ $product->image_path ?? asset('images/default-product.jpg') }}" alt="商品画像"
            class="product-detail__image--main">
    </div>
    <div class="product-detail__info">
        <h1 class="product-detail__title">{{ $product->name }}</h1>
        <p class="product-detail__brand">ブランド: {{ $product->brand ?? '未設定' }}</p>
        <p class="product-detail__price">¥{{ number_format($product->price) }}（税込）</p>

        <div class="product-detail__actions">
            <div class="product-detail__icon-group">
                <div class="product-detail__icon">
                    <form method="POST" action="{{ route('favorites.toggle', $product->id) }}">
                        @csrf
                        <div class="product-detail__icon-star {{ Auth::check() && Auth::user()->favoritedByProducts()->where('product_id', $product->id)->exists() ? 'product-detail__icon-star--active' : '' }}">
                            <button type="submit">
                                {{ Auth::check() && Auth::user()->favoritedByProducts()->where('product_id', $product->id)->exists() ? '★' : '☆' }}
                            </button>
                        </div>

                    </form>
                    <span class="product-detail__icon-count">{{ $product->favoritedByUsers->count() }}</span>
                </div>
                <div class="product-detail__icon">
                    <span class="product-detail__icon-comment">💬</span>
                    <span class="product-detail__icon-count">{{ $product->reviews->count() }}</span>
                </div>
            </div>
            <a href="{{ url('/purchase/' . $product->id) }}" class="product-detail__buy-button">購入手続きへ</a>
        </div>

        <div class="product-detail__description">
            <h2 class="product-detail__description-title">商品説明</h2>
            <p class="product-detail__description-text">{{ $product->description }}</p>
        </div>

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

        <div class="product-detail__comments">
            <p class="product-detail__comments-title">コメント ({{ $product->reviews->count() }})</p>

            @if ($product->reviews->isNotEmpty())
            @foreach ($product->reviews as $review)
            <div class="product-detail__comment">
                <div class="product-detail__comment-header">
                    <img src="{{ $review->user->profile_image_path ?? asset('images/default-avatar.jpg') }}"
                        alt="{{ $review->user->name }}"
                        class="product-detail__comment-image">
                    <p class="product-detail__comment-username"><strong>{{ $review->user->name }}</strong></p>
                </div>
                <p class="product-detail__comment-text">{{ $review->comment }}</p>
            </div>
            @endforeach
            @else
            <p class="product-detail__no-comments">コメントはまだありません。</p>
            @endif

            <form method="POST" action="{{ route('reviews.store', $product->id) }}" class="product-detail__comment-form" novalidate>
                @csrf
                <textarea name="comment" placeholder="コメントを入力してください" rows="3" class="product-detail__comment-input"></textarea>
                @error('comment')
                <div class="error-message">{{ $message }}</div>
                @enderror
                <button type="submit" class="product-detail__comment-submit">コメントを送信する</button>
            </form>
        </div>
    </div>
</div>
@endsection