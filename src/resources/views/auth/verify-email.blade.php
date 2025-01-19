@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-email">
    <h1 class="verify-email__title">メール認証を完了してください</h1>
    <p class="verify-email__description">
        登録したメールアドレスに送信されたリンクをクリックして認証を完了してください。
    </p>
    <p class="verify-email__instruction">
        認証メールが届かない場合は、以下のボタンを押して再送してください。
    </p>

    {{-- メール再送信後の成功メッセージ --}}
    @if (session('status') == 'verification-link-sent')
    <p class="verify-email__message verify-email__message--success">
        認証メールを再送信しました。メールボックスをご確認ください。
    </p>
    @endif

    <form class="verify-email__form" method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button class="verify-email__button" type="submit">認証メールを再送する</button>
    </form>
</div>
@endsection