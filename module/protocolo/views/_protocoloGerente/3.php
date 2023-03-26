<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_status = filter_input(INPUT_POST, 'id_status', FILTER_SANITIZE_NUMBER_INT);
$id_status_novo = filter_input(INPUT_POST, 'id_status_novo', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_protocolo)) {
    $protocolo = $model->getProtocolo($id_protocolo);
    $id_status = $protocolo['fk_id_status'];

    if (!empty($protocolo['fk_id_inst_AEE'])) {
       $id_inst = $protocolo['fk_id_inst_AEE'];
    }
    if (!empty($protocolo['fk_id_turma_AEE'])) {
       $id_turma = $protocolo['fk_id_turma_AEE'];
    }
}
if ($id_status<>2 && $id_status<>1){
    $escolas = $model->idEscolas();

    if ($id_pessoa) {
        $aluno = $model->alunoAeeGet($id_pessoa);
    }
    if (!empty($id_inst)) {
       $turmas = $model->idTurmas($id_inst); 
    }
    $hidden['id_inst'] = $id_inst;
    $hidden['id_turma'] = $id_turma;
    $hidden['id_status'] = $id_status;
    $hidden['activeNav'] = 3;
    if (!empty($aluno)) {?>
        <div class="alert alert-info" style="padding-top:  10px; padding-bottom: 0">
            <div class="row" style="padding-bottom: 15px;">
                <div class="col">
                   <b>Alun<?php echo concord::oa($aluno['id_pessoa']) ?>:</b> <?= $aluno['n_pessoa'] ?>
                </div>
                <div class="col">
                  <b>RSE:</b> <?= $aluno['id_pessoa'] ?>
                </div>
            </div>
            <div class="row" style="padding-bottom: 15px;">
                <div class="col">
                   <b>Deficiência:</b> <?= $aluno['def'] ?>
                </div>
                <div class="col">
                   <b>Turma:</b> <?= $aluno['n_turma'] ?> - <?= $aluno['n_inst'] ?>
                </div>
            </div>
        </div>
        <?php
    }?>
    <br><br>
    <?php 
    $situacao = sql::get('protocolo_status', '*', " WHERE  at_proto_status = 1 AND id_proto_status IN (1,2,3)");?>
    <div class="row">
        <?php
        if(!empty($id_status_novo)){
           $id_status_botao =  $id_status_novo;
        }else{
            $id_status_botao =  $id_status;
        }
        foreach ($situacao as $k => $v){?>
            <div class="col text-center">
                <form id='form<?= $v['id_status'] ?>' method="POST">
                    <?= formErp::hidden($hidden);?>
                    <input type="hidden" id='id_status_novo' name="id_status_novo" value="$v['id_proto_status']">
                    <input data-just="<?= $v['id_proto_status'] ?>" class="justifica btn <?= ($id_status_botao==$v['id_proto_status'])?"btn-info":"btn-outline-info" ?>" type="button" value="<?= $v['n_status'] ?>" />
                </form>
            </div>
            <?php
        }?>
    </div>
    <br><br>
    <div class="viewDefere">
        <div><strong>INDICAR TURMA AEE</strong></div> 
        <br><br>
        <div class="row">
            <div class="col-5">
                <?php
                $hd = $hidden;
                $hd['id_status_novo']=1;
                echo formErp::select('id_inst', $escolas, 'Escolas', @$id_inst, 1, $hd) ?>
            </div>
            <div class="col-3">
                <?php
                 if (!empty($id_inst)) {
                    if (!empty($turmas)) {
                      echo formErp::select('id_turma', $turmas, 'Turmas', @$id_turma, 1, $hd, 'required'); 
                    }else{
                        toolErp::divAlert('warning','Verifique se há turmas AEE cadastradas nesta escola');
                    } 
                 }?>
            </div>
        </div>
        <br><br>
    </div>
    <form id="formProto" action="<?= HOME_URI ?>/apd/protocoloGerente" method="POST">
        <div class="row viewJustifica">
            <div><strong>JUSTIFICATIVA</strong></div> 
            <br><br>
            <div class="row">
                <div class="col">
                    <?= formErp::textarea('1[justifica]') ?>
                </div>
            </div>
        </div>
        <br><br>
        <input type="hidden" id='id_status' name="1[fk_id_proto_status]" value="<?= !empty($id_status_novo)?$id_status_novo : $id_status?>">
        <input type="hidden" id='fk_id_turma' name="1[fk_id_turma_AEE]" value="">
        <input type="hidden" id='fk_id_inst' name="1[fk_id_inst_AEE]" value="">
        <div class="row">
            <div class="col text-center">
                <?=
                formErp::hidden($hidden)
                .formErp::hidden([
                    '1[id_protocolo]' => $id_protocolo,
                ])
                . formErp::hiddenToken('protocoloAtualizar');
                ?> 
                <button id='botao' type="button" onclick="salvar()" class="btn btn-lg btn-success">Salvar</button>    
            </div>
        </div>  
    </form>
    <br><br>
    <?php
}else{
    if ($id_status==1) {
            toolErp::divAlert('success','PROTOCOLO DEFERIDO');
    }else if ($id_status==2) {
        toolErp::divAlert('danger','PROTOCOLO INDEFERIDO');
    }
}
$status = $model->historicoGet($id_protocolo);
if (!empty($status)) {
    report::simple($status);
}?>
<script type="text/javascript">
    $( document ).ready(function() {
        <?php 
        if ($id_status==1 || $id_status_novo==1 ){?>
            $('.viewJustifica').hide();
            $('.viewDefere').show();
            <?php
        }else{?>
            $('.viewJustifica').show();
            $('.viewDefere').hide();
            <?php
        }?>
        $('.justifica').click(function(){
            document.getElementById('id_status').value = $(this).data('just');
            if ($(this).data('just') == 1){
                $('.viewJustifica').hide();
                $('.viewDefere').show();
            } else {
                $('.viewJustifica').show();
                $('.viewDefere').hide();
            }
            $('.justifica').removeClass('btn-info btn-outline-info');
            $('.justifica').not(this).addClass('btn-outline-info');
            $(this).addClass('btn-info');
        });
    });
    function salvar(){
        const status = document.getElementById('id_status').value;
        if(status > 3){
            alert ('Escolha um deferimento');
            return;
        }
        <?php 
        if (!empty($id_turma)){?>
            document.getElementById('fk_id_turma').value = document.getElementById('id_turma_').value
            document.getElementById('fk_id_inst').value = document.getElementById('id_inst_').value
            <?php 
        }?>
        if (document.getElementById('id_status').value == 1){
            const select_inst = document.getElementById("id_inst_");
            if (select_inst.selectedIndex == '') {
                alert('Escolha um Escola');
                return;
            }
            <?php 
            if (!empty($id_inst)){?>
                const select_turma = document.getElementById("id_turma_");
                if (select_turma.selectedIndex == '') {
                    alert('Escolha uma Turma');
                    return;
                }
                <?php 
            }?>
        }
        document.getElementById('id_status_novo').value = null;
        document.getElementById('formProto').submit();
    }
</script>

