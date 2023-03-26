<?php
if (!defined('ABSPATH'))
    exit;
$insc = $model->_formAdm;
$id_cur = filter_input(INPUT_POST, 'id_cur', FILTER_SANITIZE_NUMBER_INT);
if(!empty($id_cur)){
$sql = "SELECT COUNT(`id_inscr`) as ct FROM `quali_incritos_$insc` WHERE `situacao` =1 AND `fk_id_cur` = $id_cur ";
$query = pdoSis::getInstance()->query($sql);
       echo $query->fetch(PDO::FETCH_ASSOC)['ct'];
}