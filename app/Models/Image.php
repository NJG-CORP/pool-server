<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $timestamps = false;
    protected $dates = ['created_at'];
    protected $hidden = ['id', 'imageable_id', 'imageable_type', 'path'];
    protected $appends = ['url'];

    public function imageable(){
        return $this->morphTo();
    }

    public function getUrlAttribute(){
        return "/public/assets/images" . $this->path;
    }
}
