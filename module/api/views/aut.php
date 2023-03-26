<?php
if (!defined('ABSPATH'))
    exit;
$pdf = new pdf();
$pdf->autentica();

##################            
?>
<pre>   
    <?php
    print_r($_POST);
    ?>
</pre>
<?php
###################
?>
teste
<?php
$pdf->exec();
