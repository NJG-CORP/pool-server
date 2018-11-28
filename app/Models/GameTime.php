<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameTime extends Model
{
    protected $table = 'game_time';
    protected $fillable = [
      'user_id', 'weekday_id'
    ];
    public $timestamps = false;
}
