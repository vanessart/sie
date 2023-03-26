<?php
if (!defined('ABSPATH'))
    exit;

$id = filter_input(INPUT_POST, 'id_item_ocorrencia', FILTER_SANITIZE_STRING);
$back = filter_input(INPUT_POST, 'back', FILTER_SANITIZE_STRING);
$areasResult = $model->getAreas();

$aAreas = [];
if (!empty($areasResult)) {
    foreach ($areasResult as $v) {
        $aAreas[$v['id_area']] = $v['n_area'];
    }
}

if(empty($id)) {
    $result = [
        'id_item_ocorrencia' => null,
        'fk_id_area' => 1,
        'n_item_ocorrencia' => null,
        'at_item_ocorrencia' => 1
    ];
}
else {
    $result = current($model->getItensOcorrencia($id));
}
?>

<div class="body">
    <form id="atr" method="POST">
        <div class="row" >
            <div class="col-md-12">
                <?= formErp::input('1[n_item_ocorrencia]', 'Descrição do item', $result['n_item_ocorrencia'], ' required') ?>
            </div>
        </div>
        <br>
        <div class="row" >
            <div class="col-md-4">
                <?= formErp::select('1[fk_id_area]', $aAreas, 'Área', $result['fk_id_area'], null, null, ' required'); ?>
            </div>
            <div class="col-md-4">
                <?= formErp::select('1[at_item_ocorrencia]', ['1' => 'Ativo', '0' => 'Inativo'], 'Status', $result['at_item_ocorrencia'], null, null, ' required') ?>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 text-center">
                <?=
                    formErp::hidden([
                        '1[id_item_ocorrencia]' => $result['id_item_ocorrencia'],
                        'back' => 1, //se for um insert, atualiza a pagina
                    ])
                    . formErp::hiddenToken('vis_item_ocorrencia','ireplace',null,null,1)
                    // formErp::button('Enviar',null,null,'btn btn-info')
                    // formErp::button('Cancelar',null,'history.back()','btn btn-danger');
                    . formErp::button('Salvar',null,null,'btn btn-success');
                ?>
            </div>
        </div>
    </form>
</div>

<script>
    function back(){
        parent.location.href = '<?= HOME_URI ?>/supervisor/itemOcorrenciaPesq';
    }
    <?php if (!empty($back)){ ?>
        back();
    <?php } ?>
</script>