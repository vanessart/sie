<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Sao_Paulo');

use PHPExcel_Style_Border;

require_once 'pdoSis.php';


if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("dttie")
        ->setTitle("Situação Final")
        ->setSubject("")
        ->setDescription("")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("");


$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Escolas')
        ->setCellValue('B1', 'Ano')
        ->setCellValue('C1', 'Situação final')
        ->setCellValue('D1', 'Quantidade');


$c = 2;

$sql = "select i.n_inst, i.id_inst, c.n_ciclo, c.id_ciclo, t.periodo_letivo, ta.situacao, ta.situacao_final "
        . " from ge_turma_aluno ta "
        . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
        . " join ge_ciclos c on c.id_ciclo = t.fk_id_ciclo "
        . " join instancia i on i.id_inst = t.fk_id_inst "
        . " join ge_situacao_final sf on sf.id_sf = ta.situacao_final "
        . " where t.periodo_letivo like '2018' "
        . " order by n_inst, n_ciclo, n_sf";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($array as $v) {
    if ($v['situacao'] == 'Frequente') {
        if (in_array($v['situacao_final'], [1, 2, 7])) {
            $sit = "Promovido";
        } elseif (in_array($v['situacao_final'], [3, 4, 5])) {
            $sit = "Retido";
        }
    } elseif(in_array($v['situacao'], ['Transferido Escola', 'Abandono', 'Evadido', 'Não Comparecimento', 'Falecido'])) {
        $sit = $v['situacao'];
    }
    @$result[$v['n_inst'] . '|' . $v['n_ciclo'] . '|' . $sit] ++;
}
foreach (@$result as $k => $v) {
    $var = explode("|", $k);
    $objPHPExcel->getActiveSheet()->getStyle('A' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('B' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('C' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('D' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

// Add some data
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $c, $var[0])
            ->setCellValue('B' . $c, $var[1])
            ->setCellValue('C' . $c, $var[2])
            ->setCellValue('D' . $c, $v);
    $c++;
}
$objPHPExcel->getActiveSheet()->getStyle('A' . $c)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
$objPHPExcel->getActiveSheet()->getStyle('A1');
// Definir a largura da coluna A para automático/auto-ajustar
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('40');
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('15');
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('25');
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('10');



// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Ramais Internos');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Ramais Internos.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
