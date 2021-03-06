<?php

namespace App\Models;

use App\Services\mainImage;
use App\Services\UrlService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Events extends Model
{
    use SoftDeletes, mainImage;
    const HEADER_SUFFIX = 'СУФФИКС ';

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getUrl()
    {
        return '/events/' . $this->url;
    }

    public function club()
    {
        return $this->hasOne(Club::class, 'id', 'club_id');
    }

    public function getMainImageEvent()
    {
        return $this->hasOne(Image::class, 'id', 'mainImg');
    }

    public function getHeader()
    {
        return !empty($this->name) ? $this->name : UrlService::getMetaTitle($this->title);
    }

}