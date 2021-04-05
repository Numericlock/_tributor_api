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

Route::group(["middleware" => "api"], function () {
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('/current_user', function () {
        return Auth::user();
    });
    Route::apiResource('/users', 'Api\UserController');
    Route::group(['middleware' => ['auth:api']], function () {
        //ここに認証が必要なパスを書いていく
//        /Route::apiResource('/users', 'Api\UserController')->except(['show']);
    });
});