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
            $payload = $this->getPayload(['title' => 'Hello Testing', 'body' => 'Hello Testing Body'], 'android');
            return $this->firebase->sendNotification($serverKey, $payload);
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    /**
     * Use Firebase Access Token to execute the firebase CURL Request
     *  */
    public function withAccessToken()
    {
        $accessToken = $this->firebase->getAccessToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fcm.googleapis.com/v1/projects/push-notification-1ce0b/messages:send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "validate_only": true,
            "message":{
                "token":"fpxn7RvAQJiwPu7iqjFHys:APA91bE1RJkGESv2fsSRfzyIILwXd9Wxov0h8ArQhk8J9yB-Zn9fsGCYjXF-U-H4Q9Ee4mFlbpKGvwkPp7ljp2m_Qhbry4CvYknXNRjJ1Jtlx_NC8GBejayXto6w9gP1iqM44XdzNoTe",
                "notification":{
                "title":"FCM Message",
                "body":"This is an FCM notification message!"
                }
            }
            }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $accessToken . '',
                'Content-Type: application/json',
                'Accept: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        dd($response);

        try {
            $accessToken = $this->firebase->getAccessToken();
            dd($accessToken);
            $payload = $this->getPayload(['title' => 'Hello Testing IOS', 'body' => 'Hello Testing Body IOS'], 'ios');
            return $this->firebase->sendNotification($accessToken, $payload);
        } catch (\Throwable $th) {
            dd($th->getMessage(), $th->getLine());
        }
    }

    public function getPayload(array $notificationData, string $deviceType): string
    {
        $data = [
            'priority'     => 'high',
            'to'         => $this->device_token,
            'content_available'         =>  true,
            'mutable_content'           =>  true,
            'data'  => [
                'key'         => 'value',
            ],
            'notification'  => $notificationData,
        ];
        if ($deviceType == 'ios') {
            $data['sound'] = 'default';
        }

        return json_encode($data);
    }
}
