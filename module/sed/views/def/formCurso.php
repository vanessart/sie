<?php
$id_inst = tool::id_inst();
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_pl = ng_main::periodoSet($id_pl);
$periodos = ng_main::periodosPorSituacao();
$turmas = gtTurmas::turmas($id_inst, $id_pl);
$turmas_id = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$turma = sql::get("ge_turmas", "*", ['id_turma' => $turmas_id], 'fetch');
$ciclo = sql::get("ge_ciclos", "n_ciclo", ['id_ciclo' => $turma['fk_id_ciclo']], 'fetch')['n_ciclo'];
$grade = sql::get("ge_grades", "n_grade", ['id_grade' => $turma['fk_id_grade']], 'fetch')['n_grade'];
$readonly = 'readonly';

$prof = $model->profEscRm($id_inst);
?>
<br>

<div class="border">
    <form action="<?= HOME_URI . '/sed/turmaSet' ?>" target="_parent" method="post">

        <div class="row">
            <div class="col-4">
                <?= formErp::input('1[id_turma]', 'ID', $turma['id_turma'], $readonly) ?>
            </div>
            <div class="col-8">
                <?= formErp::input('1[n_turma]', 'Turma', $turma['n_turma'], $readonly) ?>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-4">
                <?= formErp::input(null, 'Ciclo', $ciclo, $readonly) ?>
            </div>
            <div class="col-4">
                <?= formErp::input(null, 'Grade', $grade, $readonly) ?>
            </div>
            <div class="col-3">
                <?= formErp::select('1[periodo]', ng_main::periodoDoDia(), 'Periodo', $turma['periodo'], null, null, $readonly . ' disabled ') ?>
            </div>
        </div><br>

        <div class="row"> 
            <div class="col-4">
                <?= formErp::input('1[codigo]', 'Codigo', $turma['codigo'], $readonly) ?>
            </div>
            <div class="col-3">
                <?= formErp::input('1[letra]', 'Letra', $turma['letra'], $readonly) ?>
            </div>
            <div class="col-4">
                <?= formErp::input('1[prodesp]', 'Prodesp', $turma['prodesp'], $readonly) ?>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col">
                <?= formErp::select('1[rm_prof]', $prof, 'Professor Responsável', $turma['rm_prof']) ?>
            </div>
        </div>
        <br />
        <div style="text-align: center"> 
            <?= formErp::button('Salvar') ?>
            <?= formErp::hiddenToken('ge_turmas', 'ireplace') ?>
            <?=
            formErp::hidden(['1[id_turma]' => $turma['id_turma'],
                'id_turma' => $turmas_id,
                '1[fk_id_grade]' => $turma['fk_id_grade'],
                '1[fk_id_ciclo]' => $turma['fk_id_ciclo'],
            ])
            ?>
        </div>
    </form>
</div>