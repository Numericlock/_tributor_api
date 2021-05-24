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

Route::post('login', 'Api\UserController@login');
Route::post('register', 'Api\UserController@register');
Route::post('exists/email', 'Api\UserController@email_exists');
Route::post('exists/user_id', 'Api\UserController@user_id_exists');

// 下記のgroupの下には認証が必要
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'Api\UserController@details');
    Route::post('/home', 'Api\HomeController@home');
    Route::post('/home/before', 'Api\HomeController@get_before_posts');
    Route::get('/profile/{id}', 'Api\ProfileController@profile');
    Route::get('/profile/posts/{id}', 'Api\ProfileController@user_posts');
    Route::get('/profile/media/{id}', 'Api\ProfileController@user_media_posts');
    Route::get('/profile/reply/{id}', 'Api\ProfileController@user_reply_posts');
    Route::get('/profile/like/{id}', 'Api\ProfileController@user_like_posts');
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
});


Route::group(["middleware" => "api"], function () {
    //Route::post('/login', 'Auth\LoginController@login');
    //Route::post('/register', 'Auth\RegisterController@register');
   // Route::post('/logout', 'Auth\LoginController@loggedOut');
    

    Route::get('/current_user', function () {
        return Auth::user();
    });
    Route::apiResource('/users', 'Api\UserController');
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::middleware('auth:api')->group(function() {
    });
});

