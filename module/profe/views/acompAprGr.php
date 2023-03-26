<?php
if (!defined('ABSPATH'))
    exit;
$id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$sql = "SELECT id_ciclo, n_ciclo, fk_id_curso FROM `ge_ciclos` WHERE `fk_id_curso` IN (3,7,8) ORDER BY `ge_ciclos`.`n_ciclo` ASC ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($array as $v) {
    $ciclos[$v['id_ciclo']] = $v['n_ciclo'];
    $idCursos[$v['id_ciclo']] = $v['fk_id_curso'];
}

if ($id_ciclo) {

    if (!empty($id_inst)) {
        $filter = ['id_ciclo' => $id_ciclo, 'id_inst' => $id_inst];
        $alunosEsc = " and t.fk_id_inst = $id_inst ";
    } else {
        $filter = ['id_ciclo' => $id_ciclo];
        $alunosEsc = "";
    }
    $escolas = ng_escolas::idEscolas([$idCursos[$id_ciclo]]);
    //aluno e data nascimento
    $sql = "select p.id_pessoa, p.dt_nasc from ge_turma_aluno ta "
            . " join pessoa p on p.id_pessoa = ta.fk_id_pessoa "
            . " join ge_turmas t on t.id_turma = ta.fk_id_turma and ta.fk_id_tas = 0 and fk_id_ciclo = $id_ciclo $alunosEsc"
            . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and at_pl = 1 ";
    $query = pdoSis::getInstance()->query($sql);
    $a = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($a as $v) {
        $alu[$v['id_pessoa']] = $v['dt_nasc'];
    }
    $aluTotal = count($alu);

    $mongo = new mongoCrude('Diario');
    $sond = $mongo->query('sondagem.' . $idCursos[$id_ciclo] . '.' . date("Y"), $filter);
    foreach ($sond as $v) {
        $id_pessoa = $v->id_pessoa;
        foreach ($v->hab as $kh => $h) {
            if (!empty($alu[$id_pessoa])) {
                $idade = data::calcula($alu[$id_pessoa], $h, 'mes');
                // if ($idade > 24) {
                //      $idade = 'mais';
                //  }
                if ($idade > 0 && $idade < 216) {
                    @$habQt[$kh]++;
                    @$habQtMes[$kh][$idade]++;
                }
            }
        }
    }

    if (!empty($habQtMes)) {
        $min = 100;
        $max = 0;
        foreach ($habQtMes as $v) {
            foreach ($v as $i => $y) {
                if ($min > $i) {
                    $min = $i;
                }
                if ($max < $i) {
                    $max = $i;
                }
            }
        }
    }
    $sondagensCurso = $model->sondagensCurso();
    $id_gh = $sondagensCurso[$idCursos[$id_ciclo]];
    $sql = "SELECT * FROM `coord_hab` h "
            . " join coord_campo_experiencia e on e.id_ce = h.fk_id_ce "
            . " and h.`fk_id_gh` =  $id_gh "
            . " join coord_hab_ciclo ci on ci.fk_id_hab = h.id_hab and ci.fk_id_ciclo = $id_ciclo "
            . " order by e.n_ce ";
    $query = pdoSis::getInstance()->query($sql);
    $hab = $query->fetchAll(PDO::FETCH_ASSOC);
    $c = 0;
    foreach ($hab as $k => $v) {
        @$hab[@$v['id_ce'] . str_pad(@$habQt[$v['id_hab']], 5, "0", STR_PAD_LEFT) . str_pad($c++, 5, "0", STR_PAD_LEFT)] = $v;
        unset($hab[$k]);
        @$ceQt[$v['fk_id_ce']]++;
    }
    ksort($hab);
}
?>
<div class="body">
    <div class="fieldTop">
        Acompanhamento de Aprendizagem - Rede
    </div>
    <div class="row">
        <div class="col">
            <?php echo formErp::select('id_ciclo', $ciclos, 'Ciclo', $id_ciclo, 1); ?>
        </div>
        <?php
        if ($id_ciclo) {
            ?>
            <div class="col">
                <?php echo formErp::select('id_inst', $escolas, ['Escola', 'Todas'], $id_inst, 1, ['id_ciclo' => $id_ciclo]); ?>
            </div>
            <?php
        }
        ?>
    </div>
    <br />
    <?php
    if ($id_ciclo) {
        ?>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

        <table class="table table-bordered table-hover table-responsive">
            <?php
            $id_ce = 0;
            foreach ($hab as $h) {
                ?>
                <tr>
                    <?php
                    if ($id_ce != $h['id_ce']) {
                        ?>
                        <td rowspan="<?= $ceQt[$h['id_ce']] ?>" style="writing-mode: vertical-rl; text-orientation: mixed;border: #000 thin solid; padding: 5px; width: 50px; font-weight: bold; font-size: 1.8em !important">
                            <?= $h['n_ce'] ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td style="border: #000 thin solid !important; padding: 5px; width: 800px">
                        <table class="table table-bordered table-hover table-responsive">
                            <tr>
                                <td style="width: 35%;font-weight: bold; font-size: 1.6em !important">
                                    <?= $h['codigo'] ?> - <?= $h['descricao'] ?>

                                </td>
                                <td style="width: 15%;font-weight: bold; font-size: 1.6em !important">
                                    <?php
                                    if ($aluTotal) {
                                        $porc = round((@$habQt[$h['id_hab']] / $aluTotal) * 100, 2);
                                    } else {
                                        $porc = 0;
                                    }
                                    echo $porc . '%'
                                    ?>
                                </td>
                                <td style="width: 50%">
                                    <?php
                                    echo '<div class="progress"><div class="progress-bar" style="width: ' . $porc . '%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">' . $porc . '%</div></div>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="width: 80%">


                                    <script type="text/javascript">
                                        google.charts.load('current', {'packages': ['corechart']});
                                        google.charts.setOnLoadCallback(drawChart);

                                        function drawChart() {
                                            var data = google.visualization.arrayToDataTable([
                                                ['Meses', 'Aferição Por Idade em Meses'],
        <?php
        foreach (range($min, $max) as $r) {
            echo "['$r'," . intval(@$habQtMes[$h['id_hab']][$r]) . "],";
        }
        ?>
                                            ]);

                                            var options = {
                                                //hAxis: {title: 'Dias', titleTextStyle: {color: '#333'}},
                                                //vAxis: {minValue: 0}
                                                legend: {position: 'top'}
                                            };

                                            var chart = new google.visualization.AreaChart(document.getElementById('chart_div<?= $h['id_hab'] ?>'));
                                            chart.draw(data, options);
                                        }
                                    </script>
                                    <div id="chart_div<?= $h['id_hab'] ?>" style="width: 100%; height: 200px;"></div> 
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php
                $id_ce = $h['id_ce'];
            }
            ?>
        </table>
        <?php
    }
    ?>
</div>



<?php
##################            
?>
<pre>   
    <?php
    print_r(@$habQtMes);
    ?>
</pre>
<?php
###################
?>