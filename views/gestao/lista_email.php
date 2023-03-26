<?php
ob_start();
$escola = new escola(@$_POST['id_inst']);

$cor = '#F5F5F5';

$s = $model->pegasituacaosedlayout();
$sit = $model->pegasituacaosedlayout();

foreach ($_POST['sel'] as $v) {
    if (!empty($v)) {
        $idTumas[] = $v;
    }
}
$idTumas = implode(",", $idTumas);
$wsql = "Select id_pessoa, n_pessoa, dt_nasc, ra, ra_dig, tel1, fk_id_turma, chamada, dt_matricula, situacao,"
        . " dt_transferencia, codigo_classe, emailgoogle"
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
            padding-top: 1px;
            padding-bottom: 1px;
        }
        .quebra { 
            page-break-before: always; 
        }
    </style>
</head>

<?php
foreach ($cla as $kw => $w) {
    $prof = $model->pegaprof($kw);
    $per = explode('|', $model->completadadossala($kw));
    $con = $model->contaaluno($kw);
    if (!empty($proximaFolha)) {
        ?>
        <div style="page-break-after: always"></div>
        <?php
    } else {
        $proximaFolha = 1;
    }
    ?>

    <div style="font-weight:bold; font-size:10pt; background-color: #000000; width: 679px; color:#ffffff; text-align: center">
        Lista Email
    </div>

    <div style = "border-width: 0.5px; border-style: solid; font-weight:bold; font-size:8pt; width: 679px; text-align: left">
        <?php echo $per[0] . ' - Ano Letivo: ' . $per[1] . ' - Período: ' . $per[2] . ' - Prof.' . $prof ?>
    </div>

    <table class="table tabs-stacked table-bordered">;

        <tr>
            <td >
                ch       
            </td>    
            <td>
                Nome do Aluno
            </td>
            <td>
                Data Nasc.
            </td>
            <td>
                RSE      
            </td>    
            <td>
                RA      
            </td>
            <td>
                Situação      
            </td>
            <td>
                Email      
            </td>
        </tr>    

        <?php
        foreach ($w as $v) {
            ?>
            <tr>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['chamada'] ?>
                </td>               
                <td style="text-align: left; background-color: <?php echo $cor ?>">
                    <?php echo addslashes($v['n_pessoa']) ?>
                </td>
                <td style="background-color: <?php echo $cor ?>"> 
                    <?php echo data::converteBr($v['dt_nasc']) ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['id_pessoa'] ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['ra'] . '-' . $v['ra_dig'] ?>
                </td>                   
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['situacao'] ?>
                </td> 
                <td style="text-align: left; background-color: <?php echo $cor ?>">
                    <?php echo $v['emailgoogle'] ?>
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