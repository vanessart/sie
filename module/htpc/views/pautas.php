<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="body">
    <div class="fieldTop">
        Pautas
    </div>

    <?php if ( empty($dados) ) { ?>
    <div class="row">
        <div class="col-sm-12 text-center" style="margin: 20px auto;">
            <div class="alert alert-info">Não há Pautas disponíveis</div>
        </div>
    </div>
    <?php }

    if ( empty($model::isProfessor()) ) { ?>
    <div class="row">
        <div class="col-md-2">
            <button class="btn btn-info" onclick="novo()">
               Nova Pauta
            </button>
        </div>
        <div class="col-md-2">
            <form method="POST">
                <?php if (empty($old)) { ?>
                <input type="hidden" name="old" value="1">
                <button class="btn btn-outline-warning">
                   Visualizar Pautas Antigas
                </button>
                <?php } else { ?>
                    <button class="btn btn-outline-info">
                       Visualizar Pautas Recentes
                    </button>
                <?php } ?>
            </form>
        </div>
    </div>
    <?php } ?>
    <br>
    <?php
    if (!empty($formDados)) {
        report::simple($formDados);
    }
    ?>
    <form id="form" target="frame" action="" method="POST">
        <input type="hidden" id="id_pauta" name="id_pauta">
    </form>
    <form id="formList" action="" method="POST">
        <input type="hidden" id="id_pauta_list" name="id_pauta">
        <input type="hidden" id="desativarPauta" name="desativarPauta" value="1">
    </form>
    <form id="formPP" target="frame" action="" method="POST">
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
        document.getElementById('id_pauta').value = '';
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Cadastrar Pauta";
        document.getElementById("form").action = '<?= HOME_URI ?>/htpc/pautasCadastro';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function editar(id_pauta){
        document.getElementById('id_pauta').value = id_pauta;
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Editar Pauta";
        document.getElementById("form").action = '<?= HOME_URI ?>/htpc/pautasCadastro';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function desativar(id_pauta){
        if (confirm('Deseja realmente desativar a Pauta ?')) {
            document.getElementById('id_pauta_list').value = id_pauta;
            document.getElementById("formList").action = '<?= HOME_URI ?>/htpc/pautas';
            document.getElementById("formList").submit();
        }
    }
    function up(id_pauta){
        document.getElementById('id_pauta').value = id_pauta;
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Upload de Documentos";
        document.getElementById("form").action = '<?= HOME_URI ?>/htpc/pautaUpload';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function pdf(id_pauta){
        document.getElementById('id_pauta').value = id_pauta;
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Visualizar PDF";
        document.getElementById("form").action = '<?= HOME_URI ?>/htpc/pautaPDF';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function upPP(id_pauta_proposta){
        document.getElementById('id_pauta_proposta').value = id_pauta_proposta;
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "Upload de Documentos";
        document.getElementById("formPP").action = '<?= HOME_URI ?>/htpc/proporPautaUpload';
        document.getElementById("formPP").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>