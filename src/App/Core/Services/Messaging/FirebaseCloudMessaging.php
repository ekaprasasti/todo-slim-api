<?php

namespace App\Core\Services\Messaging;

use DI\Container;
use App\Core\Services\Messaging\MessageQueue;

class FirebaseCloudMessaging
{
    const FCM_SEND_URL = 'https://fcm.googleapis.com/fcm/send';

    protected $container;
    protected $serverKey;

    function __construct(Container $container, MessageQueue $queue)
    {
        $this->container = $container;
        $this->serverKey = $container->get('settings.fcm.serverKey');
        $this->queue = $queue;
    }

    public function send($destination, $message, $direct = true)
    {
        if(!$direct) {
            return $this->queue->push($destination, $message);
        }

        $timestamp = new \DateTime();
        $data = [
            'to' => $destination,
            'data' => [
                'message' => $message,
                'timestamp' => $timestamp->format('Y-m-d H:i:s')
            ]
        ];

        $dataString = json_encode($data);
        $ch = curl_init(self::FCM_SEND_URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($dataString),
            'Authorization: key=' . $this->serverKey
        ]);

        $result = curl_exec($ch);
        die(var_dump($result));
        return true;
    }

}
