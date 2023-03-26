<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_cma = filter_input(INPUT_POST, 'id_cma', FILTER_SANITIZE_NUMBER_INT);
$acm = sql::get('lab_chrome_mov_adm', '*', ['id_cma' => $id_cma], 'fetch');
?>
<div class="body">
    <form action="<?= HOME_URI ?>/lab/movLog" target="_parent" method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[obs_adm]', @$acm['obs_adm'], 'Observações') ?>
            </div>
        </div>
        <br />
        <div style="text-align: center">
            <?=
            formErp::hidden([
                'id_inst' => $id_inst,
                '1[id_cma]' => $id_cma,
                '1[ativo]' => '0'
            ])
            . formErp::hiddenToken('lab_chrome_mov_adm', 'ireplace')
            . formErp::button('Finalizar')
            ?>
        </div>
    </form>

</div>
