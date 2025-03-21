@extends('layouts.app_with_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/product-list.css') }}">
@endsection

@section('content')
<div class="container">

    {{-- タブ切り替え --}}
    <div class="tabs-container">
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif

        <div class="tabs">
            <a href="{{ route('products.index', ['tab' => 'recommend', 'query' => request('query')]) }}"
                class="tabs__link {{ $tab === 'recommend' ? 'tabs__link--active' : '' }}">おすすめ</a>
            <a href="{{ route('products.index', ['tab' => 'mylist', 'query' => request('query')]) }}"
                class="tabs__link {{ $tab === 'mylist' ? 'tabs__link--active' : '' }}">マイリスト</a>
        </div>
    </div>

    {{-- 商品一覧 --}}
    <div class="product-list">
        @forelse ($products as $product)

        {{-- 自分の出品商品は非表示 --}}
        @if (Auth::check() && $product->user_id === Auth::id())
        @continue
        @endif

        <a href="{{ route('product.show', ['item_id' => $product->id]) }}" class="product-list__link">
            <div class="product-list__item">
                {{-- 商品画像 --}}
                <img src="{{ $product->image_path }}" alt="商品画像" class="product-list__image">

                {{-- "Sold" ラベルを表示 --}}
                @if ($product->status === 'sold')
                <span class="product-list__sold">Sold</span>
                @endif

                {{-- 商品名 --}}
                <p class="product-list__name">{{ $product->name }}</p>
            </div>
        </a>
        @empty
        <p class="product-list__empty">商品がありません。</p>
        @endforelse
    </div>

</div>
@endsection