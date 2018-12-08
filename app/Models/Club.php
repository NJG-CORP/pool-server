<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Club extends Model
{
    use SoftDeletes;
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
     protected $guarded = [];
    public function images(){
        return $this->morphMany(Image::class, 'imageable');
    }

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function location(){
        return $this->belongsTo(Location::class);
    }

    public function rating(){
        return $this->morphMany(Rating::class, 'rateable');
    }

    public function getCalculatedRatingAttribute(){
        return (int)$this->rating()->avg('score');
    }
    public function gametype(){
        return $this->belongsTo(GameType::class,'gametype_id');
    }       
    public function getWorkTime(){
        return $this->hasMany(WorkTime::class,'club_id','id');
    }
}
