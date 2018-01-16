<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $dates = ['created_at'];

    public function users(){
        return $this->hasMany(User::class);
    }

    public function locations(){
        return $this->hasMany(Location::class);
    }

    public function clubs(){
        return $this->hasManyThrough(Club::class, Location::class);
    }
}
