<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_campanha = $model->campanha('id_campanha');
$n_campanha = $model->campanha();
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

    $liberar_aviso = $model->aviso();
    $turmas = $model->getTurmas(toolErp::id_pessoa(),$id_inst,2);
    $id_turma = $model->getId_turma($turmas,$id_turma);
    if (empty($liberar_aviso)) {
        echo toolErp::divAlert('warning','Não há Encaminhamentos ou Avisos para exibir');
    }else{
        ?>
        <div class="body">
            <div class="fieldTop">
                Emitir Encaminhamentos <br><br> <?= $n_campanha ?>
            </div>
            <div class="row">
                <?php 
                if ($gerente == 1) {?>
                    <div class="col-5">
                        <?= formErp::select('id_inst',$escolas,'Escolas',$id_inst,1,['id_inst' => $id_inst]) ?>
                    </div>
                    <?php 
                }?>
                <div class="col-3">
                    <?= formErp::select('id_turma',$turmas,'Turma',$id_turma,1,['id_inst' => $id_inst, 'id_turma' => $id_turma]) ?>
                </div>
                <?php 
                if ($gerente == 1) {?>
                    <div class="col-4">
                        <button onclick="pdf('encaminhamentoPedTodos',null,<?= $id_turma ?>,null,null)"   class="btn btn-outline-info">
                           Imprimir Todos os Encaminhamentos
                        </button>
                    </div>
                    <?php 
                }else{?>
                    <div class="col-4">
                        <button onclick="pdf('avisoTriagemTodos',null,<?= $id_turma ?>,null,null)"   class="btn btn-outline-info">
                           Imprimir Todos os Avisos de Triagem
                        </button>
                    </div>
                    <?php 
                }?>
            </div>
            <br />
            <?php 
            $model->formAvisos($id_turma);
            ?>
        </div>
        <form id="formPDF" target="_blank" action="" method="POST">
            <input type="hidden" id="id_pessoa" name="id_pessoa" value="" />
            <input type="hidden" id="id_turma" name="id_turma" value="" />
        </form>
        <?php 
        toolErp::modalInicio(null,'modal-md');
        ?>
        <iframe style=" width: 100%; height: 80vh; border: none" name="frame"></iframe>
        <?php
        toolErp::modalFim();
    }
}?>
<script>
    function pdf(action,id_pessoa,id_turma,qrCode,resp) {
        document.getElementById('id_pessoa').value = id_pessoa;
        if (id_turma) {
            document.getElementById('id_turma').value = id_turma;
        }
        document.getElementById('formPDF').action = "<?= HOME_URI ?>/audicao/"+action;
        document.getElementById('formPDF').submit();
    }
</script>