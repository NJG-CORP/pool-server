<?php

Route::get('/', 'Site\Hstatic\MainController@index');

Route::prefix('auth')->group(function() {
    Route::post("login", "Site\User\AuthController@login");
    Route::post("register", "Site\User\AuthController@register");
});