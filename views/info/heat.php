<?php
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_tp_ens = filter_input(INPUT_POST, 'id_tp_ens', FILTER_SANITIZE_NUMBER_INT);
$tipo = empty($_POST['tipo']) ? 'calor' : $_POST['tipo'];
$escolas = $model->escolasLatLng($id_tp_ens, $id_inst);
$ciclo = @$_POST['ciclo'];
$heat = [];

if (!empty($_POST['pesq']))
    $heat = $model->heat($ciclo, $id_inst);
?>
<br />
<a id="tp"></a>
<div id="vt" onclick="volta()" style="width: 3%;float: left; display: none;" >
    <div class="col-sm-6 text-center"  style="padding-top: 20px">
        <a href="#tp" onclick="volta()" >
            <img src="<?php echo HOME_URI ?>/views/_images/d.jpg" width="40" height="40" />
        </a>
    </div>
</div>
<div id="rc" style="width: 35%;float: left; padding: 20px">
    <div class="row">
        <div class="col-sm-3">
            <a href="#tp" onclick="recua();" >
                <img src="<?php echo HOME_URI ?>/views/_images/e.jpg" width="40" height="40" />
            </a>
        </div>
        <div class="col-sm-9">
            <?php echo form::selectDB('ge_tp_ensino', 'id_tp_ens', NULL, $id_tp_ens, 1) ?>
        </div>
    </div>
    <br /><br />
    <br />
    <?php
    if (!empty($id_tp_ens)) {
        $cursoCiclo = $model->cursoCiclo($id_tp_ens);
        $optEscola = $model->optEscola($id_tp_ens);
        ?>
        <form method="POST">
            <select name="id_inst">
                <option></option>
                <?php
                foreach ($optEscola as $k => $v) {
                    ?>
                    <option <?php echo $id_inst == $k ? 'selected' : '' ?> value="<?php echo $k ?>">
                        <?php echo $v . '..' . $k ?>
                    </option>
                    <?php
                }
                ?>

            </select>
            <br /><br />
            <?php echo form::select('tipo', ['calor' => 'Mapa de Calor', 'loc' => 'Localização'], NULL, $tipo); ?>
            <br /><br />
            <div class="row">
                <div class="col-sm-6">
                    <div style="font-weight: bold">
                        <label>
                            <input type="checkbox" name="chkAll" onClick="checkAll(this)" />
                            &nbsp;&nbsp;&nbsp;
                            Todos os Ciclos
                        </label>
                    </div>
                    <br />
                    <?php
                    foreach ($cursoCiclo as $k => $v) {
                        ?>
                        <ul type="disc">
                            <div style="font-weight: bold">
                                <?php echo $k ?>
                            </div>
                            <?php
                            foreach ($v as $kk => $vv) {
                                ?>
                                <li>
                                    <label>
                                        <input <?php echo!empty($ciclo[$vv]) ? 'checked' : '' ?> type="checkbox" name="ciclo[<?php echo $vv ?>]" value="<?php echo $vv ?>" />
                                        &nbsp;&nbsp;&nbsp;
                                        <?php echo $kk ?>
                                    </label>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                        <?php
                    }
                    ?>
                </div>
                <div class="col-sm-6 text-center">
                    <input type="hidden" name="id_tp_ens" value="<?php echo $id_tp_ens ?>" />
                    <button name="pesq" value="1" type="submit" class="btn btn-info">
                        Pesquisar
                    </button>
                    <br /><br />
                    <div class="alert alert-info" style="text-align: center; font-size: 18px; font-weight: bold">
                        Atenção!  Uma pesquisa muito abrangente pode travar seu computador
                    </div>
                </div>
            </div>
            <br /><br />

        </form>
        <?php
    }
    ?>
</div>
<div id="mp" style="float: right; width: 65%;">

    <style>
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 100%;
        }
        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        #floating-panel {
            position: absolute;
            top: 10px;
            left: 25%;
            z-index: 5;
            background-color: #fff;
            padding: 5px;
            border: 1px solid #999;
            text-align: center;
            font-family: 'Roboto','sans-serif';
            line-height: 30px;
            padding-left: 10px;
        }
        #floating-panel {
            background-color: #fff;
            border: 1px solid #999;
            left: 25%;
            padding: 5px;
            position: absolute;
            top: 10px;
            z-index: 5;
        }
    </style>
