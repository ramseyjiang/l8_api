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
Route::get('/', function (Request $request) {
    echo "Api request works.";
});

Route::post('/register', 'Api\AuthController@register')->name('api.auth.register');
Route::post('/login', 'Api\AuthController@login')->name('api.auth.login');
Route::post('/email/password/forgot', 'Api\EmailController@sendResetLinkEmail')->name('api.email.forgotPassword');
Route::post('/email/password/reset', 'Api\EmailController@passwordReset')->name('api.email.resetPassword');

//protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', 'Api\AuthController@logout')->name('api.auth.logout');
    Route::get('/getCurrentUser', 'Api\UserController@getCurrentUser')->name('api.user.getCurrentUser');
    Route::get('/users', 'Api\UserController@getAllUsers')->name('api.user.getAllUsers');
    Route::get('/users/{id}', 'Api\UserController@show')->where('id', '[0-9]+')->name('api.user.show');
});
