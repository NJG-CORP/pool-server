<?php
// user part
Route::get('/users/show', 'UserController@getAllData')->name('get:users:data');
Route::get('/users/edit/{id}', 'UserController@edit')->name('get:users:edit');
Route::post('/users/update', 'UserController@update')->name('post:users:update');
Route::post('/users/delete', 'UserController@delete')->name('post:users:delete');

//rating part
Route::get('/rating/show', 'RatingController@getAllRating')->name('get:rating:data');
Route::get('/rating/edit/{id}', 'RatingController@edit')->name('get:rating:edit');
Route::post('/rating/update', 'RatingController@update')->name('post:rating:update');
Route::post('/rating/delete', 'RatingController@delete')->name('post:rating:delete');
//clubs part
Route::get('/clubs/create', 'ClubController@create')->name('get:club:create');
Route::post('/clubs/store', 'ClubController@store')->name('post:club:store');
Route::get('/clubs/show', 'ClubController@show')->name('get:club:data');
Route::get('/clubs/edit/{id}', 'ClubController@edit')->name('get:club:edit');
Route::post('/clubs/update', 'ClubController@update')->name('post:club:update');
Route::post('/clubs/delete', 'ClubController@delete')->name('post:club:delete');