<?php
ini_set('memory_limit', '2000M');
if (empty($_POST['periodoLetivo'])) {
    $periodoLetivo = sql::get('ge_setup', 'fk_id_pl', ['id_set' => 1], 'fetch')['fk_id_pl'];
} else {
    $periodoLetivo = $_POST['periodoLetivo'];
}
@$id_agrup = @$_POST['id_agrup'];
$hidden['id_agrup'] = @$id_agrup;

if ($_SESSION['userdata']['id_nivel'] == 8) {
    $id_inst = tool::id_inst();
} else {
    $id_inst = @$_POST['id_inst'];
}
$hidden['id_inst'] = $id_inst;

$escolas = escolas::idInst();
?>
<div class="fieldBody">
    <div class="fieldTop">
        Avaliação Diagnóstica
    </div>

    <div class="noprint">
        <br /><br />
        <div class="row">
            <div class="col-md-6">
                <?php
                if ($_SESSION['userdata']['id_nivel'] == 8) {
                    $where = ['ativo' => 1, '>' => 'n_agrup'];
                } else {
                    $where = ['>' => 'n_agrup'];
                }
                $agrup = sql::idNome('global_agrupamento', @$where);
                formulario::select('id_agrup', $agrup, 'Agrupamento', @$id_agrup, 1);
                ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <?php
            if (!empty(@$id_agrup)) {
                ?>
                <form method="POST">
                    <div class="col-md-5">
                        <?php
                        formulario::select('id_inst', $escolas, 'Escola', @$id_inst, NULL, @$hidden);
                        $hidden['id_inst'] = $id_inst;
                        ?>
                    </div>

                    <div class="col-md-3">
                        <?php
                        $sql = "SELECT * FROM `ge_ciclos` WHERE `id_ciclo` IN (1,2,3,4,5,6,7,8,9,25,26,33) ORDER BY `ge_ciclos`.`n_ciclo` ASC ";
                        $query = $model->db->query($sql);
                        $array = $query->fetchAll();
                        foreach ($array as $v) {
                            $ciclos[$v['id_ciclo']] = $v['n_ciclo'];
                        }
                        ?>
                        <?php echo formulario::select('id_ciclo', $ciclos, 'Ciclo') ?>
                    </div>
                    <div class="col-md-3">
                        <input class="btn btn-info" name="acessarGrafico" type="submit" value="Acessar Gráfico" />
                    </div>
                </form>

                <?php
            }
            ?>
        </div>
        <br /><br />
    </div>
    <?php
    if (!empty($_POST['id_ciclo'])) {
        if (!empty($_POST['id_inst'])) {
            $inst = " AND t.fk_id_inst = " . $_POST['id_inst'];
        }


        $sql = "SELECT "
                . "(sum(r.nota)/count(r.nota)) as media, a.abrev_gl"
                . " FROM global_result r "
                . " join global_aval a on a.id_gl = r.fk_id_gl "
                . " join ge_turmas t on t.id_turma = r.fk_id_turma "
                . " WHERE t.fk_id_ciclo = ".$_POST['id_ciclo']
           
                  . " and a.fk_id_agrup = ".$_POST['id_agrup']
              . @$inst
                . " group by a.abrev_gl "
                . " order by abrev_gl ";
        $query = $model->db->query($sql);
        $grafico = $query->fetchAll();
        ?>

        <div style="text-align: center; font-size: 25px">
            <?php echo sql::get('ge_ciclos', 'n_ciclo', ['id_ciclo' => @$_POST['id_ciclo']], 'fetch')['n_ciclo'] ?>
        </div>
        <table style="width: 100%">
            <tr> 
                <td>
                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <script type="text/javascript">
                        google.charts.load("current", {packages: ['corechart']});
                        google.charts.setOnLoadCallback(drawChart);
                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                                ["Element", "Média", {role: "style"}],
    <?php
    $cor = 1;
    foreach ($grafico as $v) {
        ?>
                                    ["<?php echo $v['abrev_gl'] ?>", <?php echo round($v['media'], 2) ?>, "<?php echo tool::cor($cor++) ?>"],
        <?php
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
                                width: 1600,
                                height: 500,
                                bar: {groupWidth: "95%"},
                                legend: {position: "none"},
                            };
                            var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
                            chart.draw(view, options);
                        }
                    </script>
                    <div id="columnchart_values" style="width: 100%; height: 600px; "></div>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <table>
                        <tr>
                            <td>

                                <table>
                                    <?php
                                    $cor = 1;
                                    foreach ($grafico as $v) {
                                        ?>
                                        <tr>
                                            <td style="width: 200px">
                                                <?php echo $v['abrev_gl'] ?>
                                            </td>
                                            <td>
                                                <?php echo round($v['media'], 2) ?>
                                            </td>
                                            <td style="padding: 5px">
                                                <div style="background-color: <?php echo tool::cor($cor++) ?>">
                                                    &nbsp;
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table>
                            </td>
                            <td style="width: 300px">
                                &nbsp;
                            </td>
                            <td>
                                <div style="text-align: center; font-size: 25px">
                                    cálculo
                                </div>
                                <br /><br />
                                cálculo das questões: questão = 10/Número de questoes;
                                <br /><br />
                                cálculo das alternativas (com 3 alternativas): alternativas = 0, questão/2, questão
                                <br /><br />
                                cálculo das alternativas (com 4 alternativas): alternativas = 0, questão/3, (questão/3)*2, questão

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <?php
    }
    ?>
</div>
