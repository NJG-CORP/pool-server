@extends('layouts.default')

@section('content')

    <main class="main inner_page_main profile_inner_page_main">

        <section class="the_content_section">
            <div class="inner_section">

                <div class="breadcrumbs">
                    <p><a href="/">Главная</a> / <span>Мой профиль</span></p>
                </div>

                <h1>Мой профиль</h1>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="profile_page_block">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        <div class="the_form the_players_search_form profile_form clearfix">

                            <div class="profile_page_avatar">
                                <div class="img">
                                    <img src="{{ asset('img/player1.png') }}" alt="">
                                </div>

                                <div class="text">
                                    <p class="name">Fake</p>
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
                                                   name="phone" placeholder="Телефон" aria-invalid="false" value="{{ $user->phone }}">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="the_form_div">
                                        <div class="form-group field-profileform-email has-success">
                                            <label class="control-label" for="profileform-email">Email</label>
                                            <input type="text" id="profileform-email" class="form-control"
                                                   name="email" value="{{ $user->email }}"
                                                   placeholder="E-mail"
                                                   aria-invalid="false">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>

                                    <div class="the_form_div">
                                        <div class="form-group field-profileform-age required has-error">
                                            <label class="control-label" for="profileform-age">Возраст</label>
                                            <input type="number" id="profileform-age" class="form-control"
                                                   name="age" placeholder="Возраст" aria-required="true"
                                                   aria-invalid="true" value="{{ $user->age }}">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="the_form_div">
                                        <div class="form-group field-profileform-location">
                                            <label class="control-label" for="profileform-location">Город,
                                                страна</label>
                                            <input type="text" id="profileform-location" class="form-control"
                                                   name="location" value=""
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
                                                   name="houselocation" placeholder="Квартира">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="the_form_div">
                                        <div class="form-group field-profileform-sex required">
                                            <label class="control-label">Пол</label>
                                            <input type="hidden" name="sex" value="">
                                            <div id="profileform-sex" aria-required="true"><label>
                                                    <div class="ez-radio"><input type="radio" name="sex"
                                                                                 value="0" class="ez-hide"></div>
                                                    Мужчина</label>
                                                <label>
                                                    <div class="ez-radio"><input type="radio" name="sex"
                                                                                 value="1" class="ez-hide"></div>
                                                    Женщина</label></div>
                                            <div class="help-block"></div>
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
                                                                                    value="pool" class="ez-hide"></div>
                                                    Пул</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="types[]"
                                                                                    value="snooker" class="ez-hide">
                                                    </div>
                                                    Снукер</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="types[]"
                                                                                    value="rus" class="ez-hide"></div>
                                                    Русский бильярд</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="types[]"
                                                                                    value="carambol" class="ez-hide">
                                                    </div>
                                                    Карамболь</label></div>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>

                                    <div class="the_form_div">
                                        <div class="form-group field-profileform-time">
                                            <label class="control-label" for="profileform-time">Время игры</label>
                                            <input type="time" id="profileform-time" class="form-control"
                                                   name="time" placeholder="Время игры">

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
                                                                                    value="monday" class="ez-hide">
                                                    </div>
                                                    Понедельник</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="days[]"
                                                                                    value="tuesday" class="ez-hide">
                                                    </div>
                                                    Вторник</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="days[]"
                                                                                    value="wednesday" class="ez-hide">
                                                    </div>
                                                    Среда</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="days[]"
                                                                                    value="thursday" class="ez-hide">
                                                    </div>
                                                    Четверг</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="days[]"
                                                                                    value="friday" class="ez-hide">
                                                    </div>
                                                    Пятница</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="days[]"
                                                                                    value="saturday" class="ez-hide">
                                                    </div>
                                                    Суббота</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="days[]"
                                                                                    value="sunday" class="ez-hide">
                                                    </div>
                                                    Воскресенье</label></div>

                                            <div class="help-block"></div>
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
                                                                                    value="0" class="ez-hide"></div>
                                                    Пополам</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="payment[]"
                                                                                    value="1" class="ez-hide"></div>
                                                    Беру на себя</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="payment[]"
                                                                                    value="2" class="ez-hide"></div>
                                                    За счет партнера</label>
                                                <label>
                                                    <div class="ez-checkbox"><input type="checkbox"
                                                                                    name="payment[]"
                                                                                    value="3" class="ez-hide"></div>
                                                    Не имеет значения</label></div>

                                            <div class="help-block"></div>
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

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="the_form_col the_form_col_half">
                                    <div class="the_form_div">
                                        <div class="form-group field-profileform-newpassword">

                                            <input type="password" id="profileform-newpassword" class="form-control"
                                                   name="newPassword" placeholder="Новый пароль">

                                            <div class="help-block"></div>
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
