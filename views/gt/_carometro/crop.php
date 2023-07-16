<?php
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
?>
<style>
    .btns{
        text-align: center;
    }
    .bt{
        width: 150px;
        height: 120px;
        font-size: 18px; 
        color: black;
        line-height: 18px;
        border-radius: 16px;
        text-align: center;
    }
    p{
        padding-top: 10px;
    }

</style>
<script src="<?php echo HOME_URI; ?>/<?php echo INCLUDE_FOLDER ?>/js/jquery-3.4.1.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/0.9.15/js/jquery.Jcrop.min.js" integrity="sha512-KKpgpD20ujD3yJ5gIJqfesYNuisuxguvTMcIrSnqGQP767QNHjEP+2s1WONIQ7j6zkdzGD4zgBHUwYmro5vMAw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?php echo HOME_URI; ?>/<?php echo INCLUDE_FOLDER ?>/js/jquery.Jcrop.js"></script>
<link rel="stylesheet" href="<?php echo HOME_URI; ?>/<?php echo INCLUDE_FOLDER ?>/css/jquery.Jcrop.css" type="text/css" />
<div style="margin-left: 10px">
    <table style="width: 100%">
        <tr>
            <td style="width: 33%">
                <form method="POST">
                    <input type="hidden" name="id_turma" value="<?= @$_POST['id_turma'] ?>" />
                    <input type="hidden" name="proc" value="1" />
                    <input type="hidden" name="id_pessoa" value="<?= $id_pessoa ?>" />
                    <input type="hidden" name="rotateImg" value="1" />
                    <button class="btn btn-link" style=" width: 100%; height: 69px">
                        <img style="height: 30px" src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/rotate.png"/>
                    </button> 
                </form>        
            </td>
            <td style="width: 33%">
                <input type="hidden" name="id_turma" value="<?= @$_POST['id_turma'] ?>" />
                <input type="hidden" name="id_pessoa" value="<?= $id_pessoa ?>" />
                <button onclick="$('#cortar').submit()" class="btn btn-link" style="color: black; font-weight: bold; font-size: 22px">
                    Concluir
                </button> 
            </td>
        </tr>
    </table>
    <br />
    <div style="text-align: center; ">
        <img style="width: 320px; height: 426; margin: 0 auto; display: block" src="<?= HOME_URI ?>/pub/fotos/<?= $id_pessoa ?>.jpg" id="cropbox" />
    </div>

    <form id="cortar" method="post" onsubmit="return checkCoords();">
        <input type="hidden" name="cropImg" value="1" />
        <input type="hidden" id="x" name="x" />
        <input type="hidden" id="y" name="y" />
        <input type="hidden" id="w" name="w" />
        <input type="hidden" id="h" name="h" />
        <input type="hidden" name="id_turma" value="<?= @$_POST['id_turma'] ?>" />
        <input type="hidden" name="id_pessoa" value="<?= $id_pessoa ?>" />

    </form> 
    <form id="ft" method="POST">
        <input type="hidden" name="id_turma" value="<?= @$_POST['id_turma'] ?>" />
    </form>
</div>
<script language="Javascript">

    $(function () {
        $wIni = $('#cropbox').width();
        $hfim = $('#cropbox').height();
        if ($wIni < $hfim) {
            $tam = $wIni;
        } else {
            $tam = $hfim;
        }
        $tam = $tam - 100;
        $('#cropbox').Jcrop({
            bgFade: true,
            bgOpacity: .3,
            setSelect: [$tam, $tam, 50, 50],
            aspectRatio: 1,
            onSelect: updateCoords
        });

    });

    function updateCoords(c)
    {
        $('#x').val(c.x);
        $('#y').val(c.y);
        $('#w').val(c.w);
        $('#h').val(c.h);
    }
    ;

    function checkCoords()
    {
        if (parseInt($('#w').val()))
            return true;
        alert('Seleciona a região a ser salva');
        return false;
    }
    ;

</script>
<?php
exit();
?>