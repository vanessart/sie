<?php
if (!defined('ABSPATH'))
    exit;
$id_turma_1 = filter_input(INPUT_POST, 'id_turma_1', FILTER_SANITIZE_NUMBER_INT);
$id_turma_2 = filter_input(INPUT_POST, 'id_turma_2', FILTER_SANITIZE_NUMBER_INT);
permuta($id_turma_1, $id_turma_2);
$polos = sql::idNome('maker_polo');
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$periodo = filter_input(INPUT_POST, 'periodo', FILTER_SANITIZE_STRING);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
if (!$id_pl) {
    $id_pl = $model->setup();
}
$pls = ng_main::periodosPorSituacao([1, 2]);

if ($periodo && $id_polo) {
    $id_inst = sql::get('maker_polo', 'fk_id_inst_maker', ['id_polo' => $id_polo], 'fetch')['fk_id_inst_maker'];
    $turmas = sql::get('maker_gt_turma', 'n_turma, id_turma, transporte, fk_id_inst_sieb', ['fk_id_inst' => $id_inst, '>' => 'n_turma', 'fk_id_pl' => $id_pl, 'periodo' => $periodo]);

    if ($turmas) {
        foreach ($turmas as $v) {
            $turmaList[$v['id_turma']] = $v['n_turma'];
            $tur[substr($v['n_turma'], 1, 3)] = substr($v['n_turma'], 0, 1);
            $transp[substr($v['n_turma'], 1, 3)] = $v['transporte'];
            $esc[substr($v['n_turma'], 1, 3)] = $v['fk_id_inst_sieb'];
        }
    }
}
$diaSem = [
    2 => 'Segunda',
    3 => 'Terça',
    4 => 'Quarta',
    5 => 'Quinta',
    6 => 'Sexta'
];
?>
<div class="body">
    <form method="POST">
        <div class="fieldTop">
            Permutar Turmas
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::select('id_pl', $pls, 'Período Letivo', $id_pl) ?>
            </div>
            <div class="col">
                <?= formErp::select('id_polo', $polos, 'Polo', $id_polo) ?>
            </div>
            <div class="col">
                <?= formErp::select('periodo', ['M' => 'Manhã', 'T' => 'Tarde'], 'Período', empty($periodo) ? 'T' : $periodo) ?>
            </div>
            <div class="col">
                <button class="btn btn-primary">
                    Selecionar
                </button>
            </div>
        </div>
        <br />
    </form>

    <?php
    if ($periodo && $id_polo) {
        ?>
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>

                </td>
                <?php
                foreach (range(2, 6) as $ds) {
                    ?>
                    <td style="text-align: center; font-weight: bold">
                        <?= $diaSem[$ds] ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <?php
            foreach (range(1, 2) as $per) {
                ?>
                <tr>
                    <td>
                        <?= $per ?>º Horário
                    </td>
                    <?php
                    foreach (range(2, 6) as $ds) {
                        ?>
                        <td style="text-align: center; font-weight: bold">
                            <p>
                                <?= $tur[$periodo . $ds . $per] . $periodo . $ds . $per . str_pad($id_polo, 2, "0", STR_PAD_LEFT) ?>
                            </p>
                            <p>
                                <?= empty($transp[$periodo . $ds . $per]) ? 'Sem' : 'Com' ?> Transporte
                            </p>
                            <?php
                        }
                        ?>
                </tr>
                <?php
            }
            ?>
        </table>
        <form method="POST">
            <div class="row">
                <div class="col">
                    <?= formErp::select('id_turma_1', $turmaList, 'Turma 1', $id_turma_1) ?>
                </div>
                <div class="col">
                    <?= formErp::select('id_turma_2', $turmaList, 'Turma 2', $id_turma_2) ?>
                </div>
                <div class="col">
                    <?=
                    formErp::hidden([
                        'id_polo' => $id_polo,
                        'periodo' => $periodo,
                        'id_pl' => $id_pl
                    ])
                    . formErp::button('permutar')
                    ?>
                </div>
            </div>
            <br />
        </form>
        <?php
    }
    ?>
</div>
<?php

function permuta($id_turma_1, $id_turma_2) {
    if (($id_turma_1 && $id_turma_2) && ($id_turma_1 != $id_turma_2)) {
        $sql = "SELECT * FROM `maker_gt_turma` WHERE `id_turma` = $id_turma_1 ORDER BY `n_turma` ASC ";
        $query = pdoSis::getInstance()->query($sql);
        $turma_1 = $query->fetch(PDO::FETCH_ASSOC);
        $sql = "SELECT * FROM `maker_gt_turma` WHERE `id_turma` = $id_turma_2 ORDER BY `n_turma` ASC ";
        $query = pdoSis::getInstance()->query($sql);
        $turma_2 = $query->fetch(PDO::FETCH_ASSOC);
        $turma_1_set['n_turma'] = substr($turma_1['n_turma'], 0, 2) . substr($turma_2['n_turma'], 2, 2) . substr($turma_1['n_turma'], -2);
        $turma_2_set['n_turma'] = substr($turma_2['n_turma'], 0, 2) . substr($turma_1['n_turma'], 2, 2) . substr($turma_2['n_turma'], -2);
        $sql = "UPDATE `maker_gt_turma` SET `codigo`= 'x' WHERE `id_turma` = $id_turma_2";
        pdoSis::getInstance()->query($sql);

        $sql = "UPDATE `maker_gt_turma` SET `n_turma`= '" . $turma_1_set['n_turma'] . "',`codigo`= '" . $turma_1_set['n_turma'] . "' WHERE `id_turma` = $id_turma_1";
        pdoSis::getInstance()->query($sql);

        $sql = "UPDATE `maker_gt_turma` SET `n_turma`= '" . $turma_2_set['n_turma'] . "',`codigo`= '" . $turma_2_set['n_turma'] . "' WHERE `id_turma` = $id_turma_2";
        pdoSis::getInstance()->query($sql);
    }
}
