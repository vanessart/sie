<?php
$sql = "SELECT d.n_disc, c.n_competencia_opcoes, c.fk_id_disc_grupo, c.id_competencia_opcoes, IFNULL(a.hab1,0) AS nota1, IFNULL(a.hab2,0) AS nota2, IFNULL(a.hab3,0) AS nota3, IFNULL(a.hab4,0) AS nota4, IFNULL(a.hab5,0) AS nota5, IFNULL(a.hab6,0) AS nota6, IFNULL(a.hab7,0) AS nota7, IFNULL(a.hab8,0) AS nota8, IFNULL(a.hab9,0) As nota9, IFNULL(a.hab10,0) AS nota10, c.hab1, c.hab2, c.hab3, c.hab4, c.hab5, c.hab6, c.hab7, c.hab8, c.hab9, c.hab10 "
        . " FROM `hab_aluno` a"
        . " JOIN `hab_competencia_opcoes` c ON c.`id_competencia_opcoes` = a.`fk_id_competencia_opcoes`"
        . " JOIN hab_disc_grupo_" . date('Y') . " g ON g.`id_disc_grupo` = c.`fk_id_disc_grupo`"
        . " JOIN `ge_disciplinas` d ON d.`id_disc` = g.`fk_id_disc`"
        . " WHERE a.fk_id_pessoa = " . $_POST['id_pessoa_a']
        . " ORDER BY c.id_competencia_opcoes";
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
    ?>
            var data<?php echo $k["id_competencia_opcoes"] ?> = google.visualization.arrayToDataTable([
                ["Habilidade", "Nota", {role: "style"}, {role: 'annotation'}],
    <?php
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
            ?>

                        ["<?php echo "Habilidade " . $i ?>", <?php echo $nota ?>, "<?php echo $cor ?>", "<?php echo $conceito ?>"],
            <?php
        }
    }
    ?>


            ]);

            var view<?php echo $k["id_competencia_opcoes"] ?> = new google.visualization.DataView(data<?php echo $k["id_competencia_opcoes"] ?>);


            var options<?php echo $k["id_competencia_opcoes"] ?> = {
                title: "Habilidades",
                width: 700,
                height: 400,
                bar: {groupWidth: 20},
                legend: {position: "none"},
                hAxis: {
                    viewWindow: {
                        min: 0,
                        max: 1
                    },
                    ticks: [0, 0.5, 1] // display labels every 2.5
                }

            };
            var chart<?php echo $k["id_competencia_opcoes"] ?> = new google.visualization.BarChart(document.getElementById("columnchart_values<?php echo $k["id_competencia_opcoes"] ?>"));
            chart<?php echo $k["id_competencia_opcoes"] ?>.draw(view<?php echo $k["id_competencia_opcoes"] ?>, options<?php echo $k["id_competencia_opcoes"] ?>);

    <?php
   
}
 //calculo para gráfico pizza
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
    <div class="fieldTop">Gráfico de Habilidades</div>
<?php
foreach ($array as $l) {
    ?>

        <div class="row">
            <div class="col-md-6">
                <div id="columnchart_values<?php echo $l["id_competencia_opcoes"] ?>"></div>
            </div>
            <div class="col-md-6">
                <div class="row" style="padding-top: 40px;">
                    <div class="col-md-1"> </div>
                    <div class="col-md-11">
                        <h4>Competência:</h4><h4> <?php echo $l['n_competencia_opcoes'] ?></h4>
                    </div>
                </div>
    <?php
    for ($i = 1; $i <= 10; $i++) {
        if (isset($l['hab' . $i])) {
            ?>
                        <div class="row">
                            <div class="col-md-1">
            <?php echo $i ?>- 
                            </div>
                            <div class="col-md-11">
            <?php echo $l['hab' . $i] ?> - 
                            </div>
                        </div>

            <?php
        }
    }
    ?>
            </div>
        </div>
    <?php
}
?>
    <h2>Conceito Total de Habilidades</h2>
    <div id="piechart_3d" style="width: 900px; height: 500px;"></div>
</div>