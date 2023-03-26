<?php
if (!defined('ABSPATH'))
    exit;

$id_area = filter_input(INPUT_POST, 'id_area', FILTER_SANITIZE_STRING);
$back = filter_input(INPUT_POST, 'back', FILTER_SANITIZE_STRING);

if(empty($id_area)) {
    $result = ['id_area' => null, 'n_area' => null, 'at_area' => 1];
}
else {
    $result = current($model->getAreas($id_area));
}

?>

<div class="body">
    <form id="atr" method="POST">
        <div class="row" >
            <div class="col">
                <?= formErp::input('1[n_area]', 'Área', $result['n_area'], ' required') ?>
            </div>
        </div>
        <br>
        <div class="row" >
            <div class="col-md-3">
                <?= formErp::select('1[at_area]', ['1' => 'Ativo', '0' => 'Inativo'], 'Status', $result['at_area'], null, null, ' required') ?>
            </div>
        </div>
        <br><br><br>
        <div class="row" >
            <div class="col" style="text-align:center">
                <?php
                    echo formErp::hidden([
                        '1[id_area]' => $result['id_area'],
                        'back' => 1, //se for um insert, atualiza a pagina
                    ]);
                ?>                
                <?= formErp::hiddenToken('area','ireplace',null,null,1) ?>
                <?= formErp::button('Salvar',null,null,'btn btn-success'); ?>
            </div>
        </div>
    </form>
</div>

<script>
    function back(){
        parent.location.href = '<?= HOME_URI ?>/supervisor/areaResponsavelPesq';
    }
    <?php if (!empty($back)){ ?>
        back();
    <?php } ?>    
</script>