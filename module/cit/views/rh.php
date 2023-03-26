<?php
if (!defined('ABSPATH'))
    exit;
$ano = date("Y");
?>
<div class="body">
    <div class="fieldTop">
        APIs RH
    </div>
    <?php
    $abas[1] = ['nome' => "Ano Escolar", 'ativo' => 1];
    $abas[2] = ['nome' => "Disciplinas", 'ativo' => 1];
    $abas[3] = ['nome' => "Alocação", 'ativo' => 1];
    $abas[4] = ['nome' => "Processar", 'ativo' => 1];

    $aba = report::abas($abas);
    include ABSPATH . "/module/cit/views/_rh/$aba.php";
    ?>
</div>
