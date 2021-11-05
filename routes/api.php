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

//protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', 'Api\AuthController@logout')->name('api.auth.logout');
    Route::get('/user', 'Api\UserController@userInfo')->name('api.user.getInfo');
});
