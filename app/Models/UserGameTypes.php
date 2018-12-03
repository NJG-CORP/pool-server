<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGameTypes extends Model
{
    protected $table = 'user_game_types';
    protected $fillable = [
      'user_id', 'pool', 'snooker', 'russian', 'caromball'
    ];
    public $timestamps = false;
}
