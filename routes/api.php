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

Route::post('login','Api\UserController@login');
Route::post('register','Api\UserController@register');
Route::post('register/silent','Api\UserController@registerGuest');

Route::middleware(['api.custom.auth'])->group(function (){

    Route::get('search','Api\SearchController@index');
    Route::get('categories','Api\CategoryController@index');
    Route::get('publishers','Api\CreatorController@publisher');
    Route::get('books','Api\BookController@index');
    Route::get('books/{bookId}','Api\BookController@show');

    Route::get('profile','Api\ProfileController@current');
    Route::post('profile','Api\ProfileController@updateProfile');

    Route::get('profile/inventory','Api\ProfileController@inventory');
    Route::post('profile/inventory','Api\ProfileController@putToInventory');
    Route::delete('profile/inventory/{bookId}','Api\ProfileController@deleteFromInventory')->where(['bookId'=>'[0-9]']);
    Route::delete('profile/inventory/{bookId}/images/{imageId}','Api\ProfileController@deleteImageFromBook')->where(['bookId'=>'[0-9]','imageId'=>'[0-9]']);

    Route::get('profile/inventory/archive','Api\ProfileController@archive');
    Route::post('profile/inventory/archive','Api\ProfileController@putToArchive');
    Route::delete('profile/inventory/archive/{bookId}','Api\ProfileController@deleteFromArchive')->where(['bookId'=>'[0-9]+']);

    Route::get('profile/inventory/favorite','Api\ProfileController@getFavorite');
    Route::post('profile/inventory/favorite','Api\ProfileController@putToFavorite');
    Route::delete('profile/inventory/favorite/{bookId}','Api\ProfileController@deleteFromFavorite')->where(['bookId'=>'[0-9]+']);
});