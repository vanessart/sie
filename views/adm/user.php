<?php
@$user = $_POST['user'];
@$aba = $_POST['aba'];
$inst_ = inst::instancias();

foreach ($inst_ as $v){
 $inst[$v['id_inst']]=$v['n_inst'];
}

?>
<div class="fieldTop">
    Gerenciar Usuários
</div>
<div class="field row">
    <form method="POST">
        <div class="col-lg-5">
            <div class="input-group">
                <span class="input-group-addon">
                    CPF, E-mail ou ID:
                </span> 
                <input class="form-control" type="text" name="user" value="<?php echo @$user ?>"  >
                <span class="input-group-addon"  >
                    <button type="submit" class="btn btn-link btn-xs">
                        <span aria-hidden="true">
                            Buscar
                        </span>
                    </button>
                </span>
            </div>
        </div>
    </form>
    <div class="col-lg-2">
        <form method="POST" action="<?php echo HOME_URI ?>/adm/pessoa">
            <button type="submit" class="btn btn-default">
                <span>
                    Buscar por nome
                </span>
            </button>
        </form>        
    </div>
    <div class="col-lg-2">
        <form method="POST" action="<?php echo HOME_URI ?>/adm/pessoa">
            <input type="hidden" name="novo" value="1" />
            <button type="submit" name="novo" value="1" class="btn btn-default">
                Novo Cadastro
            </button>
        </form>
    </div>
    <div class="col-lg-3">
        <form method="POST">
            <button type="submit" class="btn btn-default">
                Limpar
            </button>
        </form>
    </div>
