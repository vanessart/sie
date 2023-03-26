<?php
$id_setor = filter_input(INPUT_POST, 'id_setor', FILTER_SANITIZE_NUMBER_INT);
$pessoa = @$_POST['pessoa'];
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_pessoa)) {
    $model->db->insert('avs_setor_pessoa', ['fk_id_setor' => $id_setor, 'fk_id_pessoa' => $id_pessoa]);
    tool::alert('Salvo com Sucesso!');
    $_POST['buscar'] = NULL;
    $_POST['pessoa'] = NULL;
    $pessoa = NULL;
}
?>
<div class="fieldBody">
    <div class="row">
        <div class="col-sm-6">
            <?php echo formulario::selectDB('setor', 'id_setor', 'Setor', $id_setor, NULL, 1) ?>
        </div>
    </div>
    <br /><br />
    <?php
    if (!empty($id_setor)) {
        ?>
        <input class="btn btn-info" type="submit" onclick=" $('#myModal').modal('show');" value="Incluir" />
        <?php
        $ativado = empty($pessoa) ? 1 : 0;
        tool::modalInicio('width: 95%', $ativado);
        ?>
        <form method = "POST">
            <div class="row">
                <div class="col-sm-6">
                    <?php echo formulario::input('pessoa', 'Nome, CPF', NULL, $pessoa) ?>
                </div>
                <div class="col-sm-6">
                    <input type="hidden" name="id_setor" value="<?php echo $id_setor ?>" />
                    <input name="buscar" class="btn btn-info" type="submit" value="Buscar" />
                </div>
            </div>
        </form>
        <br /><br />
        <?php
        if (!empty($_POST['buscar'])) {
            pessoa::buscar($pessoa, ['id_setor' => $id_setor, 'pessoa' => $pessoa, 'buscar' => 1], 'Incluir');
        }
    }
    tool::modalFim();
    ?>
    <br /><br />
    <?php
    $pessoaSetor = sql::get(['avs_setor_pessoa', 'pessoa'], 'id_setorpessoa, fk_id_pessoa, fk_id_setor, n_pessoa, cpf', ['fk_id_setor' => $id_setor]);
    $sqlkey = DB::sqlKey('avs_setor_pessoa', 'delete');
    foreach ($pessoaSetor as $k => $v) {
        $pessoaSetor[$k]['d'] = formulario::submit('Excluir', $sqlkey, ['1[id_setorpessoa]'=>$v['id_setorpessoa'],'id_setor'=>$id_setor]);
    }

    $form['array'] = $pessoaSetor;
    $form['fields'] = [
        'Nome' => 'n_pessoa',
        'CPF' => 'cpf',
        "||"=>'d'
    ];

    tool::relatSimples($form);
    ?>

</div>

