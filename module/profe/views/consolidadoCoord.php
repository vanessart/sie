<?php
if (!defined('ABSPATH'))
    exit;
foreach (range(2022, date("Y")) as $v) {
    $anos[$v] = $v;
}
$ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_NUMBER_INT);
if (empty($ano)) {
    $ano = date("Y");
}
$sistema = $model->getSistema('22', '48,2,18,53,54,55,24,56');
if (in_array(toolErp::id_nilvel(), [18, 53, 54, 22])) {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    if (toolErp::id_nilvel() == 22) {
        $escolas = ng_escolas::idEscolasSupervisor(tool::id_pessoa());
    } else {
        $escolas = ng_escolas::idEscolas();
    }
} else {
    $id_inst = tool::id_inst();
}
if ($id_inst) {
    $turmas = $model->turmasSegAtiva($id_inst, $ano);
    $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
    $escola = sql::get('instancia', 'n_inst', ['id_inst' => $id_inst], 'fetch')['n_inst'];
    if ($id_turma) {
        $turma = sql::get(['ge_turmas', 'ge_ciclos'], '*', ['id_turma' => $id_turma], 'fetch');
        if (!empty($turma['fk_id_grade'])) {
            $grade = $model->grade($turma['fk_id_grade']);
        }
        if (empty($grade)) {
            toolErp::alertModal('Para acessar o Diário é necessário atribuir uma grade a turma');
        }
    }
}
?>
<style>
    .geral{
        margin: 0 auto;
        max-width:800px;
    }
</style>
<div class="body geral">

    <div class="fieldTop">
        Consolidado
    </div>
    <div class="row">
        <div class="col-4">
            <?= formErp::select('ano', $anos, 'Ano', $ano, 1, ['id_inst' => $id_inst, 'ano' => $ano]) ?>
        </div>
        <div class="col-4">
            <?php
            if (empty($id_inst) && in_array(toolErp::id_nilvel(), [18, 53, 54, 22])) {
                echo formErp::select('id_inst', $escolas, 'Escola', $id_inst, 1, ['ano' => $ano]) . '<br>';
            } else {
                echo formErp::select('id_turma', $turmas, 'Turmas', $id_turma, 1, ['id_inst' => $id_inst, 'ano' => $ano]);
            }
            ?>
        </div>
        <div class="col-4">
            <div style="font-weight: bold; font-size: 1.3em; padding: 5px">
                <?php
                if (!empty($id_inst) && in_array(toolErp::id_nilvel(), [18, 53, 54, 22])) {
                    echo $escolas[$id_inst];
                }
                ?>
            </div>
        </div>
    </div>
    <br />
    <?php
    if (!empty($grade)) {
        ?>
        <table class="table table-bordered table-hover table-striped">
            <?php
            foreach ($grade as $id_disc => $n_disc) {
                ?>
                <tr>
                    <td>
                        <?= $n_disc ?>
                    </td>
                    <td style="width: 100px">
                        <form target="_blank" action="<?= HOME_URI . '/' . $sistema . '/consolidado' ?>" method="post" >
                            <?=
                            formErp::hidden([
                                'id_turma' => $id_turma,
                                'id_disc' => $id_disc,
                                'n_disc' => $n_disc,
                                'n_turma' => $turma['n_turma'],
                                'id_pl' => $turma['fk_id_pl'],
                                'id_curso' => $turma['fk_id_curso']
                            ])
                            . formErp::button("Consolidado")
                            ?>
                        </form>
                    </td>
                    <td style="width: 100px">
                        <form target="_blank" action="<?= HOME_URI . '/' . $sistema . '/consolidadoResumo' ?>" method="post" >
                            <?=
                            formErp::hidden([
                                'id_turma' => $id_turma,
                                'id_disc' => $id_disc,
                                'n_disc' => $n_disc,
                                'n_turma' => $turma['n_turma'],
                                'id_pl' => $turma['fk_id_pl'],
                                'id_curso' => $turma['fk_id_curso']
                            ])
                            ?>
                            <button class="btn btn-success border">
                                Resumo
                            </button>
                        </form>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }
    ?>
</div>
