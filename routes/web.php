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

Route::get('/test', 'Api\ChatController@list');
Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

// --- LET VUE HANDLE ROUTING ---
Route::get('/{route_name?}', 'HomeController@index')->where('route_name', '^(?!(api)).*$');
