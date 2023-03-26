<?php
$sql = "SELECT d.n_disc, c.n_competencia_opcoes, c.fk_id_disc_grupo, c.id_competencia_opcoes, IFNULL(a.hab1,0) AS nota1, IFNULL(a.hab2,0) AS nota2, IFNULL(a.hab3,0) AS nota3, IFNULL(a.hab4,0) AS nota4, IFNULL(a.hab5,0) AS nota5, IFNULL(a.hab6,0) AS nota6, IFNULL(a.hab7,0) AS nota7, IFNULL(a.hab8,0) AS nota8, IFNULL(a.hab9,0) As nota9, IFNULL(a.hab10,0) AS nota10, c.hab1, c.hab2, c.hab3, c.hab4, c.hab5, c.hab6, c.hab7, c.hab8, c.hab9, c.hab10 "
        . " FROM `hab_aluno` a"
        . " JOIN `hab_competencia_opcoes` c ON c.`id_competencia_opcoes` = a.`fk_id_competencia_opcoes`"
        . " JOIN hab_disc_grupo_" . date('Y') . " g ON g.`id_disc_grupo` = c.`fk_id_disc_grupo`"
        . " JOIN `ge_disciplinas` d ON d.`id_disc` = g.`fk_id_disc`"
        . " JOIN `ge_turma_aluno` t ON t.fk_id_pessoa = a.fk_id_pessoa"
        . " WHERE a.fk_id_pessoa = " . $_POST['id_pessoa_a']
        . " ORDER BY d.n_disc, c.id_competencia_opcoes";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
$cor = tool::cor();

//echo $sql;
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", {packages: ['corechart']});

    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {


<?php
foreach ($array as $k) {

    for ($i = 1; $i <= 10; $i++) {
        if (isset($k['hab' . $i])) {
            $nota = $k['nota' . $i];
            @$conceito_total++;
            if ($nota == 0) {
                $cor = "#FF0000";
                $conceito = "Não Adquirida";
                @$conceito_0++;
            } elseif ($nota == 0.5) {
                $cor = "#FFFF00";
                $conceito = "Parcialmente Adquirida";
                @$conceito_05++;
            } else {
                $cor = "#008000";
                $conceito = "Adquirida";
                @$conceito_1++;
            }
        }
    }
}
//calculo para gráfico pizza
$adq = 0;
$n_aqd = 0;
$p_adq = 0;
if (isset($conceito_1)) {
    $adq = $conceito_1 * 100 / $conceito_total;
}
if (isset($conceito_0)) {
    $n_aqd = $conceito_0 * 100 / $conceito_total;
}
if (isset($conceito_05)) {
    $p_adq = $conceito_05 * 100 / $conceito_total;
}
?>
        //Gráfico pizza após gerar todos os graficos de competencia
        var data_pizza = google.visualization.arrayToDataTable([
            ['Habilidade', 'Conceito'],
            ['Adquirida', <?php echo $adq ?>],
            ['Não Adquirida', <?php echo $n_aqd ?>],
            ['Parcialmente Adquirida', <?php echo $p_adq ?>],
        ]);

        var options_pizza = {
            title: 'Percentual Habilidades',
            is3D: true,
        };

        var chart_pizza = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart_pizza.draw(data_pizza, options_pizza);

    }
</script>
<div class="fieldBody">
    <div class="fieldTop">Relatório de Habilidades</div>
    <?php
    foreach ($array as $l) {
        ?>

        <div class="row" style="padding-top: 20px; padding-bottom: 20px;">

            <h3> <?php echo $l['n_competencia_opcoes'] ?></h3>

        </div>
        <?php
        for ($i = 1; $i <= 10; $i++) {
            if (isset($l['hab' . $i]) && $l['nota' . $i] <> 0) {
                $nota = $l['nota' . $i];
                ?>
                <div class="row" style="padding-left: 40px;">
                    <?php
                    if ($nota == 0.5) {
                        ?>
                        <img src="<?php echo HOME_URI ?>/pub/prata.png" width="25" height="45"/>

                        <?php
                    } elseif ($nota == 1) {
                        ?>
                        <img src="<?php echo HOME_URI ?>/pub/ouro.png" width="25" height="45"/>
                        <?php
                    }
                    ?>

                    <?php echo $l['hab' . $i] ?> - 

                </div>

                <?php
            }
        }
    }
    ?>
</div>

<?php
?>
<h2>Conceito Total de Habilidades</h2>
<div id="piechart_3d" style="width: 900px; height: 500px;"></div>