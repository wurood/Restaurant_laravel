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

Route::post('/login', 'AuthenticationController@login')->name('login');
Route::post('register', 'AuthenticationController@register');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('hotel', 'HotelsController@create');
Route::get('hotels', 'HotelsController@list');
Route::get('hotel/{id}', 'HotelsController@show');

Route::post('room', 'RoomsController@create');
Route::get('rooms/{id}', 'RoomsController@show');

Route::post('reserve', 'reservationHotelsController@create');
Route::post('release', 'reservationHotelsController@release');

