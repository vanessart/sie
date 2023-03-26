<?php
if (!defined('ABSPATH'))
    exit;
$diaSem = [
    2 => 'Segunda',
    3 => 'Terça',
    4 => 'Quarta',
    5 => 'Quinta',
    6 => 'Sexta'
];
$per = [
    'M' => 'Manhã',
    'T' => 'Tarde'
];
$polos_ = sql::get('maker_polo', 'id_polo, n_polo, fk_id_inst_maker ', ['>' => 'id_polo']);
foreach ($polos_ as $y) {
    if ($y['id_polo'] != 16) {
        $n_polo = $y['n_polo'];
        $a = $model->alimento($y['fk_id_inst_maker']);

        foreach ($per as $kp => $p) {
            $array[$n_polo][$p] = [
                'Segunda' => @$a['periodo'][$kp][2],
                'Terça' => @$a['periodo'][$kp][3],
                'Quarta' => @$a['periodo'][$kp][4],
                'Quinta' => @$a['periodo'][$kp][5],
                'Sexta' => @$a['periodo'][$kp][6]
            ];
            @$totalPer[$p][2] += @$a['periodo'][$kp][2];
            @$totalPer[$p][3] += @$a['periodo'][$kp][3];
            @$totalPer[$p][4] += @$a['periodo'][$kp][4];
            @$totalPer[$p][5] += @$a['periodo'][$kp][5];
            @$totalPer[$p][6] += @$a['periodo'][$kp][6];
            @$total + @$a['periodo'][$kp][2] + @$a['periodo'][$kp][3] + @$a['periodo'][$kp][4] + @$a['periodo'][$kp][5] + @$a['periodo'][$kp][6];
        }
    }
}
ob_start();
$totalGeral = 0;
$alimento = $pdf = new pdf();
$pdf->mgt = '30';
$pdf->orientation = 'L';
$pdf->headerAlt = '<table style="width: 100%"><tr><td><img style="height: 100px" src="' . ABSPATH . '/includes/images/topo.jpg"/></td><td style="text-align: center; font-weight: bold; font-size: 14px">Controle de Lanches</td></tr></table>';
?>
<style>
    table{
        font-size: 12px;
    }
</style>
<table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
    <tr>
        <td rowspan="2">
            Polo
        </td>
        <?php
        foreach ($diaSem as $d) {
            ?>
            <td colspan="2" style="text-align: center">
                <?= $d ?>
            </td>
            <?php
        }
        ?>
    </tr>
    <tr>
        <?php
        foreach ($diaSem as $d) {
            foreach ($per as $p) {
                ?>
                <td style="text-align: center">
                    <?= $p ?>
                </td>
                <?php
            }
        }
        ?>
    </tr>
    <?php
    foreach ($array as $k => $v) {
        ?>
        <tr>
            <td>
                <?= $k ?>
            </td>
            <?php
            foreach ($diaSem as $d) {
                foreach ($per as $p) {
                    ?>
                    <td style="text-align: center">
                        <?= @$v[$p][$d] ?>
                    </td>
                    <?php
                }
            }
            ?>
        </tr>
        <?php
    }
    ?>
    <tr>
        <td rowspan="4">
            Totais
        </td>
        <?php
        foreach ($diaSem as $d) {
            foreach ($per as $p) {
                ?>
                <td style="text-align: center">
                    <?= $p ?>
                </td>
                <?php
            }
        }
        ?>
    </tr>
    <tr>
        <?php
        foreach ($diaSem as $kd => $d) {
            foreach ($per as $p) {
                ?>
                <td style="text-align: center">
                    <?= @$totalPer[$p][$kd] ?>
                </td>
                <?php
            }
        }
        ?>
    </tr>
    <tr>
        <?php
        foreach ($diaSem as $kd => $d) {
            $totalPorDia = 0;
            foreach ($per as $p) {
                $totalPorDia += @$totalPer[$p][$kd];
            }
            $totalGeral += @$totalPorDia;
            ?>
            <td colspan="2" style="text-align: center">
                <?= $totalPorDia ?>
            </td>
            <?php
        }
        ?>
    </tr>
    <tr>
        <td colspan="10" style="text-align: center">
            <?= $totalGeral ?>
        </td>
    </tr>
    <tr>
        <td colspan="11">
            Horários de Consumo: (deve ser entregue até este horário) Manhã até as 8:30 horas e a tarde até as 14:30 horas
        </td>
    </tr>
</table>
<?php
$pdf->exec();
?>