<?php
if (!defined('ABSPATH'))
    exit;
$id_ec = filter_input(INPUT_POST, 'id_ec', FILTER_SANITIZE_NUMBER_INT);
$cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_NUMBER_INT);
$dados = $model->inscrito($id_ec);
ob_start();
$pdf = new pdf();

##################            
?>
  <pre>   
    <?php
      print_r($dados);
    ?>
  </pre>
<?php
###################

$pdf->exec();