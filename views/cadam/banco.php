<div class="fieldBody">
    <div class="fieldTop">
        Cadastro de Bancos
    </div>
    <br /><br />
    <input type="submit" onclick=" $('#myModal').modal('show');" value="Novo Cadastro" />
    <br /><br />
    <?php
    if(empty($_POST['novo'])){
        $modal = 1;
    }
    tool::modalInicio('width: 80%', @$modal);
    ?>
    <br /><br />
    <form method="POST">
        <div class="row">
            <div class="col-md-8">
                <?php formulario::input('1[n_ban]', 'Banco') ?>
            </div>
            <div class="col-md-4">
                <?php formulario::input('1[codigo]', 'Código') ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-6 text-center">
                <input onclick="document.getElementById('novo').submit();" class="btn btn-warning" type="button" value="Limpar" />
            </div>
            <div class="col-md-6 text-center">
                <input type="hidden" name="1[id_ban]" value="<?php echo @$_POST['id_ban'] ?>" />
                <?php echo DB::hiddenKey('banco', 'replace') ?>
                <input class="btn btn-success" type="submit" value="Salvar" />
            </div>
        </div>
    </form>
    <br /><br />
    <?php
    tool::modalFim()
    ?>
    <form id="novo" method="POST">
        <input type="hidden" name="novo" value="1" />
    </form>
    <br /><br />
    <?php
    $bancos = sql::get('banco');
    $sqlkey = DB::sqlKey('banco', 'delete');
    foreach ($bancos as $k => $v) {
        $v['novo'] = 1;
        $bancos[$k]['ac'] = formulario::submit('Editar', NULL, $v);
        $bancos[$k]['del'] = formulario::submit('Apagar', $sqlkey, ['1[id_ban]'=>$v['id_ban']]);
    }
    $form['array'] = $bancos;
    $form['fields'] = [
        'Banco' => 'n_ban',
        'Código' => 'codigo',
        '||1' => 'del',
        '||2' => 'ac'
    ];
    tool::relatSimples($form);
    ?>
</div>