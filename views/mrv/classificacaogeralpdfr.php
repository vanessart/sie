<?php
ob_start();
$cor = '#F5F5F5';
?>
<head>
    <style>
        td{
            font-size: 8px;
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
$esc = $model->pegaescola(tool::id_inst());
//$esc = $model->pegaescola();
$seq = 0;

foreach ($esc as $w) {
    ?>
    <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
        Classificação Geral por Escola - Reserva de Vagas ITB 2022/2023
    </div>
    <div style="font-weight:bold; border: 1px solid; font-size:10pt; color:red; text-align: center">
        <?php echo $w['n_inst'] ?>
    </div>
    <?php
    //$esc2 = sql::get('mrv_beneficiado', '*', 'WHERE cie_ben = ' . $w['cie_escola'] . " AND status_ben = '" . 'Deferida' . "' AND fk_id_pl = 27 AND categoria IN(1,2,3,4) ORDER BY classificacao_geral");
    $esc2 = sql::get('mrv_beneficiado', '*', 'WHERE cie_ben = ' . $w['cie_escola'] . " AND status_ben = '" . 'Deferida' . "' AND fk_id_pl = 87 AND categoria IN(1) ORDER BY classificacao_geral");
    ?>
    <table class="table tabs-stacked table-bordered">;
        <thead>
            <tr>
                <td rowspan="2" style="width: 2%">
                    Seq.       
                </td>
                <!--
                <td rowspan="2" style="width: 4%">
                    Categoria       
                </td>
                -->
                <td colspan="2" style="width: 4%">
                    Classificação     
                </td>
                <td rowspan="2" style="width: 8%">
                    RA     
                </td> 
                <td rowspan="2" style="width: 8%">
                    RSE     
                </td>  
                <td rowspan="2" style="width: 8%">
                    CPF     
                </td> 
                <td rowspan="2" style="width: 10%">
                    SUS     
                </td>  
                <td rowspan="2" style="width: 31%">
                    Nome do Aluno/email
                </td>
                <td rowspan="2" style="width: 4%">
                    Data Nasc.
                </td>
                <td colspan="5" style="width: 25%">
                    Média
                </td>
            </tr>
            <tr>
                <td style="width: 2%">
                    Rede
                </td>
                <td style="width: 2%">
                    Escola
                </td>
                <td style="width: 5%">
                    6º Ano   
                </td>
                <td style="width: 5%">
                    7º Ano
                </td>
                <td style="width: 5%">
                    8º Ano
                </td>
                <td style="width: 5%">
                    9º Ano
                </td>
                <td style="width: 5%">
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
                    <!--
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo $v['categoria'] ?>
                    </td>
                    -->
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo $v['classificacao_geral'] ?>
                    </td>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo $v['classificacao_escola'] ?>
                    </td>                           
                    <td style="background-color:<?php echo $cor ?>">
                        <?php echo $v['ra_ben'] ?>
                    </td>
                    <td style="background-color:<?php echo $cor ?>">
                        <?php echo $v['id_pessoa'] ?>
                    </td>
                    <td style="background-color:<?php echo $cor ?>">
                        <?php echo $v['cpf_aluno'] ?>
                    </td>
                    <td style="background-color:<?php echo $cor ?>">
                        <?php echo $v['sus'] ?>
                    </td>
                    <td style="text-align: left; background-color: <?php echo $cor ?>">
                        <?php echo addslashes($v['n_pessoa']) ?>
                        <br />
                        <?php echo $v['email_fieb_ben'] ?>
                    </td>   
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo data::converteBr($v['dt_nasc']) ?>
                    </td>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo ($v['media6_ben'] == 0 ? '*' . $v['media6_ben'] : $v['media6_ben']) ?>
                    </td>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo ($v['media7_ben'] == 0 ? '*'. $v['media7_ben']: $v['media7_ben']) ?>
                    </td>
                    <td style="background-color:<?php echo $cor ?>">
                        <?php echo ($v['media8_ben'] == 0 ? '*' . $v['media8_ben'] : $v['media8_ben']) ?>
                    </td>
                    <td style="background-color:<?php echo $cor ?>">
                        <?php echo ($v['media9_ben'] == 0 ? '*' . $v['media9_ben'] : $v['media9_ben']) ?>
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

    <?php
}
?>

<?php
//$model->pdfSecretariaE('L');
tool::pdfSecretariaE('L');
?>