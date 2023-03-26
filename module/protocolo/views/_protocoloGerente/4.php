<?php
if (!defined('ABSPATH'))
    exit;
$id_status = filter_input(INPUT_POST, 'id_status', FILTER_SANITIZE_NUMBER_INT);
$id_status_novo = filter_input(INPUT_POST, 'id_status_novo', FILTER_SANITIZE_NUMBER_INT);

$escolas = $model->idEscolas();
if ($id_pessoa) {
    $aluno = $model->alunoAeeGet($id_pessoa);
}
if ($id_status<>1&&$id_status<>2&&$id_status&&3) {
   $id_status = 1; 
}
$hidden['id_status'] = $id_status;
$hidden['id_status_novo'] = $id_status_novo;
$hidden['activeNav'] = 4;

$cidPessoa = $model->cidGetPessoa($hidden); 
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
<div class="fieldTop">CIDs Associados a <?= $aluno['n_pessoa'] ?></div>
<button class="btn btn-info" onclick="cidAdd()">
       Novo CID
</button>
<br><br> 
<div class="row">
    <?php
    if (!empty($cidPessoa)) {
        report::simple($cidPessoa);
    }else{
        echo toolErp::divAlert('warning','Não há CID associado a esta pessoa');
    }?>
</div>
<br><br>  
<?php
toolErp::modalInicio();
?>  
<iframe style=" width: 100%; height: 50vh; border: none" name="frame"></iframe>
<?php
toolErp::modalFim();
?> 
<form id="formCid" target="frame" action="" method="POST">
    <?php foreach ($hidden as $key => $value) {
        echo '<input type="hidden" name="hidden['. $key .']" value="'. $value .'" >';
    } ?>
    
</form>
<script>
function cidAdd() {
    var titulo= document.getElementById('myModalLabel');
    titulo.innerHTML  = 'Associar CID a <?= $aluno['n_pessoa'] ?>';
    document.getElementById("formCid").action = '<?= HOME_URI ?>/protocolo/def/cidBusca.php';
    document.getElementById("formCid").submit();
    $('#myModal').modal('show');
    $('.form-class').val('');
}
</script>