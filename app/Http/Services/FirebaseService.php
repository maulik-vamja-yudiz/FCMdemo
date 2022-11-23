<?php

namespace App\Http\Services;

use Google\Client;

class FirebaseService
{
    public $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(base_path('google-client.json'));
        $this->client->addScope("https://www.googleapis.com/auth/firebase.messaging");
        $this->client->refreshTokenWithAssertion();
    }

    public function getAccessToken()
    {
        $data = $this->client->getAccessToken();
        return $data['access_token'];
    }

    public function sendNotification($authKey, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization: key=' . $authKey]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }
}
