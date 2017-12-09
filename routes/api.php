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


Route::group(
    ['prefix' => 'v1'], function () {
    Route::group(
        ['namespace' => 'App\Auth'], function () {
        Route::get('login/{service}', 'LoginController@redirectToProvider');
        Route::get('login/{service}/callback', 'LoginController@handleProviderCallback');
        Route::post('login', 'LoginController@store');
        Route::post('register', 'RegisterController@store');

    });
    Route::group(
        ['namespace' => 'App\OAuth'], function () {

    });
    Route::group(
        ['namespace' => 'App\Users'], function () {
        Route::resource('users', 'UsersController');
        Route::resource('user_autocomplete', 'AutoCompleteController');
    });
    Route::group(
        ['namespace' => 'App\Entity'], function () {
        Route::resource('entity', 'EntityController');
    });
    Route::group(
        ['namespace' => 'App\Top'], function () {
        Route::resource('top', 'TopController');
    });
    Route::group(
        ['namespace' => 'App\Comments'], function () {
        Route::resource('comment', 'CommentsController');
    });
    Route::group(
        ['namespace' => 'App\Messages'], function () {
        Route::resource('messages', 'MessagesController');
    });
    Route::group(
        ['namespace' => 'App\Ratings'], function () {
        Route::resource('rate', 'RatingsController');
    });
    Route::group(
        ['namespace' => 'App\Search'], function () {
        Route::resource('search', 'SearchController');
    });

});



