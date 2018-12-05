@extends('layouts.default')

@section('title', 'Мой профиль')

@section('content')
    <main class="main inner_page_main profile_inner_page_main">
        <section class="the_content_section">
            <div class="inner_section">

                <div class="breadcrumbs">
                    <p><a href="/">Главная</a> / <span>Мой профиль</span></p>
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
                <div class="profile_page_block">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        <div class="the_form the_players_search_form profile_form clearfix">

                            <div class="profile_page_avatar">
                                <div class="img">
                                    <img src="{{ asset('img/' . $user->avatar) }}" alt="">
                                </div>

                                <div class="text">
                                    <p class="name">{{ $user->name }}</p>
                                </div>

                                <a class="bell_button" href=""></a>
                            </div>
                            <p class="form_subtitle">Личные данные</p>

                            <div class="form_sub_section">
                                <div class="the_form_col the_form_col_half">
                                    <div class="the_form_div">
                                        <div class="form-group field-profileform-tel has-success">
                                            <label class="control-label" for="profileform-tel">Телефон</label>
                                            <input type="text" id="profileform-tel" class="phone-mask"
                                                   name="phone" placeholder="Телефон" aria-invalid="false"
                                                   value="{{ !old('phone') ? $user->phone : old('phone') }}">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="the_form_div">
                                        <div class="form-group field-profileform-email has-success">
                                            <label class="control-label" for="profileform-email">Email</label>
                                            <input type="text" id="profileform-email" class="form-control"
                                                   name="email" value="{{ !old('email') ? $user->email : old('email')}}"
                                                   placeholder="E-mail"
                                                   aria-invalid="false">
                                            <div class="help-block">
                                                @if($errors->has('email'))
                                                    {{ $errors->first('email') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="the_form_div">
                                        <div class="form-group field-profileform-age required has-error">
                                            <label class="control-label" for="profileform-age">Возраст</label>
                                            <input type="number" id="profileform-age" class="form-control"
                                                   name="age" placeholder="Возраст" aria-required="true"
                                                   aria-invalid="true" value="{{ !old('age') ? $user->age : old('age') }}">
                                            <div class="help-block">
                                                @if($errors->has('age'))
                                                    {{ $errors->first('age') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="the_form_div">
                                        <div class="form-group field-profileform-location">
                                            <label class="control-label" for="profileform-location">Город,
                                                страна</label>
                                            <input type="text" id="profileform-location" class="form-control"
                                                   name="location" value="{{ !old('street') ? $user->street : old('street') }}"
                                                   placeholder="Улица, номер, город, страна" autocomplete="off">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="the_form_div">
                                        <input type="hidden" name="id" value="{{ $user->id }}">
                                    </div>
                                    <div class="the_form_div">
                                        <div class="form-group field-profileform-locationhouse">
                                            <label class="control-label" for="profileform-locationhouse">Дом</label>
                                            <input type="text" id="profileform-locationhouse" class="form-control"
                                                   name="houselocation" placeholder="Квартира" value="{{ !old('location') ? $user->location : old('location') }}">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="the_form_div">
                                        <div class="form-group field-profileform-sex required">
                                            <label class="control-label">Пол</label>
                                            <input type="hidden" name="sex" value="">
                                            <div id="profileform-sex" aria-required="true"><label>
                                                    <div class="ez-radio"><input type="radio" name="sex"
                                                                                 value="0"
                                                                                 class="ez-hide" {{ $user->gender == 0 ? 'checked' : '' }}>
                                                    </div>
                                                    Мужчина</label>
                                                <label>
                                                    <div class="ez-radio"><input type="radio" name="sex"
                                                                                 value="1"
                                                                                 class="ez-hide" {{ $user->gender == 1 ? 'checked' : '' }}>
                                                    </div>
                                                    Женщина</label></div>
                                            <div class="help-block">
                                                @if($errors->has('sex'))
                                                    {{ $errors->first('sex') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="the_form_col the_form_col_half">
                                    <div class="the_form_div">
                                        <div class="form-group field-profileform-types required">
                                            <label class="control-label">Вид игры</label>
                                            <input type="hidden" name="types" value="">
                                            <div id="profileform-types" multiple="multiple" placeholder="Вид игры"
                                                 options="{&quot;&quot;:{&quot;selected&quot;:true}}"><label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="types[]"
                                                                                    value="pool"
                                                                                    class="ez-hide"
                                                                {{ old('types') && in_array('pool', old('types')) ? 'checked' : '' }}
                                                                {{ !old('types') && $types && in_array('pool', $types) ? 'checked' : '' }}>
                                                    </div>
                                                    Пул</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="types[]"
                                                                                    value="snooker"
                                                                                    class="ez-hide"
                                                                {{ old('types') && in_array('snooker', old('types')) ? 'checked' : '' }}
                                                                {{ !old('types') && $types && in_array('snooker', $types) ? 'checked' : '' }}>
                                                    </div>
                                                    Снукер</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="types[]"
                                                                                    value="russian"
                                                                                    class="ez-hide"
                                                                {{ old('types') && in_array('russian', old('types')) ? 'checked' : '' }}
                                                                {{ !old('types') && $types && in_array('russian', $types) ? 'checked' : '' }}>
                                                    </div>
                                                    Русский бильярд</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="types[]"
                                                                                    value="caromball"
                                                                                    class="ez-hide"
                                                                {{ old('types') && in_array('caromball', old('types')) ? 'checked' : '' }}
                                                                {{ !old('types') && $types && in_array('caromball', $types) ? 'checked' : '' }}>
                                                    </div>
                                                    Карамболь</label>
                                            </div>
                                            <div class="help-block">
                                                @if($errors->has('types'))
                                                    {{ $errors->first('types') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="the_form_div">
                                        <div class="form-group field-profileform-time">
                                            <label class="control-label" for="profileform-time">Время игры (от)</label>
                                            <input type="time" id="profileform-time" class="form-control"
                                                   name="time_from" placeholder="От"
                                                   value="{{ $user->game_time_from }}">
                                            <label class="control-label" for="profileform-time">Время игры (до)</label>
                                            <input type="time" id="profileform-time" class="form-control"
                                                   name="time_to" placeholder="До" value="{{ $user->game_time_to }}">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>

                                    <div class="the_form_div">
                                        <div class="form-group field-profileform-days required">
                                            <label class="control-label">Дни игры</label>
                                            <input type="hidden" name="days" value="">
                                            <div id="profileform-days" multiple="multiple" placeholder="Дни игры"
                                                 options="{&quot;&quot;:{&quot;selected&quot;:true}}"><label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="days[]"
                                                                                    value="monday" class="ez-hide"
                                                                {{ old('days') && in_array('monday', old('days')) ? 'checked' : '' }}
                                                                {{ !old('days') && $days && in_array('monday', $days) ? 'checked' : '' }}>
                                                    </div>
                                                    Понедельник</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="days[]"
                                                                                    value="tuesday" class="ez-hide"
                                                                {{ old('days') && in_array('tuesday', old('days')) ? 'checked' : '' }}
                                                                {{ !old('days') && $days && in_array('tuesday', $days) ? 'checked' : '' }}>
                                                    </div>
                                                    Вторник</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="days[]"
                                                                                    value="wednesday" class="ez-hide"
                                                                {{ old('days') && in_array('wednesday', old('days')) ? 'checked' : '' }}
                                                                {{ !old('days') && $days && in_array('wednesday', $days) ? 'checked' : '' }}>
                                                    </div>
                                                    Среда</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="days[]"
                                                                                    value="thursday" class="ez-hide"
                                                                {{ old('days') && in_array('thursday', old('days')) ? 'checked' : '' }}
                                                                {{ !old('days') && $days && in_array('thursday', $days) ? 'checked' : '' }}>
                                                    </div>
                                                    Четверг</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="days[]"
                                                                                    value="friday" class="ez-hide"
                                                                {{ old('days') && in_array('friday', old('days')) ? 'checked' : '' }}
                                                                {{ !old('days') && $days && in_array('friday', $days) ? 'checked' : '' }}>
                                                    </div>
                                                    Пятница</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="days[]"
                                                                                    value="saturday" class="ez-hide"
                                                                {{ old('days') && in_array('saturday', old('days')) ? 'checked' : '' }}
                                                                {{ !old('days') && $days && in_array('saturday', $days) ? 'checked' : '' }}>
                                                    </div>
                                                    Суббота</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="days[]"
                                                                                    value="sunday" class="ez-hide"
                                                                {{ old('days') && in_array('sunday', old('days')) ? 'checked' : '' }}
                                                                {{ !old('days') && $days && in_array('sunday', $days) ? 'checked' : '' }}>
                                                    </div>
                                                    Воскресенье</label></div>

                                            <div class="help-block">
                                                @if($errors->has('days'))
                                                    {{ $errors->first('days') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="the_form_div">
                                        <div class="form-group field-profileform-payment required">
                                            <label class="control-label">Оплата</label>
                                            <input type="hidden" name="payment" value="">
                                            <div id="profileform-payment" multiple="multiple" placeholder="Оплата"
                                                 options="{&quot;&quot;:{&quot;selected&quot;:true}}"><label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="payment[]"
                                                                                    value="half" class="ez-hide"
                                                                {{ old('payment') && in_array('half', old('payment')) ? 'checked' : '' }}
                                                                {{ !old('payment') && $payment && in_array('half', $payment) ? 'checked' : '' }}>
                                                    </div>
                                                    Пополам</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="payment[]"
                                                                                    value="me" class="ez-hide"
                                                                {{ old('payment') && in_array('me', old('payment')) ? 'checked' : '' }}
                                                                {{ !old('payment') && $payment && in_array('me', $payment) ? 'checked' : '' }}>
                                                    </div>
                                                    Беру на себя</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="payment[]"
                                                                                    value="you" class="ez-hide"
                                                                {{ old('payment') && in_array('you', old('payment')) ? 'checked' : '' }}
                                                                {{ !old('payment') && $payment && in_array('you', $payment) ? 'checked' : '' }}>
                                                    </div>
                                                    За счет партнера</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="payment[]"
                                                                                    value="unimportant" class="ez-hide"
                                                                {{ old('payment') && in_array('unimportant', old('payment')) ? 'checked' : '' }}
                                                                {{ !old('payment') && $payment && in_array('unimportant', $payment) ? 'checked' : '' }}>
                                                    </div>
                                                    Не имеет значения</label></div>

                                            <div class="help-block">
                                                @if($errors->has('payment'))
                                                    {{ $errors->first('payment') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="form_subtitle">Сменить пароль</p>

                            <div class="form_sub_section">
                                <div class="the_form_col the_form_col_half">
                                    <div class="the_form_div">
                                        <div class="form-group field-profileform-oldpassword">

                                            <input type="password" id="profileform-oldpassword" class="form-control"
                                                   name="oldPassword" placeholder="Старый пароль">

                                            <div class="help-block">
                                                @if(session('falsePass'))
                                                    {{ session('falsePass') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="the_form_col the_form_col_half">
                                    <div class="the_form_div">
                                        <div class="form-group field-profileform-newpassword">

                                            <input type="password" id="profileform-newpassword" class="form-control"
                                                   name="newPassword" placeholder="Новый пароль">

                                            <div class="help-block">
                                                @if($errors->has('newPassword'))
                                                    {{ $errors->first('newPassword') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form_sub_section">
                                <div class="the_form_col the_form_col_half">
                                    <div class="the_form_div the_form_div_submit">
                                        <input type="submit" name="submit" value="Сохранить"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>

            </div>

        </section>

    </main>

@endsection
