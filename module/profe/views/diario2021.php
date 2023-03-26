<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = tool::id_inst();
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_pl = empty($id_pl) ? 84 : $id_pl;
$periodos = [84 => '2021', 85 => '1º Sem. 2021', 86 => '2º Sem. 2021'];
$turmas = gtTurmas::idNome($id_inst, $id_pl, ' 1, 2, 3, 4, 5, 6, 7, 8, 9, 19, 20, 21, 22, 23, 24, 35 ');
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
        Diário 2021
    </div>
    <div class="row">
        <!--
        <div class="col">
        <?= formErp::select('id_pl', $periodos, 'Período Letivo', $id_pl, 1) ?>
        </div>
        -->
        <div class="col">
            <?= formErp::select('id_turma', $turmas, 'Turmas', $id_turma, 1, ["id_pl" => $id_pl]) ?>
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
                        <form action="<?= HOME_URI . '/profe/diarioCoord' ?>" method="post" >
                            <?=
                            formErp::hidden([
                                'id_turma' => $id_turma,
                                'id_disc' => $id_disc,
                                'n_disc' => $n_disc,
                                'n_turma' => $turma['n_turma'],
                                'id_pl' => $id_pl,
                                'escola' => $escola,
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
