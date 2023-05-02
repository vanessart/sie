<?php
if (!defined('ABSPATH'))
    exit;
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$escola = filter_input(INPUT_POST, 'escola');
$turma = filter_input(INPUT_POST, 'turma');
$polo = filter_input(INPUT_POST, 'polo');


$turmaCurso = sql::get(['tdics_turma', 'tdics_curso'], '*', ['id_turma' => $id_turma], 'fetch');
if ($turmaCurso['periodo'] == 'M') {
    $periodoCurso = 'T';
} else {
    $periodoCurso = 'M';
}
$fields = " p.id_pessoa, p.n_pessoa, t.codigo, ta.chamada, id_ciclo";
$sql = "select  $fields from ge_turma_aluno ta "
        . " join ge_turmas t on t.id_turma = ta.fk_id_turma and fk_id_ciclo not in (32) "
        . " and periodo = '$periodoCurso' "
        . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
        . " join pessoa p on p.id_pessoa = ta.fk_id_pessoa "
        . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
        . " where t.fk_id_inst = $id_inst "
        . " AND situacao like 'Frequente' "
        . " and at_pl = 1 "
        . " order by n_pessoa ";
        // echo $sql;
$query = pdoSis::getInstance()->query($sql);
$ae = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($ae as $v) {
    if (in_array($v['id_ciclo'], [4, 5, 6, 7, 8, 9, 38, 39, 40, 41, 42, 43, 44, 45, 46])) {
            $alunos[$v['id_pessoa']] = $v['id_pessoa'] . '-' . $v['n_pessoa'] . ' (' . $v['codigo'] . ')';
    }
}
$hidden = [
    'id_pl' => $id_pl,
    'id_polo' => $id_polo,
    'id_inst' => $id_inst,
    'id_turma' => $id_turma
];
?>
<div class="body">
    <div class="fieldTop">
        Nova Matrícula
    </div>
    <table class="table table-bordered table-hover table-responsive">
        <tr>
            <td>
                Polo
            </td>
            <td>
                <?= $polo ?>
            </td>
        </tr>
        <tr>
            <td>
                Escola de Origem
            </td>
            <td>
                <?= $escola ?>
            </td>
        </tr>
        <tr>
            <td>
                Turma
            </td>
            <td>
                <?= $turma ?>
            </td>
        </tr>
        <tr>
            <td>
                curso
            </td>
            <td>
                <?= $turmaCurso['n_curso'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Período do Curso
            </td>
            <td>
                <?= $turmaCurso['periodo'] == 'M' ? 'Manhã' : 'Tarde' ?>
            </td>
        </tr>
        <tr>
            <td>
                Dia da semana
            </td>
            <td>
                <?= $model->diaSemana($turmaCurso['dia_sem']) ?>
            </td>
        </tr>
    </table>
    <form action="<?= HOME_URI ?>/tdics/alocaAlu" target="_parent" method="POST">
        <div class="row">
            <div class="col-9">
                <?php
                if (!empty($alunos)) {
                    echo formErp::select('id_pessoa', $alunos, 'Aluno', null, null, null, ' required ');
                } else {
                    ?>
                    <div class="alert alert-danger">
                        Não há alunos para este período
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="col-3">
                <?php
                if (!empty($alunos)) {
                    echo formErp::hidden($hidden)
                    . formErp::hiddenToken('novoAluno')
                    . formErp::button('Salvar');
                } ?>
            </div>
        </div>
        <br />
    </form>
</div>