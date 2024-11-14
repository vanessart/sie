<?php
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$n_polo = sql::get('tdics_polo', 'n_polo', ['id_polo' => $id_polo], 'fetch')['n_polo'];
$a = $model->alunoEsc($id_pl, null, $id_polo, $id_turma);
$cursos = sql::idNome('tdics_curso');
foreach ($a as $v) {
    $turmas[$v['n_turma']][$v['id_pessoa']] = $v;
}
ob_start();
$pdf = new pdf();
$pdf->mgt = '30';
$pdf->orientation = 'L';
$pdf->headerAlt = '<img style="height: 100px" src="' . ABSPATH . '/'. INCLUDE_FOLDER .'/images/topo.jpg"/>';

if (!empty($turmas)) {
    $c = 1;
    foreach ($turmas as $k => $v) {
        $t = current($v);
        ?>
        <div style="text-align: center; font-weight: bold; font-size: 1.2em">
            Salas <?= $this->sistema ?>  - Núcleo: <?= $n_polo ?> - Turma: <?= $k ?> 
        </div>
        <br /><br />
        <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
            <tr>
                <td>
                    Curso: <?= $cursos[$t['fk_id_curso']] ?>
                </td>
                <td>
                    <?= $model->diaSemana($t['dia_sem']) ?> de <?= $t['periodo'] == 'M' ? 'Manhã' : 'Tarde' ?> no <?= $t['horario'] ?>º horário
                </td>
            </tr>
        </table>
        <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633">
            <tr>
                <td>
                    Matrícula
                </td>
                <td>
                    Nome
                </td>
                <td>
                    Escola
                </td>
                <td>
                    Turma de Origem
                </td>
            </tr>
            <?php
            foreach ($v as $y) {
                ?>
                <tr>
                    <td>
                        <?= $y['id_pessoa'] ?>
                    </td>
                    <td>
                        <?= $y['n_pessoa'] ?>
                    </td>
                    <td>
                        <?= $y['n_inst'] ?>
                    </td>
                    <td>
                        <?= $y['turmaEsc'] ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
        if (count($turmas) > $c++) {
            ?>
            <div style="page-break-after: always"></div>
            <?php
        }
        ?>

        <?php
    }
    ?>

    <?php
}

$pdf->exec();
