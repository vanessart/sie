<?php
$cor = tool::cor();
$i = 1;
foreach ($graf['fields'] as $k => $v) {
    $data[] = " ['$k', $v, '" . $cor[$i] . "', '$v']";
    if ($i < 19) {
        $i++;
    } else {
        $i = 1;
    }
}
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Ano', '', {role: 'style'}, {role: 'annotation'}],
            <?php echo implode(',', $data) ?>
        ]);

        var options = {
            title: '<?php echo $graf['titulo'] ?>',
            hAxis: {title: '<?php echo $graf['titulo'] ?>', titleTextStyle: {color: '#333'}},
            vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
</script>
<div id="chart_div" style="width: 100%; height: 500px;"></div>
