@extends('layouts.app_with_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/product-purchase.css') }}">
@endsection

@section('content')
<main class="purchase">
    <h1 class="purchase__title">商品購入画面</h1>
    <div class="purchase__container">
        <div class="purchase__product">
            <img src="/images/product.png" alt="商品画像" class="purchase__image">
            <p class="purchase__name">商品名</p>
            <p class="purchase__price">¥47,000</p>
        </div>
        <div class="purchase__details">
            <div class="purchase__payment">
                <label for="payment" class="purchase__label">支払い方法</label>
                <select id="payment" class="purchase__select">
                    <option value="">選択してください</option>
                </select>
            </div>
            <div class="purchase__address">
                <p class="purchase__label">配送先</p>
                <p class="purchase__address-text">〒XXX-YYYY<br>ここには住所と建物が入ります</p>
                <a href="#" class="purchase__change">変更する</a>
            </div>
            <div class="purchase-summary">
                <p class="purchase-summary__item">商品代金: ¥47,000</p>
                <p class="purchase-summary__item">支払い方法: コンビニ払い</p>
            </div>

            <button class="purchase__button">購入する</button>
        </div>
    </div>
</main>
@endsection