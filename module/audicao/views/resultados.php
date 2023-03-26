<?php
if (!defined('ABSPATH'))
    exit;
$id_campanha = $model->campanha('id_campanha');
$n_campanha = $model->campanha();
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$gerente = $model->gerente();
$ciclos = $model->getCiclosModel($id_campanha,2);
if (empty($ciclos )) {
   echo toolErp::divAlert('warning','Não há ciclos configurados para resultados nesta campanha');
}else{
    $escolas = array();
    $instancias = ng_escolas::gets();
    if (!empty($instancias)) {
        foreach ($instancias as $v) {
            $escolas[$v['id_inst']] = $v['n_inst'];
        }
    }

    if (empty($id_inst)&&$gerente<>1) {
        $id_inst = toolErp::id_inst();
    }

    $turmas = $model->getTurmas(toolErp::id_pessoa(), $id_inst,2);
    $professor = $model->professor();
    if ($professor<>1) {
        $text = ' - Professores';
    }else{
        $text = '';
    }
    ?>
    <div class="body">
        <div class="fieldTop">
            Questionários - <?= $n_campanha ?>
        </div>
        <div class="row">
            <div class="col-4">
                <?= formErp::select('id_inst',$escolas,'Escolas',$id_inst,1,['id_inst' => $id_inst, 'id_turma' => $id_turma]) ?>
            </div>
            <div class="col-2">
                <?= formErp::select('id_turma',$turmas,'Turma',$id_turma,1,['id_inst' => $id_inst, 'id_turma' => $id_turma]) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col" style="text-align:right;">
                <button  class="btn btn-warning" style="width: 20px; height: 20px;"></button> Encaminhamento
                <button  class="btn btn-success" style="width: 20px; height: 20px;"></button> Questionário Falhou
                <button  class="btn btn-outline-info" style="width: 20px; height: 20px;"></button> Questionário Passou
            </div>
        </div>
        <br />
        <?php 
        
            $model->formRespostas($id_inst, $id_turma);
        ?>
    </div>
    <form id="formFrame" target="frame" action="" method="POST">
        <input type="hidden" id="id_pessoa" name="id_pessoa" value="" />
        <input type="hidden" id="id_turma_form" name="id_turma" value="<?= $id_turma  ?>" />
        <input type="hidden" id="id_campanha" name="id_campanha" value="<?= $id_campanha ?>" />
    </form>
    <form id="formPDF" target="_blank" action="" method="POST">
        <input type="hidden" id="id_pessoa_pdf" name="id_pessoa" value="" />
        <input type="hidden" id="id_campanha_pdf" name="id_campanha_pdf" value="<?= $id_campanha ?>" />
        <input type="hidden" id="id_turma" name="id_turma" value="" />
        <input type="hidden" id="qrCode" name="qrCode" value="" />
        <input type="hidden" id="resp" name="resp" value="" />
    </form>
    <?php 
    toolErp::modalInicio(null,'modal-md');
    ?>
    <iframe style=" width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
}?>
<script>
    function acesso(action,titulo,id_pessoa,n_pessoa) {
        document.getElementById('id_pessoa').value = id_pessoa;
        document.getElementById('formFrame').action = "<?= HOME_URI ?>/audicao/"+action;
        texto = '<div style="text-align: center; color: #7ed8f5;">'+n_pessoa+titulo+'</div>';
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function pdf(action,id_pessoa,id_turma,qrCode,resp) {
        document.getElementById('id_pessoa_pdf').value = id_pessoa;
        if (resp) {
            document.getElementById('resp').value = resp;
        } else {
            document.getElementById('resp').value = '';
        }
        if (id_turma) {
            document.getElementById('id_turma').value = id_turma;
        }
        if (qrCode) {
            document.getElementById('qrCode').value = qrCode;
        }else{
            document.getElementById('qrCode').value = '';  
        }
        document.getElementById('formPDF').action = "<?= HOME_URI ?>/audicao/"+action;
        document.getElementById('formPDF').submit();
    }
</script>