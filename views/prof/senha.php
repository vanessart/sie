<?php
if (!empty($_POST['emailSet'])) {
    tool::modalInicio();
    $email = filter_input(INPUT_POST, 'email', FILTER_UNSAFE_RAW);
     $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
    ?>
    <div style="text-align: center;">
        Redefinir o E-mail d<?php echo tool::sexoArt(@$_POST['sexo']) ?> professor<?php echo tool::sexoArt(@$_POST['sexo']) == 'o' ? '' : 'a' ?> 
    </div>
    <br />
    <div style="text-align: center; font-size: 18px; font-weight: bold">
        <?php echo @$_POST['n_pessoa'] ?>? 
    </div>
    <br /><br /> 
    <form method="POST">
        <div class="row" style="width: 800px; margin: 0 auto">
            <div class="col-sm-9">
                <?= form::input('1[emailgoogle]', 'E-mail', $email)
                ?>
            </div>
            <div class="col-sm-1">
                <?php
                echo formulario::hidden(['1[id_pessoa]' => $id_pessoa]);
                echo DB::hiddenKey('pessoa', 'replace');
                ?>
                <?=
                form::button('Alterar')
                ?>
            </div>
        </div>
        <br />

    </form>
    <?php
    tool::modalFim();
} elseif (!empty($_POST['id_pessoa'])) {
    tool::modalInicio();
    $senhaProf = user::gerarSenha();
    ?>
    <br /><br />
    <div style="text-align: center; font-size: 18px">
        <div style="text-align: center;">
            Esta ação altera apenas a senha do SIEB
            <br /><br />    
            Confirma redefinir a senha d<?php echo tool::sexoArt(@$_POST['sexo']) ?> professor<?php echo tool::sexoArt(@$_POST['sexo']) == 'o' ? '' : 'a' ?> 
        </div>
        <br />
        <div style="text-align: center; font-size: 18px; font-weight: bold">
            <?php echo @$_POST['n_pessoa'] ?>? 
        </div>
        <br /><br />    
        <form  method="POST">
            <input type="hidden" name="senhaProf" value="<?php echo $senhaProf ?>" />
            <input type="hidden" name="id" value="<?php echo @$_POST['id_pessoa'] ?>" />
            <input type="hidden" name="prof" value="<?php echo @$_POST['n_pessoa'] ?>" />
            <input type="hidden" name="sexo" value="<?php echo tool::sexoArt(@$_POST['sexo']) ?>" />
            <div  style="text-align: center">
                <?php echo formulario::hidden(['a[user_password]' => $senhaProf, "a[fk_id_pessoa]" => $_POST['id_pessoa'], 'a[id_user]' => empty($_POST['id_user']) ? 'X' : $_POST['id_user'], 'a[ativo]' => 1]); ?>
                <?php
                echo $model->db->hiddenKey('users', 'replace');
                echo formulario::button('Redefinir Senha')
                ?>
            </div>
        </form>
    </div>
    <?php
    tool::modalFim();
} elseif (!empty($_POST['senhaProf'])) {
    $email = mailer::emailSenha($_POST['id'], $_POST['senhaProf']);
    tool::modalInicio();
    ?>
    <div style="text-align: center; font-size: 18px">
        Senha d<?php echo @$_POST['sexo'] ?> professor<?php echo @$_POST['sexo'] == 'o' ? '' : 'a' ?> <?php echo @$_POST['prof'] ?>:
    </div>
    <br /><br />
    <div style="text-align: center; font-size: 28px; font-weight: bold">
        <?php echo $_POST['senhaProf'] ?>
    </div>
    <br /><br />
    <?php
    if (!empty($email)) {
        ?>
        <div style="text-align: center; font-size: 28px; font-weight: bold">
            A senha também foi enviada para o e-mail <?= $email ?>
        </div>
        <br /><br />
        <?php
    }
    ?>
    <div style="text-align: center; font-size: 18px">
        Anote a senha
        <br /><br />
        Após fechar esta janela, não será possível recuperá-la.
    </div>

    <?php
    tool::modalFim();
}
?>
<style>
    .bbtt{
        width: 350px;
    }
</style>
<div class="fieldBody">
    <div class="fieldTop">
        Redefinir Senhas dos Professores
    </div>
    <br /><br />
    <div class="row">
        <div class="col-md-12">
            <?php
            $sql = "select "
                    . "distinct p.n_pessoa, p.id_pessoa, p.sexo, p.emailgoogle as email, pe.rm, pe.id_pe, u.id_user "
                    . "from ge_prof_esc pe "
                    . " join ge_funcionario f on f.rm = pe.rm "
                    . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                    . " left join users u on u.fk_id_pessoa = p.id_pessoa "
                    . " where pe.fk_id_inst = " . tool::id_inst()
                    . " order by n_pe ";
            $query = $model->db->query($sql);
            $pf = $query->fetchAll();
            foreach ($pf as $k => $v) {
                $pf[$k]['senha'] = formulario::submit('Redefinir Senha do SIEB ', Null, $v, NULL, NULL, NULL, ' btn btn-success');
                $v['emailSet'] = 1;
                $pf[$k]['emailSet'] = formulario::submit('Redefinir E-mail', Null, $v, NULL, NULL, NULL, ' btn btn-primary');
            }
            $form['array'] = $pf;
            $form['fields'] = [
                'Professor' => 'n_pessoa',
                'Matrícula' => 'rm',
                'E-mail' => 'email',
                '||1' => 'emailSet',
                '||2' => 'senha'
            ];
            tool::relatSimples($form);
            ?>
        </div>
    </div>
</div>
