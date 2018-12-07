<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Events extends Model
{
    use SoftDeletes;
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function images(){
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getUrl() {
        return '/events/' . $this->id;
    }

    public function club() {
        return $this->hasOne(Club::class, 'id', 'club_id');
    }

    public function getMainImage() {
        return $this->images->first() ? $this->images->first()->getUrlAttribute() : Image::getDefaultImage();
    }

    public function getMoreEvents() {
        return Events::with(['images'])
            ->whereNotIn('id', [$this->id])
            ->get() ?? [];
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