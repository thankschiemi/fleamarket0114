@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-email">
    <h1 class="verify-email__title">登録していただいたメールアドレスに認証メールを送付しました。<br> メール認証を完了してください。</h1>

    <a href="#" id="verify-btn" class="verify-email__button">認証はこちらから</a>

    <p id="confirmation-message" class="verify-email__message hidden">
        認証メールを開きましたか？以下のボタンをクリックして確認をお願いします。
    </p>

    {{-- メール再送信フォーム --}}
    <form class="verify-email__form" method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="verify-email__link">認証メールを再送する</button>
    </form>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const verifyBtn = document.getElementById('verify-btn');
        const confirmationMessage = document.getElementById('confirmation-message');

        if (verifyBtn) {
            verifyBtn.addEventListener('click', function(event) {
                event.preventDefault(); // ページ遷移を防ぐ
                confirmationMessage.classList.remove('hidden');
            });
        }
    });
</script>
@endsection