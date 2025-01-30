@extends('layouts.app_with_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/product-shipping-edit.css') }}">
@endsection

@section('content')
<div class="shipping">
    <h1 class="shipping__title">住所の変更</h1>
    <form action="{{ route('shipping.update', ['item_id' => $product->id]) }}" method="POST" class="shipping__form" novalidate>
        @csrf
        <div class="shipping__form-group">
            <label for="postal_code" class="shipping__label">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" class="shipping__input" value="{{ old('postal_code', $user->postal_code) }}" required>
            @error('postal_code')
            <div class="shipping__error-message">{{ $message }}</div>
            @enderror
        </div>
        <div class="shipping__form-group">
            <label for="address" class="shipping__label">住所</label>
            <input type="text" id="address" name="address" class="shipping__input" value="{{ old('address', $user->address) }}" required>
            @error('address')
            <div class="shipping__error-message">{{ $message }}</div>
            @enderror
        </div>
        <div class="shipping__form-group">
            <label for="building" class="shipping__label">建物名</label>
            <input type="text" id="building" name="building" class="shipping__input" value="{{ old('building', $user->building_name) }}">
        </div>
        <button type="submit" class="shipping__button">更新する</button>
    </form>

</div>
@endsection