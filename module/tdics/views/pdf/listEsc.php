<?php
@$id_pl = sql::get($model::$sistema . '_pl', 'id_pl', ['ativo' => 1], 'fetch')['id_pl'];
$polos = sql::idNome($model::$sistema . '_polo');
$cursos = sql::idNome($model::$sistema . '_curso');
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
if (!$id_inst) {
    $id_inst = toolErp::id_inst();
}
$a = $model->alunoEsc($id_pl, $id_inst);
if ($a) {
    foreach ($a as $v) {
        $nucleos[$v['fk_id_polo']][$v['id_pessoa']] = $v;
    }
}
if (!empty($nucleos)) {
    ob_start();
    $pdf = new pdf();
    $pdf->mgt = '30';
    $pdf->orientation = 'L';
    $pdf->headerAlt = '<img style="height: 100px" src="' . ABSPATH . '/'. INCLUDE_FOLDER .'/images/topo.jpg"/>';
    $c = 1;
    foreach ($nucleos as $kn => $n) {
        ?>
        <div style="text-align: center; font-weight: bold; font-size: 1.2em">
            Salas <?= $this->sistema ?> - Núcleo: <?= $polos[$kn] ?> 
        </div>
        <br /><br />
        <style>
            td{
                padding: 3px;
            }
        </style>
        <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
            <tr>
                <td>
                    Matrícula
                </td>
                <td>
                    Turma de Origem
                </td>
                <td>
                    Nome
                </td>
                <td>
                    Turma
                </td>
                <td>
                    Dia
                </td>
                <td>
                    Período
                </td>
                <td>
                    Horário
                </td>
            </tr>
            <?php
            foreach ($n as $v) {
                ?>
                <tr>
                    <td>
                        <?= $v['id_pessoa'] ?>
                    </td>
                    <td>
                        <?= $v['turmaEsc'] ?>
                    </td>
                    <td>
                        <?= $v['n_pessoa'] ?>
                    </td>
                    <td>
                        <?= $v['n_turma'] ?>
                    </td>
                    <td>
                        <?= $model->diaSemana($v['dia_sem']) ?>
                    </td>
                    <td>
                        <?= $v['periodo'] == 'M' ? 'Manhã' : 'Tarde' ?>
                    </td>
                    <td>
                        <?= $model->horario($v['fk_id_polo'], $v['periodo'], $v['horario']) ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
        ?>
        <?php
        if (count($nucleos) > $c++) {
            ?>
            <div style="page-break-after: always"></div>
            <?php
        }
    }




    $pdf->exec('Lista.pdf');
}


