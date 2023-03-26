<?php
ob_start();
$escola = new escola(@$_POST['id_inst']);

$cor = '#F5F5F5';

foreach ($_POST['sel'] as $v) {
    if (!empty($v)) {
        $idTumas[] = $v;
    }
}
$idTumas = implode(",", $idTumas);
$wsql = "Select chamada, id_pessoa, ra, n_pessoa, tel1, fk_id_turma, situacao  "
        . " From pessoa "
        . " JOIN ge_turma_aluno on ge_turma_aluno.fk_id_pessoa = pessoa.id_pessoa "
        . " Where ge_turma_aluno.fk_id_turma  in (" . $idTumas . ") "
        . "order by chamada";

$query = $model->db->query($wsql);
$listapiloto = $query->fetchAll();

foreach ($listapiloto as $v) {
    $cla[$v['fk_id_turma']][] = $v;
}
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
            padding-top: 2px;
            padding-bottom: 2px;
        }
        .topo{
            height: 5px;
            font-size: 10pt;
            font-weight:bolder;
            text-align: center;
            border: solid;
            border-width: 1px;
            padding-left: 5px;  
        }   
    </style> 
</head>

<?php
foreach ($cla as $kw => $w) {
    //echo $escola->cabecalho();
    if (!empty($proximaFolha)) {
        ?>
        <div style="page-break-after: always"></div>
        <?php
    } else {
        $proximaFolha = 1;
    }
    ?>

    <div style="font-weight:bold; font-size:10pt; background-color: #000000; width: 679px; color:#ffffff; text-align: center">
        Escola de Interesse - Ensino Médio
    </div>

    <?php
    $per = explode('|', $model->completadadossala($kw));
    ?>

    <div style = "border-width: 0.5px; border-style: solid; font-weight:bold; font-size:8pt; width: 679px; text-align: left">
        <?php echo $per[0] . ' - Ano Letivo: ' . $per[1] . ' - Período: ' . $per[2] . ' - Prof. ' . @$per[3] ?>
    </div>

    <table class="table tabs-stacked table-bordered">;

        <tr>
            <td style="width:5%">
                Ch       
            </td>
            <td style="width:8%">
                RSE       
            </td>
            <td style="width:10%">
                RA       
            </td>       
            <td style="width:37%">
                Nome do Aluno
            </td>
            <td style="width: 20%">
                Escola Pretendida      
            </td>
            <td style="width:20%">
                Assinatura Responsável
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
                <td style="text-align: left;background-color: <?php echo $cor ?>">
                    <?php echo addslashes($v['n_pessoa']) ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php
                    if ($v['situacao'] == "Frequente") {
                        echo '';
                    } else {
                        echo $v['situacao'];
                    }
                    ?>                   
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo '' ?>
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