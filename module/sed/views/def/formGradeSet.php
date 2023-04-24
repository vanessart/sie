<?php
if (!defined('ABSPATH'))
    exit;
$id_grade = filter_input(INPUT_POST, 'id_grade', FILTER_SANITIZE_NUMBER_INT);
if ($id_grade) {
    $grade = sql::get('ge_grades', '*', ['id_grade' => $id_grade], 'fetch');
}
?>
<div class="body">
    <div class="fieldTop">
        Grade
    </div>
    <form action="<?= HOME_URI ?>/sed/disc" target="_parent" method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::input('1[n_grade]', 'Nome da Grade', @$grade['n_grade']) ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 30px">
            <?=
            formErp::hidden([
                '1[id_grade]' => $id_grade,
                '1[fk_id_ta]' => 1,
                '1[ativo]' => 1,
                'activeNav' => 3
            ])
            . formErp::hiddenToken('ge_grades', 'ireplace', null, null, null, 1)
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
