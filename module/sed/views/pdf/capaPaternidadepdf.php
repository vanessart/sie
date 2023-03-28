<?php
ob_clean();
ob_start();
$header = '<table style="width: 100%; border: 1px solid">'
        . '<tr>'
        . '<td rowspan = "5">'
        . '<img style="width: 70px" src="' . HOME_URI . '/views/_images/brasao.jpg"/>'
        . '</td>'
        . '<td style="font-size: 18px">'. CLI_NOME .'</td>'
        . '<td rowspan = "5" style=" text-align: right">'
        . '<img style="width: 210px;" src="' . HOME_URI . '/views/_images/logo_relatorio.jpg"/>'
        . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="font-size: 16px">SE - Secretaria de Educação</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="font-size: 12px">'. CLI_END .'</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="font-size: 12px">'. CLI_BAIRRO .' - '. CLI_CIDADE .' - '. CLI_UF .' CEP '. CLI_CEP .' Fone '. CLI_FONE .'</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="font-size: 12px">'. CLI_URL .' - Email: '. CLI_MAIL .'</td>'
        . '</tr>'
        . '</table>';

$pdf = new pdf();
$pdf->orientation = 'L';
$pdf->headerAlt = $header;
$pdf->mgt = 29;
$pdf->mgb = 15;
$pdf->mgh = 15;
$pdf->mgr = 10;
$pdf->mgl = 10;
$pdf->mgf = 15;
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
        .topo{
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
<br />
<div class="topo" style="font-size: 18pt; border: 1px solid">
    <br /><br /><br /><br /><br /><br /><br /><br />
    Projeto Paternidade Responsável do Conselho Nacional de Justiça
    <br />
    <span style="fonte-size:16pt">
        Relação de Alunos Sem Paternidade Estabelecida
    </span>
    <br /><br /><br /><br /><br /><br /><br />
</div>


<?php
$pdf->exec();
?>