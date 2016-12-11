<?php

use App\Classes\Firebase;
use App\Classes\AuthManager;

Route::get('/', function () {
    return view('app');
});

Route::get('/test', function () {
    echo AuthManager::login('newuser', '12345qwert');
});


