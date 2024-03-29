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

Route::view('/', 'welcome');


Route::middleware(['auth.basic','admin.only'])->group(function (){
    Route::get('/users','Admin\UserController@index')->name('users');
    Route::get('/users/{usersId}/edit','Admin\UserController@edit')->where(['usersId'=>'[0-9]+'])->name('users.edit');
    Route::post('/users/{usersId}','Admin\UserController@update')->where(['usersId'=>'[0-9]+'])->name('users.update');

    Route::get('/books','Admin\BookController@index')->name('books');
    Route::get('/books/{bookId}/edit','Admin\BookController@edit')->where(['bookId'=>'[0-9]+'])->name('books.edit');
    Route::post('/books/{bookId}','Admin\BookController@update')->where(['bookId'=>'[0-9]+'])->name('books.update');

    Route::get('category','Admin\CategoryController@index')->name('category');
    Route::get('category/create','Admin\CategoryController@create')->name('category.create');
    Route::post('category','Admin\CategoryController@store')->name('category.store');
    Route::get('category/{categoryId}','Admin\CategoryController@edit')->where(['categoryId'=>'[0-9]+'])->name('category.edit');
    Route::post('category/{categoryId}','Admin\CategoryController@update')->where(['categoryId'=>'[0-9]+'])->name('category.update');
    Route::delete('category/{categoryId}','Admin\CategoryController@destroy')->where(['categoryId'=>'[0-9]+'])->name('category.destroy');
    Route::post('category/{categoryId}/restore','Admin\CategoryController@restore')->where(['categoryId'=>'[0-9]+'])->name('category.restore');
});
