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

//Route group for authenticated user only
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout', 'Auth\LoginController@logout');
});

//Route group for guest user only
Route::group(['middleware' => ['guest:api']], function () {
    Route::post('register', 'Auth\RegisterController@register'); // referring to the register method of the  register controller
    Route::post('verification/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify'); //Route for sending verification emails
    Route::post('verification/resend', 'Auth\VerificationController@resend');
    Route::post('login', 'Auth\LoginController@login');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
