<?php
if (!defined('ABSPATH'))
    exit;
?>

<div class="body">
    <div class="fieldTop">
        CADAMPE - Inativos
    </div>
    <div class="row">
        <div class="col-md-3">
            <button class="btn btn-info" onclick="novo()">
               Inativar Cadampe
            </button>
        </div>
    </div>
    <br>
     
        <?php
    if(!empty($formIna)){
        report::simple($formIna);
    }?>
    <form id="form" target="frame" action="" method="POST">
    </form>

    <?php
    toolErp::modalInicio();
    ?>
    <iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
</div>

<script>
    //faz reload da pagina após inserir um pedido no banco
    $('#myModal').on('hidden.bs.modal', function () {
        el=document.getElementById('fframe');
        if (typeof el == null) return;
        item = el.contentWindow.document.getElementsByName('closeModal')[0].value;
        if (item == 1)
            window.location.reload();
    }); 

    function novo(){
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Inativar Cadampe";
        document.getElementById("form").action = '<?= HOME_URI ?>/cadampe/inativaCadampe';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
   
</script>