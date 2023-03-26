<?php
if (!defined('ABSPATH'))
    exit;

$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_inst_ = filter_input(INPUT_POST, 'id_inst_', FILTER_SANITIZE_NUMBER_INT);
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$id_poloSet = filter_input(INPUT_POST, 'id_poloSet', FILTER_SANITIZE_NUMBER_INT);
$periodoSet = filter_input(INPUT_POST, 'periodoSet', FILTER_SANITIZE_STRING);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$polos = sql::idNome('maker_polo');
$setup = sql::get(['maker_setup', 'ge_periodo_letivo'], '*', null, 'fetch');
$id_pl = $setup['fk_id_pl_matr'];

if (empty($id_poloSet)) {
    $id_poloSet = $id_polo;
}
$ae = alunos::alunosGet($id_inst_);
foreach ($ae as $v) {
    if (in_array($v['id_ciclo'], [4, 5, 6, 7, 8, 9])) {
        $alunosEsc[$v['id_pessoa']] = $v['id_pessoa'] . '-' . $v['n_pessoa'];
    }
}

$hidden = [
    'id_polo' => $id_polo,
    'id_poloSet' => $id_poloSet,
    'periodoSet' => $periodoSet,
    'id_inst_' => $id_inst_,
];

if ($id_pessoa) {
    $sql = "SELECT t.n_turma, po.n_polo FROM maker_gt_turma_aluno ta "
            . " JOIN maker_gt_turma t on t.id_turma = ta.fk_id_turma "
            . " JOIN maker_polo po on po.fk_id_inst_maker = t.fk_id_inst "
            . " AND t.fk_id_pl = $id_pl AND "
            . " `fk_id_pessoa` = $id_pessoa ";
    $query = pdoSis::getInstance()->query($sql);
    $jaMatric = $query->fetch(PDO::FETCH_ASSOC);
    $alu = $model->alunoTurmaEsc($id_pessoa);
    if (empty($periodoSet)) {
        if ($alu['periodo'] == 'M') {
            $periodoSet = 'T';
        } else {
            $periodoSet = 'M';
        }
    }
}




if (!empty($alu)) {
    $sql = "SELECT * FROM `maker_escola` WHERE `fk_id_inst` = $id_inst_ ";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetch(PDO::FETCH_ASSOC);
    if ($periodoSet == 'T') {
        $quota = $array['cota_t'];
    } else {
        $quota = $array['cota_m'];
    }
    $sql = "SELECT count(fk_id_pessoa) as id FROM `maker_aluno` "
            . " WHERE `fk_id_inst` = $id_inst_ "
            . " AND `fk_id_pl` = $id_pl "
            . " AND `fk_id_as` = 1 AND "
            . " `periodo` LIKE '$periodoSet' "
            . " and fk_id_mc = 1 ";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetch(PDO::FETCH_ASSOC);

    $matriculados = @$array['id'];
    $quota = $quota - $matriculados;
    if ($quota < 1) {
        ?>
        <div class="alert alert-danger">
            <p>
                Todas as vagas estão preenchidas! Em caso de necessidade de alteração da cota de sua escola, entre em contato com o DTTIE.
            </p>
        </div>
        <?php
        exit();
    } else {
        ?>
        <div class="alert alert-info">
            <p>
                Atualmente, sua escola tem <?= $quota ?> vagas para este período. Em caso de necessidade de alteração da cota, entre em contato com o DTTIE.
            </p>
        </div>
        <?php
    }
}
$turmas = $model->turmasPoloNg($id_poloSet, $periodoSet, null, $id_pl);

if ($turmas) {
    $idTumas = array_column($turmas, 'id_turma');
    $sql = "SELECT id_turma, transporte, fk_id_inst_sieb FROM maker_gt_turma WHERE id_turma in (" . implode(', ', $idTumas) . ");";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($array as $v) {
        $transpEsc[$v['id_turma']] = [
            'e' => $v['fk_id_inst_sieb'],
            't' => $v['transporte']
        ];
    }
    
    foreach ($turmas as $k => $v) {
        $idT[$v['id_turma']]=$v['id_turma'];
        $turmaForm[$v['id_turma']] = $v;
        $turmaForm[$v['id_turma']]['qt'] = 0;
    }
}
$sql="SELECT "
        . " `fk_id_turma`, COUNT(`fk_id_turma`) ct FROM `maker_gt_turma_aluno` "
        . " GROUP by `fk_id_turma` "
        . " HAVING `fk_id_turma` in (".implode(', ', $idT).") ";
$query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v){
            $turmaQt[$v['fk_id_turma']]=$v['ct'];
        }
        
        
$alunos = $model->alunosNg($id_polo, null, null, $periodoSet, null, null, $id_pl);

if ($alunos) {
    foreach ($alunos as $k => $v) {
        if ($v['id_turma']) {
            @$qt[$v['id_turma']]++;
            $turmaForm[$v['id_turma']] = [
                'id_turma' => $v['id_turma'],
                'n_turma' => $v['n_turma'],
                'qt' => @$turmaQt[$v['id_turma']],
            ];
        }
    }
}

