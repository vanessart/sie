<?php
/** @var tdicsModel $model */

if (!defined('ABSPATH'))
    exit;

$polos = sql::idNome($model::$sistema . '_polo');
@$id_pl = sql::get($model::$sistema . '_pl', 'id_pl', ['ativo' => 1], 'fetch')['id_pl'];

$sql = "SELECT * FROM " . $model::$sistema . "_turma_aluno ta JOIN " . $model::$sistema . "_turma t on t.id_turma = ta.fk_id_turma "
        . " and t.fk_id_pl = $id_pl";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
if ($array) {
    foreach ($array as $v) {
        @$q['total']++;
        @$q['totalDiaSem'][$v['dia_sem']]++;
        @$q['totalPer'][$v['periodo']][$v['dia_sem']]++;

        @$qp[$v['fk_id_polo']][$v['periodo']][$v['dia_sem']]++;
    }
}
ob_start();
$pdf = new pdf();
$pdf->mgt = '30';
$pdf->orientation = 'L';
$pdf->headerAlt = '<table style="width: 100%"><tr><td><img style="height: 100px" src="' . ABSPATH . '/'. INCLUDE_FOLDER .'/images/topo.jpg"/></td><td style="text-align: center; font-weight: bold; font-size: 14px">Controle de Lanches</td></tr></table>';
?>
<style>
    td{
        text-align: center;
        padding: 2px;
    }
</style>
<table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
    <tr>
        <td rowspan="2">
            Núcleos
        </td>
        <?php
        foreach ($model->diaSemana() as $k => $v) {
            ?>
            <td colspan="<?= count($model::$periodos) ?>">
                <?= $v ?>
            </td>
            <?php
        }
        ?>
    </tr>
    <tr>
        <?php
        foreach ($model->diaSemana() as $k => $v) {
            foreach ($model::$periodos as $kper => $per) {
                ?>
                <td>
                    <?= $per ?>
                </td>
                <?php
            }
        }
        ?>
    </tr>
    <?php
    foreach ($polos as $id_polo => $n_polo) {
        ?>
        <tr>
            <td style="text-align: left">
                <?= $n_polo ?>
            </td>
            <?php
            foreach ($model->diaSemana() as $k => $v) {
                foreach ($model::$periodos as $kper => $per) {
                    ?>
                    <td>
                        <?php
                        if (!empty($qp[$id_polo][$kper][$k])) {
                            echo $qp[$id_polo][$kper][$k];
                        } else {
                            echo 'O';
                        }
                        ?>
                    </td>
                    <?php
                }
                ?>
                <?php
            }
            ?>
        </tr>
        <?php
    }
    ?>
    <tr>
        <td rowspan="4" style="text-align: left">
            Totais
        </td>
        <?php
        foreach ($model->diaSemana() as $k => $v) {
            foreach ($model::$periodos as $kper => $per) {
                ?>
                <td>
                    <?= $per ?>
                </td>
                <?php
            }
        }
        ?>
    </tr>
    <tr>
        <?php
        foreach ($model->diaSemana() as $k => $v) {
            foreach ($model::$periodos as $kper => $per) {
                ?>
                <td>
                    <?= intval(@$q['totalPer'][$kper][$k]) ?>
                </td>
                <?php
            }
        }
        ?>
    </tr>
    <tr>
        <?php
        foreach ($model->diaSemana() as $k => $v) {
            ?>
        <td colspan="<?= count($model::$periodos) ?>">
                <?= intval(@$q['totalDiaSem'][$k]) ?>
            </td>
            <?php
        }
        ?>
    </tr>
    <tr>
        <td colspan="<?= count($model->diaSemana()) * count($model::$periodos) ?>">
            <?= intval(@$q['total']) ?>
        </td>
    </tr>
</table>
<?php
$pdf->exec();
?>