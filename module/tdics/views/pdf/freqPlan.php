<?php
if (!defined('ABSPATH'))
    exit;
$id_inst_sieb = filter_input(INPUT_POST, 'id_inst_sieb', FILTER_SANITIZE_NUMBER_INT);
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$periodo = filter_input(INPUT_POST, 'periodo', FILTER_UNSAFE_RAW);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$transporte = filter_input(INPUT_POST, 'transporte', FILTER_UNSAFE_RAW);
$frequencia = filter_input(INPUT_POST, 'frequencia', FILTER_SANITIZE_NUMBER_INT);
$dataIni = filter_input(INPUT_POST, 'dataIni', FILTER_UNSAFE_RAW);
$dataFim = filter_input(INPUT_POST, 'dataFim', FILTER_UNSAFE_RAW);

$dados = $model->relatFerq($id_polo, $id_inst_sieb, $periodo, $id_curso, $frequencia, 1, $dataIni, $dataFim);
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

