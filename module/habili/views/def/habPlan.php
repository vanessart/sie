<?php
if (!defined('ABSPATH'))
    exit;
ob_clean();
$id_pah = filter_input(INPUT_POST, 'id_pah', FILTER_SANITIZE_NUMBER_INT);
$id_plano = filter_input(INPUT_POST, 'id_plano', FILTER_SANITIZE_NUMBER_INT);
$id_hab = filter_input(INPUT_POST, 'id_hab', FILTER_SANITIZE_NUMBER_INT);
$op = filter_input(INPUT_POST, 'op', FILTER_SANITIZE_STRING);
if ($op == 'i') {
    $ins['fk_id_plano'] = $id_plano;
    $ins['fk_id_hab'] = $id_hab;
    $model->db->insert('coord_plano_aula_hab', $ins, 1);
} elseif ($op == 'del') {
    $sql = "DELETE FROM `coord_plano_aula_hab` WHERE `coord_plano_aula_hab`.`id_pah` = $id_pah ";
    $query = pdoSis::getInstance()->query($sql);
}

$array = $model->planoHabil($id_plano);
if ($array) {
    foreach ($array as $v) {
        ?>
        <table style="font-weight: bold" class="table table-bordered table-hover table-striped border">
            <tr>
                <td style="width:12%">
                    Disciplina
                </td>
                <td style="width:12%">
                    Unidade Temática
                </td>
                <td style="width: 13%">
                    Objeto de Conhecimento
                </td>
                <td style="width: 65%">
                    Habilidades
                </td>
                <td rowspan="2" style="width: 1%">
                    <p>
                        <button style="font-weight: bold" type="button" onclick="mais(<?= $v['id_hab'] ?>)" class="btn btn-primary">
                            +
                        </button>
                    </p>
                    <p>
                        <button type="button" onclick="habilidadeSet(<?= $v['id_pah'] ?>, 'del')" class="btn btn-danger">
                            X
                        </button>
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <?= $v['n_disc'] ?>
                </td>
                <td>
                    <?= $v['n_ut'] ?>
                </td>
                <td>
                    <?= $v['n_oc'] ?>
                </td>
                <td>
                    <?= $v['codigo'] ?> - <?= $v['descricao'] ?>
                </td>
            </tr>
        </table>
        <?php
    }
}
