<?php
if (!defined('ABSPATH'))
    exit;

$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_STRING);
$back = filter_input(INPUT_POST, 'back', FILTER_SANITIZE_STRING);

$tipoResult = $model->getTipoInstancia();
$aTipo = [];
if (!empty($tipoResult)) {
    foreach ($tipoResult as $v) {
        $aTipo[$v['id_tp']] = $v['n_tp'];
    }
    asort($aTipo);
}

if(empty($id_inst)) {
    $result = [
        'id_inst' => null,
        'n_inst' => null,
        'at_inst' => 1,
        'fk_id_tp' => null,
        'email' => null,
    ];
}
else {
    $result = current($model->getEscolaParticular($id_inst));
}

?>

<div class="body">
    <form id="atr" method="POST">
        <div class="row" >
            <div class="col">
                <?= formErp::input('1[n_inst]', 'Nome da instituição', $result['n_inst'], ' required') ?>
            </div>
        </div>
        <br>
        <div class="row" >
            <div class="col">
                <?= formErp::input('1[email]', 'E-Mail', $result['email'], null, null, 'email') ?>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-3">
                <?= formErp::select('1[fk_id_tp]', $aTipo, 'Tipo', $result['fk_id_tp'], null, null, ' required'); ?>
            </div>
            <div class="col-md-3">
                <?= formErp::select('1[at_inst]', ['1' => 'Ativo', '0' => 'Inativo'], 'Status', $result['at_inst'], null, null, ' required') ?>
            </div>
        </div>
        <br><br><br>
        <div class="row" >
            <div class="col" style="text-align:center;">
                <?=
                    formErp::hidden([
                        '1[id_inst]' => $result['id_inst'],
                        'back' => 1, //se for um insert, atualiza a pagina
                    ])
                    . formErp::hiddenToken('instancia_particular','ireplace',null,null,1)
                    . formErp::button('Salvar',null,null,'btn btn-success');
                ?>
            </div>
        </div>
    </form>
</div>

<script>
    function back(){
        parent.location.href = '<?= HOME_URI ?>/supervisor/escolaParticularPesq';
    }
    <?php if (!empty($back)){ ?>
        back();
    <?php } ?>
</script>