<?php
if (!defined('ABSPATH'))
    exit;

?>
<div class="body">
    <div class="fieldTop">
        Cadastro de Empresa
    </div>
    <br /><br />
    <form id="formNew" method="POST" action="<?= HOME_URI ?>/transporte/empresamodal" target="frame">
        <input type="hidden" name="modal" value="1" />
        <input type="hidden" name="novo" value="1" />
        <input type="hidden" name="id_em" id="id_em" value="" />
        <button class="btn btn-success" onclick="novoF()">
           Nova Empresa
        </button>
    </form>
    <br /><br />

    <?php
    toolErp::modalInicio();
    ?>
    <iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
    <br /><br />

    <?php
    $form = $model->listaEmpresas();
    toolErp::relatSimples($form);
    ?>

</div>
<script type="text/javascript">
    function novoF(){
        document.getElementById("id_em").value = '';
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Cadastrar Empresa";
        document.getElementById("formNew").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function editar(id_em){
        document.getElementById("id_em").value = id_em;
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Editar Empresa";
        document.getElementById("formNew").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>