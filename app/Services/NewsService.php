<?php
namespace App\Services;

use App\Models\News;

class NewsService
{
    public function getAll() {
        $news = News::all();
        return $news;
    }

    public function getNews($id) {
        $news = News::findorfail($id);
        return $news;
    }

    public function getLastNews($id) {
        $news = News::where('id', '!=', $id)->get();
        $news = $news->slice(count($news) - 2, count($news));

        return $news;
    }
}
