<?php
/**
 * News $news
 */
?>

<div class="news_block_div">
    <div class="img">
        <a href="{{ route('news.show', $news->url) }}">
            <img src="{{ $news->getMainImageEvent->url }}" alt="News Image">
        </a>
    </div>
    <div class="text">
        <p class="title">
            <a href="{{ route('news.show', $item->url) }}">{{ $item->title }}</a>
        </p>
        <span class="date">{{ date('Y-m-d', strtotime($news->created_at)) }}</span>
        <p>{{ mb_strimwidth($news->description, 0, 200) }}...</p>
    </div>
</div>