<?php

namespace App\Utils;


class R
{
    const COMMON_ERROR = "Ошибка сервера";

    const AUTH_NO_TOKEN = "Отсутствует параметр api_token";
    const AUTH_WRONG_TOKEN = "api_token не найден в базе";

    const USER_REGISTRATION_NOT_UNIQUE = "Пользователь с таким e-mail уже зарегистрирован";
    const USER_REGISTRATION_EMAIL_SUBJECT = 'Регистрация в приложении PoolBuddy';
    const USER_PASS_RESET_FAILURE = "Ты чо";
    const USER_LOGIN_FAILURE = "E-mail или пароль не верны";

    const MODEL_NOT_FOUND = "Модель не найдена";
}


