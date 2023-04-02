<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$prof = filter_input(INPUT_POST, 'prof', FILTER_SANITIZE_NUMBER_INT);
if ($id_pessoa == 1) {
    ?>
    <div class="alert alert-danger">
        Do Marco não pode );
    </div>
    <?php
    exit();
}
$senhaProf = filter_input(INPUT_POST, 'senhaProf', FILTER_UNSAFE_RAW);
if ($id_pessoa) {
    $p = sql::get(['pessoa', 'users'], 'n_pessoa, sexo, id_user, emailgoogle ', ['id_pessoa' => $id_pessoa], 'fetch', 'left');
    ?>
    <div class="body">
        <?php
        if (empty($senhaProf)) {
            $senhaProf = user::gerarSenha();
            ?>
            <br /><br />
            <div style="text-align: center; font-size: 18px">
                <div style="text-align: center;">
                    Esta ação altera apenas a senha do SIEB
                    <br /><br />    
                    Confirma redefinir a senha d<?php echo toolErp::sexoArt($p['sexo']) ?> 
                </div>
                <br />
                <div style="text-align: center; font-size: 18px; font-weight: bold">
                    <?php echo $p['n_pessoa'] ?>? 
                </div>
                <br /><br />    
                <form  method="POST">

                    <div  style="text-align: center">
                        <?=
                        formErp::hidden([
                            'senhaProf' => $senhaProf,
                            'id_pessoa' => $id_pessoa,
                            '1[user_password]' => $senhaProf,
                            '1[id_user]' => @$p['id_user'],
                            '1[ativo]' => 1,
                            'prof' => $prof
                        ])
                        . formErp::hiddenToken('users', 'ireplace', null, ['fk_id_pessoa' => $id_pessoa])
                        . formErp::button('Redefinir Senha')
                        ?>
                    </div>
                </form>
            </div>
            <?php
        } else {
            if ($prof) {
                $ac['fk_id_pessoa'] = $id_pessoa;
                $ac['fk_id_gr'] = 18;
                $ac['fk_id_inst'] = 13;
                try {
                    $model->db->insert('acesso_pessoa', $ac, 1);
                } catch (Exception $exc) {
                    echo $exc->getTraceAsString();
                }
            }
            //   $email = mailer::emailSenha($id_pessoa, $senhaProf);
            ?>
            <div style="text-align: center; font-size: 18px">
                Senha d<?php echo toolErp::sexoArt($p['sexo']) ?> <?php echo $p['n_pessoa'] ?>:
            </div>
            <br /><br />
            <div style="text-align: center; font-size: 28px; font-weight: bold">
                <?php echo $senhaProf ?>
            </div>
            <br /><br />
            <?php
            if (!empty($email)) {
                ?>
                <div style="text-align: center; font-size: 28px; font-weight: bold">
                    A senha também foi enviada para o e-mail <?= $p['emailgoogle'] ?>
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
        }
    }
    ?>
</div>
