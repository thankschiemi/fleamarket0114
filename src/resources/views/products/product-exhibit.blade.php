@extends('layouts.app_with_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/product-exhibit.css') }}">
@endsection

@section('content')
<div class="product-exhibit">
    <h1 class="product-exhibit__title">商品の出品</h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="product-exhibit__form">
        @csrf

        <div class="product-exhibit__image-upload">
            <label for="product-image" class="product-exhibit__image-label">
                <img id="preview-image" src="{{ asset('images/default-product.jpg') }}" alt="商品画像" class="product-exhibit__image-preview">
                <span class="product-exhibit__image-text">画像を選択する</span>
                <input type="file" name="image" id="product-image" class="product-exhibit__image-input" accept="image/*">
            </label>
        </div>


        <div class="product-exhibit__details">
            <h2 class="product-exhibit__details-title">商品の詳細</h2>

            <div class="product-exhibit__category">
                <h3 class="product-exhibit__category-title">カテゴリー</h3>
                <div class="product-exhibit__category-list">
                    @foreach($categories as $category)
                    <label class="product-exhibit__category-item">
                        <input type="radio" name="category" value="{{ $category->id }}"> {{ $category->name }}
                    </label>
                    @endforeach

                </div>
            </div>

            <div class="product-exhibit__form-group">
                <label class="product-exhibit__form-label" for="product-condition">商品の状態</label>
                <select name="condition" id="product-condition" class="product-exhibit__form-select">
                    <option value="">選択してください</option>
                    <option value="new">新品・未使用</option>
                    <option value="like_new">未使用に近い</option>
                    <option value="used">やや傷や汚れあり</option>
                    <option value="damaged">傷や汚れあり</option>
                </select>
            </div>

            <div class="product-exhibit__form-group">
                <label class="product-exhibit__form-label" for="product-name">商品名</label>
                <input type="text" name="name" id="product-name" class="product-exhibit__form-input" required>
            </div>

            <div class="product-exhibit__form-group">
                <label class="product-exhibit__form-label" for="product-description">商品の説明</label>
                <textarea name="description" id="product-description" class="product-exhibit__form-textarea" rows="4" required></textarea>
            </div>

            <div class="product-exhibit__form-group">
                <label class="product-exhibit__form-label" for="product-price">販売価格</label>
                <input type="number" name="price" id="product-price" class="product-exhibit__form-input" required min="1">
            </div>

            <button type="submit" class="product-exhibit__submit">出品する</button>
        </div>
    </form>
</div>
@endsection