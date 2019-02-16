<?php

// Event Routes

Route::group(['middleware' => 'admin'], function () {
    Route::get('/events/listall', 'EventController@getEventList')->name('get:all:events');

    Route::get('/event/addnew', 'EventController@addEventForm')->name('get:event:formAdd');
    Route::post('/event/save', 'EventController@saveEvent')->name('post:save:event');

    Route::get('/event/edit/{id}', 'EventController@editEventForm')->name('get:event:formEdit');


    Route::post('/event/removeImage', 'EventController@removeEventImage')->name('post:remove:event_image');

    Route::post('/event/remove', 'EventController@removeEvent')->name('post:remove:event');


// Blog Routes

    Route::get('/blogs/listall', 'BlogController@getBlogList')->name('get:all:blogs');

    Route::get('/blog/addnew', 'BlogController@addBlogForm')->name('get:blog:formAdd');
    Route::post('/blog/save', 'BlogController@saveBlog')->name('post:save:blog');

    Route::get('/blog/edit/{id}', 'BlogController@editBlogForm')->name('get:blog:formEdit');


    Route::post('/blog/removeImage', 'BlogController@removeBlogImage')->name('post:remove:blog_image');

    Route::post('/blog/remove', 'BlogController@removeBlog')->name('post:remove:blog');


// news  Routes

    Route::get('/news/listall', 'NewsController@getNewsList')->name('get:all:news');

    Route::get('/news/addnew', 'NewsController@addNewsForm')->name('get:news:formAdd');
    Route::post('/news/save', 'NewsController@saveNews')->name('post:save:news');

    Route::get('/news/edit/{id}', 'NewsController@editNewsForm')->name('get:news:formEdit');


    Route::post('/news/removeImage', 'NewsController@removeNewsImage')->name('post:remove:news_image');

    Route::post('/news/remove', 'NewsController@removeNews')->name('post:remove:news');

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
    Route::post('/rating/accept', 'RatingController@accept')->name('post:rating:accept');
//clubs part
    Route::get('/clubs/create', 'ClubController@create')->name('get:club:create');
    Route::post('/clubs/store', 'ClubController@store')->name('post:club:store');
    Route::get('/clubs/show', 'ClubController@show')->name('get:club:data');
    Route::get('/clubs/edit/{id}', 'ClubController@edit')->name('get:club:edit');
    Route::post('/clubs/update', 'ClubController@update')->name('post:club:update');
    Route::post('/clubs/delete', 'ClubController@delete')->name('post:club:delete');
});