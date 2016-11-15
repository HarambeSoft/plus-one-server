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

// TODO: add auth middleware
Route::group(['prefix' => 'v1', /*'middleware' => 'auth:api'*/], function () {
    Route::resource('user',  'UserController');
    Route::resource('poll',  'PollController'); 


    Route::get('user/{id}/polls', 'UserController@polls');                      
    Route::get('user/{id}/notifications', 'UserController@notifications');

    Route::get('poll/{id}/comments',  'PollController@comments');
    Route::get('poll/{id}/options',   'PollController@options');
    Route::get('poll/globals',        'PollController@globals');
    
    Route::get('option/{option_id}',            'PollOptionController@option');
    Route::get('option/{option_id}/comments',   'PollOptionController@comments');

    Route::get('poll/option/{id}',          'PollOptionController@info');
    Route::get('poll/option/{id}/comments', 'PollOptionController@comments');
});