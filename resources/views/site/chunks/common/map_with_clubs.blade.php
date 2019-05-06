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
</script>