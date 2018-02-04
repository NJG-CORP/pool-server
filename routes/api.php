<?php

Route::middleware('cors')->group(function(){
    Route::prefix('auth')->group(function(){
        Route::post("login", "UserController@login");
        Route::post("register", "UserController@register");
        Route::post('reset', "UserController@resetPassword");
    });

    Route::middleware(['auth.token'])->group(function(){
        Route::prefix('players')->group(function(){
            Route::get('search', 'PlayerController@search');
            Route::get('show/{id}', 'PlayerController@show');
        });
        Route::prefix('favourite')->group(function(){
            Route::get('favourites', 'FavouriteController@favouritePlayers');
            Route::post('favourite/{id}', 'FavouriteController@addFavouritePlayer');
            Route::post('unfavourite/{id}', 'FavouriteController@removeFavouritePlayer');
        });
        Route::prefix('clubs')->group(function(){
            Route::get('list', 'ClubController@list');
            Route::post('{id}', 'ClubController@byId');
            Route::post('rate/{id}', 'ClubController@rate');
        });
        Route::prefix('rating')->group(function(){
            Route::post('rate/player/{id}', 'RatingController@ratePlayer');
        });
    });
});

