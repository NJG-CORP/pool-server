@extends('layouts.default')

@section('title', 'Клубы')

@section('content')

    <main class="main inner_page_main club_cat_inner_page_main">
        <section class="the_content_section the_club_cat_content_section">
            <div class="inner_section">
                <div class="breadcrumbs">
                    <p>
                        <a href="/">Главная</a> /
                        <span>Каталог клубов</span>
                    </p>
                </div>
                <h2>Каталог клубов</h2>
                <div class="club_cat_page_block the_tabs clearfix">
                    <div class="the_tabs_head show_on_mobile_only">
                        <a href="#">Списком</a>
                        <a class="active" href="#">На карте</a>
                    </div>
                    <div class="the_tabs_content">
                        <div class="club_cat_page_left the_tabs_div active">
                            <div class="club_cat_page_left_search the_form">
                                <div class="the_form_div">
                                    <input type="text" name="club_search" placeholder="Введите название клуба"/>
                                </div>
                            </div>
                            <div class="club_cat_page_left_wrap">
                                <div class="club_cat_page_left_wrap_inner modern-skin scrollable"
                                     id="clubs-list-container">
                                    @foreach($clubs as $club)
                                        <div class="club_cat_page_left_div">
                                            <p class="title">{{ $club->name }}</p>
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
                                                    <a class="tel_a" href="tel:89995293121">{{ $club->phone  }}</a>
                                                </p>
                                            </div>
                                            <div class="buttons">
                                                <a class="button invite_button"
                                                   href="{{ route('club.show', $club->url) }}">Подробнее</a>
                                                <!--
<a class="button book_table_button" href="#">Забронировать стол</a>
-->
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="club_cat_page_map the_tabs_div active">
                            <div class="map" id="map1">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script>

        function initMap() {
            const jsonMarkersInfo = {!!  json_encode($json_markers) !!};
            let markers = [];
            const map = new google.maps.Map(document.getElementById('map1'), {
                zoom: 12,
                center: {lat: 45.040458, lng: 38.981979}
            });
            const image = '/img/interface/balloon.png';
            let centerCoords = {
                lat: 0,
                lng: 0
            };
            let count = 0;
            $.each(jsonMarkersInfo, function (index, marker) {
                centerCoords['lat'] += parseFloat(marker['lat']);
                centerCoords['lng'] += parseFloat(marker['lng']);
                var markerGoogle = new google.maps.Marker({
                    position: {lat: parseFloat(marker['lat']), lng: parseFloat(marker['lng'])},
                    icon: image,
                    map: map
                });
                markers.push(markerGoogle);
                count++;
                markerGoogle.addListener('click', function () {
                    window.location = marker['url'];
                });
            });

            centerCoords['lat'] = centerCoords['lat'] / count;
            centerCoords['lng'] = centerCoords['lng'] / count;

            map.setCenter(centerCoords);
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&libraries=places&callback=initMap"></script>


@endsection