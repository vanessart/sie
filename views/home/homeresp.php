<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<br />

<div class="row" style="display:flex;margin: 20px auto"  id="login" >

    <!--
        <div class="col-md-8" style="margin:0 auto; padding: 36px;"> </div>

        <div class="col-md-6">
            <div>

                <iframe src="//cdn.bannersnack.com/banners/bcp0zl5pb/embed/index.html?userId=2837248&t=1493834393" width="400" height="250" scrolling="no" frameborder="0" allowtransparency="true" allowfullscreen="true"></iframe>
            </div>
        </div>
    -->
    <div class="col-md-6 rowField" style="height: 350px;margin:0 auto"  >
        <form method="post">
            <input class="screenWidth" type="hidden" name="screenWidth" value="" />
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group" style="width: 100%">
                        <span class="input-group-addon fieldrow2" id="basic-addon1"  style="width: 100px">CPF</span>
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
                <div class="col-md-12"  style="text-align: center">
                    <?php echo formulario::button('Entrar'); ?>
                </div>
                <div class="col-md-5"></div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-12"  style="text-align: center">
                    <a style="font-weight: bold; font-size:  14px" onclick="document.getElementById('login').style.display = 'none';document.getElementById('email').style.display = '';" href="#">
                        Esqueci a senha
                    </a>
                </div>

                <div class="col-md-12"  style="text-align: center">
                    <a style="font-weight: bold; font-size:  14px" href="<?php echo HOME_URI; ?>/home">Sou Gestor/Professor</a>
                </div>

                <div class="col-md-12"  style="text-align: center">
                    <a style="font-weight: bold; font-size:  14px" href="<?php echo HOME_URI; ?>/geral/resp">Primeiro Acesso ou Recuperação de Senha</a>
                </div>

                <div class="col-md-12"  style="text-align: center">
                    <a style="font-weight: bold; font-size:  14px" href="<?= CLI_URL ?>">Voltar para o Portal da Educação</a>
                </div>

                <div class="col-md-12"  style="text-align: center">
                    <a style="font-weight: bold; font-size:  14px" target="_blank" href="<?= BASE_URL ?>sie/pub/calendarios/SIEB2020_Instrucoes_Pais.pdf">Manual de instrução para acesso</a>
                </div>
            </div>
        </form>
    </div>
</div>

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
                    echo formulario::button('Enviar');
                    ?>
                </div>
                <div class="col-md-5"></div>
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



