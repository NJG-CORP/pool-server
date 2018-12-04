@extends('layouts.default')

@section('content')
    <main class="main inner_page_main activities_inner_page_main">

        <section class="the_content_section">
            <div class="inner_section">

                <div class="breadcrumbs">
                    <p><a href="/">Главная</a> / <span>Мероприятия</span></p>
                </div>

                <h1>Мероприятия</h1>

                <div class="news_block_wrap activities_block_wrap clearfix">
                    @foreach ($data as $event)
                        @include('site.events._item', ['event' => $event])
                    @endforeach
                </div>

            </div>
        </section>

    </main>
@endsection()