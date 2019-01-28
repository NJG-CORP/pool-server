@extends('layouts.default')

@section('title', $news->title . ' | ' . 'SiteName')

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
                    <h2>{{ $news->title }}</h2>
                </div>
                <div class="inner_news_page_wrap clearfix">
                    <div class="inner_news_block clearfix">
                        <div class="img">
                            <div class="big_img">
                                <a class="fancy" href=""><img src="{{ asset($news->image) }}" alt=""></a>
                            </div>
                            <div class="mini_img">

                            </div>
                        </div>
                        <div class="text">
                            <span class="date">{{ $news->created_at }}</span>
                            <p>{{ $news->description }}</p>
                        </div>
                    </div>
                    <h2>Другие новости</h2>
                    <div class="news_block_wrap other_news_block_wrap clearfix">
                        @foreach($rec_news as $item)
                            <div class="news_block_div">
                                <div class="img">
                                    <a href="{{ route('news.show', $item->id) }}">
                                        <img src="{{ asset($item->image) }}" alt="">
                                    </a>
                                </div>
                                <div class="text">
                                    <p class="title">
                                        <a href="{{ route('news.show', $item->id) }}">{{ $item->title }} </a>
                                    </p>
                                    <span class="date">{{ $item->created_at }}</span>
                                    <p>{{ substr($item->description, 0, 200) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a class="button book_table_button list_button" href="{{ route('news') }}">Перейти к списку
                        новости</a>
                </div>
            </div>
        </section>
    </main>

@endsection