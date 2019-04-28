<form id="login-form" class="frm1" action="/user/login" method="post" data-pjax="">
    <input type="hidden" name="_csrf-frontend"
           value="{{csrf_token()}}">
    <div class="the_form_div">
        <div class="form-group field-loginform-email required has-success">

            <input type="text" id="loginform-email" class="input_type_mail" name="LoginForm[email]" placeholder="Email"
                   aria-required="true" aria-invalid="false">

            <div class="help-block"></div>
        </div>
    </div>

    <div class="the_form_div">
        <div class="form-group field-loginform-password required has-success">

            <input type="password" id="loginform-password" class="input_type_password" name="LoginForm[password]"
                   placeholder="Пароль" aria-required="true" aria-invalid="false">

            <div class="help-block"></div>
        </div>
    </div>

    <div class="the_form_div the_form_div_submit the_form_div_submit_and_forgot clearfix">
        <a class="forgot_password" href="/reset-password">
            Забыли пароль?
        </a>

        <input type="submit" name="submit" value="Войти">
    </div>
</form>