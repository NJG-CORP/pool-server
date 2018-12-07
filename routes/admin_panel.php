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
