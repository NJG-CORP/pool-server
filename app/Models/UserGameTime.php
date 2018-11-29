<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGameTime extends Model
{
    protected $table = 'user_game_times';
    protected $fillable = [
        'user_id', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'
    ];
    public $timestamps = false;
}
