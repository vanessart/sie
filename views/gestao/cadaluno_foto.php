<?php
$_SESSION['tmp']['id_pessoaFoto'] = $dados ['id_pessoa'];
?> 

<div style="width: 448px; height: 336px; position: absolute; right: 62%;text-align: center; margin-right: -198px;margin-top: 40px">
    <video id="video" width="448" height="316" autoplay></video>
</div>
<div style="width: 448px; height: 336px; position: absolute; right: 62%;text-align: center; margin-right: -198px;margin-top: 40px">
    <div style="width: 90px; height: 336px; position: relative;background-color: white; margin-top: -10px "></div>
</div>
<div style="width: 448px; height: 336px; position: absolute; right: 62%;text-align: center; margin-right: -198px;margin-top: 40px">
    <div style="width: 90px; height: 336px; position: relative;background-color: white; margin-left: 373px; margin-top: -10px "></div>
</div>

<div style="width: 260px; height: 312px; position: absolute; left: 52%;text-align: center;margin-top: 30px">
    <canvas class="fieldborder" id="canvas" width="260" height="312"></canvas>
</div>
<div style="width: 260px; height: 312px; position: absolute; left: 52%;text-align: center;margin-top: 390px">
    <button id="save">Salvar Foto</button>
</div>
<div style="width: 260px; height: 312px; position: absolute; right: 56%;text-align: center;margin-top: 390px">
    <button id="snap">Tirar Foto</button>
</div>

<script>
    window.addEventListener("DOMContentLoaded", function () {
        var canvas = document.getElementById("canvas"),
                context = canvas.getContext("2d"),
                video = document.getElementById("video"),
                videoObj = {"video": true},
                errBack = function (error) {
                    console.log("Video capture error: ", error.code);
                };
        if (navigator.getUserMedia) {
            navigator.getUserMedia(videoObj, function (stream) {
                video.src = stream;
                video.play();
            }, errBack);
        } else if (navigator.webkitGetUserMedia) {
            navigator.webkitGetUserMedia(videoObj, function (stream) {
                video.src = window.webkitURL.createObjectURL(stream);
                video.play();
            }, errBack);
        } else if (navigator.mozGetUserMedia) {
            navigator.mozGetUserMedia(videoObj, function (stream) {
                video.src = window.URL.createObjectURL(stream);
                video.play();
            }, errBack);
        }
    }, false);
    document.getElementById("snap").addEventListener("click", function () {
        canvas.getContext("2d").drawImage(video, -90, 0, 448, 336);
        //alert(canvas.toDataURL());
    });
    document.getElementById("save").addEventListener("click", function () {
        $.post('<?php echo HOME_URI ?>/gestao/fotossalvar', {imagem: canvas.toDataURL()}, function (data) {
        }, 'json');
        alert("Imagem Salva");
    });
</script>   