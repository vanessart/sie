<?php
if (!in_array(tool::id_pessoa(), [1, 6])) {
    echo 'Em Desenvolvimento';
    exit();
}
ob_start();
$pdf = new pdf();
##################            
?>
<pre>   
    <?php
    print_r($_REQUEST);
    ?>
</pre>
<?php
###################

$pdf->exec();
