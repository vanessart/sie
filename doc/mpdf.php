<?php

include( ABSPATH . "/app/mpdf/mpdf.php");


$mpdf = new mPDF('c', 'A4');

$body = 'oi';
$footer = "<table width=\"1000\"><tr><td style='font-size: 18px; padding-bottom: 20px;' align=\"right\">{PAGENO}</td></tr></table>";

$mpdf->SetHTMLFooter($footer);
$css = file_get_contents(ABSPATH . "/views/_css/style.css");
$mpdf->WriteHTML($css, 1);
$css1 = file_get_contents(ABSPATH . "/views/_css/bootstrap-theme.css");
$mpdf->WriteHTML($css1, 1);
$css2 = file_get_contents(ABSPATH . "/views/_css/bootstrap.css");
$mpdf->WriteHTML($css2, 1);

$mpdf->WriteHTML($body);

$mpdf->Output();
exit;
