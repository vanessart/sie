<?php
if (!defined('ABSPATH'))
    exit;
$nome = @$_REQUEST['nome'];
$cargo = @$_REQUEST['cargo'];
$email = @$_REQUEST['email'];
$tel = @$_REQUEST['tel'];
?>
<div class="body">
    <div class="fieldTop">
        Assinatura de E-mail
    </div>
    <br />
    <div class="border" style="width: 500px; margin: 0 auto">
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
                        Só é possível utilizar os <span style="white-space: nowrap;">e-mails</span> institucionais:
                        <br /><br />
                        *@<?= CLI_MAIL_DOMINIO ?>
                    </p>
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
                    <div style="text-align: center" id="user-name"></div>
                    <br />
                    <div style="text-align: center" id="user-email"></div>
                </td>
            </tr>
        </table>
    </div>
    <br /><br />
    <div class="row">
        <div class="col">
            <div id="form" class="border" style="width: 90%; margin: 0 auto; display: none; height: 280px">
                <form action="<?= HOME_URI ?>/sed/assinatura" target="frame" method="POST">
                    <?= formErp::input('nome', 'Nome', $nome, ' required id="nome" ') ?>
                    <br />
                    <?= formErp::input('cargo', 'Cargo ou Depto ou Escola', $cargo, ' required ') ?>
                    <br />
                    <?= formErp::input('tel', 'Campo livre ou Tel.', $tel, ' required ') ?>
                    <br />
                    <?= formErp::input('email', 'E-mail', $email, ' required id="email" ') ?>
                    <br />
                    <div style="text-align: center">
                        <button class="btn btn-success" onclick="verImg()">
                            Criar Imagem
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col" id="mostra" style="text-align: center; display: none">
            <div class="border" style=" height: 280px">
                <div style="margin: 0 auto; text-align: center" id="ass"></div>
            </div>
        </div>
    </div>

</div>

<iframe style="display: none" name="frame"></iframe>


<script>
    function verImg() {
        var uniqid = String.fromCharCode(Math.floor((Math.random() * 25) + 65));
        document.getElementById('ass').innerHTML = '<img src="<?= HOME_URI . "/". INCLUDE_FOLDER ."/images/prod_loading.gif" ?>" >';
        nome = document.getElementById('nome').value;
        setTimeout(function () {
            document.getElementById('ass').innerHTML = '<img src="<?= HOME_URI ?>/pub/tmp/' + nome + '.jpeg?token=' + uniqid + '"/><br /><br /><br /><a class="btn btn-warning" href="<?= HOME_URI ?>/pub/tmp/' + nome + '.jpeg?id=<?= uniqid() ?>" download>Baixar Imagem</a>';
        }, 5000);
    }
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
            var dados = {
                userID: userID,
                userName: userName,
                userPicture: userPicture,
                userEmail: userEmail,
            };
            $.post('<?php echo HOME_URI ?>/sed/google', dados, function (retorna) {
                console.log(retorna);
                if (retorna != 'erro') {
                    document.getElementById('nome').value = retorna;
                    document.getElementById('email').value = userEmail;
                    document.getElementById('msg').innerHTML = '';
                    document.getElementById('desconect').style.display = '';
                    document.getElementById('user-photo').src = userPicture;
                    document.getElementById('user-email').innerHTML = userEmail;
                    document.getElementById('user-name').innerText = userName;
                    document.getElementById('form').style.display = '';
                    document.getElementById('mostra').style.display = '';
                } else {
                    document.getElementById('msg').innerHTML = '<br /><br />Não localizamos o seu e-mail em nosssa base de dados';
                }
                
            });
        } else {
            document.getElementById('msg').innerHTML = 'Para o recadastro é necessário logar com o Google<br /><br />Só é possível utilizar os <span style="white-space: nowrap;">e-mails</span> institucionais:<br /><br />*@<?= CLI_MAIL_DOMINIO ?>';
            document.getElementById('desconect').style.display = 'none';
            document.getElementById('user-photo').src = 'https://mariovalney.com/wp-content/uploads/2015/06/user-anonimo.jpg';
            document.getElementById('user-name').innerText = '';
            document.getElementById('user-email').innerText = '';
            document.getElementById('form').style.display = 'none';
        }
    }
    
    function signOut() {
        document.getElementById('form').style.display = 'none';
        document.getElementById('mostra').style.display = 'none';
        var auth2 = gapi.auth2.getAuthInstance();
        auth2.signOut().then(function () {
            console.log('User signed out.');
        });
        logar('', '', '', '', '');
    }
</script>
<form id="sair"  method="POST">
    <input type="hidden" value="1" name="exit" />
    <input type="hidden" value="1" name="logout" />

</form>


