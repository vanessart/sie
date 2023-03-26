<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
if ($id_inst) {
    $alunos = $model->alunoList($id_inst);
    if ($alunos) {
        ob_start();
        $pdf = new pdf();
        $pdf->id_inst = $id_inst;
        $pdf->mgt= '50';
        $pdf->headerAlt = '<img src="' . ABSPATH . '/includes/images/topo.jpg"/>'.'<div style="text-align: center; font-weight: bold; font-size: 14px">Salas Maker'.(!empty($alunos)?'<br />Polo: ' . current($alunos)['Polo']:'').'</div>';
      ?>
   
        <br />
        <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
            <tr>
                <td>
                    RES
                </td>
                <td>
                    Nome
                </td>
                <td>
                    Turma Maker
                </td>
                <td>
                    Dia da Semana
                </td>
                <td>
                    Horário
                </td>
                <td>
                    Escola de Origem
                </td>
                <td>
                    Turma de Origem
                </td>
            </tr>
            <?php
            foreach ($alunos as $v) {
                ?>
                <tr>
                    <td>
                        <?= $v['RSE'] ?>
                    </td>
                    <td>
                        <?= $v['Nome'] ?>
                    </td>
                    <td>
                        <?= $v['Turma Maker'] ?>
                    </td>
                    <td>
                        <?= $v['Dia da Semana'] ?>
                    </td>
                    <td>
                        <?= $v['Horário'] ?>
                    </td>
                    <td>
                        <?= $v['Escola de Origem'] ?>
                    </td>
                    <td>
                        <?= $v['Turma de Origem'] ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
        $pdf->exec();
    }
}
?>