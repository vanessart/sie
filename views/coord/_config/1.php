<?php

$sql = " select d.n_disc, d.id_disc, hd.fk_id_disc, hd.id_hd from ge_disciplinas d "
        . " left join coord_hab_disc hd on hd.fk_id_disc = d.id_disc "
        . " order by d.n_disc ";
$query = $model->db->query($sql);
$array = $query->fetchAll();
$sqlkey = DB::sqlKey('habDisc');
foreach ($array as $k => $v) {
    if (empty($v['fk_id_disc'])) {
        $ac = 'add';
        $tt = 'Habilitar';
    } else {
        $ac = 'del';
        $tt = 'Desabilitar';
    }
    $array[$k]['act'] = formulario::submit($tt, $sqlkey, ['id_disc' => $v['id_disc'], 'ac' => $ac, 'activeNav' => 1, 'id_hd' => $v['id_hd']]);
}
$form['array'] = $array;
$form['fields'] = [
    'Disciplina' => 'n_disc',
    '||' => 'act'
];
tool::relatSimples($form);
?>
                      
