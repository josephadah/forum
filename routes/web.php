<?php

Route::get('/', 'ThreadsController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('/threads', 'ThreadsController', ['except' => ['show']]);
Route::get('/threads/{channel}', 'ThreadsController@index')->name('channel.show');
Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store')->name('replies.store');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show')->name('threads.show');
Route::delete('/threads/{channel}/{thread}', 
		'ThreadsController@destroy')
		->name('threads.delete');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')->middleware('auth');
Route::delete('/reply/{reply}', 'RepliesController@destroy')->name('replies.delete');
Route::patch('/reply/{reply}', 'RepliesController@update')->name('replies.update');
Route::get('/replies/{reply}/favorites', 'FavoritesController@show');
Route::post('/replies/{reply}/favorites', 'FavoritesController@store')->name('favorites.store');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy')->name('favorites.delete');

Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');
Route::get('/profiles/{user}/notifications', 'UserNotificationController@index');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationController@destroy');

Route::get('/register/confirm', 'Api\RegisterConfirmationsController@index')->name('register.confirm');

Route::get('api/users', 'Api\UsersController@index');
Route::post('api/users/{user}/avatar', 'Api\AvatarsController@store')->middleware('auth')->name('avatar');