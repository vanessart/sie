<?php
if (!defined('ABSPATH'))
    exit;
@$id_pl = sql::get('tdics_pl', 'id_pl', ['ativo' => 1], 'fetch')['id_pl'];

$td = sqlErp::get(['tdics_turma', 'tdics_curso'], 'dia_sem, fk_id_polo, id_curso, n_curso, id_turma, periodo', ['fk_id_pl' => $id_pl]);
$cursos = [];
foreach ($td as $v) {
    $cursos[$v['id_curso']] = $v['n_curso'];
    $qtCur[$v['id_curso']][$v['id_turma']] = $v['id_turma'];
    if ($v['periodo'] == 'M') {
        $qtCur['M'][$v['id_curso']][$v['id_turma']] = $v['id_turma'];
    } elseif ($v['periodo'] == 'T') {
        $qtCur['T'][$v['id_curso']][$v['id_turma']] = $v['id_turma'];
    }
    if ($v['n_curso'] == 'Resolução Inovadora de Problemas - Machine Learning') {
        $v['n_curso'] = 'Machine Learning';
    }
    $diaCur[$v['fk_id_polo']][$v['dia_sem']] = $v['n_curso'];
}

$setup = sql::get('tdics_setup', '*', null, 'fetch');
$qt_turma = $setup['qt_turma'];
$polos = sql::idNome('tdics_polo');

$sql = "SELECT * FROM tdics_turma t "
        . " JOIN tdics_curso c on c.id_curso = t.fk_id_curso "
        . " and t.fk_id_pl = $id_pl ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
if ($array) {
    foreach ($array as $v) {
         @$qp[$v['fk_id_polo']]['abrevCurso'][$v['periodo']][$v['dia_sem']][$v['horario']] = $v['abrev'];
    }
}

$sql = "SELECT * FROM tdics_turma_aluno ta "
        . " JOIN tdics_turma t on t.id_turma = ta.fk_id_turma "
        . " and t.fk_id_pl = $id_pl "
        . " JOIN tdics_curso c on c.id_curso = t.fk_id_curso ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
