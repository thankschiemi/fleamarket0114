@extends('layouts.app_with_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/update-profile-information.css') }}">
@endsection

@section('content')
<div class="profile">
    <h1 class="profile__title">プロフィール設定</h1>
    <form action="{{ route('mypage.profile.update') }}" method="POST" enctype="multipart/form-data" class="profile__form" novalidate>
        @csrf
        @method('PUT')

        <div class="profile__image-group">
            <div class="profile__image-wrapper">
                <img id="profile-preview"
                    src="{{ $user->profile_image_path ? asset($user->profile_image_path) : asset('images/default-avatar.jpg') }}"
                    alt="プロフィール画像" class="profile__image">
            </div>
            <input type="file" id="image" name="image" class="profile__image-input" onchange="previewImage(event)">
            <label for="image" class="profile__image-label">画像を選択する</label>
        </div>
        @error('image')
        <div class="error-message">{{ $message }}</div>
        @enderror


        <div class="profile__form-group">
            <label for="username" class="profile__label">ユーザー名</label>
            <input type="text" id="username" name="username" class="profile__input" required
                value="{{ old('username', $user->is_first_login ? '' : $user->name) }}">
            @error('username')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="profile__form-group">
            <label for="postal_code" class="profile__label">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" class="profile__input" required
                value="{{ old('postal_code', $user->is_first_login ? '' : $user->postal_code) }}">
            @error('postal_code')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="profile__form-group">
            <label for="address" class="profile__label">住所</label>
            <input type="text" id="address" name="address" class="profile__input" required
                value="{{ old('address', $user->is_first_login ? '' : $user->address) }}">
            @error('address')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="profile__form-group">
            <label for="building" class="profile__label">建物名</label>
            <input type="text" id="building" name="building" class="profile__input"
                value="{{ old('building', $user->is_first_login ? '' : $user->building_name) }}">
            @error('building')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="profile__button">更新する</button>
    </form>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('profile-preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection