<?php
ob_start();
$escola = new escola();
$cor = '#F5F5F5';
$seq = 1;
$seqac = 1;
$seqg = 1;
?>
<head>
    <style type="text/css">
        .topo{
            font-size: 8pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            padding-left: 2px;
            padding-right: 2px;
        }

    </style>
</head>
<?php
if ($_POST['escola'] == "Infantil") {
    $x = 19;
    $y = 24;
    $tit = " Educação Infantil ";
} elseif ($_POST['escola'] == "Fundamental") {
    $x = 1;
    $y = 9;
    $tit = " Ensino Fundamental ";
} else {
    $x = 25;
    $y = 35;
    $tit = " Ensino Fundamental - Eja ";
}
if (!empty($proximaFolha)) {
    ?>
    <div style="page-break-after: always"></div>
    <?php
} else {
    $proximaFolha = 1;
}

$quadro = $model->geraquadroaluno($_POST['datai'], $_POST['dataf'], $_POST['tipo']);
$quadrores = $model->geraquadroalunoresumo($_POST['datai'], $_POST['dataf'], $_POST['tipo']);
$s = $model->pegasituacaosedlayout();

if (!empty($quadrores)) {
    foreach ($quadrores as $k => $v) {
        ?>
        <div style="font-weight:bold; font-size:9pt; background-color: #000000; color:#ffffff; text-align: center">Quadro de Alunos <?php echo $tit . "Data Base " . date('d/m/Y') ?></div>
        <div style = "border-width: 0.5px; border-style: solid; font-weight:bold; font-size:9pt; color:red; text-align: center"><?php echo $model->completaperiodo($k) ?></div>
        <?php
        for ($c = $x; $c <= $y; $c++) {
            if (!empty($quadro[$k][$c])) {
                ?>

                <div style = "border-width: 0.5px; border-style: solid; font-weight:bold; font-size:8pt; text-align: center"><?php echo $descricao = $model->pegades($c) ?> </div>

                <table class="table tabs-stacked table-bordered">
                    <thead>
                        <tr>
                            <td rowspan="2" class="topo">
                                Seq.
                            </td>
                            <td rowspan="2" class="topo">
                                Código Classe
                            </td>
                            <td  style="color:red" rowspan="2" class="topo">
                                <?php echo $s[1] ?>
                            </td>
                            <td rowspan="2" class="topo">
                                <?php echo $s[2] ?>
                            </td>
                            <td rowspan="2" class="topo">
                                <?php echo $s[3] ?>
                            </td>
                            <td rowspan="2" class="topo">
                                <?php echo $s[4] ?>
                            </td>
                            <td rowspan="2" class="topo">
                                <?php echo $s[5] ?>
                            </td>
                            <td rowspan="2" class="topo">
                                <?php echo $s[6] ?>
                            </td>
                            <td rowspan="2" class="topo">
                                <?php echo $s[7] ?>
                            </td>
                            <td class="topo">
                                Movimentação Total
                            </td>
                            <td rowspan="2" class="topo">
                                <?php echo $s[8] ?>
                            </td>
                            <td colspan="2" class="topo">
                                Critério <?php echo $_POST['datai'] . ' a ' . $_POST['dataf'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 7px" class="topo">
                                (FR+AB+FA+NC+RC+C+TE)
                            </td>
                            <td class="topo">
                                Matrículas Suplementares
                            </td>
                            <td class="topo">
                                Transferido Escola
                            </td>
                        </tr>
                    </thead>

                    <?php
                    foreach ($quadro[$k][$c] as $cod) {
                        ?>
                        <tbody>
                            <tr>
                                <td style="background-color: <?php echo $cor ?>" class="topo">       
                                    <?php echo $seq++ ?>                                
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo"> 
                                    <?php echo $cod ?>
                                </td>
                                <td style="color:red; background-color: <?php echo $cor ?>" class="topo"> 
                                    <?php echo intval(@$quadro[$cod][$s[1]]) ?>                                      
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo"> 
                                    <?php echo intval(@$quadro[$cod][$s[2]]) ?>                                  
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo"> 
                                    <?php echo intval(@$quadro[$cod][$s[3]]) ?>                                      
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo"> 
                                    <?php echo intval(@$quadro[$cod][$s[4]]) ?>   
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo"> 
                                    <?php echo intval(@$quadro[$cod][$s[5]]) ?>   
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo"> 
                                    <?php echo intval(@$quadro[$cod][$s[6]]) ?>   
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo"> 
                                    <?php echo intval(@$quadro[$cod][$s[7]]) ?>   
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo"> 
                                    <?php echo @$rem = intval(@$quadro[$cod][$s[1]] + @$quadro[$cod][$s[2]] + @$quadro[$cod][3] + @$quadro[$cod][$s[4]] + @$quadro[$cod][$s[5]] + @$quadro[$cod][$s[6]] + @$quadro[$cod][$s[7]]) ?> 
                                </td>           
                                <td style="background-color: <?php echo $cor ?>" class="topo"> 
                                    <?php echo intval(@$quadro[$cod][$s[8]]) ?>   
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo intval(@$quadro[$cod]['suplementar']) ?>   
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo intval(@$quadro[$cod]['transferido']) ?>   
                                    <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5' ?>
                                </td>
                            </tr>
                        </tbody>
                        <?php
                        @$seqac++;
                        @$seqg++;
                        @$conta_fr += intval(@$quadro[$cod][$s[1]]);
                        @$conta_ab += intval(@$quadro[$cod][$s[2]]);
                        @$conta_fa += intval(@$quadro[$cod][$s[3]]);
                        @$conta_nc += intval(@$quadro[$cod][$s[4]]);
                        @$conta_rc += intval(@$quadro[$cod][$s[5]]);
                        @$conta_ce += intval(@$quadro[$cod][$s[6]]);
                        @$conta_te += intval(@$quadro[$cod][$s[7]]);
                        @$conta_re += intval(@$quadro[$cod][$s[8]]);
                        @$conta_su += intval(@$quadro[$cod]['suplementar']);
                        @$conta_tr += intval(@$quadro[$cod]['transferido']);
                    }
                    ?>
                    <tfoot>
                        <tr>
                            <td class="topo">
                                -
                            </td>
                            <td class="topo">
                                Total de Classes <?php echo --$seq ?>
                            </td>
                            <td style="color: red" class="topo">
                                <?php echo @$conta_fr ?>
                            </td>
                            <td class="topo">
                                <?php echo @$conta_ab ?>
                            </td>
                            <td class="topo">
                                <?php echo @$conta_fa ?>
                            </td>
                            <td class="topo">
                                <?php echo @$conta_nc ?>
                            </td>
                            <td class="topo">
                                <?php echo @$conta_nc ?>
                            </td>
                            <td class="topo">
                                <?php echo @$conta_ce ?>
                            </td>
                            <td class="topo">
                                <?php echo @$conta_te ?>
                            </td>
                            <td class="topo">
                                <?php echo $t = @$conta_fr + @$conta_ab + @$conta_ce + @$conta_fa + @$conta_nc + @$conta_rc + @$conta_te ?>
                            </td>
                            <td class="topo"> 
                                <?php echo @$conta_re ?>
                            </td>
                            <td class="topo">
                                <?php echo @$conta_su ?>
                            </td>
                            <td class="topo"> 
                                <?php echo @$conta_tr ?>
                            </td>
                    </tfoot>
                </table>

                <?php
                $seq = 1;
                unset($conta_fr);
                unset($conta_ab);
                unset($conta_ce);
                unset($conta_fa);
                unset($conta_nc);
                unset($conta_rc);
                unset($conta_te);
                unset($conta_t);
                unset($conta_re);
                unset($conta_su);
                unset($conta_tr);
                unset($conta_rem);
            }
        }
        ?>
        <div style="font-weight: bolder; border-width: 0.5px; border-style: solid; color: red; padding-left:27px; padding-bottom: 5px">
            <span style="font-size: 8pt; text-align: left">Resumo Período <?php echo $model->completaperiodo($k) ?></span>
            <table>
                <tr>
                    <td rowspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        Período
                    </td>
                    <td rowspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        Total de Classes
                    </td>             
                    <td rowspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; color: red; padding: 3px">
                        <?php echo $s[1] ?>
                    </td>
                    <td rowspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo $s[2] ?>
                    </td>
                    <td rowspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo $s[3] ?>
                    </td>
                    <td rowspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo $s[4] ?>
                    </td>
                    <td rowspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo $s[5] ?>
                    </td>
                    <td rowspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo $s[6] ?>
                    </td>  
                    <td rowspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo $s[7] ?>
                    </td>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        Movimentação Total
                    </td>
                    <td rowspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo $s[8] ?>
                    </td>
                    <td colspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        Critério <?php echo $_POST['datai'] . ' a ' . $_POST['dataf'] ?>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        (FR+AB+FA+NC+RC+C+TE)
                    </td>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        Matrículas Suplementares
                    </td>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        Transferido Escola
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo ($k == "G") ? "I" : $k ?>
                    </td>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo --$seqac ?>
                    </td>          
                    <td style="color: red; font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo intval(@$v[$s[1]]) ?>
                    </td>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo intval(@$v[$s[2]]) ?>
                    </td>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo intval(@$v[$s[3]]) ?>
                    </td>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo intval(@$v[$s[4]]) ?>
                    </td>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo intval(@$v[$s[5]]) ?>
                    </td>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo intval(@$v[$s[6]]) ?>
                    </td>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo intval(@$v[$s[7]]) ?>
                    </td> 
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo $tc = intval(@$v[$s[1]] + @$v[$s[2]] + @$v[$s[3]] + @$v[$s[4]] + @$v[$s[5]] + @$v[$s[8]] + @$v[$s[7]]) ?>
                    </td>  
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo intval(@$v[$s[8]]) ?>
                    </td> 
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo intval(@$v['suplementarac']) ?>
                    </td> 
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo intval(@$v['transferidoac']) ?>
                    </td> 
                </tr>
            </table>
            <?php
            $seqac = 1;
            ?>
        </div>   
        <div style="page-break-after: always"></div>

        <?php
    }
    ?>

    <div style="font-weight: bolder; border-width: 0.5px; border-style: solid; color: red; padding-left:27px; padding-bottom: 5px">
        <span style="font-size: 8pt; text-align: left">Resumo Geral</span>
        <table>
            <thead>
                <tr>
                    <td rowspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        Período
                    </td>
                    <td rowspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        Total de Classes
                    </td> 
                    <td rowspan="2" style="color: red; font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo $s[1] ?>
                    </td>
                    <td rowspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo $s[2] ?>
                    </td>
                    <td rowspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo $s[3] ?>
                    </td>
                    <td rowspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo $s[4] ?>
                    </td>
                    <td rowspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo $s[5] ?>
                    </td>
                    <td rowspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo $s[6] ?>
                    </td>
                    <td rowspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo $s[7] ?>
                    </td>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        Movimentação Total
                    </td>
                    <td rowspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo $s[8] ?>
                    </td>
                    <td colspan="2" style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        Critério <?php echo $_POST['datai'] . ' a ' . $_POST['dataf'] ?>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        (FR+AB+FA+NC+RC+C+TE)
                    </td>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        Matrículas Suplementares
                    </td>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo $s[7] ?>
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($quadrores as $k => $v) {

                    switch ($k) {
                        case "M":
                            $conta = $v['manha'];
                            break;
                        case "T":
                            $conta = $v['tarde'];
                            break;
                        case "I":
                        case "G":
                            $conta = $v['integral'];
                            break;
                        case "N":
                            $conta = $v['noite'];
                            break;
                        default :
                            $conta = 0;
                            break;
                    }
                    ?>
                    <tr>
                        <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                            <?php echo ($k == "G") ? "I" : $k ?>
                        </td>     
                        <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                            <?php echo @$conta ?>
                        </td>
                        <td style="color:red; font-size: 8pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                            <?php echo intval(@$v[$s[1]]) ?>
                        </td>  
                        <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                            <?php echo intval(@$v[$s[2]]) ?>
                        </td>
                        <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                            <?php echo intval(@$v[$s[3]]) ?>
                        </td>
                        <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                            <?php echo intval(@$v[$s[4]]) ?>
                        </td>
                        <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                            <?php echo intval(@$v[$s[5]]) ?>
                        </td>
                        <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                            <?php echo intval(@$v[$s[6]]) ?>
                        </td>
                        <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                            <?php echo intval(@$v[$s[7]]) ?>
                        </td>     
                        <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                            <?php echo $tc = intval(@$v[$s[1]] + @$v[$s[2]] + @$v[$s[3]] + @$v[$s[4]] + @$v[$s[5]] + @$v[$s[6]] + @$v[$s[7]]) ?>
                        </td>     
                        <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                            <?php echo intval(@$v[$s[8]]) ?>
                        </td>              
                        <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                            <?php echo intval(@$v['suplementarac']) ?>
                        </td> 
                        <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                            <?php echo intval(@$v['transferidoac']) ?>
                        </td> 
                    </tr>
                </tbody>
                <?php
                @$conta_fr += intval(@$v[$s[1]]);
                @$conta_ab += intval(@$v[$s[2]]);
                @$conta_fa += intval(@$v[$s[3]]);
                @$conta_nc += intval(@$v[$s[4]]);
                @$conta_rc += intval(@$v[$s[5]]);
                @$conta_ce += intval(@$v[$s[6]]);
                @$conta_te += intval(@$v[$s[7]]);
                @$conta_re += intval(@$v[$s[8]]);
                @$conta_su += intval(@$v['suplementarac']);
                @$conta_tr += intval(@$v['transferidoac']);
            }
            ?>
            <tfoot>
                <tr>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        -
                    </td>     
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo --$seqg ?>          
                    </td>
                    <td style="color:red; font-size: 8pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo @$conta_fr ?>
                    </td>  
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo @$conta_ab ?>
                    </td>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo @$conta_fa ?>
                    </td>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo @$conta_nc ?>
                    </td>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo @$conta_rc ?>
                    </td>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo @$conta_ce ?>
                    </td>
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo @$conta_te ?>
                    </td>     
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo $tc = (@$conta_fr + @$conta_ab + @$conta_ce + @$conta_fa + @$conta_nc + @$conta_rc + @$conta_te) ?>
                    </td>     
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo @$conta_re ?>
                    </td>              
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo @$conta_su ?>
                    </td> 
                    <td style="font-size: 7pt; font-weight: bolder; border-width: 0.5px; border-style: solid; text-align: center; padding: 3px">
                        <?php echo @$conta_tr ?>
                    </td> 
                </tr>
            </tfoot>
            <?php ?>
        </table>
    </div>

    <?php
} else {
    ?>
    Não existe dado para Relatório
    <?php
}
tool::pdfescola('L');
?>