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
    });
});

