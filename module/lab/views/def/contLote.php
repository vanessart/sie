<?php
if (!defined('ABSPATH'))
    exit;
$id_cm = filter_input(INPUT_POST, 'id_cm', FILTER_SANITIZE_STRING);
$modelo = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_STRING);
if (!empty($id_cm)) {
    $sql = "SELECT count(id_ch) as ct FROM `lab_chrome` WHERE `fk_id_cm` = $id_cm ";
    $query = pdoSis::getInstance()->query($sql);
    @$ct = $query->fetch(PDO::FETCH_ASSOC)['ct'];
}
?>
<div class="alert alert-dark text-center">
    <?= $modelo ?>
    <br />
    <div style="font-weight: bold; font-size: 45px; text-align: center">
        <?= intval($ct) ?>
    </div>
</div>