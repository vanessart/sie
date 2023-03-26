<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
if (empty($id_inst)) {
    $sql = "SELECT count(id_ch) as ct FROM `lab_chrome` WHERE `fk_id_inst` is null AND `fk_id_modem` = 1";
} else {
    $sql = "SELECT count(id_ch) as ct FROM `lab_chrome` WHERE `fk_id_inst` = $id_inst AND `fk_id_modem` = 1";
}
$query = pdoSis::getInstance()->query($sql);
@$ct = $query->fetch(PDO::FETCH_ASSOC)['ct'];
?>
<div class="alert alert-dark text-center">
    Este Lote
    <br />
    <div style="font-weight: bold; font-size: 45px; text-align: center">
        <?= intval($ct) ?>
    </div>
</div>