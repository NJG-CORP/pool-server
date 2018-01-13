<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $dates = ['updated_at'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
