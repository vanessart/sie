<?php
ob_clean();
ob_start();
$header = '<table style="width: 100%; border: 1px solid">'
        . '<tr>'
        . '<td rowspan = "5">'
        . '<img style="width: 70px" src="' . HOME_URI . '/views/_images/brasao.jpg"/>'
        . '</td>'
        . '<td style="font-size: 18px">Prefeitura Municipal de Barueri</td>'
        . '<td rowspan = "5" style=" text-align: right">'
        . '<img style="width: 210px;" src="' . HOME_URI . '/views/_images/logo_relatorio.jpg"/>'
        . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="font-size: 16px">SE - Secretaria de Educação</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="font-size: 12px">Rua Cabo PM José Maria Schiavelli nº. 125</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="font-size: 12px">Jardim dos Camargos - Barueri - SP CEP  06410-355 Fone (11) 4199-2900</td>'
        . '</tr>'
        . '<tr>'
        . '<td style="font-size: 12px">http://portal.educ.net.br/ - Email: gabinete@educbarueri.sp.gov.br</td>'
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