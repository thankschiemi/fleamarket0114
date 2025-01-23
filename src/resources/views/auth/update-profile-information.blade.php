@extends('layouts.app_with_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/update-profile-information.css') }}">
@endsection

@section('content')
<div class="profile">
    <h1 class="profile__title">プロフィール設定</h1>
    <form action="{{ route('mypage.update') }}" method="POST" enctype="multipart/form-data" class="profile__form" novalidate>
        @csrf
        @method('PUT')
        <div class="profile__image-group">
            <div class="profile__image-wrapper">

                <img src="{{ $user->profile_image_path ? asset('storage/' . $user->profile_image_path) : asset('images/default-avatar.jpg') }}"
                    alt="プロフィール画像"
                    class="profile__image">

            </div>
            <input type="file" id="image" name="image" class="profile__image-input">
            <label for="image" class="profile__image-label">画像を選択する</label>
        </div>

        <div class="profile__form-group">
            <label for="username" class="profile__label">ユーザー名</label>
            <input type="text" id="username" name="username" class="profile__input" required value="{{ old('username') }}">
            @error('username')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        <div class="profile__form-group">
            <label for="postal_code" class="profile__label">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" class="profile__input" required value="{{ old('postal_code') }}">
            @error('postal_code')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        <div class="profile__form-group">
            <label for="address" class="profile__label">住所</label>
            <input type="text" id="address" name="address" class="profile__input" required value="{{ old('address') }}">
            @error('address')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        <div class="profile__form-group">
            <label for="building" class="profile__label">建物名</label>
            <input type="text" id="building" name="building" class="profile__input" value="{{ old('building') }}">
            @error('building')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="profile__button">更新する</button>
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

    </form>
</div>
@endsection