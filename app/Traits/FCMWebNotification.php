<?php

namespace App\Traits;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

trait FCMWebNotification
{
    public function sendWebNotification($device_key, $title, $body)
    {
        return true;
        $url = 'https://fcm.googleapis.com/fcm/send';

        $serverKey = 'AAAALN9l6-I:APA91bEl1d4JnClAOHF6ZKQmQl362dh34OTciVTSM-rtGxBuHB1vbhcUf_opzsDHUreIcYUdl1jMky8bKnnGP-aQSdmlR3dRmMpF_XGBGRWp7a7DnLcquzGoqKOoXxhLJg8kkfwCM-rU';

        $data = [
            "registration_ids" => [$device_key],
            "notification" => [
                "title" => $title,
                "body" => $body,
            ]
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        // dd($result);
    }
}
