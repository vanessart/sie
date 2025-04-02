<?php
if (!defined('ABSPATH'))
    exit;
$id_inst_sieb = filter_input(INPUT_POST, 'id_inst_sieb', FILTER_SANITIZE_NUMBER_INT);
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$periodo = filter_input(INPUT_POST, 'periodo');
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$transporte = filter_input(INPUT_POST, 'transporte');
$frequencia = filter_input(INPUT_POST, 'frequencia', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$dataIni = filter_input(INPUT_POST, 'dataIni');
$dataFim = filter_input(INPUT_POST, 'dataFim');

$dados = $model->relatFerq($id_polo, $id_inst_sieb, $periodo, $id_curso, $frequencia, 1, $dataIni, $dataFim, $id_pl);
if (empty($dados['alunos'])) {
    toolErp::alertModal('Dados não encontrados');
    exit();
} else {
    $array = $dados['alunos'];
}
$nomearquivo = 'Frequencia';
if (!empty($array)) {

    toolErp::geraExcel($array);

} else {
    ?>
    <div>
        Não Existem Dados referente a esta consulta.
    </div>
    <?php
}

