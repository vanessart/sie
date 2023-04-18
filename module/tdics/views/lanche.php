<?php
if (!defined('ABSPATH'))
    exit;

$polos = sql::idNome('tdics_polo');
@$id_pl = sql::get('tdics_pl', 'id_pl', ['ativo' => 1], 'fetch')['id_pl'];

$sql = "SELECT * FROM tdics_turma_aluno ta JOIN tdics_turma t on t.id_turma = ta.fk_id_turma "
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
$pdf->headerAlt = '<table style="width: 100%"><tr><td><img style="height: 100px" src="' . ABSPATH . '/includes/images/topo.jpg"/></td><td style="text-align: center; font-weight: bold; font-size: 14px">Controle de Lanches</td></tr></table>';


die('111');

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
            <td colspan="2">
                <?= $v ?>
            </td>
            <?php
        }
        ?>
    </tr>
    <tr>
        <?php
        foreach ($model->diaSemana() as $k => $v) {
            ?>
            <td>
                Manhã
            </td>
            <td>
                Tarde
            </td>
            <?php
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
                foreach (['M' => 'Manhã', 'T' => 'Tarde'] as $kper => $per) {
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
            ?>
            <td>
                Manhã
            </td>
            <td>
                Tarde
            </td>
            <?php
        }
        ?>
    </tr>
    <tr>
        <?php
        foreach ($model->diaSemana() as $k => $v) {
            ?>
            <td>
                <?= intval(@$q['totalPer']['M'][$k]) ?>
            </td>
            <td>
               <?= intval(@$q['totalPer']['T'][$k]) ?>
            </td>
            <?php
        }
        ?>
    </tr>
    <tr>
        <?php
        foreach ($model->diaSemana() as $k => $v) {
            ?>
        <td colspan="2">
                <?= intval(@$q['totalDiaSem'][$k]) ?>
            </td>
            <?php
        }
        ?>
    </tr>
    <tr>
        <td colspan="10">
            <?= intval(@$q['total']) ?>
        </td>
    </tr>
</table>
<?php
$pdf->exec();
?>