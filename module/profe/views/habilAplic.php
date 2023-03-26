<?php
if (!defined('ABSPATH'))
    exit;
$id_pl = ng_main::periodoSet();
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_NUMBER_INT);
$id_tcd = filter_input(INPUT_POST, 'id_tcd');
if ($id_tcd) {
    $id_tcdArr = explode('_', $id_tcd);
    $id_ciclo = $id_tcdArr[0];
    $iddisc = $id_tcdArr[1];
    if ($iddisc != 'nc') {
        $id_disc = $iddisc;
    }
} else {
    $iddisc = null;
    $id_ciclo = null;
}

$disc = ng_main::disciplinasCurso(1);

$id_pessoa = tool::id_pessoa();
$id_pls = ng_main::periodosAtivos();
$cicloDiscEsc = ng_professor::ciclosDisc($id_pessoa, null, NULL, $id_pls);

if ($cicloDiscEsc) {
    ?>
    <div class="body">
        <div class="fieldTop">
            Habilidades Aplicadas
        </div>
        <div class="row">
            <div class="col">
                <?php
                foreach ($cicloDiscEsc as $v) {
                    if (in_array($v['id_curso'], [1, 5, 9])) {
                        $tcd[$v['id_ciclo'] . '_' . $v['iddisc']] = $v['n_ciclo'] . ' - ' . $v['n_disc'];
                    }
                }
                echo formErp::select('id_tcd', $tcd, 'Ciclo/Disciplina/Escola', $id_tcd, 1)
                ?>
            </div>
            <div class="col">
                <?php
                if ($id_ciclo && $iddisc == 'nc') {
                    echo formErp::select('id_disc', $disc, 'Disciplina', $id_disc, 1, ['id_tcd' => $id_tcd]);
                }
                ?>
            </div>
        </div>
        <br />
        <?php
        if ($id_ciclo && $id_disc) {
            ?>
            <div class="row">
                <div class="col text-center">
                    <button class="btn btn-primary">Habilidade Aplicada</button>
                </div>
                <div class="col text-center">
                    <button class="btn btn-warning">Habilidade Não Aplicada</button>
                </div>
            </div>
            <br />
            <?php
            $tpd = $model->turmaDisciplina($id_pessoa);
            foreach ($tpd as $k => $v) {
                if (!is_numeric($k)) {
                    if ($iddisc == $v['id_disc'] && $id_ciclo == $v['id_ciclo']) {
                        $turmas[$v['id_turma']] = $v['nome_turma'] . ' - ' . $v['escola'];
                    }
                    $idTurmas[] = (string) $v['id_turma'];
                }
            }
            $habAplic = hab::habAplic($id_pl, $idTurmas);

            @$habList = current(current(hab::habilidadesList($id_ciclo, $id_disc)));
            if ($habList) {
                foreach ($habList as $h) {
                    if (!empty($h['atual_letiva'])) {
                        foreach (explode(',', $h['atual_letiva']) as $b) {
                            if (!empty($b)) {
                                $bim[$h['id_hab']][] = $b;
                            }
                        }
                    }
                }
                foreach ($habList as $h) {
                    $id_hab = $h['id_hab'];
                    ?>
                    <table class="table table-bordered table-hover table-striped border" style="background-color: white">
                        <tr>
                            <td>
                                <p>
                                    <?= $h['codigo'] ?> - <?= $h['descricao'] ?>
                                </p>
                                <p>
                                    <?php
                                    if (!empty($bim[$h['id_hab']])) {
                                        echo toolErp::virgulaE($bim[$h['id_hab']], 'º Bimestre');
                                    }
                                    ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="row">
                                    <?php
                                    $ct = 1;
                                    foreach ($turmas as $id_turma => $n) {
                                        if (empty($habAplic[$id_hab])) {
                                            $btn = 'warning';
                                        } elseif (in_array($id_turma, $habAplic[$id_hab])) {
                                            $btn = 'primary';
                                        } else {
                                            $btn = 'warning';
                                        }
                                        ?>
                                        <div class="col-4">
                                            <button class="btn btn-<?= $btn ?>">
                                                <?= $n ?>
                                            </button>
                                        </div>
                                        <?php
                                        if ($ct++ >= 3) {
                                            $ct = 1;
                                            ?>
                                        </div>
                                        <br />
                                        <div class="row">
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <br /><br />
                    <?php
                }
            }
        }
        ?>
    </div>
    <?php
}