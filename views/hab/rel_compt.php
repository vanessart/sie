<?php
if ($_POST['id_filtro'] == 1) {
    $turmas = hab::idturmas($_POST['id_inst']);
}


$sql = "SELECT t.id_turma_aluno, count(id_pessoa) AS alunos, t.fk_id_inst, tt.n_turma, tt.id_turma, d.n_disc, c.n_competencia_opcoes, c.fk_id_disc_grupo, c.id_competencia_opcoes, sum(IFNULL(a.hab1,0))+ sum(IFNULL(a.hab2,0)) + sum(IFNULL(a.hab3,0))+ sum(IFNULL(a.hab4,0)) + sum(IFNULL(a.hab5,0)) + sum(IFNULL(a.hab6,0)) + sum(IFNULL(a.hab7,0)) + sum(IFNULL(a.hab8,0))+ sum(IFNULL(a.hab9,0)) + sum(IFNULL(a.hab10,0)) AS nota "
        . "FROM `hab_aluno` a "
        . "JOIN `hab_competencia_opcoes` c ON c.`id_competencia_opcoes` = a.`fk_id_competencia_opcoes` "
        . "JOIN pessoa on pessoa.id_pessoa = a.fk_id_pessoa "
        . "JOIN hab_disc_grupo_2017 g ON g.`id_disc_grupo` = c.`fk_id_disc_grupo` "
        . "INNER JOIN ge_cursos ON ge_cursos.id_curso = g.fk_id_curso "
        . "INNER JOIN ge_ciclos ON ge_ciclos.id_ciclo = g.fk_id_ciclo "
        . "INNER JOIN ge_turma_aluno t ON t.fk_id_pessoa = a.fk_id_pessoa "
        . "INNER JOIN ge_turmas tt ON tt.id_turma = t.fk_id_turma "
        . "INNER JOIN ge_disciplinas d ON g.fk_id_disc = d.id_disc "
        . "WHERE t.fk_id_inst = " . $_POST['id_inst']
        . " GROUP BY c.n_competencia_opcoes "
        . "ORDER BY tt.n_turma, c.id_competencia_opcoes";
//echo $sql;

$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
$cor = tool::cor();
$cor = 1;
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", {packages: ['corechart']});

    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
<?php
foreach ($array as $j) {
    if (@$id_turma <> $j["id_turma"]) {
        ?>
                var data<?php echo $j["id_turma"] ?> = google.visualization.arrayToDataTable([
                    ["Habilidade", "Nota", {role: "style"}, {role: 'annotation'}],
        <?php
    }
    foreach ($array as $k) {
        if (isset($k['n_competencia_opcoes'])) {
            $nota = ($k['nota'] * 100 / $k['alunos']);
            $cor++;
            ?>
                        ["<?php echo $k['n_competencia_opcoes'] ?>", <?php echo $nota ?>, "<?php echo $cor ?>", "<?php echo $nota ?>"],
            <?php
        }
    }
    if (@$id_turma <> $j["id_turma"]) {
        ?>
                ]);
                var view<?php echo $j["id_turma"] ?> = new google.visualization.DataView(data<?php echo $j["id_turma"] ?>);
                var options<?php echo $j["id_turma"] ?> = {
                    title: "Habilidades",
                    width: 700,
                    height: 400,
                    bar: {groupWidth: 20},
                    legend: {position: "none"},
                    hAxis: {
                        viewWindow: {
                            min: 0,
                            max: 100
                        },
                        ticks: [0, 25, 50, 75, 100] // display labels every 2.5
                    }

                };
                var chart<?php echo $j["id_turma"] ?> = new google.visualization.BarChart(document.getElementById("columnchart_values<?php echo $j["id_turma"] ?>"));
                chart<?php echo $j["id_turma"] ?>.draw(view<?php echo $j["id_turma"] ?>, options<?php echo $j["id_turma"] ?>);
        <?php
        $id_turma = $j["id_turma"];
    }
}
?>
    }
</script>
<div class="fieldBody">
    <div class="fieldTop">Gráfico de Habilidades</div>
    <?php
    foreach ($array as $l) {
        if (@$turma <> $l["id_turma"]) {
            ?>
            <div class="fieldTop"><?php echo $l["n_turma"] ?></div>
            <?php
        }
        if (@$competencia <> $l["id_competencia_opcoes"]) { //verifica se já imprimiu a competencia
            ?>

            <div class="row">
                <div class="col-md-6">
                    <div id="columnchart_values<?php echo $l["id_competencia_opcoes"] ?>"></div>
                </div>
                <div class="col-md-6">
                    <div class="row" style="padding-top: 40px;">
                        <div class="col-md-1"> </div>
                        <div class="col-md-11">
                            <h4>Disciplina:</h4><h4> <?php echo $l['n_disc'] ?></h4>
                            <h4>Competência:</h4><h4> <?php echo $l['n_competencia_opcoes'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $competencia = $l["id_competencia_opcoes"];
            $turma = $l["id_turma"];
        }
    }
    ?>

</div>