<?php
if (!defined('ABSPATH'))
    exit;
$id_modem = filter_input(INPUT_POST, 'id_modem', FILTER_SANITIZE_NUMBER_INT);
if ($id_modem) {
    $modem = sql::get('lab_modem', '*', ['id_modem' => $id_modem], 'fetch');
} else {
    $modem = null;
}
$escola = $model->escolasOpt();
?>
<div class="body">
    <div class="fieldTop">
        Cadastro de Modem
    </div>
    <br />
    <form action="<?= HOME_URI ?>/lab/modem" target="_parent" method="POST">
        <div class="row">
            <div class="col-3">
                <?= formErp::input(null, 'ID', $id_modem, ' readonly ') ?>
            </div>
            <div class="col-9">
                <?= formErp::input('1[Serial]', 'N/S', @$modem['serial'], ' required  style="text-transform:uppercase;" ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('1[at_modem]', [1 => 'Sim', 0 => 'Não'], 'Ativo', empty($modem)?1:$modem['at_modem']) ?>
            </div>
            <div class="col">
                <?= formErp::selectDB('lab_modem_modelo', '1[fk_id_mm]', 'Modelo', @$modem['fk_id_mm']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('1[fk_id_inst]', $escola, 'Escola', @$modem['fk_id_inst']); ?>
            </div>
        </div>
        <br /><br /><br />
        <div style="text-align: center">
            <?=
            formErp::hidden([
            '1[id_modem]' => $id_modem
            ])
            .formErp::hiddenToken('lab_modem', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
