<?php
$ano = date("Y");

function graficoRendimentoConsulta() {
    $id_pl = ng_main::periodoSet();

    $sql = "select i.n_inst, i.id_inst, c.n_ciclo, c.id_ciclo, t.periodo_letivo, ta.situacao, sf.n_sf "
            . "from ge_turma_aluno ta "
            . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
            . " join ge_ciclos c on c.id_ciclo = t.fk_id_ciclo "
            . " join instancia i on i.id_inst = t.fk_id_inst "
            . " join ge_situacao_final sf on sf.id_sf = ta.situacao_final "
            . " where ta.situacao = 'Frequente' "
            . " and t.fk_id_pl = $id_pl " // 81 = ano de 2020
            . " and i.id_inst = " . tool::id_inst()
            . " order by n_inst, n_ciclo, n_sf";

    $query = autenticador::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);

    return $array;
}

//Consulta para gráfico de rendimento
$array = graficoRendimentoConsulta();

foreach ($array as $v) {
    @$result[$v['n_ciclo']][$v['n_sf']]++;
}
?>

<div class="fieldBody">
    <?php
    echo $header = '<table style="width: 100%">'
    . '<tr>'
    . '<td>'
    . '<img style="width: 80px" src="' . HOME_URI . '/views/_images/brasao.png"/>'
    . '</td>'
    . '<td style=" text-align: right">'
    . '<img style="width: 350px;" src="' . HOME_URI . '/views/_images/logose.png"/>'
    . '</td>'
    . '</table>';
    ?>
    <div class="fieldTop" style="font-size: 20px">
        Rendimento <?php echo $ano ?>
    </div>

    <br /><br />
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <?php
    $c = 1;

    //Operação ternária para verificar se $result está setado ou não
    $result = isset($result) ? $result : [];

    if ($result) {
        foreach ($result as $k => $v) {
            ?>
            <script type="text/javascript">
                google.charts.load('current', {'packages': ['corechart']});
                google.charts.setOnLoadCallback(drawChart<?php echo $c ?>);
                function drawChart<?php echo $c ?>() {

                    var data = google.visualization.arrayToDataTable([
                        ['Ciclo', 'Situação'],
        <?php
        foreach ($v as $kk => $vv) {
            ?>
                            ['<?php echo $kk ?> (<?php echo $vv ?>)', <?php echo $vv ?>],
            <?php
        }
        ?>


                    ]);

                    var options = {
                        title: '<?php echo $k ?>'
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('piechart<?php echo $c ?>'));

                    chart.draw(data, options);
                }
            </script>
            <div id = "piechart<?php echo $c ?>" style = "width: 1500px; height: 600px; <?php echo $c % 2 == 0 ? 'page-break-after: always;' : '' ?>" > </div>

            <?php
            $c++;
        }
    } else {
        echo "<p class='erro'>NÃO HÁ DADOS PARA RELATÓRIO</p>";
    }
    ?>
</div>

<style>
    .erro {
        text-align:center;
        font-size:1.8rem;
    }
</style>