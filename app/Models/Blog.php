<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    const HEADER_SUFFIX = 'СУФФИКС ';

    protected $table = 'blogs';
    protected $fillable = ['title', 'description', 'author_id'];

    public function getHeader()
    {
        return !empty($this->name) ? $this->name : $this::HEADER_SUFFIX . $this->title;
    }
}
