@extends('layouts.default')

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
                                <a class="fancy" href="{{$event->getMainImage()}}"><img src="{{$event->getMainImage()}}"
                                                                                        alt=""></a>
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

                <h2>Другие мероприятия</h2>

                <div class="news_block_wrap other_news_block_wrap clearfix">
                    @foreach((new \App\Services\EventsService())->getMoreEvents($event->id) as $event)
                        @include('site.events._more', ['event' => $event])
                    @endforeach
                </div>

                <a class="button book_table_button list_button" href="/events">
                    Перейти к списку мероприятий
                </a>

            </div>
        </section>
    </main>
@endsection()