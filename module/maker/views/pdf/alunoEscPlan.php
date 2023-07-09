<?php
if (!defined('ABSPATH'))
    exit;
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$n_polo = filter_input(INPUT_POST, 'n_polo', FILTER_UNSAFE_RAW);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_UNSAFE_RAW);
if ($id_turma) {
    $sql = "SELECT distinct * FROM maker_gt_turma_aluno ta "
            . " join maker_gt_turma t on t.id_turma = ta.fk_id_turma and t.id_turma = $id_turma"
            . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
            . " JOIN instancia i on i.id_inst = t.fk_id_inst "
            . " ORDER BY p.n_pessoa ";
    $query = pdoSis::getInstance()->query($sql);
    $alunos = $query->fetchAll(PDO::FETCH_ASSOC);

    if ($alunos) {
        ob_start();
        $pdf = new pdf();
        $pdf->mgt = '50';
        $pdf->mgb = '30';
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
        <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
            <tr>
                <td>
                    RSE
                </td>
                <td>
                    Nome
                </td>

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

                </tr>
                <?php
            }
            ?>
        </table>
        <?php
        $pdf->exec();
    }
}