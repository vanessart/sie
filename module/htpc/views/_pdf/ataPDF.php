<?php
if (!defined('ABSPATH'))
    exit;

ob_start();
$pdf = new pdf();
?>
<style>
    .table{
        width: 100%;
        border: 1;
        font-size: 12px;
        cellspacing: 0;
        cellpadding: 2;
    }
    .table td{
        padding: 4px;
    }
</style>

<?php

$pdfView = true;
require_once __DIR__.'/../ataVisualizar.php';

$pdf->exec();
