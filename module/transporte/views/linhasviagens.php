<?php
ob_start();
if (!defined('ABSPATH'))
    exit;

$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_em = filter_input(INPUT_POST, 'id_em', FILTER_SANITIZE_NUMBER_INT);
$id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);

$array = $model->pegacabecalhorel($id_li, $id_inst, $id_em);
if (empty($array)) {
    ?>
    <div>
        Não Existem Dados referente a esta consulta.
    </div>
    <?php
    return;
}

$a = [];
foreach ($array as $key => $value) {
    $a[] = [
        'Empresa' => $value['n_em'],
        'Escola' => $value['n_inst'],
        'Linha' => $value['n_li'],
        'Viagem' => $value['viagem'],
        'Motorista' => $value['motorista'],
        'Monitor' => $value['monitor'],
    ];
}

toolErp::geraExcel($a);
