<?php

namespace App\Classes;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class Firebase {
    private static $fcm_send_url = 'https://fcm.googleapis.com/fcm/send';

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
        $result = $client->request('POST', self::$fcm_send_url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'key=' . $_ENV['FCM_APIKEY']
            ], 'json' => $json
        ]);
        
        return $result->getBody();
    }    
}


