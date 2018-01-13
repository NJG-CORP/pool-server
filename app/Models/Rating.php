<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'rating';

    protected function rater(){
        return $this->belongsTo(User::class, 'rater_id');
    }

    protected function rated(){
        return $this->belongsTo(User::class, 'rated_id');
    }
}
