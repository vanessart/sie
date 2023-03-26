<?php
if (!defined('ABSPATH'))
    exit;

$n_campanha = $model->campanha();
$id_pl = $model->campanha('id_pl');
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
//$id_turma = $model->getId_turma($turmas,$id_turma);

if (!empty($id_inst)) {
    $turmas = $model->getTurmas(toolErp::id_pessoa(),$id_inst); 
}

if (!empty($id_turma)) {
    $alunos = $model->getAlunosOdonto($id_turma,$id_pl); 
}
$escolas = array();
$instancias = ng_escolas::gets();
if (!empty($instancias)) {
    foreach ($instancias as $v) {
        $escolas[$v['id_inst']] = $v['n_inst'];
    }
}
?>
<style type="text/css">
    .table>thead>tr>th{
        white-space: pre-line !important;
    }
</style>
<div class="body">
    <br><br>
    <div class="row">
        <div class="col" style="font-weight:bold; font-size:20px; text-align: center;">
            Saúde Bucal - PSE - <?= $n_campanha ?>
            <br><br>
        </div>
    </div>
    <div class="row">
        <div class="col-5">
            <?= formErp::select('id_inst',$escolas,'Escolas',$id_inst,1,['id_inst' => $id_inst]) ?>
        </div>
        <?php 
        if (!empty($id_inst)) {?>
            <div class="col-2">
                <?= formErp::select('id_turma',$turmas,'Turma',$id_turma,1,['id_inst' => $id_inst]) ?>
            </div>
           <?php  
        }
        ?>
    </div>
    <br />
    <br><br><br><br>
    <?php 
    if (!empty($alunos) && !empty($id_turma)) {
        report::simple($alunos);
    }else{
        echo toolErp::divAlert('warning','Escolha uma turma');
    }
    toolErp::modalInicio();
    ?>
    <iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
</div>
<form id="formIn" target="frame" action="" method="POST">
    <input type="hidden" name="id_pessoa" id="id_pessoa" value="">
    <input type="hidden" name="sus" id="sus" value="">
    <?=
    formErp::hidden([
        'id_inst' => $id_inst,
        'id_turma' => $id_turma,
        'id_pl' => $id_pl,
    ]);
    ?>   
</form>
<form id="export" target="_blank" action="<?= HOME_URI ?>/pse/excelAlunos" method="POST">
    <?=
    formErp::hidden([
        'id_inst' => $id_inst,
        'id_turma' => $id_turma,
        'id_pl' => $id_pl,
    ]);
    ?>  
</form>
<script>
    function cadOdonto(){
        //pega o nome da escola selecionada
        const selectElementInst = document.getElementById("id_inst_");
        const selectedOptionInst = selectElementInst.selectedIndex;
        const escola = selectElementInst.options[selectedOptionInst].text;
        
        //pega o nome da turma selecionada
        const selectElementTurma = document.getElementById("id_turma_");
        const selectedOptionTurma = selectElementTurma.selectedIndex;
        const turma = selectElementTurma.options[selectedOptionTurma].text;

        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = turma+' - '+escola;
        document.getElementById("formIn").action = '<?= HOME_URI ?>/pse/cadOdonto';
        document.getElementById("formIn").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function cadAluno(id_pessoa,n_pessoa,sus){
        var titulo= document.getElementById('myModalLabel');
        document.getElementById("id_pessoa").value = id_pessoa;
        document.getElementById("sus").value = sus;
        titulo.innerHTML = n_pessoa;
        document.getElementById("formIn").action = '<?= HOME_URI ?>/pse/cadAluno';
        document.getElementById("formIn").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>
