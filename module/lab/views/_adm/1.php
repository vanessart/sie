<?php
if (!defined('ABSPATH'))
    exit;
$dtCad = $dashboard['dataCad'];

$inicio = date('Y-m-d', strtotime('-30 days', strtotime(date("Y-m-d"))));
$fim = date("Y-m-d");
$dts = dataErp::datasPeriodo($inicio, $fim);
?>
<script type="text/javascript">
    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Dias', 'Recadastramento Diário'],
<?php
foreach ($dts as $dt) {
    ?>
                ['<?= substr(data::converteBr($dt), 0, 5) ?>', <?= intval(@$dtCad[@$dt]) ?>],
    <?php
}
?>
        ]);

        var options = {
            //hAxis: {title: 'Dias', titleTextStyle: {color: '#333'}},
            //vAxis: {minValue: 0}
            legend: {position: 'top'}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div1'));
        chart.draw(data, options);
    }
</script>
<div id="chart_div1" style="width: 100%; height: 200px;"></div>
