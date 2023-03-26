<?php
$cor = tool::cor();
$i = 1;
foreach ($graf['fields'] as $k => $v) {
    $data[] = " ['$k', $v, $v]";
    if ($i < 19) {
        $i++;
    } else {
        $i = 1;
    }
}
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div id="chart_div" style="height: 4000px; width: 90%"></div>
<script>
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawDualX);

    function drawDualX() {
        var data = google.visualization.arrayToDataTable([
            ['', '', ''],
<?php echo implode(',', $data) ?>
        ]);

        var materialOptions = {
            chart: {
                title: '<?php echo @$graf['titulo'] ?>',
                subtitle: '<?php echo @$graf['subtitulo'] ?>'
            },
            hAxis: {
                title: '<?php echo @$graf['titulo'] ?>'
            },
            vAxis: {
                title: '<?php echo @$graf['titulo'] ?>'
            },
            bars: 'horizontal',
            series: {
                0: {axis: '2010'},
                1: {axis: '2000'}
            },
            axes: {
                x: {
                    2010: {label: '<?php echo @$graf['titulo'] ?>', side: 'top'},
                    2000: {label: '<?php echo @$graf['titulo'] ?>'}
                }
            }
        };
        var materialChart = new google.charts.Bar(document.getElementById('chart_div'));
        materialChart.draw(data, materialOptions);
    }
</script>