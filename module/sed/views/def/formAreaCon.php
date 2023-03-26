<?php
if (!defined('ABSPATH'))
    exit;
$id_area = filter_input(INPUT_POST, 'id_area', FILTER_SANITIZE_NUMBER_INT);
if ($id_area) {
    $area = sql::get('ge_areas', '*', ['id_area' => $id_area], 'fetch');
}
?>
<div class="body">
    <div class="fieldTop">
        Área de Conhecimento
    </div>
    <form action="<?= HOME_URI ?>/sed/disc" target="_parent" method="POST">
        <div class="row">
            <div class="col-8">
                <?= formErp::input('1[n_area]', 'Área', @$area['n_area']) ?>
            </div>
            <div class="col-4">
                <?= formErp::input('1[sg_area]', 'Sigla', @$area['sg_area']) ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 30px">
            <?=
            formErp::hidden([
                '1[id_area]' => $id_area
            ])
            . formErp::hiddenToken('ge_areas', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
