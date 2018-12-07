<?php

namespace App\Http\Controllers\Web;

use App\Services\BlogService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function blog()
    {
        $blogs = (new BlogService())->getAll();
        return view('site.pages.blogs', compact('blogs'));
    }

    public function showBlog($id)
    {
        $blog = (new BlogService())->getBlog($id);
        $rec_blogs = (new BlogService())->getLastBlogs($id);
        return view('site.pages.blogs-single', compact('blog', 'rec_blogs'));
    }
}
