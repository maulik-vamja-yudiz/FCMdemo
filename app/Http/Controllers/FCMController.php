<?php

namespace App\Http\Controllers;

use App\Http\Services\FirebaseService;
use Google\Client;

class FCMController extends Controller
{

    private $device_token = 'aaaaaaaaaaaaaaaaaaaaaa';
    public $firebase;

    public function __construct()
    {
        $this->firebase = new FirebaseService();
    }
    /**
     * Use Firebase Server key to execute the firebase CURL Request
     *  */
    public function withServerKey()
    {
        try {
            $serverKey = 'AAAA2tVjGAU:APA91bHyqvH3FvXj_zatzlRWW6FF1etSVunV5VJIE8bHEzSGWxxY2k8bg2mW8UG45DRGfmFyhhskXDybK-8D3_KG-zLXHKGkeHHh16FniMdkcByEi00gYeoSNCp6oA2SleRgVaPmh7iB';

            return $this->firebase->sendNotification($serverKey, $this->device_token);
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    /**
     * Use Firebase Access Token to execute the firebase CURL Request
     *  */
    public function withAccessToken()
    {
        try {
            $accessToken = $this->firebase->getAccessToken();
            return $this->firebase->sendNotification($accessToken, $this->device_token);
        } catch (\Throwable $th) {
            dd($th->getMessage(), $th->getLine());
        }
    }

    public function getNotificationData($deviceType)
    {
        $data = [
            'priority'     => 'high',
            'to'         => $this->device_token,
            'content_available'         =>  true,
            'mutable_content'           =>  true,
        ];
        if ($deviceType == 'ios') {
        }
    }
}
