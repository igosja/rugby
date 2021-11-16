<!DOCTYPE html>
<html lang="uk">
<head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="http://maps.googleapis.com/maps/api/js?v=3.exp&language=uk"></script>
    <title>Карта</title>
    <style>
        html, body {
            height: 100%;
        }

        #map {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
<div id="map"></div>
<script>
    function initialize(lat, lng) {
        var myLatlng = new google.maps.LatLng(lat, lng);
        var mapOptions = {
            zoom: 2,
            center: myLatlng
        };
        var map = new google.maps.Map(document.getElementById('map'), mapOptions);
        var marker = [
            new google.maps.Marker({
                position: new google.maps.LatLng(-13.163333, -72.545556), //Мачу-Пікчу/Стежка інків
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(-71, 0), //Антарктида
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(35.017, 135.75), //Кіото/Сакура
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(48.8589466, 2.2769947), //Париж/Східний експрес
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(40.6976701, -74.2598778), //Нью-Йорк/Хелоуін
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(45.4046829, 12.1071394), //Венеція/Гондоли
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(40.4319118, 116.5681862), //Китайська стіна
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(30.328459, 35.4421735), //Петра
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(27.1751496, 78.0399535), //Тадж-Махал
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(41.8902142, 12.4900422), //Колізей
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(20.6842899, -88.5699713), //Чичен-Іца
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(-22.951911, -43.2126759), //Христос-спаситель/Танго
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(-8.4543384, 114.5110073), //Балі/Захід сонця
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(38.9073603, 1.4121383), //Ібіца/Пиятика
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(-22.9132524, -43.7261856), //Ріо-де-Жанейро
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(48.2208286, 16.2399742), //Відень/Опера
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(13.7251088, 100.3529019), //Бангкок/Лой Кратонг
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(41.8339042, -88.01216), //Чикаго/Блюз бар/Траса 66
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(43.166752, 131.8133713), //Владивосток/Трансиб
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(-1.9589243, 34.1809027), //Серенгети/Сафарі
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(-1.3138282, 35.0265317), //Масаї-Мара/Сафарі
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(36.233, -116.767), //Долина сметрі
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(36.0459593, -112.222127), //Великий каньон
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(-17.9242942, 25.8550116), //Водоспад вікторія
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(45.9610105, -109.6585125), //Йеллоустон
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(-50.5025092, -73.1997351), //Періто Морено льодовик
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(21.9950834, 13.0724103), //Сахара
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(16.9561446, -88.1063367), //Белизский Барьерный риф
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(-25.6952541, -54.4388549), //Водоспади Ігуасу
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(37.9715327, 23.7245279), //Парфенон
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(37.9715365, 23.7235605), //Акрополь
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(37.1760826, -3.59033), //Альгамбра
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(-27.1258098, -109.4088556), //Острів Пасхи
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(-14.7390218, -75.1321937), //Лінії Наски
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(55.7518209, 37.6086255), //Кремль
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(29.9773008, 31.1303068), //Піраміди Гізи
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(51.1788853, -1.8284037), //Стоунхендж
                map: map
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(-33.8567799, 151.213108), //Оперний театр Сідней
                map: map
            }),
        ];
        marker.setMap(map);
    }

    jQuery(document).ready(function ($) {
        var map_div = $("#map");
        if (map_div.length) {
            initialize(0, 0);
        }
    });
</script>
</body>
</html>