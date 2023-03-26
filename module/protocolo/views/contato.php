<?php
if (!defined('ABSPATH'))
    exit;
$id_protocolo = filter_input(INPUT_POST, 'id_protocolo', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
if (toolErp::id_nilvel()==24) {
    $tel = $model->getTel($id_pessoa);
    $fone1 = $tel[0]['tel1'];
    $fone2 = $tel[0]['tel2'];
    $fone3 = $tel[0]['tel3'];

    $semContato = (empty($fone1) && empty($fone2) && empty($fone3)) ? "Não existem contatos cadastrados" : "";
    ?>
    <div class="body" style="overflow-x: hidden;">
        <form id="atr" method="POST">
            <div><strong>TELEFONES</strong></div>
            <br>
            <div class="card p-4">
            <?php 
            if (!empty($semContato)) {
                echo $semContato;
            } else {
            if (isset($fone1)) { ?>
                <div class="row">
                    <div class="col">
                        <?= formErp::input('1[tel1Obs]', $fone1, null, null, 'Obs:') ?>
                    </div>
                </div>
                <br>
            <?php } ?>
            <?php if (isset($fone2)) { ?>
                <div class="row">
                    <div class="col">
                        <?= formErp::input('1[tel2Obs]', $fone2, null, null, 'Obs:') ?>
                    </div>
                </div>
                <br>
            <?php } ?>
            <?php if (isset($fone3)) { ?>
                <div class="row">
                    <div class="col">
                        <?= formErp::input('1[tel3Obs]', $fone3, null, null, 'Obs:') ?>
                    </div>
                </div>
                <br>
            <?php } 
            } ?>
            </div>
            <br>
            <div class="row">
                <div class="col">
                    <?php formErp::textarea('1[obs]', NULL, 'Obs:', '30px') ?>
                </div>
            </div>
            <br>
            <div class="row">
                <input type="hidden" name="1[fk_id_protocolo_status]" id="id_protocolo_status" value="" />
                
                <?php
                echo formErp::hidden([
                    '1[fk_id_protocolo]' => $id_protocolo,
                    '1[fk_id_pessoa]' => $id_pessoa,
                    '1[fk_id_pessoa_cadastra]' => toolErp::id_pessoa(),
                    '1[tel1]' => $fone1,
                    '1[tel2]' => $fone2,
                    '1[tel3]' => $fone3,
                    'id_pessoa' => $id_pessoa,
                    'id_protocolo' => $id_protocolo,
                ]);?>
                <?= formErp::hiddenToken('protocolo_contactar', 'ireplace') ?>
                 <div class="col text-center">
                    <input onclick="salvar(6)" class="btn btn-outline-info" type="button" value="Não foi possível Contatar" />
                </div>
                <div class="col text-center">
                    <input onclick="salvar(7)" class="btn btn-outline-info" type="button" value="Contatado" />
                </div>
            </div>
        </form>
    </div>
    <br><br>
    <?php 
}
$historico = $model->getHistoricoContato($id_protocolo);
if (!empty($historico)) {
    report::simple($historico);
}else{
    echo toolErp::divAlert('warning','Sem Informações');
}?>
<script>
    function salvar(id_protocolo_status) {
        /*if (window.parent) {
            setTimeout(function(){
                window.parent.location.reload(true);
            }, 300);
        }*/
        document.getElementById("id_protocolo_status").value = id_protocolo_status;
        document.getElementById('atr').submit();
    }
</script>