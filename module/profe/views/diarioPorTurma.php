<style>
    .inteiro{
        color: white; 
        border-radius: 10px; 
        height: 30px; 
        padding: 5px; 
        font-weight: bold; 
        text-align: center ;
    }
    .comeca{
        color: white; 
        border-radius: 10px 0 0 10px; 
        height: 30px; 
        padding: 5px; 
        font-weight: bold; 
        text-align: center ;
    }
    .termina{
        color: white; 
        border-radius: 0 10px 10px 0;
        height: 30px; 
        padding: 5px; 
        font-weight: bold; 
        text-align: center ;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;

$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$turmas = ng_escola::turmasSegAtiva(toolErp::id_inst());
if (!empty($id_turma)) {
    @$profDisc = current(gtTurmas::professores($id_turma));
    $turma = sql::get(['ge_turmas', 'ge_ciclos'], 'n_turma, fk_id_ciclo, fk_id_curso, periodo, fk_id_pl, ge_turmas.fk_id_grade, aulas', ['id_turma' => $id_turma], 'fetch');
    $grade = gtMain::discGrade($turma['fk_id_grade']);
}
?>

<div class="body">
    <div class="fieldTop">
        Plano de Aula
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
            <form action="<?= HOME_URI . '/profe/chamada' ?>" method="post" id="form_ids-<?= $id_turma ?>-<?= $id_disc ?>">
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
                        ?>
                        <input type="hidden" name="id_turma" value="<?= $id_turma ?>">
                        <input type="hidden" name="id_disc" value="<?= $id_disc ?>">
                        <input type="hidden" name="nome_disc" value="<?= $g['n_disc'] ?>">
                        <input type="hidden" name="aulas" value="<?= $turma['aulas'] ?>">
                        <input type="hidden" name="nome_turma" value="<?= $turma['n_turma'] ?>">
                        <input type="hidden" name="id_pl" value="<?= $turma['fk_id_pl'] ?>">
                        <input type="hidden" name="escola" value="<?= toolErp::n_inst() ?>">
                        <input type="hidden" name="id_curso" value="<?= $turma['fk_id_curso'] ?>">
                        <input type="hidden" name="id_ciclo" value="<?= $turma['fk_id_ciclo'] ?>">
                    </div>
                </div>
            </form>
            <br /><br />
            <?php
        }
    }
    ?>
</div>
