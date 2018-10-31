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
        </div>
    </div>

    <div class="the_form_div the_form_div_submit the_form_div_submit_and_forgot clearfix">
        <a class="forgot_password open-popup-forgot-password" href="#popup-forgot-password">
            Забыли пароль?
        </a>

        <input type="submit" name="submit" value="Войти">
    </div>
</form>
@push('modals')
    <div id="popup-forgot-password" class="white-popup mfp-hide">
        <form action="#" id="form-forgot-password">
            {{csrf_field()}}
            <h4>Восстановление пароля</h4>
            <div class="form-group">
                <input type="text" name="email" class="input_type_mail" placeholder="Введите e-mail">
            </div>
            <button type="submit">Восстановить</button>
        </form>
    </div>

    <div id="popup-forgot-password-success" class="white-popup mfp-hide">
        Письмо с ссылкой на восстановление пароля отправлено на почту.
    </div>
    <div id="popup-forgot-password-error" class="white-popup mfp-hide">
        Такой e-mail еще не зарегистрирован :(
    </div>
    @endpush
@push('scripts')
    <script>
        var popupForgotPasswordFormLink = $('.open-popup-forgot-password');
        var popupForgotPasswordForm = $('#popup-forgot-password');
        var popupForgotPasswordSuccess = $('#popup-forgot-password-success').magnificPopup({
            type:'inline',
        });


        var popupForgotPasswordError = $('#popup-forgot-password-error').magnificPopup({
            type:'inline',
        });

        popupForgotPasswordFormLink.magnificPopup({
            type:'inline',
            midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
        });
        $('#form-forgot-password').submit(function(e){
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            const _this = $(this);
            $.ajax({
                method: 'POST',
                url: '/password/forgot',
                dataType: "json",
                data: _this.serialize(),
                beforeSend: function( xhr){
                    popupForgotPasswordForm.html('Отправка...');
                },
                success: function(data) {
                    $.magnificPopup.close(popupForgotPasswordForm);
                    $.magnificPopup.open({
                        items: {
                            src: popupForgotPasswordSuccess,
                            type: 'inline'
                        }
                    });
                },
                error: function(data) {

                }
            });
        });
    </script>
    @endpush