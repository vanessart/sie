<?php
if (!defined('ABSPATH'))
    exit;
$id_inst_ = filter_input(INPUT_POST, 'id_inst_', FILTER_SANITIZE_NUMBER_INT);
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$id_poloSet = filter_input(INPUT_POST, 'id_poloSet', FILTER_SANITIZE_NUMBER_INT);
$periodo = filter_input(INPUT_POST, 'periodo', FILTER_UNSAFE_RAW);
$periodoSet = filter_input(INPUT_POST, 'periodoSet', FILTER_UNSAFE_RAW);
$id_mc = filter_input(INPUT_POST, 'id_mc', FILTER_SANITIZE_NUMBER_INT);
$diaSem = filter_input(INPUT_POST, 'diaSem', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_ta = filter_input(INPUT_POST, 'id_ta', FILTER_SANITIZE_NUMBER_INT);
$polos = sql::idNome('maker_polo');
    $alu = $model->alunoTurmaNg($id_ta);
    
    if (empty($periodoSet)) {
        $periodoSet = $alu['periodo'];
    }
   $escola = $model->escolaAlunofrequ($alu['id_pessoa']);

if (empty($id_poloSet)) {
    $id_poloSet = $id_polo;
    $turmas = $model->turmasPolo($id_poloSet, $periodoSet);
    $alunos = $model->alunosNg($id_polo, null, null, $periodoSet);

    if ($alunos) {
        $t = $model->turmasPoloNg($id_poloSet, $periodoSet);
        
        $idTumas = array_column($t, 'id_turma');
        $sql = "SELECT id_turma, transporte, fk_id_inst_sieb FROM maker_gt_turma WHERE id_turma in (" . implode(', ', $idTumas) . ");";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $transpEsc[$v['id_turma']] = [
                'e' => $v['fk_id_inst_sieb'],
                't' => $v['transporte']
            ];
        }
        if ($t) {
            foreach ($t as $k => $v) {
                if ($k != $alu['id_turma']) {
                    $turmaForm[$v['id_turma']] = [
                        'id_turma' => $v['id_turma'],
                        'n_turma' => $v['n_turma'],
                        'qt' => 0,
                    ];
                }
            }
        }
        
        foreach ($alunos as $k => $v) {
            if ($v['id_turma'] != $alu['id_turma'] && !empty($turmaForm[$v['id_turma']])) {
                    @$qt[$v['id_turma']]++;
                $turmaForm[$v['id_turma']]['qt'] = @$qt[$v['id_turma']];
            }
        }
        if (!empty($turmaForm)) {
            foreach ($turmaForm as $k => $v) {
                if ($v['qt'] >= 30) {
                    $turmaForm[$k]['class'] = 'danger';
                    unset($turmas[$v['id_turma']]);
                    $turmaForm[$k]['onclick'] = ' onclick="alert(\'O limite de alunos ativos por sala é 30\')" ';
                } else {
                    $turmaForm[$k]['onclick'] = '  onclick="salvt(' . $v['id_turma'] . ', \'' . $v['n_turma'] . '\')" ';
                    if ($transpEsc[$v['id_turma']]['t'] == 1) {
                        $turmaForm[$k]['class'] = 'primary';
                    } else {
                        $turmaForm[$k]['class'] = 'info';
                    }
                }
            }
        }
    }
}
ksort($turmaForm);
?>
<div class="body">
    <?php
    if (!empty($alu)) {
        ?>
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    RSE...
                </td>
                <td>
                    <?= $alu['id_pessoa'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Dt. Nasc
                </td>
                <td>
                    <?= $alu['dt_nasc'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Nome
                </td>
                <td>
                    <?= $alu['n_pessoa'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Ciclo
                </td>
                <td>
                    <?= $alu['n_mc'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Escola
                </td>
                <td>
                    <?= $escola['n_inst'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Turma
                </td>
                <td>
                    <?= $alu['n_turma'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Período
                </td>
                <td>
                    <?= $alu['periodo'] == 'M' ? 'Manhã' : 'Tarde' ?>
                </td>
            </tr>
            <tr>
                <td>
                    Polo
                </td>
                <td>
                    <?= $alu['n_polo'] ?>
                </td>
            </tr>
            </tr>
        </table>
        <br />
        <div class="border">
            <div class="row">
                <?php
                $ct = 0;
                foreach ($turmaForm as $k => $v) {
                    if ((substr($v['n_turma'], 0, 1) == $alu['id_mc']) && (($transpEsc[$v['id_turma']]['e'] == $id_inst_) || ($transpEsc[$v['id_turma']]['e'] == 0))) {
                        ?>
                        <div class="col">
                            <button <?= $v['onclick'] ?> type="button" style="width: 100%" class="btn btn-<?= $v['class'] ?>">
                                <p>
                                    <?= $v['n_turma'] ?>
                                </p>
                                <p>
                                    <?= $v['qt'] ?> aluno<?= $v['qt'] > 1 ? 's' : '' ?>
                                </p>
                                <p>
                                    <?php
                                    if ($transpEsc[$v['id_turma']]['t'] == 1) {
                                        echo 'Com Transporte Escolar';
                                    } else {
                                        echo 'Sem Transporte Escolar';
                                    }
                                    ?>
                                </p>
                            </button>
                        </div>
                        <?php
                        $ct++;
                        if ($ct % 4 == 0) {
                            ?>
                        </div>
                        <br />
                        <div class="row">
                            <?php
                        }
                    }
                }
                ?>

            </div>
        </div>
        <br />
        <?php
        if ($id_poloSet) {
            $fk_id_inst_maker = sql::get('maker_polo', 'fk_id_inst_maker', ['id_polo' => $id_polo], 'fetch')['fk_id_inst_maker'];
            ?>
            <form action="<?= HOME_URI ?>/maker/transf" target="_parent" id="formTrasf" method="POST">
                <input type="hidden" name="1[fk_id_turma]" id="idTurma" />
                <?=
                formErp::hidden([
                    'id_pessoa' => $alu['id_pessoa'],
                    'id_polo' => $id_polo,
                    'periodo' => $periodo,
                    'id_mc' => $id_mc,
                    'diaSem' => $diaSem,
                    'id_turma' => $id_turma,
                    'id_ta' => $id_ta,
                    'id_poloSet' => $id_poloSet,
                    'periodoSet' => $periodoSet,
                    '1[fk_id_inst]' => $id_poloSet,
                    '1[id_ta]' => $id_ta,
                    'id_inst_' => $id_inst_,
                    'id_inst_sieb' => $escola['id_inst']
                ])
                . formErp::hiddenToken('transferirAluno')
                ?>
            </form>
            <?php
        }
    }
    ?>
</div>
<script>
    function salvt(id, nome) {
        if (confirm("transferir o aluno para a turma " + nome + "?")) {
            idTurma.value = id;
            formTrasf.submit();
        }
    }
</script>