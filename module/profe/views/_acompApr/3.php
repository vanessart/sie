<?php
if (!defined('ABSPATH'))
    exit;

$aluno = new ng_aluno($id_pessoaAlu);
$dt_nasc = $aluno->dadosPessoais['dt_nasc'];
$dadosAluno = $aluno->dadosPessoais;
$idade = dataErp::calcula($dt_nasc, date("Y-m-d"), 'mes');
$confHabGrup = $model->sondagens($id_pl, $id_curso);
if ($confHabGrup) {
    $id_gh = $confHabGrup['fk_id_gh'];
    $sql = "SELECT * FROM `coord_hab` h "
            . " join coord_hab_ciclo ci on ci.fk_id_hab = h.id_hab and ci.fk_id_ciclo = $id_ciclo and `fk_id_gh` =  $id_gh ";
    $query = pdoSis::getInstance()->query($sql);
    $hab = $query->fetchAll(PDO::FETCH_ASSOC);
    $sql = "select distinct e.* from coord_campo_experiencia e "
            . " join coord_hab h on h.fk_id_ce = e.id_ce "
            . " JOIN coord_hab_ciclo ci on ci.fk_id_hab = h.id_hab and ci.fk_id_ciclo = $id_ciclo  "
            . " join profe_sodagem s on s.fk_id_gh = e.fk_id_gh and s.fk_id_pl = $id_pl and s.fk_id_curso = $id_curso";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);

    $mongo = new mongoCrude('Diario');

    $sond = $mongo->query('sondagem.' . $id_curso . '.' . date("Y"), ['id_pessoa' => $id_pessoaAlu, 'num_sondagem'=>'1']);

    if ($hab) {
        if ($sond) {
            $h = (array) $sond[0]->hab;
        }
        if (!empty($h)) {
            foreach ($h as $k => $v) {
                $mes = dataErp::calcula($dt_nasc, $v, 'mes');
                $habPorMes[$mes][] = $k;
            }
        }

        foreach ($hab as $k => $v) {
            @$campExpHab[$v['fk_id_ce']]++;
            if (!empty($h[$v['id_hab']])) {
                $hab[$k]['sit'] = 'Em conformidade';
                $hab[$k]['sn'] = 'Sim';
                $hab[$k]['dataHab'] = !empty($h[$v['id_hab']]) ? data::converteBr($h[$v['id_hab']]) : '';
                $hab[$k]['class'] = 'primary';
                if ($v['fim']) {
                    $idadeHab = dataErp::calcula($dt_nasc, $h[$v['id_hab']], 'mes');
                    if ($v['fim'] < $idadeHab) {
                        $hab[$k]['sit'] = 'Após a expectativa';
                        $hab[$k]['class'] = 'info';
                    }
                }
            } else {
                $hab[$k]['sit'] = 'Em conformidade';
                $hab[$k]['sn'] = 'Não';
                $hab[$k]['class'] = 'warning';
                if ($v['fim']) {
                    if ($v['fim'] < $idade) {
                        $hab[$k]['sit'] = 'Desvelo';
                        $hab[$k]['class'] = 'danger';
                    }
                }
            }
            $hab[$k]['inicioF'] = $v['inicio'] ? $v['inicio'] . 'º mês' : '';
            $hab[$k]['fimF'] = $v['fim'] ? $v['fim'] . 'º mês' : '';
        }
        $form['array'] = $hab;
        $form['fields'] = [
            'Aferida' => 'sn',
            'Situação' => 'sit',
            'Desenvolveu' => 'dataHab',
            'Início' => 'inicioF',
            'Fim' => 'fimF',
            'Código' => 'codigo',
            'Habilidade' => 'descricao'
        ];

        if ($sond) {
            $ce = (array) $sond[0]->campoEsp;
            foreach ($array as $v) {
                $campExp[$v['id_ce']] = $v['n_ce'];
                $campExpQt[$v['id_ce']] = 0;
                if (!empty($ce[$v['id_ce']])) {
                    $c = (array) $ce[$v['id_ce']];
                    foreach ($c as $kc => $cc) {
                        if ($cc > 0) {
                            $campExpQt[$v['id_ce']]++;
                        }
                    }
                }
            }
        }
        if (!empty($campExp)) {

            foreach ($campExp as $kce => $ce) {
                if (@$campExpHab[$kce] > 0) {
                    $campExpQt[$kce] = intval((@$campExpQt[$kce] / $campExpHab[$kce]) * 100);
                }
            }
        }
    }
}
?>
<script src="<?= HOME_URI ?>/module/profe/views/_faltaSeq/vendor/chart.js/Chart.min.js"></script>
<div class="row">
    <div class="col">
        <table class="table table-bordered table-hover table-responsive border">
            <tr>
                <td>
                    Aluno
                </td>
                <td>
                    <?= $dadosAluno['n_pessoa'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Turma
                </td>
                <td>
                    <?= $n_turma ?>
                </td>
            </tr>
            <tr>
                <td>
                    Data Nasc.
                </td>
                <td>
                    <?= data::converteBr($dt_nasc) ?> (<?= $idade ?> meses)
                </td>
            </tr>
        </table>
        <br /><br /><br />
        <div class="border">
            <div class="fieldTop">
                Habilidades
            </div>
            <div class="row">
                <div class="col">
                    <button style="width: 10px; height: 10px; margin-right: 10px" class="btn btn-primary"></button>Habilidades aferidas dentro da idade esperada
                </div>
                <div class="col">
                    <button style="width: 10px; height: 10px; margin-right: 10px" class="btn btn-info"></button>Habilidades aferidas após a idade esperada
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col">
                    <button style="width: 10px; height: 10px; margin-right: 10px" class="btn btn-warning"></button>Habilidades não aferidas
                </div>
                <div class="col">
                    <button style="width: 10px; height: 10px; margin-right: 10px" class="btn btn-danger"></button>Habilidades não aferidas dentro da idade esperada
                </div>
            </div>
            <br />
            <?php
            foreach ($hab as $v) {
                if ($v['inicio'] && $v['fim']) {
                    $mesesHab = ' (de ' . $v['inicio'] . ' a ' . $v['fim'] . ' meses)';
                } elseif ($v['inicio']) {
                    $mesesHab = ' (A partir dos ' . $v['inicio'] . ' meses)';
                } elseif ($v['fim']) {
                    $mesesHab = ' (Até os ' . $v['fim'] . ' meses)';
                } else {
                    $mesesHab = ' ';
                }
                ?>
                <button onclick="ver('<?= $v['codigo'] ?> <?= rtrim(str_replace(["\r", "\n"], '', $v['descricao'])) ?><?= $mesesHab ?>')" style="width: 10px; height: 10px" class="btn btn-<?= $v['class'] ?>"  data-toggle="tooltip" data-placement="top" title="<?= $v['codigo'] ?> - <?= $v['descricao'] ?><?= $mesesHab ?>"></button>
                <?php
                $habBtn[$v['id_hab']] = '<div style="padding: 2px; display: block"><button onclick="ver(\'' . $v['codigo'] . rtrim(str_replace(["\r", "\n"], '', $v['descricao'])) . $mesesHab . '\')" style="width: 10px; height: 10px" class="btn btn-' . $v['class'] . '"  data-toggle="tooltip" data-placement="top" title="' . $v['codigo'] . ' - ' . $v['descricao'] . $mesesHab . '"></button></div>';
            }
            ?>
        </div>
    </div>
    <div class="col">
        <div class="border">
            <div class="fieldTop">
                Campos de Experiência
            </div>
            <canvas id="myChart"></canvas>
        </div>
    </div>
</div>

<br />
<table class="table table-bordered table-hover table-responsive">
    <tr>
        <td colspan="<?= $idade ?>" style="text-align: center">
            Aferimento das Habilidades por  Meses
        </td>
    </tr>
    <tr>
        <?php
        foreach (range(1, 24) as $m) {
            ?>
            <td style="width: 4.16%">
                <?= $m ?>
            </td>
            <?php
        }
        ?>

    </tr>
    <tr>
        <?php
        foreach (range(1, 24) as $m) {
            ?>
            <td>
                <?php
                if (!empty($habPorMes[$m])) {
                    foreach ($habPorMes[$m] as $hs) {
                        if (!empty($habBtn[$hs])) {
                            echo $habBtn[$hs];
                        }
                    }
                }
                ?>
            </td>
            <?php
        }
        ?>

    </tr>
</table>
<br /><br /><br />
<?php
if (!empty($form)) {
    report::simple($form);
}
toolErp::modalInicio(0, NULL, NULL, 'Habilidade');
?>
<div id="textHab"></div>
<?php
toolErp::modalFim();
?>
<script>
// <block:setup:1>
    const data = {
        labels: [
<?= "'" . implode("','", $campExp) . "'" ?>
        ],
        datasets: [{
                label: 'My First Dataset',
                data: [<?= implode(',', $campExpQt) ?>],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(75, 192, 192)',
                    'rgb(255, 205, 86)',
                    'rgb(201, 203, 207)',
                    'rgb(54, 162, 235)'
                ]
            }]
    };
// </block:setup>

// <block:config:0>
    const config = {
        type: 'polarArea',
        data: data,
        options: {}
    };
// </block:config>

    module.exports = {
        actions: [],
        config: config,
    };
</script>

<script>
    const myChart = new Chart(
            document.getElementById('myChart'),
            config
            );
</script>

<script>
    function ver(texto) {
        textHab.innerHTML = texto;
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>