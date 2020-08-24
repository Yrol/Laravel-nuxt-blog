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

/** ******* Users ********* */

/**
 * Get currently logged in user
 * */
Route::get('me', 'User\MeController@getMe');

/**
 * Fetching all users
 * */
Route::get('users', 'User\UserController@index');

/**
 * Fetching active users (users with articles)
 * */
Route::get('users/active', 'User\UsersWithArticlesController');

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
 * Fetching articles all by category
 * */
Route::get('articles/category/{category}', 'Articles\ArticlesByCategoryController');

/**
 * Fetching articles by user
 * */
Route::get('articles/user/{id}', 'Articles\ArticlesByUserController');

/** ******* Categories ********* */

/**
 * Fetching all categories
 * */
Route::get('categories', 'Articles\CategoryController@index');

/**
 * Fetching all active categories (ones consist of articles).
 * */
Route::get('categories/active', 'Articles\CategoriesWithArticlesController');

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

    /*
    * Commenting on the article
    * delete and update are using 'comments' route directly (instead of ex: 'articles') since these operation are common regardless of the model
    */
    Route::post('articles/{article}/comments', 'Articles\CommentsController@store');
    Route::delete('comments/{comment}', 'Articles\CommentsController@destroy');
    Route::put('comments/{comment}', 'Articles\CommentsController@update');

    Route::post('articles/{article}/comment', 'Articles\ArticleController@commentingArticle');

    /*
    * Likes and unlikes
    * Using one route for both like and unlike (if user has already liked will execute the like otherwise execute the like)
    */
    Route::post('articles/{article}/like', 'Articles\ArticleController@like');
    Route::get('articles/{article}/liked', 'Articles\ArticleController@hasUserLikedArticle');//check if user already liked the article
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
