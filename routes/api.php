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

Route::get('/', function (Request $request) {
    return response("Api request works.");
})->name('api.entrance');

//public routes
Route::post('/login', 'Api\AuthController@login')->name('api.auth.login');
Route::post('/register', 'Api\AuthController@register')->name('api.auth.register');
Route::post('/password/forgot', 'Api\PasswordController@forgotPassword')->name('api.password.forgot');
Route::post('/password/reset', 'Api\PasswordController@resetPassword')->name('api.password.reset');

//protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/email/verification', 'Api\EmailController@sendVerification')->name('api.email.verification');
    ROute::get('/email/verify/{id}/{hash}', 'Api\EmailController@verifyEmail')->name('api.email.verify');
    Route::post('/logout', 'Api\AuthController@logout')->name('api.auth.logout');
});

//Only email verified users can access routes below.
Route::middleware('auth:sanctum', 'verified')->group(function () {
    Route::get('/user', 'Api\UserController@userInfo')->name('api.user.getInfo');
});
