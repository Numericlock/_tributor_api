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
    Route::post('/register', 'Auth\RegisterController@register');
   // Route::post('/logout', 'Auth\LoginController@loggedOut');
    Route::get('/home', 'Api\HomeController@home');
    Route::post('/home/before', 'Api\HomeController@get_before_posts');
    Route::get('/profile/{id}', 'Api\ProfileController@profile');
    Route::get('/lists', 'Api\ListsController@index');
    Route::post('/lists', 'Api\ListsController@lists_insert');
    Route::get('/lists/{id}', 'Api\ListsController@lists_member_post');
    Route::post('/post', 'Api\PostController@post');
    Route::get('/post/{id}', 'Api\PostController@get_post_detail');
    Route::post('/search/user', 'Api\SearchController@users_search');
    Route::post('/post/retribute', 'Api\RetributeController@retribute');
    Route::post('/post/retribute/remove', 'Api\RetributeController@remove');
    Route::post('/post/like', 'Api\FavoriteController@users_favorite');
    Route::post('/post/like/remove', 'Api\FavoriteController@remove');
    Route::get('/current_user', function () {
        return Auth::user();
    });
    Route::apiResource('/users', 'Api\UserController');
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::middleware('auth:api')->group(function() {
    });
});