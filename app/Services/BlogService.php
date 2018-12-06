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
        $blog = Blog::where('id', '!=', $id)->get();
        $blog = $blog->slice(count($blog) - 2, count($blog));
        return $blog;
    }
}
