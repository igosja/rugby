<!DOCTYPE html>
<html lang="uk">
<head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="http://maps.googleapis.com/maps/api/js?v=3.exp&language=uk"></script>
    <title>Карта</title>
</head>
<body>
<style>
    html, body {
        height: 100%;
    }

    #map {
        width: 100%;
        height: 100%;
    }
</style>
<div id="map"></div>
<script>
    function initialize(lat, lng) {
        var myLatlng = new google.maps.LatLng(lat, lng);
        var mapOptions = {
            zoom: 6,
            center: myLatlng
        };
        var map = new google.maps.Map(document.getElementById('map'), mapOptions);
        var marker = [
            new google.maps.Marker({
                position: new google.maps.LatLng(48.293860, 25.938716), //Чернівці
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(48.922243, 24.711977), //Івано-Франківськ
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(50.907789, 34.799106), //Суми
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(51.674722, 33.913333), //Глухів
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(51.498110, 31.290536), //Чернігів
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(51.037953, 31.885692), //Ніжин
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(48.448643, 22.709668), //Мукачеве
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(48.620498, 22.288776), //Ужгород
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(50.450696, 30.529093), //Київ
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(50.351244, 31.319146), //Баришівка
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(50.511391, 30.790281), //Бровари
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(50.589704, 30.905293), //Тарасівка
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(48.639241, 25.737360), //Заліщики
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(27.978610, 34.393610), //Шарм-еш-шейх
                map: map
            }),
        ];
        marker.setMap(map);
    }

    jQuery(document).ready(function ($) {
        var map_div = $("#map");
        if (map_div.length) {
            initialize(48.5505313, 30.6270735);
        }
    });
</script>
</body>
</html>