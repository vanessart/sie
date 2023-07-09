<?php
$name = md5(time());
?>
<div style="height: 80vh"> 
    <script type="text/javascript" src="<?php echo HOME_URI ?>/<?php echo INCLUDE_FOLDER ?>/js/webcam.min.js"></script>

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
                width: 307,
                height: 230,
                dest_width: 307,
                dest_height: 230,
                //crop_width: 300,
                //crop_height: 400,
                image_format: 'jpeg',
                jpeg_quality: 100,
                flip_horiz: false
            });
            Webcam.attach('#minha_camera');
        }

        function salvar_foto() {
            document.getElementById("carregando").innerHTML = "Salvando, aguarde...";
            var file = document.getElementById("base64image").src;
            var formdata = new FormData();
            formdata.append("base64image", file);
            var ajax = new XMLHttpRequest();
            ajax.addEventListener("load", function (event) {
                upload_completo(event);
            }, false);
            ajax.open("POST", "<?php echo HOME_URI ?>/sed/webcamup?name=<?php echo @$id_pessoa . '_' . $name ?>");
                    ajax.send(formdata);
                }

                function upload_completo(event) {
                    document.getElementById("carregando").innerHTML = "";
                    var image_return = event.target.responseText;
                    document.getElementById('img').submit();
                }
                function someat() {
                    document.getElementById('previa').style.display = 'none';
                    document.getElementById('sal').style.display = 'none';
                }
                function selset() {
                    valor = document.getElementById('fk_id_pront_').value;
                    if (valor.length == 0) {
                        alert('Escolha um Tipo de Documento');
                    }
                }
                window.onload = mostrar_camera;
    </script>
    <br /><br />
    <div class="row">
        <div class="col" id="camera" style="text-align: center">
            <div id="minha_camera"></div>
            <br /><br />
        </div>
        <div class="col" id="previa" style="text-align: center">
            <div id="results" ></div>
            <br /><br />
        </div>
    </div>
    <div style="text-align: center">
        <form> 
            <input class="btn btn-info" type="button" value="Tirar Foto" onClick="bater_foto()">
        </form>
    </div>
    <form id="img" target="_parent" method="POST" action="<?= HOME_URI ?>/sed/aluno" >
        <?php echo formErp::select('fk_id_pront', $tipoDoc, 'Documento', NULL, NULL, NULL, ' required ') ?>
        <br /><br />
        <?php echo formErp::input('n_pu', 'Obs:') ?>
        <div class="col-sm-5">
            <?php echo formErp::hiddenToken('uploadImgCad') ?>

            <input type="hidden" name="name" value="<?php echo $name ?>" />
            <input type="hidden" name="activeNav" value="5" />
            <input type="hidden" name="id_pessoa" value="<?php echo @$id_pessoa ?>" />
        </div>
    </form> 
    <br /><br />
    <div style="text-align: center">
        <button onmousemove="selset()" style="display: none" id="sal" class="btn btn-success" onclick="salvar_foto();">Upload desta Foto</button>
    </div>

    <div class="container" id="salva">
        <span id="carregando"></span><img id="completado" src=""/>
    </div>

</div>
