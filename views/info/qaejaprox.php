<?php
$ciclos = [25, 26, 27, 28, 29, 30, 34, 35, 36];
$ano = date("Y") + 1;
$result = ensino::qa($ciclos, NULL, $ano);
$cic = sql::get('ge_ciclos');
foreach ($cic as $v) {
    $n_ciclos[$v['id_ciclo']] = $v['n_ciclo'];
}

asort($result['escola']);
?>
<hr>
<div class="fieldBody">
    <div style="text-align: center">
        NÚMERO DE CLASSES E ALUNOS - ENSINO INFANTIL - EJA 
    </div>
    <div  style="text-align: center">
        Atualizado em
        <?php
        echo date("d/m/Y")
        ?>
    </div>
    <?php
    if (empty($pdf)) {
        ?>
        <div>
            <form target="_blank" action="<?php echo HOME_URI ?>/info/qaejaPdfprox" style="text-align: right; width: 100%" method="POST">
                <input class="btn btn-success" type="submit" value="Imprimir" />
            </form>
        </div>
        <?php
    }
    ?>
    <br /><br />
    <div style="background-color: #E0E0E0">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td colspan="8" style="text-align: center; font-size: 18px; font-weight: bold;">
                        Totalização
                    </td>
                </tr>
                <tr style="background-color: white; font-weight: bold">
                    <td>
                        Info.
                    </td>
                    <?php
                    foreach ($ciclos as $ci) {
                        ?>
                        <td>
                            <?php echo $n_ciclos[$ci] ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td style="background-color: khaki">
                        Total
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight: bold">
                        Classes
                    </td>
                    <?php
                    foreach ($ciclos as $ci) {
                        ?>
                        <td>
                            <?php echo @$result['totalTurmas'][$ci] ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td style="background-color: khaki">
                        <?php echo $result['totalTurmasG'] ?>
                    </td>
                </tr>
                <tr style="background-color: khaki">
                    <td style="font-weight: bold">
                        Alunos
                    </td>
                    <?php
                    foreach ($ciclos as $ci) {
                        ?>
                        <td>
                            <?php echo @$result['totalAlunoCiclo'][$ci] ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td style="background-color: khaki">
                        <?php echo $result['Aluno'] ?>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold">
                        Média
                    </td>
                    <?php
                    foreach ($ciclos as $ci) {
                        ?>
                        <td>
                            <?php
                            if (!empty($result['totalTurmas'][$ci])) {
                                echo round(@$result['totalAlunoCiclo'][$ci] / @$result['totalTurmas'][$ci], 1);
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td style="background-color: khaki">
                        <?php echo round($result['Aluno'] / $result['totalTurmasG'], 1) ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br /><br />
    <div style="background-color: #E0E0E0">
        <table class="table table-bordered">
            <thead>
                <tr style="font-weight: bold">
                    <td rowspan="2" style="background-color:  #3c3c3c; color: white">
                        nº
                    </td>
                    <td rowspan="2" style="background-color:  #3c3c3c; color: white">
                        Escola
                    </td>
                    <td rowspan="2" style="background-color:  #3c3c3c; color: white">
                        CIE
                    </td>
                    <td rowspan="2" style="background-color:  #3c3c3c; color: white">
                        Info.
                    </td>
                    <td colspan="2" style="background-color:  #3c3c3c; color: white; text-align: center">
                        Ciclo I
                    </td>
                    <td colspan="4" style="background-color:  #3c3c3c; color: white; text-align: center">
                        Ciclo II
                    </td>
                </tr>
                <tr>
                    <?php
                    foreach ($ciclos as $ci) {
                        ?>
                        <td style="white-space: nowrap; padding: 3px;background-color:  #555555; color: white">
                            <?php
                            if (empty($pdf)) {
                                echo $n_ciclos[$ci];
                            } else {
                                echo str_replace("Maternal ", '', $n_ciclos[$ci]);
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?> 
                    <td rowspan="2" style="background-color:  #3c3c3c; color: white;border-right:  solid #000000 2px">
                        Total
                    </td>
                </tr>
            </thead>
            <?php
            $conta = 1;
            foreach ($result['escola'] as $k => $v) {
                if (!empty($result['turmas'][$k]['M'])) {
                    $classes = 0;
                    $alunos = 0;
                    if ($conta % 6 == 0 && @$pdf <> 1) {
                        ?>
                        <tr style="font-weight: bold">
                            <td rowspan="2" style="background-color:  #3c3c3c; color: white">
                                nº
                            </td>
                            <td rowspan="2" style="background-color:  #3c3c3c; color: white">
                                Escola
                            </td>
                            <td rowspan="2" style="background-color:  #3c3c3c; color: white">
                                CIE
                            </td>
                            <td rowspan="2" style="background-color:  #3c3c3c; color: white">
                                Info.
                            </td>
                            <td colspan="2" style="background-color:  #3c3c3c; color: white; text-align: center">
                                Ciclo I
                            </td>
                            <td colspan="4" style="background-color:  #3c3c3c; color: white; text-align: center">
                                Ciclo II
                            </td>

                        </tr>
                        <tr>
                            <?php
                            foreach ($ciclos as $ci) {
                                ?>
                                <td style="white-space: nowrap; padding: 3px;background-color:  #555555; color: white">
                                    <?php
                                    if (empty($pdf)) {
                                        echo $n_ciclos[$ci];
                                    } else {
                                        echo str_replace("Maternal ", '', $n_ciclos[$ci]);
                                    }
                                    ?>
                                </td>
                                <?php
                            }
                            ?> 
                            <td rowspan="2" style="background-color:  #3c3c3c; color: white;border-right:  solid #000000 2px">
                                Total
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td rowspan="3" style="font-weight: bold; background-color: white; vertical-align: middle">
                            <?php echo $conta++; ?>
                        </td>
                        <td rowspan="3" style="background-color: white; vertical-align: middle">
                            <?php echo $v ?>
                        </td>
                        <td rowspan="3" style="background-color: white; vertical-align: middle">
                            <?php echo $result['cie'][$k]; ?>
                        </td>
                        <td style="background-color: white">
                            Classes
                        </td>
                        <?php
                        foreach (['M'] as $p) {
                            $classp[$p] = 0;
                            foreach ($ciclos as $ci) {
                                ?>
                                <td style="background-color: white; ">
                                    <?php
                                    echo @$result['turmas'][$k][$p][$ci];
                                    $classes += @$result['turmas'][$k][$p][$ci];
                                    $classp[$p] += @$result['turmas'][$k][$p][$ci];
                                    @$tturma[$p][$ci] += @$result['turmas'][$k][$p][$ci];
                                    ?>
                                </td>
                                <?php
                            }
                            ?>
                            <td style="background-color: white;border-right:  solid #000000 2px">
                                <?php echo $classp[$p] ?>
                            </td>
                            <?php
                        }
                        ?>

                    </tr>
                    <tr>
                        <td  style="background-color: khaki">
                            Alunos
                        </td>
                        <?php
                        foreach (['M'] as $p) {
                            $alunop[$p] = 0;
                            foreach ($ciclos as $ci) {
                                ?>
                                <td style="background-color: khaki;">
                                    <?php
                                    echo @$result['AlunoCicloPeriodo'][$k][$p][$ci];
                                    $alunos += @$result['AlunoCicloPeriodo'][$k][$p][$ci];
                                    $alunop[$p] += @$result['AlunoCicloPeriodo'][$k][$p][$ci];
                                    @$talunos[$p][$ci] += @$result['AlunoCicloPeriodo'][$k][$p][$ci];
                                    ?>
                                </td>
                                <?php
                            }
                            ?>
                            <td style="background-color: khaki; border-right: solid #000000 2px">
                                <?php echo $alunop[$p] ?>
                            </td>
                            <?php
                        }
                        ?>

                    </tr>
                    <tr>
                        <td>
                            Média
                        </td>
                        <?php
                        foreach (['M'] as $p) {
                            foreach ($ciclos as $ci) {
                                ?>
                                <td>
                                    <?php
                                    if (@$result['turmas'][$k][$p][$ci] != 0) {
                                        $porc = (@$result['AlunoCicloPeriodo'][$k][$p][$ci] / @$result['turmas'][$k][$p][$ci]);
                                        echo round($porc, 1);
                                    }
                                    ?>
                                </td>
                                <?php
                            }
                            ?>
                            <td style=" border-right: solid #000000 2px">
                                <?php
                                if (!empty($alunop[$p])) {
                                    echo @round($alunop[$p] / $classp[$p], 1);
                                } else {
                                    echo '0';
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
            //fim escola
            ?>
            <tr>
                <td rowspan="3" colspan="3" style="background-color: white; font-weight: bold; font-size: 18px; vertical-align: middle">
                    Totais
                </td>
                <td style="background-color: white">
                    Classes
                </td>
                <?php
                foreach (['M'] as $p) {
                    $tturmap = 0;
                    foreach ($ciclos as $ci) {
                        ?>
                        <td style="background-color: white; <?php echo $c == 6 ? '' : '' ?>">
                            <?php
                            echo $tturma[$p][$ci];
                            @$tturmap += $tturma[$p][$ci];
                            @$ttgturma[$c] += $tturma[$p][$ci];
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td style="background-color: white; border-right: solid #000000 2px">
                        <?php echo $tturmap ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <td style="background-color: khaki">
                    Alunos
                </td>
                <?php
                foreach (['M'] as $p) {
                    $talunosp = 0;
                    foreach ($ciclos as $ci) {
                        ?>
                        <td style="background-color: khaki">
                            <?php
                            echo $talunos[$p][$ci];
                            @$talunosp += $talunos[$p][$ci];
                            @$tgalunos[$ci] += $talunos[$p][$ci];
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td style="background-color: khaki; border-right: solid #000000 2px">
                        <?php echo $talunosp ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <td>
                    Média
                </td>
                <?php
                foreach (['M'] as $p) {
                    foreach ($ciclos as $ci) {
                        ?>
                        <td>
                            <?php
                            if (!empty($tturma[$p][$ci])) {
                                echo round($talunos[$p][$ci] / $tturma[$p][$ci], 1);
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td style="border-right: solid #000000 2px">
                        <?php echo round($talunosp / $tturmap, 1) ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
        </table>
    </div>
</div>