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
        . " join ge_turmas t on t.id_turma = ge_turma_aluno.fk_id_turma "
        . " Where ge_turma_aluno.fk_id_turma  in (" . $idTumas . ") "
        . "order by fk_id_ciclo, letra, chamada";

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
    </style> 
</head>

<?php
foreach ($cla as $kw => $w) {
    $prof = $model->pegaprof($kw);
    if (!empty($proximaFolha)) {
        ?>
        <div style="page-break-after: always"></div>
        <?php
    } else {
        $proximaFolha = 1;
    }
    ?>

    <div style="font-weight:bold; font-size:10pt; background-color: #000000; width: 679px; color:#ffffff; text-align: center">
        <?php
        if (empty($_REQUEST['titulo'])) {
            ?>
            Lista de Presença
            <?php
        } else {
            echo $_REQUEST['titulo'];
        }
        ?>

    </div>

    <?php
    $per = explode('|', $model->completadadossala($kw));
    ?>

    <div style = "border-width: 0.5px; border-style: solid; font-weight:bold; font-size:8pt; width: 679px; text-align: left">
        <?php echo $per[0] . ' - Ano Letivo: ' . $per[1] . ' - Período: ' . $per[2] . ' - Prof. ' . $prof ?>
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
                Situação
            </td>
            <!--
            <td>
                Telefone
            </td>
            -->
            <td style="width: 20%">
                Assinatura do Responsável      
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
                <!--
                <td style="background-color: <?php echo $cor ?>">
                <?php echo $v['tel1'] ?>
                </td>
                -->
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['situacao'] ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo ($v['situacao'] == 'Frequente') ? '': $v['situacao'] ?>
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