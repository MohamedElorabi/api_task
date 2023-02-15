<?php

use Illuminate\Support\Facades\Route;

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

//Route::get('/', function () {
//    return view('welcome');
//});


Route::group(['namespace'=>'Dashboard','prefix'=>'admin'], function () {

    Route::get('/home', 'HomeController@index')->name('admin.home');

################################################ brands routes################################################
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'UserController@index')->name('admin.users');
        Route::get('create', 'UserController@create')->name('admin.users.create');
        Route::post('store', 'UserController@store')->name('admin.users.store');
        Route::get('edit/{id}', 'UserController@edit')->name('admin.users.edit');
        Route::post('update/{id}', 'UserController@update')->name('admin.users.update');
        Route::get('delete/{id}', 'UserController@destroy')->name('admin.users.delete');

    });
################################################ end brands   ###############################################
});
