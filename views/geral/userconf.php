<?php
$usuario = $model->dadosUser();
?>
<br /><br />
<div class="panel panel-default" style="height: 500px">
    <div class="panel-heading text-center">
        Dados do Usuário
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
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
                            <?php echo $usuario['email'] ?>
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
                            Telefone(s):
                        </td>
                        <td>
                            <?php
                            echo $usuario['tel1'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $usuario['tel2'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $usuario['tel3'];
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <form method="POST">
                            <input type="hidden" name="aba" value="dados" />
                            <button style="width: 100%" class="btn btn-info">
                                Alterar Dados Pessoais
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form method="POST">
                            <input type="hidden" name="aba" value="senha" />
                            <button style="width: 100%" class="btn btn-success">
                                Alterar Senha
                            </button>
                        </form>
                    </div>
                </div>
                <br /><br />
                <div class="row">
                    <?php
                    if (@$_POST['aba'] == "senha") {
                        ?>
                        <div class="panel panel-default">

                            <div class="panel-heading text-center">
                                Alterar Senha
                            </div>
                            <div id="2" class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="panel panel-danger text-center">
                                            <span>
                                                A senha deve que conter no mínimo 8 caracteres e ser composta de letras e números
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <form method="POST">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    Redefinir a Senha:
                                                </span>
                                                <input required class="form-control" style="font-size: 16px;text-align: center" type="password" id="ps" name="a[<?php echo tool::encrypt('user_password') ?>]" value="" <?php echo $usuario['ativo'] == 1 ? 'placeholder="Senha Criptografada"' : '' ?>  >
                                                <span class="input-group-addon"  >
                                                    <button type="button" class="btn btn-link btn-xs" onclick="document.getElementById('ps').value = '<?php echo user::gerarSenha() ?>';document.getElementById('ps').type = 'text'">
                                                        Gerar Senha
                                                    </button>
                                                </span>
                                            </div> 
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    Digite Novamente a Senha:
                                                </span> 
                                                <input required class="form-control" style="font-size: 16px;text-align: center" type="password" id="ps" name="password" value="" <?php echo $usuario['ativo'] == 1 ? 'placeholder="Senha Criptografada"' : '' ?>  >
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <?php echo DB::hiddenKey('users', 'replace') ?>
                                            <button class="btn btn-success">
                                                Salvar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php
                    } elseif (@$_POST['aba'] == "dados") {
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                Alterar Dados
                            </div>
                            <div class="panel-body" >
                                <form id="form" method="POST">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <?php echo formulario::input('1[n_social]', 'Nome Social', NULL, @$usuario['n_social'])
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <?php echo formulario::input('1[email]', 'E-mail', NULL, @$usuario['email'])
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <?php echo formulario::input('1[tel1]', 'Telefone 1', NULL, @$usuario['tel1']) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <?php echo formulario::input('1[tel2]', 'Telefone 2', NULL, @$usuario['tel2']) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <?php echo formulario::input('1[tel3]', 'Telefone 3', NULL, @$usuario['tel3']) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 offset4" style="text-align: center">
                                            <?php
                                            echo DB::hiddenKey('PessoaEdit');
                                            ?>
                                            <button style="padding: 20px" class="btn btn-success">
                                                Salvar
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>

                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>
