<?php

extract($_POST);

use PHPExcel_Style_Border;

require_once '../../Conexao.php';
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Sao_Paulo');

require_once '../../../class/class-escolas.php';
require_once '../../../class/class-sqlDB.php';

$prof = escolas::professor($ano, $disc, $cie, $nome);

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("dttie")
        ->setTitle("Professores")
        ->setSubject("")
        ->setDescription("")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("");

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B2', 'Professores');

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A4', 'Matrícula')
        ->setCellValue('B4', 'Nome')
        ->setCellValue('C4', 'Disciplina')
        ->setCellValue('D4', ' Escola');

// Colocar uma borda em torno da área A1:A5
//$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
$objPHPExcel->getActiveSheet()->getStyle('A4:D4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$c = 5;
$cc = 4;


foreach ($prof as $col_f) {
    $objPHPExcel->getActiveSheet()->getStyle('A' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('B' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('C' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('D' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

// Add some data
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $c, $col_f['rm'])
            ->setCellValue('B' . $c, $col_f['nome'])
            ->setCellValue('C' . $c, $col_f['disc'])
            ->setCellValue('D' . $c, $col_f['escola']);
    $c++;
}
$objPHPExcel->getActiveSheet()->getStyle('A' . $c)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
$objPHPExcel->getActiveSheet()->getStyle('A1');
// Definir a largura da coluna A para automático/auto-ajustar
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('80');
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('40');
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('80');



// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Ramais Internos');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Professores-' . date("Y-m-d") . '.xls"');
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
