<?php
$proficiencia = aval::proficiencia($id_gl, @$id_turma, @$id_instReclassificado);
$aval = aval::getAval($id_gl);
$valores = explode(',', $aval['valores']);
$val = unserialize($aval['val']);
$Main = NULL;
$MainParc = NULL;
$aluno = NULL;
?>
<br /><br />
<table style="width: 100%; font-weight: bold" cellspacing=0 cellpadding=1 border="1">
    <tr>
        <td style="text-align: center">
            <?php echo $aval['n_gl'] ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: center">
            <?php echo sql::get('instancia', 'n_inst', ['id_inst' => $id_inst], 'fetch')['n_inst'] ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: center">
            <?php echo sql::get('ge_turmas', 'n_turma', ['id_turma' => $id_turma], 'fetch')['n_turma'] ?> - 
            <?php echo sql::get('ge_disciplinas', 'n_disc', ['id_disc' => @$aval['fk_id_disc']], 'fetch')['n_disc'] ?>  
        </td>
    </tr>
    <tr>
        <td>
            <table style="width: 100%; font-weight: bold" cellspacing=0 cellpadding=1 border="1">
                <td>
                    % de Domínio da Habilidade
                </td>
                <td style="text-align: center; background-color: green  !important; color: white   !important; -webkit-print-color-adjust: exact !important; color-adjust: exact !important;">
                    Adequado
                    <br />
                    (maior que 70%)
                </td>
                <td style="text-align: center; background-color: yellow  !important; -webkit-print-color-adjust: exact !important; color-adjust: exact !important;">
                    Básico
                    <br />
                    (entre 50% e 70%)
                </td>
                <td style="text-align: center; background-color: red  !important; color: white   !important; -webkit-print-color-adjust: exact !important; color-adjust: exact !important;">
                    Abaixo do Básico
                    <br />
                    (menor que 50%)
                </td>
            </table>   
        </td>
    </tr>
    <tr>
        <td>
            Total de alunos presentes: <?php echo count($proficiencia) ?>
        </td>
    </tr>
    <tr>
        <td>
            <table style="width: 100%; font-weight: bold" cellspacing=0 cellpadding=1 border="1">
                <tr>
                    <td rowspan="2" style="text-align: center">
                        Alunos
                    </td>
                    <td colspan="<?php echo $aval['quest'] ?>" style="text-align: center">
                        Itens
                    </td> 
                    <td rowspan="2" style="text-align: center">
                        % Domínio
                        <br />
                        da
                        <br />
                        Habilidade
                    </td>
                    <td colspan="5" style="text-align: center">
                        Percentual dos Distratores por Aluno
                        <br />
                        (<?php echo $aval['quest'] ?> itens)
                    </td>
                </tr>
                <tr>
                    <?php
                    for ($c = 1; $c <= $aval['quest']; $c++) {
                        ?>
                        <td style="text-align: center">
                            <?php echo $c ?>
                        </td>
                        <?php
                    }
                    foreach ($valores as $kv => $vv) {
                        ?>
                        <td style="text-align: center; <?php echo $vv == "G1" ? 'font-weight: bold; background-color: darkgrey' : '' ?>">
                            <?php echo $vv ?>
                            <br />
                            (<?php echo $val[$kv + 1] ?>)
                        </td>
                        <?php
                    }
                    ?>

                </tr>
                <?php
                foreach ($proficiencia as $v) {
                    if (($v['situacao'] == "Frequente") and ( $v['nfez'] == NULL)) {
                        $aluno++;
                        ?>
                        <tr>
                            <td rowspan="2">
                                <?php echo $v['chamada'] ?>
                            </td>
                            <?php
                            $soma = 0;
                            $cont = 0;
                            $itemCont = NULL;
                            for ($c = 1; $c <= $aval['quest']; $c++) {
                                ?>
                                <td style="text-align: center">
                                    <?php echo @$valores[($v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)] - 1)] ?>
                                </td>
                                <?php
                                $soma += @($val[($v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)])]);
                                @$itemCont[@$valores[($v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)] - 1)]] ++;
                                @$MainParc[@$valores[($v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)] - 1)]] ++;
                                $cont++;
                            }
                            if (($soma / $cont) > 0.7) {
                                $backgroundColor = "green";
                                $color = 'white';
                            } elseif (($soma / $cont) < 0.5) {
                                $color = 'white';
                                $backgroundColor = "red";
                            } else {
                                $color = 'black';
                                $backgroundColor = "yellow";
                            }
                            ?>
                            <td rowspan="2" style="text-align: center; color: <?php echo $color ?> !important; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; background-color: <?php echo $backgroundColor ?>  !important;">
                                <?php
                                echo $t = intval((($soma / $cont) * 100));
                                $Main += $t;
                                ?>
                                % 
                            </td>
                            <?php
                            foreach ($valores as $kvv => $vv) {
                                ?>
                                <td rowspan="2" style="text-align: center; <?php echo $vv == "G1" ? 'font-weight: bold; background-color: darkgrey' : '' ?>">
                                    <?php echo intval(@($itemCont[$vv] / $cont) * 100); ?>%
                                    <br />
                                    <?php echo @$itemCont[$vv] ?>
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                        <tr>
                            <?php
                            for ($c = 1; $c <= $aval['quest']; $c++) {
                                ?>
                                <td style="text-align: center">
                                    <?php echo @($val[($v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)])]) * 100 ?>%
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                        <?php
                    } else {
                        ?>
                        <tr>
                            <td>
                                <?php echo $v['chamada'] ?>
                            </td>
                            <td colspan="<?php echo 21 * count($valores) ?>" style="text-align: center">

                                <?php
                                if ($v['nfez'] === 'a') {
                                    echo (($v['situacao'] == "Frequente") ? "Anulada" : $v['situacao']);
                                } elseif ($v['nfez'] === 'b') {
                                    echo (($v['situacao'] == "Frequente") ? "Em Branco" : $v['situacao']);
                                } elseif ($v['nfez'] === 'l') {
                                    echo (($v['situacao'] == "Frequente") ? "Adaptação Curricular" : $v['situacao']);
                                } elseif ($v['nfez'] === 'n') {
                                    echo (($v['situacao'] == "Frequente") ? "Ausente" : $v['situacao']);
                                } elseif ($v['nfez'] === 'r') {
                                    echo (($v['situacao'] == "Frequente") ? "Reclassificado/Transferido" : $v['situacao']);
                                } elseif ($v['nfez'] === 't') {
                                    echo (($v['situacao'] = "Frequente") ? "Transferido" : $v['situacao']);
                                } else {
                                echo ($v['situacao'] );}
                                
                                ?>



                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                <td colspan="<?php echo $aval['quest'] ?>" style="text-align: center">

                </td>
                <td>
                    Total 
                </td>
                <td style="text-align: center">
                    <?php echo round($Main / $aluno, 2) ?>%
                </td>
                <?php
                foreach ($MainParc as $p) {
                    @$tm += $p;
                }
                foreach ($valores as $kv => $vv) {
                    ?>
                    <td style="text-align: center; width: 50px">
                        <?php echo round((@$MainParc[$vv] / $tm) * 100, 2) ?>%
                        <br />
                        <?php echo @$MainParc[$vv] ?>
                    </td>
                    <?php
                }
                ?>
            </table>
        </td>
    </tr>
</table>
