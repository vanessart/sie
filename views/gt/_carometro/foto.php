<?php
if (empty($id_pessoa)) {
    $id_turma = @$_REQUEST['id_turma'];
    $id_pessoa = @$_REQUEST['id_pessoa'];
}
$_SESSION['tmp']['id_pessoaFoto'] = $id_pessoa;
$_SESSION['tmp']['id_turma'] = $id_turma;
?> 
<?php
$name = md5(time());
?>
<div style="height: 80vh"> 
    <script type="text/javascript" src="<?php echo HOME_URI ?>/includes/js/webcam.min.js"></script>

    <script language="JavaScript">

        function bater_foto()
        {
            Webcam.snap(function (data_uri)
            {
                document.getElementById('minha_camera').innerHTML = '<div style="cursor: pointer"><img id="base64image" src="' + data_uri + '"/><div>';
            });
            //document.getElementById('previa').style.display = '';
            document.getElementById('sal').style.display = '';
            document.getElementById('denovo').style.display = '';
            document.getElementById('tirafoto').style.display = 'none';
        }

        function mostrar_camera()
        {
            console.log(Webcam);
            Webcam.set({
                width: 384,
                height: 288,
                dest_width: 384,
                dest_height: 288,
                image_format: 'jpeg',
                jpeg_quality: 100,
                force_flash: false,
                facingMode: "user"
            });

            Webcam.attach('#minha_camera');
            document.getElementById('sal').style.display = 'none';
            document.getElementById('denovo').style.display = 'none';
            document.getElementById('tirafoto').style.display = '';
        }

        function salvar_foto()
        {
            document.getElementById("carregando").innerHTML = "Salvando, aguarde...";
            var file = document.getElementById("base64image").src;
            var formdata = new FormData();
            formdata.append("base64image", file);
            var ajax = new XMLHttpRequest();
            ajax.addEventListener("load", function (event) {
                upload_completo(event);
            }, false);
            ajax.open("POST", "<?php echo HOME_URI ?>/gt/fotossalvar?name=<?php echo @$id_pessoa ?>");
                    ajax.send(formdata);
                }

                function upload_completo(event)
                {
                    document.getElementById("carregando").innerHTML = "";
                    var image_return = event.target.responseText;
                    document.getElementById('img').submit();
                }
                function someat() {
                    //document.getElementById('previa').style.display = 'none';
                    document.getElementById('sal').style.display = 'none';
                }
                window.onload = mostrar_camera;
    </script>
    <br /><br />
    <div class="row">
        <div class="col-sm-12" id="camera" style="padding: 50px">
            <table class="fieldBorder2">
                <tr>
                    <td>
                        <div id="minha_camera"></div>
                    </td>
                    <td>
                        <div style="height: 144px">  
                            <button id="tirafoto" style=" font-weight: bold; height: 144px; width: 100px" class="btn btn-info " onClick="bater_foto()">
                                Tirar 
                                <br>
                                Foto
                            </button>
                            <button style="display: none; height: 144px; font-weight: bold; width: 100px"  id="sal" class="btn btn-success" onclick="salvar_foto();">
                                Upload 
                                <br>
                                desta
                                <br>
                                Foto
                            </button> 
                        </div>
                        <div style="height: 144px">
                            <button id="denovo" style=" display: none; font-weight: bold; height: 144px; width: 100px" class="btn btn-danger " onClick="mostrar_camera()">
                                Tentar 
                                <br>
                                de
                                <br />
                                Novo
                            </button> 
                        </div>

                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?php
                        if (!empty($id_turma)) {
                            ?>
                            <form action="<?php echo HOME_URI ?>/gt/carometro" method="POST">
                                <input type="hidden" name="id_turma" value="<?php echo @$id_turma ?>" />
                                <div style="text-align: center">
                                    <input style="width: 100%" class="btn btn-warning" type="submit" value="Atualizar o Carômetro e Fechar" />
                                </div>
                            </form>
                            <?php
                        }
                        ?>    
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="container" id="salva">
    <span id="carregando"></span><img id="completado" src=""/>
</div>

<form action="<?php echo HOME_URI ?>/gt/carometro" id="img" method="POST">
    <input type="hidden" name="name" value="<?php echo $name ?>" />
    <input type="hidden" name="up" value="1" />
    <input type="hidden" name="activeNav" value="4" />
    <input type="hidden" name="id_pessoa" value="<?php echo @$id_pessoa ?>" />
    <input type="hidden" name="id_turma" value="<?php echo @$id_turma ?>" />
</form>
</div>
