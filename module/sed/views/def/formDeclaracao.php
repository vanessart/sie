<?php
$id_evento = filter_input(INPUT_POST, 'id_evento', FILTER_SANITIZE_NUMBER_INT);
if ($id_evento) {
    $evento = sqlErp::get('ge_eventos', '*', ['id_evento' => $id_evento], "fetch");
}
?>
<div class="body">
    <div class="fieldTop">
        Cadastro - Convocações, Comunicados e Eventos
    </div>
    <form method="POST" action="<?= HOME_URI ?>/sed/convocacao" target="_parent">
        <div class="row">
            <div class="col">
                <?= formErp::input('1[evento]', 'Evento ou Assunto', @$evento['evento'], 'required') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::input('1[dt_evento]', 'Data', @$evento['dt_evento'], null, null, 'date') ?>
            </div>
            <div class="col">
                <?= formErp::input('1[h_inicio]', 'Hora Início', @$evento['h_inicio'], null, null, 'time') ?>
            </div>
            <div class="col">
                <?= formErp::input('1[h_final]', 'Hora Final', @$evento['h_final'], null, null, 'time') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::input('1[local_evento]', 'Local Evento', @$evento['local_evento']) ?>
            </div>
            <div class="col">
                <?= formErp::input('1[ev_resp]', 'Responsável pelo Evento', @$evento['ev_resp']) ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 30px">
            <?=
            formErp::hiddenToken('ge_eventos', 'ireplace')
            . formErp::hidden([
                '1[id_evento]' => $id_evento,
                '1[fk_id_inst]' => toolErp::id_inst()
            ])
            . formErp::button('Salvar')
            ?>
        </div>

    </form>
</div>