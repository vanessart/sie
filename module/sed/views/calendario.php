<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="body">
    <div class="fieldTop">
        Calendário
    </div>
    <?php
    $abas[1] = ['nome' => "Curso", 'ativo' => 1, 'hidden' => []];
    $abas[2] = ['nome' => "Calendário", 'ativo' => 1, 'hidden' => []];
    $abas[3] = ['nome' => "Legendas", 'ativo' => 1, 'hidden' => []];
    $aba = report::abas($abas);
    include ABSPATH . "/module/sed/views/_calendario/$aba.php";
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
</div>
