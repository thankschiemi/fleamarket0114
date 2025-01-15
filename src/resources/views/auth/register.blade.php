@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="register">
    <h1 class="register__title">会員登録</h1>
    <form action="{{ route('register') }}" method="POST" class="register__form">
        @csrf
        <div class="register__form-group">
            <label for="name" class="register__label">ユーザー名</label>
            <input type="text" id="name" name="name" class="register__input" required>
        </div>
        <div class="register__form-group">
            <label for="email" class="register__label">メールアドレス</label>
            <input type="email" id="email" name="email" class="register__input" required>
        </div>
        <div class="register__form-group">
            <label for="password" class="register__label">パスワード</label>
            <input type="password" id="password" name="password" class="register__input" required>
        </div>
        <div class="register__form-group">
            <label for="password_confirmation" class="register__label">確認用パスワード</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="register__input" required>
        </div>
        <button type="submit" class="register__button">登録</button>
    </form>
    <p class="register__login-link">
        すでにアカウントをお持ちの方は <a href="{{ route('login') }}">こちら</a> からログイン
    </p>
</div>
@endsection