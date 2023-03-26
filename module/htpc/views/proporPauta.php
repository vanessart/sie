<?php
if (!defined('ABSPATH'))
    exit;
?>
<style type="text/css">
    .alert-success {
        padding: 3px;
        font-size: 8px;
    }
    .alert-danger {
        padding: 3px;
        font-size: 8px;
    }
</style>
<div class="body">
    <div class="fieldTop">
        Propostas de Pauta
    </div>
    <?php if (empty($formDados)) { ?>
    <div class="row">
        <div class="col-sm-12 text-center" style="margin: 20px auto;">
            <div class="alert alert-info">Nenhuma pauta proposta cadastrada</div>
        </div>
    </div>
    <?php } 

    if (!empty($model::isCoordenadoria())) {
    ?>
    <div class="row">
        <div class="col-md-3">
            <button class="btn btn-info" onclick="novo()">
               Propor Pauta
            </button>
        </div>
    </div>
    <br>
    <?php
    }

    if (!empty($formDados)) {
        report::simple($formDados);
    }
    ?>
    <form id="form" target="frame" action="" method="POST">
        <input type="hidden" id="id_pauta_proposta" name="id_pauta_proposta">
    </form>
    <?php
    toolErp::modalInicio();
    ?>
    <iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
</div>
<script type="text/javascript">
    function novo(){
        document.getElementById('id_pauta_proposta').value = '';
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Cadastrar Proposta de Pauta";
        document.getElementById("form").action = '<?= HOME_URI ?>/htpc/cadastrarPropostaPauta';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function editar(id_pauta_proposta){
        document.getElementById('id_pauta_proposta').value = id_pauta_proposta;
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Editar Proposta de Pauta";
        document.getElementById("form").action = '<?= HOME_URI ?>/htpc/cadastrarPropostaPauta';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function up(id_pauta_proposta){
        document.getElementById('id_pauta_proposta').value = id_pauta_proposta;
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Upload de Documentos";
        document.getElementById("form").action = '<?= HOME_URI ?>/htpc/proporPautaUpload';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>