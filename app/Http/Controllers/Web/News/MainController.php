<?php

namespace App\Http\Controllers\Web\News;

use App\Http\Controllers\Controller;
use App\Services\NewsService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends Controller
{
    public function list()
    {
        $news = (new NewsService())->getAll();
        return view('site.news.index', compact('news'));
    }

    public function viewId($id)
    {
        $news = (new NewsService())->getNews($id);
        if (!$news) {
            throw new NotFoundHttpException;
        }

        return redirect()->route('news', ['url' => $news->url]);
    }

    public function view(string $url)
    {
        $news = (new NewsService())->getNewsByUrl($url);
        $rec_news = (new NewsService())->getLastNews($news->id);
        return view('site.news.single', compact('news', 'rec_news'));
    }
}
