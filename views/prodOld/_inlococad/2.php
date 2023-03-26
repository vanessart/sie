<?php
$items = sql::get(['prod1_item','prod1_eixo'], '*', ['prod1_item.fk_id_pa' => $id_pa, '>' => 'ordem_eixo, ordem_item']);
if(!empty($items)){
foreach ($items as $v) {
    for ($c = 1; $c <= 3; $c++) {
        @$valor[$c] += $v['valor' . $c];
    }
}
}
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
                'fk_id_pa' => $id_pa,
                'activeNav' => 2
            ]);
            echo form::hidden($hidden);
            echo form::button('Novo Item');
            ?> 
        </div>
        <div class="col-sm-1">
        </div>
        <div class="col-sm-4">
            <table class="table table-bordered table-striped">
                <tr>
                    <td>
                        1º Avaliação
                    </td>
                    <td>
                        2º Avaliação
                    </td>
                    <td>
                        3º Avaliação
                    </td>
                </tr>
                     <td>
                        <?php echo @$valor[1] ?>
                    </td>
                    <td>
                        <?php echo @$valor[2] ?>
                    </td>
                    <td>
                        <?php echo @$valor[3] ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>
<br /><br />

<?php
if (!empty($items)) {
    $token = DB::sqlKey('prod1_item', 'delete');
    foreach ($items as $k => $v) {
        $v['fk_id_pa'] = $id_pa;
        $v['novo'] = 1;
        $v['activeNav'] = 2;
        $items[$k]['ac'] = form::submit('Acessar', NULL, $v);
        $items[$k]['del'] = form::submit('Apagar', $token, ['1[id_item]' => $v['id_item'], 'fk_id_pa' => $id_pa]);
    }
    $form['array'] = $items;
    $form['fields'] = [
        'Ordem' => 'ordem_item',
        'Item' => 'n_item',
        'Eixo'=>'n_eixo',
        '1º Aval.' => 'valor1',
        '2º Aval.' => 'valor2',
        '3º Aval.' => 'valor3',
        '||2' => 'del',
        '||1' => 'ac',
    ];

    tool::relatSimples($form);
}
if (!empty($_POST['novo'])) {
    tool::modalInicio();
    ?>
    <div class="fieldTop">
        Cadastro de Item (<?php echo $aval[$id_pa] ?>)
    </div>
    <br /><br />
    <form method="POST">
        <div class="row">
            <div class="col-sm-3">
                <?php echo form::selectNum('1[ordem_item]', [1, 50], 'Ordem', @$dados['ordem_item']) ?>
            </div>
            <div class="col-sm-3">
                <?php echo form::input('1[valor1]', 'Valor (1º Aval.)', str_replace('.', ',', @$dados['valor1'])) ?>
            </div>
            <div class="col-sm-3">
                <?php echo form::input('1[valor2]', 'Valor (2º Aval.)', str_replace('.', ',', @$dados['valor2'])) ?>
            </div>
            <div class="col-sm-3">
                <?php echo form::input('1[valor3]', 'Valor (3º Aval.)', str_replace('.', ',', @$dados['valor3'])) ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-12">
                <?php echo form::selectDB('prod1_eixo', '1[fk_id_eixo]', 'Eixo', @$dados['fk_id_eixo'], NULL, NULL, NULL, ['fk_id_pa'=>$id_pa]) ?>

            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-12">
                <?php echo form::textarea('1[n_item]', @$dados['n_item'], 'Item') ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-12 text-center">
                <?php
                echo DB::hiddenKey('prod1_item', 'replace');
                echo form::hidden([
                    '1[id_item]' => @$dados['id_item'],
                    '1[fk_id_pa]' => $id_pa,
                    'fk_id_pa' => $id_pa,
                    'activeNav' => 2
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

