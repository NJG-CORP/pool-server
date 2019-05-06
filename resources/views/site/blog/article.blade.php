@extends('layouts.default')

@section('title', $blog->getHeader())

@section('content')

    <main class="main inner_page_main news_inner_page_main">
        <section class="the_content_section">
            <div class="inner_section">
                <div class="breadcrumbs">
                    <p><a href="/">Главная</a> /
                        <a href="{{ route('blog') }}">Блог</a> /
                        <span>{{ $blog->title }}</span></p>
                </div>
                <div class="font50_wrap">
                    <h1>{{ $blog->title }}</h1>
                </div>
                <div class="inner_news_page_wrap clearfix">
                    <div class="inner_news_block clearfix">
                        <div class="img">
                            <div class="big_img">
                                <a class="fancy" href=""><img src="{{ $blog->getMainImageEvent->url }}" alt=""></a>
                            </div>
                            <div class="mini_img">

                            </div>
                        </div>
                        <div class="text">
                            <span class="date">{{ $blog->created_at }}</span>
                            <p>{{ $blog->description }}</p>
                        </div>
                    </div>
                    <div class="paragraph">
                        {!! $blog->paragraph !!}
                    </div>
                    @if(count($rec_blogs))
                        <h2>Другие новости</h2>
                        <div class="news_block_wrap other_news_block_wrap clearfix">
                            @foreach($rec_blogs as $item)
                                @include('site.chunks.blog.item', ['article' => $item])
                            @endforeach
                        </div>
                    @endif
                    <a class="button book_table_button list_button" href="{{ route('blog') }}">Перейти к списку
                        блога</a>
                </div>
            </div>
        </section>

    </main>

@endsection