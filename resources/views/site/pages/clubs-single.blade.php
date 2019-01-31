@extends('layouts.default')

@section('title', $club->getHeader())

@section('content')

    <main class="main inner_page_main club_card_inner_page_main">
        <section class="the_content_section">
            <div class="inner_section">
                <div class="breadcrumbs">
                    <p>
                        <a href="{{ route('home') }}">Главная</a> /
                        <a href="{{ route('clubs') }}">Клубы</a>
                        /
                        <span>{{ $club->title }}</span>
                    </p>
                </div>
                <h1>{{ $club->name }}</h1>
                <div class="the_card_wrap clearfix">
                    <div class="the_card_img">
                        <a href="/img/club-22.jpg" class="fancy"><img src="/img/club-22.jpg" alt=""></a>
                    </div>
                    <div class="the_card_description">
                        <p class="name">Подробное описание клуба</p>
                        <div class="stars stars_small">
                            <p>Рейтинг клуба</p>
                            <span class="star{{ $club->calculated_rating ?? '0' }}"></span>
                        </div>
                        <p>{{ $club->description }}</p>
                        <div class="the_card_description_bottom">
                            <div class="the_card_description_bottom_div time">
                                <p>
                                    <b>Время работы:</b><br>
                                    Working Time </p>
                            </div>
                            <div class="the_card_description_bottom_div kitchen">
                                <p>
                                    <b>Кухня</b><br/>
                                    European </p>
                            </div>
                            <div class="the_card_description_bottom_div tables">
                                <p>
                                    <b>Число столов</b><br/>
                                    Пул <span>1</span>,<br>Русский бильярд <span>1</span>,<br>Снукер <span>1</span>,<br>Карамболь
                                    <span>1</span></p>
                            </div>
                        </div>
                        <!--
    <a class="button book_table_button fancy" href="#booking_popup">Забронировать стол</a>
-->
                    </div>
                </div>
                <div id="UpdateClubForm" data-pjax-container="" data-pjax-timeout="1000">
                    <div class="the_form write_review_form three_col_form bg_eeeff3">
                        <form id="club-review-form" class="frm3" action="/rating/add-club-review" method="post"
                              data-pjax>
                            <input type="hidden" name="_csrf-frontend"
                                   value="i9GkvJv82eMyZvxnrUjlzrR0gVANkFhAqm5fd7tjQYjfttOIyqif13gOlQLYI52Ngx62BEejCQ6HNxofjzwT2Q==">
                            <p class="form_title">Написать отзыв о клубе</p>
                            <div class="form-group field-clubreviewform-clubid required">
                                <input type="hidden" id="clubreviewform-clubid" class="form-control"
                                       name="ClubReviewForm[clubId]" value="1">
                                <div class="help-block"></div>
                            </div>
                            <div class="form-group field-clubreviewform-ip required">
                                <input type="hidden" id="clubreviewform-ip" class="form-control"
                                       name="ClubReviewForm[ip]" value="141.136.79.1">
                                <div class="help-block"></div>
                            </div>
                            <div class="the_form_col the_form_col_third">
                                <div class="the_form_div">
                                    <div class="form-group field-clubreviewform-name required">
                                        <input type="text" id="clubreviewform-name" class="form-control"
                                               name="ClubReviewForm[name]" placeholder="Имя" aria-required="true">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="the_form_div">
                                    <div class="form-group field-clubreviewform-mail required">
                                        <input type="text" id="clubreviewform-mail" class="form-control"
                                               name="ClubReviewForm[mail]" placeholder="E-mail" aria-required="true">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="the_form_col the_form_col_third the_form_col_textarea">
                                <div class="the_form_div the_form_div_textarea">
                                    <div class="form-group field-clubreviewform-reviewtext required">
                                                                                <textarea id="clubreviewform-reviewtext"
                                                                                          class="form-control"
                                                                                          name="ClubReviewForm[reviewText]"
                                                                                          placeholder="Текст отзыва"
                                                                                          aria-required="true"></textarea>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="the_form_col the_form_col_third the_form_col_submit">
                                <div class="the_form_div the_form_div_submit">
                                    <input type="submit" name="submit1" value="Отправить отзыв">
                                    <p class="note">Ваш отзыв будет добавлен<br/>сразу после модерации<br/>администратором
                                        сайта
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <section class="partners_reviews_section">
            <div class="inner_section clearfix">
                <p class="section_title">Отзывы участников проекта</p>
                <div class="partners_wrap">
                    <div class="partners_div" style="">
                        <div class="img">
                            <img src="/img/default-person.jpg" alt="">
                            <p class="name">fsdfsdfs</p>
                            <p class="date">4 июня 2018 г.</p>
                        </div>
                        <div class="text">
                            <p>
                                sdfsdfsdfsd
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection