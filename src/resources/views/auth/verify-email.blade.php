@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')

<body>
    <h1>メール認証を完了してください</h1>
    <p>登録したメールアドレスに送信されたリンクをクリックして認証を完了してください。</p>
    <p>認証メールが届かない場合は、以下のボタンを押して再送してください。</p>
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">認証メールを再送する</button>
    </form>
</body>
@endsection