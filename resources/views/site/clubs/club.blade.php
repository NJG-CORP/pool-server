@extends('layouts.default')

@section('title', $club->getHeader())

@section('content')

    <main class="main inner_page_main club_card_inner_page_main">
        <section class="the_content_section">
            <div class="inner_section">
                <div class="breadcrumbs inner_section">
                    <p itemscope itemtype="http://schema.org/BreadcrumbList">
                        <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <a href="#" itemprop="item"><span itemprop="name">Главная</span></a>
                            <meta itemprop="position" content="1">
                        </span>
                        <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <a href="#" itemprop="item"><span itemprop="name">Клубы</span></a>
                            <meta itemprop="position" content="2">
                        </span>
                        <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <span itemprop="item"><span itemprop="name">{{$club->title}}</span></span>
                            <meta itemprop="position" content="3">
                        </span>
                    </p>
                </div>

                <h1>{{ $club->title }}</h1>
                <div class="the_card_wrap clearfix">
                    <div class="the_card_img">
                        <a href="{{ $club->getMainImageEventUrl()->url }}" class="fancy"><img
                                    src="{{ $club->getMainImageEventUrl()->url }}" alt=""></a>
                    </div>
                    <div class="the_card_description">
                        <p class="name">Подробное описание клуба</p>
                        <div class="stars stars_small">
                            <p>Рейтинг клуба</p>
                            <span class="star{{ $club->calculated_rating ?? '0' }}"></span>
                        </div>
                        <p>{{ $club->description }}</p>
                        <div class="the_card_description_bottom">
                            <div class="the_card_description_bottom_div time">
                                <p>
                                    <b>Время работы:</b><br>
                                    {!! $club->getWorkingTimeHtml() !!} </p>
                            </div>
                            <div class="the_card_description_bottom_div kitchen">
                                <p>
                                    <b>Кухня</b><br/>
                                    {{$club->getKitchensLabels()}} </p>
                            </div>
                            <div class="the_card_description_bottom_div tables">
                                <p>
                                    <b>Число столов</b><br/>
                                    {!!  $club->getTablesLabels() !!}
                                </p>
                            </div>
                        </div>
                        <!--
    <a class="button book_table_button fancy" href="#booking_popup">Забронировать стол</a>
-->
                    </div>
                </div>
                @if($club->gallery_title)
                    <h2>{{$club->gallery_title}}</h2>
                    <div class="images_grid">


                        {{--echo "<h2>{$this->galleryTitle}</h2>";--}}
                        {{--echo "<div class=\"images_grid\">";--}}
                        {{--foreach ($this->galleryImages as $img) {--}}
                        {{--echo "--}}
                        {{--<div class=\"images_grid_div\">--}}
                        {{--<a class=\"fancy\" href=\"" . Images::getPathById($img) . "\">--}}
                        {{--<img src=\"" . Images::getPathById($img) . "\" alt=\"\">--}}
                        {{--</a>--}}
                        {{--</div>";--}}
                        {{--}--}}
                        {{--echo "</div>";--}}
                    </div>
                @endif
                @if($review_form)
                    @include('site.clubs._review_form', ['club_id' => $club->id])
                @endif
            </div>
        </section>
        @if(count($partners_review) > 0 )
            <section class="partners_reviews_section">
                <div class="inner_section clearfix">
                    <p class="section_title">Отзывы участников проекта</p>
                    <div class="partners_wrap">
                        @foreach($partners_review as $review)
                            @include('site.chunks.clubs.review', ['review' => $review])
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>

@endsection