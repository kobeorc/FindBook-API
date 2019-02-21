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

//Route::get('/','App\Http\Controllers\UserController@index')->middleware(['auth:api']);

//Route::post('register');
//Route::post('auth');
//
//Route::middleware(['auth:api'])->group(function (){
//    Route::resource('profile','\App\Http\Controllers\UserController');
//    Route::resource('profile/inventory','\App\Http\Controllers\BookController');
//});


Route::get('profile/inventory','api\ProfileController@inventory');