<?php
if (!defined('ABSPATH'))
    exit;
$sistema = $model->getSistema('22','48,2,18,53,54,55,24,56');
if (in_array(toolErp::id_nilvel(),[54,22])) {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
} else {
    $id_inst = toolErp::id_inst();
}
$bimest = @$_POST['bimest'];
$id_pl = ng_main::periodoSet(filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT));
$id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_NUMBER_INT);
$pls = ng_main::periodosPorSituacao();
$esc = ng_escolas::idEscolas([1]);
if (toolErp::id_nilvel() == 22) {
    $esc = ng_escolas::idEscolasSupervisor(tool::id_pessoa(),[1]);
}else{
    $esc = ng_escolas::idEscolas([1]);
}
$porEscola = filter_input(INPUT_POST, 'porEscola', FILTER_SANITIZE_NUMBER_INT);
$ciclos = sql::idNome('ge_ciclos', ['fk_id_curso' => 1]);
if (!empty($bimest)) {
    foreach ($bimest as $v) {
        if ($v != 0) {
            $testBim = 1;
        }
    }
}
$disc = ng_main::disciplinasCurso(1);
unset($ciclos[31]);
$cores = [
    'MistyRose',
    'LemonChiffon',
    'AliceBlue',
    'PaleGreen',
    'Gainsboro',
    'LightGoldenrodYellow',
    'AntiqueWhite',
    'Ivory',
    'Lavender',
    'MistyRose',
    'LemonChiffon',
    'AliceBlue',
    'PaleGreen',
    'Gainsboro',
    'LightGoldenrodYellow',
    'AntiqueWhite',
    'Ivory',
    'Lavender',
    'MistyRose',
    'LemonChiffon',
    'AliceBlue',
    'PaleGreen',
    'Gainsboro',
    'LightGoldenrodYellow',
    'AntiqueWhite',
    'Ivory',
    'Lavender',
];
$cor = 1;
if ($id_pl && ($id_ciclo || $id_disc)) {
    $habList = hab::habilidadesList($id_ciclo, $id_disc);
    $turmaList = hab::turmasCiclo($id_pl, $id_inst, $id_ciclo);
    $habAplic = hab::habAplic($id_pl, $turmaList[2]);
    $bim = [];
    if (!empty($habList)) {
        foreach ($habList as $k => $v1) {
            foreach ($v1 as $v2) {
                foreach ($v2 as $v3) {
                    if (!empty($v3['atual_letiva'])) {
                        foreach (explode(',', $v3['atual_letiva']) as $b) {
                            if (!empty($b)) {
                                $bim[$k][$v3['id_hab']][] = $b;
                            }
                        }
                    }
                }
            }
        }
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Habilidades Aplicadas
    </div>
    <form id="pesquisa" method="POST">
        <div class="row">
            <!--
            <div class="col">
            <?= formErp::select('id_pl', $pls, 'Período letivo', $id_pl) ?>
            </div>
            -->
            <div class="col">
                <?= formErp::select('id_ciclo', $ciclos, 'Ciclo', $id_ciclo) ?>
            </div>
            <div class="col">
                <?= formErp::select('id_disc', $disc, 'Disciplina', $id_disc) ?>
            </div>
            <?php
            if (in_array(toolErp::id_nilvel(),[54,22])) {
                ?>
                <div class="col">
                    <?= formErp::select('id_inst', $esc, 'Escola', $id_inst) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col">
                    <?= formErp::select('porEscola', [0 => 'Não', 1 => 'Sim'], 'Visualizar por Escola', $porEscola) ?>
                </div>
                <div class="col">
                    <table style="width: 100%">
                        <tr>
                            <?php
                            foreach (range(1, 4) as $bb) {
                                ?>
                                <td><?= formErp::checkbox('bimest[' . $bb . ']', 1, $bb . 'º Bim.', empty($bimest[$bb]) ? 0 : 1) ?></td>
                                <?php
                            }
                            ?>
                        </tr>
                    </table>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-info" >
                        Pesquisar
                    </button>
                </div>
                <?php
            } else {
                ?>
                <div class="col">
                    <button type="submit" class="btn btn-info" >
                        Pesquisar
                    </button>
                </div>
                <?php
            }
            ?>
        </div>
        <br />
    </form>
    <div class="row">
        <div class="col text-center alert alert-primary">
            Habilidade Aplicada
        </div>
        <div class="col text-center alert alert-warning">
            Habilidade Não Aplicada
        </div>
    </div>
    <br /><br />
    <?php
    if (empty($porEscola) && !empty($turmaList[0])) {
        foreach ($turmaList[0] as $id_cicloSet => $turmas) {
            ?>
            <div class="border" style="background-color: <?= $cores[$cor++] ?>;">
                <div class="fieldTop" style="font-weight: bold; font-size: 2em;">
                    <?= $ciclos[$id_cicloSet] ?>
                </div>
                <?php
                if (!empty($habList[$id_cicloSet])) {
                    foreach ($habList[$id_cicloSet] as $id_discSet => $habi) {
                        ?>
                        <div class="border" style=" border: #000000 double medium; background-color: <?= $cores[$cor++] ?>;">
                            <div style="font-weight: bold; font-size: 2em;">
                                <?= $disc[$id_discSet] ?>
                            </div>
                            <br />
                            <?php
                            foreach ($habi as $id_hab => $h) {
                                if (!empty($testBim)) {
                                    if (!empty($bim[$id_cicloSet][$h['id_hab']])) {
                                        $mostraBim = null;
                                        foreach ($bimest as $bm => $bmv) {
                                            if (in_array($bm, $bim[$id_cicloSet][$h['id_hab']]) && !empty($bmv)) {
                                                $mostraBim = 1;
                                            }
                                        }
                                        if (empty($mostraBim)) {
                                            continue;
                                        }
                                    } else {
                                        continue;
                                    }
                                }
                                ?>
                                <table class="table table-bordered table-hover table-striped border" style="background-color: white">
                                    <tr>
                                        <td>
                                            <p>
                                                <?= $h['codigo'] ?> - <?= $h['descricao'] ?>
                                            </p>
                                            <p>
                                                <?php
                                                if (!empty($bim[$id_cicloSet][$h['id_hab']])) {
                                                    echo toolErp::virgulaE($bim[$id_cicloSet][$h['id_hab']], 'º Bimestre');
                                                }
                                                ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php
                                            foreach ($turmas as $id_turmaSet => $n) {
                                                if (in_array($id_cicloSet, [1, 2, 3, 4, 5]) && in_array($id_discSet, [6, 9, 10, 12, 13, 14])) {
                                                    $id_disc_cons = 'nc';
                                                    $disc_cons = 'Núcleo Comum';
                                                } else {
                                                    $id_disc_cons = $id_discSet;
                                                    $disc_cons = $disc[$id_discSet];
                                                }
                                                if (empty($habAplic[$id_hab])) {
                                                    $btn = 'warning';
                                                } elseif (in_array($id_turmaSet, $habAplic[$id_hab])) {
                                                    $btn = 'primary';
                                                } else {
                                                    $btn = 'warning';
                                                }
                                                ?>
                                                <button onclick="consolidado('<?= $id_turmaSet ?>', '<?= $id_disc_cons ?>', '<?= $disc_cons ?>', '<?= $esc[$n['id_inst']] ?>')" class="btn btn-<?= $btn ?>"  title="<?= $n['n_turma'] ?> da <?= $esc[$n['id_inst']] ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip">
                                                    <?= $id_inst ? $n['n_turma'] : null ?>
                                                </button>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                                <br /><br />
                                <?php
                            }
                            ?>
                        </div>
                        <br /><br />
                        <?php
                    }
                }
                ?>
            </div>
            <br /><br />
            <?php
        }
    } elseif (!empty($turmaList[1])) {
        foreach ($turmaList[1] as $id_instSet => $cicloSet) {
            ?>
            <div class="border"  style="background-color: <?= $cores[$cor++] ?>;">
                <div style="font-weight: bold; font-size: 2em;">
                    <?= $esc[$id_instSet] ?>
                </div>
                <br />
                <?php
                foreach ($cicloSet as $id_cicloSet => $turmas) {
                    ?>
                    <div class="border" style="background-color: <?= $cores[$cor++] ?>;">
                        <div class="fieldTop" style="font-weight: bold; font-size: 2em;">
                            <?= $ciclos[$id_cicloSet] ?>
                        </div>
                        <?php
                        if (!empty($habList[$id_cicloSet])) {
                            foreach ($habList[$id_cicloSet] as $id_discSet => $habi) {
                                ?>
                                <div class="border" style=" border: #000000 double medium; background-color: <?= $cores[$cor++] ?>;">
                                    <div style="font-weight: bold; font-size: 2em;">
                                        <?= $disc[$id_discSet] ?>
                                    </div>
                                    <br />
                                    <?php
                                    foreach ($habi as $id_hab => $h) {
                                        ?>
                                        <table class="table table-bordered table-hover table-striped border" style="background-color: white">
                                            <tr>
                                                <td>
                                                    <?= $h['codigo'] ?> - <?= $h['descricao'] ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php
                                                    foreach ($turmas as $id_turmaSet => $n) {
                                                        if (in_array($id_cicloSet, [1, 2, 3, 4, 5]) && in_array($id_discSet, [6, 9, 10, 12, 13, 14])) {
                                                            $id_disc_cons = 'nc';
                                                            $disc_cons = 'Núcleo Comum';
                                                        } else {
                                                            $id_disc_cons = $id_discSet;
                                                            $disc_cons = $disc[$id_discSet];
                                                        }
                                                        if (empty($habAplic[$id_hab])) {
                                                            $btn = 'warning';
                                                        } elseif (in_array($id_turmaSet, $habAplic[$id_hab])) {
                                                            $btn = 'primary';
                                                        } else {
                                                            $btn = 'warning';
                                                        }
                                                        ?>
                                                        <button  onclick="consolidado('<?= $id_turmaSet ?>', '<?= $id_disc_cons ?>', '<?= $disc_cons ?>', '<?= $esc[$n['id_inst']] ?>')" class="btn btn-<?= $btn ?>"  title="<?= $n['n_turma'] ?> da <?= $esc[$n['id_inst']] ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip">
                                                            <?= $id_inst || $porEscola ? $n['n_turma'] : null ?>
                                                        </button>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                        <br /><br />
                                        <?php
                                    }
                                    ?>
                                </div>
                                <br /><br />
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <br /><br />
                    <?php
                }
                ?>
            </div>
            <br /><br />
            <?php
        }
    }
    ?>
    <form action="<?= HOME_URI ?>/<?= $sistema ?>/consolidado" target="frame" id="cons" method="POST">
        <?=
        formErp::hidden([
            'id_curso' => 1,
            'id_pl' => $id_pl
        ])
        ?>
        <input type="hidden" name="n_disc" id="n_disc" value="" />
        <input type="hidden" name="id_turma" id="id_turma" value="" />
        <input type="hidden" name="id_disc" id="id_disc" value="" />
        <input type="hidden" name="n_inst" id="n_inst" value="" />
    </form>
    <?php
    toolErp::modalInicio();
    ?>
    <iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
        <?php
        toolErp::modalFim();
        ?>
    <script>
        function consolidado(idTurma, idDisc, nDisc, nInst) {
            n_disc.value = nDisc;
            id_turma.value = idTurma;
            id_disc.value = idDisc;
            n_inst.value = nInst;
            cons.submit();
            $('#myModal').modal('show');
            $('.form-class').val('');
        }
    </script>
