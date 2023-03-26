<?php

if (DB::sqlKeyVerif('senha')) {
    
    tool::modalInicio();
    $senhaCadam = user::gerarSenha();
    ?>
    <br /><br />
    <div style="text-align: center; font-size: 18px">
        <div style="text-align: center;">
            Confirma redefinir a senha d<?php echo tool::sexoArt(@$_POST['sexo']) ?> professor<?php echo tool::sexoArt(@$_POST['sexo']) == 'o' ? '' : 'a' ?> 
        </div>
        <br />
        <div style="text-align: center; font-size: 18px; font-weight: bold">
            <?php echo @$_POST['n_pessoa'] ?>? 
        </div>
        <br /><br />    
        <form  method="POST">
            <input type="hidden" name="senhaCadam" value="<?php echo $senhaCadam ?>" />
            <div  style="text-align: center">
                <?php echo formulario::hidden(['a[user_password]' => $senhaCadam, "a[fk_id_pessoa]" => $_POST['id_pessoa'], 'a[id_user]' => empty($_POST['id_user'])?'X':$_POST['id_user'], 'a[ativo]' => 1]); ?>
                <?php
                echo $model->db->hiddenKey('users', 'replace');
                echo formulario::button('Redefinir Senha')
                ?>
            </div>
        </form>
    </div>
    <?php
    tool::modalFim();
} elseif (!empty($_POST['senhaCadam'])) {
    tool::modalInicio();
    ?>
    <div style="text-align: center; font-size: 18px">
        Senha do(a) professor(a) <?php echo @$_POST['prof'] ?>:
    </div>
    <br /><br />
    <div style="text-align: center; font-size: 28px; font-weight: bold">
        <?php echo $_POST['senhaCadam'] ?>
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
<style>
    .bbtt{
        width: 500px;
    }
</style>
<div class="fieldBody">
    <div class="fieldTop">
        Senhas
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-12">
            <?php
            $cadam = $model->cadampesEscola();
            $sqlkey = DB::sqlKey('senha');
            foreach ($cadam as $k => $v) {
                $cadam[$k]['ac'] = formulario::submit('Redefinir Senha ('.$v['n_pessoa'].')', $sqlkey, $v, NULL, NULL, NULL, ' btn btn-success bbtt');
            }
            $form['array'] = $cadam;
            $form['fields'] = [
                'Cadastro' => 'cadpmb',
                'Nome' => 'n_pessoa',
                '||' => 'ac'
            ];

            tool::relatSimples($form);
            ?>
        </div> 
    </div>
</div>