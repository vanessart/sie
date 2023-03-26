<?php
$cargo = sql::get('dtgp_cadampe_cargo');

foreach ($cargo as $v) {
    $car[$v['id_cargo']] = $v['n_cargo'];
}
$sql = "select n_insc, id_inscr from dtgp_sel_1_2017 order by n_insc";
$query = $model->db->query($sql);
$ins = $query->fetchAll();

foreach ($ins as $v) {

    $sql = "select class, fk_id_cargo, fk_id_inscr from dtpg_class cl "
            . " where fk_id_inscr = " . $v['id_inscr'];
    $query = $model->db->query($sql);
    $array = $query->fetchAll();

    foreach ($array as $a) {
        $inscritos[] = $a['fk_id_inscr'];
        $dados[$v['id_inscr']]['nome'] = $v['n_insc'];
        $dados[$v['id_inscr']]['cl'][$a['fk_id_cargo']] = $a['class'];
    }
}
?>
<table border=1 cellspacing=0 cellpadding=1 style="width: 100%">
    <tr>
        <td>
            Nome
        </td>
        <?php
        foreach ($cargo as $c) {
            ?>
            <td>
                <?php echo $c['n_cargo'] ?>
            </td>
            <?php
        }
        ?>
    </tr> 

    <?php
    foreach ($ins as $v) {
        if (in_array($v['id_inscr'], $inscritos)) {
            ?>
            <tr>
                <td>
                    <?php echo $v['n_insc'] ?>
                </td>
                <?php
                foreach ($cargo as $c) {
                    ?>
                    <td>
                        <?php echo @$dados[$v['id_inscr']]['cl'][$c['id_cargo']] ?>
                    </td>
                    <?php
                }
                ?>
            </tr> 
            <?php
        }
    }
    ?>
</table>
