@extends('layouts.app_with_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/product-exhibit.css') }}">
@endsection

@section('content')
<div class="product-exhibit">
    <h1 class="product-exhibit__title">商品の出品</h1>
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="product-exhibit__form" novalidate>
        @csrf
        <div class="product-exhibit__image-upload">
            <span class="product-exhibit__image-label-title">商品画像</span>
            <label for="product-image" class="product-exhibit__image-button">
                画像を選択する
                <input type="file" name="image" id="product-image" class="product-exhibit__image-input" accept="image/*">
            </label>
        </div>
        @error('image')
        <div class="error-message">{{ $message }}</div>
        @enderror
        <div class="product-exhibit__details">
            <h2 class="product-exhibit__details-title">商品の詳細</h2>

            <div class="product-exhibit__category">
                <h3 class="product-exhibit__category-title">カテゴリー</h3>
                <div class="product-exhibit__category-list">
                    @foreach($categories as $category)
                    <input type="checkbox" name="category[]" value="{{ $category->id }}" id="category-{{ $category->id }}" class="product-exhibit__category-input">
                    <label for="category-{{ $category->id }}" class="product-exhibit__category-item">{{ $category->name }}</label>
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
                @error('condition')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <h2 class="product-exhibit__sub-title">商品名と説明</h2>
            <div class="product-exhibit__form-group">
                <label class="product-exhibit__form-label" for="product-name">商品名</label>
                <input type="text" name="name" id="product-name" class="product-exhibit__form-input" required>
                @error('name')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="product-exhibit__form-group">
                <label class="product-exhibit__form-label" for="product-description">商品の説明</label>
                <textarea name="description" id="product-description" class="product-exhibit__form-textarea" rows="4" required></textarea>
                @error('description')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="product-exhibit__form-group">
                <label class="product-exhibit__form-label" for="product-price">販売価格</label>
                <div class="product-exhibit__price-container">
                    <span class="product-exhibit__price-symbol">¥</span>
                    <input type="number" name="price" id="product-price" class="product-exhibit__form-input" required min="1">
                </div>
                @error('price')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="product-exhibit__submit">出品する</button>
        </div>
    </form>
</div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const conditionSelect = document.getElementById("product-condition");

        function updateSelectedCondition() {
            Array.from(conditionSelect.options).forEach(option => {
                option.textContent = option.textContent.replace("✔ ", "");
            });

            const selectedOption = conditionSelect.options[conditionSelect.selectedIndex];
            selectedOption.textContent = `✔ ${selectedOption.textContent}`;
        }

        conditionSelect.addEventListener("focus", function() {
            updateSelectedCondition();
        });

        conditionSelect.addEventListener("change", function() {
            updateSelectedCondition();
        });

        conditionSelect.addEventListener("blur", function() {
            updateSelectedCondition();
        });
    });
</script>
@endsection