@extends('layouts.default')

@section('title', 'Главная | Поиск Игроков')

@section('content')

    <main class="main inner_page_main">


        <section class="the_content_section">
            <div class="inner_section">

                <div class="breadcrumbs">
                    <p><a href="/">Главная</a> / <span>Игроки</span></p>
                </div>

                <h1>Игроки</h1>
                <div class="the_form the_players_search_form clearfix">
                    <form id="frm44" class="frm4 clearfix" action="{{ route('search') }}" method="post" data-pjax=""
                          enctype="multipart/form-data">
                        <input type="hidden" name="_csrf-frontend"
                               value="T9bCrVWUPUKshG05Jpfi2PWPIcQUj7SItaFrtwWMi_UVju_KPPFtNM7yIHdv8rS5sb9zvSDexbH65gjSScbAjw==">
                        <div class="the_form_div the_form_div_half">
                            <div class="form-group field-searchform-location">

                                <input type="text" id="search-location" class="form-control"
                                       name="city" placeholder="Город" autocomplete="off"
                                       value="{{ old('city') ? old('city') : '' }}">

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
                                                                            value="monday"
                                                                            class="ez-hide" {{ old('days') && in_array('monday', old('days')) ? 'checked' : '' }}>
                                            </div>
                                            Понедельник</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="days[]"
                                                                            value="tuesday"
                                                                            class="ez-hide" {{ old('days') && in_array('tuesday', old('days')) ? 'checked' : '' }}>
                                            </div>
                                            Вторник</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="days[]"
                                                                            value="wednesday"
                                                                            class="ez-hide" {{ old('days') && in_array('wednesday', old('days')) ? 'checked' : '' }}>
                                            </div>
                                            Среда</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="days[]"
                                                                            value="thursday"
                                                                            class="ez-hide" {{ old('days') && in_array('thursday', old('days')) ? 'checked' : '' }}>
                                            </div>
                                            Четверг</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="days[]"
                                                                            value="thursday"
                                                                            class="ez-hide" {{ old('days') && in_array('thursday', old('days')) ? 'checked' : '' }}>
                                            </div>
                                            Пятница</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="days[]"
                                                                            value="saturday"
                                                                            class="ez-hide" {{ old('days') && in_array('saturday', old('days')) ? 'checked' : '' }}>
                                            </div>
                                            Суббота</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="days[]"
                                                                            value="sunday"
                                                                            class="ez-hide" {{ old('days') && in_array('sunday', old('days')) ? 'checked' : '' }}>
                                            </div>
                                            Воскресенье</label></div>
                                </div>

                                <p class="help-block help-block-error">
                                    @if($errors->has('days'))
                                        {{ $errors->first('days') }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="the_form_div the_form_div_half">

                            <div><input type="text" id="range" name="time" value="{{ old('time') ? old('time') : '' }}">
                            </div>
                            <div class="form-group field-searchform-time-from required" id="">
                                <input type="hidden" id="searchform-time-from" name="from">
                                <p class="help-block help-block-error"></p>
                            </div>
                            <div class="form-group field-searchform-time-to required" id="">

                                <input type="hidden" id="searchform-time-to" name="to">
                                <p class="help-block help-block-error"></p>
                            </div>
                        </div>

                        <div class="the_form_div the_form_div_half">
                            <div class="form-group field-searchform-types required">
                                <label class="control-label">Тип игры</label>
                                <div id="searchform-types" multiple="multiple" placeholder="Оплата">
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="types[]"
                                                                            value="pool"
                                                                            class="ez-hide" {{ old('types') && in_array('pool', old('types')) ? 'checked' : '' }}>
                                            </div>
                                            Пул</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="types[]"
                                                                            value="snooker"
                                                                            class="ez-hide" {{ old('types') && in_array('snooker', old('types')) ? 'checked' : '' }}>
                                            </div>
                                            Снукер</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="types[]"
                                                                            value="russian"
                                                                            class="ez-hide" {{ old('types') && in_array('russian', old('types')) ? 'checked' : '' }}>
                                            </div>
                                            Русский бильярд</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="types[]"
                                                                            value="caromball"
                                                                            class="ez-hide" {{ old('types') && in_array('caromball', old('types')) ? 'checked' : '' }}>
                                            </div>
                                            Карамболь</label></div>
                                </div>

                                <p class="help-block help-block-error">
                                    @if($errors->has('types'))
                                        {{ $errors->first('types') }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="the_form_div the_form_div_half">

                            <div class="form-group field-searchform-payment required">
                                <label class="control-label">Оплата</label>
                                <div id="searchform-payment" multiple="multiple" placeholder="Оплата">
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="payment[]"
                                                                            value="half"
                                                                            class="ez-hide" {{ old('payment') && in_array('half', old('payment')) ? 'checked' : '' }}>
                                            </div>
                                            Пополам</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="payment[]"
                                                                            value="me"
                                                                            class="ez-hide" {{ old('payment') && in_array('me', old('payment')) ? 'checked' : '' }}>
                                            </div>
                                            Беру на себя</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="payment[]"
                                                                            value="you"
                                                                            class="ez-hide" {{ old('payment') && in_array('you', old('payment')) ? 'checked' : '' }}>
                                            </div>
                                            За счет партнера</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="payment[]"
                                                                            value="unimportant"
                                                                            class="ez-hide" {{ old('payment') && in_array('unimportant', old('payment')) ? 'checked' : '' }}>
                                            </div>
                                            Не имеет значения</label></div>
                                </div>

                                <p class="help-block help-block-error">
                                    @if($errors->has('payment'))
                                        {{ $errors->first('payment') }}
                                    @endif
                                </p>
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
                                <input type="hidden" name="rating" class="ratings"
                                       value="{{ old('rating') ? old('rating') : '' }}">
                            </div>
                        </div>

                        <div class="the_form_div the_form_div_radio the_form_div_half">
                            <div class="form-group field-searchform-sex required">
                                <label class="control-label">Пол</label>
                                <div id="searchform-sex" aria-required="true">
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="gender[]"
                                                                            value="0"
                                                                            class="ez-hide" {{ old('gender') && in_array('0', old('gender')) ? 'checked' : '' }}>
                                            </div>
                                            Мужчина</label></div>
                                    <div class="checkbox"><label>
                                            <div class="ez-checkbox"><input type="checkbox" name="gender[]"
                                                                            value="1"
                                                                            class="ez-hide" {{ old('gender') && in_array('1', old('gender')) ? 'checked' : '' }}>
                                            </div>
                                            Женщина</label></div>
                                </div>

                                <p class="help-block help-block-error">
                                    @if($errors->has('gender'))
                                        {{ $errors->first('gender') }}
                                    @endif
                                </p>
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
                    @if(!empty($results))
                        <div class="players_table">
                            <div class="players_table_head">
                                <p>Игрок</p>
                                <p>Рейтинг</p>
                                <p>Вид игры</p>
                            </div>
                            <div class="players_table_content">
                                @foreach($results as $result)

                                    <div class="players_table_row">
                                        <div class="img" style="cursor: pointer"
                                             onclick="window.open('/user/2-%D0%94%D0%B5%D0%BD%D0%B8%D1%81+%D0%A1%D0%B8%D0%B4%D0%BE%D1%80%D0%BE%D0%B2.html')">
                                            <img src="{{ asset('/img' . $result->getAvatar()) }}" alt="">
                                        </div>
                                        <div class="specs">
                                            <div class="text" style="cursor: pointer"
                                                 onclick="window.open('/user/2-%D0%94%D0%B5%D0%BD%D0%B8%D1%81+%D0%A1%D0%B8%D0%B4%D0%BE%D1%80%D0%BE%D0%B2.html')">
                                                <p class="name">{{ $result->name ?? '' }}, {{ $result->age ?? '' }}
                                                    год</p>
                                                <p>Город: {{ $result->city->name ?? '' }}</p>
                                            </div>

                                            <div class="stars">
                                                <span class="star{{ $result->calculated_rating ?? '0' }}"></span>
                                            </div>

                                            <div class="descr">
                                                <p>Вид
                                                    игры: @foreach($result->getGameType as $type) {{ $type->term->name . ' ' }}  @endforeach</p>
                                            </div>

                                            <div class="status">
                                                <!--<p>Статус <span class="status_span">Pro</span></p>-->        </div>

                                            <a class="button invite_button" data-toggle="modal"
                                               data-target="#InvitationModal"
                                               onclick="$('#invitationform-invitedid').val({{ $result->id }})">Пригласить</a>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    @else
                        <p>По Вашему запросу не найдено игроков</p>
                    @endif
                </div>
            </div>
        </section>
        <div id="InvitationModal" class="modal fade " role="dialog" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div id="myModalLabel" class="modal-header">
                        <h4>Приглашение</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div id="InvitationForm" data-pjax-container="" data-pjax-timeout="1000">
                            <form id="w0" action="{{ route('send.invite') }}" method="post" data-pjax=""
                                  enctype="multipart/form-data">
                                <input type="hidden" name="club_id" value="1">
                                <input type="hidden" name="invited_id" value="" id="invitationform-invitedid">
                                <div class="form-group field-invitationform-clubid required">
                                    <label class="control-label" for="invitationform-clubid">Клуб</label>
                                    <input type="text" id="invitationform-clubid" class="form-control"
                                           name="InvitationForm[clubId]" list="clubs-list" aria-required="true">
                                    <div class="help-block"></div>
                                </div>
                                <datalist id="clubs-list"></datalist>
                                <label>Время встречи</label>
                                <div class="time-picker">
                                    <div class="half-time-picker field-invitationform-meetingat required">
                                        <label class="control-label" for="invitationform-meetingat"></label>
                                        <div class="input-group datetime"><span class="input-group-addon"><i
                                                        class="glyphicon glyphicon-calendar"></i></span><input
                                                    type="text" id="invitationform-meetingat"
                                                    class="form-control hasDatepicker" name="meeting_at"></div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success" name="login-button">Пригласить
                                    </button>
                                </div>
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>



@endsection