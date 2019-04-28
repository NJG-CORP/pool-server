@extends('layouts.default')

@section('title', 'Новости')

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
                        @include('site.chunks.news.item', ['news' => $item])
                    @endforeach
                </div>
            </div>
        </section>
    </main>

@endsection