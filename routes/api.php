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
Route::post('register/silent','api\UserController@registerGuest');

Route::middleware(['custom.auth'])->group(function (){
    Route::get('books','api\BookController@index');
    Route::get('publishers','api\CreatorController@publisher');
    Route::get('profile','api\ProfileController@current');
    Route::post('profile','api\ProfileController@updateProfile');//IMAGE

    Route::get('profile/inventory','api\ProfileController@inventory');
    Route::get('profile/inventory/{bookId}','api\ProfileController@inventoryBook')->where(['bookId'=>'[0-9]']);
    Route::post('profile/inventory','api\ProfileController@putToInventory');//IMAGE

    Route::get('profile/inventory/archive','api\ProfileController@archive');
    Route::post('profile/inventory/archive','api\ProfileController@putToArchive');
    Route::delete('profile/inventory/archive/{bookId}','api\ProfileController@deleteFromArchive')->where(['bookId'=>'[0-9]+']);

    Route::get('profile/inventory/favorite','api\ProfileController@getFavorite');
    Route::post('profile/inventory/favorite','api\ProfileController@putToFavorite');
    Route::delete('profile/inventory/favorite/{bookId}','api\ProfileController@deleteFromFavorite')->where(['bookId'=>'[0-9]+']);

    Route::get('categories','api\CategoryController@index');
});