<?php
if (!defined('ABSPATH'))
    exit;
$diaSem = [
    2 => 'Segunda',
    3 => 'Terça',
    4 => 'Quarta',
    5 => 'Quinta',
    6 => 'Sexta'
];
$escolas = $model->escolasMaker();
?>
<div class="body">
    <div class="fieldTop">
        Lista de Transporte Geral
    </div>
    <?php
    $a = $model->transporteEsc();
    include ABSPATH . '/module/maker/views/_lache/tabela2.php';
    foreach ($escolas as $ke => $ee) {
        $a = $model->transporteEsc($ke);
        if ($a) {
            ?>
            <div class="fieldTop">
                <?= $ee ?>
            </div>
            <?php
            include ABSPATH . '/module/maker/views/_lache/tabela2.php';
        }
    }
    ?>
</div>
