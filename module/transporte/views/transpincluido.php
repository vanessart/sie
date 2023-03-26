<?php
ob_clean();
ini_set('memory_limit', '20000M');
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

if (!defined('ABSPATH'))
    exit;

$idinst = filter_input(INPUT_POST, 'idinst', FILTER_SANITIZE_NUMBER_INT);
$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_NUMBER_INT);

if ( empty($_POST['mes']) || empty($_POST['idinst']) ) {
    ?>
    <div>
    Favor Selecionar a Escola e o Mês
    </div>
    <?php
    return;
}

$array = $model->movimentacaoalunoinclusao($_POST['mes'], $_POST['idinst']);
if (empty($array)) {
    ?>
    <div>
        Não Existem Dados referente a esta consulta.
    </div>
    <?php
    return;
}

foreach ($array as $k => $v) {
    if (!empty($v['Data'])) {
        $array[$k]['Data'] = dataErp::converteBr($v['Data']);
    }
}

toolErp::geraExcel($array);
