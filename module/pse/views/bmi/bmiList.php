<?php
if (!defined('ABSPATH'))
    exit;
$n_campanha = $model->campanha();
?>

<div class="body">
    <div class="fieldTop">
    Programa Saúde na Escola <br> <br>PSE - <?= $n_campanha ?>
    </div>
    <div class="row">
        <div class="col-2">
            <button onclick="imc('bmi','IMC')"   class="btn btn-outline-info">
              Calcular IMC
            </button>
        </div>
    </div>
    <form id="formFrame" target="frame" action="" method="POST">
        <input type="hidden" id="id_pessoa" name="id_pessoa" value="" />
    </form>
    <?php 
    toolErp::modalInicio(null,'modal-md');
    ?>
    <iframe style=" width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
    <script>
    function imc(action,titulo) {
        document.getElementById('formFrame').action = "<?= HOME_URI ?>/pse/"+action;
        texto = '<div style="text-align: center; color: #7ed8f5;">'+titulo+'</div>';
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>