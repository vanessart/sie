<?php
ob_start();
//$escola = new escola(@$_POST['id_inst']);
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
        .quebra { 
            page-break-before: always; 
        }
    </style>
</head>
<?php
if (!empty($_POST['sel'])) {
    foreach ($_POST['sel'] as $v) {
        if (!empty($v)) {
            $turma[] = $v;
        }
    }

    $idturmas = implode(",", $turma);
    $controle = $model->fichainscricao($idturmas);

    foreach ($controle as $v) {
        $wturma[$v['codigo']][] = $v;
    }

    foreach ($wturma as $k => $w) {
        if (!empty($proximaFolha)) {
            ?>
            <div style="page-break-after: always"></div>
            <?php
        } else {
            $proximaFolha = 1;
        }
        ?>

        <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
            Controle de Entrega
        </div>
        <div style = "border-width: 0.5px; border-style: solid; font-weight:bold; font-size:8pt; text-align: left">
            <?php echo 'Código Turma: ' . $k ?>
        </div> 

        <table class="table tabs-stacked table-bordered">;

            <tr>
                <td>
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
                    Data Entrega/Visto
                </td>
                <td>
                    Data Retorno/Visto     
                </td>
                <td>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Observação&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;     
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
                    <td style="background-color: <?php echo $cor ?>"> 
                        <?php echo data::converteBr($v['dt_nasc']) ?>
                    </td>
                    <td style="text-align: left; background-color: <?php echo $cor; ?>">
                        <?php echo addslashes($v['n_pessoa']) ?>
                    </td>      
                    <td style="background-color: <?php echo $cor ?>">

                    </td>
                    <td style="background-color: <?php echo $cor ?>">

                    </td>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5' ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>

        <?php
    }
    tool::pdfescola('P');
}
?>