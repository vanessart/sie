<?php

if (!defined('ABSPATH'))
    exit;
if (!in_array(tool::id_pessoa(), [1, 6])) {
    exit;
}
$cate = sql::idNome('inscr_categoria', ['fk_id_evento' => $model->evento]);
foreach ($cate as $k => $v) {
    $array = $model->classifica($k);
    foreach ($array as $v) {
        $ins['class'] = $v['Classificação'];
        $ins['fk_id_ec'] = $v['Inscrição'];
        $ins['pontos'] = $v['Pontos'];
        $ins['fk_id_cate'] = $k;
        $model->db->insert('inscr_classifica', $ins, 1);
    }
}


exit();
if (in_array(tool::id_pessoa(), [1, 5])) {
    $sql = "SELECT i.fk_id_pessoa , c.fk_id_cargo FROM inscr_evento_categoria_validado i JOIN inscr_categoria c on c.id_cate = i.fk_id_cate ORDER BY `i`.`fk_id_pessoa` ASC ";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($array as $v) {
        if (empty($c[$v['fk_id_pessoa']])) {
            $c[$v['fk_id_pessoa']] = '|';
        }
        $c[$v['fk_id_pessoa']] .= $v['fk_id_cargo'] . '|';
    }
    foreach ($c as $k => $v) {
        $sql = "update cadam_cadastro set "
                . " cargo = '$v', "
                . " cargos_e = '$v' "
                . " where fk_id_pessoa = $k";
        $query = pdoSis::getInstance()->query($sql);
    }
}