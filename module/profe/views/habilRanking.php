<?php
if (!defined('ABSPATH'))
    exit;
if (toolErp::id_nilvel() == 48) {
    $id_inst = toolErp::id_inst();
} else {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
}
$esc = ng_escolas::idEscolas([1]);
$bimest = @$_POST['bimest'];
$id_pl = ng_main::periodoSet(filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT));
$id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_NUMBER_INT);
$pls = ng_main::periodosPorSituacao();
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
        Ranqueamento das Habilidades Aplicadas
    </div>
    <form id="pesquisa" method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::select('id_ciclo', $ciclos, 'Ciclo', $id_ciclo) ?>
            </div>
            <div class="col">
                <?= formErp::select('id_disc', $disc, 'Disciplina', $id_disc) ?>
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
        </div>
        <br />
        <div class="row">
            <div class="col text-center">
                <button type="submit" class="btn btn-info" >
                    Pesquisar
                </button>
            </div>
        </div>
        <br />
    </form>
    <br /><br />
    <?php
    if (!empty($turmaList[0])) {
        foreach ($turmaList[0] as $id_cicloSet => $turmas) {
            if (!empty($habList[$id_cicloSet])) {
                foreach ($habList[$id_cicloSet] as $id_discSet => $habi) {
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
                        if (in_array($id_cicloSet, [1, 2, 3, 4, 5]) && in_array($id_discSet, [6, 9, 10, 12, 13, 14])) {
                            $id_disc_cons = 'nc';
                            $disc_cons = 'Núcleo Comum';
                        } else {
                            $id_disc_cons = $id_discSet;
                            $disc_cons = $disc[$id_discSet];
                        }
                        foreach ($turmas as $id_turmaSet => $n) {
                            $rk[$ciclos[$id_cicloSet]][$disc[$id_discSet]][$id_hab]['n_disc'] = $disc_cons;
                            $rk[$ciclos[$id_cicloSet]][$disc[$id_discSet]][$id_hab]['id_disc'] = $id_disc_cons;
                            $rk[$ciclos[$id_cicloSet]][$disc[$id_discSet]][$id_hab]['habilidade'] = $h['descricao'];
                            $rk[$ciclos[$id_cicloSet]][$disc[$id_discSet]][$id_hab]['codigo'] = $h['codigo'];
                            @$rk[$ciclos[$id_cicloSet]][$disc[$id_discSet]][$id_hab]['tTurmas']++;
                            if (!empty($bim[$id_cicloSet][$h['id_hab']])) {
                                $rk[$ciclos[$id_cicloSet]][$disc[$id_discSet]][$id_hab]['bimestre'] = toolErp::virgulaE($bim[$id_cicloSet][$h['id_hab']], 'º Bimestre');
                            }
                            if (empty($habAplic[$id_hab])) {
                                @$rk[$ciclos[$id_cicloSet]][$disc[$id_discSet]][$id_hab]['naoAplicada']++;
                                @$rk[$ciclos[$id_cicloSet]][$disc[$id_discSet]][$id_hab]['turmaNaoAplic'][$esc[$n['id_inst']]][$id_turmaSet] = $n['n_turma'];
                                @$rk[$ciclos[$id_cicloSet]][$disc[$id_discSet]][$id_hab]['EscNaoAplicada'][$esc[$n['id_inst']]]++;
                            } elseif (in_array($id_turmaSet, $habAplic[$id_hab])) {
                                @$rk[$ciclos[$id_cicloSet]][$disc[$id_discSet]][$id_hab]['aplicada']++;
                                @$rk[$ciclos[$id_cicloSet]][$disc[$id_discSet]][$id_hab]['EscAplicada'][$esc[$n['id_inst']]]++;
                                @$rk[$ciclos[$id_cicloSet]][$disc[$id_discSet]][$id_hab]['turmaAplic'][$esc[$n['id_inst']]][$id_turmaSet] = $n['n_turma'];
                            } else {
                                @$rk[$ciclos[$id_cicloSet]][$disc[$id_discSet]][$id_hab]['naoAplicada']++;
                                @$rk[$ciclos[$id_cicloSet]][$disc[$id_discSet]][$id_hab]['EscNaoAplicada'][$esc[$n['id_inst']]]++;
                                @$rk[$ciclos[$id_cicloSet]][$disc[$id_discSet]][$id_hab]['turmaNaoAplic'][$esc[$n['id_inst']]][$id_turmaSet] = $n['n_turma'];
                            }
                        }
                    }
                }
            }
        }
        if (empty($rk)) {
            echo 'Não há habilidades cadastradas para os critérios escolhidos';
        } else {
            foreach ($rk as $ciclo => $a) {
                foreach ($a as $disc => $b) {
                    foreach ($b as $id_hab => $c) {
                        $porc = intval((intval(@$c['aplicada']) / $c['tTurmas']) * 100);
                        $index = ($porc * 1000000000) + $id_hab;
                        $ranking[$ciclo][$disc][$index] = $c;
                        $ranking[$ciclo][$disc][$index]['porc'] = $porc;
                    }
                }
            }
            foreach ($ranking as $ciclo => $a) {
                foreach ($a as $disc => $b) {
                    krsort($ranking[$ciclo][$disc]);
                }
            }
            foreach ($ranking as $ciclo => $a) {
                ?>
                <div class="border" style="background-color: <?= $cores[$cor++] ?>;">
                    <div class="fieldTop" style="font-weight: bold; font-size: 2em;">
                        <?= $ciclo ?>
                    </div>
                    <?php
                    foreach ($a as $disc => $b) {
                        ?>
                        <div class="border" style=" border: #000000 double medium; background-color: <?= $cores[$cor++] ?>;">
                            <div style="font-weight: bold; font-size: 2em;">
                                <?= $disc ?>
                            </div>
                            <?php
                            foreach ($b as $kc => $c) {
                                ?>
                                <style>
                                    #list td{
                                        text-align: center;
                                        font-size: 1.3em !important;
                                    }
                                </style>
                                <table id="list" class="table table-bordered table-hover table-striped border" style="background-color: white">
                                    <tr>
                                        <td colspan="5" style="text-align: left; font-weight: bold">
                                            <?= $c['codigo'] ?> - <?= $c['habilidade'] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Porcentagem
                                        </td>
                                        <td>
                                            Aplicadas
                                        </td>
                                        <td>
                                            Não aplicadas
                                        </td>
                                        <td>
                                            Total de Turmas
                                        </td>
                                        <td>
                                            Bimeste(s)
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?= $c['porc'] ?>%
                                        </td>
                                        <td>
                                            <?= intval(@$c['aplicada']) ?>
                                        </td>
                                        <td>
                                            <?= intval(@$c['naoAplicada']) ?>
                                        </td>
                                        <td>
                                            <?= $c['tTurmas'] ?>
                                        </td>
                                        <td>
                                            <?= @$c['bimestre'] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">
                                            <?php
                                            if ($c['porc'] < 50) {
                                                $btn = 'danger';
                                            } elseif ($c['porc'] < 75) {
                                                $btn = 'warning';
                                            } else {
                                                $btn = 'info';
                                            }
                                            ?>
                                            <div class="progress">
                                                <div class="progress-bar bg-<?= $btn ?>" role="progressbar" style="width: <?= $c['porc'] ?>%" aria-valuenow="<?= $c['porc'] ?>" aria-valuemin="0" aria-valuemax="100"><?= $c['porc'] ?>%</div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="<?= $kc ?>_a">
                                        <td colspan="5">
                                            <button class="btn btn-success"  onclick="abr(<?= $kc ?>)">
                                                Situação por escola
                                            </button>
                                        </td>
                                    </tr>
                                    <tr id="<?= $kc ?>_f"  style="display: none">
                                        <td colspan="5">
                                            <button class="btn btn-outline-success"  onclick="abrf(<?= $kc ?>)">
                                                Situação por escola
                                            </button>
                                        </td>
                                    </tr>
                                    <tr id="<?= $kc ?>"  style="display: none" >
                                        <td colspan="5">
                                            <table class="table table-bordered table-hover table-responsive">
                                                <?php
                                                foreach ($esc as $ke => $e) {
                                                    if (!empty($c['turmaAplic'][$e]) || !empty($c['turmaNaoAplic'][$e])) {
                                                        ?>
                                                        <tr>
                                                            <td style="text-align: left">
                                                                <?= $e ?>
                                                            </td>
                                                            <td style="text-align: left">
                                                                <?php
                                                                if (!empty($c['turmaAplic'][$e])) {
                                                                    foreach ($c['turmaAplic'][$e] as $kt => $t) {
                                                                        ?>
                                                                        <button onclick="consolidado('<?= $kt ?>', '<?= $c['id_disc'] ?>', '<?= $c['n_disc'] ?>', '<?= $e ?>')" class="btn btn-primary">
                                                                            <?= $t ?>
                                                                        </button>
                                                                        <?php
                                                                    }
                                                                }
                                                                if (!empty($c['turmaNaoAplic'][$e])) {
                                                                    foreach ($c['turmaNaoAplic'][$e] as $kt => $t) {
                                                                        ?>
                                                                        <button onclick="consolidado('<?= $kt ?>', '<?= $c['id_disc'] ?>', '<?= $c['n_disc'] ?>', '<?= $e ?>')" class="btn btn-danger">
                                                                            <?= $t ?>
                                                                        </button>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <?php
                            }
                            ?>
                        </div>
                        <br />
                        <?php
                    }
                }
                ?>
            </div>
            <?php
        }
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/profe/consolidado" target="frame" id="cons" method="POST">
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

<script>
    function abr(id) {
        ab = document.getElementById(id + '_a');
        abf = document.getElementById(id + '_f');
        er = document.getElementById(id);
        er.style.display = '';
        ab.style.display = 'none';
        abf.style.display = '';
    }
    function abrf(id) {
        ab = document.getElementById(id + '_a');
        abf = document.getElementById(id + '_f');
        er = document.getElementById(id);
        er.style.display = 'none';
        ab.style.display = '';
        abf.style.display = 'none';
    }
</script>