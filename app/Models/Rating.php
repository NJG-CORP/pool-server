<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'rating';
    protected $dates = ['created_at', 'updated_at'];
    protected $guarded = [];

    public function rater(){
        return $this->belongsTo(User::class, 'rater_id');
    }

    public function rateable(){
        return $this->morphTo();
    }
}
