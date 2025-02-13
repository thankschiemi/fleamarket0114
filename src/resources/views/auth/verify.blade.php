@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-email">
    <h1 class="verify-email__title">登録したメールアドレスを確認してください</h1>
    <p class="verify-email__description">
        認証メールが登録したメールアドレスに送信されました。<br>
        メール内のリンクをクリックして認証を完了してください。
    </p>
    <p class="verify-email__instruction">
        認証メールが届かない場合は、以下のボタンで再送信できます。
    </p>

    @if (session('status') == 'verification-link-sent')
    <p class="verify-email__message verify-email__message--success">
        認証メールを再送信しました。メールボックスをご確認ください。
    </p>
    @endif

    <form class="verify-email__form" method="POST" action="{{ route('verification.send') }}">
        @csrf
        <a href="{{ route('verification.send') }}" class="verify-email__link">認証メールを再送する</a>

    </form>
</div>
@endsection