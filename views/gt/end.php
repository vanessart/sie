<style>
    #map {
        height: 400px;
        width: 100%;
    }
</style>
<div style="text-align: center">
    <button onclick="document.getElementById('endMain').submit()" type="button" class="btn btn-warning" data-dismiss="modal">Salvar localização</button>
</div>
<br /><br />
<div style="width: 80%; margin: 0 auto" id="map"></div>
<script>
    function initMap() {
        
            document.getElementById('inputlat').value = '<?php echo @$lat ?>';
            document.getElementById('inputlong').value = '<?php echo @$long ?>';
        var local = {lat: <?php echo $lat ?>, lng: <?php echo $long ?>};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 18,
            center: local
        });
        var mapa = new google.maps.Marker({
            position: local,
            map: map
        });
        google.maps.event.addListener(map, 'click', function (event) {
            mapa.setMap(null);
            mapa = new google.maps.Marker({
                position: event.latLng,
                map: map
            });

            document.getElementById('inputlat').value = event.latLng.lat();
            document.getElementById('inputlong').value = event.latLng.lng();

        });
    }
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuJgECnkb1b8M-KZIinGrRPd98cC_3egs&callback=initMap">
</script>