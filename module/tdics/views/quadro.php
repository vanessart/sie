<?php
/** @var tdicsModel $model */

if (!defined('ABSPATH'))
    exit;

$polos = sql::idNome($model::$sistema . '_polo');
@$id_pl = sql::get($model::$sistema . '_pl', 'id_pl', ['ativo' => 1], 'fetch')['id_pl'];
$sql = "SELECT t.* 
        FROM " . $model::$sistema . "_turma_aluno ta
        JOIN " . $model::$sistema . "_turma t on t.id_turma = ta.fk_id_turma
        AND t.fk_id_pl = $id_pl
        JOIN ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa AND (ta2.fk_id_tas = 0 OR ta2.fk_id_tas IS NULL)
        JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma 
        JOIN ge_periodo_letivo pl on pl.id_pl = t2.fk_id_pl AND pl.at_pl = 1";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
if ($array) {
    foreach ($array as $v) {
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

$diasSemana = $model->diaSemana();
$periodos = !empty($model::$periodos) ? $model::$periodos : [];
$horarios = !empty($model::$horarios) ? $model::$horarios : [];
$colspanTitulo = count($diasSemana) + 2;
$colspanTotal = max(1, count($diasSemana));

$renderQuadro = function ($titulo, $dados = []) use ($diasSemana, $periodos, $horarios, $colspanTitulo, $colspanTotal) {
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
            foreach ($diasSemana as $dia) {
                ?>
                <td>
                    <?= $dia ?>
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
                        ?>
                        <td style="font-weight: initial;">
                            <?= $dados['totalhorario'][$kPeriodo][$kDia][$kHorario] ?? 0 ?>
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
                    ?>
                    <td>
                        <?= $dados['totalPer'][$kPeriodo][$kDia] ?? 0 ?>
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
                ?>
                <td>
                    <?= $dados['totalDiaSem'][$kDia] ?? 0 ?>
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
                <?= $dados['total'] ?? 0 ?>
            </td>
        </tr>
    </table>
    <?php
};
?>
<div class="body">
    <div class="fieldTop">
        Quadro de Alunos
    </div>
    <?php $renderQuadro('Quadro Geral', $q ?? []); ?>
    <br /><br />
    <?php
    foreach ($polos as $id_polo => $n_polo) {
        $renderQuadro('Quadro do Núcleo ' . $n_polo, $qp[$id_polo] ?? []);
    }
    ?>
</div>
