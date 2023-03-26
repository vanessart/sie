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

$a = $model->alimento();
?>
<style>
    @media print{
        .border{ page-break-inside:avoid; }
    }
</style>
<div class="body">
    <div style="text-align: right">
        <form action="<?= HOME_URI ?>/maker/pdf/lanchePlan" target="_blank" method="POST">
            <button style="width: 200px" class="btn btn-warning">
                Exportar Planilha
            </button>
        </form>
    </div>
    <div class="fieldTop">
                Quadro Geral
            </div>
    <?php
    include ABSPATH . '/module/maker/views/_lache/tabela.php';
    ?>
    <div class="page-break-after: always;"></div>
    <?php
    $polos_ = sql::get('maker_polo', 'n_polo, fk_id_inst_maker ', ['>' => 'id_polo']);
    $c = 1;
    foreach ($polos_ as $y) {
        $n_polo = $y['n_polo'];
        if (!empty($n_polo)) {
            ?>
            <div class="fieldTop">
                <?= $n_polo ?>
            </div>
            <?php
        }
        ?>

        <?php
        $a = $model->alimento($y['fk_id_inst_maker']);
        include ABSPATH . '/module/maker/views/_lache/tabela.php';
        if ($c % 3 == 0) {
            ?>
            <div class="page-break-after: always;"></div>
            <?php
        }
        $c++;
    }
    ?>
</div>
