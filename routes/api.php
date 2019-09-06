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

Route::post('auth/login', 'APILoginController@login');

Route::middleware('jwt.auth')->get('auth/user', function() {
    return auth('api')->user();
});

Route::post('/makeevent', 'EventController@store');
Route::get('/eventlist/{userId}', 'EventController@list');
Route::patch('/event/{id}', 'EventController@update');
Route::delete('/event/{id}', 'EventController@destroy');
Route::patch('/setenroll/{eventId}/{userId}', 'EventController@enroll');

Route::prefix('auth')->group(function(){

    Route::post('reset-password', 'APILoginController@sendPasswordResetLink');

    Route::post('reset/password', 'APILoginController@callResetPassword');
});
