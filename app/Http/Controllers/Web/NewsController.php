<?php

namespace App\Http\Controllers\Web;

use App\Services\NewsService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    public function news()
    {
        $news = (new NewsService())->getAll();
        return view('site.pages.news', compact('news'));
    }

    public function showNews($id)
    {
        $news = (new NewsService())->getNews($id);
        $rec_news = (new NewsService())->getLastNews($id);
        return view('site.pages.news-single', compact('news', 'rec_news'));
    }
}
