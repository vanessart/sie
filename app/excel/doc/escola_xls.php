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
        ->setTitle("Escolas")
        ->setSubject("")
        ->setDescription("")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("");

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B2', 'Escolas');

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A4', 'Escolas')
        ->setCellValue('B4', 'Diretor')
        ->setCellValue('C4', 'Telefones')
        ->setCellValue('D4', ' E-mail')
        ->setCellValue('E4', ' Endereço');

// Colocar uma borda em torno da área A1:A5
//$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$c = 5;
$cc = 4;
$no_old = NULL;
            $campos = "n_col, n_school, email_school AS email, tel1, tel2, address_school, num_school, district_school,zipcode_school as CEP";
            $sql = "SELECT $campos from dep_colabore "
                    . "right join school on concat('1_',school.cie_school) =  dep_colabore.fk_id "
                    . "where atrib like '1' "
                    . "AND fk_id like'1_%' "
                    . "order by n_school";
            $query = Conexao::getInstance()->query($sql);
        $col_q = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($col_q as $col_f) {
$objPHPExcel->getActiveSheet()->getStyle('A'.$c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle('B'.$c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle('C'.$c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle('D'.$c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle('E'.$c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

// Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $c, $col_f['n_school'])
                ->setCellValue('B' . $c, $col_f['n_col'])
                ->setCellValue('C' . $c, $col_f['tel1'] . (!empty($col_f['tel2']) ? "-" . $col_f['tel2'] : ''))
                ->setCellValue('D' . $c, $col_f['email'])
                ->setCellValue('E' . $c, $col_f['address_school'] . ', ' . $col_f['num_school'].'  '.$col_f['district_school'].' - CEP: '.$col_f['CEP']);
        $c++;
}
$objPHPExcel->getActiveSheet()->getStyle('A'.$c)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
$objPHPExcel->getActiveSheet()->getStyle('A1');
// Definir a largura da coluna A para automático/auto-ajustar
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('50');
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('40');
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('12');
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('35');
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('70');



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
