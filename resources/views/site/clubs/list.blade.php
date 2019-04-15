@extends('layouts.default')

@section('title', 'Клубы')

@section('content')

    <main class="main inner_page_main club_cat_inner_page_main">
        <section class="the_content_section the_club_cat_content_section">
            <div class="inner_section">
                <div class="breadcrumbs inner_section">
                    <p itemscope itemtype="http://schema.org/BreadcrumbList">
								<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
									<a href="#" itemprop="item"><span itemprop="name">Главная</span></a>
									<meta itemprop="position" content="1">
								</span>
                        <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
									<span itemprop="item"><span itemprop="name">Каталог клубов</span></span>
									<meta itemprop="position" content="2">
								</span>
                    </p>
                </div>

                <h1>Каталог клубов</h1>
                <div class="club_cat_page_block the_tabs clearfix">
                    <div class="the_tabs_head show_on_mobile_only">
                        <a href="#">Списком</a>
                        <a class="active" href="#">На карте</a>
                    </div>
                    <div class="the_tabs_content">
                        <div class="club_cat_page_left the_tabs_div active">
                            <div class="club_cat_page_left_search the_form">
                                <div class="the_form_div">
                                    <form method="post" id="clubs-search-form">
                                        {{csrf_field()}}
                                        <input type="text" name="club_search" id="club-search-input"
                                               list="club-search-suggest" placeholder="Введите название клуба"/>
                                        <datalist id="club-search-suggest"></datalist>
                                    </form>
                                </div>
                            </div>
                            <div class="club_cat_page_left_wrap">
                                <div class="club_cat_page_left_wrap_inner modern-skin scrollable"
                                     id="clubs-list-container">
                                    @foreach($clubs as $club)
                                        @include('site.clubs._list_item',['club' => $club])
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
            let bounds = new google.maps.LatLngBounds();
            for (let i = 0; i < markers.length; i++) {
                bounds.extend(markers[i].getPosition());
            }

            map.fitBounds(bounds);
        }

        $("#club-search-input").on('input', function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });
            $.ajax({
                url: '/clubs/find/' + encodeURIComponent($(this).val()),
                dataType: 'JSON',
                method: 'GET',
                success: function (response) {
                    let list = $('#club-search-suggest');
                    list.html('');
                    $.each(response.results, function (index, value) {
                        list.append('<option value="' + value.title + '">' + value.title + '</option>')
                    })
                }
            })
        });
        $("#club-search-suggest").on('click', function () {
            $("#clubs-search-form").submit();
        })
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&libraries=places&callback=initMap"></script>


@endsection