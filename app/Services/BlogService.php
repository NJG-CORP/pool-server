<?php
namespace App\Services;

use App\Models\Blog;

class BlogService
{
    public function getAll() {
        $news = Blog::query()->orderBy('created_at', 'desc')->get();
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

    public function getBlogByUrl(string $url)
    {
        return Blog::with(['images'])->where(['url' => $url])->first();
    }
}
