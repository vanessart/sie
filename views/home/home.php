<?php
if (!defined('ABSPATH'))
    exit;
$key = $model->key();
$token = DB::sqlKey('googleLogin');
?>
<br /><br /><br />
<form method="post">
    <div class="row"  id="login" style="display: ">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="row rowField" style="padding: 36px">

                <div class="col-md-6" style="height: 250px" >
                    <img src="<?php echo HOME_URI ?>/views/_images/sieb.jpg">
                    <br /><br />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group" style="width: 100%">
                                <span class="input-group-addon fieldrow2" id="basic-addon1"  style="width: 100px">CPF ou E-mail</span>
                                <input type="text" name="userdata[user]" class="form-control"  aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group" style="width: 100%">
                                <span class="input-group-addon fieldrow2" id="basic-addon1"  style="width: 100px">Senha</span>
                                <input type="password" name="userdata[user_password]" class="form-control"  aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                    <br /><br />
                    <div class="row">
                        <div class="col-md-5"></div>
                        <div class="col-md-2">
                            <?php echo formulario::button('Entrar') ?>
                        </div>
                        <div class="col-md-5"></div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-11"  style="text-align: right">
                            <a onclick="document.getElementById('login').style.display = 'none';document.getElementById('email').style.display = '';" href="#">
                                Esqueci a senha
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                Primeiro acesso
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" style="height: 250px" >
                    <div class="user">
                        <table style="width: 100%"> 
                            <tr>
                                <td style="width: 120px">
                                    <img style="width: 120px" id="user-photo" src="https://mariovalney.com/wp-content/uploads/2015/06/user-anonimo.jpg">
                                    <br /><br />
                                </td>
                                <td>
                                    &nbsp;&nbsp;&nbsp;
                                </td>
                                <td>
                                    <p id='msg' style="font-weight: bold">
                                        <?php if (!empty($_SESSION['msgLogin'])) { ?>

                                            <label style="color:#F00"><?= $_SESSION['msgLogin'] ?></label>
                                            <?php unset($_SESSION['msgLogin']);

                                        } else { ?>
                                            Só é possível utilizar este método de login com os <span style="white-space: nowrap;">e-mails</span> institucionais:
                                            <br /><br />
                                            <?php
                                            echo '*__*' . implode("<br /><br />*__*", $model->emails());
                                        } ?>
                                    </p>
                                    <div id="user-email"></div>
                                    <br />
                                    <a id="desconect" class=" btn btn-warning" style="width: 100%; display: none" href="#" onclick="signOut();">Desconectar</a> 
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="g_id_onload"
                                        data-client_id="<?= API_GOOGLE ?>"
                                        data-context="signin"
                                        data-ux_mode="popup"
                                        data-auto_select="true"
                                        data-login_uri="<?php echo HOME_URI ?>/home/google"
                                        data-sqlToken="<?php echo $token['sqlToken']; ?>"
                                        data-table="<?php echo $token['table']; ?>"
                                        data-key="<?php echo $key ?>" >
                                    </div>
                                    <div class="g_id_signin"
                                        data-type="standard"
                                        data-shape="rectangular"
                                        data-theme="filled_blue"
                                        data-text="signin"
                                        data-size="medium"
                                        data-logo_alignment="left">
                                    </div>
                                </td>
                                <td>
                                    &nbsp;&nbsp;&nbsp;
                                </td>
                                <td>
                                    <div id="user-name"></div>
                                </td>
                            </tr>
                        </table>
                        <br /><br />
                    </div>

                </div>
            </div>
        </div>


    </div>
</form>
<form method="post">
    <div id="email" class="row" style="display: none; ">
        <div class="col-md-4"></div>
        <div class="col-md-4 rowField" style="height: 200px">
            <div class="row">
                <br />
                <div class="col-md-12">
                    <div class="input-group" style="width: 100%">
                        <span class="input-group-addon fieldrow2" id="basic-addon1"  style="width: 100px">E-mail ou CPF</span>
                        <input type="text" name="user" class="form-control"  aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group" style="width: 100%">
                        O sistema enviará um link para cadastro da senha
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-5"></div>
                <div class="col-md-2">
                    <?php
                    echo DB::hiddenKey('recupera');
                    echo formulario::button('Enviar')
                    ?>
                </div>
                <div class="col-md-5"></div>
            </div>
            <div class="row">
                <div class="col-md-11" style="padding-top: 20px">
                    <a onclick="document.getElementById('email').style.display = 'none';document.getElementById('login').style.display = '';" href="#">
                        Voltar ao Login
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4"></div>
    </div>
    <?php
    if ($this->login_error) {
        echo '<tr><td colspan="2" class="error">' . $this->login_error . '</td></tr>';
    }
    ?>
</form>

<form id="sair" action="<?php echo HOME_URI ?>" method="POST">
    <input type="hidden" value="1" name="exit" />
    <input type="hidden" value="1" name="logout" />

</form>


