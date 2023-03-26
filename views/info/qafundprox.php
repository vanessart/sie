<?php

$ano = date("Y") + 1;
$result = ensino::qaFund(NULL,$ano);

asort($result['escola']);

?>
<hr>
<div class="fieldBody">
    <div style="text-align: center">
        NÚMERO DE CLASSES E ALUNOS - ENSINO FUNDAMENTAL 
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
            <form target="_blank" action="<?php echo HOME_URI ?>/info/qafundPdfprox" style="text-align: right; width: 100%" method="POST">
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
                    <td colspan="11" style="text-align: center; font-size: 18px; font-weight: bold">
                        Totalização
                    </td>
                </tr>
                <tr style="background-color: white; font-weight: bold">
                    <td>
                        Info.
                    </td>
                    <td>
                        <?php
                        for ($c = 1; $c <= 9; $c++) {
                            ?>
                        <td style="; <?php echo $c == 6 ? 'border-left: solid #000000 2px' : '' ?>">
                            <?php echo $c ?>º Ano
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
                    <td>
                        <?php
                        for ($c = 1; $c <= 9; $c++) {
                            ?>
                        <td style="; <?php echo $c == 6 ? 'border-left: solid #000000 2px' : '' ?>">
                            <?php echo @$result['totalTurmas'][$c] ?>
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
                    <td>
                        <?php
                        for ($c = 1; $c <= 9; $c++) {
                            ?>
                        <td style="; <?php echo $c == 6 ? 'border-left: solid #000000 2px' : '' ?>">
                            <?php echo @$result['totalAlunoCiclo'][$c] ?>
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
                    <td>
                        <?php
                        for ($c = 1; $c <= 9; $c++) {
                            ?>
                        <td style="; <?php echo $c == 6 ? 'border-left: solid #000000 2px' : '' ?>">
                            <?php echo round(@$result['totalAlunoCiclo'][$c] / @$result['totalTurmas'][$c], 1) ?>
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
                    <td colspan="10" style="background-color:  #3c3c3c; color: white">
                        Manhã
                    </td>
                    <td colspan="10" style="background-color:  #3c3c3c; color: white">
                        Tarde
                    </td>
                    <td rowspan="2" style="background-color:  #3c3c3c; color: white">
                        Total Geral
                    </td>
                </tr>
                <tr>
                    <?php
                    for ($c1 = 1; $c1 <= 2; $c1++) {
                        for ($c = 1; $c <= 9; $c++) {
                            ?>
                            <td style="background-color:  #555555; color: white" >
                                <?php echo $c ?>º Ano
                            </td>
                            <?php
                        }
                        ?>
                        <td style="background-color:  #555555; color: white">
                            Total
                        </td>
                        <?php
                    }
                    ?>
                </tr>
            </thead>
            <?php
            $conta = 1;
            foreach ($result['escola'] as $k => $v) {
                $classes = 0;
                $alunos = 0;
                if ($conta % 6 == 0 && @$pdf <> 1) {
                    ?>
                    <tr style="font-weight: bold;">
                        <td rowspan="2" style="background-color: #3c3c3c; color: white">
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
                        <td colspan="10" style="text-align: center;background-color:  #3c3c3c; color: white">
                            Manhã
                        </td>
                        <td colspan="10" style="text-align: center;background-color:  #3c3c3c; color: white">
                            Tarde
                        </td>
                        <td rowspan="2" style="background-color:  #3c3c3c; color: white">
                            Total Geral
                        </td>
                    </tr>
                    <tr>
                        <?php
                        for ($c1 = 1; $c1 <= 2; $c1++) {
                            for ($c = 1; $c <= 9; $c++) {
                                ?>
                                <td style="background-color:  #555555; color: white" >
                                    <?php echo $c ?>º Ano
                                </td>
                                <?php
                            }
                            ?>
                            <td style="background-color:  #555555; color: white" >
                                Total
                            </td>
                            <?php
                        }
                        ?>
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
                    foreach (['M', 'T'] as $p) {
                        $classp[$p] = 0;
                        for ($c = 1; $c <= 9; $c++) {
                            ?>
                            <td style="background-color: white; <?php echo $c == 6 ? 'border-left: solid #000000 2px' : '' ?>">
                                <?php
                                echo @$result['turmas'][$k][$p][$c];
                                $classes += @$result['turmas'][$k][$p][$c];
                                $classp[$p] += @$result['turmas'][$k][$p][$c];
                                @$tturma[$p][$c] += @$result['turmas'][$k][$p][$c];
                                ?>
                            </td>
                            <?php
                        }
                        ?>
                        <td style="background-color: khaki">
                            <?php echo $classp[$p] ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td style="font-weight: bold">
                        <?php
                        echo $classes;
                        @$classest += $classes;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td  style="background-color: khaki">
                        Alunos
                    </td>
                    <?php
                    foreach (['M', 'T'] as $p) {
                        $alunop[$p] = 0;
                        for ($c = 1; $c <= 9; $c++) {
                            ?>
                            <td style="background-color: khaki; <?php echo $c == 6 ? 'border-left: solid #000000 2px' : '' ?>">
                                <?php
                                echo @$result['AlunoCicloPeriodo'][$k][$p][$c];
                                $alunos += @$result['AlunoCicloPeriodo'][$k][$p][$c];
                                $alunop[$p] += @$result['AlunoCicloPeriodo'][$k][$p][$c];
                                @$talunos[$p][$c] += @$result['AlunoCicloPeriodo'][$k][$p][$c];
                                ?>
                            </td>
                            <?php
                        }
                        ?>
                        <td style="background-color: khaki">
                            <?php echo $alunop[$p] ?>
                        </td>
                        <?php
                    }
                    ?>

                    <td>
                        <?php
                        echo $alunos;
                        @$alunost += $alunos;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Média
                    </td>
                    <?php
                    foreach (['M', 'T'] as $p) {
                        for ($c = 1; $c <= 9; $c++) {
                            ?>
                            <td style="; <?php echo $c == 6 ? 'border-left: solid #000000 2px' : '' ?>">
                                <?php
                                if (@$result['turmas'][$k][$p][$c] != 0) {
                                    $porc = (@$result['AlunoCicloPeriodo'][$k][$p][$c] / @$result['turmas'][$k][$p][$c]);
                                    echo round($porc, 1);
                                }
                                ?>
                            </td>
                            <?php
                        }
                        ?>
                        <td style="background-color: khaki">
                            <?php
                            echo @round($alunop[$p] / $classp[$p], 1);
                            ?>
                        </td>
                        <?php
                    }
                    ?>

                    <td>
                        <?php
                        if ($classes != 0) {
                            echo round(($alunos / $classes), 1);
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td rowspan="3" colspan="3" style="background-color: white; font-weight: bold; font-size: 18px; vertical-align: middle">
                    Totais
                </td>
                <td style="background-color: white">
                    Classes
                </td>
                <?php
                foreach (['M', 'T'] as $p) {
                    $tturmap = 0;
                    for ($c = 1; $c <= 9; $c++) {
                        ?>
                        <td style="background-color: white; <?php echo $c == 6 ? 'border-left: solid #000000 2px' : '' ?>">
                            <?php
                            echo $tturma[$p][$c];
                            @$tturmap += $tturma[$p][$c];
                            @$ttgturma[$c] += $tturma[$p][$c];
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td style="background-color: khaki">
                        <?php echo $tturmap ?>
                    </td>
                    <?php
                }
                ?>
                <td>
                    <?php echo $result['totalTurmasG'] ?>
                </td>
            </tr>
            <tr>
                <td style="background-color: khaki">
                    Alunos
                </td>
                <?php
                foreach (['M', 'T'] as $p) {
                    $talunosp = 0;
                    for ($c = 1; $c <= 9; $c++) {
                        ?>
                        <td style="background-color: khaki; <?php echo $c == 6 ? 'border-left: solid #000000 2px' : '' ?>">
                            <?php
                            echo $talunos[$p][$c];
                            @$talunosp += $talunos[$p][$c];
                            @$tgalunos[$c] += $talunos[$p][$c];
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td style="background-color: khaki">
                        <?php echo $talunosp ?>
                    </td>
                    <?php
                }
                ?>
                <td>
                    <?php echo $result['Aluno'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Média
                </td>
                <?php
                foreach (['M', 'T'] as $p) {
                    for ($c = 1; $c <= 9; $c++) {
                        ?>
                        <td style="; <?php echo $c == 6 ? 'border-left: solid #000000 2px' : '' ?>">
                            <?php
                            echo ($tturma[$p][$c] == 0) ? '-' : round($talunos[$p][$c] / $tturma[$p][$c], 1);
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td style="background-color: khaki; <?php echo $c == 6 ? 'border-left: solid #000000 2px' : '' ?>">
                        <?php echo round($talunosp / $tturmap, 1) ?>
                    </td>
                    <?php
                }
                ?>
                <td>
                    <?php echo round($result['Aluno'] / $result['totalTurmasG'], 1) ?>
                </td>
            </tr>
        </table>
    </div>
</div>