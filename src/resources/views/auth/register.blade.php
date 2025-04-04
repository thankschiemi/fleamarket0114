@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="register">
    <h1 class="register__title">会員登録</h1>
    <form action="{{ route('register') }}" method="POST" class="register__form" novalidate>
        @csrf

        <div class="register__form-group">
            <label for="name" class="register__label">ユーザー名</label>
            <input type="text" id="name" name="name" class="register__input" value="{{ old('name') }}" required>
            @error('name')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="register__form-group">
            <label for="email" class="register__label">メールアドレス</label>
            <input type="email" id="email" name="email" class="register__input" value="{{ old('email') }}" required>
            @error('email')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="register__form-group">
            <label for="password" class="register__label">パスワード</label>
            <input type="password" id="password" name="password" class="register__input" required>
            @error('password')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="register__form-group">
            <label for="password_confirmation" class="register__label">確認用パスワード</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="register__input" required>
            @error('password_confirmation')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="register__button">登録する</button>
    </form>
    <p class="register__login-link">
        <a href="{{ route('login') }}">ログインはこちら</a>
    </p>
</div>
@endsection