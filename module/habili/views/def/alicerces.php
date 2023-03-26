<?php
if (!defined('ABSPATH'))
    exit;
$id_hab = filter_input(INPUT_POST, 'id_hab', FILTER_SANITIZE_NUMBER_INT);
$id_hab_alicerce = filter_input(INPUT_POST, 'id_hab_alicerce', FILTER_SANITIZE_NUMBER_INT);
$acao = filter_input(INPUT_POST, 'acao', FILTER_SANITIZE_STRING);
if ($acao == 'i') {
    $ins = ['fk_id_hab' => $id_hab, 'fk_id_hab_alicerce' => $id_hab_alicerce];
    $model->db->ireplace('coord_hab_alicerces', $ins, 1);
} elseif ($acao == 'del') {
    $sql = "DELETE FROM `coord_hab_alicerces` "
            . " WHERE fk_id_hab_alicerce = $id_hab_alicerce "
            . " and fk_id_hab = $id_hab ";
    $query = pdoSis::getInstance()->query($sql);
}
$sql = "SELECT fk_id_hab_alicerce, codigo, descricao FROM coord_hab_alicerces "
        . " JOIN coord_hab ON coord_hab.id_hab = coord_hab_alicerces.fk_id_hab_alicerce "
        . "  WHERE fk_id_hab like '$id_hab' "
        . " order by codigo";
$query = pdoSis::getInstance()->query($sql);
$alicerces = $query->fetchAll(PDO::FETCH_ASSOC);
if ($alicerces) {
    foreach ($alicerces as $v) {
        ?>
        <div class="alert alert-info">
            <table style="width: 100%">
                <tr>
                    <td>
                        <?= $v['codigo'] ?> - <?= $v['descricao'] ?>
                    </td>
                    <td style="width: 10px">
                        <button type="button" onclick="alicerceSet(<?= $v['fk_id_hab_alicerce'] ?>, 'del')" class="btn btn-danger">
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
