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
        $news = News::where('id', '!=', $id)->orderBy('id', 'desc')->take(2)->get();;
        return $news;
    }

    public function getNewsByUrl(string $url)
    {
        return News::with(['images'])->where(['url' => $url])->first();
    }
}
