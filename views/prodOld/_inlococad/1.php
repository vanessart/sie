<?php
$eixos = sql::get('prod1_eixo', '*', ['fk_id_pa' => $id_pa, '>' => 'ordem_eixo']);
?>
<br /><br /><br />
<form method="POST">
    <div class="row">
        <div class="col-sm-1">
        </div>
        <div class="col-sm-4">
            <?php
            echo form::hidden([
                'novo' => 1,
                'fk_id_pa' => $id_pa
            ]);
            echo form::hidden($hidden);
            echo form::button('Novo Eixo');
            ?> 
        </div>
    </div>
</form>
<br /><br />
<?php
if (!empty($eixos)) {
    $token = DB::sqlKey('prod1_eixo', 'delete');
    foreach ($eixos as $k => $v) {
        $v['fk_id_pa'] = $id_pa;
        $v['novo'] = 1;
        $eixos[$k]['ac'] = form::submit('Acessar', NULL, $v);
        $eixos[$k]['del'] = form::submit('Apagar', $token, ['1[id_eixo]' => $v['id_eixo'], 'fk_id_pa' => $id_pa]);
    }
    $form['array'] = $eixos;
    $form['fields'] = [
        'Ordem' => 'ordem_eixo',
        'Eixo' => 'n_eixo',
        '||2' => 'del',
        '||1' => 'ac',
    ];

    tool::relatSimples($form);
}
if (!empty($_POST['novo'])) {
    tool::modalInicio();
    ?>
    <div class="fieldTop">
        Cadastro de Eixo (<?php echo $aval[$id_pa] ?>)
    </div>
    <br /><br />
    <form method="POST">
        <div class="row">
            <div class="col-sm-3">
                <?php echo form::selectNum('1[ordem_eixo]', [1, 50], 'Ordem', @$dados['ordem_eixo']) ?>
            </div>
            <div class="col-sm-9">
                <?php echo form::input('1[n_eixo]', 'Eixo', @$dados['n_eixo']) ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-12 text-center">
                <?php
                echo DB::hiddenKey('prod1_eixo', 'replace');
                echo form::hidden([
                    '1[id_eixo]' => @$dados['id_eixo'],
                    '1[fk_id_pa]' => $id_pa,
                    'fk_id_pa' => $id_pa,
                ]);
                echo form::button('Salvar')
                ?>
            </div>
        </div>
    </form>
    <br /><br />
    <?php
    tool::modalFim();
}
