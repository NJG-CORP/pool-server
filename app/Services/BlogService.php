<?php
namespace App\Services;

use App\Models\Blog;

class BlogService
{
    public function getAll() {
        $news = Blog::all();
        return $news;
    }

    public function getBlog($id) {
        $news = Blog::findorfail($id);
        return $news;
    }

    public function getLastBlogs($id) {
        $blog = Blog::where('id', '!=', $id)->orderBy('id', 'desc')->take(2)->get();
        return $blog;
    }
}
