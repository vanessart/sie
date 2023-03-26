<?php
if (!defined('ABSPATH'))
    exit;
$id_mc = filter_input(INPUT_POST, 'id_mc');
if ($id_mc) {
    $m = sql::get('profe_msg_coord', '*', ['id_mc' => $id_mc], 'fetch');
}
?>
<div class="body">
    <div class="fieldTop">
        Avisos para os Coordenadores
    </div>
    <form target="_parent" action="<?= HOME_URI ?>/profe/coordMsg" method="POST">
        <div class="row">
            <div class="col-3">
                <?= formErp::input(null, 'ID', @$m['id_mc'], 'desabled') ?>
            </div>
            <div class="col-9">
                <?= formErp::input('1[n_mc]', 'Título', @$m['n_mc']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[descr_mc]', @$m['descr_mc'], 'Texto', null, 1) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('1[tp_coord]', [12 => 'Todos', 1 => 'Fundamental', 2 => 'Infantil'], 'Segmento', empty($m['tp_coord']) ? 12 : $m['tp_coord']) ?>
            </div>
            <div class="col">
                <?= formErp::input('1[dt_inicio]', 'Início', empty($id_mc) ? date("Y-m-d") : $m['dt_inicio'], null, null, 'date') ?>
            </div>
            <div class="col">
                <?= formErp::input('1[dt_fim]', 'Término', empty($id_mc) ? date("Y-m-d") : $m['dt_fim'], null, null, 'date') ?>
            </div>
            <div class="col">
                <?= formErp::select('1[at_mc]', [1 => 'Sim', 0 => 'Não'], 'Ativo', @$m['at_mc']) ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 50px">
            <?=
            formErp::hidden([
                '1[id_mc]' => $id_mc,
                '1[fk_id_pessoa]' => toolErp::id_pessoa()
            ])
            . formErp::hiddenToken('profe_msg_coord', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>