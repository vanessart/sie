<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = toolErp::id_inst();
$sql = "SELECT "
        . " p.fk_id_inst_maker, n_polo "
        . " FROM maker_escola e "
        . " JOIN maker_polo p on p.id_polo = e.fk_id_polo "
        . " WHERE fk_id_inst = $id_inst ";
$query = pdoSis::getInstance()->query($sql);
$polo = $query->fetch(PDO::FETCH_ASSOC);
if ($polo) {
    $nPolo = $polo['n_polo'];
    ob_start();
    $pdf = new pdf();
    $pdf->orientation = 'L';
    $pdf->mgt = 68;
    $pdf->headerAlt = '<img src="' . ABSPATH . '/'. INCLUDE_FOLDER .'/images/topo.jpg"/>' . '<div style="text-align: center; font-weight: bold; font-size: 14px">Lista Geral Interna da Escola<br>Salas Maker - Polo: ' . $nPolo . '<br>Escola de Origem: ' . toolErp::n_inst() . '</div>';
    $alunos = $model->interna($id_inst, 't.n_turma, p.n_pessoa');
    ?>
    <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
        <tr>
            <td>
                Polo
            </td>
            <td>
                Turma Maker
            </td>
            <td></td>
            <td>
                RSE
            </td>
            <td>
                Nome 
            </td>
            <td>
                Turma de Origem
            </td>
            <td>
                Dia da Semana
            </td>
            <td>
                Horário
            </td>
            <td>
                Período
            </td>
        </tr>
        <?php
        foreach ($alunos as $v) {
            if (empty($itOld)) {
                $cont = 1;
            } elseif ($itOld != $v['Turma Maker']) {
                $cont = 1;
            }
            $itOld = $v['Turma Maker'];
            ?>
            <tr>
                <td style="text-align: center">
                    <?= $v['polo'] ?>    
                </td>
                <td style="text-align: center">
                    <?= $v['Turma Maker'] ?>    
                </td>
                <td>
                    <?= $cont ?>
                </td>
                <td style="text-align: center">
                    <?= $v['RSE'] ?> 
                </td>
                <td>
                    <?= $v['Nome'] ?>  
                </td>
                <td style="text-align: center">
                    <?= $v['Turma de Origem'] ?>    
                </td>
                <td style="text-align: center">
                    <?= $v['Dia da Semana'] ?>     
                </td>
                <td>
                    <?= $v['Horário'] ?>     
                </td>
                <td style="text-align: center">
                    <?= $v['Período'] ?>    
                </td>
            </tr>
            <?php
            $cont++;
        }
        ?>
    </table>
    <?php
    $pdf->exec();
}