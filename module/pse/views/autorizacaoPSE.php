<?php
if (!defined('ABSPATH'))
    exit;
$n_campanha = $model->campanha();
$id_pl = $model->campanha('id_pl');
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$turmas = $model->getTurmas(toolErp::id_pessoa());
$id_turma = $model->getId_turma($turmas,$id_turma);
$professor = $model->professor();
if ($professor<>1) {
    $text = ' - Professores';
}else{
    $text = '';
}
?>
<div class="body">
    <div class="fieldTop">
        Autorização de Participação nas Atividades Programa Saúde na Escola <br> <br>PSE - <?= $n_campanha ?>
    </div>
    <div class="row">
        <div class="col-2">
            <?= formErp::select('id_turma',$turmas,'Turma',$id_turma,1) ?>
        </div>
        <?php if ($professor<>1) {?>
            <div class="col-2">
                <button onclick="pdf('autorizacaoPSETodosPDF',null,<?= $id_turma ?>,1,2)"   class="btn btn-outline-info">
                   Imprimir Não Respondidos <br> (Somente QrCode)
                </button>
            </div>
            <div class="col-2">
                <button onclick="pdf('autorizacaoPSETodosPDF',null,<?= $id_turma ?>,null,2)"   class="btn btn-outline-info">
                   Imprimir Não Respondidos
                </button>
            </div>
            <div class="col-2">
                <button onclick="pdf('autorizacaoPSETodosPDF',null,<?= $id_turma ?>,null,1)"   class="btn btn-outline-info">
                   Imprimir Respondidos
                </button>
            </div>
            <?php
        }else{?>
            <div class="col-6">
                
            </div>
            <?php
        }?>
    </div>
    <br />
    <div class="row">
        <div class="col" style="text-align:right;">
            <button  class="btn btn-success" style="width: 20px; height: 20px;"></button> Respondido
            <button  class="btn btn-outline-info" style="width: 20px; height: 20px;"></button> Não Respondido
        </div>
    </div>
    <br />
    <?php 
    $model->autorizacaoPSE($id_turma);
    ?>
</div>
<form id="formFrame" target="frame" action="" method="POST">
    <input type="hidden" id="id_pessoa" name="id_pessoa" value="" />
    <input type="hidden" id="id_turma_form" name="id_turma" value="<?= $id_turma  ?>" />
    <input type="hidden" id="id_pl" name="id_pl" value="<?= $id_pl ?>" />
    <input type="hidden" id="n_campanha" name="n_campanha" value="<?= $n_campanha ?>" />
</form>
<form id="formPDF" target="_blank" action="" method="POST">
    <input type="hidden" id="id_pessoa_pdf" name="id_pessoa" value="" />
    <input type="hidden" id="id_pl" name="id_pl" value="<?= $id_pl ?>" />
    <input type="hidden" id="id_turma" name="id_turma" value="" />
    <input type="hidden" id="qrCode" name="qrCode" value="" />
    <input type="hidden" id="resp" name="resp" value="" />
    <input type="hidden" id="n_campanha" name="n_campanha" value="<?= $n_campanha ?>" />
</form>
<?php 
toolErp::modalInicio(null,'modal-md');
?>
<iframe style=" width: 100%; height: 80vh; border: none" name="frame"></iframe>
<?php
toolErp::modalFim();
?>
<script>
    function acesso(action,titulo,id_pessoa,n_pessoa) {
        document.getElementById('id_pessoa').value = id_pessoa;
        document.getElementById('formFrame').action = "<?= HOME_URI ?>/pse/"+action;
        texto = '<div style="text-align: center; color: #7ed8f5;">'+n_pessoa+titulo+'</div>';
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function pdf(action,id_pessoa,id_turma,qrCode,resp) {
        document.getElementById('id_pessoa_pdf').value = id_pessoa;
        document.getElementById('resp').value = resp;
        
        if (id_turma) {
            document.getElementById('id_turma').value = id_turma;
        }
        if (qrCode) {
            document.getElementById('qrCode').value = qrCode;
        }else{
            document.getElementById('qrCode').value = '';  
        }
        document.getElementById('formPDF').action = "<?= HOME_URI ?>/pse/"+action;
        document.getElementById('formPDF').submit();
    }
</script>