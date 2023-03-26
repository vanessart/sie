<?php
if (!defined('ABSPATH'))
    exit;
$id_grade = filter_input(INPUT_POST, 'id_grade', FILTER_SANITIZE_NUMBER_INT);
$id_aloca = filter_input(INPUT_POST, 'id_aloca', FILTER_SANITIZE_NUMBER_INT);
if ($id_aloca) {
    $aloca = sql::get('ge_aloca_disc', '*', ['fk_id_grade' => $id_grade, 'id_aloca' => $id_aloca], 'fetch');
}
?>
<div class="body">
    <div class="fieldTop">
        Alocação de Disciplinas
    </div>
    <form action="<?= HOME_URI ?>/sed/disc" target="_parent" method="POST">
        <div class="row">
            <div class="col-8">
                <?= formErp::selectDB('ge_disciplinas', '1[fk_id_disc]', 'Disciplina', @$aloca['fk_id_disc']) ?>
            </div>
            <div class="col-4">
                <?= formErp::selectNum('1[aulas]', [1, 100], 'Aulas', @$aloca['aulas']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::selectNum('1[ordem]', [1, 100], 'Ordem', @$aloca['ordem']) ?>
            </div>
            <div class="col">
                <?= formErp::checkbox('1[nucleo_comum]', 1, 'Núcleo Comum', @$aloca['nucleo_comum']) ?>
            </div>
            <div class="col">
                <?= formErp::selectDB('ge_aloca_disc_base', '1[fk_id_adb]', 'Base', @$aloca['fk_id_adb']) ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 30px">
            <?=
            formErp::hidden([
                '1[fk_id_grade]' => $id_grade,
                'id_grade' => $id_grade,
                '1[id_aloca]' => $id_aloca,
                'activeNav' => 4
            ])
            . formErp::hiddenToken('ge_aloca_disc', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
