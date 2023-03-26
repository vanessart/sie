<?php
ob_start();
$escola = new escola(@$_POST['id_inst']);
$cor = '#F5F5F5';
$s = $model->pegasituacaosedlayout();
$sit = $model->pegasituacaosedlayout();
?>
<head>
    <style>
        td{
            font-size: 7pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 1px;
            padding-bottom: 1px;
        }
        .quebra { 
            page-break-before: always; 
        }

    </style>
</head>

<?php
foreach ($_POST['sel'] as $v) {
    if (!empty($v)) {
        $idTumas[] = $v;
    }
}

$idTumas = implode(",", $idTumas);
$wsql = "Select id_pessoa, n_pessoa, dt_nasc, ra, ra_dig, fk_id_turma, chamada, situacao, "
        . "codigo_classe, fk_id_ciclo From pessoa "
        . " JOIN ge_turma_aluno on ge_turma_aluno.fk_id_pessoa = pessoa.id_pessoa"
        . " JOIN ge_turmas ON ge_turmas.id_turma = ge_turma_aluno.fk_id_turma"
        . " Where ge_turma_aluno.fk_id_turma  in (" . $idTumas . ") "
        . "order by chamada";

$query = $model->db->query($wsql);
$listapiloto = $query->fetchAll();

foreach ($listapiloto as $v) {
    $cla[$v['fk_id_turma']][] = $v;
}

foreach ($cla as $kw => $w) {
    $per = explode('|', $model->completadadossala($kw));
    $con = $model->contaaluno($kw);
    $prof = $model->pegaprof($kw);
    if (!empty($proximaFolha)) {
        ?>
        <div style="page-break-after: always"></div>
        <?php
    } else {
        $proximaFolha = 1;
    }
    ?>

    <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
        Relatório Geral de Relação Idade/Ano Escolar - Data Base 31/03/<?php echo date('Y') ?>
    </div>

    <div style = "border-width: 0.5px; border-style: solid; font-weight:bold; font-size:8pt; text-align: left">
        <?php echo $per[0] . ' - Ano Letivo: ' . $per[1] . ' - Período: ' . $per[2] . ' - Prof. ' . $prof ?>
    </div>

    <table class="table tabs-stacked table-bordered">;

        <tr>
            <td rowspan="2">
                nº Ch       
            </td>
            <td rowspan="2">
                RSE       
            </td>
            <td rowspan="2">
                RA       
            </td>       
            <td rowspan="2">
                Nome do Aluno
            </td>
            <td rowspan="2">
                Data Nasc.
            </td>
            <td rowspan="2">
                Situação
            </td>
            <td rowspan="2">
                Idade
            </td>         
            <td colspan="3">
                Diferença em
            </td>
        </tr>
        <tr>
            <td>
                Ano
            </td>
            <td>
                Mês     
            </td>
            <td>
                Dia      
            </td>
        </tr>    
        <?php
        foreach ($w as $v) {
            ?>
            <tr>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['chamada'] ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['id_pessoa'] ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['ra'] . '-' . $v['ra_dig'] ?>
                </td>
                <td style="text-align: left; background-color: <?php echo $cor; ?>">
                    <?php echo addslashes($v['n_pessoa']) ?>
                </td>      
                <td style="background-color: <?php echo $cor ?>"> 
                    <?php echo data::converteBr($v['dt_nasc']) ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['situacao'] ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php
                    $d = explode('|', $model->calcdefas($v['dt_nasc']));
                    echo $d[0];
                    ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php
                    $idadeesc = [1 => 6, 2 => 7, 3 => 8, 4 => 9, 5 => 10, 6 => 11, 7 => 12, 8 => 13, 9 => 14, 19 => 4, 20 => 5, 21 => 0, 22 => 1, 23 => 2, 24 => 3];
                    $de = $d[0] - $idadeesc[$v['fk_id_ciclo']];
                    //$de = $d[0] - (substr($v['codigo_classe'], 3, 1) + 5);

                    if ($de > 1) {
                        ?>
                        <span style="color:red"><?php echo $de ?></span>
                        <?php
                    } elseif ($de < 0) {
                        ?>
                        <span style="color:blue"><?php echo $de ?></span>
                        <?php
                    } else {
                        ?>
                        <span><?php echo $de ?></span>
                        <?php
                    }
                    ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php
                    if ($de > 1) {
                        ?>
                        <span style="color:red"><?php echo $d[1] ?></span>
                        <?php
                    } elseif ($de < 0) {
                        ?>
                        <span style="color:blue"><?php echo $d[1] ?></span>
                        <?php
                    } else {
                        ?>
                        <span><?php echo $d[1] ?></span>

                        <?php
                    }
                    ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">   
                    <?php
                    if ($de > 1) {
                        ?>
                        <span style="color:red"><?php echo $d[2] ?></span>
                        <?php
                    } elseif ($de < 0) {
                        ?>
                        <span style="color:blue"><?php echo $d[2] ?></span>
                        <?php
                    } else {
                        ?>
                        <span><?php echo $d[2] ?></span>

                        <?php
                    }
                    ?>
                    <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5' ?>
                </td>
            </tr>
            <?php
        }
        ?>

    </table>
    <div style="font-size: 8pt; font-weight: bolder; border-width: 0.5px; border-style: solid">
        Resumo Situação
        <table style="font-size: 8pt; font-weight: bolder; border-width: 0.5px; border-style: solid">
            <tr>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[2] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[6] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[3] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[1] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[4] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[8] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[5] ?>
                </td>  
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[7] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    Outras Situações
                </td>  
                <td style="color: red; font-weight: bolder">
                    Total Alunos Lista
                </td>          
            </tr>
            <tr>
                <td>
                    <?php echo $con[$sit[2]] ?>
                </td>
                <td>
                    <?php echo $con[$sit[6]] ?>
                </td>
                <td>
                    <?php echo $con[$sit[3]] ?>
                </td>
                <td>
                    <?php echo $con[$sit[1]] ?>
                </td>
                <td>
                    <?php echo $con[$sit[4]] ?>
                </td>
                <td>
                    <?php echo $con[$sit[8]] ?>
                </td>
                <td>
                    <?php echo $con[$sit[5]] ?>
                </td><td>
                    <?php
                    $t = $con[$sit[1]] + $con[$sit[2]] + $con[$sit[3]] + $con[$sit[4]] + $con[$sit[5]] + $con[$sit[6]] + $con[$sit[7]] + $con[$sit[8]];
                    echo $t;
                    ?>
                </td>   
                <td>
                    <?php echo $con[$sit[7]] ?>
                </td>         
                <td>
                    <?php echo ' - ' ?>
                </td>     
                    
            </tr>
        </table>
    </div>

    <?php
}
tool::pdfescola('P', @$_POST['id_inst']);
?>