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
                <img src="{{ asset('images/coachtech_logo.svg') }}" alt="COACHTECH Logo" class="header__logo-img">
            </a>
        </div>
        <nav class="header__nav">
            <form action="{{ route('search') }}" method="GET" class="header__search-form">
                <input type="text" name="query" class="header__search-input" placeholder="何をお探しですか？">
                <button type="submit" class="header__search-button">検索</button>
            </form>
            <ul class="header__menu">
                <li class="header__menu-item"><a href="{{ route('logout') }}" class="header__menu-link">ログアウト</a></li>
                <li class="header__menu-item"><a href="{{ route('mypage') }}" class="header__menu-link">マイページ</a></li>
                <li class="header__menu-item"><a href="{{ route('post.create') }}" class="header__menu-link">出品</a></li>
            </ul>
        </nav>
    </header>
    <main>
        @yield('content')
    </main>
</body>

</html>