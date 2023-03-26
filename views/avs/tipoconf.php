<?php
$id_tipoconf = filter_input(INPUT_POST, 'id_tipoconf', FILTER_SANITIZE_NUMBER_INT);
$confirma = sql::get('avs_tipoconf');
$sqlkey = DB::sqlKey('avs_tipoconf', 'delete');
foreach ($confirma as $k => $v) {
    $confirma[$k]['at'] = tool::simnao($v['ativo']);
    $confirma[$k]['acesso'] = formulario::submit('Editar', NULL, $v);
    $confirma[$k]['del'] = formulario::submit('Apagar', $sqlkey, ['1[id_tipoconf]'=>$v['id_tipoconf']]);
}

$form['array'] = $confirma;
$form['fields'] = [
    'ID' => 'id_tipoconf',
    'Nome' => 'n_tipoconf',
    'Descrição' => 'desc_tipoconf',
    'Ativo' => 'at',
    '||2' => 'del',
    '||1' => 'acesso'
];
?>
<div class="fieldBody">
    <form method="POST">
        <div class="row">
            <div class="col-sm-9">
                <?php echo formulario::input('1[n_tipoconf]', 'Tipo de Confirmação') ?>
            </div>
            <div class="col-sm-3">
                <?php echo formulario::checkbox('1[ativo]', 1, 'Ativo', @$_POST['ativo']) ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-8">
                <?php echo formulario::input('1[desc_tipoconf]', 'Descrição') ?>
            </div>
            <div class="col-sm-2 text-center">
                <?php
                echo DB::hiddenKey('avs_tipoconf', 'replace');
                ?>
                <input type="hidden" name="1[id_tipoconf]" value="<?php echo $id_tipoconf ?>" />
                <input class="btn btn-success" type="submit" value="Salvar" />
            </div>
               <div class="col-sm-2 text-center">
                   <a class="btn btn-default" href="">Limpar</a>
               </div>
        </div>
    </form>
    <br /><br />
    <?php
    tool::relatSimples($form);
    ?>
</div>
