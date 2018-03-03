<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $timestamps = false;
    protected $dates = ['created_at'];
    protected $hidden = ['id', 'imageable_id', 'imageable_type', 'path'];
    protected $appends = ['url'];
    protected $guarded = [];

    private static $imagesPath = "/assets/images";

    public function imageable(){
        return $this->morphTo();
    }

    public function getUrlAttribute(){
        return static::$imagesPath . $this->path;
    }

    public static function getDefaultImage(){
        return [
            "id" => 0,
            "url" => static::$imagesPath . '/placeholder.jpg'
        ];
    }
}
