<?php
ob_start();
$escola = new escola(@$_POST['id_inst']);
$cor = '#F5F5F5';
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
$wsql = "Select id_pessoa, n_pessoa, dt_nasc, ra, fk_id_turma, chamada, situacao_final, codigo_classe, situacao From pessoa "
        . " JOIN ge_turma_aluno on ge_turma_aluno.fk_id_pessoa = pessoa.id_pessoa"
        . " Where ge_turma_aluno.fk_id_turma  in (" . $idTumas . ") "
        . "order by chamada";

$query = $model->db->query($wsql);
$listapiloto = $query->fetchAll();

foreach ($listapiloto as $v) {
    $cla[$v['fk_id_turma']][] = $v;
}

foreach ($cla as $kw => $w) {
    $per = explode('|', $model->completadadossala($kw));
    $con = explode('|', $model->contaaluno($kw));
    //echo $escola->cabecalho();
    if (!empty($proximaFolha)) {
        ?>
        <div style="page-break-after: always"></div>
        <?php
    } else {
        $proximaFolha = 1;
    }
    ?>

    <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
        Relatório de Resultados Finais - Conselho de Classe
    </div>

    <div style = "border-width: 0.5px; border-style: solid; font-weight:bold; font-size:8pt; text-align: left">
        <?php echo $per[0] . ' - Ano Letivo: ' . $per[1] . ' - Período: ' . $per[2] . ' - Prof. ' . @$per[3] ?>
    </div>

    <table class="table tabs-stacked table-bordered">;

        <tr>
            <td >
                nº Ch       
            </td>
            <td >
                RSE       
            </td>
            <td>
                RA       
            </td>       
            <td>
                Nome do Aluno
            </td>
            <td>
                Data Nasc.
            </td>
            <td>
                Situação Final
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
                    <?php echo $v['ra'] ?>
                </td>
                <td style="text-align: left; background-color: <?php echo $cor; ?>">
                    <?php echo addslashes($v['n_pessoa']) ?>
                </td>      
                <td style="background-color: <?php echo $cor ?>"> 
                    <?php echo data::converteBr($v['dt_nasc']) ?>
                </td>

                <td style="background-color: <?php echo $cor ?>">

                    <?php
                    switch ($v['situacao_final']) {
                        case 1:
                            $sit_final = 'Promovido';
                            break;
                        case 2:
                            $sit_final = 'Promovido';
                            break;
                        case 3:
                            $sit_final = 'Retido';
                            break;
                        case 4:
                            $sit_final = 'Retido p/Frequência';
                            break;
                        case 5:
                            $sit_final = 'Retido';
                            break;
                        case 6:
                            $sit_final = 'Aguardando Conselho';
                            break;
                        default :
                            $sit_final = $v['situacao'];
                            break;
                    }
                    ?>

                    <?php
                    if ($v['situacao'] == 'Frequente'){
                        echo $sit_final;
                    } else {
                        echo $v['situacao'];
                    }
                    ?>
                    <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5' ?>
                </td>
            </tr>
            <?php
        }
        ?>

    </table>

    <?php
}
tool::pdfescola('P', @$_POST['id_inst']);
?>