@extends('layouts.default')

@section('content')
    <main class="main inner_page_main activities_inner_page_main">

        <section class="the_content_section">
            <div class="inner_section">

                <div class="breadcrumbs inner_section">
                    <p itemscope itemtype="http://schema.org/BreadcrumbList">
								<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
									<a href="#" itemprop="item"><span itemprop="name">Главная</span></a>
									<meta itemprop="position" content="1">
								</span>
                        <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
									<span itemprop="item"><span itemprop="name">Мероприятия</span></span>
									<meta itemprop="position" content="2">
								</span>
                    </p>
                </div>

                <h1>Мероприятия</h1>

                <div class="news_block_wrap activities_block_wrap clearfix">
                    @foreach ($data as $event)
                        @include('site.chunks.events.item', ['event' => $event])
                    @endforeach
                </div>

            </div>
        </section>

    </main>
@endsection()