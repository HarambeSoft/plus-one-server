<?php

use App\Classes\Firebase;
use App\Classes\AuthManager;

Route::get('/', function () {
    return view('app');
});

Route::get('/notify/{name}', function ($name) {
    echo Firebase::sendNotificationToUser($name, "Foo", "barat", ['poll_id' => 110, 'foo' => 'bar']);
});

Route::get('/fdb', function () {
    echo json_encode(Firebase::findNearUsers(39.7862387,30.5122116, 100000000000000));
});


