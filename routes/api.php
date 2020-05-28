<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('/v1/tasks',v1\TaskController::class,[
    'except'=>['create','edit'],
]);



Route::group(['prefix'=>'/v1/auth'],function (){
    Route::post('login','v1\AuthController@login');
    Route::post('signup','v1\AuthController@signup');
  
    Route::group(['middleware'=>'auth:api'],function(){
        Route::get('logout','v1\AuthController@logout');
        Route::get('user','v1\AuthController@user');
    });
});
