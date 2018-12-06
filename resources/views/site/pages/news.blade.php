@extends('layouts.default')

@section('title', '')

@section('content')

    <main class="main inner_page_main news_inner_page_main">
        <section class="the_content_section">
            <div class="inner_section">
                <div class="breadcrumbs">
                    <p><a href="/">Главная</a> / <span>Новости</span></p>
                </div>
                <h2>Новости</h2>
                <div class="news_block_wrap clearfix">
                    @foreach($news as $item)
                        <div class="news_block_div">
                            <div class="img">
                                <a href="{{ route('news.show', $item->id) }}">
                                    <img src="{{ $item->image }}" alt="News Image">
                                </a>
                            </div>
                            <div class="text">
                                <p class="title">
                                    <a href="{{ route('news.show', $item->id) }}">{{ $item->title }}</a>
                                </p>
                                <span class="date">{{ $item->created_at }}</span>
                                <p>{{ substr($item->description, 0, 200) }}...</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

@endsection