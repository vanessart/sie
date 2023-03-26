<?php
if (!defined('ABSPATH'))
    exit;
$id_protocolo = filter_input(INPUT_POST, 'id_protocolo', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_protocolo)) {
    $protocolo = sql::get('protocolo_cadastro', '*', ['id_protocolo' => $id_protocolo], 'fetch');
} else {
    $protocolo = null;
}
?>
<div class="body">
    <div class="fieldTop">
        <?= !empty($protocolo) ? 'Protocolo No '.$id_protocolo : 'Abrir Protocolo' ?>
    </div>
    <form action="<?= HOME_URI ?>/apd/protocolo" target="_parent" method="POST">
        <div class="row">
            <div class="col-3">
                <?= formErp::input(null, 'ID', $id_protocolo, ' disabled ') ?>
            </div>
            <div class="col-9">
                <?= formErp::input('1[n_protocolo]', 'Nome', empty($protocolo['n_protocolo'])?null:$protocolo['n_protocolo']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::input('1[dt_inicio]', 'Início', @$protocolo['dt_inicio'], null, null, 'date') ?>
            </div>
            <div class="col">
                <?= formErp::input('1[dt_fim]', 'Término', @$protocolo['dt_fim'], null, null, 'date') ?>
                '            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('1[at_protocolo]', [0 => 'Não', 1 => 'Sim'], 'Ativo', @$protocolo['at_protocolo']) ?>
            </div>
            <div class="col">
                <?= formErp::selectDB('protocolo_setor', '1[fk_id_setor]', 'Setor', @$protocolo['fk_id_setor']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[descr_protocolo]', @$protocolo['descr_protocolo'], 'Descrição') ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 10pz">

            <?=
            formErp::hidden([
                '1[id_protocolo]' => $id_protocolo
            ])
            . formErp::hiddenToken('protocolo_protocolo', 'ireplace')
            . formErp::button("Salvar")
            ?>
        </div>
    </form>
</div>
