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
            Route::get('map', 'PlayerController@mapLocation');
            Route::get('show/{id}', 'PlayerController@show');
        });
        Route::prefix('favourite')->group(function(){
            Route::get('list', 'FavouriteController@favouritePlayers');
            Route::post('add/{id}', 'FavouriteController@addFavouritePlayer');
            Route::post('remove/{id}', 'FavouriteController@removeFavouritePlayer');
        });
        Route::prefix('clubs')->group(function(){
            Route::get('list', 'ClubController@list');
            Route::post('{id}', 'ClubController@byId');
            Route::post('rate/{id}', 'ClubController@rate');
        });
        Route::prefix('rating')->group(function(){
            Route::post('rate/player/{id}', 'RatingController@ratePlayer');
        });
        Route::prefix('invitation')->group(function(){
            Route::get('list', 'InvitationController@invitationList');
            Route::get('send', 'InvitationController@inviteUser');
        });
    });
});

