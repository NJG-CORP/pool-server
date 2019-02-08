<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:type" content="article">
    <meta property="og:title" content="">
    <meta property="og:description" content="">
    <meta property="og:url" content="">
    <meta property="og:site_name" content="">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:description" content="">
    <meta name="twitter:title" content="">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/jquery.kladr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ion.rangeSlider.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ion.rangeSlider.skinFlat.css') }}">
    <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta charset=utf-8">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
</head>
<body>
<header>
    <div class="inner_section inner_header clearfix">

        <div class="logo">
            <a href="{{ route('home') }}"><img src="/img/interface/logo.png" alt=""></a>
        </div>

        <div class="header_menu_wrap">
            <nav class="header_menu">
                <ul>
                    <li><a href="{{ route('clubs') }}">Клубы</a></li>
                    <li><a href="{{ route('events') }}">Мероприятия</a></li>
                    <li><a href="{{ route('news') }}">Новости</a></li>
                    <li><a href="{{ route('blog') }}">Блог</a></li>
                    <li><a href="{{ route('contacts') }}">Контакты</a></li>
                </ul>
            </nav>
        </div>
        @if($user)
            <div class="header_personal">
                <div class="header_personal_inner clearfix">
                    <ul>
                        <li>
                            <a href="">
                                <img src="{{$user->getAvatarUrl() }}" alt="">
                                <span>{{ $user->name }}</span>
                            </a>

                            <ul class="">
                                <li>
                                    <a href="{{ route('profile.index') }}">
                                        <span>Личный кабинет</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('profile.card') }}">
                                        <span>Моя карточка</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('profile.invites') }}">
                                        <span>Мои приглашения</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('profile.partners') }}">
                                        <span>Мои партнеры</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('profile.chat') }}">
                                        <span>Чат</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('logout') }}">
                                        <span>Выход</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        @endif
    </div>
</header>
@yield('content')
<footer>
    <div class="inner_section inner_footer clearfix">

        <div class="footer_left">
            <div class="logo">
                <img src="/img/interface/logo.png" alt="">
            </div>

            <p>
                © <?= date('Y') ?> «PoolBuddy»<br><br>
                Оперативная информация о событиях бильярда<br>в России. Найти партнера для игры в боулинг.<br>Боулинг
                для команды.
            </p>
        </div>

        <div class="footer_right">
            <nav class="footer_menu">
                <ul>
                    <li><a>Рекламные возможности</a>
                        <ul id="w1" class="nav">
                            <li><a href="#">Рекламодателям</a></li>
                            <li><a href="#">Бильярдным клубам3</a></li>
                        </ul>
                    </li>

                    <li><a>Партнерам</a>
                        <ul id="w2" class="nav">
                            <li><a href="#">Вход для партнеров</a></li>
                            <li><a href="#">Рекламодателям</a></li>
                            <li><a href="#">Бильярдным клубам</a></li>
                            <li><a href="#">Рекламные возможности</a></li>
                            <li><a href="#">Контакты</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Партнерам</a>

                        <ul>
                            <li><a href="#">Игроки</a></li>
                            <li><a href="#">Тренеры</a></li>
                            <li><a href="#">Клубы</a></li>
                            <li><a href="#">Мероприятия</a></li>
                            <li><a href="#">Новости</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>

        </div>

    </div>

    <div class="footer_bottom">
        <div class="inner_section clearfix">
            <p class="policy">
                <a href="#">Пользовательское Соглашение</a> и <a href="#">Политику конфиденциальности.</a>
            </p>
            <p class="made_by">
                Дизайн сайта - <a href="#">Михаил Соловьев</a>
            </p>
        </div>
    </div>
</footer>
@stack('modals')
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="{{ asset('js/jquery.kladr.min.js') }}"></script>
<script src="{{ asset('js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/form.js') }}"></script>
<script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
<script>

</script>
@stack('scripts')
</body>
</html>
