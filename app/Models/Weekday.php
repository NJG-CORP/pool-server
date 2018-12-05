<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Weekday extends Model
{
    protected $fillable = ['key', 'name'];
    public $timestamps = false;

    public function usersOnThisDay(){
        return $this
            ->belongsToMany(
                User::class,
                'game_time',
                'weekday_id',
                'user_id'
            );
    }
}
