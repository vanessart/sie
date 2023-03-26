<?php
if (empty($id_pessoa)) {
    $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
    $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
}
$_SESSION['tmp']['id_pessoaFoto'] = $id_pessoa;
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
                document.getElementById('results').innerHTML = '<div style="cursor: pointer;text-align: center"><img id="base64image" src="' + data_uri + '"/><div>';
            });
            document.getElementById('previa').style.display = '';
            document.getElementById('sal').style.display = '';
        }

        function mostrar_camera()
        {
            Webcam.set({
                width: 384,
                height: 288,
                dest_width: 384,
                dest_height: 288,
                image_format: 'jpeg',
                jpeg_quality: 100,
                flip_horiz: false
            });
            Webcam.attach('#minha_camera');
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
                    document.getElementById('previa').style.display = 'none';
                    document.getElementById('sal').style.display = 'none';
                }
                window.onload = mostrar_camera;
    </script>
    <br /><br />
    <div class="row">
        <div class="col-sm-2 " id="camera" ></div>
        <div class="col-sm-4 " id="camera" style="text-align: center">
            <div id="minha_camera"></div>
            <br /><br />
            <form> 
                <input class="btn btn-info" type="button" value="Tirar Foto" onClick="bater_foto()">
            </form>
        </div>
        <div class="col-sm-4 " id="previa" style="text-align: center">
            <div id="results" ></div>
            <br /><br />
            <button  style="display: none" id="sal" class="btn btn-success" onclick="salvar_foto();">Upload desta Foto</button>
        </div>
    </div>

    <div class="container" id="salva">
        <span id="carregando"></span><img id="completado" src=""/>
    </div>

    <form id="img" method="POST">
        <input type="hidden" name="name" value="<?php echo $name ?>" />
        <input type="hidden" name="up" value="1" />
        <input type="hidden" name="activeNav" value="4" />
        <input type="hidden" name="id_cur" value="<?php echo $id_cur ?>" />
        <input type="hidden" name="id_pessoa" value="<?php echo @$id_pessoa ?>" />
    </form>
</div>
