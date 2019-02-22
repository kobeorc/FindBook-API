<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login','api\UserController@login');
Route::post('register','api\UserController@register');

//Route::get('/','App\Http\Controllers\UserController@index')->middleware(['auth:api']);

//Route::post('register');
//Route::post('auth');
//
Route::middleware(['custom.auth'])->group(function (){
    Route::get('profile','api\ProfileController@current');
    Route::get('profile/inventory','api\ProfileController@inventory');
    Route::get('profile/inventory/archive','api\ProfileController@archive');
    Route::post('profile/inventory/archive','api\ProfileController@putToArchive');
//    Route::resource('profile','\App\Http\Controllers\UserController');
//    Route::resource('profile/inventory','\App\Http\Controllers\BookController');
});


