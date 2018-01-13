<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    const MODEL_PROFILE = 'pr';
    const MODEL_CLUB = 'cl';

    protected $dates = ['created_at'];

    public function imageable(){
        return $this->morphTo();
    }
}
