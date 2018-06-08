<?php

Route::middleware('cors')->group(function(){
    Route::prefix('auth')->group(function(){
        Route::post("login", "UserController@login");
        Route::post("register", "UserController@register");
        Route::post('reset', "UserController@makeResetPassword");
        Route::post('social', 'UserController@social');
        Route::get('vkinfo', 'UserController@vkInfo');
    });
    Route::middleware(['auth.token'])->prefix('devices')->group(function(){
        Route::post('ensure', 'UserController@ensureDevice');
    });
    Route::middleware(['auth.token'])->group(function(){
        Route::prefix('players')->group(function(){
            Route::get('self', 'PlayerController@selfInfo');
            Route::post('update', 'PlayerController@update');
            Route::get('search', 'PlayerController@search');
            Route::get('map', 'PlayerController@mapLocation');
            Route::get('show/{id}', 'PlayerController@show');
            Route::post('location', 'PlayerController@updateLocation');
        });
        Route::prefix('favourites')->group(function(){
            Route::get('list', 'FavouriteController@favouritePlayers');
            Route::post('add/{id}', 'FavouriteController@addFavouritePlayer');
            Route::post('remove/{id}', 'FavouriteController@removeFavouritePlayer');
        });
        Route::prefix('clubs')->group(function(){
            Route::get('list', 'ClubController@list');
            Route::get('{id}', 'ClubController@byId');
        });
        Route::prefix('rating')->group(function(){
            Route::post('player/{id}', 'RatingController@ratePlayer');
            Route::post('club/{id}', 'RatingController@rateClub');
        });
        Route::prefix('invitations')->group(function(){
            Route::get('list', 'InvitationController@invitationList');
            Route::post('send', 'InvitationController@inviteUser');
            Route::post('accept/{id}', 'InvitationController@invitationAccept');
            Route::post('reject/{id}', 'InvitationController@invitationReject');
            Route::post('delete/{id}', 'InvitationController@invitationDelete');
        });
        Route::prefix('cities')->group(function(){
            Route::get('search', 'CityController@search');
        });
        Route::prefix('taxonomies')->group(function(){
            Route::get('vocabularies', 'TaxonomyController@getVocabularies');
            Route::get('terms/{id}', 'TaxonomyController@getTerms');
        });
    });
});

