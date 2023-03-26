<?php
ob_clean();
ini_set('memory_limit', '200M');
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

if (!defined('ABSPATH'))
    exit;

$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_STRING);

if ( empty($_POST['id_inst']) ) {
    ?>
    <div>
    Favor Selecionar a Escola
    </div>
    <?php
    return;
}

$periodo = null;
if (!empty($mes)) {
    $periodo = date('Y') .'-'. $mes;
}

$array = transporteErp::getListaEspera($periodo, $id_inst);
if (empty($array)) {
    ?>
    <div>
        Não Existem Dados referente a esta consulta.
    </div>
    <?php
    return;
}

$a = [];
foreach ($array as $k => $v) {
    $a[$k]['Nome Aluno'] = $v['n_pessoa'];
    $a[$k]['RA'] = $v['ra'];
    $a[$k]['Turma'] = $v['n_turma'];
    $a[$k]['Período'] = dataErp::periodoDoDia($v['periodo']);
    $a[$k]['Data da Solicitação'] = $v['dt_solicita'];
}

toolErp::geraExcel($a, false);
