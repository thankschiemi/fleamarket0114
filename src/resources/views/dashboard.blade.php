@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')
<div class="dashboard">
    <h1 class="dashboard__title">会員登録ありがとうございます。</h1>
    <p class="dashboard__welcome-message">ここでは、登録後の機能を確認することができます。</p>

</div>
@endsection