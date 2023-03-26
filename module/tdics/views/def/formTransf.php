<?php
if (!defined('ABSPATH'))
    exit;
$todasTurmas = filter_input(INPUT_POST, 'todasTurmas', FILTER_SANITIZE_NUMBER_INT);
$setup = sql::get('tdics_setup', '*', null, 'fetch');
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_ta = filter_input(INPUT_POST, 'id_ta', FILTER_SANITIZE_NUMBER_INT);

$sql = "SELECT p.id_pessoa, p.n_pessoa, p.sexo, ta.fk_id_turma, t.fk_id_curso FROM tdics_turma_aluno ta "
        . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa AND ta.id_ta = $id_ta "
        . " join tdics_turma t on t.id_turma = ta.fk_id_turma";
$query = pdoSis::getInstance()->query($sql);
$alu = $query->fetch(PDO::FETCH_ASSOC);
$id_turma = $alu['fk_id_turma'];
$qtAlunos = $model->countAlunosCurso($alu['fk_id_curso'], $id_pl);

if (toolErp::id_nilvel() == 8 || empty($todasTurmas)) {
    $periodos = " and o.periodo = t.periodo ";
} else {
    $periodos = null;
}
$sql = "SELECT t.*, p.n_polo, c.n_curso FROM tdics_turma o "
        . " JOIN tdics_turma t on t.fk_id_curso = o.fk_id_pl and o.id_turma = $id_turma AND o.fk_id_pl= t.fk_id_pl $periodos "
        . " JOIN tdics_polo p on p.id_polo = t.fk_id_polo "
        . " JOIN tdics_curso c on c.id_curso = t.fk_id_curso "
        . " order by id_polo, periodo, dia_sem, horario";
$query = pdoSis::getInstance()->query($sql);
$turmas = $query->fetchAll(PDO::FETCH_ASSOC);
if ($turmas) {
    foreach ($turmas as $k => $v) {

        if ($id_turma == $v['id_turma']) {
            unset($turmas[$k]);
        } else {
            $turmas[$k]['qt'] = intval(@$qtAlunos[$v['id_turma']]);
            $turmas[$k]['dia_sem'] = $v['dia_sem'] . 'ª Feira';
            $turmas[$k]['horario'] = $v['horario'] . 'º Horário';
            $turmas[$k]['periodo'] = $v['periodo'] == 'M' ? 'Manhã' : 'Tarde';
            if (toolErp::id_nilvel() != 8 || @$qtAlunos[$v['id_turma']] < $setup['qt_turma']) {
                $turmas[$k]['ac'] = '<button onclick="tr(' . $v['id_turma'] . ', \'' . $v['n_turma'] . '\')" class="btn btn-info" >Transferir</button>';
            } else {
                $turmas[$k]['ac'] = '<button class="btn btn-danger" >Lotada</button>';
            }
        }
    }
    $form['array'] = $turmas;
    $form['fields'] = [
        'ID' => 'id_turma',
        'Polo' => 'n_polo',
        'Período' => 'periodo',
        'Turma' => 'n_turma',
        'Dia da Semana' => 'dia_sem',
        'Horário' => 'horario',
        'Qt. Alunos' => 'qt',
        '||1' => 'ac'
    ];
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
        Transferência
    </div>
    <table class="table table-bordered table-hover table-responsive">
        <tr>
            <td>
                Alun<?= toolErp::sexoArt($alu['sexo']) ?>
            </td>
            <td>
                <?= $alu['n_pessoa'] ?>
            </td>
        </tr>
        <tr>
            <td>
                RSE
            </td>
            <td>
                <?= $alu['id_pessoa'] ?>
            </td>
        </tr>
        <tr>
            <td>
                curso
            </td>
            <td>
                <?= current($turmas)['n_curso'] ?>
            </td>
        </tr>
    </table>
    <div class="row">
        <div class="col">
            <div class="alert alert-info" style="font-weight: bold">
                Para qual turma deseja transferir?
            </div>
        </div>
        <div class="col">
            <?php
            if (toolErp::id_nilvel() != 8) {
                if (empty($todasTurmas)) {
                    ?>
                    <form method="POST">
                        <?=
                        formErp::hidden($hidden)
                        . formErp::hidden(['todasTurmas' => 1, 'id_ta' => $id_ta])
                        . formErp::button('Mostar Todas as Turmas')
                        ?>
                    </form>
                    <?php
                } else {
                    ?>
                    <form method="POST">
                        <?=
                        formErp::hidden($hidden)
                        . formErp::hidden(['todasTurmas' => null, 'id_ta' => $id_ta])
                        . formErp::button('Mostar só as Turmas do Contraturno')
                        ?>
                    </form>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/tdics/alocaAlu" id="trans" target="_parent" method="POST">
    <input type="hidden" name="1[fk_id_turma]" id="id_turma" value="" />
    <?=
    formErp::hidden($hidden)
    . formErp::hidden([
        '1[id_ta]' => $id_ta
    ])
    . formErp::hiddenToken('tdics_turma_aluno', 'ireplace')
    ?>
</form>
<script>
    function tr(id, nt) {
        if (confirm("Transferir <?= toolErp::sexoArt($alu['sexo']) ?> alun<?= toolErp::sexoArt($alu['sexo']) ?> para a turma " + nt + '?')) {
            id_turma.value = id;
            trans.submit();
        }
    }
</script>