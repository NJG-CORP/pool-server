<?php

namespace App\Models;

use App\Services\UrlService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Club extends Model
{
    use SoftDeletes;
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $guarded = [];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function getCalculatedRatingAttribute()
    {
        return (int)$this->rating()->avg('score');
    }

    public function rating()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    public function gametype()
    {
        return $this->belongsTo(GameType::class, 'gametype_id');
    }

    public function getWorkTime()
    {
        return $this->hasMany(WorkTime::class, 'club_id', 'id');
    }

    public function getKitchensIdAttribute()
    {
        return json_decode($this->attributes['kitchens_id']);
    }

    public function setKitchensIdAttribute($kitchens)
    {
        $this->attributes['kitchens_id'] = json_encode($kitchens);
        return $this;
    }

    public function getKitchensLabels()
    {
        $kitchens = Kitchens::query()->findMany($this->kitchens_id, ['name'])->all();

        return implode(', ', array_map(function ($array) {
            return $array['name'];
        }, $kitchens));
    }

    public function getHeader()
    {
        return !empty($this->name) ? $this->name : UrlService::getMetaTitle($this->title);
    }
}
