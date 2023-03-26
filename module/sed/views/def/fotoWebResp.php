<?php
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa_resp = filter_input(INPUT_POST, 'id_pessoa_resp', FILTER_SANITIZE_NUMBER_INT);
if ($id_pessoa_resp) {
    ?>
    <div> 
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
                    crop_width: 225,
                    crop_height: 300,
                    width: 472,
                    height: 354,
                    dest_width: 472,
                    dest_height: 354,
                    image_format: 'jpeg',
                    jpeg_quality: 100,
                    flip_horiz: true
                });
                setTimeout(function(){ Webcam.attach('#minha_camera'); }, 1000);
                
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
                ajax.open("POST", "<?php echo HOME_URI ?>/sed/def/webcamup.php?id_pessoa=<?php echo @$id_pessoa_resp ?>");
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
                    function selset() {
                        valor = document.getElementById('sel').value;
                        if (valor.length == 0) {
                            alert('Escolha um Tipo de Documento');
                        }
                    }
                    window.onload = mostrar_camera;
        </script>
        <br /><br />
        <div class="row" style="width: 90%; margin: 0 auto">
            <div class="col-sm-5">
                <div class="border" id="camera" style="text-align: center; ">
                    <div id="minha_camera"></div>
                    <br /><br />
                    <form> 
                        <input class="btn btn-info" type="button" value="Tirar Foto" onClick="bater_foto()">
                    </form>
                </div>
            </div>
            <div class="col-sm-5 " id="previa">
                <div class="border" id="camera" style="text-align: center; ">
                    <div id="results" ></div>
                    <br /><br />
                    <div style="text-align: center">
                        <button onmousemove="selset()" style="display: none" id="sal" class="btn btn-success" onclick="salvar_foto();">Upload desta Foto</button>
                    </div>
                </div>
            </div>
        </div>
        <form id="img" target="_parent" action="<?= HOME_URI ?>/sed/aluno" method="POST">
            <?=
            formErp::hidden([
                'id_pessoa' => $id_pessoa,
                'activeNav' => 6
            ])
            ?>
        </form> 
        <div class="container" id="salva">
            <span id="carregando"></span><img id="completado" src=""/>
        </div>

    </div>
    <?php
}