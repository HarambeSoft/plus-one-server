<?php


Route::get('/', function () {
    return view('app');
});

// TODO: add auth middleware
Route::group(['prefix' => '/api/v1', /*'middleware' => 'auth'*/], function () {
    Route::get('user/{id}',  'UserController@index');                           // General user info
    Route::get('user/{id}/polls', 'UserController@polls');                      // Polls created by user
    Route::get('user/{id}/notifications', 'UserController@notifications');      // User Notifications

    Route::get('poll/{id}',           'PollController@index');                  // Poll info with poll option infos
    Route::get('poll/{id}/comments',  'PollController@comments');
    Route::get('poll/globals',        'PollController@globals');

    Route::get('poll/option/{id}',          'PollOptionController@info');
    Route::get('poll/option/{id}/comments', 'PollOptionController@comments');
});

?>
