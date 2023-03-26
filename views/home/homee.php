<?php
if (!defined('ABSPATH'))
    exit;
$key = $model->key();
$token = DB::sqlKey('googleLogin');
?>
<br /><br /><br />
<form method="post">
    <input class="screenWidth" type="hidden" name="screenWidth" value="" />
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
                                        Só é possível utilizar este método de login com os <span style="white-space: nowrap;">e-mails</span> institucionais:
                                        <br /><br />
                                        *__*@professor.barueri.br
                                        <br /><br />
                                        *__*@educbarueri.sp.gov.br
                                    </p>
                                    <div id="user-email"></div>
                                    <br />
                                    <a id="desconect" class=" btn btn-warning" style="width: 100%; display: none" href="#" onclick="signOut();">Desconectar</a> 
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>
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

<script>
    $(document).ready(function () {
        var tan = $(document).width();
        jQuery('.screenWidth').val(tan)
    });
    function onSignIn(googleUser) {
        var profile = googleUser.getBasicProfile();
        var userID = profile.getId();
        var userName = profile.getName();
        var userPicture = profile.getImageUrl();
        var userEmail = profile.getEmail();
        var userToken = googleUser.getAuthResponse().id_token;
        //document.getElementById('msg').innerHTML = userEmail;

        if (userEmail !== '') {

            logar(userID, userName, userPicture, userEmail);
        }
    }

    function logar(userID, userName, userPicture, userEmail) {
        if (userEmail !== '') {
            var tan = $(document).width();
            var dados = {
                screenWidth: tan,
                userID: userID,
                userName: userName,
                userPicture: userPicture,
                userEmail: userEmail,
                sqlToken: '<?php echo $token['sqlToken']; ?>',
                table: '<?php echo $token['table']; ?>',
                key: '<?php echo $key ?>'
            };
            $.post('<?php echo HOME_URI ?>/home/google', dados, function (retorna) {

                if (retorna.substring(0, 5) === 'login') {
                    document.getElementById('msg').innerHTML = '';
                    document.getElementById('desconect').style.display = '';
                    document.getElementById('user-photo').src = userPicture;
                    document.getElementById('user-email').innerHTML = '<a href="<?php echo HOME_URI ?>"  style="width: 100%" class="btn btn-success" >Entrar usando o E-mail <br />' + userEmail + '</a>';
                    document.getElementById('user-name').innerText = userName;
                    //  window.location.href = '<?php echo HOME_URI ?>';
                } else {
                    document.getElementById('msg').innerHTML = '<br /><br />' + retorna;
                }

            });
        } else {
            document.getElementById('desconect').style.display = 'none';
            document.getElementById('user-photo').src = '';
            document.getElementById('user-name').innerText = '';
            document.getElementById('user-email').innerText = '';
        }
    }


</script>

<script>
    function signOut() {
        var auth2 = gapi.auth2.getAuthInstance();
        auth2.signOut().then(function () {
            console.log('User signed out.');
        });
        logar('', '', '', '', '');
        window.location.href = "<?php echo HOME_URI ?>?logout=1";
    }
</script>
<form id="sair" action="<?php echo HOME_URI ?>" method="POST">
    <input type="hidden" value="1" name="exit" />
    <input type="hidden" value="1" name="logout" />

</form>


