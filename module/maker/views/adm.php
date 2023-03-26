<?php
if (!defined('ABSPATH'))
    exit;
$cotasT = 0;
$setup = sql::get(['maker_setup', 'ge_periodo_letivo'], '*', null, 'fetch');
$id_pl = $setup['fk_id_pl_matr'];
$sql = "SELECT periodo, COUNT(periodo) ct FROM maker_gt_turma "
        . " where fk_id_pl = $id_pl  AND fk_id_ciclo = 1 "
        . " GROUP BY periodo ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($array as $v) {
    $mt[$v['periodo']] = $v['ct'];
}
$polos = $model->poloComEscolas();
$dados = $model->dadosInscr();
$nc_ = sql::get('maker_nao_contempla');
$ncManha = 0;
$ncTarde = 0;
if ($nc_) {
    foreach ($nc_ as $v) {
        $ncManha += $v['qt_manha'];
        $ncTarde += $v['qt_tarde'];
        $nc[$v['id_inst_nc']]['M'] = @$v['qt_manha'];
        $nc[$v['id_inst_nc']]['T'] = @$v['qt_tarde'];
    }
}

$manha = @$mt['M'] * 32;
$tarde = @$mt['T'] * 32;
if (empty($tarde)) {
    $tarde = 1;
}
if (empty($manha)) {
    $manha = 1;
}
?>
<div class="body">
    <div style="text-align: right; padding: 20px">
        <a class="btn btn-info" href="<?= HOME_URI ?>/maker/rankingMatr" target="_blank">
            Exportar
        </a>
        <a class="btn btn-primary" href="<?= HOME_URI ?>/maker/rankingMatrVagas" target="_blank">
            Exportar vagar
        </a>
    </div>
    <?php
    include ABSPATH . '/module/maker/views/_adm/graf.php';
    @$manhaBasico = $dados['per']['Manhã']['Básico'];
    @$manhaInter = $dados['per']['Manhã']['Intermediário'];
    @$manhaAv1 = $dados['per']['Manhã']['Avançado 1'];
    @$tardeBasico = $dados['per']['Tarde']['Básico'];
    @$tardeInter = $dados['per']['Tarde']['Intermediário'];
    @$tardeAv1 = $dados['per']['Tarde']['Avançado 1'];
    ?>
    <br /><br />
    <table class="table table-bordered table-striped" style="font-weight: bold">
        <tr>
            <td rowspan="2" style="width: 10%; text-align: center"></td>
            <td rowspan="2" style="width: 10%; text-align: center; padding: 20px">
                Básico
            </td>
            <td rowspan="2" style="width: 10%; text-align: center; padding: 20px">
                Disponíveis para o Básico
            </td>
            <td rowspan="2" style="width: 10%; text-align: center; padding: 20px">
                Não Contemplados
            </td>
            <td colspan="4" style="text-align: center">
                Preenchidas
            </td>
        </tr>
        <tr>
            <td style="text-align: center; color: white" class="bg-primary">
                Básico
            </td>
            <td style="text-align: center; color: white" class="bg-danger">
                Intermediário
            </td>
            <td style="text-align: center; color: white" class="bg-info">
                Avançado 1
            </td>
            <td style="text-align: center; color: white" class="bg-success">
                Total
            </td>
        </tr>
        <tr>
            <td style="text-align: center">
                Manhã
            </td>
            <td style="text-align: center">
                <?= $manha ?>
            </td>
            <td style="text-align: center">
                <?= $manha - $manhaBasico ?>
            </td>
            <td style="text-align: center">
                <?= $ncManha ?>
            </td>
            <td style="text-align: center">
                <?= $manhaBasico ?>
            </td>
            <td style="text-align: center">
                <?= $manhaInter ?>
            </td>
            <td style="text-align: center">
                <?= $manhaAv1 ?>
            </td>
            <td style="text-align: center">
                <?= $manhaBasico + $manhaInter + $manhaAv1 ?>
            </td>
        </tr>
        <tr>
            <td style="text-align: center">
                Tarde
            </td>
            <td style="text-align: center">
                <?= $tarde ?>
            </td>
            <td style="text-align: center">
                <?= $tarde - $tardeBasico ?>
            </td>
            <td style="text-align: center">
                <?= $ncTarde ?>
            </td>
            <td style="text-align: center">
                <?= $tardeBasico ?>
            </td>
            <td style="text-align: center">
                <?= $tardeInter ?>
            </td>
            <td style="text-align: center">
                <?= $tardeAv1 ?>
            </td>
            <td style="text-align: center">
                <?= $tardeBasico + $tardeInter + $tardeAv1 ?>
            </td>
        </tr>
        <tr>
            <td style="text-align: center">
                Total
            </td>
            <td style="text-align: center">
                <?= $totalVagas = $tarde + $manha ?>
            </td>
            <td style="text-align: center">
                <?= ($tarde - $tardeBasico) + ($manha - $manhaBasico) ?>
            </td>
            <td style="text-align: center">
                <?= $ncManha + $ncTarde ?>
            </td>
            <td style="text-align: center">
                <?= $tardeBasico + $manhaBasico ?>
            </td>
            <td style="text-align: center">
                <?= $totalInter = $tardeInter + $manhaInter ?>
            </td>
            <td style="text-align: center">
                <?= $totalAv1 = $tardeAv1 + $manhaAv1 ?>
            </td>
            <td style="text-align: center">
                <?= $totalBasico = $tardeBasico + $tardeInter + $tardeAv1 + $manhaBasico + $manhaInter + $manhaAv1 ?>
            </td>
        </tr>
    </table>
    <br />
    <table class="table table-bordered border">
        <tr>
            <td style="width: 200px; font-size: 1.2em; padding-top: 15px">
                Manhã (<?= $manha ?> Vagas)
            </td>
            <td>
                <div class="progress" style="font-size: 1.3em; height: 30px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?= ($manhaBasico / $manha) * 100 ?>%;" aria-valuenow="<?= ($manhaBasico / $manha) * 100 ?>" aria-valuemin="0" aria-valuemax="100">
                        Básico (<?= $manhaBasico ?> - <?= intval(($manhaBasico / $manha) * 100) ?>%)
                    </div>
                    <div class="progress-bar bg-danger" role="progressbar" style="width: <?= ($manhaInter / $manha) * 100 ?>%" aria-valuenow="<?= (@$manhaInter / @$manha) * 100 ?>" aria-valuemin="0" aria-valuemax="100">
                        Intermediário  (<?= @$manhaInter ?> - <?= intval((@$manhaInter / @$manha) * 100) ?>%)
                    </div>
                    <div class="progress-bar bg-info" role="progressbar" style="width: <?= ($manhaAv1 / $manha) * 100 ?>%" aria-valuenow="<?= ($manhaInter / $manha) * 100 ?>" aria-valuemin="0" aria-valuemax="100">
                        Avançado 1  (<?= $manhaAv1 ?> - <?= intval(($manhaAv1 / $manha) * 100) ?>%)
                    </div>
                </div>   
            </td>
        </tr>
        <tr>
            <td style="font-size: 1.2em; padding-top: 15px">
                Tarde (<?= $tarde ?> Vagas)
            </td>
            <td>
                <div class="progress" style="font-size: 1.3em; height: 30px;">
                    <div class="progress-bar  bg-primary" role="progressbar" style="width: <?= ($tardeBasico / $manha) * 100 ?>%;" aria-valuenow="<?= ($tardeBasico / $manha) * 100 ?>" aria-valuemin="0" aria-valuemax="100">
                        Básico (<?= $tardeBasico ?> - <?= intval(($tardeBasico / $tarde) * 100) ?>%)
                    </div>
                    <div class="progress-bar bg-danger" role="progressbar" style="width: <?= ($tardeInter / $manha) * 100 ?>%" aria-valuenow="<?= ($tardeInter / $manha) * 100 ?>" aria-valuemin="0" aria-valuemax="100">
                        Intermediário  (<?= $tardeInter ?> - <?= intval(($tardeInter / $tarde) * 100) ?>%)
                    </div>
                    <div class="progress-bar bg-info" role="progressbar" style="width: <?= ($tardeAv1 / $manha) * 100 ?>%" aria-valuenow="<?= ($tardeInter / $manha) * 100 ?>" aria-valuemin="0" aria-valuemax="100">
                        Avançado 1  (<?= $tardeAv1 ?> - <?= intval(($tardeAv1 / $tarde) * 100) ?>%)
                    </div>
                </div>  
            </td>
        </tr>
        <tr>
            <td style="font-size: 1.2em; padding-top: 15px">
                Total (<?= $tarde + $manha ?> Vagas)
            </td>
            <td>
                <div class="progress" style="font-size: 1.3em; height: 30px;">
                    <div class="progress-bar  bg-primary" role="progressbar" style="width: <?= intval(($totalBasico / $totalVagas) * 100) ?>%;" aria-valuenow="<?= ($tardeBasico / $manha) * 100 ?>" aria-valuemin="0" aria-valuemax="100">
                        Básico (<?= $totalBasico ?> - <?= intval(($totalBasico / $totalVagas) * 100) ?>%)
                    </div>
                    <div class="progress-bar bg-danger" role="progressbar" style="width: <?= intval(($totalInter / $totalVagas) * 100) ?>%" aria-valuenow="<?= ($tardeInter / $manha) * 100 ?>" aria-valuemin="0" aria-valuemax="100">
                        Intermediário  (<?= $totalInter ?> - <?= intval(($totalInter / $totalVagas) * 100) ?>%)
                    </div>
                    <div class="progress-bar bg-info" role="progressbar" style="width: <?= intval(($totalAv1 / $totalVagas) * 100) ?>%" aria-valuenow="<?= ($tardeInter / $manha) * 100 ?>" aria-valuemin="0" aria-valuemax="100">
                        Avançado 1  (<?= $totalAv1 ?> - <?= intval(($totalAv1 / $totalVagas) * 100) ?>%)
                    </div>
                </div>  
            </td>
        </tr>
    </table>
    <br />
    <?php
    include ABSPATH . '/module/maker/views/_adm/ordena.php';
    ?>
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <td rowspan="2">
                Polo
            </td>
            <td rowspan="2">
                Escola
            </td>
            <td rowspan="2">
                cotas
            </td>
            <?php
            if (!empty($dados['rede'])) {
                foreach ($dados['rede'] as $periodo => $ci) {
                    $col = 0;
                    if ($ci) {
                        foreach ($ci as $ct) {
                            $col += count($ct);
                        }
                    }
                    ?>
                    <td colspan="<?= $col + 1 ?>" style="text-align: center">
                        <?= $periodo ?> 
                    </td>
                    <?php
                }
            }
            ?>
        </tr>
        <tr>
            <?php
            if (!empty($dados['rede'])) {
                foreach ($dados['rede'] as $periodo => $ci) {
                    ?>
                    <td>
                        Não Contemplados
                    </td>

                    <?php
                    foreach ($ci as $ciclo => $stt) {
                        ?>
                        <td colspan="<?= count($stt) ?>">
                            <?= $ciclo ?>
                        </td>
                        <?php
                    }
                }
            }
            ?>
        </tr>
        <?php
        foreach ($polos as $k => $v) {
            $pEsc = $v['escolas'][0];

            unset($v['escolas'][0]);
            ?>
            <tr>
                <td rowspan="<?= (count($v['escolas']) + 1) * 2 ?>">
                    <?= $v['nome'] ?>
                </td>
                <td>
                    <?= abrev($pEsc['n_inst'], 1) ?> <?= $pEsc['sede'] == 1 ? ' (Sede)' : '' ?>
                </td>
                <td style="background-color: #18C0DF;">
                    <?= intval($pEsc['cota_m']) + intval($pEsc['cota_t']) ?>
                    <?php
                    $cotasT += $pEsc['cota_m'] + $pEsc['cota_t'];
                    ?>
                </td>
                <?php
                $ctt = 2;
                if (!empty($dados['rede'])) {
                    $graf = 0;
                    foreach ($dados['rede'] as $periodo => $ci) {
                        $ctt++;
                        ?>
                        <td style="background-color: lightsteelblue; font-weight: bold">
                            <?php
                            if (!empty($nc[$pEsc['id_inst']])) {
                                echo $nc[$pEsc['id_inst']][substr($periodo, 0, 1)];
                            } else {
                                echo '0';
                            }
                            ?>
                        </td>
                        <?php
                        foreach ($ci as $ciclo => $stt) {
                            foreach ($stt as $status => $q) {
                                if ($ciclo == 'Básico') {
                                    $graf += intval(@$dados['esc'][$pEsc['id_inst']][$periodo][$ciclo][$status]);
                                    $style = 'style="background-color: #0d6efd; color: white; font-weight: bold"';
                                } else {
                                    $style = null;
                                }
                                $ctt++;
                                @$total[$periodo][$ciclo][$status] += intval(@$dados['esc'][$pEsc['id_inst']][$periodo][$ciclo][$status]);
                                ?>
                                <td <?= $style ?>>
                                    <?= intval(@$dados['esc'][$pEsc['id_inst']][$periodo][$ciclo][$status]) ?>
                                </td>
                                <?php
                            }
                        }
                    }
                }
                @$bar = ($graf / (intval($pEsc['cota_m']) + intval($pEsc['cota_t']) )) * 100
                ?>
            </tr>
            <tr>
                <td colspan="<?= $ctt ?>">
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $bar ?>%;" aria-valuenow="<?= $bar ?>" aria-valuemin="0" aria-valuemax="100">
                            <?= $graf ?> (<?= intval($bar) ?>%)
                        </div>
                    </div> 
                </td>
            </tr>
            <?php
            if (!empty($v['escolas'])) {
                foreach ($v['escolas'] as $e) {
                    ?>
                    <tr>
                        <td>
                            <?= abrev($e['n_inst'], 1) ?><?= $e['sede'] == 1 ? ' (Sede)' : '' ?>
                        </td>
                        <td style="background-color: #18C0DF">
                            <?= intval($e['cota_m']) + intval($e['cota_t']) ?>
                            <?php
                            $cotasT += $e['cota_m'] + $e['cota_t'];
                            ?>
                        </td>
                        <?php
                        if (!empty($dados['rede'])) {
                            $graf = 0;
                            foreach ($dados['rede'] as $periodo => $ci) {
                                ?>
                                <td style="background-color: lightsteelblue; font-weight: bold">
                                    <?php
                                    if (!empty($nc[$e['id_inst']])) {
                                        echo $nc[$e['id_inst']][substr($periodo, 0, 1)];
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </td>
                                <?php
                                foreach ($ci as $ciclo => $stt) {
                                    foreach ($stt as $status => $q) {
                                        if ($ciclo == 'Básico') {
                                            $graf += intval(@$dados['esc'][$e['id_inst']][$periodo][$ciclo][$status]);
                                            $style = 'style="background-color: #0d6efd; color: white; font-weight: bold"';
                                        } else {
                                            $style = null;
                                        }
                                        @$total[$periodo][$ciclo][$status] += intval(@$dados['esc'][$e['id_inst']][$periodo][$ciclo][$status]);
                                        ?>
                                        <td <?= $style ?>>
                                            <?= intval(@$dados['esc'][$e['id_inst']][$periodo][$ciclo][$status]) ?>
                                        </td>
                                        <?php
                                    }
                                }
                            }
                        }
                        @$bar = ($graf / (intval($e['cota_m']) + intval($e['cota_t']) )) * 100
                        ?>
                    </tr>
                    <tr>
                        <td colspan="<?= $ctt ?>">
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?= $bar ?>%;" aria-valuenow="<?= $bar ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?= $graf ?> (<?= intval($bar) ?>%)
                                </div>
                            </div> 
                        </td>
                    </tr>
                    <?php
                }
            }
        }
        ?>
        <tr>
            <td colspan="2">
                Total
            </td>
            <td>
                <?= $cotasT ?>
            </td>
            <?php
            if (!empty($dados['rede'])) {
                foreach ($dados['rede'] as $periodo => $ci) {
                    ?>
                    <td>
                        <?php
                        if ($periodo == "Manhã") {
                            echo $ncManha;
                        } elseif ($periodo == "Tarde") {
                            echo $ncTarde;
                        } else {
                            echo '0';
                        }
                        ?>
                    </td>
                    <?php
                    foreach ($ci as $ciclo => $stt) {
                        foreach ($stt as $status => $q) {
                            ?>
                            <td>
                                <?php
                                if ($ciclo == 'Básico') {
                                    if ($periodo == "Manhã") {
                                        echo $manhaBasico;
                                    } elseif ($periodo == "Tarde") {
                                        echo $tardeBasico;
                                    } else {
                                        echo '0';
                                    }
                                } elseif ($ciclo == 'Intermediário') {
                                    if ($periodo == "Manhã") {
                                        echo $manhaInter;
                                    } elseif ($periodo == "Tarde") {
                                        echo $tardeInter;
                                    } else {
                                        echo '0';
                                    }
                                } elseif ($ciclo == 'Avançado 1') {
                                    if ($periodo == "Manhã") {
                                        echo $manhaAv1;
                                    } elseif ($periodo == "Tarde") {
                                        echo $tardeAv1;
                                    } else {
                                        echo '0';
                                    }
                                }
                                ?>
                            </td>
                            <?php
                        }
                    }
                }
            }
            ?>
        </tr>
    </table>
</div>
<script>
    $(function () {
        setTimeout(function () {
            location.reload();
        }, 200000);
    });
</script>
<?php

function abrev($nome, $prefixo = null) {
    if (!empty($nome)) {
        $sede = explode(' ', $nome);
        if ($prefixo) {
            $prefixo = @$sede[0] . ' ';
        }
        if (strlen(@$sede[2]) < 4) {
            $nSede = $prefixo . @$sede[1] . ' ' . @$sede[2] . (strlen(@$sede[3]) > 3 ? ' ' . @$sede[3] : '');
        } else {
            $nSede = $prefixo . @$sede[1] . ' ' . @$sede[2];
        }
    } else {
        $nSede = null;
    }

    return $nSede;
}
