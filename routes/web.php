<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'ThreadsController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('/threads', 'ThreadsController', ['except' => ['show']]);
Route::get('/threads/{channel}', 'ThreadsController@index')->name('channel.show');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store')->name('replies.store');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show')->name('threads.show');
Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy')->name('threads.delete');
Route::delete('/reply/{reply}', 'RepliesController@destroy')->name('replies.delete');
Route::patch('/reply/{reply}', 'RepliesController@update')->name('replies.update');
Route::get('/replies/{reply}/favorites', 'FavoritesController@show');
Route::post('/replies/{reply}/favorites', 'FavoritesController@store')->name('favorites.store');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy')->name('favorites.delete');
Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');