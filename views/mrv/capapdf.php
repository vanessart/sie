<?php

ob_start();

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

<div style="text-align: center; font-size: 18pt; border: 1px solid">
    <br /><br /><br /><br /><br />
    CLASSIFICAÇÃO NO SISTEMA DE RESERVA DE VAGAS (ITB 2022/2023)
    <br /><br />
    OUTUBRO/2022
    <br /><br /><br /><br /><br /><br /><br /><br /><br />
</div>
        

<?php

tool::pdfSecretariaE('L');
//$model->pdfSecretariaE('L');
?>