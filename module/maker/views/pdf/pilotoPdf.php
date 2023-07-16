<?php
if (!defined('ABSPATH'))
    exit;
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$n_polo = filter_input(INPUT_POST, 'n_polo', FILTER_UNSAFE_RAW);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_UNSAFE_RAW);
if ($id_turma) {
    $sql="SELECT * FROM maker_gt_turma_aluno ta JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa JOIN maker_aluno m on m.fk_id_pessoa = ta.fk_id_pessoa JOIN instancia i on i.id_inst = m.fk_id_inst WHERE ta.fk_id_turma = $id_turma ORDER BY p.n_pessoa ";
   $query = pdoSis::getInstance()->query($sql);
        $alunos = $query->fetchAll(PDO::FETCH_ASSOC);
    
   // $alunos = sql::get(['maker_gt_turma_aluno', 'pessoa', 'instancia'], 'n_pessoa, id_pessoa, n_inst', ['fk_id_turma' => $id_turma, '>' => 'n_pessoa']);
    if ($alunos) {
        ob_start();
        $pdf = new pdf();
        $pdf->mgt = '60';
        $pdf->mgb ='30';
        $pdf->orientation = 'L';
        $pdf->headerAlt = '<img src="' . ABSPATH . '/'. INCLUDE_FOLDER .'/images/topo.jpg"/>' . '<div style="text-align: center; font-weight: bold; font-size: 14px">Salas Maker - Polo: ' . $n_polo . ' - Turma: ' . $n_turma . '</div>';
        $diaSem = [
            2 => 'Segunda',
            3 => 'Terça',
            4 => 'Quarta',
            5 => 'Quinta',
            6 => 'Sexta'
        ];
        $sql = "SELECT * FROM `maker_horario` WHERE `periodo` LIKE '" . substr($n_turma, 1, 1) . "' AND `aula` = " . substr($n_turma, 3, 1) . " ";
        $query = pdoSis::getInstance()->query($sql);
        $h = $query->fetch(PDO::FETCH_ASSOC);
        ?>
        <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
            <tr>
                <td style="text-align: center">
                    <?= $diaSem[substr($n_turma, 2, 1)] ?>-feira de <?= substr($n_turma, 1, 1) == 'M' ? 'Manhã' : 'Tarde' ?>
                </td>
                <td style="text-align: center">
                    <?= substr($n_turma, 3, 1) ?>º Período (<?= $h['incio'] ?> às <?= $h['fim'] ?>)
                </td>
                <td style="text-align: center">
                    Ciclo <?= substr($n_turma, 0, 1) == 1 ? 'Básico' : 'Intermediário' ?>
                </td>
            </tr>
        </table>
        <table style="width: 100%; font-size: 10px" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
            <tr>
                <td>
                    RSE
                </td>
                <td>
                    Nome
                </td>
                <td>
                    Escola
                </td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <?php
            foreach ($alunos as $v) {
                ?>
                <tr>
                    <td>
                        <?= $v['id_pessoa'] ?>
                    </td>
                    <td>
                        <?= $v['n_pessoa'] ?>
                    </td>
                    <td>
                        <?php
                        $n_inst = explode(' ', $v['n_inst']);
                        unset($n_inst[0]);
                        $escola = implode(' ', $n_inst)
                        ?>
                        <?= toolErp::abrevia($escola, 15) ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
        $pdf->exec();
    }
}