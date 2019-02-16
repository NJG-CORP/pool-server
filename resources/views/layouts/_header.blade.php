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
                                <span class="selectBox-label">{{ $user->name }}</span>
                                <span class="selectBox-arrow"></span>
                            </a>

                            <ul class="selectBox-dropdown-menu selectBox-options selectBox-options-bottom">
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