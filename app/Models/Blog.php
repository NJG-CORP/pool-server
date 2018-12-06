<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $fillable = ['title', 'description', 'author_id'];

    public function getAll() {
        $news = Blog::all();
        return $news;
    }

    public function getBlog($id) {
        $news = Blog::findorfail($id);
        return $news;
    }

    public function getLastBlogs($id) {
        $blog = Blog::where('id', '!=', $id);
        $blog1 = $blog->get()->last();
        $blog2 = $blog->find(intval($blog1->id - 1));
        $data = [];
        isset($blog1) ? $data[] = $blog1 : '';
        isset($blog2) ? $data[] = $blog2 : '';

        return $data;
    }
}
