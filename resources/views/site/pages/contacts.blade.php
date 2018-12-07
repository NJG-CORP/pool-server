@extends('layouts.default')

@section('title', 'Контакты')

@section('content')

    <main class="main contacts_page_main">
        <section class="the_content_section">
            <div class="inner_section">
                <div class="breadcrumbs">
                    <p><a href="/">Главная</a> / <span>Контакты</span></p>
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

@endsection