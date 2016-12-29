<?php

namespace App\Classes;

use Firebase\FirebaseLib;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class Firebase {
    const FDB_URL = 'https://plus-one-59ee5.firebaseio.com/';
    const FCM_SEND_URL = 'https://fcm.googleapis.com/fcm/send';

    public static function sendNotificationToUser($name, $title, $body, $data=[]) {
        return self::sendNotification('/topics/user_' . $name, $title, $body, $data);
    }

    public static function sendNotification($to, $title, $body, $data=[]) {
        $json = [
            'to' => $to,
            'notification' => [
                'title' => $title,
                'body' => $body
                ]
        ];
        
        if (!empty($data)) {
            $json['data'] = $data;
        }
        
        $client = new Client();
        $result = $client->request('POST', Firebase::FCM_SEND_URL, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'key=' . $_ENV['FCM_APIKEY']
            ], 'json' => $json
        ]);
        
        return $result->getBody();
    }

    public static function findNearUsers($lat, $long, $rad) {
        //TODO: use firebase queries to reduce load on server
        $near_users = [];

        $firebase = new FirebaseLib(Firebase::FDB_URL, $_ENV['FDB_TOKEN']);
        $locations = $firebase->get('/locations' /*, ['orderBy' => '"height"']*/);
        $locations = json_decode($locations);
        //echo $locations;

        $R = 6371;  // earth's mean radius, km

        // first-cut bounding box (in degrees)
        $maxLat = $lat + rad2deg($rad/$R);
        $minLat = $lat - rad2deg($rad/$R);
        $maxLong = $long + rad2deg(asin($rad/$R) / cos(deg2rad($lat)));
        $minLong = $long - rad2deg(asin($rad/$R) / cos(deg2rad($lat)));


        foreach ($locations as $user_id => $location) {
            if (($location->lat > $minLat && $location->lat < $maxLat) &&
                ($location->long > $minLong && $location->long < $maxLong)) {
                $near_users[] = $user_id;
            }
        }

        return $near_users;
    }


}




