<?php
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);
if (empty($id_li)) {
    return;
}

$esc = sqlErp::get(['ge_escolas', 'instancia'], 'n_inst, longitude, latitude ', ['fk_id_inst' => $id_inst], 'fetch');

function linhaLatLng($id_li) {
    $sql = "SELECT "
            . " p.n_pessoa, p.id_pessoa, p.ra, p.ra_dig, p.ra_uf, e.*, "
            . "a.fk_id_sa, a.dt_solicita, a.distancia_esc  "
            . " FROM `transporte_aluno` a "
            . " join pessoa p on p.id_pessoa = a.fk_id_pessoa "
            . " left join endereco e on e.fk_id_pessoa = p.id_pessoa "
            . " where fk_id_li = $id_li";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);

    return $array;
}

$linhaLatLng = linhaLatLng($id_li);

?>
<style>
    /* Always set the map height explicitly to define the size of the div
     * element that contains the map. */
    #map {
        height: 100%;
        overflow: visible;
    }
    /* Optional: Makes the sample page fill the window. */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
</style>

<?php
$locations = [];
foreach ($linhaLatLng as $key => $loc)
{
    $locations[] = [
        'lat' => $loc['latitude'],
        'long' => $loc['longitude'],
        'img' => ($loc['fk_id_sa'] == 1) ? HOME_URI .'/includes/images/pessoaverd.png' : HOME_URI .'/includes/images/pessoa.png',
    ];
}

echo toolErp::geraMapaRoute($locations);

return;
?>

<div id="map"></div>
<script>

    // This example requires the Visualization library. Include the libraries=visualization
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=visualization">

    var map, heatmap;
    var gradient = [
            'rgba(0, 255, 255, 0)',
            'rgba(0, 255, 255, 1)',
            'rgba(0, 191, 255, 1)',
            'rgba(0, 127, 255, 1)',
            'rgba(0, 63, 255, 1)',
            'rgba(0, 0, 255, 1)',
            'rgba(0, 0, 223, 1)',
            'rgba(0, 0, 191, 1)',
            'rgba(0, 0, 159, 1)',
            'rgba(0, 0, 127, 1)',
            'rgba(63, 0, 91, 1)',
            'rgba(127, 0, 63, 1)',
            'rgba(191, 0, 31, 1)',
            'rgba(255, 0, 0, 1)'
    ];

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
            center: {lat: <?php echo $esc['latitude'] ?>, lng: <?php echo $esc['longitude'] ?>},
            // mapTypeId: 'satellite'
        });
        var infoWindow = new google.maps.InfoWindow;
        var image = '<?php echo HOME_URI ?>/views/_images/maps/pessoa.png';
        var imageVerde = '<?php echo HOME_URI ?>/views/_images/maps/pessoaverd.png';
        //  var imageVerde = '<?php echo HOME_URI ?>/views/_images/maps/pessoaverde.png';
        var marker = new google.maps.Marker({
            position: {
                lat: <?php echo $esc['latitude'] ?>,
                lng: <?php echo $esc['longitude'] ?>
            },
            map: map
        });
        marker.addListener('click', function () {
            infoWindow.setContent('<?php echo $esc['n_inst'] ?>');
            infoWindow.open(map, marker);
        });
        <?php
        if (!empty($linhaLatLng)){
            foreach ($linhaLatLng as $v) {
                if ($v['fk_id_sa'] == 1) {
                    $image = 'imageVerde';
                } else {
                    $image = 'image';
                }
                $id = $v['id_pessoa'];
                ?>
                var marker<?php echo $id ?> = new google.maps.Marker({
                    position: {
                        lat: <?php echo $v['latitude'] ?>,
                        lng: <?php echo $v['longitude'] ?>
                    },
                    map: map,
                    icon: <?php echo $image ?>
                });
                marker<?php echo $id ?>.addListener('click', function () {
                    infoWindow.setContent('<?php echo $v['n_pessoa'].' - RA: '. $v['ra'].' - Dist.: '.$v['distancia_esc'] ?>');
                    infoWindow.open(map, marker<?php echo $id ?>);
                });
                <?php
            }
        }
        ?>

        heatmap = new google.maps.visualization.HeatmapLayer({
            data: getPoints(),
            map: map
        });
        heatmap.set('gradient', gradient);
        heatmap.set('radius', 100);
        heatmap.set('opacity', 10);
    }

    heatmap.setMap(heatmap.getMap() ? null : map);
    // Heatmap data: 500 Points
    function getPoints() {
        return [
        <?php
        if (!empty($id_tp_ens) && $tipo === 'calor' && !empty($heat)) {
            foreach ($heat as $v) { ?>
                new google.maps.LatLng(<?php echo $v['latitude'] ?>, <?php echo $v['longitude'] ?>),
            <?php
            }
        }
        ?>

        ];
    }

    function checkAll(o) {
        var boxes = document.getElementsByTagName("input");
        for (var x = 0; x < boxes.length; x++) {
            var obj = boxes[x];
            if (obj.type == "checkbox") {
                if (obj.name != "chkAll")
                    obj.checked = o.checked;
            }
        }
    }

    function dadosAluno(id) {
        document.getElementById('id_pessoa').value = id;
        document.getElementById('alunoVer').submit();
        $('#myModal').modal('show');
    }

    function volta() {
        document.getElementById('rc').style.display = '';
        document.getElementById('mp').style.width = '65%';
        document.getElementById('vt').style.display = 'none';
    }

    function recua() {
        document.getElementById('rc').style.display = 'none';
        document.getElementById('mp').style.width = '95%';
        document.getElementById('vt').style.display = '';
    }
</script>

