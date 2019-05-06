@extends('layouts.default')

@section('content')

    <main class="main inner_page_main activities_inner_page_main">
        <section class="main_page_top_section">

            <div class="flexslider top_section_slider">
                <ul class="slides">
                    <li style="background-image:url(/img/slide2.jpg);">
                        <div class="inner_section">
                            <div class="top_section_div">
                                <p class="title">Наименование <br/>проекта</p>

                                <p>
                                    Регистрируйтесь и находите партнеров<br/>
                                    и тренеров по игре в бильярд
                                </p>
                            </div>
                        </div>
                    </li>

                    <li style="background-image:url(/img/slide2.jpg);">
                        <div class="inner_section">
                            <div class="top_section_div">
                                <p class="title">Наименование <br/>проекта</p>

                                <p>
                                    Регистрируйтесь и находите партнеров<br/>
                                    и тренеров по игре в бильярд
                                </p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            @if(Auth::guest())
            <div class="top_section_personal_block">
                <div class="the_tabs top_section_personal_block_tabs">
                    <div class="the_tabs_head clearfix">
                        <a href="">Зарегистрироваться</a>
                        <a class="active" href="">Войти на сайт</a>
                        <a href="" style="display: none">Забыли пароль</a>
                    </div>

                    <div class="the_tabs_content">
                        <div class="the_tabs_div">
                            <div class="top_section_personal_div">
                                <br/><br/>
                                <div class="the_form">
                                    @include('site.main._form._signup')

                                </div>
                            </div>
                        </div>

                        <div class="the_tabs_div active">
                            <div class="top_section_personal_div">
                                <div class="social">
                                    <p>Войти через соцсети:</p>
                                    <a href="{{route('fb.auth')}}" class="fb"></a>
                                    <a href="{{route('vk.auth')}}" class="vk"></a>
                                </div>

                                <div class="the_form">
                                    @include('site.main._form._signin')
                                </div>
                            </div>
                        </div>

                        <div class="the_tabs_div">
                            <div class="top_section_personal_div">
                                <br/><br/>
                                <div class="the_form">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </section>

        <section class="partners_section">
            <div class="inner_section clearfix">

                <h1>Найдите интересных партнеров для<br/>совместной игры!</h1>

                <p class="section_subtitle">Превратите Ваше хобби в веселое, увлекательное и необычное приключение!</p>

                <div class="icons_wrap">
                    <div class="icons_div">
                        <a href="#">
									<span>
										Пул
									</span>
                        </a>
                    </div>

                    <div class="icons_div">
                        <a href="#">
									<span>
										Русский биллиард
									</span>
                        </a>
                    </div>

                    <div class="icons_div">
                        <a href="#">
									<span>
										Снукер
									</span>
                        </a>
                    </div>
                </div>

                <a class="button search_partner_button" href="#">Найти партнера для игры</a>

            </div>
        </section>

        <section class="map_section">
            <div class="inner_section clearfix">

                <p class="section_title">Бильярдные клубы</p>

                <p class="section_subtitle">Расскажите, где играете Вы! Находите новые места для игры! Играйте в других
                    городах<br/>
                    в путешествиях по России! Ищите партнеров по игре в другом городе!</p>

            </div>

            <div class="map_block">
                <div class="map" id="map1"></div>
                <a href="/clubs/" class="button club_search_button">Найти клуб</a>
            </div>
        </section>

        <section class="opportunities_section">
            <div class="inner_section clearfix">

                <p class="section_title">Ваши возможности</p>

                <div class="icons_wrap">
                    <div class="icons_div">

                        <div class="text">
                            <p class="title">
                                Ищите единомышленников
                            </p>

                            <p>
                                Находите партнеров по игре,<br/>создавайте свои списки<br/>игроков
                            </p>
                        </div>
                    </div>

                    <div class="icons_div">

                        <div class="text">
                            <p class="title">
                                Чат
                            </p>

                            <p>
                                Общайтесь в чате
                            </p>
                        </div>
                    </div>

                    <div class="icons_div">

                        <div class="text">
                            <p class="title">
                                Ищите тренера
                            </p>

                            <p>
                                Находите тренера, который<br/>сделает из вас профессионала!
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <section class="description_section">
            <div class="inner_section clearfix">

                <p class="section_title">Описание проекта</p>

                <div class="description_text_col">
                    <p>
                        Первый бильярдный стол (согласно сохранившимся документам) был изготовлен мастером Анри де Винемом в
                        1469 году для короля Франции Людовика XI. Этот стол был похож на современный бильярдный: у него было
                        каменное основание, ограждение, он был покрыт сукном. Документально подтверждены утверждения об игре
                        в бильярд королевы Шотландии Марии Стюарт накануне её казни и о её просьбе архиепископу Глазго
                        подыскать столу подходящее помещение после её смерти.
                    </p>
                </div>

                <div class="description_text_col">
                    <p>
                        В Россию бильярд был завезен из Голландии Петром I. Новинка быстро завоевала популярность. После
                        смерти Петра I обучение игре на бильярде было включено Верховным тайным советом в курс наук Петра
                        II, его внука и наследника. Екатерина II указом от <br/>
                        7 декабря 1770 года приказала в трактирах и на постоялых дворах «для увеселения приходящих дозволить
                        иметь биллиарды». На рубеже XVIII и XIX веков бильярд являлся частью «обязательной» программы
                        воспитания дворян в Европе и в России. Так, например, у Пушкина в Михайловском был бильярдный стол.
                    </p>
                </div>

                <a href="#" class="button button_with_arrow learn_more_button">Подробнее</a>

            </div>
        </section>

        <section class="partners_reviews_section">
            <div class="inner_section clearfix">

                <p class="section_title">Ваши партнеры говорят</p>

                <div class="partners_wrap">
                    <?php
/*                    // Блок отзывов
                    $rates = Rating::find()
                    ->orderBy('id')
                    ->where(['rateable_type' => 'main', 'actived' => 0])
                    ->limit(4)
                    ->all();
                    foreach ($rates as $rate) { */?><!--
                    <div class="partners_div" style="">
                    <?php
/*                    $user = User::findOne($rate->rater_id);*/?>
                    <div class="img">
                    <img src="<?/*= $user->imagePath */?>" alt="">
                    <p class="name"><?/*= $user->username */?></p>
                    <p class="date"><?/*= $rate->dateFormatted */?></p>
                    </div>
                    <div class="text">
                    <p>
                    <?/*= $rate->comment */?>
                    </p>
                    </div>
                    </div>
                    --><?php
/*                    }
                    */?>
                </div>

            </div>
        </section>
    </main>

    @include('site.chunks.common.map_with_clubs', ['json_markers' => $json_markers])
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&libraries=places&callback=initMap"></script>

    
@endsection