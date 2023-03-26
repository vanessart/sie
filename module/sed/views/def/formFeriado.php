<?php
if (!defined('ABSPATH'))
    exit;
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_feriado = filter_input(INPUT_POST, 'id_feriado', FILTER_SANITIZE_NUMBER_INT);
if ($id_feriado) {
    $feriado = sql::get('sed_feriados', '*', ['id_feriado' => $id_feriado], 'fetch');
}
?>
<div class="body">
    <form action="<?= HOME_URI ?>/sed/feriado" target="_parent" method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::input('1[n_feriado]', 'Evento', @$feriado['n_feriado']) ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col">
                <?= formErp::input('1[dt_feriado]', 'Data', @$feriado['dt_feriado'], ' required ', null, 'date') ?>
            </div>
            <div class="col text-center">
                <?=
                formErp::hidden([
                    '1[id_feriado]' => $id_feriado,
                    'id_curso' => $id_curso,
                    'id_pl' => $id_pl,
                    '1[fk_id_curso]' => $id_curso,
                    '1[fk_id_pl]' => $id_pl
                ])
                . formErp::hiddenToken('sed_feriados', 'ireplace')
                . formErp::button('Salvar')
                ?>
            </div>
        </div>
        <br />
    </form>
</div>
