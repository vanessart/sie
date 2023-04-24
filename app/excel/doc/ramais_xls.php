<?php

use PHPExcel_Style_Border;

require_once '../../Conexao.php';
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Sao_Paulo');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("dttie")
        ->setTitle("Ramais Internos")
        ->setSubject("")
        ->setDescription("")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("");

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B2', 'Ramais e E-mails');

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A4', 'Departamento')
        ->setCellValue('B4', 'Colaborador')
        ->setCellValue('C4', 'Ramal')
        ->setCellValue('D4', ' Tel. Direto')
        ->setCellValue('E4', ' E-mail');

// Colocar uma borda em torno da área A1:A5
$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);

$c = 5;
$cc = 4;
$no_old = NULL;
$sql = "SELECT n_dep, n_col, dep_colabore.ramal AS ramal, teld_col, email, ramal1 FROM `dep_colabore` "
        . "INNER JOIN department ON concat('2_',department.id_dep) = dep_colabore.fk_id "
        . "WHERE n_dep NOT LIKE '|||%' AND n_dep != ''  "
        . "ORDER BY n_dep ASC, n_col ASC";
$query = Conexao::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $col_f){
    if (!empty($col_f['teld_col'])) {
        $teld = str_replace('-', '', $col_f['teld_col']);
        $teld = substr($teld, 0, 4) . '-' . substr($teld, 4, 4);
    } else {
        $teld = NULL;
    }
    if (!empty($col_f['ramal'])) {

        if ($no_old != $col_f['n_dep']) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $c, $col_f['n_dep']);
            $ccc = $c - 1;
            $objPHPExcel->getActiveSheet()->getStyle('A' . $cc . ':A' . $ccc)->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
            $cc = $c;
        }
        $objPHPExcel->getActiveSheet()->getStyle('B' . $c . ':E' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);

// Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B' . $c, $col_f['n_col'])
                ->setCellValue('C' . $c, $col_f['ramal'] . (!empty($col_f['ramal1']) ? "-" . $col_f['ramal1'] : ''))
                ->setCellValue('D' . $c, $teld)
                ->setCellValue('E' . $c, $col_f['email']);

        $no_old = $col_f['n_dep'];
        $c++;
    }
}
$objPHPExcel->getActiveSheet()->getStyle('A'.$c)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
$objPHPExcel->getActiveSheet()->getStyle('A1');
// Definir a largura da coluna A para automático/auto-ajustar
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('40');
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('40');
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('8');
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('12');
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('40');



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
