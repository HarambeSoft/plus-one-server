<?php

use App\Classes\Firebase;

Route::get('/', function () {
    return view('app');
});

Route::get('/test', function () {
    $device = 'evkQvzKflKo:APA91bENQeOtBGpqVLMfQRmBVY5y697daMQvlLpKN0aI6LGXJvDQeMXvb97CRWoL-yJcCHB2VR6OOtGva7NcC5EaYb-BW8TH0fCAfLrzlj31iXEHnT4R2jRVbjtVnvF2QJugL0_OCrNo';
    echo Firebase::sendNotification($device, 'title', 'here goes the body');
});


