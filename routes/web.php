<?php

Route::get('/', function () {
    return view('app');
});

// TODO: add auth middleware
Route::group(['prefix' => '/api/v1', /*'middleware' => 'auth'*/], function () {
    Route::resource('user',  'UserController');
    Route::resource('poll',  'PollController'); 
    
    Route::get('user/{id}/polls', 'UserController@polls');                      
    Route::get('user/{id}/notifications', 'UserController@notifications');      


    Route::get('poll/{id}/comments',  'PollController@comments');
    Route::get('poll/{id}/options',   'PollController@options');
    Route::get('poll/globals',        'PollController@globals');

    Route::get('poll/option/{id}',          'PollOptionController@info');
    Route::get('poll/option/{id}/comments', 'PollOptionController@comments');
});
