<?php
if (!defined('ABSPATH'))
    exit;

$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$turmas = ng_escola::turmasSegAtiva(toolErp::id_inst());
if (!empty($id_turma)) {
    @$profDisc = current(gtTurmas::professores($id_turma));
    $turma = sql::get(['ge_turmas', 'ge_ciclos'], 'n_turma, fk_id_ciclo, fk_id_curso, periodo, fk_id_pl, ge_turmas.fk_id_grade', ['id_turma' => $id_turma], 'fetch');
    $grade = gtMain::discGrade($turma['fk_id_grade']);
}
?>

<div class="body">
    <div class="fieldTop">
        Lançamento de notas
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_turma', $turmas, 'Turma', $id_turma, 1) ?>
        </div>
    </div>
    <br /><br />
    <?php
    if ($id_turma) {

        foreach ($grade as $id_disc => $g) {
            ?>
            <form action="<?= HOME_URI . '/profe/lancNotaSet' ?>" method="post" id="form_ids-<?= $id_turma ?>-<?= $id_disc ?>">
                <div class="border" id="border-main" onclick="document.getElementById('form_ids-<?= $id_turma ?>-<?= $id_disc ?>').submit()" style="cursor: pointer">
                    <div class="col" align="center">
                        <h3><?= $g['n_disc'] ?></h3>
                        <h5 ><?= $turma['n_turma'] ?></h5>
                        <?php
                        if (!empty($profDisc[$id_disc]['nome'])) {
                            ?>
                            <h6>Prof. <?= $profDisc[$id_disc]['nome'] ?></h6>
                            <?php
                        }
                        echo formErp::hidden([
                            'id_ciclo'=>$turma['fk_id_ciclo'],
                            'id_inst'=> toolErp::id_inst(),
                            'id_pl'=>$turma['fk_id_pl'],
                            'id_turma'=>$id_turma,
                            'id_curso'=>$turma['fk_id_curso'],
                            'escola'=>toolErp::n_inst(),
                            'n_turma'=>$turma['n_turma'],
                            'n_disc'=>$g['n_disc'],
                            'id_disc'=>$id_disc
                        ])
                        ?>
                    </div>
                </div>
            </form>
            <br /><br />
            <?php
        }
    }
    ?>
</div>
