<?php

extract($_POST);

use PHPExcel_Style_Border;

require_once '../../Conexao.php';
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Sao_Paulo');


   function auditoria() {




//status errado
        $sql = "select * from mrv_beneficiado "
                . "where status_ben not in ('Deferido', 'Indeferido') "
                . "order by 	n_escola_ben";
        $query = Conexao::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($array as $k => $v){
        
        $pendencia[$v['id_fk_Aluno']]['n_aluno']=$v['n_aluno'];
        $pendencia[$v['id_fk_Aluno']]['pend']=$v['status_ben'];
        $pendencia[$v['id_fk_Aluno']]['escola']=$v['n_escola_ben'];
        $pendencia[$v['id_fk_Aluno']]['turma']=$v['turma_ben'];
        
        }
        
        //não morador deferido
                $sql = "select * from mrv_beneficiado "
                . "where status_ben = 'Deferido' "
                        . "and cep not like '064%' "
                . "order by 	n_escola_ben";
        $query = Conexao::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        
            foreach ($array as $k => $v){
        
        $pendencia[$v['id_fk_Aluno'].'e']['n_aluno']=$v['n_aluno'];
        $pendencia[$v['id_fk_Aluno'].'e']['pend']='Deferido e nao é Morador de Barueri';
        $pendencia[$v['id_fk_Aluno'].'e']['escola']=$v['n_escola_ben'];
        $pendencia[$v['id_fk_Aluno'].'e']['turma']=$v['turma_ben'];
        
        }    

                //nota baixa
                $sql = "select * from mrv_beneficiado "
                . "where status_ben = 'Deferido' "
                        . "and media_final_bem < 6.5 "
                . "order by 	n_escola_ben";
        $query = Conexao::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        
            foreach ($array as $k => $v){
        
        $pendencia[$v['id_fk_Aluno'].'e']['n_aluno']=$v['n_aluno'];
        $pendencia[$v['id_fk_Aluno'].'e']['pend']='Deferido e a Media Final é inferior a 6,5';
        $pendencia[$v['id_fk_Aluno'].'e']['escola']=$v['n_escola_ben'];
        $pendencia[$v['id_fk_Aluno'].'e']['turma']=$v['turma_ben'];
        
        } 
        
        return $pendencia;
    }
$auditoria = auditoria();

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("dttie")
        ->setTitle("Auditoria")
        ->setSubject("")
        ->setDescription("")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("");

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B2', 'Auditoria');

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A4', 'Alunos com Situação Irregular')
        ->setCellValue('B4', 'Pendencia')
        ->setCellValue('C4', 'Escola')
        ->setCellValue('D4', 'Turma');

// Colocar uma borda em torno da área A1:A5
//$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
$objPHPExcel->getActiveSheet()->getStyle('A4:D4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$c = 5;
$cc = 4;


foreach ($auditoria as $col_f) {
    $objPHPExcel->getActiveSheet()->getStyle('A' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('B' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('C' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('D' . $c)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

// Add some data
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $c, $col_f['n_aluno'])
            ->setCellValue('B' . $c, $col_f['pend'])
            ->setCellValue('C' . $c, $col_f['escola'])
            ->setCellValue('D' . $c, $col_f['turma']);
    $c++;
}
$objPHPExcel->getActiveSheet()->getStyle('A' . $c)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
$objPHPExcel->getActiveSheet()->getStyle('A1');
// Definir a largura da coluna A para automático/auto-ajustar
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('50');
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('40');
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('50');
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('20');



// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Auditoria');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Auditoria-' . date("Y-m-d") . '.xls"');
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
