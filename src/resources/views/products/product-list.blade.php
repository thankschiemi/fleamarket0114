@extends('layouts.app_with_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/product-list.css') }}">
@endsection

@section('content')
<div class="container">

    <div class="tabs-container">
        <div class="tabs">
            <a href="{{ route('products.index', ['tab' => 'recommend']) }}"
                class="tabs__link {{ $tab === 'recommend' ? 'tabs__link--active' : '' }}">おすすめ</a>
            <a href="{{ route('products.index', ['tab' => 'mylist']) }}"
                class="tabs__link {{ $tab === 'mylist' ? 'tabs__link--active' : '' }}">マイリスト</a>
        </div>
    </div>
    <div class="product-list">
        @forelse ($products as $product)
        <a href="{{ route('product.show', ['item_id' => $product->id]) }}" class="product-list__link">
            <div class="product-list__item">
                <img src="{{ $product->image_path }}" alt="商品画像" class="product-list__image">
                @if ($product->is_sold)
                <span class="product-list__sold">Sold</span>
                @endif
                <p class="product-list__name">{{ $product->name }}</p>
            </div>
        </a>
        @empty
        <p class="product-list__empty">商品がありません。</p>
        @endforelse
    </div>

</div>
@endsection