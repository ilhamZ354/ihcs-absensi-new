<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Maps</title>    
</head>
<body>
    <div id="map" class="map-canvas" style="height: 600px;"></div>
    <script>

        var marker;
        var coordinates = ['{{ $att->coordinate_in }}','{{ $att->coordinate_out }}'];
        var in_coordinates = coordinates[0].split(',');
        var out_coordinates = coordinates[1].split(',');

        var officeCoords = JSON.parse('{!! trim(!is_null($lokasi) ? $lokasi->coordinates : '[]') !!}');

        var inCoords = {lat: parseFloat(in_coordinates[0]), lng: parseFloat(in_coordinates[1])};
        var outCoords = {lat: parseFloat(out_coordinates[0]), lng: parseFloat(out_coordinates[1])};
        console.log();
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 18,
                center: !isNaN(inCoords.lat) ? inCoords : outCoords
            });

            var officeMarker = new google.maps.Polygon({
                paths: officeCoords,
                strokeColor: '#fe0000',
                strokeOpacity: 0.8,
                strokeWeight: 3,
                fillColor: '#ff8800',
                fillOpacity: 0.3
            });
            officeMarker.setMap(map);

            marker = new google.maps.Marker({
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
                position: inCoords,
                label: {
                    text: 'Masuk',
                    color: "white",
                    fontWeight: '600',
                }
            });
            marker.addListener('click', toggleBounce);

            marker = new google.maps.Marker({
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
                position: outCoords,
                label: {
                    text: 'Pulang',
                    color: "white",
                    fontWeight: '600',
                }
              });
            marker.addListener('click', toggleBounce);
        }

        function toggleBounce() {
            if (marker.getAnimation() !== null) {
                marker.setAnimation(null);
            } else {
                marker.setAnimation(google.maps.Animation.BOUNCE);
            }
        }

        
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAeyKICwg9NQgFzVs0CIEUFYporsqSsw3o&v=3&callback=initMap"></script>
</body>
</html>