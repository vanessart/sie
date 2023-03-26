<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="body">
    <div class="fieldTop">
        Mala direta
    </div>


    <?php
    $abas[1] = ['nome' => "Mala Direta", 'ativo' => 1, 'hidden' => []];
    $abas[2] = ['nome' => "Editar", 'ativo' => null, 'hidden' => []];
    $abas[3] = ['nome' => "Imprimir", 'ativo' => null, 'hidden' => []];
    $aba = report::abas($abas);

    include ABSPATH . "/module/sed/views/_mala/$aba.php";
    ?>
</div>