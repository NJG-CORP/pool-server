@extends('layouts.default')

@section('content')
    <main class="main" style="padding-bottom: 0">
        <section class="the_content_section">
            <div class="inner_section">

                <div class="breadcrumbs inner_section">
                    <p itemscope itemtype="http://schema.org/BreadcrumbList">
								<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
									<a href="/" itemprop="item"><span itemprop="name">Главная</span></a>
									<meta itemprop="position" content="1">
								</span>
                        <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
									<span itemprop="item"><span itemprop="name">Приглашения</span></span>
									<meta itemprop="position" content="2">
								</span>
                    </p>
                </div>

                <h1>Приглашения</h1>

                <div class="news_block_wrap partners_block_wrap invitation_block_wrap clearfix">
                    @foreach ($invites as $invite)
                        @include ('site.user.profile._inviteRow', compact('$invite'))
                    @endforeach
                </div><!--/news_block_wrap-->

                {{--<div class="pagination pagination_mark2">--}}
                {{--<a class="prev" href="#"><i class="fa fa-angle-left" aria-hidden="true"></i></a>--}}

                {{--<a href="#">1</a>--}}
                {{--<a href="#">2</a>--}}
                {{--<a class="active" href="#">3</a>--}}
                {{--<a href="#">4</a>--}}
                {{--<a href="#">5</a>--}}
                {{--<a href="#">6</a>--}}

                {{--<a class="next" href="#"><i class="fa fa-angle-right" aria-hidden="true"></i></a>--}}
                {{--</div>--}}

            </div>
        </section><!--/the_content_section-->
    </main>
@endsection