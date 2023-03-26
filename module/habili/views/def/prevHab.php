<?php
if (!defined('ABSPATH'))
    exit;
$id_hab = filter_input(INPUT_POST, 'id_hab', FILTER_SANITIZE_NUMBER_INT);
$id_hab_previa = filter_input(INPUT_POST, 'id_hab_previa', FILTER_SANITIZE_NUMBER_INT);
$acao = filter_input(INPUT_POST, 'acao', FILTER_SANITIZE_STRING);
if ($acao == 'i') {
    $ins = ['fk_id_hab' => $id_hab, 'fk_id_hab_previa' => $id_hab_previa];
    $model->db->ireplace('coord_hab_previas', $ins, 1);
} elseif ($acao == 'del') {
    $sql = "DELETE FROM `coord_hab_previas` "
            . " WHERE fk_id_hab_previa = $id_hab_previa "
            . " and fk_id_hab = $id_hab ";
    $query = pdoSis::getInstance()->query($sql);
}
$sql = "SELECT fk_id_hab_previa, codigo, descricao, n_disc FROM coord_hab_previas "
        . " JOIN coord_hab ON coord_hab.id_hab = coord_hab_previas.fk_id_hab_previa "
        . " join ge_disciplinas d on d.id_disc = coord_hab.fk_id_disc "
        . " WHERE fk_id_hab like '$id_hab' "
        . " order by codigo";
$query = pdoSis::getInstance()->query($sql);
$previas = $query->fetchAll(PDO::FETCH_ASSOC);
if ($previas) {
    foreach ($previas as $v) {
        ?>
        <div class="alert alert-info">
            <table style="width: 100%">
                <tr>
                    <td>
                        <?= $v['codigo'] ?> - <?= $v['n_disc'] ?> - <?= $v['descricao'] ?>
                    </td>
                    <td style="width: 10px">
                        <button type="button" onclick="previaSet(<?= $v['fk_id_hab_previa'] ?>, 'del')" class="btn btn-danger">
                            X
                        </button>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
}
?>
