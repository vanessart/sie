<?php
$cor = tool::cor();
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load("current", {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Element", "Média", {role: "style"}],
<?php
$t = count($v);
$i = 1;
foreach ($graf['fields'] as $kk => $vv) {
    ?>
                ["<?php echo $kk ?>", <?php echo round($vv, 2) ?>, "<?php echo $cor[$i] ?>"],
    <?php
    if($i <19){
        $i++;
    } else {
    $i=1;    
    }
}
?>



        ]);

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            {calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"},
            2]);

        var options = {
            title: "<?php echo $graf['titulo'] ?>",
            width: 1700,
            height: 400,
            bar: {groupWidth: "95%"},
            legend: {position: "none"},
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
        chart.draw(view, options);
    }
</script>
<div id="columnchart_values" style="width: 100%; height: 300px;page-break-after: always"></div>
