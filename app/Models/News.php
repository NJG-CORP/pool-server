<?php

namespace App\Models;

use App\Services\UrlService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;
    const HEADER_SUFFIX = 'СУФФИКС ';

    protected $table = 'news';
    protected $fillable = ['title', 'description'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
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
