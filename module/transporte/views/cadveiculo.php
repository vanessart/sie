<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="body">
    <div class="fieldTop">
        Cadastro de Veículo
    </div>
    <br /><br />
    <form id="formNew" method="POST" action="<?= HOME_URI ?>/transporte/cadveiculomodal" target="frame">
        <input type="hidden" name="modal" value="1" />
        <input type="hidden" name="id_tv" id="id_tv" value="" />
        <button class="btn btn-success" onclick="novo()">
           Cadastrar Veículo
        </button>
    </form>

    <?php
    toolErp::modalInicio();
    ?>
    <iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
    <br /><br />
    <?php
    $form = $model->listaVeiculos();

    toolErp::relatSimples($form);
    ?>
</div>
<script type="text/javascript">
    function novo(){
        document.getElementById("id_tv").value = '';
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Cadastrar Veículo";
        document.getElementById("formNew").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function editar(id_tv){
        document.getElementById("id_tv").value = id_tv;
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Editar Veículo";
        document.getElementById("formNew").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>