<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_STRING);
$ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_STRING);
$professores = $model->getProfSubstituido(null,$ano,null,$id_pessoa,1);
?>
<div class="body">
    <div class="fieldTop">
        Histórico de substituições - <?= $ano ?>
    </div>
    <br><br>
    <?php
    if(!empty($professores)){
        report::simple($professores);
    }?>
</div>