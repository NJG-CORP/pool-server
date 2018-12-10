<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Blogs extends Model
{
    use SoftDeletes;
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function images(){
        return $this->morphMany(Image::class, 'imageable');
    }

    /* 
     @role: 
     
     @comments: 
     */ 
     
     public function getMainImageEvent() 
     { 
     
        return $this->hasOne(Image::class,'id','mainImg');
     
     }
}
