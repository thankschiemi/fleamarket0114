<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/app_with_search.css') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('storage/images/coachtech_logo.svg') }}" alt="COACHTECH Logo" class="header__logo-img">
            </a>
        </div>
        <nav class="header__nav">
            <form action="{{ route('products.index') }}" method="GET" class="header__search-form">
                <input type="hidden" name="tab" value="{{ request('tab', 'recommend') }}"> <!-- 🔥 これを追加！ -->
                <input type="text" name="query" class="header__search-input"
                    placeholder="なにをお探しですか？"
                    value="{{ request('query') }}">
            </form>

            <ul class="header__menu">

                <li class="header__menu-item">
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="header__menu-link-button">ログアウト</button>
                    </form>
                </li>

                <li class="header__menu-item"><a href="{{ route('mypage') }}" class="header__menu-link">マイページ</a></li>
                <li class="header__menu-item">
                    <a href="{{ route('products.create') }}" class="header__button">出品</a>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>
    @yield('js')

</body>