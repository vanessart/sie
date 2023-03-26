<?php

$tipo = @$_POST['tipo'];
$sql = "SELECT * FROM prod.aaa_graf a JOIN prod.aa_ciclos ci on ci.id_ciclo = a.fk_id_ciclo ";

if (empty($tipo)) {
    $sql = "SELECT i.n_inst, e.nota FROM prod.aaa_esc e "
            . "JOIN prod.instancia i on i.id_inst = e.id_inst ORDER by e.nota DESC";
} else {
    $sql = "SELECT * FROM prod.`aaa_graf_tipo` "
            . " WHERE `tipo` LIKE '$tipo' "
            . " ORDER BY `aaa_graf_tipo`.`nota` DESC ";
}
$query = $model->db->query($sql);
$dados = $query->fetchAll();

foreach ($dados as $v) {
    $graf['fields'][$v['n_inst']] = round($v['nota'],1);
}

$graf['titulo'] = @$_POST['titulo'];
//$graf['legenda']='Escola';

include ABSPATH . '/views/relat/graf_barra1.php';
?>
