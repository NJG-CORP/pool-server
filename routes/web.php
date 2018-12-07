<?php

Route::group(['namespace' => 'Web'], function(){
    Route::get('/', 'HomeController@index');
    Route::post('/search', 'SearchController@search')->name('search');
    Route::get('/contacts', 'ContactsController@index')->name('contacts');
    Route::post('/contacts', 'ContactsController@review')->name('send.review');
    Route::get('/news', 'NewsController@news')->name('news');
    Route::get('/news/{id}', 'NewsController@showNews')->name('news.show');
    Route::get('/blog', 'BlogController@blog')->name('blog');
    Route::get('/blog/{id}', 'BlogController@showBlog')->name('blog.show');


    Route::group(['namespace' => 'Auth'], function(){
        Route::get('/login/vk/callback', 'LoginController@handleCallback');
        Route::get('/login/vk', 'LoginController@loginVk')->name('vk.auth');

        Route::post('/login', 'LoginController@login');
        Route::post('/register', 'RegisterController@register');

        Route::get('/logout', 'LoginController@logout')->name('logout');
        Route::get('/password/reset/{token}', 'PasswordResetController@showResetForm')->name('password.reset');
        Route::post('/password/forgot', 'PasswordResetController@sendMessage');
        Route::post('/password/reset', 'PasswordResetController@reset')->name('password.reset.post');

    });

    Route::group(['middleware' => 'authenticated'], function(){
        Route::group(['namespace' => 'User'], function() {
            Route::get('/profile', 'ProfileController@index')->name('profile.index');
            Route::get('/user/profile', 'ProfileController@card')->name('profile.card');
            Route::get('/invites', 'ProfileController@invites')->name('profile.invites');
            Route::get('/partners', 'ProfileController@partners')->name('profile.partners');
            Route::get('/chat', 'ProfileController@chat')->name('profile.chat');

            Route::post('/profile', 'ProfileController@updateProfile')->name('profile.update');
        });
    });
});
Route::group(['middleware' => 'authenticated'], function() {

    Route::post('/invite', 'InvitationController@inviteUser')->name('send.invite');
    Route::get('/invite/accept/{id}', 'InvitationController@invitationAccept')->name('accept.invite');

});




