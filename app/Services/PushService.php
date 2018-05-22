<?php
namespace App\Services;

use App\Models\Device;

class PushService{
    public function sendToUser($userId, $message, $title, $data){
        $devices = Device::where('user_id', $userId)->get();
        $params = [
            "headings" => [
                "en" => $title
            ]
        ];
        foreach ($devices as $device) {
            \OneSignal::addParams($params)
                ->sendNotificationToUser($message, $device->player_id, null, $data);
        }
    }
}
