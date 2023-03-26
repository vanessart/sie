<?php 
$id_serial = filter_input(INPUT_POST, 'id_serial', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_local = filter_input(INPUT_POST, 'id_local', FILTER_SANITIZE_NUMBER_INT);
$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
$local = $model->getLocal($id_inst);
$gerente = $model->gerente();
$escola = $model->getInst();
//$escola = escolas::idEscolas();

$situacao = sql::get('recurso_serial', 'fk_id_situacao', ['id_serial' =>$id_serial], 'fetch');
if ($situacao['fk_id_situacao']==1) {
    $regular = 1;
}else{
    $regular = 0;
}
if (empty($id_local)) {
   $id_local = filter_input(INPUT_POST, '1[fk_id_local]', FILTER_SANITIZE_NUMBER_INT); 
   if (empty($id_local)) {
       $id_local = -1;
   }
}
$hidden = [
    'id_inst' => $id_inst,
];
if (!empty($id_serial)) {

    if (!empty($id_pessoa)) {
        $historico = $model->historicoGet($id_pessoa,null);
    }else{
        $historico = $model->historicoGet(null,$id_serial);   
    }?>
    <form id='formEnvia' target="_parent" method="POST" action="<?= HOME_URI ?>/recurso/<?= $action ?>">
        <div class="row">
            <?php if ($gerente == 1 && $regular == 1) {?>
                <div class="col">
                    <?= formErp::select('1[fk_id_inst]', $escola, 'Escola', $id_inst,null,null,'onchange="local()" required') ?>
                </div>
            <?php } ?>
            <div class="col" id="colLocal">
                <?= formErp::select('1[fk_id_local]', $local, 'Local', $id_local, null, null, 'required ') ?>
            </div>
            <div class="col">
                <?=
                formErp::hidden($hidden)
                .formErp::hidden([
                    '1[fk_id_pessoa_aloca]' => toolErp::id_pessoa(),
                    '1[dt_update]' => date("Y-m-d"),
                    '1[id_serial]' => $id_serial,
                ])
                .formErp::hiddenToken('recurso_serial','ireplace')
                .formErp::button('Alocar');?>
            </div>
        </div>
    </form>
    <?php
    if (!empty($historico)) {?>
        <br><br>
        <div class="fieldTop">
            Histórico de Movimentações
        </div>
        <?php
        report::simple($historico);
    }else{?>
        <br><br>
         <div class="alert alert-warning" style="padding-top:  10px; padding-bottom: 0">
            <div class="row" style="padding-bottom: 15px;">
                <div class="col" style="font-weight: bold; text-align: center;">
                    Não há movimentações para este Equipamento.
                </div>
            </div>
        </div>
        <?php
    }
}?>
<script type="text/javascript">
    function local(){
        var col = document.getElementById('colLocal');
        col.style.display = "none";
        document.getElementsByName('1[fk_id_local]').values = -1;
    }
</script>