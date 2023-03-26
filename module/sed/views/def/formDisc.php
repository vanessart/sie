<?php
if (!defined('ABSPATH'))
    exit;
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_NUMBER_INT);
if ($id_disc) {
    $disc = sql::get('ge_disciplinas', '*', ['id_disc' => $id_disc], 'fetch');
}
?>
<div class="body">
    <div class="fieldTop">
        Disciplinas
    </div>
    <form action="<?= HOME_URI ?>/sed/disc" target="_parent" method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::input('1[n_disc]', 'Disciplina', @$disc['n_disc']) ?>
            </div>
            <div class="col">
                <?= formErp::input('1[sg_disc]', 'Sigla', @$disc['sg_disc']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::selectDB('ge_areas', '1[fk_id_area]', 'Área', @$disc['fk_id_area']) ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 30px">
            <?=
            formErp::hidden([
                '1[id_disc]' => $id_disc,
                'activeNav' => 2
            ])
            . formErp::hiddenToken('ge_disciplinas', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
