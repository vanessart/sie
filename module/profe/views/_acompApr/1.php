<?php
if (!defined('ABSPATH'))
    exit;
if ($id_inst) {
    $sql = "SELECT "
            . " t.id_turma, count(ta.fk_id_pessoa) ct  "
            . " FROM ge_turma_aluno ta "
            . " join ge_turmas t on t.id_turma= ta.fk_id_turma and t.fk_id_inst = $id_inst and ta.fk_id_tas = 0 "
            . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl=1 "
            . " group by t.id_turma ";
    $query = pdoSis::getInstance()->query($sql);
    $a = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($a as $v) {
        $aluQt[$v['id_turma']] = $v['ct'];
    }
    $sondagensCurso = $model->sondagensCurso();
    $habQtPorGrupo = hab::habQtPorGrupo();
    $mongo = new mongoCrude('Diario');

    foreach ([3, 7, 8] as $v) {
        $sond = $mongo->query('sondagem.' . $v . '.' . date("Y"), ['id_inst' => $id_inst]);
        if ($sond) {
            foreach ($sond as $v) {
                $hl = (array) $v->hab;
                foreach ($hl as $kl => $l) {
                    if (!empty($l)) {
                        @$habSim[$v->id_turma][$kl]++;
                        @$habSimTotal[$v->id_turma]++;
                    }
                }
            }
        }
    }
}
if (!empty($id_pessoa)) {
    $tpd = $model->turmaDisciplina($id_pessoa);
    if ($tpd) {

        foreach ($tpd as $k => $v) {
            if (!is_numeric($k) && in_array($v['id_curso'], [3, 7, 8])) {
                $temTurma = 1;
                $t = [
                    'id_pl' => $v['id_pl'],
                    'id_inst_turma' => $v['id_inst'],
                    'id_curso' => $v['id_curso'],
                    'id_turma' => $v['id_turma'],
                    'n_turma' => $v['nome_turma'],
                    'escola' => $v['escola'],
                    'activeNav' => 2,
                    'id_ciclo' => $v['id_ciclo'],
                    'id_inst' => $id_inst
                ];
                $t['ac'] = formErp::submit('Acessar', null, $t);
                $t['s1'] = formErp::submit('1º Semestre', null, ['escola' => $v['escola'], 'n_turma' => $v['nome_turma'], 'id_turma' => $v['id_turma'], 'semestre' => 1, 'ano' => date("Y")], HOME_URI . '/profe/pdf/acompAprendTurma.php', 1);
                $t['s2'] = formErp::submit('2º Semestre', null, ['escola' => $v['escola'], 'n_turma' => $v['nome_turma'], 'id_turma' => $v['id_turma'], 'semestre' => 2, 'ano' => date("Y")], HOME_URI . '/profe/pdf/acompAprendTurma.php', 1);
                $form['array'][$v['id_turma']] = $t;
            }
        }
    }
    $form['fields'] = [
        'Turma' => 'n_turma',
        'Escola' => 'escola',
        '||3' => 's1',
        '||2' => 's2',
        '||1' => 'ac'
    ];
} elseif (!empty($id_inst)) {
    $esc = new escola($id_inst);
    $tpd = $esc->turmas(NULL, '3,7,8,10');
    if ($tpd) {
        foreach ($tpd as $v) {
            if (in_array($v['id_curso'], [3, 7, 8])) {
                $ciclos[$v['id_ciclo']] = $v['n_ciclo'];
                $idGhs[$v['id_ciclo']] = $sondagensCurso[$v['id_curso']];
                $totalHabAlu = @$aluQt[$v['id_turma']] * @$habQtPorGrupo[@$sondagensCurso[$v['id_curso']]][$v['id_ciclo']];
                if ($totalHabAlu) {
                    $porc = intval((intval(@$habSimTotal[$v['id_turma']]) / $totalHabAlu) * 100);
                } else {
                    $porc = 0;
                }
                $porcent = '<div class="progress"><div class="progress-bar" style="width: ' . $porc . '%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">' . $porc . '%</div></div>';

                $temTurma = 1;
                $t = [
                    'id_pl' => $v['id_pl'],
                    'id_inst_turma' => $id_inst,
                    'id_curso' => $v['id_curso'],
                    'id_turma' => $v['id_turma'],
                    'n_turma' => $v['n_turma'],
                    'escola' => $n_inst,
                    'activeNav' => 2,
                    'id_ciclo' => $v['id_ciclo'],
                    'id_inst' => $id_inst,
                    'porc' => $porcent
                ];
                $t['ac'] = formErp::submit('Acessar', null, $t);
                $t['s1'] = formErp::submit('1º Semestre', null, ['escola' => $n_inst, 'n_turma' => $v['n_turma'], 'id_turma' => $v['id_turma'], 'semestre' => 1, 'ano' => date("Y")], HOME_URI . '/profe/pdf/acompAprendTurma.php', 1);
                $t['s2'] = formErp::submit('2º Semestre', null, ['escola' => $n_inst, 'n_turma' => $v['n_turma'], 'id_turma' => $v['id_turma'], 'semestre' => 2, 'ano' => date("Y")], HOME_URI . '/profe/pdf/acompAprendTurma.php', 1);
                $form['array'][$v['id_turma']] = $t;
            }
        }
    }
    $form['fields'] = [
        'Turma' => 'n_turma',
        'Porcentagem' => 'porc',
        'Escola' => 'escola',
        '||3' => 's1',
        '||2' => 's2',
        '||1' => 'ac'
    ];
}
if ($tpd) {
    if (!empty($temTurma)) {
        report::simple($form);
    }
}
if ($id_inst && !empty($ciclos)) {
    $id_ciclo_hab = filter_input(INPUT_POST, 'id_ciclo_hab', FILTER_SANITIZE_NUMBER_INT);
    echo formErp::select('id_ciclo_hab', $ciclos, 'Ciclo para análise por Habilidade', $id_ciclo_hab, 1, $hidden);
    if ($id_ciclo_hab) {
        $id_gh = $idGhs[$id_ciclo_hab];
        $sql = "SELECT * FROM `coord_hab` h "
                . " join coord_campo_experiencia e on e.id_ce = h.fk_id_ce "
                . " and h.`fk_id_gh` =  $id_gh "
                . " join coord_hab_ciclo ci on ci.fk_id_hab = h.id_hab and ci.fk_id_ciclo = $id_ciclo_hab "
                . " order by e.n_ce ";
        $query = pdoSis::getInstance()->query($sql);
        $hab = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($hab as $v) {
            @$ceQt[$v['fk_id_ce']]++;
        }
        ?>
        <table class="table table-bordered table-hover table-responsive">
            <tr>
                <td colspan="2"  style="; width: 20%"></td>
                <?php
                foreach ($form['array'] as $v) {
                    if ($v['id_ciclo'] == $id_ciclo_hab) {
                        ?>
                        <td style=" border: #000 thin solid !important; font-weight: bold">
                            <?= $v['n_turma'] ?>
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
                    <?php
                    foreach ($form['array'] as $v) {
                        if ($v['id_ciclo'] == $id_ciclo_hab) {
                            ?>
                            <td>
                                <?php
                                $habAplic = intval(@$habSim[$v['id_turma']][$h['id_hab']]);
                                $qtAlu = $aluQt[$v['id_turma']];
                                if ($qtAlu) {
                                    $porc = intval(($habAplic / $qtAlu) * 100);
                                } else {
                                    $porc = 0;
                                }
                                echo '<div class="progress"><div class="progress-bar" style="width: ' . $porc . '%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">' . $porc . '%</div></div>';
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
        <?php
    }
}

