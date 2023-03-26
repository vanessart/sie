<?php

if (!defined('ABSPATH'))
    exit;
ob_start();
$pdf = new pdf();
$_POST = $pdf->autentica();
$id_manut = @$_POST['id_manut'];
if (!empty($id_manut)) {
    $manut = $model->manut($id_manut);
    if ($manut) {
        $id_ch = $manut['fk_id_ch'];
        $empret = $model->chromebook($id_ch);
    }
}
echo 'protocolo';
##################            
?>
  <pre>   
    <?php
      print_r($manut);
    ?>
  </pre>
<?php
###################
##################            
?>
  <pre>   
    <?php
      print_r($empret);
    ?>
  </pre>
<?php
###################
$pdf->exec();


