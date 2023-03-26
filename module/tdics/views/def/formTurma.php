<?php
if (!defined('ABSPATH'))
    exit;
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$cursos = sql::idNome('tdics_curso');
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);

$pls = sql::idNome('tdics_pl');

if ($id_polo && $id_turma) {
    $t = sql::get('tdics_turma', '*', ['id_turma' => $id_turma], 'fetch');
}
$diaSem = $model->diaSemana();
?>
<div class="body">
    <div class="fieldTop">
        Cadastro de Turma
        <br /><br />
        <?= sql::get('tdics_polo', 'n_polo', ['id_polo' => $id_polo], 'fetch')['n_polo'] ?> - <?= $pls[$id_pl] ?>
    </div>
    <form action="<?= HOME_URI ?>/tdics/turmaCad" target="_parent" method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::select('1[dia_sem]', $diaSem, 'Dia da Semana', @$t['dia_sem']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('1[fk_id_curso]', $cursos, 'Curso', @$t['fk_id_curso']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('1[periodo]', ['M' => 'Manhã', 'T' => 'Tarde'], 'Período do Dia', @$t['periodo']) ?>
            </div>
            <div class="col">
                <?= formErp::select('1[horario]', [1 => '1º Horário', 2 => '2º Horário'], 'Horário', @$t['horario']) ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 5px">
            <?=
            formErp::hidden([
                '1[id_turma]' => $id_turma,
                'id_polo' => $id_polo,
                '1[fk_id_polo]' => $id_polo,
                '1[fk_id_pl]' => $id_pl,
                'id_pl' => $id_pl
            ])
            . formErp::hiddenToken('tdics_turmaSet')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>