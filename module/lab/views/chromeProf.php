<?php
if (!defined('ABSPATH'))
    exit;
$nome = @$_REQUEST['nome'];
$cargo = @$_REQUEST['cargo'];
$email = @$_REQUEST['email'];
$tel = @$_REQUEST['tel'];
?>
<div class="body">
    <br />
    <div class="row">
        <div class="col">
            <div class="fieldTop border">
                Recadastramento Chromebook
                <br /><br />
                <img style="height: 116px" src="<?= HOME_URI ?>/includes/images/chrome.png"/>
            </div>
        </div>
        <div class="col">
            <div class="border" style="width: 500px">
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
                                Para o recadastro é necessário logar com o Google
                                <br /><br />
                                Só é possível utilizar os <span style="white-space: nowrap;">e-mails</span> institucionais:
                                <br /><br />
                                *@educbarueri.sp.gov.br
                                <br /><br />
                                *@professor.barueri.br
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
        </div>
    </div>
    <br />

    <br />

    <br /><br />
    <div id="list"></div>
</div>



<script>

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
            $.post('<?php echo HOME_URI ?>/lab/chromeProfList', dados, function (retorna) {
                if (retorna != 'erro') {
                    console.log(retorna);
                    document.getElementById('msg').innerHTML = '';
                    document.getElementById('desconect').style.display = '';
                    document.getElementById('user-photo').src = userPicture;
                    document.getElementById('user-email').innerHTML = userEmail;
                    document.getElementById('user-name').innerText = userName;
                    document.getElementById('list').innerHTML = retorna;
                } else {
                    document.getElementById('msg').innerHTML = '<br /><br />Não localizamos o seu e-mail em nosssa base de dados';
                    document.getElementById('list').innerHTML = '';
                }

            });
        } else {
            document.getElementById('msg').innerHTML = 'Para o recadastro é necessário logar com o Google<br /><br />Só é possível utilizar os <span style="white-space: nowrap;">e-mails</span> institucionais:<br /><br />*@educbarueri.sp.gov.br<br /><br />*@professor.barueri.br';
            document.getElementById('desconect').style.display = 'none';
            document.getElementById('user-photo').src = 'https://mariovalney.com/wp-content/uploads/2015/06/user-anonimo.jpg';
            document.getElementById('user-name').innerText = '';
            document.getElementById('user-email').innerText = '';
            document.getElementById('list').innerHTML = '';
        }
    }

    function signOut() {
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
<form action="<?= HOME_URI ?>/lab/defProf/ocorrencia.php" target="frame" method="POST" id="formFrame">
    <input type="hidden" name="id_ch" id="id_ch" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="frame" style="width: 100%; height: 80vh; border:  none"></iframe>
    <?php
    toolErp::modalFim();
    ?>

<script>
    function ocorre(id) {
        if (id) {
            document.getElementById('id_ch').value = id;
            document.getElementById('formFrame').submit();
            $('#myModal').modal('show');
            $('.form-class').val('');
        }
    }
</script>

