<?php
if (!defined('ABSPATH'))
    exit;
$id_evento = filter_input(INPUT_POST, 'id_evento', FILTER_SANITIZE_NUMBER_INT);
if ($id_evento) {
    $eve = sql::get('inscr_evento', '*', ['id_evento' => $id_evento], 'fetch');
}
?>
<div class="body">
    <form action="<?= HOME_URI ?>/inscr/eventoSet" target="_parent" method="POST">
        <div class="row">
            <div class="col-2">
                <?= formErp::input(null, 'ID', $id_evento, ' desabled ') ?>
            </div>
            <div class="col-10">
                <?= formErp::input('1[n_evento]', 'Nome', @@$eve['n_evento'], ' required ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[descr_evento]', @$eve['descr_evento'], 'Descrição') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('1[at_evento]', ['Não', 'Sim'], 'Ativo', (empty($id_evento) ? 1 : @$eve['at_evento'])) ?>
            </div>
            <div class="col">
                <?= formErp::input('1[dt_inicio]', 'Início', @$eve['dt_inicio'], null, null, 'date') ?>
            </div>
            <div class="col">
                <?= formErp::input('1[dt_fim]', 'Termino', @$eve['dt_fim'], null, null, 'date') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('1[public]', ['Não', 'Sim'], 'Publicar Resultado', @$eve['public']) ?>
            </div>
            <div class="col">
                <?= formErp::select('1[entrega_online]', ['Não', 'Sim'], 'Abrir para Entrega Online', @$eve['entrega_online']) ?>
            </div>
            <!--
            <div class="col-9">
            <?= formErp::input('1[categoria]', 'Nome das categorias', @$eve['categoria'], null, 'Ex: Curso, Função etc') ?>
            </div>
            -->
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('1[recurso]', ['Não', 'Sim'], 'Abrir para Recursos', @$eve['recurso']) ?>
            </div>
            <div class="col">
                <?= formErp::select('1[recurso_ver]', ['Não', 'Sim'], 'Visualizar Recursos', @$eve['recurso_ver']) ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 10px">
            <?=
            formErp::hidden([
                '1[id_evento]' => $id_evento
            ])
            . formErp::hiddenToken('eventoSalvar')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
