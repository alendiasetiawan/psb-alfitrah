<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class WhaCenterHelper
{
    public static function sendText($waNumber, $message)
    {
        $client = new Client;
        $options = [
            'multipart' => [
                [
                    'name' => 'device_id',
                    'contents' => config('app.whacenter_device_id'),
                ],
                [
                    'name' => 'number',
                    'contents' => $waNumber,
                ],
                [
                    'name' => 'message',
                    'contents' => $message,
                ],
            ]
        ];
        $request = new Request('POST', config('app.whacenter_api_send_url'));
        $res = $client->sendAsync($request, $options)->wait();

        return $res->getBody();
    }

    public static function sendScheduleText($waNumber, $message, $timeSchedule)
    {
        $client = new Client;
        $options = [
            'multipart' => [
                [
                    'name' => 'device_id',
                    'contents' => config('app.whacenter_device_id'),
                ],
                [
                    'name' => 'number',
                    'contents' => $waNumber,
                ],
                [
                    'name' => 'message',
                    'contents' => $message,
                ],
                [
                    'name' => 'schedule',
                    'contents' => $timeSchedule
                ]
            ]
        ];
        $request = new Request('POST', config('app.whacenter_api_send_url'));
        $res = $client->sendAsync($request, $options)->wait();

        return $res->getBody();
    }
}
