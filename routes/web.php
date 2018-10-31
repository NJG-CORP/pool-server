<?php

Route::group(['namespace' => 'Web'], function(){
    Route::group(['namespace' => 'Auth'], function(){
        Route::get('/login/vk/callback', 'LoginController@handleCallback');
        Route::get('/login/vk', 'LoginController@loginVk')->name('vk.auth');

        Route::post('/login', 'LoginController@login');
        Route::post('/register', 'RegisterController@register');

        Route::get('/logout', 'LoginController@logout');
        Route::get('/password/reset/{token}', 'PasswordResetController@showResetForm')->name('password.reset');
        Route::post('/password/forgot', 'PasswordResetController@sendMessage');
        Route::post('/password/reset', 'PasswordResetController@reset')->name('password.reset.post');
    });
    Route::get('/', 'HomeController@index');


});

