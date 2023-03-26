<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="body">
    <div class="fieldTop">
        Gerenciamento de E-mails
    </div>
    <?php
    $abas[1] = ['nome' => "Ativados", 'ativo' => 1, 'hidden' => []];
    $abas[2] = ['nome' => "Destivados", 'ativo' => 1, 'hidden' => []];
    $aba = report::abas($abas);
    include ABSPATH . "/module/cit/views/_email/$aba.php";
    ?>
</div>

