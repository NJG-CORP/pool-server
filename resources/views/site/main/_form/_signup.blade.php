<div class="the_form">
    <div id="p0" data-pjax-container="" data-pjax-timeout="1000">
        <form id="signup-form" class="frm1" action="/user/signup" method="post" data-pjax="">
            <input type="hidden" name="_csrf-frontend"
                   value="{{ csrf_token() }}">
            <div class="the_form_div">
                <div class="form-group field-signupform-username required has-success">

                    <input type="text" id="signupform-username" class="input_type_name" name="SignupForm[username]"
                           placeholder="Имя" aria-required="true" aria-invalid="false">

                    <div class="help-block"></div>
                </div>
            </div>

            <div class="the_form_div">
                <div class="form-group field-signupform-email required">

                    <input type="text" id="signupform-email" class="input_type_mail" name="SignupForm[email]"
                           placeholder="E-mail" aria-required="true">

                    <div class="help-block"></div>
                </div>
            </div>

            <div class="the_form_div">
                <div class="form-group field-signupform-password required has-success">

                    <input type="password" id="signupform-password" class="input_type_password"
                           name="SignupForm[password]" placeholder="Пароль" aria-required="true" aria-invalid="false">

                    <div class="help-block"></div>
                </div>
            </div>


            <p class="note">
                Продолжая, вы подтверждаете, что прочитали и принимаете
                <a href="">Пользовательское Соглашение</a> и
                <a href="/site/politic">Политику
                    конфиденциальности</a>.
            </p>

            <div class="the_form_div the_form_div_submit clearfix">
                <input class="type2" type="submit" name="submit" value="Зарегистрироваться">
            </div>
        </form>
    </div>
</div>