if ($array) {
    foreach ($array as $v) {
        if ($v['periodo'] == 'M') {
            @$aluPorCur['M'][$v['fk_id_curso']]++;
        } elseif ($v['periodo'] == 'T') {
            @$aluPorCur['T'][$v['fk_id_curso']]++;
        }
        @$aluPorCur[$v['fk_id_curso']]++;
        @$q['total']++;
        @$q['totalDiaSem'][$v['dia_sem']]++;
        @$q['totalPer'][$v['periodo']][$v['dia_sem']]++;
        @$q['totalhorario'][$v['periodo']][$v['dia_sem']][$v['horario']]++;

        @$qp[$v['fk_id_polo']]['total']++;
        @$qp[$v['fk_id_polo']]['totalDiaSem'][$v['dia_sem']]++;
        @$qp[$v['fk_id_polo']]['totalPer'][$v['periodo']][$v['dia_sem']]++;
        @$qp[$v['fk_id_polo']]['totalhorario'][$v['periodo']][$v['dia_sem']][$v['horario']]++;
    }
    ksort($qp);
}
$tg = $qt_turma * count($polos);
?>
<div class="body">
    <div class="fieldTop">
        Quadro de Vagas
    </div>
    <br /><br />
    <table class="table table-bordered table-hover table-responsive border">
        <tr>
            <td colspan="4" style="text-align: center">
                Alunos por Curso - Geral
            </td>
        </tr>
        <tr>
            <td>
                Polo
            </td>
            <td>
                Vagas iniciais
            </td>
            <td>
                Matriculados
            </td>
            <td>
                Vagas remanecentes
            </td>
        </tr>
        <?php
        foreach ($cursos as $id_curso => $n_curso) {
            if (!empty($qtCur[$id_curso])) {
                $qtc = count($qtCur[$id_curso]) * @$qt_turma;
            } else {
                $qtc = 0;
            }
            if (!empty($aluPorCur[$id_curso])) {
                $tqac = $aluPorCur[$id_curso];
            } else {
                $tqac = 0;
            }
            ?>
            <tr>
                <td>
                    <?= $n_curso ?>
                </td>
                <td>
                    <?= $qtc ?>
                </td>
                <td>
                    <?= $tqac ?>
                </td>
                <td style="color: <?= ($qtc - $tqac) < 0 ? 'red' : 'black' ?> !important">
                    <?= ($qtc - $tqac) ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <br /><br /> 
    <?php
    foreach (['M' => 'Manhã', 'T' => 'Tarde'] as $p => $n_p) {
        ?>
        <table class="table table-bordered table-hover table-responsive border">
            <tr>
                <td colspan="4" style="text-align: center;">
                    Alunos por Curso - <?= $n_p ?>
                </td>
            </tr>
            <tr>
                <td>
                    Polo
                </td>
                <td>
                    Vagas iniciais
                </td>
                <td>
                    Matriculados
                </td>
                <td>
                    Vagas remanecentes
                </td>
            </tr>
            <?php
            foreach ($cursos as $id_curso => $n_curso) {
                if (!empty($qtCur[$p][$id_curso])) {
                    $qtc = count($qtCur[$p][$id_curso]) * @$qt_turma;
                } else {
                    $qtc = 0;
                }
                if (!empty($aluPorCur[$p][$id_curso])) {
                    $tqac = $aluPorCur[$p][$id_curso];
                } else {
                    $tqac = 0;
                }
                ?>
                <tr>
                    <td>
                        <?= $n_curso ?>
                    </td>
                    <td>
                        <?= $qtc ?>
                    </td>
                    <td>
                        <?= $tqac ?>
                    </td>
                    <td style="color: <?= ($qtc - $tqac) < 0 ? 'red' : 'black' ?> !important">
                        <?= ($qtc - $tqac) ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <br /><br />
        <?php
    }
    ?>
    <table class="table table-bordered table-hover table-responsive border">
        <tr>
            <td colspan="7" style="text-align: center; font-weight: bold; font-size: 1.4em">
                Quadro Geral
            </td>
        </tr>
        <tr>
            <td colspan="2">

            </td>
            <?php
            foreach ($model->diaSemana() as $v) {
                ?>
                <td>
                    <?= $v ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <?php
        foreach (['M' => 'Manhã', 'T' => 'Tarde'] as $kper => $per) {
            ?>
            <tr>
                <td rowspan="3">
                    <?= $per ?>
                </td>
                <td>
                    1º Horário
                </td>
                <?php
                foreach ($model->diaSemana() as $k => $v) {
                    $qta = intval($tg - @$q['totalhorario'][$kper][$k][1]);
                    ?>
                    <td>
                        <span style="color: <?= $qta > 0 ? 'blue' : 'red' ?>; font-weight: bold"><?= $qta ?></span>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <td>
                    2º Horário
                </td>
                <?php
                foreach ($model->diaSemana() as $k => $v) {
                    $qta = intval($tg - @$q['totalhorario'][$kper][$k][2]);
                    ?>
                    <td>
                        <span style="color: <?= $qta > 0 ? 'blue' : 'red' ?>; font-weight: bold"><?= $qta ?></span>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <td>
                    Total da <?= $per ?>
                </td>
                <?php
                foreach ($model->diaSemana() as $k => $v) {
                    $qta = intval(($tg * 2) - @$q['totalPer'][$kper][$k]);
                    ?>
                    <td>
                        <span style="color: <?= $qta > 0 ? 'blue' : 'red' ?>; font-weight: bold"><?= $qta ?></span>
                    </td>
                    <?php
                }
                ?>
            </tr>

            <?php
        }
        ?>
        <tr>
            <td colspan="2">
                Total do Dia da Semana
            </td>
            <?php
            foreach ($model->diaSemana() as $k => $v) {
                ?>
                <td>
                    <span style="color: blue; font-weight: bold"><?= intval(($tg * 4) - @$q['totalDiaSem'][$k]) ?></span>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td colspan="2">
                Total Geral
            </td>
            <td colspan="5">
                <span style="color: blue; font-weight: bold"><?= intval(($tg * 20) - @$q['total']) ?></span>
            </td>
        </tr>
    </table>
    <br /><br />
    <?php
    foreach ($polos as $id_polo => $n_polo) {
        ?>
        <table class="table table-bordered table-hover table-responsive border">
            <tr>
                <td colspan="7" style="text-align: center; font-weight: bold; font-size: 1.4em">
                    Quadro do Núcleo <?= $n_polo ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">

                </td>
                <?php
                foreach ($model->diaSemana() as $k => $v) {
                    ?>
                    <td>
                        <?= $v ?>
                        <br />
                        <?= ''// @$diaCur[$id_polo][$k] ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <?php
            foreach (['M' => 'Manhã', 'T' => 'Tarde'] as $kper => $per) {
                ?>
                <tr>
                    <td rowspan="3">
                        <?= $per ?>
                    </td>
                    <td>
                        1º Horário
                    </td>
                    <?php
                    foreach ($model->diaSemana() as $k => $v) {
                        $qta = intval($qt_turma - @$qp[$id_polo]['totalhorario'][$kper][$k][1]);
                        ?>
                        <td>
                            <span style="color: <?= $qta > 0 ? 'blue' : 'red' ?>; font-weight: bold"><?= $qta ?></span> : <?= empty($qp[$id_polo]['abrevCurso'][$kper][$k][1]) ? 'Vazia' : $qp[$id_polo]['abrevCurso'][$kper][$k][1] ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <tr>
                    <td>
                        2º Horário
                    </td>
                    <?php
                    foreach ($model->diaSemana() as $k => $v) {
                        $qta = intval($qt_turma - @$qp[$id_polo]['totalhorario'][$kper][$k][2]);
                        ?>
                        <td>
                            <span style="color: <?= $qta > 0 ? 'blue' : 'red' ?>; font-weight: bold"><?= $qta ?></span> : <?= empty($qp[$id_polo]['abrevCurso'][$kper][$k][2]) ? 'Vazia' : $qp[$id_polo]['abrevCurso'][$kper][$k][2] ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <tr>
                    <td>
                        Total da <?= $per ?>
                    </td>
                    <?php
                    foreach ($model->diaSemana() as $k => $v) {
                        $qta = intval(($qt_turma * 2) - @$qp[$id_polo]['totalPer'][$kper][$k]);
                        ?>
                        <td>
                            <span style="color: <?= $qta > 0 ? 'blue' : 'red' ?>; font-weight: bold"><?= $qta ?></span>
                        </td>
                        <?php
                    }
                    ?>
                </tr>

                <?php
            }
            ?>
            <tr>
                <td colspan="2">
                    Total do Dia da Semana
                </td>
                <?php
                foreach ($model->diaSemana() as $k => $v) {
                    $qta = intval(($qt_turma * 4) - @$qp[$id_polo]['totalDiaSem'][$k]);
                    ?>
                    <td>
                        <span style="color: <?= $qta > 0 ? 'blue' : 'red' ?>; font-weight: bold"><?= $qta ?></span>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <td colspan="2">
                    Total Geral
                </td>
                <td colspan="5">
                    <?php
                    $qta = intval(($qt_turma * 20) - @$qp[$id_polo]['total']);
                    ?>
                    <span style="color: <?= $qta > 0 ? 'blue' : 'red' ?>; font-weight: bold"><?= $qta ?></span>
                </td>
            </tr>
        </table>
        <?php
    }
    ?>
</div>