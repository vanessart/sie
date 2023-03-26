<?php
$empresas = transporte::empresas();
foreach ($empresas as $k => $v) {
    $v['novo'] = 1;
    $empresas[$k]['atv'] = tool::simnao($v['ativo']);
    $empresas[$k]['ac'] = form::submit('Acessar', NULL, $v);
}
$id_em = filter_input(INPUT_POST, 'id_em', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_em)) {
    $dados = transporte::getEmpresa($id_em);
}
?>
<div class="fieldBody">
    <div class="fieldTop">
        Cadastro de Empresa
    </div>
    <br /><br />
    <?php echo form::submit('Nova Empresa', NULL, ['novo'=>1], null, null, null, 'btn btn-info') ?>
    <br /><br />
    <?php
    $form['array'] = $empresas;
    $form['fields'] = [
        'ID' => 'id_em',
        'Empresa' => 'n_em',
        'Ativo' => 'atv',
        "||" => 'ac'
    ];
    tool::relatSimples($form);
    if (empty($_POST['novo'])) {
        $desativado = 1;
    }
    tool::modalInicio('width: 95%', @$desativado);
    ?>
    <form method="POST">
        <div class="row">
            <div class="col-sm-4">
                <?php echo form::input('1[n_em]', 'Nome', @$dados['n_em']) ?>
            </div>
            <div class="col-sm-4">
                <?php echo form::select('1[ativo]', [1 => 'Sim', 0 => 'Não'], 'Ativo', @$dados['ativo']) ?>
            </div>
            <div class="col-sm-4">
                <?php
                echo form::hidden(['1[id_em]'=>@$id_em]);
                echo DB::hiddenKey('transp_empresa', 'replace');
                echo form::button('Salvar');
                ?>
            </div>
        </div>
    </form>
    <br /><br />
    <?php
    tool::modalFim();
    ?>
</div>
