@extends('layouts.default')

@section('title', 'Блог')

@section('content')

    <main class="main inner_page_main news_inner_page_main">

        <section class="the_content_section">
            <div class="inner_section">
                <div class="breadcrumbs">
                    <p>
                        <a href="/">Главная</a> / <span>Блог</span>
                    </p>
                </div>
                <h2>Блог</h2>
                <div class="news_block_wrap clearfix">
                    @foreach($blogs as $item)
                        <div class="news_block_div">
                            <div class="img">
                                <a href="{{ route('blog.show', $item->url) }}">
                                    <img src="{{ $item->getMainImageEvent->url }}" alt="News Image">
                                </a>
                            </div>
                            <div class="text">
                                <p class="title">
                                    <a href="{{ route('blog.show', $item->url) }}">{{ $item->title }}</a>
                                </p>
                                <span class="date">{{ $item->created_at }}</span>
                                <p>{{ mb_strimwidth($item->description, 0, 200) }}...</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

    </main>

@endsection