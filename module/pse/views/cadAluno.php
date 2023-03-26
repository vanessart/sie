<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$sus = filter_input(INPUT_POST, 'sus', FILTER_SANITIZE_STRING);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
?>
<div class="body">
    <form id="formEnvia" target="_parent" action="<?= HOME_URI ?>/pse/avalOdonto" method="POST">
        <div class="row">
            <div class="col text-center">
                <?= formErp::input('1[sus]', 'Cartão SUS', $sus, ' required') ?>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col text-center">
                <?=
                formErp::hidden([
                    '1[id_pessoa]' => $id_pessoa,
                    'id_turma' => $id_turma,
                    'id_inst' => $id_inst,
                    'id_pl' => $id_pl,
                ])
                . formErp::hiddenToken('pessoa','ireplace')
                . formErp::button('Salvar');
                ?>            
            </div>
        </div>
    </form>
</div>