<?php

namespace App\Http\Controllers\Web\Blog;

use App\Http\Controllers\Controller;
use App\Services\BlogService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends Controller
{
    public function list()
    {
        $blogs = (new BlogService())->getAll();
        return view('site.pages.blogs', compact('blogs'));
    }

    public function viewId($id)
    {
        $blog = (new BlogService())->getBlog($id);

        if (!$blog) {
            throw new NotFoundHttpException;
        }

        return redirect(null, 301)->route('blog.show', ['url' => $blog->url]);
    }

    public function view(string $url)
    {
        $blog = (new BlogService())->getBlogByUrl($url);
        if (!$blog) {
            throw new NotFoundHttpException;
        }
        $rec_blogs = (new BlogService())->getLastBlogs($blog->id);

        return view('site.pages.blogs-single', compact('blog', 'rec_blogs'));
    }
}
