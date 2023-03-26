<?php
if (!defined('ABSPATH'))
    exit;

?>
<style type="text/css">
    .modal_red{
        color: red;
        font-weight: bold;
        text-align: center;
    }
    .modal_green{
        color: green;
        font-weight: bold;
        text-align: center;
    }
    .modal_blue{
        color: blue;
        font-weight: bold;
        text-align: center;
    }
</style>
<div class="body">
    <div class="fieldTop">
        Protocolos
    </div>
</div>

<?php
$abas[1] = ['nome' => "Todos os Protocolos", 'ativo' => 1];
$abas[2] = ['nome' => "Meus Protocolos", 'ativo' => 1];
$aba = report::abas($abas);
include ABSPATH . "/module/cadampe/views/_abas/$aba.php"

?>
