<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameType extends Model
{
    const LABELS = [
        'pool' => "Пул",
        'Russian' => 'Русский',
        'Snooker' => 'Снукер',
        'Cannon' => 'Карамболь'
    ];

    protected $guarded = [];
    protected $table = 'games_type';
}
