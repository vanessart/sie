<?php
if (!defined('ABSPATH'))
    exit;
$presenca = filter_input(INPUT_POST, 'presenca', FILTER_SANITIZE_NUMBER_INT);
$bimestre = filter_input(INPUT_POST, 'bimestre', FILTER_SANITIZE_NUMBER_INT);
$id_pdi = filter_input(INPUT_POST, 'id_pdi', FILTER_SANITIZE_NUMBER_INT);
$presenca_texto = filter_input(INPUT_POST, 'presenca_texto', FILTER_SANITIZE_STRING);

$atend = $model->getAtend($id_pdi,$bimestre,null, $presenca);
$titulo = $presenca_texto." DO ".$bimestre."º BIMESTRE";
if ($atend) {
    $model->ViewHistoricoAtend($atend,$titulo);
}else{
    toolErp::divAlert('warning','Sem Resultados');
}
