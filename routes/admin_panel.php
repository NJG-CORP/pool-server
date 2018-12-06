<?php

// Event Routes

Route::get('/events/listall','EventController@getEventList')->name('get:all:events');

Route::get('/event/addnew','EventController@addEventForm')->name('get:event:formAdd');
Route::post('/event/save','EventController@saveEvent')->name('post:save:event');

