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

    /**
     * @param $deviceToken
     * @return null
     */
    public function deleteDevice($deviceToken){
        $device = Device::where('device_token', $deviceToken)->first();
        if ( $device ){
            return $device->delete();
        }
        return null;
    }
}
