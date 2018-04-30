<?php

namespace App\Utils;


class R
{
    const COMMON_ERROR = "Ошибка сервера";

    const AUTH_NO_TOKEN = "Отсутствует параметр api_token";
    const AUTH_WRONG_TOKEN = "api_token не найден в базе";

    const USER_REGISTRATION_NOT_UNIQUE = "Пользователь с таким e-mail уже зарегистрирован";
    const USER_REGISTRATION_EMAIL_SUBJECT = 'Регистрация в приложении PoolBuddy';
    const USER_PASS_RESET_FAILURE = "Ошибка отправки письма на указанный адрес";
    const USER_LOGIN_FAILURE = "E-mail или пароль не верны";
    const USER_REGISTERED_WITH_NO_SOCIAL = "Вы зарегистрированы другим способом";

    const MODEL_NOT_FOUND = "Модель не найдена";

    const RATING_SAME_MODEL = "Нельзя повысить рейтинг самому себе";

    const FAVOURITE_SAME_PLAYER = "Этот игрок уже есть в избранном";
}


