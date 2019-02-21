@extends('layouts.default')

@section('title', $user_profile->getUserName() . ' | Карточка')

@section('content')

    <main class="main inner_page_main">

        <section class="the_content_section">

            <div class="breadcrumbs inner_section">
                <p><a href="/">Главная</a> / <span>Карточка игрока</span></p>
            </div>

            <div class="profile2_wrap clearfix">
                <div class="profile2_wrap_inner inner_section clearfix">

                    <div class="profile_left_column profile_left_column_avatar_block">
                        <div class="img">
                            <img src="{{ $user_profile->getAvatarUrl() }}" alt="">
                        </div>

                        <div class="text">
                            <p class="name">
                                {{ $user_profile->getUserName() }} </p>
                            <p>
                            </p>

                            <!--<p class="status">Статус <span class="status_span">Pro</span></p>-->

                            <p>
                                <b>Вид игры:</b><br>
                                {{$user_profile->getGameTypes()}}
                            </p>
                            <div class="rating">
                                <p><b>Рейтинг игрока</b></p>

                                <div class="stars">
                                    <div><span class="star{{$user_profile->calculated_rating ?? '0' }}"></span></div>
                                </div>
                            </div>

                            <p class="partners_p"><b>Мои партнеры</b> <span class="partners_span">0</span></p>

                        </div>
                    </div>

                    <div class="profile_right_column">

                        <h2>Отзывы об игроке</h2>

                        <div class="partners_wrap partners_reviews_wrap">
                            @if(count($reviews) > 0)
                                @foreach($reviews as $review)
                                    <h5>{{ $review->rater_id }}</h5>
                                    <p style="font-size: 14px;">{{ $review->comment }}</p>
                                @endforeach
                            @else
                                <p style="font-size: 24px;">{{ $user_profile->id === Auth::user()->id ? 'О вас' : ('О ползователе ' . $user_profile->getUsername() . ' ') }}
                                    еще не оставили отзывов</p></div>
                    @endif
                    <!--<div class="pagination">
                            <a class="prev" href="#"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                            <a class="active" href="#">1</a>
                            <a href="#">2</a>
                            <a href="#">3</a>
                            <a href="#">4</a>
                            <a href="#">5</a>
                            <a href="#">6</a>
                            <a class="next" href="#"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                        </div>-->

                    </div>
                </div>
            </div>

        </section>

    </main>

@endsection