<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="body">
    <div class="fieldTop">
        Quadro de Alunos
    </div>
    <form target="_blank" action="<?php echo HOME_URI ?>/gestao/quadroalunopdf" id="form" method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::input('datai', 'Data Início', date("Y") . '-01-01', null, null, 'date') ?>
            </div>
            <div class="col">
                <?= formErp::input('dataf', 'Data Final', date("Y-m-d"), null, null, 'date') ?>
            </div>
        </div>
        <br />
        <input type="hidden" name= "escola" id="escola" value="" />
        <input type="hidden" name="tipo" id="tipo" value =""/>
        <div class="row">
            <div class="col">
                <button onclick="sib('Fundamental', 'EF')"  style="width: 80%" type="button" class="btn btn-info">                
                    Visualizar Fundamental
                </button> 
            </div>
            <div class="col">
                <button onclick="sib('Infantil', 'EI')"  style="width: 80%" type="button" class="btn btn-info">                
                    Visualizar Infantil - Pré
                </button> 
            </div>
            <div class="col">
                <button onclick="sib('Infantil', 'EM')"  style="width: 80%" type="button" class="btn btn-info">                
                    Visualizar Infantil - Maternal
                </button> 
            </div>
            <div class="col">
                <button onclick="sib('EJA', 'EJ')"  style="width: 80%" type="button" class="btn btn-info">                
                    Visualizar Eja - AEE - Multisseriada
                </button> 
            </div>
        </div>
        <br />
    </form>
    <br />
</div>
<script>
    function sib(escola, tipo) {
        document.getElementById('escola').value = escola;
        document.getElementById('tipo').value = tipo;
        document.getElementById('form').submit();
    }
</script>
