<?php

// Event Routes

Route::get('/events/listall','EventController@getEventList')->name('get:all:events');

Route::get('/event/addnew','EventController@addEventForm')->name('get:event:formAdd');
Route::post('/event/save','EventController@saveEvent')->name('post:save:event');

Route::get('/event/edit/{id}','EventController@editEventForm')->name('get:event:formEdit');


Route::post('/event/removeImage','EventController@removeEventImage')->name('post:remove:event_image');

Route::post('/event/remove','EventController@removeEvent')->name('post:remove:event');


// Blog Routes

Route::get('/blogs/listall','BlogController@getBlogList')->name('get:all:blogs');

Route::get('/blog/addnew','BlogController@addBlogForm')->name('get:blog:formAdd');
Route::post('/blog/save','BlogController@saveBlog')->name('post:save:blog');

Route::get('/blog/edit/{id}','BlogController@editBlogForm')->name('get:blog:formEdit');


Route::post('/blog/removeImage','BlogController@removeBlogImage')->name('post:remove:blog_image');

Route::post('/blog/remove','BlogController@removeBlog')->name('post:remove:blog');



// news  Routes

Route::get('/news/listall','NewsController@getNewsList')->name('get:all:news');

Route::get('/news/addnew','NewsController@addNewsForm')->name('get:news:formAdd');
Route::post('/news/save','NewsController@saveNews')->name('post:save:news');

Route::get('/news/edit/{id}','NewsController@editNewsForm')->name('get:news:formEdit');


Route::post('/news/removeImage','NewsController@removeNewsImage')->name('post:remove:news_image');

Route::post('/news/remove','NewsController@removeNews')->name('post:remove:news');



