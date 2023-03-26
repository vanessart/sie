<?php
if (!defined('ABSPATH'))
    exit;
$anos = $model->getAnosProtocolos();
?>
<style type="text/css">
    .modal_red{
        color: red;
        font-weight: bold;
        text-align: center;
    }
    .modal_green{
        color: green;
        font-weight: bold;
        text-align: center;
    }
    .modal_blue{
        color: blue;
        font-weight: bold;
        text-align: center;
    }
    .novaMensagem{
        padding: 1px 3px 3px;
        font-size: 70%;
        border-radius: 6px;
        display: block;
        width: 85px;
        margin-top: 1px;
    }
</style>
<div class="body">
    <div class="fieldTop">
        Convocados
    </div>
    <div class="row">
        <div class="col-md-2">
            <?= formErp::select('ano', $anos, 'Ano', @$ano, 1, @$hidden, ' required ') ?>
        </div>
        <div class="col-md-3">
            <?= formErp::select('mes', data::meses(), 'mês', @$mes, 1, @$hidden, ' required ') ?>
        </div>
    </div>
    <br>
     
        <?php
    if(!empty($formPedido)){
        report::simple($formPedido);
    }?>

    <form id="form" target="frame" action="" method="POST">
        <input type="hidden" name="id_cadampe_pedido" id="id_cadampe_pedido" value="" />
        <input type="hidden" name="tel1" id="tel1" value="" />
        <input type="hidden" name="tel2" id="tel2" value="" />
        <input type="hidden" name="tel3" id="tel3" value="" />
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

    function edit(id,id_status,n_status){

        if (id){
            document.getElementById("id_cadampe_pedido").value = id;
            var titulo= document.getElementById('myModalLabel');
            if (id_status==3){
                titulo.innerHTML  = "Protocolo "+id+" <label class='modal_green'>"+n_status+"</label";
            } else if (id_status==2){
                titulo.innerHTML  = "Protocolo "+id+" <label class='modal_red'>"+n_status+"</label";
            }else{
                titulo.innerHTML  = "Protocolo "+id+" <label class='modal_blue'>"+n_status+"</label";
            
            }
            document.getElementById("form").action = '<?= HOME_URI ?>/cadampe/solicitarRel';
        }else{
            var titulo= document.getElementById('myModalLabel');
            titulo.innerHTML  = "Solicitar Cadampe";
            document.getElementById("id_cadampe_pedido").value = "";
            document.getElementById("form").action = '<?= HOME_URI ?>/cadampe/solicitar';
        }
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }

    
</script>