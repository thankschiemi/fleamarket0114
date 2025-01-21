@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="login">
    <h1 class="login__title">ログイン</h1>
    <form action="{{ route('login') }}" method="POST" class="login__form" novalidate>
        @csrf
        <div class="login__form-group">
            <label for="email" class="login__label">ユーザー名/メールアドレス</label>
            <input type="text" id="email" name="email" class="login__input" required>
            @error('email')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        <div class="login__form-group">
            <label for="password" class="login__label">パスワード</label>
            <input type="password" id="password" name="password" class="login__input" required>
            @error('password')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="login__button">ログインする</button>
    </form>
    <p class="login__register-link">
        <a href="{{ route('register') }}">会員登録はこちら</a>
    </p>
</div>
@endsection