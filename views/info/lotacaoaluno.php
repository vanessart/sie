<?php
$sql = "select n_pessoa, id_pessoa, logradouro, num, n_inst, mae, complemento, codigo_classe as cod from pessoa p "
        . " left join  endereco e on e.fkid = p.id_pessoa "
        . "join ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa "
        . " join instancia i on i.id_inst = ta.fk_id_inst "
        . " where id_pessoa in (" . @$_POST['id_pessoa'] . ") "
        . " and ta.situacao like 'Frequente' ";
$query = $model->db->query($sql);
$array = $query->fetchAll();
foreach ($array as $v) {
    @$a[$v['n_inst'] . '<br />' . $v['logradouro'] . ', ' . $v['num']][] = $v;
}
$form['fields'] = [
    'RSE/ID' => 'id_pessoa',
    'Classe'=>'cod',
    'Aluno' => 'n_pessoa',
    'Mãe' => 'mae',
    'Complemento' => 'complemento'
];
?>
<div style="width: 90%">
    <?php
    foreach ($a as $k => $v) {
        $form['titulo'] = $k;
        $form['array'] = $v;
        tool::relatSimples($form);
        ?>
        <br /><br />
        <?php
    }
    ?>
</div>