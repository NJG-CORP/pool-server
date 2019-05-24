@extends('layouts.default')

@section('title', 'Главная | Поиск Игроков')

@section('content')

    <main class="main inner_page_main bg_eeeff3">

        <section class="the_content_section">
            <div class="inner_section">

                <div class="breadcrumbs inner_section">
                    <p itemscope itemtype="http://schema.org/BreadcrumbList">
								<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
									<a href="/" itemprop="item"><span itemprop="name">Главная</span></a>
									<meta itemprop="position" content="1">
								</span>
                        <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
									<span itemprop="item"><span itemprop="name">Игроки</span></span>
									<meta itemprop="position" content="2">
								</span>
                    </p>
                </div>

                <h1>Игроки</h1>

                @if(session('success'))
                    <div>
                        {{session('success')}}
                    </div>
                @endif
                @if(session('error'))
                    <div>
                        {{session('error')}}
                    </div>
                @endif
                @if($errors)
                    @foreach($errors as $error)
                        {{$error}}
                    @endforeach

                @endif
                <div class="the_form the_players_search_form the_players_search_form_mark2 clearfix">
                    <form id="frm44" class="frm4 clearfix" action="https://poolbuddy.ru/search" method="post"
                          data-pjax="" enctype="multipart/form-data">
                        <input type="hidden" name="_csrf-frontend"
                               value="T9bCrVWUPUKshG05Jpfi2PWPIcQUj7SItaFrtwWMi_UVju_KPPFtNM7yIHdv8rS5sb9zvSDexbH65gjSScbAjw==">
                        {{csrf_field()}}
                        <div class="the_form_div the_form_div_half">
                            <div class="form-group field-searchform-location">
                                <label class="control-label">Город</label>
                                <input type="text" name="city" id="location"
                                       value="{{ !old('city') ? ($user->city ? $user->city->name : '') : old('city') }}"
                                       class="city-autocomplete" placeholder="Санкт-Петербург"/>
                            </div>

                            <div class="form-group field-searchform-days required cleared">
                                <label class="control-label">Дни игры</label>

                                <select name="days[]" data-placeholder="Дни игры" class="multiselect" multiple
                                        tabindex="2">
                                    <!--<option value=""></option>-->
                                    <option value="1" {{ collect(old('days'))->contains(1) ? 'selected="selected"' : '' }}>
                                        Понедельник
                                    </option>
                                    <option value="2" {{ collect(old('days'))->contains(2) ? 'selected="selected"' : '' }}>
                                        Вторник
                                    </option>
                                    <option value="3" {{ collect(old('days'))->contains(3) ? 'selected="selected"' : '' }}>
                                        Среда
                                    </option>
                                    <option value="4" {{ collect(old('days'))->contains(4) ? 'selected="selected"' : '' }}>
                                        Четверг
                                    </option>
                                    <option value="5" {{ collect(old('days'))->contains(5) ? 'selected="selected"' : '' }}>
                                        Пятница
                                    </option>
                                    <option value="6" {{ collect(old('days'))->contains(6) ? 'selected="selected"' : '' }}>
                                        Суббота
                                    </option>
                                    <option value="7" {{ collect(old('days'))->contains(7) ? 'selected="selected"' : '' }}>
                                        Воскресенье
                                    </option>
                                </select>
                            </div>

                            <div class="form-group field-searchform-payment required">
                                <label class="control-label">Оплата</label>

                                <select name="game_payment_type[]" data-placeholder="Оплата"
                                        class="multiselect" multiple tabindex="2">
                                    <option value="{{\App\Models\User::PAYMENT_TYPE_HALF}}" {{collect(old('game_payment_type'))->contains(\App\Models\User::PAYMENT_TYPE_HALF )? 'selected="selected"}' : ''}}>
                                        Пополам
                                    </option>
                                    <option value="{{\App\Models\User::PAYMENT_TYPE_ME}}" {{collect(old('game_payment_type'))->contains(\App\Models\User::PAYMENT_TYPE_ME )? 'selected="selected"}' : ''}}>
                                        Беру на себя
                                    </option>
                                    <option value="{{\App\Models\User::PAYMENT_TYPE_YOU}}" {{collect(old('game_payment_type'))->contains(\App\Models\User::PAYMENT_TYPE_YOU ) ? 'selected="selected"}' : ''}}>
                                        За счет партнера
                                    </option>
                                    <option value="{{\App\Models\User::PAYMENT_TYPE_UNIMPORTANT}}" {{collect(old('game_payment_type'))->contains(\App\Models\User::PAYMENT_TYPE_UNIMPORTANT ) ? 'selected="selected"}' : ''}}>
                                        Не имеет значения
                                    </option>
                                </select>
                            </div>

                        </div>


                        <div class="the_form_div the_form_div_half">

                            <div class="form-group">
                                <label class="control-label">Удобное время</label>

                                <div><input readonly="readonly" type="text" name="time"
                                            value="с {{ !old('game_time_from') ?  $user->game_time_from_hour : old('game_time_from') }} до {{ !old('game_time_to') ? $user->game_time_to_hour : old('game_time_to') }} часов">
                                </div>
                                <input type="hidden" name="game_time_from"
                                       value="{{ !old('game_time_from') ? $user->game_time_from_hour : old('game_time_from') }}">
                                <input type="hidden" name="game_time_to"
                                       value="{{ !old('game_time_to') ? $user->game_time_to_hour : old('game_time_to') }}">
                                <div class="slider-range-wrap" id="slider-range-time-wrap">
                                    <div class="slider-range" id="slider-range-time"><span class="val-min">0</span><span
                                                class="val-max">24</span></div>
                                </div>
                            </div>


                            <div class="form-group field-searchform-types required">
                                <label class="control-label">Тип игры</label>

                                <select name="game_type[]" data-placeholder="Тип игры" class="multiselect"
                                        multiple tabindex="2">
                                    <option value="{{\App\Models\User::GAME_TYPE_POOL}}" {{collect(old('game_type'))->contains(\App\Models\User::GAME_TYPE_POOL) ? 'selected="selected"}' : ''}}>
                                        Пул
                                    </option>
                                    <option value="{{\App\Models\User::GAME_TYPE_SNOOKER}}" {{collect(old('game_type'))->contains(\App\Models\User::GAME_TYPE_SNOOKER) ? 'selected="selected"}' : ''}}>
                                        Снукер
                                    </option>
                                    <option value="{{\App\Models\User::GAME_TYPE_RUSSIAN}}" {{collect(old('game_type'))->contains(\App\Models\User::GAME_TYPE_RUSSIAN)? 'selected="selected"}' : ''}}>
                                        Русский бильярд
                                    </option>
                                </select>

                            </div>

                            <div class="form-group field-searchform-sex required">
                                <label class="control-label">Пол</label>

                                <select name="gender" data-placeholder="Пол" tabindex="2">
                                    <option disabled="disabled" selected="selected" value="">Пол</option>
                                    <option value="0" {{ old('gender') == 0 ? 'selected="selected"' : '' }}>Мужчина
                                    </option>
                                    <option value="1" {{ old('gender') == 1 ? 'selected="selected"' : '' }}>Женщина
                                    </option>
                                    <option value="-1" {{ old('gender') == -1 ? 'selected="selected"' : '' }}>Не важно
                                    </option>
                                </select>

                            </div>
                        </div>

                        <div class="the_form_div the_form_div_half stars_rating">
                            <div class="form-group">
                                <label class="control-label">Рейтинг игрока</label>

                                <div class="rating">
                                    <div class="form-group field-searchform-rating required">
                                        <p class="help-block help-block-error"></p>
                                    </div>
                                    <div class="stars stars_big dynamic_stars">
                                        <div>
                                            <span class="star{{ old('rating') ?? '' }}">{{ old('rating') ?? '' }}</span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="rating" class="ratings"
                                           value="{{ old('rating') ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="the_form_div the_form_div_submit the_form_div_half">
                            <input type="submit" name="submit1" value="Начать поиск">
                        </div>

                        <!--<div class="the_form_div the_form_div_checkbox the_form_div_half">
                            <label><input type="checkbox" name="remember_me">
                            <span>Запомнить параметры поиска</span></label>
                        </div>-->

                    </form>

                </div>
                @if(isset($results) && isset($results['players']) && count($results['players']) > 0)
                    <div class="players_table_wrap">
                        <div class="inner_section">
                            <div class="players_table">
                                <div class="players_table_head">
                                    <p>Игрок</p>
                                    <p>Рейтинг</p>
                                    <p>Вид игры</p>
                                </div>

                                <div class="players_table_content">
                                    @foreach($results['players'] as $player)
                                        <div class="players_table_row">
                                            <div class="img">
                                                <img src="{{$player->getAvatarUrl()}}" alt="">
                                            </div>

                                            <div class="specs">

                                                <div class="text">
                                                    <p class="name"><a
                                                                href="{{(route('profile.card.other', ['id' => $player->id]))}}">{{$player->getUsername()}}
                                                            , {{$player->age}} год</a></p>

                                                    <p>{{ $player->getAddress() }}</p>
                                                </div>

                                                <div class="stars">
                                                    <span class="star{{$player->calculated_rating}}"></span>
                                                </div>

                                                <div class="descr">
                                                    <p>Вид игры: {{$player->getGameTypes()}}</p>
                                                </div>

                                                {{--<div class="status">--}}
                                                {{--<p>Статус <span class="status_span">Pro</span></p>--}}
                                                {{--</div>--}}

                                                <a class="button invite_button show-invite-popup" href="#invite_popup"
                                                   data-uid="{{$player->id}}">Пригласить</a>
                                            </div>
                                        </div><!--/players_table_row-->
                                    @endforeach
                                </div>
                            </div><!--/players_table-->

                        </div>
                    </div>
                @endif
            </div>
        </section><!--/the_content_section-->
    </main>

    <div class="popup order_call_popup invite_popup" id="invite_popup">
        <div class="the_form">
            <p class="title">Отправить приглашение</p>

            <form method="post" class="frm02" id="invite-form" action="{{route('send.invite')}}">
                {{csrf_field()}}
                <input type="hidden" name="invited_id" id="invited-id">
                <div class="the_form_div">
                    <select name="club_id" id="invite_place" data-placeholder="Выберите клуб" tabindex="2" required>
                        <option disabled="disabled" selected="selected" value="">Выберите клуб</option>
                        @foreach($clubs as $club)
                            <option value="{{$club['id']}}">{{$club['title']}}</option>
                        @endforeach
                    </select>
                    <span class="legend">пр.Культуры, д.111, ПаркХаус</span>
                </div>
                <div class="the_form_div">
                    <input type="text" id="invite-form-date" name="invite-date" placeholder="Дата" autocomplete="off" tabindex="3" required>
                </div>
                <div class="the_form_div">
                    <input type="text" id="invite-form-time" class="timepicker" name="time" autocomplete="off" required>
                </div>
                <div class="the_form_div">
                    <input type="submit" name="submit1" value="Отправить" tabindex="5">
                </div>
            </form>
        </div>
    </div><!--/order_call_popup invite_popup-->

    @push('scripts')
    <script>
        function initAutoComplete() {
            const autocomplete = new google.maps.places.Autocomplete(document.getElementById('location'), {
                types: ['(cities)'],
                componentRestrictions: {country: "ru"}
            });
            autocomplete.addListener('place_changed', function () {
                const place = autocomplete.getPlace();
                $("#location").val(getCity(place.address_components));
            });


            const autocompleteInvite = new google.maps.places.Autocomplete(document.getElementById('invite-city'), {
                types: ['(cities)'],
                componentRestrictions: {country: "ru"}
            });

            autocompleteInvite.addListener('place_changed', function () {
                const place = autocomplete.getPlace();
                $('#invite-city').val(getCity(place.address_components));
            });
        }

        function getCity(address) {
            let city = '';
            $.each(address, function (index, value) {
                if (value.types[0] === 'locality') {
                    city = value.long_name;
                }
            });

            return city;
        }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&libraries=places&callback=initAutoComplete"></script>

    <script src="{{asset('js/timepicker.js')}}"></script>
    <script>
      $('.timepicker').timepicker({"timeFormat": "HH:mm:ss", "showSecond": false});
      $('#invite-form').submit(function(e){
        var date = $("#invite-form-date").val();
        var time = $("#invite-form-time").val();
        $("<input>").attr('name', 'meeting_at').css('display', 'none').val(date + " " + time).appendTo(this);
        return true;
      });

      $('.show-invite-popup').click(function(e){
        var user_id = $(this).attr('data-uid');
        $('#invited-id').val(user_id);
      });

    </script>
    @endpush

    @push('styles')
        <link rel="stylesheet" href="{{asset('css/timepicker.css')}}">
    @endpush

@endsection