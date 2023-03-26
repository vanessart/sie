<?php
ob_clean();
if (!defined('ABSPATH'))
    exit;

$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_NUMBER_INT);
if (!empty($_POST['sel'])) {
    foreach ($_POST['sel'] as $v) {
        if (!empty($v)) {
            $idlinha[] = $v;
        }
    }
}
@$idlinha = implode(",", $idlinha);
if (empty($ano)) {
    $ano = date("Y");
}
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$al = transporteErp::alunoFrequencia($mes, $idlinha, $ano)['aluno'];

if (empty($al)) {
    ?>
    <div>
        Não Existem Dados referente a esta consulta.
    </div>
    <?php
    return;
}

$num = 1;
foreach ($al as $v) {
    if ($v['id_inst'] = $id_inst) {
        $numString = (string) $num++;
        $array[] = [
            'Seq.' => $numString,
            'RA' => $v['ra'],
            'Nome Aluno' => $v['n_pessoa'],
            'Cód.Classe' => $v['codigo'],
            'Data Nasc.' => dataErp::converteBr($v['dt_nasc']),
            'Linha' => $v['n_li']
        ];
    }
}

if (empty($array)) {
    ?>
    <div>
        Não Existem Dados referente a esta consulta.
    </div>
    <?php
    return;
}

toolErp::geraExcel($array);
