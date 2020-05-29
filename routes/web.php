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
    return view('welcome');
});

Route::group(['middleware' => ['web']], function () {
    Auth::routes();

    Route::get('/home', 'HomeController@index')->name('home');
    
    Route::get('/create-task','TaskController@create')->name('create_task');
    Route::post('/create-task','TaskController@store')->name('store_task');

    Route::get('/edit-task/{id}','TaskController@edit')->name('edit_task');
    Route::get('/delete-task/{id}','TaskController@show')->name('delete_task');

    Route::post('/update-task/{id}','TaskController@update')->name('update_task');
    Route::post('/destroy-task/{id}','TaskController@destroy')->name('destroy_task');
});


