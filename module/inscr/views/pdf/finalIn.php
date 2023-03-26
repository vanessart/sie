<?php
if (!defined('ABSPATH'))
    exit;
ob_start();
$pdf = new pdf();
$pdf->headerSet = null;
?>

final|In

<?php
$pdf->exec();
?>