if (!empty($turmaForm)) {
    foreach ($turmaForm as $k => $v) {
        if ($v['qt'] >= 32 && toolErp::id_nilvel() != 2) {
            $turmaForm[$k]['class'] = 'danger';
            unset($turmas[$v['id_turma']]);
            $turmaForm[$k]['onclick'] = ' onclick="alert(\'O limite de alunos ativos por sala é 32\')" ';
        } else {
            $turmaForm[$k]['onclick'] = '  onclick="salvt(' . $v['id_turma'] . ', \'' . $v['n_turma'] . '\')" ';
            if (@$transpEsc[$v['id_turma']]['t'] == 1) {
                $turmaForm[$k]['class'] = 'primary';
            } else {
                $turmaForm[$k]['class'] = 'info';
            }
        }
    }
    ksort($turmaForm);
}
?>
<div class="body">
    <div class="row">
        <div class="col">
            <?= formErp::select('id_pessoa', $alunosEsc, 'Aluno', $id_pessoa, 1, $hidden) ?>
        </div>
        <div class="col">

        </div>
        <div class="col">

        </div>
    </div>
    <br />
    <?php
    if (!empty($jaMatric)) {
        ?>
        <table class="table table-bordered table-hover table-striped">
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
                    Nome
                </td>
                <td>
                    <?= $alu['n_pessoa'] ?>
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
                <td colspan="2">
                    Est<?= $alu['sexo'] == 'M' ? 'a' : 'e' ?> alun<?= $alu['sexo'] == 'M' ? 'a' : 'o' ?> já está matriculad<?= $alu['sexo'] == 'M' ? 'a' : 'o' ?> na turma <span style="font-weight: bold"><?= $jaMatric['n_turma'] ?></span> no polo <span style="font-weight: bold"><?= $jaMatric['n_polo'] ?></span>
                </td>
            </tr>

        </table>
        <?php
    } elseif (!empty($alu)) {
        ?>
        <form action="<?= HOME_URI ?>/maker/transf" target="_parent" id="formTrasf" method="POST">
            <table class="table table-bordered table-hover table-striped">
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
                        Dt. Nasc
                    </td>
                    <td>
                        <?= data::converteBr($alu['dt_nasc']) ?>
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
                        Turma
                    </td>
                    <td>
                        <?= $alu['n_turma'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Escola
                    </td>
                    <td>
                        <?= sql::get('instancia', 'n_inst', ['id_inst' => $id_inst_], 'fetch')['n_inst'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Polo
                    </td>
                    <td>
                        <?= sql::get('maker_polo', 'n_polo', ['id_polo' => $id_polo], 'fetch')['n_polo'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Período
                    </td>
                    <td>
                        <?php
                        if ($alu['periodo'] == 'M') {
                            echo 'De MANHÃ na escola e a TARDE no POLO';
                        } else {
                            echo 'De TARDE na escola e a MANHÃ no POLO';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Necessita de transporte?
                    </td>
                    <td>
                        <div class="row">
                            <div class="col">
                                <?= formErp::radio('1[transporte]', 0, 'Não', 0) ?>
                            </div>
                            <div class="col">
                                <?= formErp::radio('1[transporte]', 1, 'Sim') ?>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            <br />
            <div class="border">
                <div class="row">
                    <?php
                    $ct = 0;
                    if (!empty($turmaForm)) {
                        foreach ($turmaForm as $k => $v) {
                            if ((substr($v['n_turma'], 0, 1) == 1) && ((@$transpEsc[$v['id_turma']]['e'] == $id_inst_) || (@$transpEsc[$v['id_turma']]['e'] == 0))) {
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
                                            if (@$transpEsc[$v['id_turma']]['t'] == 1) {
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
                                $tem = 1;
                            }
                        }
                    }
                    if (empty($tem)) {
                        echo '&nbsp;&nbsp;&nbsp;&nbsp;Não há turma com os critérios necessários para esta matrícula';
                    }
                    ?>

                </div>
            </div>
            <br />
            <?php
            if ($id_poloSet) {
                $fk_id_inst_maker = sql::get('maker_polo', 'fk_id_inst_maker', ['id_polo' => $id_polo], 'fetch')['fk_id_inst_maker'];
                ?>
                <input type="hidden" name="1[fk_id_turma]" id="idTurma" />
                <?=
                formErp::hidden([
                    'id_pessoa' => $alu['id_pessoa'],
                    'id_polo' => $id_polo,
                    'periodo' => $periodoSet,
                    'id_poloSet' => $id_poloSet,
                    'periodoSet' => $periodoSet,
                    '1[fk_id_pessoa]' => $alu['id_pessoa'],
                    '1[dt_matricula]' => date("Y-m-d"),
                    'id_inst_' => $id_inst_,
                    'id_inst_sieb' => $id_inst_,
                    'id_pl' => $id_pl
                ])
                . formErp::hiddenToken('matriculaAluno')
                ?>
            </form>
            <?php
        }
    }
    ?>
</div>
<script>
    function salvt(id, nome) {
        if (confirm("Matricular o aluno na turma " + nome + "?")) {
            idTurma.value = id;
            formTrasf.submit();
        }
    }
</script>