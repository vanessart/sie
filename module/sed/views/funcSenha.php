<?php
$id_inst = toolErp::id_inst();
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$sexo = filter_input(INPUT_POST, 'sexo', FILTER_SANITIZE_STRING);
$id_user = filter_input(INPUT_POST, 'id_user', FILTER_SANITIZE_NUMBER_INT);
$prof = filter_input(INPUT_POST, 'prof', FILTER_SANITIZE_STRING);
if (!empty($_POST['redefineSenha'])) {
    toolErp::modalInicio(1);
    $senhafun = user::gerarSenha();
    ?>
    <br /><br />
    <div style="text-align: center; font-size: 18px">
        <div style="text-align: center;">
            Confirma redefinir a senha do(a) funcionário(a) 
        </div>
        <br />
        <div style="text-align: center; font-size: 18px; font-weight: bold">
            <?php echo @$n_pessoa ?>? 
        </div>
        <br /><br />    
        <form  method="POST">
            <input type="hidden" name="senhafun" value="<?php echo $senhafun ?>" />
            <input type="hidden" name="prof" value="<?php echo @$n_pessoa ?>" />
            <input type="hidden" name="sexo" value="<?php echo toolErp::sexoArt(@$sexo) ?>" />
            <div  style="text-align: center">
                <?=
                formErp::hidden(['a[user_password]' => $senhafun, "a[fk_id_pessoa]" => $id_pessoa, 'a[id_user]' => empty($id_user) ? 'X' : $id_user, 'a[ativo]' => 1])
                . formErp::hiddenToken('users', 'ireplace')
                . formErp::button('Redefinir Senha')
                ?>
            </div>
        </form>
    </div>
    <?php
    toolErp::modalFim();
} elseif (!empty($_POST['senhafun'])) {
    toolErp::modalInicio(1);
    ?>
    <div style="text-align: center; font-size: 18px">
        Senha do(a) funcionário(a)  <?php echo @$prof ?>:
    </div>
    <br /><br />
    <div style="text-align: center; font-size: 28px; font-weight: bold">
        <?php echo $_POST['senhafun'] ?>
    </div>
    <br /><br />
    <div style="text-align: center; font-size: 18px">
        Anote a senha
        <br /><br />
        Após fechar esta janela, não será possível recuperá-la.
    </div>

    <?php
    toolErp::modalFim();
}
?>
<div class="fieldBody">
    <br /><br />
    <!--
    <input class="btn btn-success" type="submit" onclick=" $('#cad').modal('show');" value="Novo Cadastro" />
    -->
    <br /><br />
    <?php
    toolErp::modalInicio(null, null, 'cad');
    ?>
    <iframe style="width: 100%; height: 50vh; border: none" src="<?php echo HOME_URI ?>/gt/cadusersecre"></iframe>
        <?php
        toolErp::modalFim();
        $fun = funcionarios::funcInstancia($id_inst);
        $acesso = sql::get('acesso_pessoa', 'fk_id_pessoa', ['fk_id_inst' => $id_inst, 'fk_id_gr' => $id_gr]);
        $idAcesso = array();
        foreach ($acesso as $v) {
            $idAcesso[] = $v['fk_id_pessoa'];
        }
        foreach ($fun as $k => $v) {
            $v['redefineSenha'] = 1;
            $fun[$k]['senha'] = formErp::submit('Redefinir Senha', Null, $v);
            if (in_array(@$v['id_pessoa'], $idAcesso)) {
                $fun[$k]['acesso'] = formErp::submit('Excluir Acesso', NULL, ['id_pessoa' => $v['id_pessoa'], 'id_inst' => $id_inst, 'escluirUser' => 1], NULL, NULL, NULL, ' btn btn-danger');
            } else {
                $fun[$k]['acesso'] = formErp::submit('Acesso como Secretario', NULL, ['id_pessoa' => $v['id_pessoa'], 'id_inst' => $id_inst, 'fk_id_gr' => $id_gr, 'grupoInsert' => 1], NULL, NULL, NULL, 'btn btn-warning');
            }
            $fun[$k]['del'] = formErp::submit('Excluir Funcionário', NULL, ['fk_id_inst' => $id_inst, 'id_pessoa' => $v['id_pessoa'], 'excluirFunc' => 1]);
        }
        $form['array'] = $fun;
        $form['fields'] = [
            'Nome' => 'n_pessoa',
            'CPF' => 'cpf',
            'Função' => 'funcao',
            // '||h' => 'acesso',
            '||2' => 'senha'
        ];

        toolErp::relatSimples($form);
        ?>
</div>