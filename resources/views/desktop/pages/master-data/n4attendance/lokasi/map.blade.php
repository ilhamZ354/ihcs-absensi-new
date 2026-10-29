<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Maps</title>    
</head>
<body>
    <div id="map" class="map-canvas" style="height: 600px;"></div>
    <script>
        var officeCoords = JSON.parse('{!! trim(!is_null($lokasi) ? $lokasi->coordinates : '[]') !!}');
        
        var inCoords = {lat: parseFloat(officeCoords[0].lat), lng: parseFloat(officeCoords[0].lng)};
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 18,
                center: inCoords
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
        }
        
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAeyKICwg9NQgFzVs0CIEUFYporsqSsw3o&v=3&callback=initMap"></script>
</body>
</html>