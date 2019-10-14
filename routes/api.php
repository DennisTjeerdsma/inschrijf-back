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

Route::post('auth/login', 'AuthController@login');
Route::group(['middleware' => 'jwt.auth'], function(){
  Route::get('auth/user', 'AuthController@user');});Route::group(['middleware' => 'jwt.refresh'], function(){
  Route::get('auth/refresh', 'AuthController@refresh');
  Route::post('auth/logout', 'AuthController@logout');  
});

Route::prefix('auth')->group(function(){

    Route::post('reset-password', 'AuthController@sendPasswordResetLink');

    Route::post('reset/password', 'AuthController@callResetPassword');
});


// Event routes
Route::post('/makeevent', 'EventController@store');
Route::get('/eventlist/{userId}', 'EventController@list');
Route::patch('/event/{id}', 'EventController@update');
Route::delete('/event/{id}', 'EventController@destroy');
Route::patch('/setenroll/{eventId}', 'EventController@enroll');
Route::get('/event/{id}', 'EventController@load');


// User routes
Route::get('/userlist', 'UserController@load');
Route::get('/roleslist', 'UserController@loadroles');
Route::delete('/user/delete/{userId}', 'UserController@destroy');
Route::delete('/user/multidelete' ,'UserController@multidestroy');
Route::post('/user/create', 'UserController@create');
Route::patch('/user/patch/{userId}', 'UserController@patch');
Route::get('/user/{userId}', 'UserController@fetch');
Route::post('/image/upload', 'UserController@upload');