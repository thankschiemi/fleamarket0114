<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
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
    </header>


    <main>
        @yield('content')
    </main>
    @yield('js')
</body>

</html>