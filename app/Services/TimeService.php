<?php


namespace App\Services;


use Carbon\Carbon;

class TimeService
{

    public static function mutateToTime($value)
    {
        if (count($time = explode(':', $value)) > 2) {
            return $time[0] == 23 ? 24 : $time[0];
        } else {
            return Carbon::createFromTime(
                $value == 24 ? 23 : $value,
                $value == 24 ? 59 : 0,
                $value == 24 ? 59 : 0
            )->format("H:i:s");
        }
    }
}