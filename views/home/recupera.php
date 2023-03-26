<?php
$vr = @$_REQUEST['vr'];
$dados = $model->verifRecupera($vr);
?>
<div class="row" style="padding-top: 50px">
    <div class="col-md-4"></div>
    <div class="col-md-4" >
        <div id="2" class="field" >
            <div class="row">
                <div class="col-md-12 text-center">
                    <div style="font-size: 25px">
                        Redefinição de Senha
                    </div>
                    <br />
                    <ol style="text-align: left">
                        <li>
                            A senha deve conter letras, números e ter no mínimo 8 caracteres.
                        </li>
                        <li>
                            Confirme a nova senha, digitando-a duas vezes
                        </li>
                    </ol>

                </div>
            </div>
            <form method="POST">
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Nova Senha:
                            </span> 
                            <input onkeyup="verifica()" required class="form-control" style="font-size: 16px;padding-left: 10px" type="password" id="ps" name="gh6fdg" value=""   >

                        </div> 
                    </div>
                </div>
                <br /><br />
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Confirmação de Nova Senha:
                            </span> 
                            <input onkeyup="verifica()" required class="form-control" style="font-size: 16px;padding-left: 10px" type="password" id="ps1" name="dfdfdfd" value=""  >

                        </div> 
                    </div>
                </div>
                <br /><br />
                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-2">
                        <?php
                        echo $model->db->hiddenKey('users', 'replace', '/models/home/includeRecupera.php');
                        echo formulario::hidden(['1[ativo]' => 1]);
                        echo formulario::hidden(['ljfgd7jfg' => $dados]);
                        ?>
                        <button id="btn" type="submit" class="btn btn-success" disabled>
                            Redefinir Senha
                        </button>
                    </div>
                </div>
                <div style="text-align: right">
                    <a href="<?php echo HOME_URI ?>">Voltar ao login</a>
                </div>

            </form>
        </div>                 
    </div>  
    <div class="col-md-4"></div>

</div>   
<script>
    function verifica() {
        s = document.getElementById('ps').value;
        s1 = document.getElementById('ps1').value;
        if (s == s1) {
            console.log('sim');
            document.getElementById("btn").disabled = false;
            s1 = document.getElementById('ps1').style.borderColor = "black"
        } else {
            console.log('não');
            s1 = document.getElementById('ps1').style.borderColor = "red"
            document.getElementById("btn").disabled = true;
        }
        console.log(s);
        console.log(s1);

    }
</script>