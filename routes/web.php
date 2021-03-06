<?php

Route::group(['namespace' => 'Web'], function(){
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/search', 'SearchController@index')->name('search');
    Route::post('/search', 'SearchController@search')->name('search-execute');
    Route::get('/contacts', 'ContactsController@index')->name('contacts');
    Route::post('/contacts', 'ContactsController@review')->name('send.review');

    Route::group(['namespace' => 'Events'], function () {
        Route::get('/events', 'MainController@list')->name('events');
        Route::get('/events/{id}', 'MainController@viewId')->where('id', '[0-9]+')->name('eventItem');
        Route::get('/events/{url}', 'MainController@view');
    });

    Route::group(['namespace' => 'News'], function () {
        Route::get('/news', 'MainController@list')->name('news');
        Route::get('/news/{id}', 'MainController@viewId')->where('id', '[0-9]+')->name('news.show.id');
        Route::get('/news/{url}', 'MainController@view')->name('news.show');;
    });
    Route::group(['namespace' => 'Blog'], function () {
        Route::get('/blog', 'MainController@list')->name('blog');
        Route::get('/blog/{id}', 'MainController@viewId')->where('id', '[0-9]+')->name('blog.show.id');
        Route::get('/blog/{url}', 'MainController@view')->name('blog.show');;
    });
    Route::group(['namespace' => 'Club'], function () {
        Route::get('/clubs', 'MainController@list')->name('clubs');
        Route::post('/clubs', 'MainController@list')->name('clubs');
        Route::get('/clubs/{id}', 'MainController@viewId')->where('id', '[0-9]+')->name('club.show.id');
        Route::get('/clubs/{url}', 'MainController@view')->name('club.show');;
        Route::get('/clubs/find/{name}', 'MainController@suggestClub')->name('club.suggestion');
        Route::get('/clubs/list/{city}', 'MainController@searchClubs')->name('club.search');
    });

    Route::group(['namespace' => 'Auth'], function(){
        Route::get('/login/vk/callback', 'LoginController@handleCallback');
        Route::get('/login/fb/callback', 'LoginController@handleCallbackFb');
        Route::get('/login/vk', 'LoginController@loginVk')->name('vk.auth');
        Route::get('/login/fb', 'LoginController@loginFb')->name('fb.auth');

        Route::post('/login', 'LoginController@login');
        Route::get('/login/as/{userId}', 'AsController@loginAs');
        Route::post('/register', 'RegisterController@register');

        Route::get('/logout', 'LoginController@logout')->name('logout');
        Route::get('/password/reset/{token}', 'PasswordResetController@showResetForm')->name('password.reset');
        Route::post('/password/forgot', 'PasswordResetController@sendMessage')->name('password.reset.email');
        Route::post('/password/reset', 'PasswordResetController@reset')->name('password.reset.post');
    });

    Route::group(['middleware' => 'authenticated'], function(){
        Route::post('/rate/club/{club_id}', 'RatingController@rateClub')->name('rate.club');

        Route::group(['namespace' => 'User'], function() {
            Route::get('/profile', 'ProfileController@index')->name('profile.index');
            Route::get('/user/profile', 'ProfileController@card')->name('profile.card');
            Route::get('/user/profile/{id}', 'ProfileController@card')->name('profile.card.other');
            Route::get('/invites', 'ProfileController@invites')->name('profile.invites');
            Route::get('/partners', 'ProfileController@partners')->name('profile.partners');
            Route::get('/chat/{id}', 'ChatController@chat')->name('chat.withUser');
            Route::get('/chat', 'ChatController@chat')->name('chat');

            Route::post('/profile', 'ProfileController@updateProfile')->name('profile.update');
        });
    });
});
Route::group(['middleware' => 'authenticated'], function() {

    Route::post('/invite', 'InvitationController@inviteUser')->name('send.invite');
    Route::get('/invite/accept/{id}', 'InvitationController@invitationAccept')->name('accept.invite');
    Route::get('/invite/decline/{id}', 'InvitationController@invitationReject')->name('accept.reject');

});