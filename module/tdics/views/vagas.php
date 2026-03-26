<?php
/** @var tdicsModel $model */

if (!defined('ABSPATH'))
    exit;
@$id_pl = sql::get($model::$sistema . '_pl', 'id_pl', ['ativo' => 1], 'fetch')['id_pl'];

$td = sqlErp::get([$model::$sistema . '_turma', $model::$sistema . '_curso'], 'dia_sem, fk_id_polo, id_curso, n_curso, id_turma, periodo', ['fk_id_pl' => $id_pl]);
$cursos = [];
foreach ($td as $v) {
    $cursos[$v['id_curso']] = $v['n_curso'];
    $qtCur[$v['id_curso']][$v['id_turma']] = $v['id_turma'];
    $qtCur[$v['periodo']][$v['id_curso']][$v['id_turma']] = $v['id_turma'];
    if ($v['n_curso'] == 'Resolução Inovadora de Problemas - Machine Learning') {
        $v['n_curso'] = 'Machine Learning';
    }
    $diaCur[$v['fk_id_polo']][$v['dia_sem']] = $v['n_curso'];
}

$setup = sql::get($model::$sistema . '_setup', '*', null, 'fetch');
$qt_turma = $setup['qt_turma'];
$polos = sql::idNome($model::$sistema . '_polo');

$sql = "SELECT * FROM " . $model::$sistema . "_turma t "
        . " JOIN " . $model::$sistema . "_curso c on c.id_curso = t.fk_id_curso "
        . " and t.fk_id_pl = $id_pl ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
if ($array) {
    foreach ($array as $v) {
         @$qp[$v['fk_id_polo']]['abrevCurso'][$v['periodo']][$v['dia_sem']][$v['horario']] = $v['abrev'];
    }
}

$sql = "SELECT * FROM " . $model::$sistema . "_turma_aluno ta "
        . " JOIN " . $model::$sistema . "_turma t on t.id_turma = ta.fk_id_turma "
        . " and t.fk_id_pl = $id_pl "
        . " JOIN " . $model::$sistema . "_curso c on c.id_curso = t.fk_id_curso "
        . " JOIN ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa "
        . " AND ta2.fk_id_tas = 0 "
        . " JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma AND t.fk_id_pl = t2.fk_id_pl ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
if ($array) {
    foreach ($array as $v) {
        @$aluPorCur[$v['periodo']][$v['fk_id_curso']]++;
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
    if ($qp) {
        ksort($qp);
    }
}

$tg = $qt_turma * count($polos);
$diasSemana = $model->diaSemana();
$periodos = !empty($model::$periodos) ? $model::$periodos : [];
$horarios = !empty($model::$horarios) ? $model::$horarios : [];
$colspanTitulo = count($diasSemana) + 2;
$colspanTotal = max(1, count($diasSemana));
$capacidadePorPeriodoDia = $tg * count($horarios);
$capacidadePorDia = $tg * count($horarios) * count($periodos);
$capacidadeTotal = $capacidadePorDia * count($diasSemana);
$capacidadePoloPeriodoDia = $qt_turma * count($horarios);
$capacidadePoloDia = $qt_turma * count($horarios) * count($periodos);
$capacidadePoloTotal = $capacidadePoloDia * count($diasSemana);

$renderQuadroVagas = function ($titulo, $dados, $capacidadeHorario, $capacidadePeriodoDia, $capacidadeDia, $capacidadeTotal, $mostrarCurso = false) use ($diasSemana, $periodos, $horarios, $colspanTitulo, $colspanTotal) {
    $rowspanPeriodo = count($horarios) + 1;
    ?>
    <table class="table table-bordered table-hover table-responsive border">
        <tr>
            <td colspan="<?= $colspanTitulo ?>" style="text-align: center; font-weight: bold; font-size: 1.4em">
                <?= $titulo ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">

            </td>
            <?php
            foreach ($diasSemana as $kDia => $dia) {
                ?>
                <td>
                    <?= $dia ?>
                    <?php
                    if ($mostrarCurso) {
                        ?>
                        <br />
                        <?= '' // @$diaCur[$id_polo][$kDia] ?>
                        <?php
                    }
                    ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <?php
        foreach ($periodos as $kPeriodo => $periodo) {
            $linhaHorario = 0;
            foreach ($horarios as $kHorario => $horario) {
                ?>
                <tr>
                    <?php
                    if ($linhaHorario === 0) {
                        ?>
                        <td rowspan="<?= $rowspanPeriodo ?>">
                            <?= $periodo ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td>
                        <?= $horario ?>
                    </td>
                    <?php
                    foreach ($diasSemana as $kDia => $dia) {
                        $qta = intval($capacidadeHorario - ($dados['totalhorario'][$kPeriodo][$kDia][$kHorario] ?? 0));
                        ?>
                        <td>
                            <span style="color: <?= $qta > 0 ? 'blue' : 'red' ?>; font-weight: bold"><?= $qta ?></span>
                            <?php
                            if ($mostrarCurso) {
                                ?>
                                : <?= empty($dados['abrevCurso'][$kPeriodo][$kDia][$kHorario]) ? 'Vazia' : $dados['abrevCurso'][$kPeriodo][$kDia][$kHorario] ?>
                                <?php
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
                $linhaHorario++;
            }
            ?>
            <tr>
                <td>
                    Total da <?= $periodo ?>
                </td>
                <?php
                foreach ($diasSemana as $kDia => $dia) {
                    $qta = intval($capacidadePeriodoDia - ($dados['totalPer'][$kPeriodo][$kDia] ?? 0));
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
            foreach ($diasSemana as $kDia => $dia) {
                $qta = intval($capacidadeDia - ($dados['totalDiaSem'][$kDia] ?? 0));
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
            <td colspan="<?= $colspanTotal ?>">
                <?php
                $qta = intval($capacidadeTotal - ($dados['total'] ?? 0));
                ?>
                <span style="color: <?= $qta > 0 ? 'blue' : 'red' ?>; font-weight: bold"><?= $qta ?></span>
            </td>
        </tr>
    </table>
    <?php
};
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
    foreach ($model::$periodos as $p => $n_p) {
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
    <?php $renderQuadroVagas('Quadro Geral', $q ?? [], $tg, $capacidadePorPeriodoDia, $capacidadePorDia, $capacidadeTotal); ?>
    <br /><br />
    <?php
    foreach ($polos as $id_polo => $n_polo) {
        ?>
        <?php $renderQuadroVagas('Quadro do Núcleo ' . $n_polo, $qp[$id_polo] ?? [], $qt_turma, $capacidadePoloPeriodoDia, $capacidadePoloDia, $capacidadePoloTotal, true); ?>
        <?php
    }
    ?>
</div>
