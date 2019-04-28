<div class="club_cat_page_left_div">
    <p class="title"><a href="{{route('club.show', $club->url)}}">{{ $club->title }}</a></p>
    <div class="img">
        <img src="{{$club->getMainImage()}}" alt="">
    </div>
    <div class="text">
        <p>
            <b>{{ $club->location->address }}</b>
            Пул, Русский бильярд, Снукер, Карамболь </p>
        <p>
            <b>Время работы:</b>
            {!! $club->getWorkingTimeHtml() !!}
        </p>
        <p>
            <b>Телефон:</b>
            <a class="tel_a" href="tel:{{ $club->phone }}">{{ $club->phone  }}</a>
        </p>
    </div>
    <div class="buttons">
    {{--<a class="button invite_button"--}}
    {{--href="{{ route('club.show', $club->url) }}">Подробнее</a>--}}
    <!--
<a class="button book_table_button" href="#">Забронировать стол</a>
-->
    </div>
</div>