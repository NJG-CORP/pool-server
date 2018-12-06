<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';
    protected $fillable = ['title', 'description'];


    public function getAll() {
        $news = News::all();
        return $news;
    }

    public function getNews($id) {
        $news = News::findorfail($id);
        return $news;
    }

    public function getLastNews($id) {
        $news = News::where('id', '!=', $id);
        $news1 = $news->get()->last();
        $news2 = $news->find(intval($news1->id - 1));
        $data = [];
        isset($news1) ? $data[] = $news1 : '';
        isset($news2) ? $data[] = $news2 : '';

        return $data;
    }
}
