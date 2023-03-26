<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php
$pagina = 1;
$corN = [
    'Alfabético' => 'green',
    'Silábico alfabético' => 'orange',
    'Silábico com valor' => 'yellow',
    'Silábico sem valor' => 'pink',
    'Pré-silábico' => 'red'
];

$tt = 0;
foreach ($escritaa as $k => $v) {
    $tt += $v;
    @$jvEscrita .= " ['" . $k . "', " . $v . "],";
    @$jvEscritaEscola = substr(@$jvEscritaEscola, 0, -1);
}
@$jvEscrita = substr(@$jvEscrita, 0, -1);
if (empty($id_inst)) {
    ?>
    <div style="background-color: tomato; border-radius: 10px; padding: 15px">
        <div style="text-align: center; font-weight: bold; font-size: 22px;color: white; padding: 10px">
            Resultados Gerais
        </div>
        <br />
        <div class="fieldBorder2" style="background-color: white" >
            <div class="row">
                <div class="col-sm-4" style="z-index: 999">
                    <table style="font-size: 14; font-weight: bold" class="table table-bordered table-hover table-striped">
                        <tr  style=" background-color: wheat; padding: 5px">
                            <td >
                                Níveis de Escrita
                            </td>
                            <td>
                                Total
                            </td>
                            <td>
                                Porc.
                            </td>
                        </tr>
                        <?php
                        $ct = 1;
                        foreach ($escritaa as $k => $v) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $k ?>
                                </td>
                                <td>
                                    <?php echo $v ?>
                                </td>
                                <td>
                                    <?php echo round(($v / $tt) * 100, 1) . '%' ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr  style=" background-color: wheat; padding: 5px">
                            <td colspan="2">
                                Total de Avaliados
                            </td>
                            <td>
                                <?php echo $tt ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-8">
                    <script type="text/javascript">
                        google.charts.load('current', {'packages': ['corechart']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {

                            var data = google.visualization.arrayToDataTable([
                                ['Task', 'Hours per Day'],
    <?php echo $jvEscrita ?>
                            ]);

                            var options = {
                                title: 'Níveis de escrita'
                            };

                            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                            chart.draw(data, options);
                        }
                    </script>
                    <div id="piechart" style="width: 100%; height: 500px;margin-left: -10%"></div>
                </div>
            </div>
        </div>
    </div>
    <br />
    <?php
}
?>
<div style="page-break-after: always;"></div>
<?php
foreach ($escolas as $k => $v) {
    if (!empty($totalEscritaa[$k])) {
        $totalEscrita_[$k] = $totalEscritaa[$k];
    }
}
$ctesc = 1;
foreach ($totalEscrita_ as $k => $v) {
    $escritaEscola1 = $escritaEscola[$k];
    ksort($escritaEscola1);
    ?>
    <script type="text/javascript">
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Task', 'Hours per Day'],

    <?php
    $ct = 1;
    foreach (@$escritaEscola1 as $ke => $e) {
        ?>
                    ['<?php echo str_replace('\n', '', $ke) ?>', <?php echo $e ?>],
        <?php
    }
    ?>
            ]);

            var options = {
                title: ''
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart<?php echo $ctesc ?>'));

            chart.draw(data, options);
        }


    </script>
    <div style="background-color: #0076cb; border-radius: 10px; padding: 15px">
        <div style="text-align: center; font-weight: bold; font-size: 22px;color: white; padding: 10px">
            <?php echo $escolas[$k] ?>
        </div>
        <br />
        <div class="fieldBorder2" style="background-color: white" >
            <div class="row">
                <div class="col-sm-4" style="z-index: 999">
                    <table style="font-size: 14; font-weight: bold" class="table table-bordered table-hover table-striped">
                        <tr  style=" background-color: wheat; padding: 5px">
                            <td >
                                Níveis de Escrita
                            </td>
                            <td>
                                Total
                            </td>
                            <td>
                                Porc.
                            </td>
                        </tr>
                        <?php
                        $ct = 1;
                        $tt = 0;
                        foreach (@$escritaEscola[$k] as $kesc => $vesc) {
                            $tt += $vesc;
                        }
                        foreach (@$escritaEscola[$k] as $kesc => $vesc) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $kesc ?>
                                </td>
                                <td>
                                    <?php echo $vesc ?>
                                </td>
                                <td>
                                    <?php echo round(($vesc / $tt) * 100, 1) . '%' ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr  style=" background-color: wheat; padding: 5px">
                            <td colspan="2">
                                Total Avaliado
                            </td>
                            <td>
                                <?php echo $tt ?>
                            </td>
                        </tr>
                    </table>      
                </div>
                <div class="col-sm-8">
                    <div id="piechart<?php echo $ctesc ?>" style="width: 100%; height: 500px;margin-left: -10%"></div>
                </div>
            </div>
        </div>
    </div>
    <br />
    <?php
    if ($ctesc % 2 == 0) {
        ?>
        <div style="page-break-after: always;"></div>
        <?php
    }
    $ctesc++;
}
