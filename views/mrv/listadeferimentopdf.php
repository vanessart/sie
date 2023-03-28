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
            padding: 5px;

        }
        .topo{
            font-size: 7pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            padding: 5px;

        }
    </style>
</head>

<?php
foreach ($_POST['sel'] as $v) {
    if (!empty($v)) {
        $turma[] = $v;
    }
}

foreach ($turma as $t) {

    $lista = $model->listadeferimento($t);
    $per = explode('|', $model->completadadossala($t));
    if (!empty($proximaFolha)) {
        ?>
        <div style="page-break-after: always"></div>
        <?php
    } else {
        $proximaFolha = 1;
    }
    ?>

    <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
        Lista Piloto para Conferência com Ficha de Inscrição
    </div>
    <div style = "border-width: 0.5px; border-style: solid; font-weight:bold; font-size:8pt; text-align: left; padding: 3px">
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
                Data Nasc.
            </td>
            <td>
                Nome Aluno
            </td>
            <td>
                Status
            </td>
            <td>
                Categoria     
            </td>
        </tr>    
        <?php
        foreach ($lista as $v) {
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
                <td style="background-color: <?php echo $cor ?>"> 
                    <?php echo data::converteBr($v['dt_nasc']) ?>
                </td>
                <td style="text-align: left; background-color: <?php echo $cor; ?>">
                    <?php echo addslashes($v['n_pessoa']) ?>
                </td>      
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo (($v['cidade'] == CLI_CIDADE OR$v['cidade'] == ucfirst(CLI_CIDADE)) ? $v['status_ben'] : $v['cidade']) ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['categoria'] ?>
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