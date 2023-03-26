<?php
if (!empty($_REQUEST['id_curso'])) {
    $sql = " select * from coord_grupo cc "
            . " join ge_disciplinas gd on gd.id_disc = cc.fk_id_disc "
            . " where fk_id_curso = " . $_REQUEST['id_curso']
            . " order by n_disc, n_gr ";
    $query = $model->db->query($sql);
    $d = $query->fetchAll();
    foreach ($d as $v) {
        $grup[$v['n_disc']][$v['id_gr']] = $v['n_gr'];
    }
    if (!empty($grup)) {
        formulario::select('id_gr', $grup, 'Grupo', @$_REQUEST['id_gr'], 1, @$_REQUEST);
    } else {
        ?>
        <div class="alert alert-warning ">
            Não há grupo neste curso
        </div>
        <?php
    }
}
?>

