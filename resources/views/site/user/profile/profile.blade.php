@extends('layouts.default')

@section('title', 'Мой профиль')

@section('content')
    <main class="main inner_page_main profile_inner_page_main">
        <section class="the_content_section">
            <div class="inner_section">

                <div class="breadcrumbs inner_section">
                    <p itemscope itemtype="http://schema.org/BreadcrumbList">
								<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
									<a href="/" itemprop="item"><span itemprop="name">Главная</span></a>
									<meta itemprop="position" content="1">
								</span>
                        <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
									<span itemprop="item"><span itemprop="name">Мой профиль</span></span>
									<meta itemprop="position" content="2">
								</span>
                    </p>
                </div>

                <h1>Мой профиль</h1>
                @if(session('success'))
                    <div class="alert alert-success">
                        <ul>
                            <li>{{ session('success') }}</li>
                        </ul>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">
                        <ul>
                            <li>{{ session('error') }}</li>
                        </ul>
                    </div>
                @endif
                @if(session('errors'))
                    <div class="alert alert-danger">
                        <ul>
                            @foreach(session('errors') as $error)
                                <li>{{$error}}</li>
                            @endforeach

                        </ul>
                    </div>
                @endif
                <div class="profile_page_block">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">

                        <div class="the_form the_players_search_form profile_form clearfix">

                            <div class="profile_page_avatar">
                                <div class="img">
                                    <img src="{{ $user->getAvatarUrl() }}" alt="">
                                </div>

                                <div class="text">
                                    <p class="name">{{ $user->getFullUsername() }}</p>

                                    <p>
                                        {{$user->getAddress()}}
                                    </p>
                                </div>

                                <a class="bell_button" href="#"></a>
                            </div><!--/profile_page_avatar-->

                            <form method="post" class="frm4 clearfix" id="frm44">
                                {{csrf_field()}}
                                <p class="form_subtitle">Личные данные</p>

                                <div class="form_sub_section">

                                    <div class="the_form_col the_form_col_half">

                                        <div class="the_form_div">
                                            <input type="text" name="name"
                                                   value="{{!old('name') ? $user->name : old('name')}}"
                                                   placeholder="Имя"/>
                                            @if($errors->has('name'))
                                                {{ $errors->first('name') }}
                                            @endif
                                        </div>

                                        <div class="the_form_div">
                                            <input type="text" name="surname"
                                                   value="{{ !old('surname') ? $user->surname : old('surname') }}"
                                                   placeholder="Фамилия"/>
                                            @if($errors->has('surname'))
                                                {{ $errors->first('surname') }}
                                            @endif
                                        </div>

                                        <div class="the_form_div">
                                            <input type="text" name="age" placeholder="Возраст"
                                                   value="{{ !old('age') ? $user->age : old('age') }}"/>
                                            <div class="help-block help-block-red @if($errors->has('age')) {{ 'has-error' }} @endif">
                                                @if($errors->has('age'))
                                                    {{ $errors->first('age') }}
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                    <div class="the_form_col the_form_col_half">

                                        <div class="the_form_div">
                                            <input type="text" name="phone" placeholder="Телефон"
                                                   value="{{ !old('phone') ? $user->phone : old('phone') }}">
                                            <div class="help-block help-block-red @if($errors->has('phone')) {{ 'has-error' }} @endif">
                                                @if($errors->has('phone'))
                                                    {{ $errors->first('phone') }}
                                                @endif
                                            </div>
                                        </div>

                                        <div class="the_form_div">
                                            <input type="text"
                                                   name="email"
                                                   value="{{ !old('email') ? $user->email : old('email')}}">
                                            <div class="help-block help-block-red @if($errors->has('email')) {{ 'has-error' }} @endif">
                                                @if($errors->has('email'))
                                                    {{ $errors->first('email') }}
                                                @endif
                                            </div>
                                        </div>

                                        <div class="the_form_div">
                                            <div class="switcher">
                                                <span>Пол:</span>
                                                <input type="radio" name="gender" value="0"
                                                       id="switch1" {{ $user->gender == 0 ? 'checked' : '' }} />
                                                <label for="switch1">Мужской</label>
                                                <input type="radio" name="gender" value="1"
                                                       id="switch2" {{ $user->gender == 1 ? 'checked' : '' }} />
                                                <label for="switch2">Женский</label>
                                            </div>
                                            <div class="help-block help-block-red @if($errors->has('gender')) {{ 'has-error' }} @endif">
                                                @if($errors->has('gender'))
                                                    {{ $errors->first('gender') }}
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="form_sub_section">

                                    <div class="the_form_col the_form_col_half">

                                        <div class="the_form_div">
                                            <input type="text" name="city" id="location"
                                                   value="{{ !old('city[name]') ? ($user->city ? $user->city->name : '') : old('city[name]') }}"
                                                   class="city-autocomplete" placeholder="Город"/>
                                        </div>

                                    </div>
                                    <div class="the_form_col the_form_col_half">
                                        <div class="the_form_div">
                                            <input type="text" name="street" placeholder="Улица"
                                                   value="{{ !old('street') ? $user->street : old('street') }}">
                                        </div>
                                    </div>

                                </div>

                                <div class="form_sub_section">

                                    <div class="the_form_col the_form_col_half">

                                        <div class="the_form_div">
                                            <!--												<input type="text" name="days" placeholder="Дни игры" /> -->
                                            <select name="game_days[]" data-placeholder="Дни игры" class="multiselect"
                                                    multiple tabindex="2">
                                                <option value="1" {{ (new \App\Services\GameTimeService($user))->isChecked(1) ? 'selected="selected"' : '' }}>
                                                    Понедельник
                                                </option>
                                                <option value="2" {{ (new \App\Services\GameTimeService($user))->isChecked(2) ? 'selected="selected"' : '' }}>
                                                    Вторник
                                                </option>
                                                <option value="3" {{ (new \App\Services\GameTimeService($user))->isChecked(3) ? 'selected="selected"' : '' }}>
                                                    Среда
                                                </option>
                                                <option value="4" {{ (new \App\Services\GameTimeService($user))->isChecked(4) ? 'selected="selected"' : '' }}>
                                                    Четверг
                                                </option>
                                                <option value="5" {{ (new \App\Services\GameTimeService($user))->isChecked(5) ? 'selected="selected"' : '' }}>
                                                    Пятница
                                                </option>
                                                <option value="6" {{ (new \App\Services\GameTimeService($user))->isChecked(6) ? 'selected="selected"' : '' }}>
                                                    Суббота
                                                </option>
                                                <option value="7" {{ (new \App\Services\GameTimeService($user))->isChecked(7) ? 'selected="selected"' : '' }}>
                                                    Воскресенье
                                                </option>
                                            </select>
                                            @if($errors->has('game_days'))
                                                {{ $errors->first('game_days') }}
                                            @endif
                                        </div>

                                        <div class="the_form_div">
                                            <!--												<input type="text" name="level" placeholder="Уровень игры" /> -->
                                            <select name="skill_level[]" data-placeholder="Уровень игры"
                                                    class="multiselect" multiple tabindex="2">
                                                <option value="{{\App\Models\User::SKILL_LEVEL_WEAK}}" {{ $user->isSkillLevel(\App\Models\User::SKILL_LEVEL_WEAK) ? 'selected="selected"' : '' }}>
                                                    Новичок
                                                </option>
                                                <option value="{{\App\Models\User::SKILL_LEVEL_NORMAL}}" {{ $user->isSkillLevel(\App\Models\User::SKILL_LEVEL_NORMAL) ? 'selected="selected"' : '' }}>
                                                    Стандартный
                                                </option>
                                                <option value="{{\App\Models\User::SKILL_LEVEL_PROFI}}"{{ $user->isSkillLevel(\App\Models\User::SKILL_LEVEL_PROFI) ? 'selected="selected"' : '' }}>
                                                    Профи
                                                </option>
                                            </select>
                                            @if($errors->has('skill_level'))
                                                {{ $errors->first('skill_level') }}
                                            @endif
                                        </div>

                                        <div class="the_form_div">
                                            <!--												<input type="text" name="payment" placeholder="Оплата" /> -->
                                            <select name="game_payment_type[]" data-placeholder="Оплата"
                                                    class="multiselect" multiple tabindex="2">
                                                <option value="{{\App\Models\User::PAYMENT_TYPE_HALF}}" {{$user->isGamePaymentType(\App\Models\User::PAYMENT_TYPE_HALF )? 'selected="selected"}' : ''}}>
                                                    Пополам
                                                </option>
                                                <option value="{{\App\Models\User::PAYMENT_TYPE_ME}}" {{$user->isGamePaymentType(\App\Models\User::PAYMENT_TYPE_ME )? 'selected="selected"}' : ''}}>
                                                    Беру на себя
                                                </option>
                                                <option value="{{\App\Models\User::PAYMENT_TYPE_YOU}}" {{$user->isGamePaymentType(\App\Models\User::PAYMENT_TYPE_YOU ) ? 'selected="selected"}' : ''}}>
                                                    За счет партнера
                                                </option>
                                                <option value="{{\App\Models\User::PAYMENT_TYPE_UNIMPORTANT}}" {{$user->isGamePaymentType(\App\Models\User::PAYMENT_TYPE_UNIMPORTANT ) ? 'selected="selected"}' : ''}}>
                                                    Не имеет значения
                                                </option>
                                            </select>
                                            @if($errors->has('game_payment_type'))
                                                {{ $errors->first('game_payment_type') }}
                                            @endif
                                        </div>

                                    </div>

                                    <div class="the_form_col the_form_col_half">

                                        <div class="the_form_div">
                                            <!--												<input type="text" name="times" placeholder="Время игры" /> -->
                                            <div><input readonly="readonly" type="text" name="time"
                                                        value="c {{ $user->gameTimeFromHour}} до {{ $user->gameTimeToHour }} часов">
                                            </div>
                                            <input type="hidden" name="game_time_from"
                                                   value="{{$user->gameTimeFromHour }}">
                                            <input type="hidden" name="game_time_to"
                                                   value="{{ $user->gameTimeToHour }}">

                                            <div class="slider-range-wrap" id="slider-range-time-wrap">
                                                <div class="slider-range" id="slider-range-time">
                                                    <span class="val-min">0</span>
                                                    <span class="val-max">23</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="the_form_div">
                                            <!--												<input type="text" name="types" placeholder="Вид игры" /> -->
                                            <select name="game_type[]" data-placeholder="Тип игры" class="multiselect"
                                                    multiple tabindex="2">
                                                <option value="{{\App\Models\User::GAME_TYPE_POOL}}" {{$user->isGameType(\App\Models\User::GAME_TYPE_POOL) ? 'selected="selected"}' : ''}}>
                                                    Пул
                                                </option>
                                                <option value="{{\App\Models\User::GAME_TYPE_SNOOKER}}" {{$user->isGameType(\App\Models\User::GAME_TYPE_SNOOKER) ? 'selected="selected"}' : ''}}>
                                                    Снукер
                                                </option>
                                                <option value="{{\App\Models\User::GAME_TYPE_RUSSIAN}}" {{$user->isGameType(\App\Models\User::GAME_TYPE_RUSSIAN)? 'selected="selected"}' : ''}}>
                                                    Русский бильярд
                                                </option>
                                            </select>
                                            @if($errors->has('game_type'))
                                                {{ $errors->first('game_type') }}
                                            @endif
                                        </div>

                                        <!--
                                                                                    <div class="the_form_div the_form_div_checkbox">
                                                                                        <label><input type="checkbox" name="itrener"><span>Я тренер</span></label>
                                                                                    </div>
                                        -->
                                    </div>

                                </div>

                                <p class="form_subtitle">Сменить пароль</p>

                                <div class="form_sub_section">
                                    <div class="the_form_col the_form_col_half">
                                        <div class="the_form_div">
                                            <input type="password" name="oldpass" placeholder="Старый пароль"/>
                                        </div>

                                    </div><!--/the_form_col_half-->

                                    <div class="the_form_col the_form_col_half">
                                        <div class="the_form_div">
                                            <input type="password" name="newpass" placeholder="Новый пароль"/>
                                        </div>
                                        @if($errors->has('newpass'))
                                            {{ $errors->first('newpass') }}
                                        @endif
                                    </div><!--/the_form_col_half-->
                                </div><!--/form_sub_section-->

                                <!--
                                                                    <p class="form_subtitle">Платежные данные</p>

                                                                    <div class="form_sub_section">
                                                                        <div class="the_form_col the_form_col_half">
                                                                            <div class="payment_card_block">
                                                                                <div class="the_form_div">
                                                                                    <input type="text" name="card_n" placeholder="Номер карты" />
                                                                                </div>

                                                                                <div class="the_form_div black_bg">
                                                                                    <input type="text" name="card_n1" placeholder="Номер карты" />

                                                                                    <input type="text" name="cvv" placeholder="CVV" />
                                                                                </div>

                                                                                <div class="the_form_div">
                                                                                    <input type="text" name="owner" placeholder="Имя владельца" />
                                                                                </div>
                                                                            </div>
                                                                            <a class="button add_card_button" href="#">Добавить карту</a>
                                                                        </div>
                                                                    </div>
                                -->

                                <div class="form_sub_section">
                                    <div class="the_form_col the_form_col_half">
                                        <!--
                                                                                    <div class="the_form_div the_form_div_submit">
                                                                                            <a class="button edit_data_button" href="#">Редактировать данные</a>
                                                                                    </div>
                                        -->
                                    </div>

                                    <div class="the_form_col the_form_col_half">
                                        <div class="the_form_div the_form_div_submit">
                                            <input type="submit" name="submit" value="Сохранить"
                                                   placeholder="Сохранить данные"/>
                                        </div>
                                    </div>
                                </div>

                            </form>

                        </div><!--/profile_form-->
                </div><!--/profile_page_block-->
            </div>

            </div>

        </section>

    </main>
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
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&libraries=places&callback=initAutoComplete&language=ru-RU"></script>

@endsection
