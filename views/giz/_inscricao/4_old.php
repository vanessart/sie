<?php
$sql = "SELECT "
        . " po.rm, p.n_pessoa "
        . " FROM ge_prof_esc po "
        . " JOIN ge_funcionario f on f.rm = po.rm "
        . " JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa "
        . " WHERE po.fk_id_inst = $id_inst "
        . " and p.id_pessoa != ". tool::id_pessoa();
$query = $model->db->query($sql);
$p = $query->fetchAll();
foreach ($p as $v) {
    $profs[$v['rm']] = $v['n_pessoa'];
}
?>
<br /><br /><br />
<div style="text-align: center; font-size: 18px">
    Se você quiser pode incluir coautores:
</div>
<br /><br />
<form method="POST">
    <div class="row">
        <div class="col-sm-10">
            <?php echo formulario::select('1[rm]', $profs, 'Coautor', @$_POST['rm'], NULL, NULL, ' required ') ?>
        </div>
        <div class="col-sm-2">          
            <input type="hidden" name="activeNav" value="4" />
            <input type="hidden" name="1[fk_id_prof]" value="<?php echo @$proj['id_prof'] ?>" />
            <input type="hidden" name="1[id_co]" value="<?php echo @$_POST['id_co'] ?>" />
            <?php echo formulario::hidden($hidden) ?>
            <?php echo DB::hiddenKey('giz_coautor', 'replace') ?>
            <input class="btn btn-success" type="submit" value="Incluir" /> 
        </div>
    </div>
</form>
<br /><br />
<?php
$coautores = sql::get('giz_coautor', '*', ['fk_id_prof' => @$proj['id_prof']]);
$sqlkey = DB::sqlKey('giz_coautor', 'delete');
foreach ($coautores as $k => $v) {
    $coautores[$k]['nome'] = $profs[$v['rm']];
    $hidden['1[id_co]']=$v['id_co'];
    $hidden['activeNav']=4;
    $coautores[$k]['del'] = formulario::submit('Excluir', $sqlkey, $hidden);
}
$form['array'] = $coautores;
$form['fields'] = [
    'RM' => 'rm',
    'Nome' => 'nome',
    '||' => 'del'
];
tool::relatSimples($form);
?>