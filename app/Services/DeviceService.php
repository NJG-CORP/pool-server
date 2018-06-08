<?php
namespace App\Services;

use App\Models\Device;
use App\Models\User;

class DeviceService
{
    /**
     * @param User $user
     * @param $deviceToken
     * @param $platform
     * @param $playerId
     * @return Device
     */
    public function ensureDevice(User $user, $deviceToken, $platform, $playerId){
        $device = Device::where('player_id', $playerId)->first();
        if ( $device ) return $device;
        return Device::create([
            'user_id' => $user->id,
            'player_id' => $playerId,
            'device_token' => $deviceToken,
            'platform' => $platform
        ]);
    }
}