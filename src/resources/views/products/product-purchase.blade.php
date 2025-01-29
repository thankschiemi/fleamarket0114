@extends('layouts.app_with_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/product-purchase.css') }}">
@endsection

@section('content')
<main class="purchase">
    <div class="purchase__container">
        <div class="purchase__left">
            <div class="purchase__product">
                <img src="{{ $product->image_path ?? asset('images/default-product.jpg') }}" alt="商品画像"
                    class="product-detail__image--main">
                <p class="purchase__product-name">{{ $product->name }}</p>
                <p class="purchase__product-price">¥{{ number_format($product->price) }}</p>
            </div>
            <div class="purchase__details">
                <div class="purchase__details-payment">
                    <label for="payment" class="purchase__details-label">支払い方法</label>
                    <select id="payment" name="payment_method" class="purchase__details-select">
                        <option value="">選択してください</option>
                        <option value="convenience_store">コンビニ払い</option>
                        <option value="credit_card">カード支払い</option>
                    </select>
                </div>
                <div class="purchase__details-address">
                    <div class="purchase__details-header">
                        <p class="purchase__details-label">配送先</p>
                        <a href="#" class="purchase__details-change">変更する</a>
                    </div>
                    <p class="purchase__details-text">〒{{ Auth::user()->postal_code }}<br>{{ Auth::user()->address }}</p>
                </div>

            </div>
        </div>

        <div class="purchase__right">
            <div class="purchase__summary">
                <div class="purchase__summary-price-item">
                    <span class="purchase__summary-label">商品代金</span>
                    <span class="purchase__summary-value">¥{{ number_format($product->price) }}</span>
                </div>
                <div class="purchase__summary-item">
                    <span class="purchase__summary-label">支払い方法</span>
                    <span class="purchase__summary-value" id="selected-payment">選択してください</span>
                </div>
            </div>



            <form method="POST" action="{{ route('purchase.complete', ['item_id' => $product->id]) }}" class="purchase__form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                <input type="hidden" name="price" value="{{ $product->price }}">
                <button type="submit" class="purchase__form-button">購入する</button>
            </form>
        </div>
    </div>
</main>
@endsection