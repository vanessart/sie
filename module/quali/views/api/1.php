<?php
$formulario = $model->_formAdm;
if (!defined('ABSPATH'))
    exit;

$id_inscr = filter_input(INPUT_POST, 'id_inscr', FILTER_SANITIZE_STRING);
$situacao = filter_input(INPUT_POST, 'situacao', FILTER_SANITIZE_STRING);
if (in_array(tool::id_nilvel(), [13, 2])) {
    if (empty($situacao)) {
        $sql = "UPDATE `quali_incritos_$formulario` SET `situacao` = null WHERE `quali_incritos_$formulario`.`id_inscr` = $id_inscr;";
    } else {
        $sql = "UPDATE `quali_incritos_$formulario` SET `situacao` = '$situacao' WHERE `quali_incritos_$formulario`.`id_inscr` = $id_inscr;";
    }
    try {
        $query = pdoSis::getInstance()->query($sql);
        echo json_encode(['result'=>1]);
    } catch (Exception $exc) {
        echo json_encode(['result'=> null]);
    }
}