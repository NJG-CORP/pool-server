<?php

Route::prefix('auth')->group(function(){
    Route::post("login", "UserController@login");
    Route::post("register", "UserController@register");
    Route::post('reset', "UserController@resetPassword");
});

Route::middleware(['auth.token'])->group(function(){
    Route::prefix('players')->group(function(){
        Route::get('favourites', 'PlayerController@favourite');
        Route::post('favourite/{id}', 'PlayerController@addFavouritePlayer');
        Route::post('unfavourite/{id}', 'PlayerController@removeFavouritePlayer');
        Route::post('rate/{id}', 'PlayerController@rate');
        Route::get('search', 'PlayerController@search');
        Route::get('show/{id}', 'PlayerController@show');
    });
    Route::prefix('clubs')->group(function(){
        Route::get('list', 'ClubController@list');
        Route::post('{id}', 'ClubController@byId');
        Route::post('rate/{id}', 'ClubController@rate');
    });
});

