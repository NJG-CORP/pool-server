@extends('layouts.default')

@section('title', $event->getHeader())

@section('content')
    <main class="main inner_page_main news_inner_page_main">
        <section class="the_content_section">
            <div class="inner_section">

                <div class="breadcrumbs">
                    <p><a href="/">Главная</a> /
                        <a href="/events">Мероприятия</a> /
                        <span>{{$event->title}}</span></p>
                </div>

                <div class="font50_wrap">
                    <h1>{{$event->title}}</h1>
                </div>

                <div class="inner_news_page_wrap clearfix">
                    <div class="inner_news_block clearfix">
                        <div class="img">
                            <div class="big_img">
                                <a class="fancy" href="{{$event->getMainImageEvent->url}}">
                                    <img src="{{$event->getMainImageEvent->url}}" alt="">
                                </a>
                            </div>

                            <div class="mini_img">
                                @foreach($event->images as $image)
                                    <a class="fancy" href="{{$image->path}}"><img src="{{$image->path}}" alt=""></a>
                                @endforeach
                            </div>
                        </div>

                        <div class="text">
                            <span class="date">{{$event->date}}</span>
                            <p>
                                {{$event->description}}
                            </p>
                        </div>
                    </div>
                    {!! $event->paragraph !!}
                </div>

                @if(count($more_events) > 0)
                    <h2>Другие мероприятия</h2>

                    <div class="news_block_wrap other_news_block_wrap clearfix">
                        @foreach($more_events as $event)
                            @include('site.chunks.events.more-item', ['event' => $event])
                        @endforeach
                    </div>
                @endif;

                <a class="button book_table_button list_button" href="/events">
                    Перейти к списку мероприятий
                </a>

            </div>
        </section>
    </main>
@endsection()