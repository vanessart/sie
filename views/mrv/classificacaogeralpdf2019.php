<?php
ob_start();
$cor = '#F5F5F5';
?>
<head>
    <style>
        td{
            font-size: 8pt;
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
ini_set('memory_limit', '20000M');
set_time_limit(1000000000);
$esc = $model->pegaescola();

$seq = 0;

foreach ($esc as $w) {
    ?>
    <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
        Classificação Geral por Escola - Reserva de Vagas ITB 2019/2020
    </div>
    <div style="font-weight:bold; border: 1px solid; font-size:10pt; color:red; text-align: center">
        <?php echo $w['n_inst'] ?>
    </div>
    <?php
    $esc2 = sql::get('mrv_beneficiado', '*', 'WHERE cie_ben = ' . $w['cie_escola'] . " AND status_ben = '" . 'Deferida' . "' AND fk_id_pl = 27 AND categoria IN(1,2,3,4) ORDER BY classificacao_geral");
    ?>
    <table class="table tabs-stacked table-bordered">;
        <thead>
            <tr>
                <td rowspan="2">
                    Seq.       
                </td>
                <td rowspan="2">
                    Categoria       
                </td>
                <td colspan="2">
                    Classificação     
                </td>
                <td rowspan="2">
                    RA     
                </td> 
                <td rowspan="2">
                    RSE     
                </td>             
                <td rowspan="2">
                    Nome do Aluno
                </td>
                <td rowspan="2">
                    Data Nasc.
                </td>
                <td colspan="5">
                    Média
                </td>
            </tr>
            <tr>
                <td>
                    Escola
                </td>
                <td>
                    Rede
                </td>
                <td>
                    6º Ano   
                </td>
                <td>
                    7º Ano
                </td>
                <td>
                    8º Ano
                </td>
                <td>
                    9º Ano
                </td>
                <td>
                    Média Geral
                </td>       
            </tr>
        </thead>
        <?php
        foreach ($esc2 as $v) {
            ?>
            <tbody>
                <tr>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php
                        $seq += 1;
                        echo $seq;
                        ?>
                    </td>                   
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo $v['categoria'] ?>
                    </td>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo $v['classificacao_escola'] ?>
                    </td>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo $v['classificacao_geral'] ?>
                    </td>                
                    <td style="background-color:<?php echo $cor ?>">
                        <?php echo $v['ra_ben'] . ' - ' . $v['ra_dig']?>
                    </td>
                    <td style="background-color:<?php echo $cor ?>">
                        <?php echo $v['id_pessoa'] ?>
                    </td>
                    <td style="text-align: left; background-color: <?php echo $cor ?>">
                        <?php echo addslashes($v['n_pessoa']) ?>
                    </td>   
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo data::converteBr($v['dt_nasc']) ?>
                    </td>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo $v['media6_ben'] ?>
                    </td>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo $v['media7_ben'] ?>
                    </td>
                    <td style="background-color:<?php echo $cor ?>">
                        <?php echo $v['media8_ben'] ?>
                    </td>
                    <td style="background-color:<?php echo $cor ?>">
                        <?php echo $v['media9_ben'] ?>
                    </td>
                    <td style="background-color:<?php echo $cor ?>">
                        <?php echo $v['media_final_ben'] ?>
                        <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5' ?>
                    </td>
                </tr>
            </tbody>
            <?php
        }
        ?>       
    </table>

    <div style="page-break-before: always"></div>
    <?php
}
?>

<?php
$model->pdfSecretariaE('L');
?>