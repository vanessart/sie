<?php
if (!defined('ABSPATH'))
    exit;
$confHabGrup = $model->sondagens($id_pl, $id_curso);
if ($confHabGrup) {
    $id_gh = $confHabGrup['fk_id_gh'];
    $sql = "SELECT * FROM `coord_hab` h "
            . " join coord_campo_experiencia e on e.id_ce = h.fk_id_ce "
            . " and h.`fk_id_gh` =  $id_gh "
            . " join coord_hab_ciclo ci on ci.fk_id_hab = h.id_hab and ci.fk_id_ciclo = $id_ciclo "
            . " order by e.n_ce ";
    $query = pdoSis::getInstance()->query($sql);
    $hab = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($hab as $v) {
        @$ceQt[$v['fk_id_ce']]++;
    }
}
$mongo = new mongoCrude('Diario');

$sond = $mongo->query('sondagem.' . $id_curso . '.' . date("Y"), ['id_turma' => $id_turma]);
if ($sond) {
    foreach ($sond as $v) {
        $hl = (array) $v->hab;
        $hm[$v->id_pessoa] = $hl;
        foreach ($hl as $kl => $l) {
            if (!empty($l)) {
                @$habSim[$kl]++;
                @$habSimAluno[$v->id_pessoa]++;
            }
        }
    }
}
$alunos = ng_escola::alunoPorTurma($id_turma);
if ($alunos) {
    foreach ($alunos as $k => $v) {
        $i = data::idade($v['dt_nasc']);
        $v['id_pl'] = $id_pl;
        $v['id_inst_turma'] = $id_inst_turma;
        $v['id_curso'] = $id_curso;
        $v['id_inst'] = $id_inst;
        $v['id_ciclo'] = $id_ciclo;
        $v['escola'] = $escola;
        $v['n_turma'] = $n_turma;
        $v['id_turma'] = $id_turma;
        $v['id_pessoaAlu'] = $v['id_pessoa'];
        $alunos[$k]['idade'] = $i > 1 ? $i . ' Anos' : $i . ' Ano';
        $v['activeNav'] = 3;
        $alunos[$k]['sonda'] = formErp::submit('Resultado da Pauta de Observação', null, $v, null, null, null, 'btn btn-primary');
        $v['activeNav'] = 4;
        $alunos[$k]['acomp'] = formErp::submit('Acompanhamento Semestral', null, $v);
        if (!empty($habSimAluno[$v['id_pessoa']])) {
            $porc = intval(($habSimAluno[$v['id_pessoa']] / count($hab) * 100));
        } else {
            $porc = 0;
        }
        $alunos[$k]['porc'] = '<div class="progress"><div class="progress-bar" style="width: ' . $porc . '%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">' . $porc . '%</div></div>';
    }
    $form['array'] = $alunos;
    $form['fields'] = [
        'Num.' => 'chamada',
        'RSE' => 'id_pessoa',
        'Nume' => 'n_pessoa',
        'Porcentagem' => 'porc',
        'Situação' => 'n_tas',
        'Idade' => 'idade',
        '||1' => 'sonda',
        '||2' => 'acomp'
    ];
}
?>
<div class="fieldTop">
    <?= $n_turma ?> - <?= $escola ?>
</div>
<?php
if (!empty($form['array'])) {
    report::simple($form);
}
?>
<table class="table table-bordered table-hover table-responsive">
    <tr>
        <td colspan="2" rowspan="2" style="; width: 20%"></td>
        <td rowspan="2" style="writing-mode: vertical-rl; text-orientation: mixed; border: #000 thin solid !important">
            Média
        </td>
        <?php
        if ($alunos) {
            foreach ($alunos as $k => $v) {
                ?>
                <td style="writing-mode: vertical-rl; text-orientation: mixed; border: #000 thin solid !important">
                    <?= $v['n_pessoa'] ?>
                </td>
                <?php
            }
        }
        ?>
    </tr>
    <tr>
        <?php
        if ($alunos) {
            foreach ($alunos as $k => $v) {
                ?>
                <td style="writing-mode: vertical-rl; text-orientation: mixed; border: #000 thin solid !important">
                    <?= $v['chamada'] ?>
                </td>
                <?php
            }
        }
        ?>
    </tr>
    <?php
    $id_ce = 0;
    foreach ($hab as $h) {
        ?>
        <tr>
            <?php
            if ($id_ce != $h['id_ce']) {
                ?>
                <td rowspan="<?= $ceQt[$h['id_ce']] ?>" style="border: #000 thin solid; padding: 5px">
                    <?= $h['n_ce'] ?>
                </td>
                <?php
            }
            ?>
            <td style="border: #000 thin solid !important; padding: 5px">
                <?= $h['codigo'] ?> - <?= $h['descricao'] ?>
            </td>
            <td style="border: #000 thin solid !important; padding: 5px">
                <?= intval((@$habSim[$h['id_hab']] / count($alunos)) * 100) ?>%
            </td>
            <?php
            if ($alunos) {
                foreach ($alunos as $k => $v) {
                    ?>
                    <td style="border: #000 thin solid !important; padding: 5px">
                        <?php
                        if (!empty($hm[$v['id_pessoa']][$h['id_hab']])) {
                            echo '<button class="btn btn-primary">Sim</button>';
                        } else {
                            echo '<button class="btn btn-secondary">Não</button>';
                        }
                        ?>
                    </td>
                    <?php
                }
            }
            ?>
        </tr>
        <?php
        $id_ce = $h['id_ce'];
    }
    ?>
</table>