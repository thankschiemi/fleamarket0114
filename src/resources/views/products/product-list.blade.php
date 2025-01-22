@extends('layouts.app_with_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/product-list.css') }}">
@endsection

@section('content')
<div class="container">
    <!-- タブ切り替え -->
    <div class="tabs-container">
        <div class="tabs">
            <a href="{{ route('products.index', ['tab' => 'recommend']) }}"
                class="tabs__link {{ $tab === 'recommend' ? 'tabs__link--active' : '' }}">おすすめ</a>
            <a href="{{ route('products.index', ['tab' => 'mylist']) }}"
                class="tabs__link {{ $tab === 'mylist' ? 'tabs__link--active' : '' }}">マイリスト</a>
        </div>
    </div>


    <!-- 商品リスト -->
    <div class="product-list">
        @forelse ($products as $product)
        <div class="product-list__item">
            <img src="{{ $product->image_path }}" alt="商品画像" class="product-list__image">
            <p class="product-list__name">{{ $product->name }}</p>
        </div>
        @empty
        <p class="product-list__empty">商品がありません。</p>
        @endforelse
    </div>
</div>

@endsection