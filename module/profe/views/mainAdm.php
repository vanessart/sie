<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = tool::id_inst();
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_pl = ng_main::periodoSet($id_pl);
$periodos = ng_main::periodosPorSituacao();
$turmas = gtTurmas::idNome($id_inst, $id_pl);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$escola = sql::get('instancia', 'n_inst', ['id_inst' => $id_inst], 'fetch')['n_inst'];
?>
<style>
    .geral{
        margin: 0 auto;
        max-width:800px;
    }
</style>
<div class="body geral">
    <div class="row">
        <div class="col">
            <?= formErp::select('id_pl', $periodos, 'Período Letivo', $id_pl, 1) ?>
        </div>
        <div class="col">
            <?= formErp::select('id_turma', $turmas, 'Turmas', $id_turma, 1, ["id_pl" => $id_pl]) ?>
        </div>
    </div>
    <br />
    <?php
    if ($id_turma) {
        $turma = sql::get(['ge_turmas', 'ge_ciclos'], '*', ['id_turma' => $id_turma], 'fetch');
        if (!empty($turma['fk_id_grade'])) {
            $grade = $model->grade($turma['fk_id_grade']);
        }
        if (empty($grade)) {
            toolErp::alertModal('Para acessar o Diário é necessário atribuir uma grade a turma');
        }
    }
    if (!empty($grade)) {
        foreach ($grade as $id_disc => $n_disc) {
            ?>
            <form action="<?= HOME_URI . '/profe/chamada' ?>" method="post" id="form_ids-<?= $id_turma ?>-<?= $id_disc ?>">
                <div class="border" id="border-main" onclick="document.getElementById('form_ids-<?= $id_turma ?>-<?= $id_disc ?>').submit()" style="cursor: pointer">
                    <div class="col" align="center">
                        <h5><?= $n_disc ?></h5>
                        <input type="hidden" name="id_turma" value="<?= $id_turma ?>">
                        <input type="hidden" name="id_disc" value="<?= $id_disc ?>">
                        <input type="hidden" name="nome_disc" value="<?= $n_disc ?>">
                        <input type="hidden" name="nome_turma" value="<?= $turma['n_turma'] ?>">
                        <input type="hidden" name="id_pl" value="<?= $id_pl ?>">
                        <input type="hidden" name="escola" value="<?= $escola ?>">
                        <input type="hidden" name="id_curso" value="<?= $turma['fk_id_curso'] ?>">
                        <input type="hidden" name="id_ciclo" value="<?= $turma['fk_id_ciclo'] ?>">
                    </div>
                </div>
            </form>
            <br />
            <?php
        }
    }
    ?>
</div>
