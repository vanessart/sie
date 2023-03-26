<?php
if (!defined('ABSPATH'))
    exit;
$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_STRING);
$ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_STRING);
$iddisc = filter_input(INPUT_POST, 'iddisc', FILTER_SANITIZE_NUMBER_INT);

if (empty($mes)) {
    $mes = date("m");
}

if (empty($ano)) {
    $ano = date("Y");
}

$anos = $model->getAnosProtocolos();
$disc_ = $model->getDisc();
$professores = $model->getProfSubstituido($mes,$ano,$iddisc);
$hidden = [
    'iddisc' => $iddisc,
    'mes' => $mes,
    'ano' => $ano
];
?>
<div class="body">
    <div class="fieldTop">
        Professores Substítuídos através do CADAMPE
    </div>
    <br><br>
    <div class="row">
        <div class="col-md-2">
            <?= formErp::select('ano', $anos, 'Ano', @$ano, 1, $hidden, ' required ') ?>
        </div>
        <div class="col-md-2">
            <?= formErp::select('mes', data::meses(), 'Mês', @$mes, 1, $hidden, ' required ') ?>
        </div>
        <div class="col-md-6">
            <?= formErp::select('iddisc', $disc_, ['Disciplina', 'Todas'], @$iddisc, 1, $hidden); ?>
        </div> 
    </div>      
    <br> 
    <?php
    if(!empty($professores)){
        report::simple($professores);
    }?>
    <form id="form" target="frame" action="" method="POST">
        <input type="hidden" name="id_pessoa" id="id_pessoa" value="" />
        <input type="hidden" name="ano" id="ano" value="" />
    </form>
    <?php
    toolErp::modalInicio();
    ?>
    <iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
</div>

<script>
    function his(id_pessoa,ano,n_pessoa){
        document.getElementById("id_pessoa").value = id_pessoa;
        document.getElementById("ano").value = ano;
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "<b>"+n_pessoa+"</b";
        
        document.getElementById("form").action = '<?= HOME_URI ?>/cadampe/historicoProf';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>