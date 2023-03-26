<?php
$id_setor = filter_input(INPUT_POST, 'id_setor', FILTER_SANITIZE_NUMBER_INT);
$setor = sql::get('setor', '*', ['>' => 'n_setor']);
$sqlkey = DB::sqlKey('setor', 'delete');
foreach ($setor as $k => $v) {
    $setor[$k]['at'] = tool::simnao($v['ativo']);
    $setor[$k]['ac'] = formulario::submit('Editar', NULL, $v);
    $setor[$k]['del'] = formulario::submit('Apagar', $sqlkey, ['1[id_setor]' => $v['id_setor']]);
}
$form['array'] = $setor;
$form['fields'] = [
    'ID' => 'id_setor',
    'Nome' => 'n_setor',
    'Ativo' => 'at',
    '||1' => 'del',
    '||2' => 'ac'
];
?>
<div class="fieldBody">
    <form method="POST">
        <div class="row">
            <div class="col-sm-7">
                <?php echo formulario::input('1[n_setor]', 'Setor') ?> 
            </div>
            <div class="col-sm-3">
                <?php echo formulario::checkbox('1[ativo]', 1, 'Ativo', @$_POST['ativo']) ?>
            </div>
            <div class="col-sm-2">
                <?php
                echo DB::hiddenKey('setor', 'replace');
                ?>
                <input type="hidden" name="1[id_setor]" value="<?php echo $id_setor ?>" />
                <button type="submit" class="btn btn-success">
                    Salvar
                </button>
            </div>
        </div>
    </form>
    <br /><br />
    <?php
    tool::relatSimples($form);
    ?>
</div>
