<?php
//"SELECT d.n_disc, c.n_competencia_opcoes, c.fk_id_disc_grupo, c.id_competencia_opcoes, a.hab1*c.peso1 AS 'nota1', a.hab2*c.peso2 AS 'nota2', a.hab3*c.peso3 AS 'nota3', a.hab4*c.peso4 AS 'nota4', a.hab5*c.peso5 AS 'nota5', a.hab6*c.peso6 AS 'nota6', a.hab7*c.peso7 AS 'nota7', a.hab8*c.peso8 AS 'nota8', a.hab9*c.peso9 AS 'nota9', a.hab10*c.peso10 AS 'nota10'"
$sql = "SELECT d.n_disc, c.n_competencia_opcoes, c.fk_id_disc_grupo, c.id_competencia_opcoes, IFNULL(a.hab1*c.peso1,0) + IFNULL(a.hab2*c.peso2,0) + IFNULL(a.hab3*c.peso3,0) + IFNULL(a.hab4*c.peso4,0) + IFNULL(a.hab5*c.peso5,0) + IFNULL(a.hab6*c.peso6,0) + IFNULL(a.hab7*c.peso7,0) + IFNULL(a.hab8*c.peso8,0) + IFNULL(a.hab9*c.peso9,0) + IFNULL(a.hab10*c.peso10,0) AS 'nota'"
        . " FROM `hab_aluno` a"
        . " JOIN `hab_competencia_opcoes` c ON c.`id_competencia_opcoes` = a.`fk_id_competencia_opcoes`"
        . " JOIN hab_disc_grupo_" . date('Y') . " g ON g.`id_disc_grupo` = c.`fk_id_disc_grupo`"
        . " JOIN `ge_disciplinas` d ON d.`id_disc` = g.`fk_id_disc`"
        . " WHERE a.fk_id_pessoa = " . $_POST['id_pessoa_a']
        . " ORDER BY d.n_disc";

?>
                                <pre>
                                    <?php
                                    print_r($sql)
                                    ?>
                                </pre>
                                <?php
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);

$cht_aux = 0;
$disciplinaCht = '';
foreach ($array as $j) {
    if($disciplinaCht != $j['n_disc']){
        $disciplinaCht = $j['n_disc'];
        $cht_aux++;
?>
<h3>Disciplina: <strong><?php echo $disciplinaCht ?></strong></h3>
<hr>
    <div id="chart_div<?= $cht_aux ?>" style="width: 900px; height: 500px;"></div>
<?php }
} 
?>

<!--script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script-->
<script type="text/javascript" src="../../views/_js/chart.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(drawVisualization);

    var colors = ['#0086c6', '#795ca3', '#795ca3', '#e44c83', '#00bbb7', '#fff400', '#ff4d31'];
    var posVetChart=-1;
    function getColorChart(vet){
        if(vet.length < posVetChart) posVetChart=-1;
        posVetChart++;
        //return 'bar {color: '+ vet[posVetChart] +'}';
        return 'color: '+ vet[Math.floor(Math.random() * vet.length)];
    }
    
    function drawVisualization() {
        <?php
        $cht = 0;
        $cht_aux = 0;
        $disciplinaCht = '';
        foreach ($array as $j) {
            if($disciplinaCht != $j['n_disc']){
                $disciplinaCht = $j['n_disc'];
            
                if ($cht>0){
            ?>    
                    ]);
                    
                    var chart<?= $cht_aux ?> = new google.visualization.ComboChart(document.getElementById('chart_div<?= $cht_aux ?>'));
                    chart<?= $cht_aux ?>.draw(data<?= $cht_aux ?>, options<?= $cht_aux ?>);
            <?php }
            $cht_aux++;
            ?>
                var options<?= $cht_aux ?> = {
                    //title: 'Disciplina: <?php echo $disciplinaCht ?>',
                    vAxis: {title: 'Nota'},
                    hAxis: {title: 'Competências'},
                    seriesType: 'bars',
                    series: {1: {type: 'line'}},
                    width: 900,
                    height: 500
                    //colors: ['#e0440e', '#e6693e', '#ec8f6e', '#f3b49f', '#f6c7b6']
                };
                
                // Some raw data (not necessarily accurate)
                var data<?= $cht_aux ?> = google.visualization.arrayToDataTable([
                    ['competências', 'Nota', {role: 'style'}, {role: 'style'}],
            <?php }

            echo "['" . $j['n_competencia_opcoes'] . "'," . $j['nota'] . ", getColorChart(colors), getColorChart(colors)],\n";
            $cht++;
        }
        if ($cht>0){ ?>
            ]);
    
            var options<?= $cht_aux ?> = {
                //title: 'Disciplina: <?php echo $disciplinaCht ?>',
                vAxis: {title: 'Nota'},
                hAxis: {title: 'Competências'},
                seriesType: 'bars',
                series: {1: {type: 'line'}},
                width: 900,
                height: 500
                //colors: ['#e0440e', '#e6693e', '#ec8f6e', '#f3b49f', '#f6c7b6']
            };

            var chart<?= $cht_aux ?> = new google.visualization.ComboChart(document.getElementById('chart_div<?= $cht_aux ?>'));
            chart<?= $cht_aux ?>.draw(data<?= $cht_aux ?>, options<?= $cht_aux ?>);
        <?php } ?>
    }
</script>


