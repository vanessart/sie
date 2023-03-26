<?php
ob_clean();
ini_set('memory_limit', '200M');
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

if (!defined('ABSPATH'))
    exit;

$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_NUMBER_INT);

if ( empty($_POST['mes']) ) {
    ?>
    <div>
    Favor Selecionar o Mês
    </div>
    <?php
    return;
}

$array = transporteErp::arquivoexcel($mes);
if (empty($array)) {
    ?>
    <div>
        Não Existem Dados referente a esta consulta.
    </div>
    <?php
    return;
}

$datas = ['Dt_Nasc', 'dt_inicio', 'dt_fim'];
foreach ($array as $k => $v) {
    if (!empty($v['Dt_Nasc'])) {
        $array[$k]['Dt_Nasc'] = dataErp::converteBr($v['Dt_Nasc']);
    }
    if (!empty($v['dt_inicio'])) {
        $array[$k]['dt_inicio'] = dataErp::converteBr($v['dt_inicio']);
    }
    if (!empty($v['dt_fim'])) {
        $array[$k]['dt_fim'] = dataErp::converteBr($v['dt_fim']);
    }
}

toolErp::geraExcel($array);
