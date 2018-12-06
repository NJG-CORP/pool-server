<?php
Route::get('/users/show', 'UserController@getAllData')->name('get:users:data');
Route::get('/users/edit/{id}', 'UserController@edit')->name('get:users:edit');
Route::post('/users/update', 'UserController@update')->name('post:users:update');
Route::post('/users/delete', 'UserController@delete')->name('post:users:delete');