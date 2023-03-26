<?php
$id_inst = tool::id_inst(@$_POST['id_inst']);

if (!empty($_POST['redefineSenha'])) {
    tool::modalInicio();
    $senhafun = user::gerarSenha();
    ?>
    <br /><br />
    <div style="text-align: center; font-size: 18px">
        <div style="text-align: center;">
            Confirma redefinir a senha d<?php echo tool::sexoArt(@$_POST['sexo']) ?> funcionári<?php echo tool::sexoArt(@$_POST['sexo']) == 'o' ? '' : 'a' ?> 
        </div>
        <br />
        <div style="text-align: center; font-size: 18px; font-weight: bold">
            <?php echo @$_POST['n_pessoa'] ?>? 
        </div>
        <br /><br />    
        <form  method="POST">
            <input type="hidden" name="senhafun" value="<?php echo $senhafun ?>" />
            <input type="hidden" name="prof" value="<?php echo @$_POST['n_pessoa'] ?>" />
            <input type="hidden" name="sexo" value="<?php echo tool::sexoArt(@$_POST['sexo']) ?>" />
            <div  style="text-align: center">
                <?php echo formulario::hidden(['a[user_password]' => $senhafun, "a[fk_id_pessoa]" => $_POST['id_pessoa'], 'a[id_user]' => empty($_POST['id_user']) ? 'X' : $_POST['id_user'], 'a[ativo]' => 1]); ?>
                <?php
                echo $model->db->hiddenKey('users', 'replace');
                echo formulario::button('Redefinir Senha')
                ?>
            </div>
        </form>
    </div>
    <?php
    tool::modalFim();
} elseif (!empty($_POST['senhafun'])) {
    tool::modalInicio();
    ?>
    <div style="text-align: center; font-size: 18px">
        Senha d<?php echo @$_POST['sexo'] ?> professor<?php echo @$_POST['sexo'] == 'o' ? '' : 'a' ?> <?php echo @$_POST['prof'] ?>:
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
    tool::modalFim();
}
?>
<div class="fieldBody">
    <br /><br />
    <input class="btn btn-success" type="submit" onclick=" $('#cad').modal('show');" value="Novo Cadastro" />
    <br /><br />
    <?php
    tool::modalInicio('width: 95%', 1, 'cad');
    ?>
    <iframe style="width: 100%; height: 50vh; border: none" src="<?php echo HOME_URI ?>/gt/caduser"></iframe>
        <?php
        tool::modalFim();

        $fun = funcionarios::funcInstancia($id_inst);
        $acesso = sql::get('acesso_pessoa', 'fk_id_pessoa', ['fk_id_inst' => $id_inst, 'fk_id_gr' => $id_gr]);
        $idAcesso = array();
        foreach ($acesso as $v) {
            $idAcesso[] = $v['fk_id_pessoa'];
        }
        foreach ($fun as $k => $v) {
            $v['redefineSenha'] = 1;
            $fun[$k]['senha'] = formulario::submit('Redefinir Senha', Null, $v, NULL, NULL, NULL, ' btn btn-success');
            if (@in_array($v['id_pessoa'], $idAcesso)) {
                $fun[$k]['acesso'] = formulario::submit('Excluir Acesso', NULL, ['id_pessoa' => $v['id_pessoa'], 'id_inst' => $id_inst, 'fk_id_gr' => $id_gr,'escluirUser' => 1], NULL, NULL, NULL, ' btn btn-danger');
            } else {
                $fun[$k]['acesso'] = formulario::submit('Acesso como Secretario', NULL, ['id_pessoa' => $v['id_pessoa'], 'id_inst' => $id_inst, 'fk_id_gr' => $id_gr, 'grupoInsert' => 1], NULL, NULL, NULL, 'btn btn-warning');
            }
            $fun[$k]['del'] = formulario::submit('Excluir Funcionário', NULL, ['fk_id_inst' => $id_inst, 'id_pessoa' => $v['id_pessoa'],'fk_id_gr' => $id_gr, 'excluirFunc' => 1]);
        }
        $form['array'] = $fun;
        $form['fields'] = [
            'Nome' => 'n_pessoa',
            'CPF' => 'cpf',
            'Função' => 'funcao',
            '||d' => 'del',
            '||h' => 'acesso',
            '||2' => 'senha'
        ];

        tool::relatSimples($form);
        ?>
</div>