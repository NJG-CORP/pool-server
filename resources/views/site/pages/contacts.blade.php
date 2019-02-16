@extends('layouts.default')

@section('title', 'Контакты')

@section('content')

    <main class="main contacts_page_main">
        <section class="the_content_section">
            <div class="inner_section">
                <div class="breadcrumbs inner_section">
                    <p itemscope itemtype="http://schema.org/BreadcrumbList">
								<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
									<a href="#" itemprop="item"><span itemprop="name">Главная</span></a>
									<meta itemprop="position" content="1">
								</span>
                        <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
									<span itemprop="item"><span itemprop="name">Контакты</span></span>
									<meta itemprop="position" content="2">
								</span>
                    </p>
                </div>
                <h1>Контакты</h1>
                @if(session('success'))
                    <div class="alert alert-success">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                <div class="contacts_page_wrap clearfix">
                    <div class="contacts_page_div">
                        <p>
                            Телефоны службы<br>поддержки клиентов
                        </p>
                        <p class="phone_p">
                            <a href="tel:+7 911 032 23 12">+7 911 032 23 12</a>
                        </p>
                    </div>
                    <div class="contacts_page_div">
                        <p>
                            <br>Обратная связь
                        </p>
                        <p class="mail_p">
                            <a href="mailto:info@poolbuddy.com">info@poolbuddy.com</a>
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <section class="pre_footer_form_section">
            <div class="inner_section">
                <div class="the_form write_review_form">

                    <form action="{{ route('send.review') }}" method="POST" class="frm3" id="frm33"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <p class="form_title">Остались вопросы?</p>
                        <div class="the_form_col the_form_col_fourth">
                            <div class="the_form_div">
                                <input type="text" name="location" placeholder="Страна, город">
                            </div>
                        </div>
                        <div class="the_form_col the_form_col_fourth">
                            <div class="the_form_div">
                                <input type="text" name="email" placeholder="E-mail">
                            </div>
                        </div>
                        <div class="the_form_col the_form_col_fourth">
                            <div class="the_form_div">
                                <input type="text" name="phone" placeholder="Телефон">
                            </div>
                        </div>
                        <div class="the_form_col the_form_col_fourth">
                            <div class="the_form_div the_form_div_submit">
                                <input type="submit" value="Отправить отзыв">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <section class="map_section padd0">
            <div class="map" id="map1">

            </div>
        </section>
    </main>

    <script>
        if ($('#map1').length) {
            var script0 = document.createElement('script');
            script0.onload = function () {
            };
            script0.text = " function initMap() {" +
                "var map = new google.maps.Map(document.getElementById('map1'), {" +
                " zoom: 17," +
                " center: {lat: 45.040458, lng: 38.981979}" +
                "});" +
                "var image = 'img/interface/balloon.png';" +
                "var beachMarker = new google.maps.Marker({" +
                " position: {lat: 45.040458, lng: 38.981979}," +
                "map: map," +
                "icon: image" +
                "});" +
                "}";
            document.getElementById('map1').appendChild(script0);
            setTimeout(function () {
                var script = document.createElement('script');
                script.onload = function () {
                };
                script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyAMj0tzo5dL6q5svRGhyCEYhMwqRcAtve4&callback=initMap";
                script.setAttribute('defer', '');
                script.setAttribute('async', '');
                document.getElementById('map1').appendChild(script);
            }, 999);
        }
    </script>
@endsection