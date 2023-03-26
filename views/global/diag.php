<?php
@$id_agrup = @$_POST['id_agrup'];
$hidden['id_agrup'] = @$id_agrup;
?>
<style>
    body {
        -webkit-print-color-adjust: exact;
    }
</style>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php
if (empty($_POST['periodoLetivo'])) {
    $periodoLetivo = sql::get('ge_setup', 'fk_id_pl', ['id_set' => 1], 'fetch')['fk_id_pl'];
} else {
    $periodoLetivo = $_POST['periodoLetivo'];
}
$cor = [
    1 => 'green',
    2 => 'orange',
    3 => 'yellow',
    4 => 'red'
];
if ($_SESSION['userdata']['id_nivel'] == 8) {
    $id_inst = tool::id_inst();
} else {
    $id_inst = @$_POST['id_inst'];
}

$id_turma = @$_POST['id_turma'];
if (!empty($id_turma)) {
    $turmaSet = sql::get('ge_turmas', '*', ['id_turma' => $id_turma], 'fetch');
}
?>
<div class="fieldBody">
    <div class="fieldTop">
        Diagnóstica
    </div>
    <br /><br />
    <div class="row">
        <div class="col-md-6">xxx
            <?php
                $where = ['ativo' => 1, '>' => 'n_agrup'];
 //               $where = ['ativo' => 1, '>' => 'n_agrup', 'tipo' => 'TCT'];
            $agrup = sql::idNome('global_agrupamento', @$where);
            formulario::select('id_agrup', $agrup, 'Agrupamento', @$id_agrup, 1);
            ?>
        </div>
    </div>
    <br /><br />
    <?php
    
    if (!empty(@$id_agrup)) {
        if (empty($_POST['professor'])) {
            ?>
            <div class="row">
                <div class="col-md-3">
                    <?php
                    $per = gtMain::periodosPorSituacao();
                    formulario::select('periodoLetivo', $per, 'Período Letivo', @$periodoLetivo, 1);
                    $hidden['periodoLetivo'] = @$periodoLetivo;
                    ?>
                </div>
                <?php
                if ($_SESSION['userdata']['id_nivel'] == 8) {
                    $id_inst = tool::id_inst();
                } else {
                    ?>
                    <div class="col-md-5">
                        <?php
                        formulario::select('id_inst', escolas::idInst(), 'Escola', @$id_inst, 1, @$hidden);
                        ?>
                    </div>
                    <?php
                }
                $hidden['id_inst'] = $id_inst;
                ?>
                <div class="col-md-4">
                    <form method="POST">
                        <table>
                            <tr>
                                <td>
                                    <?php
                                    if (!empty($id_inst)) {
                                        $turmas = turma::option($id_inst, $periodoLetivo, 'fk_id_inst', '1,2,3,4,5,6,7,8,9,25,26');
                                        formulario::select('id_turma', $turmas, 'Turma', @$id_turma, NULL, $hidden);
                                        $hidden['id_turma'] = @$id_turma;
                                    }
                                    ?>   
                                </td>
                                <td>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </td>
                                <td>
                                    <input class="btn btn-success" type="submit" value="Buscar" />   
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
            <br /><br />
            <?php
        } else {
            ?>
            <div style="text-align: center; font-size: 18px; font-weight: bold">
                <?php echo @$_POST['escola'] ?> - <?php echo @$_POST['classeDisc'] ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="btn btn-info" href="<?php echo HOME_URI ?>/global/classeprof">Voltar</a>
            </div>
            <br /><br />
            <?php
        }
        if (!empty($id_turma)) {
            $sql = "SELECT fk_id_pessoa FROM `ge_turma_aluno` WHERE `fk_id_turma` = " . $id_turma;
            $query = $model->db->query($sql);
            $alu = $query->fetchAll();
            foreach ($alu as $v) {
                $id_pessoas[] = $v['fk_id_pessoa'];
            }
            $sql = "SELECT "
                    . " g.`fk_id_gl`,g.`fk_id_pessoa`,g.`nfez`,g.`q01`,g.`q02`,g.`q03`,g.`q04`,g.`q05`,g.`q06`,g.`q07`,g.`q08`,g.`q09`, g.escrita as escrita, "
                    . " a.escrita as ne, a.ciclos, "
                    . " a.escrita as ne, g.escrita as escrita "
                    . " FROM global_respostas g "
                    . " left join global_aval a on a.id_gl = g.fk_id_gl "
                    . " join global_agrupamento ag on ag.id_agrup = a.fk_id_agrup "
                    . " WHERE g.`fk_id_pessoa` in (" . implode(',', $id_pessoas) . ") "
                    . " and ag.id_agrup = $id_agrup "
                    . " order by n_gl";
            $query = $model->db->query($sql);
            $a = $query->fetchAll();
            if (!empty($a)) {
                foreach ($a as $v) {
                    if (!empty($v['ne'])) {
                        @$totalEscrita[$v['escrita']] ++;
                        $escrita[$v['fk_id_pessoa']] = $v['escrita'];
                    }
                    $av[$v['fk_id_gl']] = $v['fk_id_gl'];
                    $aval[$v['fk_id_pessoa']][$v['fk_id_gl']] = $v;

                    for ($c = 1; $c <= 50; $c++) {
                        if (!empty($v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)])) {
                            @$quest[$v['fk_id_gl']][$c][$v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)]] ++;
                        }
                    }
                }
                $sql = "SELECT * FROM `global_aval` "
                        . " WHERE `id_gl` in  (" . implode(',', $av) . ") "
                        . " and ciclos = " . $turmaSet['fk_id_ciclo'];
                $query = $model->db->query($sql);
                $avali = $query->fetchAll();
                foreach ($avali as $w) {

                    $titulo2[$w['id_gl']] = $w['n_gl'];
                    $avaliacao[$w['id_gl']] = $w['n_gl'];
                }
                $sql = "SELECT * FROM `global_descritivo` "
                        . " WHERE `aval` in  (" . implode(',', $av) . ") ";
                $query = $model->db->query($sql);
                $d = $query->fetchAll();
                if (empty($d[0]['descricao'])) {
                    tool::alertModal('Esta Avaliação não é compatível com este gráfico');
                    exit();
                }
                foreach ($d as $v) {
                    $devol[$v['aval']][$v['num']][$v['valor']]['questao'] = $v['questao'];
                    $devol[$v['aval']][$v['num']][$v['valor']]['titulo'] = $v['titulo'];
                    $devol[$v['aval']][$v['num']][$v['valor']]['valorNominal'] = $v['valorNominal'];
                    $devol[$v['aval']][$v['num']][$v['valor']]['descricao'] = $v['descricao'];
                }
                if (!empty($quest)) {
                    foreach ($quest as $k => $v) {

                        for ($c = 1; $c <= count($v); $c++) {

                            @$titulo = $devol[$k][$c][1]['titulo'];
                            if (!empty($quest[$k][$c])) {
                                foreach ($v as $ky => $y) {
                                    @$graf[$avaliacao[$k]][$titulo][$devol[$k][$c][$ky]['descricao']] = $quest[$k][$c][$ky];
                                }
                            }
                        }
                    }
                }

                $alu = alunos::listar($id_turma, 'id_pessoa, n_pessoa, dt_nasc, deficiencia');

                foreach ($alu as $v) {
                    $aluno[$v['id_pessoa']]['nome'] = $v['n_pessoa'];
                    $aluno[$v['id_pessoa']]['apd'] = @$v['deficiencia'] == 1 ? 'Sim' : 'Não';
                    $aluno[$v['id_pessoa']]['idade'] = data::idade($v['dt_nasc']);
                }
                if (!empty($totalEscrita)) {
                    ?>
                    <script type="text/javascript">
                        google.charts.load('current', {'packages': ['corechart']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {

                            var data = google.visualization.arrayToDataTable([
                                ['Task', 'Hours per Day'],

                <?php
                $ct = 1;
                foreach ($totalEscrita as $ke => $e) {
                    if ($ke != '') {
                        ?>
                                        ['<?php echo str_replace('\n', '', $ke) ?>', <?php echo $e ?>],
                        <?php
                    }
                }
                ?>
                            ]);

                            var options = {
                                title: ''
                            };

                            var chart = new google.visualization.PieChart(document.getElementById('nivel'));

                            chart.draw(data, options);
                        }


                    </script>
                    <div style="background-color: #0076cb; border-radius: 10px; padding: 15px">
                        <div style="text-align: center; font-weight: bold; font-size: 22px;color: white">
                            Nível de Escrita
                        </div> 
                        <br />        
                        <div class="fieldBorder2" style="background-color: white" >
                            <div class="row">
                                <div class="col-sm-4" style="z-index: 999">
                                    <table style="font-size: 14; font-weight: bold" class="table table-bordered table-hover table-striped">
                                        <tr  style=" background-color: wheat; padding: 5px">
                                            <td >
                                                Níveis de Escrita
                                            </td>
                                            <td>
                                                Total
                                            </td>
                                            <td>
                                                Porc.
                                            </td>
                                        </tr>
                                        <?php
                                        $ct = 1;
                                        $tt = 0;
                                        foreach ($totalEscrita as $kesc => $vesc) {
                                            if ($kesc != '') {
                                                $tt += $vesc;
                                            }
                                        }
                                        foreach ($totalEscrita as $kesc => $vesc) {
                                            if ($kesc != '') {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $kesc ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $vesc ?>
                                                    </td>
                                                    <td>
                                                        <?php echo round(($vesc / $tt) * 100, 1) . '%' ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                        <tr  style=" background-color: wheat; padding: 5px">
                                            <td colspan="2">
                                                Total Avaliado
                                            </td>
                                            <td>
                                                <?php echo $tt ?>
                                            </td>
                                        </tr>
                                    </table>      
                                </div>
                                <div class="col-sm-8">
                                    <div id="nivel" style="width: 100%; height: 500px;margin-left: -10%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                if (!empty($graf)) {
                    $cc = 1;
                    $corDiv = 0;
                    foreach ($graf as $av_ => $va_) {
                        if (!empty($av_)) {
                            ?>
                            <div style="background-color: <?php echo tool::cor(++$corDiv) ?>; border-radius: 10px; padding: 15px">
                                <div style="text-align: center; font-weight: bold; font-size: 22px;color: white">
                                    <?php echo $av_ ?>
                                </div> 
                                <br /><br />
                                <?php
                                foreach ($va_ as $questao => $vv) {
                                    ?>
                                    <div class="fieldBorder2" style="background-color: white">
                                        <div style="text-align: center; font-size: 18px; font-weight: bold; ">
                                            <?php echo $questao ?>
                                        </div>
                                        <br /><br />
                                        <div class="row">
                                            <div class="col-sm-4" style="z-index: 999">
                                                <table class="table table-bordered table-hover table-striped" style="font-weight: bold; font-size: 18px">
                                                    <tr style="background-color: wheat">
                                                        <td>
                                                            Descritores
                                                        </td>
                                                        <td>
                                                            Totais
                                                        </td>
                                                        <td>
                                                            Porc.
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $tt = 0;
                                                    foreach ($vv as $k => $v) {
                                                        $tt += $v;
                                                    }
                                                    foreach ($vv as $k => $v) {
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo $k ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $v ?>
                                                            </td>
                                                            <td>
                                                                <?php echo round(($v / $tt) * 100, 1) . '%' ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                    <tr style="background-color: wheat">
                                                        <td colspan="2">
                                                            Total de Avaliados
                                                        </td>
                                                        <td>
                                                            <?php echo $tt ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-sm-8">
                                                <script type="text/javascript">
                                                    google.charts.load('current', {'packages': ['corechart']});
                                                    google.charts.setOnLoadCallback(drawChart);

                                                    function drawChart() {

                                                        var data = google.visualization.arrayToDataTable([
                                                            ['Quest', 'Val'],
                            <?php
                            foreach ($vv as $k => $v) {
                                ?>
                                                                ['<?php echo str_replace('\n', '', $k) ?>', <?php echo!empty($v) ? $v : 0 ?>],
                                <?php
                            }
                            ?>
                                                        ]);

                                                        var options = {
                                                            title: ''
                                                        };

                                                        var chart = new google.visualization.PieChart(document.getElementById('pie<?php echo $cc ?>'));

                                                        chart.draw(data, options);
                                                    }
                                                </script>
                                                <div id="pie<?php echo $cc ?>" style="width: 100%; height: 500px;margin-left: -15%"></div>


                                            </div>
                                        </div>
                                    </div>
                                    <br /><br />
                                    <div style="page-break-after: always"></div>
                                    <?php
                                    $cc++;
                                }
                                ?>
                            </div>
                            <br /><br />

                            <?php
                        }
                    }
                }
                ?>
            </div>
            <?php
        } else {
            tool::alertModal("Não há dados referente a esta consulta");
            exit();
        }
    }
}