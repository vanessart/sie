<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = toolErp::id_inst();
?>
<div class="body">
    <br />
    <form action="<?php echo HOME_URI ?>/sed/def/cadUser.php" target="frame" method="POST">
        <button onclick="$('#myModal').modal('show');$('.form-class').val('');" type="submit" class="btn btn-info">
            Novo Cadastro
        </button>
    </form>

    <?php
    $fun = funcionarios::funcInstancia($id_inst);
    $acesso = sql::get('acesso_pessoa', 'fk_id_pessoa', ['fk_id_inst' => $id_inst, 'fk_id_gr' => $id_gr]);
    $idAcesso = array();
    foreach ($acesso as $v) {
        $idAcesso[] = $v['fk_id_pessoa'];
    }
    foreach ($fun as $k => $v) {
        $v['redefineSenha'] = 1;
        $fun[$k]['senha'] = '<button class="btn btn-success" style="white-space: nowrap;" onclick="senha(' . $v['id_pessoa'] . ')">Redefinir Senha</button>';
        if (@in_array($v['id_pessoa'], $idAcesso)) {
            $fun[$k]['acesso'] = formErp::submit('Excluir Acesso', NULL, ['id_pessoa' => $v['id_pessoa'], 'id_inst' => $id_inst, 'fk_id_gr' => $id_gr, 'escluirUser' => 1], NULL, NULL, NULL, ' btn btn-danger');
        } else {

            $fun[$k]['acesso'] = formErp::submit('Acesso como Secretario', NULL, ['id_pessoa' => $v['id_pessoa'], 'id_inst' => $id_inst, 'fk_id_gr' => $id_gr, 'grupoInsert' => 1], NULL, NULL, NULL, 'btn btn-warning');
        }
        if (!is_numeric($v['rm'])) {
            $fun[$k]['del'] = formErp::submit('Excluir Funcionário', null, ['fk_id_inst' => $id_inst, 'id_pessoa' => $v['id_pessoa'], 'fk_id_gr' => $id_gr, 'excluirFunc' => 1]);
        }
    }
    $form['array'] = $fun;
    $form['fields'] = [
        'Nome' => 'n_pessoa',
        'CPF' => 'cpf',
        'Função' => 'funcao',
        'RM' => 'rm',
        '||d' => 'del',
        '||h' => 'acesso',
        '||2' => 'senha'
    ];

    report::simple($form);
    ?>
</div>
<form action="<?= HOME_URI ?>/sed/def/formSenha.php" id="formSenha" target="frame" method="POST">
    <input type="hidden" name="id_pessoa" id="id_pessoa_senha" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="frame" style="width: 100%; height: 80vh; border: none" ></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function senha(id) {
        if (id) {
            id_pessoa_senha.value = id;
        } else {
            id_pessoa_senha.value = "";
        }
        formSenha.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>

