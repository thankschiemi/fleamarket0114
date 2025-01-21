@extends('layouts.app_with_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/product-list.css') }}">
@endsection

@section('content')
<div class="container">
    <!-- タブ切り替え -->
    <div class="tabs">
        <a href="{{ route('products.index', ['tab' => 'recommend']) }}" class="{{ $tab === 'recommend' ? 'active' : '' }}">おすすめ</a>
        <a href="{{ route('products.index', ['tab' => 'mylist']) }}" class="{{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>

    <!-- 商品リスト -->
    <div class="product-list">
        @forelse ($products as $product)
        <div class="product-item">
            <img src="{{ $product->image_path }}" alt="商品画像" class="product-item__image">
            <p class="product-item__name">{{ $product->name }}</p>
        </div>
        @empty
        <p>商品がありません。</p>
        @endforelse
    </div>
</div>
@endsection