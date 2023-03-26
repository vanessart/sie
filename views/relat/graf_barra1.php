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
<div id="chart_div" style="height: 4000px; width: 100%;margin-top: -400px"></div>
<script>
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawBasic);

    function drawBasic() {

        var data = google.visualization.arrayToDataTable([
            ['<?php echo @$graf['legenda'] ?>', '<?php echo @$graf['legenda'] ?>', {role: 'style'}, {role: 'annotation'}],
<?php echo implode(',', $data) ?>
        ]);

        var options = {
            title: "<?php echo $graf['titulo'] ?>",
            width: '100%',
            height: 4000,
            bar: {groupWidth: "55%"},
            legend: {position: "none"},
        };

        var chart = new google.visualization.BarChart(document.getElementById('chart_div'));

        chart.draw(data, options);
    }
</script>