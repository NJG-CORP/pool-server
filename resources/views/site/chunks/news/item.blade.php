<?php
/**
 * News $news
 */
?>

<div class="news_block_div">
    <div class="img">
        <a href="{{ route('news.show', $article->url) }}">
            <img src="{{ $article->getMainImageEvent->url }}" alt="News Image">
        </a>
    </div>
    <div class="text">
        <p class="title">
            <a href="{{ route('news.show', $article->url) }}">{{ $article->title }}</a>
        </p>
        <span class="date">{{ date('Y-m-d', strtotime($article->created_at)) }}</span>
        <p>{{ mb_strimwidth($article->description, 0, 200) }}...</p>
    </div>
</div>