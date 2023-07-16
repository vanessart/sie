<?php
$usuario = $model->dadosUser();
$tel = ng_aluno::telefone($usuario['id_pessoa']);
?>
<div class="body">
    <div class="panel panel-default" style="height: 500px">
        <div class="panel-heading text-center fieldTop">
            Dados do Usuário
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
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
                <div class="col-md-8">
                    <div class="row">
                        <!--
                        <div class="col-md-4" >
                            <form method="POST">
                                <input type="hidden" name="aba" value="dados" />
                                <button style="width: 100%" class="btn btn-info">
                                    Alterar Dados Pessoais
                                </button>
                            </form>
                        </div>
                        -->
                        <div class="col-6">
                            <form method="POST">
                                <input type="hidden" name="aba" value="senha" />
                                <button style="width: 100%" class="btn btn-success">
                                    Alterar Senha
                                </button>
                            </form>
                        </div>
                        <div class="col-6">
                            <form method="POST">
                                <input type="hidden" name="aba" value="assinatura" />
                                <button style="width: 100%" class="btn btn-primary">
                                    Assinatura de E-mail
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

                                <div class="panel-heading text-center fieldTop">
                                    Alterar Senha
                                </div>
                                <div id="2" class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="panel panel-danger text-center p-1">
                                                <span>
                                                    A senha deve que conter no mínimo 8 caracteres e ser composta de letras e números
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <form method="POST">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="input-group p-1">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">Redefinir a Senha:</div>
                                                    </div>
                                                    <input required class="form-control" style="font-size: 16px;text-align: center" type="password" id="ps" name="a[<?php echo tool::encrypt('user_password') ?>]" value="" <?php echo $usuario['ativo'] == 1 ? 'placeholder="Senha Criptografada"' : '' ?>  >
                                                    <span class="input-group-addon"  >
                                                        <button type="button" class="btn btn-link btn-xs btn-light" onclick="document.getElementById('ps').value = '<?php echo user::gerarSenha() ?>'; document.getElementById('ps').type = 'text'">
                                                            Gerar Senha
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="input-group p-1">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">Digite Novamente a Senha:</div>
                                                    </div> 
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
                                <div class="panel-heading text-center fieldTop">
                                    Alterar Dados
                                </div>
                                <div class="panel-body" >
                                    <form id="form" method="POST">
                                        <div class="row">
                                            <div class="col-lg-12 p-1">
                                                <?php echo formErp::input('1[n_social]', 'Nome Social', @$usuario['n_social'])
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row" style="display: none">
                                            <div class="col-lg-12 p-1">
                                                <?php echo formErp::input('1[email]', 'E-mail', @$usuario['email'])
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <?= formErp::selectDB('telefones_tipo', '1[fk_id_tt][1]', 'Tipo', @$tel[0]['fk_id_tt']) ?>
                                            </div>
                                            <div class="col-lg-2">
                                                <?php echo formErp::input('1[ddd][1]', 'DDD', @$tel[0]['ddd'], 'maxlength="2"', NULL, 'tel') ?>
                                            </div>
                                            <div class="col-lg-4">
                                                <?php echo formErp::input('1[tel1]', 'Telefone 1', @$tel[0]['num'], NULL, NULL, 'tel') ?>
                                            </div>
                                            <div class="col-lg-3">
                                                <?php echo formErp::input('1[tel_comp][1]', 'Compl.', @$tel[0]['complemento'], NULL, 'Ex: mãe') ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <?= formErp::selectDB('telefones_tipo', '1[fk_id_tt][2]', 'Tipo', @$tel[1]['fk_id_tt']) ?>
                                            </div>
                                            <div class="col-lg-2">
                                                <?php echo formErp::input('1[ddd][2]', 'DDD', @$tel[1]['ddd'], 'maxlength="2"', NULL, 'tel') ?>
                                            </div>
                                            <div class="col-lg-4">
                                                <?php echo formErp::input('1[tel2]', 'Telefone 2', @$tel[1]['num'], NULL, NULL, 'tel') ?>
                                            </div>
                                            <div class="col-lg-3">
                                                <?php echo formErp::input('1[tel_comp][2]', 'Compl.', @$tel[1]['complemento'], NULL, 'Ex: pai') ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <?= formErp::selectDB('telefones_tipo', '1[fk_id_tt][3]', 'Tipo', @$tel[2]['fk_id_tt']) ?>
                                            </div>
                                            <div class="col-lg-2">
                                                <?php echo formErp::input('1[ddd][3]', 'DDD', @$tel[2]['ddd'], 'maxlength="2"', NULL, 'tel') ?>
                                            </div>
                                            <div class="col-lg-4">
                                                <?php echo formErp::input('1[tel3]', 'Telefone 3', @$tel[2]['num'], NULL, NULL, 'tel') ?>
                                            </div>
                                            <div class="col-lg-3">
                                                <?php echo formErp::input('1[tel_comp][3]', 'Compl.', @$tel[2]['complemento'], NULL, 'Ex: avó') ?>
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
                        } elseif (@$_POST['aba'] == "assinatura") {
                            $nome = filter_input(INPUT_POST, 'nome');
                            $cargo = filter_input(INPUT_POST, 'cargo');
                            $email = filter_input(INPUT_POST, 'email');
                            $tel = filter_input(INPUT_POST, 'tel');
                            if (!$nome) {
                                $nome = $usuario['n_pessoa'];
                            }
                            if (!$email) {
                                $email = $usuario['emailgoogle'];
                            }
                            ?>
                            <form action="<?= HOME_URI ?>/sed/assinatura" target="frame" method="POST">
                                <?= formErp::input('nome', 'Nome', $nome, ' required id="nome" ') ?>
                                <br />
                                <?= formErp::input('cargo', 'Cargo ou Depto ou Escola', $cargo, ' required id="cargo" ') ?>
                                <br />
                                <?= formErp::input('tel', 'Campo livre ou Tel.', $tel) ?>
                                <br />
                                <?= formErp::input('email', 'E-mail', $email, ' required id="email" ') ?>
                                <br />
                                <div style="text-align: center">
                                    <?= formErp::hidden(['aba' == "assinatura"]) ?>
                                    <button class="btn btn-success" onclick="verImg()">
                                        Criar Imagem
                                    </button>
                                </div>
                            </form>
                            <iframe  style="display: none" name="frame"></iframe>
                            <div class="col" id="mostra" style="text-align: center;">
                                <div class="border" style=" height: 280px">
                                    <div style="margin: 0 auto; text-align: center" id="ass"></div>
                                </div>
                            </div>
                            <script>
                                function verImg() {
                                    var uniqid = String.fromCharCode(Math.floor((Math.random() * 25) + 65));
                                    document.getElementById('ass').innerHTML = '<img src="<?= HOME_URI . "/". INCLUDE_FOLDER ."/images/prod_loading.gif" ?>" >';
                                    nome = document.getElementById('nome').value;
                                    cargo = document.getElementById('cargo').value;
                                    email = document.getElementById('email').value;
                                    if (email && nome && cargo) {
                                        setTimeout(function () {
                                            document.getElementById('ass').innerHTML = '<img src="<?= HOME_URI ?>/pub/tmp/' + nome + '.jpeg?token=' + uniqid + '"/><br /><br /><br /><a class="btn btn-warning" href="<?= HOME_URI ?>/pub/tmp/' + nome + '.jpeg?id=<?= uniqid() ?>" download>Baixar Imagem</a>';
                                        }, 5000);
                                    } else {
                                        document.getElementById('ass').innerHTML = "Preencha os campos Nome, E-mail e Cargo ou Depto ou Escola";
                                    }
                                }
                            </script>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
