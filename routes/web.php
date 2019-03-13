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

Route::get('/', function () {
    print 'FindBook API';
});


Route::middleware(['auth.basic','admin.only'])->group(function (){
    Route::get('/users','admin\UserController@index')->name('users');
    Route::get('/users/{usersId}/edit','admin\UserController@edit')->where(['usersId'=>'[0-9]+'])->name('users.edit');
    Route::post('/users/{usersId}','admin\UserController@update')->where(['usersId'=>'[0-9]+'])->name('users.update');

    Route::get('/books','admin\BookController@index')->name('books');
    Route::get('/books/{bookId}/edit','admin\BookController@edit')->where(['bookId'=>'[0-9]+'])->name('books.edit');
    Route::post('/books/{bookId}','admin\BookController@update')->where(['bookId'=>'[0-9]+'])->name('books.update');
});
