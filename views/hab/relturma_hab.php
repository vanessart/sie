<?php
//RELATÓRIO DE HABILIDADES DE APENAS UMA COMPETENCIA
$id_competencia_opcoes = 18;
$sql = "SELECT d.n_disc, c.n_competencia_opcoes, c.fk_id_disc_grupo, c.id_competencia_opcoes, IFNULL(a.hab1*c.peso1,0) AS nota1, IFNULL(a.hab2*c.peso2,0) AS nota2, IFNULL(a.hab3*c.peso3,0) AS nota3, IFNULL(a.hab4*c.peso4,0) AS nota4, IFNULL(a.hab5*c.peso5,0) AS nota5, IFNULL(a.hab6*c.peso6,0) AS nota6, IFNULL(a.hab7*c.peso7,0) AS nota7, IFNULL(a.hab8*c.peso8,0) AS nota8, IFNULL(a.hab9*c.peso9,0) As nota9, IFNULL(a.hab10*c.peso10,0) AS nota10, c.hab1, c.hab2, c.hab3, c.hab4, c.hab5, c.hab6, c.hab7, c.hab8, c.hab9, c.hab10 "
        . " FROM `hab_aluno` a"
        . " JOIN `hab_competencia_opcoes` c ON c.`id_competencia_opcoes` = a.`fk_id_competencia_opcoes`"
        . " JOIN hab_disc_grupo_" . date('Y') . " g ON g.`id_disc_grupo` = c.`fk_id_disc_grupo`"
        . " JOIN `ge_disciplinas` d ON d.`id_disc` = g.`fk_id_disc`"
        . " WHERE a.fk_id_pessoa = " . $_POST['id_pessoa_a'] . " AND id_competencia_opcoes = ".$id_competencia_opcoes
        . " ORDER BY d.n_disc";
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
        var data = google.visualization.arrayToDataTable([
            ["Habilidade", "Nota", {role: "style"}],

<?php
foreach ($array as $k) {

    $competencia = $k['n_competencia_opcoes'];
    $disciplina = $k['n_disc'];

    for ($i = 1; $i <= 10; $i++) {
        if (isset($k['hab' . $i])) {
            ?>

                        ["<?php echo "Habilidade " . $i ?>", <?php echo $k['nota' . $i] ?>, "<?php echo $cor[$i] ?>"],
            <?php
        }
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
            title: "Habilidades",
            width: 700,
            height: 400,
            //bar: {groupWidth: "95%"},
            legend: {position: "none"},

        };
        var chart = new google.visualization.BarChart(document.getElementById("columnchart_values"));
        chart.draw(view, options);
    }
</script>
<div class="fieldBody">
    <div class="fieldTop">Gráfico de Habilidades</div>

    <div class="row">
        <div class="col-md-6">
            <div id="columnchart_values"></div>
        </div>
        <div class="col-md-6">
            <div class="row" style="padding-top: 40px;">
                <div class="col-md-1"> </div>
                <div class="col-md-11">
                    <h4>Competência:</h4><h4> <?php echo $competencia ?></h4>
                </div>
            </div>
            <?php
            foreach ($array as $j) {

                for ($i = 1; $i <= 10; $i++) {
                    if (isset($k['hab' . $i])) {
                        ?>
                        <div class="row">
                            <div class="col-md-1">
                                <?php echo $i ?>- 
                            </div>
                            <div class="col-md-11">
                                <?php echo $k['hab' . $i] ?> - 
                            </div>
                        </div>

                        <?php
                    }
                }
            }
            ?>
        </div>


    </div>
</div>