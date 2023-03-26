<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="body">
    <div class="fieldTop">
        Lista Branca
    </div>
    <br /><br />
    <form id="formNew" method="POST" action="<?= HOME_URI ?>/transporte/listabrancamodal" target="frame">
        <input type="hidden" name="id_pessoa" id="id_pessoa" value="" />
        <button class="btn btn-success" onclick="novo()">
           Incluir Aluno
        </button>
    </form>
    
    <?php
    $form = $model->listaBranca();
    toolErp::relatSimples($form);

    toolErp::modalInicio();
    ?>
    <iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
</div>

<script type="text/javascript">
    function novo(){
        document.getElementById("id_pessoa").value = '';
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Incluir Aluno";
        document.getElementById("formNew").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function editar(id_pessoa){
        document.getElementById("id_pessoa").value = id_pessoa;
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Editar Aluno";
        document.getElementById("formNew").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>