</div>
<?php
if (!empty($user)) {
    $usuario = user::get($user);
}
if (!empty($usuario)) {
    ?>

    <div class="field">
        <ul class="nav nav-tabs">
            <li class="active">
                <form id="d1" method="POST">
                    <input type="hidden" name="user" value="<?php echo $user ?>" />
                </form>
                <a onclick="document.getElementById('d1').submit()" href="#">Dados do Usuário</a>
            </li>
            <li class="active">
                <form id="d2" method="POST">
                    <input type="hidden" name="user" value="<?php echo $user ?>" />
                    <input type="hidden" name="aba" value="2" />
                </form>
                <a onclick="document.getElementById('d2').submit()" href="#">Ativação, Desativação e Senha</a>
            </li>
            <li class="active">
                <form id="d3" method="POST">
                    <input type="hidden" name="user" value="<?php echo $user ?>" />
                    <input type="hidden" name="aba" value="3" />
                </form>
                <a onclick="document.getElementById('d3').submit()" href="#">Restrições</a>
            </li>
            <li class="active">
                <form id="d4" method="POST">
                    <input type="hidden" name="user" value="<?php echo $user ?>" />
                    <input type="hidden" name="aba" value="4" />
                </form>
                <a onclick="document.getElementById('d4').submit()" href="#">Acesso</a>
            </li>
        </ul>
        <div class="row">
            <?php
            if (empty(@$aba)) {
                ?>
                <br />
                <div class="col-lg-12" >
                    <div class="panel panel-default" style="height: 300px">
                        <div class="panel-heading text-center">
                            Dados do Usuário
                        </div>
                        <div class="panel-body">
                            <table class="table table-hover">
                                <tr>
                                    <td style="width: 100px">
                                        Nome:
                                    </td>
                                    <td>
                                        <?php echo $usuario['n_pessoa'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Nome Social:
                                    </td>
                                    <td>
                                        <?php echo $usuario['n_social'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        CPF:
                                    </td>
                                    <td>
                                        <?php echo $usuario['cpf'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        E-mail:
                                    </td>
                                    <td>
                                        <?php echo $usuario['emailgoogle'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Dt. Nasc.:
                                    </td>
                                    <td>
                                        <?php echo data::converteBr($usuario['dt_nasc']) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Situacão:
                                    </td>
                                    <td>
                                        <?php echo $usuario['ativo'] == 1 ? 'Ativado' : 'Desativado' ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <?php
            }
            if (@$aba == 2) {
                ?>
                <br />
                <div class="col-lg-12">
                    <div class="panel panel-default" style="height: 300px">
                        <div class="panel-heading">
                            Ativação, Desativação e Senha
                        </div>
                        <div class="panel-body">
                            <?php
                            if (empty($usuario["user_password"]) || $usuario["ativo"] == 1) {
                                ?>
                                <form id="sll" method="POST">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <?php formulario::input('a[user_password]', !empty($usuario['user_password']) ? 'Redefinir a Senha:' : 'Definir a Senha:', NULL, NULL, ' required id="ps"  ', '') ?>
                                        </div>
                                        <div class="col-lg-2">
                                            <input type="hidden" name="aba" value="2" />
                                            <input type="hidden" name="user" value="<?php echo $user ?>" />
                                            <?php echo formulario::hidden(["a[fk_id_pessoa]" => $usuario['id_pessoa'], 'a[id_user]' => empty($usuario['id_user']) ? 'X' : $usuario['id_user'], 'a[ativo]' => 1]); ?>

                                            <?php
                                            echo $model->db->hiddenKey('users', 'replace');
                                            echo formulario::button(!empty($usuario['user_password']) ? 'Redefinir Senha' : 'Definir Senha e Ativar Usuário')
                                            ?>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-warning" onclick="document.getElementById('ps').value = '<?php echo user::gerarSenha() ?>';document.getElementById('sll').submit()">
                                                Gerar Senha aleatória
                                            </button>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-info" onclick="document.getElementById('sen').value = document.getElementById('ps').value;document.getElementById('envia').submit();">
                                                Enviar senha por e-mail
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="panel panel-danger">
                                                <span>
                                                    &nbsp;&nbsp;A senha tem que conter, no mínimo 8 caracteres e ser composta de letras e números
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <br />
                                <hr>
                                <?php
                            }
                            if (!empty($usuario['user_password'])) {
                                if ($usuario['ativo'] == 1) {
                                    $button = "Desativar Usuário";
                                } else {
                                    $button = "Ativar Usuário";
                                }
                                ?>
                                <form id="re" method="POST">
                                    <input type="hidden" name="aba" value="2" />
                                    <input type="hidden" name="user" value="<?php echo $user ?>" />
                                    <?php echo formulario::hidden(['a[id_user]' => $usuario['id_user'], 'a[ativo]' => 'X']) ?>
                                    <?php
                                    echo $model->db->hiddenKey('users', 'replace');
                                    ?>
                                </form>
                                <form id="envia" method="POST">
                                    <input type="hidden" name="aba" value="2" />
                                    <input type="hidden" name="user" value="<?php echo $user ?>" />
                                    <input type="hidden" id="sen" name="senha" value="" />
                                    <input type="hidden" name="n_pessoa" value="<?php echo $usuario['n_pessoa'] ?>" />
                                    <input type="hidden" name="email" value="<?php echo $usuario['email'] ?>" />
                                </form>
                                <div class="text-center">
                                    <button type="button" class="btn btn-default" onclick="if (confirm('<?php echo $button ?>?')) {
                                                document.getElementById('re').submit()
                                            }">
                                                <?php echo $button ?>
                                    </button>
                                </div>
                                <?php
                            }
                            ?>


                        </div>
                    </div>
                </div>
                <?php
            }
            if (@$aba == 3) {
                ?>
                <br />
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading text-center">
                            Restrições
                        </div>
                        <div class="panel-body" style="min-height: 300px;">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel panel-danger text-center">
                                        <span>
                                            &nbsp;&nbsp;Só utilize esta aba se houver restrições ou exceções específica à este usuário
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <form method="POST">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php echo formulario::input('expira', 'O acesso deste usuário ao sistema expira em:', NULL, @$usuario['expira'] != '0000-00-00' ? data::converteBr(@$usuario['expira']) : '  ', formulario::dataConf()) ?>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        Horas em que é permitido o acesso deste usuário ao sistema
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <?php
                                            $horas = unserialize($usuario['horas']);
                                            for ($c = 0; $c < 24; $c++) {
                                                @$checked = (in_array($c, $horas) ? 'checked' : '')
                                                ?>
                                                <div class="col-lg-2">
                                                    <label  style="width: 100%">
                                                        <span class="input-group-addon <?php echo $class ?>" style="text-align: left; width: 20px">
                                                            <input <?php echo @$checked ?> type="checkbox" aria-label="..." name="horas[]" value="<?php echo $c ?>">
                                                        </span>
                                                        <span class="input-group-addon <?php echo $class ?>" style="text-align: left">
                                                            <?php echo $c ?>h00
                                                        </span>
                                                    </label>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div class="text-center">
                                    <input type="hidden" name="aba" value="3" />
                                    <input type="hidden" name="user" value="<?php echo $user ?>" />
                                    <input type="hidden" name="id_pessoa" value="<?php echo $usuario['id_pessoa'] ?>" />
                                    <?php
                                    echo $this->db->hiddenKey('pessoaPermissao');
                                    formulario::button('Salvar')
                                    ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
            }
            if (@$aba == 4) {
                ?>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Acesso
                        </div>
                        <div class="panel-body" style="min-height: 300px;overflow: auto">
                            <div class="row">
                                <div class="col-lg-4">
                                    <form method="POST">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <?php
                                                formulario::selectDB('grupo', '1[fk_id_gr]', 'Grupo', 'null', ' style="width: 100%" required', NULL, NULL, NULL, 'where at_gr = 1 order by n_gr');
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-10">
                                                <?= formErp::select('1[fk_id_inst]', $inst, 'Instância&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <input type="hidden" name="aba" value="4" />
                                                <input type="hidden" name="user" value="<?php echo $user ?>" />
                                                <input type="hidden" name="1[fk_id_pessoa]" value="<?php echo $usuario['id_pessoa'] ?>" />
                                                <?php
                                                echo DB::hiddenKey('acesso_pessoa', 'replace');
                                                formulario::button('Adicionar')
                                                ?>  
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-8">
                                    <?php
                                    $permissao = user::permissao($usuario['id_pessoa']);
                                    ?>
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr class="art-button">
                                                <th>Grupo</th>
                                                <th>Instância</th>
                                                <th style="width: 100px"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($permissao as $v) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $v['n_gr'] ?></td>
                                                    <td><?php echo $v['n_inst'] ?></td>
                                                    <td>
                                                        <form id="<?php echo $v['id_ac'] ?>" method="POST">
                                                            <?php echo DB::hiddenKey('acesso_pessoa', 'delete') ?>
                                                            <input type="hidden" name="1[id_ac]" value="<?php echo $v['id_ac'] ?>" />
                                                            <input type="hidden" name="aba" value="4" />
                                                            <input type="hidden" name="user" value="<?php echo $user ?>" />
                                                        </form>
                                                        <button type="button" onclick="if (confirm('Apagar \'<?php echo $v['n_gr'] ?>/<?php echo str_replace(['"', "'"], ['',''], $v['n_inst']) ?>\' ?')) {
                                                                    document.getElementById('<?php echo $v['id_ac'] ?>').submit()
                                                                }" class="btn btn-default">
                                                            Apagar
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        <?php
    }
} else {
    ?>
    <br /><br />
    <div class="field" style="text-align: center; font-size: 20px">
        <?php echo 'Usuário não Encontrado'; ?>
    </div>
    <?php
}
?>
