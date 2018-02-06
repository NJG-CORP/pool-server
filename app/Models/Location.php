<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public $timestamps = false;

    public function user(){
        return $this->hasOne(User::class);
    }

    public function club(){
        return $this->hasOne(Club::class);
    }

    public function city(){
        return $this->belongsTo(City::class);
    }
}
