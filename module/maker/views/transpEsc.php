<?php

if (!defined('ABSPATH'))
    exit;

ob_start();
$pdf = new pdf();
$pdf->mgb = '30';
$pdf->orientation = 'L';
$pdf->headerAlt = '<table style="width: 100%"><tr><td><img style="height: 100px" src="' . ABSPATH . '/'. INCLUDE_FOLDER .'/images/topo.jpg"/></td><td style="text-align: center; font-weight: bold; font-size: 14px">Transporte</td></tr></table>';
?>
<table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 

</table>
<?php

$pdf->exec();
?>