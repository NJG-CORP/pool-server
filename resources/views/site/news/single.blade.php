@extends('layouts.default')

@section('title', $news->getHeader())

@section('content')

    <main class="main inner_page_main news_inner_page_main">
        <section class="the_content_section">
            <div class="inner_section">
                <div class="breadcrumbs">
                    <p>
                        <a href="/">Главная</a> /
                        <a href="{{ route('news') }}">Новости</a> /
                        <span>{{ $news->title }}</span>
                    </p>
                </div>
                <div class="font50_wrap">
                    <h1>{{ $news->title }}</h1>
                </div>
                <div class="inner_news_page_wrap clearfix">
                    <div class="inner_news_block clearfix">
                        <div class="img">
                            <div class="big_img">
                                <a class="fancy" href=""><img src="{{ asset($news->getMainImageEvent->url) }}"
                                                              alt=""></a>
                            </div>
                            <div class="mini_img">

                            </div>
                        </div>
                        <div class="text">
                            <span class="date">{{ $news->created_at }}</span>
                            <p>{{ $news->description }}</p>
                        </div>
                    </div>
                    <div class="paragraph">
                        {!! $news->paragraph !!}
                    </div>
                    <h2>Другие новости</h2>
                    <div class="news_block_wrap other_news_block_wrap clearfix">
                        @foreach($rec_news as $item)
                            @include('site.chunks.news.item', ['article' => $item])
                        @endforeach
                    </div>
                    <a class="button book_table_button list_button" href="{{ route('news') }}">Перейти к списку
                        новости</a>
                </div>
            </div>
        </section>
    </main>

@endsection