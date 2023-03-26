<?php
 $sql = "SELECT c.n_ciclo, a.nota FROM prod.aaa_graf_ciclo a 
JOIN prod.aa_ciclos c on c.id_ciclo = a.id_ciclo 
order by a.ordem ";
$query = $model->db->query($sql);
$dados = $query->fetchAll();

foreach ($dados as $v) {
    $graf['fields'][$v['n_ciclo']] = round($v['nota'], 1);
}

$graf['titulo'] = 'Seriação';
//$graf['legenda']='Escola';

include ABSPATH . '/views/relat/graf_linha.php';
?>