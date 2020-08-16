<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//public routes
Route::get('me', 'User\MeController@getMe');//get the authenticated user information when user token is passed.

//Users
Route::get('users', 'User\UserController@index');

/** ******* Articles ********* */

/**
 * Fetching all articles
 * */
Route::get('articles', 'Articles\ArticleController@index');

/**
 * Fetching a single article
 * Using route model binding - {article} variable matches what's in Route method
 * */
Route::get('articles/{article}', 'Articles\ArticleController@show');

/**
 * Fetching all by category
 * */
Route::get('articles/category/{category}', 'Articles\ArticlesByCategoryController');

/** ******* Categories ********* */
Route::apiResource('categories', 'Articles\CategoryController');

//Route group for authenticated user only
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    //User profile
    Route::put('settings/profile', 'User\UserSettingController@updateProfile');
    Route::put('settings/password', 'User\UserSettingController@updatePassword');

    /*
    * Using route model binding - {article} variable matches what's in Route method- uses slugs to match resources
    * https://youtu.be/XyyGG5qIWoQ
    */
    Route::put('articles/{article}', 'Articles\ArticleController@update');
    Route::post('articles/', 'Articles\ArticleController@store');
    Route::delete('articles/{article}', 'Articles\ArticleController@destroy');
});

//Route group for guest user only
Route::group(['middleware' => ['guest:api']], function () {

    //login and user verification
    Route::post('register', 'Auth\RegisterController@register'); // referring to the register method of the  register controller
    Route::post('verification/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify'); //Route for sending verification emails
    Route::post('verification/resend', 'Auth\VerificationController@resend');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');//Endpoint:"api/password/email". Using the "sendResetLinkEmail" method in ForgotPasswordController (pre-built controller in Laravel auth)
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');//Endpoint:"api/password/reset"
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
