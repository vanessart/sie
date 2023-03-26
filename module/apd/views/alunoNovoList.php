<?php
if (!defined('ABSPATH'))
    exit;

$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$btn = filter_input(INPUT_POST, 'btn', FILTER_SANITIZE_NUMBER_INT);
if (empty($btn)) {
    $btn = 1;
}

$btns = [
    1 => "Alunos Encaminhados para Atendimento AEE",
    2 => "Alunos com Termos Aceitos",
    3 => "Alunos com Termos Recusados",
];

switch ($btn) {
    case 1:
        #PROTOCOLO
        $protocolos = $model->protocoloGet(1,toolErp::id_inst(),1,1,$id_turma);//1=AEE
        break;

    case 2:
        #TERMO DE ACEITE
        $protocolos = $model->formTermos(toolErp::id_inst(),'A',$id_turma);
        break;

    case 3:
        #TERMO DE RECUSA
        $protocolos = $model->formTermos(toolErp::id_inst(),'R',$id_turma);
        break;
}

$turmas = $model->getTurmasProf();
$id_turma = $model->getTurmaSelect($turmas, $id_turma);
?>
<div class="body">
    <form method="POST" id="formBtn">
        <div class="row fieldTop">
            <?php foreach ($btns as $k => $v) { ?>
                <div class="col-4">
                    <input class="btn btn-<?php if ($btn != $k) { ?>outline-<?php } ?>info botoes" type="button" data-val="<?php echo $k ?>" value="<?php echo $v ?>" />
                </div>
            <?php } ?>
            <input type="hidden" id='btn' name="btn" value="<?php echo $btn ?>">
        </div>
        <br />

    </form>

    <div class="fieldTop">
        <?php echo $btns[$btn] ?>
    </div>

    <?php 
    if (!empty($turmas) && count($turmas) > 1) { 
        foreach ($turmas as $v){
            if (toolErp::id_nilvel()==24) {
                $n_turmas[$v['id_turma']]= $v['n_inst'].' - '.$v['n_turma'];
            }else{
                $n_turmas[$v['id_turma']]= $v['n_turma'];
            }
        }?>
        <div class="row">
            <div class="col">
                <?= formErp::select('id_turma', $n_turmas, 'Turmas', $id_turma, 1, ["id_turma" => $id_turma, "btn" => $btn]) ?>
            </div>
        </div>
        <br>
        <?php 
    }
    if (!empty($protocolos)) {
        report::simple($protocolos);
    }else{
        toolErp::divAlert('warning','Não há Protocolos a serem exibidos');
    } ?>
</div>
<form id="form" target="frame" action="" method="POST">
    <input type="hidden" name="id_pessoa" id="id_pessoa" value="" />
    <input type="hidden" name="id_protocolo" id="id_protocolo" value="" />
    <input type="hidden" name="n_pessoa" id="n_pessoa" value="" />
    <input type="hidden" name="n_turma" id="n_turma" value="" />
    <input type="hidden" name="id_turma_AEE" id="id_turma_AEE" value="<?= @$id_turma ?>" />
    <input type="hidden" name="id_turma" id="id_turma" value="" />
    <input type="hidden" name="id_entre" id="id_entre" value="" />
    <input type="hidden" name="action" id="action" value="AlunoNovoList" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
<?php
toolErp::modalFim();
?>
<script>
    function contactaALuno(id,n_pessoa,id_protocolo){
        if (id){
            document.getElementById("id_pessoa").value = id;
            document.getElementById("id_turma").value = id_turma;
            document.getElementById("id_protocolo").value = id_protocolo;
            document.getElementById("n_pessoa").value = n_pessoa;
            var titulo= document.getElementById('myModalLabel');
            titulo.innerHTML  = n_pessoa;
        }
        document.getElementById("form").action = '<?= HOME_URI ?>/apd/contato';
        document.getElementById("form").target = 'frame';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
        $('#myModal').on('hidden.bs.modal', function () {
            location.reload();
        });
    }
    function entr(id,n_pessoa,id_turma,id_entre){
        if (id){
            document.getElementById("id_pessoa").value = id;
            document.getElementById("id_turma").value = id_turma;
            document.getElementById("id_entre").value = id_entre;
            document.getElementById("n_pessoa").value = n_pessoa;
            var titulo= document.getElementById('myModalLabel');
            titulo.innerHTML  = n_pessoa;
        }
        document.getElementById("form").action = '<?= HOME_URI ?>/apd/entrevista';
        document.getElementById("form").target = 'frame';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
        $( "body" ).off( "click", "#myModal" );
    }
    function termo(id_protocolo,id){
        if (id){
            document.getElementById("id_protocolo").value = id_protocolo;
            document.getElementById("id_pessoa").value = id;
        }
        document.getElementById("form").action = '<?= HOME_URI ?>/apd/termoAceite';
        document.getElementById("form").target = '';
        document.getElementById("form").submit();
    }
    function recusa(id_protocolo,id){
        if (id){
            document.getElementById("id_protocolo").value = id_protocolo;
            document.getElementById("id_pessoa").value = id;
        }
        document.getElementById("form").action = '<?= HOME_URI ?>/apd/termoRecusa';
        document.getElementById("form").target = '';
        document.getElementById("form").submit();
    }

    $( document ).ready(function() {
        $('.botoes').click(function(){
            $("#btn").val( $(this).data('val') );
            $("#formBtn").submit();
        });
    });
</script>