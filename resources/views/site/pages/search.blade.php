@extends('layouts.default')

@section('title', '')


@section('content')

    <main class="main inner_page_main">


        <section class="the_content_section">
            <div class="inner_section">

                <div class="breadcrumbs">
                    <p><a href="/">Главная</a> / <span>Игроки</span></p>
                </div>

                <h1>Игроки</h1>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="the_form the_players_search_form clearfix">
                    <form id="frm44" class="frm4 clearfix" action="{{ route('search') }}" method="post" data-pjax="" enctype="multipart/form-data">
                        <input type="hidden" name="_csrf-frontend"
                               value="T9bCrVWUPUKshG05Jpfi2PWPIcQUj7SItaFrtwWMi_UVju_KPPFtNM7yIHdv8rS5sb9zvSDexbH65gjSScbAjw==">
                        <div class="the_form_div the_form_div_half">
                            <div class="form-group field-searchform-location">

                                <input type="text" id="searchform-location" class="form-control"
                                       name="location" placeholder="Город" autocomplete="off">

                                <p class="help-block help-block-error"></p>
                            </div>
                        </div>

                        <div class="form-group field-searchform-locationcity">



                            <p class="help-block help-block-error"></p>
                        </div>

                        <div class="the_form_div the_form_div_half">
                            <div class="form-group field-searchform-days required">
                                <label class="control-label">Дни игры</label>
                                <div id="searchform-days" multiple="multiple" placeholder="Дни игры">
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="days[]"
                                                                            value="monday" class="ez-hide"></div>
                                            Понедельник</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="days[]"
                                                                            value="tuesday" class="ez-hide"></div>
                                            Вторник</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="days[]"
                                                                            value="wednesday" class="ez-hide"></div>
                                            Среда</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="days[]"
                                                                            value="thursday" class="ez-hide"></div>
                                            Четверг</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="days[]"
                                                                            value="friday" class="ez-hide"></div>
                                            Пятница</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="days[]"
                                                                            value="saturday" class="ez-hide"></div>
                                            Суббота</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="days[]"
                                                                            value="sunday" class="ez-hide"></div>
                                            Воскресенье</label></div>
                                </div>

                                <p class="help-block help-block-error"></p>
                            </div>
                        </div>

                        <div class="the_form_div the_form_div_half">
                            <span class="irs js-irs-0"><span class="irs"><span class="irs-line" tabindex="0"><span
                                                class="irs-line-left"></span><span class="irs-line-mid"></span><span
                                                class="irs-line-right"></span></span><span class="irs-min"
                                                                                           style="visibility: hidden;">0:00</span><span
                                            class="irs-max" style="visibility: hidden;">23:00</span><span
                                            class="irs-from"
                                            style="visibility: visible; left: -1.59065%;">0:00</span><span
                                            class="irs-to"
                                            style="visibility: visible; left: 94.2485%;">23:00</span><span
                                            class="irs-single"
                                            style="visibility: hidden; left: 42.6503%;">0:00 — 23:00</span></span><span
                                        class="irs-grid"></span><span class="irs-bar"
                                                                      style="left: 1.8018%; width: 96.3964%;"></span><span
                                        class="irs-shadow shadow-from" style="display: none;"></span><span
                                        class="irs-shadow shadow-to" style="display: none;"></span><span
                                        class="irs-slider from" style="left: 0%;"></span><span class="irs-slider to"
                                                                                               style="left: 96.3964%;"></span></span>
                            <div id="slider-search" class="irs-hidden-input" tabindex="-1"></div>
                            <div class="form-group field-searchform-time-from required">


                                <p class="help-block help-block-error"></p>
                            </div>
                            <div class="form-group field-searchform-time-to required">


                                <p class="help-block help-block-error"></p>
                            </div>
                        </div>

                        <div class="the_form_div the_form_div_half">
                            <div class="form-group field-searchform-types required">
                                <label class="control-label">Тип игры</label>
                                <div id="searchform-types" multiple="multiple" placeholder="Оплата">
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="types[]"
                                                                            value="pool" class="ez-hide"></div>
                                            Пул</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="types[]"
                                                                            value="snooker" class="ez-hide"></div>
                                            Снукер</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="types[]"
                                                                            value="russian" class="ez-hide"></div>
                                            Русский бильярд</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="types[]"
                                                                            value="caromball" class="ez-hide"></div>
                                            Карамболь</label></div>
                                </div>

                                <p class="help-block help-block-error"></p>
                            </div>
                        </div>

                        <div class="the_form_div the_form_div_half">

                            <div class="form-group field-searchform-payment required">
                                <label class="control-label">Оплата</label>
                                <div id="searchform-payment" multiple="multiple" placeholder="Оплата">
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="payment[]"
                                                                            value="half" class="ez-hide"></div>
                                            Пополам</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="payment[]"
                                                                            value="me" class="ez-hide"></div>
                                            Беру на себя</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="payment[]"
                                                                            value="you" class="ez-hide"></div>
                                            За счет партнера</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="payment[]"
                                                                            value="default" class="ez-hide"></div>
                                            Не имеет значения</label></div>
                                </div>

                                <p class="help-block help-block-error"></p>
                            </div>
                        </div>

                        <div class="the_form_div the_form_div_half">
                            <div class="rating">
                                <p>Рейтинг игрока</p>
                                <div class="form-group field-searchform-rating required">

                                    <p class="help-block help-block-error"></p>
                                </div>
                                <div class="stars stars_big dynamic_stars">
                                    <div><span></span></div>
                                </div>
                            </div>
                        </div>

                        <div class="the_form_div the_form_div_radio the_form_div_half">
                            <div class="form-group field-searchform-sex required">
                                <label class="control-label">Пол</label>
                                <div id="searchform-sex" aria-required="true">
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="sex[]"
                                                                            value="0" class="ez-hide"></div>
                                            Мужчина</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="sex[]"
                                                                            value="1" class="ez-hide"></div>
                                            Женщина</label></div>
                                </div>

                                <p class="help-block help-block-error"></p>
                            </div>
                        </div>

                        <div class="the_form_div the_form_div_submit the_form_div_half">
                            <input type="submit" name="submit1" value="Начать поиск">
                        </div>

                        <!--<div class="the_form_div the_form_div_checkbox the_form_div_half">
                            <label><input type="checkbox" name="remember_me">
                                <span>Запомнить параметры поиска</span></label>
                        </div>-->
                        <script>
                            var placeSearch, autocomplete;

                            function initAutocomplete() {
                                autocomplete = new google.maps.places.Autocomplete((document.getElementById('searchform-location')),
                                    {types: ['geocode']});

                                // When the user selects an address from the dropdown, populate the address
                                // fields in the form.
                                autocomplete.addListener('place_changed', fillInAddress);
                            }

                            function fillInAddress() {
                                // Get the place details from the autocomplete object.
                                var place = autocomplete.getPlace();

                                console.log(place['geometry']);
                                // Get each component of the address from the place details
                                // and fill the corresponding field on the form.
                                for (var i = 0; i < place.address_components.length; i++) {
                                    var addressType = place.address_components[i].types[0];
                                    switch (addressType) {
                                        case "locality":
                                            $("#searchform-locationcity").val(place.address_components[i].long_name);
                                            break;
                                    }
                                }
                            }
                        </script>
                        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAMj0tzo5dL6q5svRGhyCEYhMwqRcAtve4&amp;libraries=places&amp;callback=initAutocomplete"
                                async="" defer=""></script>

                        {{ csrf_field() }}
                    </form>
                </div>
            </div>


            <div class="players_table_wrap">
                <div class="inner_section">
                    <p>По Вашему запросу не найдено игроков</p></div>
            </div>
        </section>

    </main>

@endsection