<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = tool::id_inst();
$turmas = ng_escola::turmasSegAtiva(toolErp::id_inst());
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
?>
<style>
    .geral{
        margin: 0 auto;
        max-width:800px;
    }
</style>
<div class="body geral">
    <div class="fieldTop">
        Relatório de Chamada
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_turma', $turmas, 'Turmas', $id_turma, 1) ?>
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
                        <form action="<?= HOME_URI . '/profe/chamadaTabela' ?>" method="post" >
                            <?=
                            formErp::hidden([
                                'id_turma' => $id_turma,
                                'id_disc' => $id_disc,
                                'n_disc' => $n_disc,
                                'n_turma' => $turma['n_turma'],
                                'id_pl' => $turma['fk_id_pl'],
                                'id_curso' => $turma['fk_id_curso']
                            ])
                            . formErp::button("Acessar")
                            ?>
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