</head>

<body>

<?php
$locations = [];
$dados = [];

foreach ($escolas as $k => $v) {
    // $cie = $v['cie_escola'];
    $locations[] = [
        'lat' => $v['latitude'],
        'long' => $v['longitude'],
        // 'img' => HOME_URI .'/'. INCLUDE_FOLDER .'/images/pessoa.png',
    ];
}

if (!empty($id_tp_ens) && @$tipo == 'loc' && !empty($heat)) {

    foreach ($heat as $v) {
        // $id = $v['fk_id_pessoa'];
        $locations[] = [
            'lat' => $v['latitude'],
            'long' => $v['longitude'],
            'img' => HOME_URI .'/'. INCLUDE_FOLDER .'/images/pessoa.png',
        ];
    }
}

if (!empty($id_tp_ens) && $tipo === 'calor' && !empty($heat)) {
    $dados = [
        "type" => "FeatureCollection",
        "name" => "dados",
        "features" => [],
    ];
    foreach ($heat as $v) {
        $dados["features"][] = [ 
            "type" => "Feature",
            "properties" => [ 
                "id_pessoa" => $v['fk_id_pessoa'] 
            ],
            "geometry" => [ 
                "type" => "MultiPoint",
                "coordinates" => [ 
                    [ $v['longitude'], $v['latitude'] ] 
                ] 
            ] 
        ];
    }
}

echo toolErp::geraMapaCalor($locations, $dados);

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
        ]

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: {lat: -23.5118213, lng: -46.86},
                // mapTypeId: 'satellite'
            });
            var infoWindow = new google.maps.InfoWindow;
            var image = '<?php echo HOME_URI ?>/views/_images/maps/pessoa.png';
<?php
foreach ($escolas as $k => $v) {
    $cie = $v['cie_escola'];
    ?>
                var marker<?php echo $cie ?> = new google.maps.Marker({
                    position: {
                        lat: <?php echo $v['latitude'] ?>,
                        lng: <?php echo $v['longitude'] ?>,
                        destination: 'EMEF+José+Leandro+de+Barros+Pimentel'
                    },
                    map: map
                });
                marker<?php echo $cie ?>.addListener('click', function () {
                    infoWindow.setContent('<?php echo $v['n_inst'] ?>');
                    infoWindow.open(map, marker<?php echo $cie ?>);
                });
    <?php
}
if (!empty($id_tp_ens) && @$tipo == 'loc' && !empty($heat)) {

    foreach ($heat as $v) {
        $id = $v['fk_id_pessoa'];
        ?>
                    var marker<?php echo $id ?> = new google.maps.Marker({
                        position: {
                            lat: <?php echo $v['latitude'] ?>,
                            lng: <?php echo $v['longitude'] ?>
                        },
                        map: map,
                        icon: image
                    });
                    marker<?php echo $id ?>.addListener('click', function () {
                        dadosAluno(<?php echo $id ?>);
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
    foreach ($heat as $v) {
        ?>
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

    <?php
    tool::modalInicio('width: 60%', 1);
    ?>
    <iframe id="aluno" name="aluno" style="border: none; width: 100%; height: 350px"></iframe>
    <?php
    tool::modalFim();
    ?>
</div>

<form target="aluno" action="<?php echo HOME_URI ?>/info/alunover" id="alunoVer" method="POST">
    <input id="id_pessoa" type="hidden" name="id_pessoa" value="" />
</form>