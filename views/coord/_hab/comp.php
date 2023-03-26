<?php
$sql = "select id_comp, n_comp from coord_competencia "
        . "where fk_id_gr = " . @$_REQUEST['id_gr'];
$query = $model->db->query($sql);
$array = $query->fetchAll();
foreach ($array as $v) {
    $comp[$v['id_comp']] = $v['n_comp'];
}
if (!empty($comp)) {
    formulario::select('id_comp', $comp, 'Competência', @$_REQUEST['id_comp'], 1, @$_REQUEST);
} else {
    ?>
    <div class="alert alert-warning">
        Não há Competência neste Grupo
    </div>
    <?php
}
?>