<form id="login-form" class="frm1 not-ajax" action="/login" method="post">
    {{ csrf_field() }}
    <div class="the_form_div">
        <div class="form-group field-loginform-email required has-success">

            <input type="text" id="loginform-email" class="input_type_mail" name="email" placeholder="Email"
                   aria-required="true" aria-invalid="false">

            @if ($errors->has('email'))
                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
            @endif
        </div>
    </div>

    <div class="the_form_div">
        <div class="form-group field-loginform-password required has-success">

            <input type="password" id="loginform-password" class="input_type_password" name="password"
                   placeholder="Пароль" aria-required="true" aria-invalid="false">

            @if ($errors->has('password'))
                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
            @endif
            @if(session('success') && session('success') == 'password.sent')
                <span class="help-block">
                                                    <strong>Ссылка отправлена на email</strong>
                                                </span>
            @endif
        </div>
    </div>

    <div class="the_form_div the_form_div_submit the_form_div_submit_and_forgot clearfix">
        <a class="forgot_password open-popup-forgot-password" href="#popup-forgot-password">
            Забыли пароль?
        </a>

        <input type="submit" name="submit" value="Войти">
    </div>
</form>
@push('scripts')
    <script src="{{asset('js/signin.js')}}"></script>
@endpush