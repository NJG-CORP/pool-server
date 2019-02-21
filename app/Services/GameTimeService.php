<?php


namespace App\Services;


use App\Models\User;

class GameTimeService
{
    private $days;

    public function __construct(User $user)
    {
        $this->days = $user->gameTime;
    }

    public function isChecked(int $id)
    {
        foreach ($this->days as $day) {
            if ($day->id === $id) {

                return true;
            }
        }
        return false;
    }

}