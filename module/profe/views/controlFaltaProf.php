<?php
if (!defined('ABSPATH'))
    exit;

$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_pl = ng_main::periodoSet($id_pl);
$pls = ng_main::periodosPorSituacao(1);

$semana = filter_input(INPUT_POST, 'semana', FILTER_SANITIZE_NUMBER_INT);
if (empty($semana)) {
    $semana = date("W");
}
$dt_semana = dataErp::dataPorSemana($semana, null, 1, 1);

$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = toolErp::id_pessoa($id_pessoa);
$horario = horario($id_pessoa);
$diasDaSemana = dataErp::diasDaSemana();
unset($diasDaSemana[0]);
unset($diasDaSemana[6]);
$perArr = dataErp::periodoDoDia();
$id_turmas = [];
if ($horario) {
    foreach ($horario as $periodo => $aulas) {
        foreach ($aulas as $dia => $tm) {
            foreach ($tm as $v) {
                if (!in_array($v['id_turma'], $id_turmas))
                    $id_turmas[] = $v['id_turma'];
            }
        }
    }

    $mongo = new mongoCrude('Diario');
    $conteudo_ = $mongo->query('Aula.' . $id_pl, ['id_turma' => ['$in' => $id_turmas], 'data' => ['$in' => $dt_semana]], ['projection' => ['id_turma' => 1, '_id' => 0, 'data' => 1, 'id_disc' => 1]]);
    foreach ($conteudo_ as $v) {
        $conteudo[$v->data][$v->id_turma][$v->id_disc] = 1;
    }
    $chamada_ = $mongo->query('presence_' . $id_pl, ['id_turma' => ['$in' => $id_turmas], 'data' => ['$in' => $dt_semana]], ['projection' => ['id_turma' => 1, '_id' => 0, 'data' => 1, 'id_disc' => 1]]);
    foreach ($chamada_ as $v) {
        $chamada[$v->data][$v->id_turma][$v->id_disc] = 1;
    }
    ?>
    <div class="body">
        <div class="fieldTop">
            <p>
                Horário e Controle de Registros Diários 
            </p>
            <div class="row">
                <div class="col-5">

                </div>
                <div class="col">
                    <?= formErp::select('id_pl', $pls, 'Período Letivo', $id_pl, 1, ['semana' => $semana]) ?>
                </div>
            </div>
        </div>
        <?php
        foreach ($horario as $periodo => $aulas) {
            ?>
            <div class="row">
                <div class="col-3" style="padding-left: 50px">
                    <?php
                    if ($semana > 1) {
                        ?>
                        <form method="POST">
                            <?=
                            formErp::hidden([
                                'semana' => $semana - 1,
                                'id_pl' => $id_pl
                            ])
                            ?>
                            <button class="btn btn-outline-info" >
                                <img style="width: 30px" src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/voltar.png" >
                            </button>
                        </form>
                        <?php
                    }
                    ?>
                </div>
                <div class="col-6">
                    <div class="fieldTop">
                        <p>
                            Período: <?= $perArr[$periodo] ?>
                        </p>
                        <p>
                            <?php
                            if (substr($dt_semana[0], 5, 2) == substr($dt_semana[4], 5, 2)) {
                                ?>
                                Semana de <?= substr($dt_semana[0], -2) ?> à <?= substr($dt_semana[4], -2) ?> de <?= dataErp::meses(substr($dt_semana[4], 5, 2)) ?>
                                <?php
                            } else {
                                ?>
                                Semana de <?= substr($dt_semana[0], -2) ?> de <?= dataErp::meses(substr($dt_semana[0], 5, 2)) ?>  à <?= substr($dt_semana[4], -2) ?> de <?= dataErp::meses(substr($dt_semana[4], 5, 2)) ?>
                                <?php
                            }
                            ?>
                        </p>
                    </div>
                </div>
                <div class="col-3" style=" text-align: right; padding-right: 50px">
                    <?php
                    if ($semana < 52) {
                        ?>
                        <form method="POST">
                            <?=
                            formErp::hidden([
                                'semana' => $semana + 1,
                                'id_pl' => $id_pl
                            ])
                            ?>
                            <button class="btn btn-outline-info" >
                                <img style="width: 30px" src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/ir.png" >
                            </button>
                        </form>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <br />

            <div class="border">
                <table class="table table-bordered table-hover table-striped">
                    <tr style="font-weight: bold; background-color: mintcream !important ">
                        <td>
                            Aula
                        </td>
                        <?php
                        foreach ($diasDaSemana as $numSem => $nomeSem) {
                            $d = explode('-', $dt_semana[$numSem - 1]);
                            $dt = $d[2] . '/' . $d[1];
                            ?>
                            <td style="text-align: center">
                                <p>
                                    <?= $nomeSem ?>  
                                </p>
                                <p>
                                    <?= $dt ?>
                                </p>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                    foreach (range(1, 5) as $aula) {
                        if (!empty($aulas[$aula])) {
                            ?>
                            <tr>
                                <td>
                                    <?= $aula ?>ª Aula  
                                </td>
                                <?php
                                foreach ($diasDaSemana as $numSem => $nomeSem) {
                                    ?>
                                    <td>
                                        <?php
                                        if (!empty($aulas[$aula][$numSem])) {
                                            $turmaDisc = $aulas[$aula][$numSem];
                                            if (empty($conteudo[$dt_semana[$numSem - 1]][$aulas[$aula][$numSem]['id_turma']][$aulas[$aula][$numSem]['iddisc']])) {
                                                $img = 'n';
                                            } else {
                                                $img = 's';
                                            }
                                            if (empty($chamada[$dt_semana[$numSem - 1]][$aulas[$aula][$numSem]['id_turma']][$aulas[$aula][$numSem]['iddisc']])) {
                                                $img2 = 'n';
                                            } else {
                                                $img2 = 's';
                                            }
                                            ?>
                                            <div style=" font-weight: bold; text-align: center">
                                                <p>
                                                    <?= $turmaDisc['n_turma'] ?>  
                                                </p>
                                                <p>
                                                    <?= $turmaDisc['disc'] ?>  
                                                </p>
                                                <?php
                                                if (in_array($aulas[$aula][$numSem]['id_ciclo'], [1, 2, 3, 4, 5, 6, 7, 8, 9, 25, 26, 27, 28, 29, 30, 31, 34, 35, 36, 37])) {
                                                    ?>
                                                    <p>
                                                        Conteúdo <img src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/<?= $img ?>.png" width="16" height="16" alt="n"/>
                                                    </p>
                                                    <?php
                                                }
                                                ?>
                                                <p>
                                                    Chamada <img src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/<?= $img2 ?>.png" width="16" height="16" alt="n"/>
                                                </p>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </table>
            </div>
            <?php
        }
        ?>
    </div>
    <?php



} else {
?>
<div class="aler alert-danger" style="text-align: center; font-weight: bold; font-size: 1.6em; padding: 50px">
    <p>
        Parece que você não está alocado(a).
    </p>
    <p>
        Procure a secretaria de sua escola.
    </p>
</div>
                                    <?php    
}
    function horario($id_pessoa) {
        $disc_ = sqlErp::get('ge_disciplinas', 'id_disc, sg_disc n_disc');
        $disc = toolErp::idName($disc_);
        $disc['nc'] = 'Polivalente';
        $sql = "SELECT t.periodo, t.n_turma, t.fk_id_ciclo, h.* FROM ge_aloca_prof ap "
                . " JOIN ge_horario h on h.fk_id_turma = ap.fk_id_turma AND ap.iddisc = h.iddisc "
                . " JOIN ge_funcionario f on f.rm = ap.rm "
                . " join ge_turmas t on t.id_turma = ap.fk_id_turma AND t.fk_id_ciclo != 32"
                . " WHERE f.fk_id_pessoa = $id_pessoa "
                . " ORDER BY h.fk_id_turma, h.aula ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($array) {
            foreach ($array as $v) {
                $aula[$v['periodo']][$v['aula']][$v['dia_semana']] = [
                    'id_turma' => $v['fk_id_turma'],
                    'id_ciclo' => $v['fk_id_ciclo'],
                    'iddisc' => $v['iddisc'],
                    'n_turma' => $v['n_turma'],
                    'disc' => $disc[$v['iddisc']]
                ];
            }
            return $aula;
        } else {
            return;
        }
    }