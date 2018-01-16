<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $dates = ['created_at'];

    public function imageable(){
        return $this->morphTo();
    }
}
