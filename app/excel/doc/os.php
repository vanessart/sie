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
        ->setTitle("Alunos")
        ->setSubject("")
        ->setDescription("")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("");


$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Nome')
        ->setCellValue('B1', 'RG')
        ->setCellValue('C1', 'Sexo')
        ->setCellValue('D1', 'Bairro')
        ->setCellValue('E1', 'CEP')
        ->setCellValue('F1', 'Endereço')
        ->setCellValue('G1', 'Nº Endereço')
        ->setCellValue('H1', 'Data de Nascimento')
        ->setCellValue('I1', 'Complemento Endereço')
        ->setCellValue('J1', 'Telefone')
        ->setCellValue('K1', 'Extra1');


$c = 2;
$cc = 1;
$no_old = NULL;
$campos = "p.n_pessoa, p.rg, p.sexo, e.bairro, e.cep, e.logradouro, e.num, p.dt_nasc, e.complemento, p.tel1, i.n_inst ";
$sql = "SELECT $campos FROM ge_turma_aluno ta 
JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa 
JOIN ge_turmas t on t.id_turma = ta.fk_id_turma 
JOIN instancia i on i.id_inst = t.fk_id_inst 
LEFT JOIN endereco e ON e.fk_id_pessoa = p.id_pessoa 
WHERE i.id_inst IN(100,109)  
AND t.fk_id_pl = 24";
$query = pdoSis::getInstance()->query($sql);
$col_q = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($col_q as $col_f) {
    $objPHPExcel->getActiveSheet()->getStyle('A' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('B' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('C' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('D' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('E' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('F' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('G' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('H' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('I' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('J' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('K' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

// Add some data
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $c, $col_f['n_pessoa'])
            ->setCellValue('B' . $c, $col_f['rg'])
            ->setCellValue('C' . $c, $col_f['sexo'])
            ->setCellValue('D' . $c, $col_f['bairro'])
            ->setCellValue('E' . $c, $col_f['cep'])
            ->setCellValue('F' . $c, $col_f['logradouro'])
            ->setCellValue('G' . $c, $col_f['num'])
            ->setCellValue('H' . $c, $col_f['dt_nasc'])
            ->setCellValue('I' . $c, $col_f['complemento'])
            ->setCellValue('j' . $c, $col_f['tel1'])
            ->setCellValue('K' . $c, $col_f['n_inst']);
    $c++;
}
$objPHPExcel->getActiveSheet()->getStyle('A' . $c)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
$objPHPExcel->getActiveSheet()->getStyle('A1');
// Definir a largura da coluna A para automático/auto-ajustar
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('40');
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('15');
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('5');
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('30');
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('15');
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth('40');
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth('10');
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth('15');
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth('40');
$objPHPExcel->getActiveSheet()->getColumnDimension('j')->setWidth('15');
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth('40');



// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Alunos');


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
