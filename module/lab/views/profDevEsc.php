<?php
if (!defined('ABSPATH'))
    exit;
ob_start();
##################            
?>
  <pre>   
    <?php
      print_r($_REQUEST);
    ?>
  </pre>
<?php
###################
?>
<style>
    td{
        padding: 3px;
    }
</style>
 
<br /><br /><br />
<table style="width: 100%">
    <tr>
        <td style="width: 35%">&nbsp;</td>
        <td style="width: 5%">&nbsp;</td>
        <td>
            Baruei, <?= data::porExtenso(date("Y-m-d")) ?>
        </td>
    </tr>
    <tr>
        <td>
            Nome:
            <hr>
            Função/cargo:
            <hr>
            Matrícula:
            <hr>
        </td>
        <td style="width: 5%">&nbsp;</td>
        <td>
            Responsável:
            <hr>
        </td>
    </tr>
</table>
<?php
tool::pdf();
