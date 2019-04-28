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
                        @include('site.chunks.blog.item', ['article' => $item])
                    @endforeach
                </div>
            </div>
        </section>

    </main>

@endsection