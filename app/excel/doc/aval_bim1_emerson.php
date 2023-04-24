<?php
ini_set('memory_limit', '2000M');
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

use PHPExcel_Style_Border;

require_once 'pdoSis.php';

$sql = "SELECT perc,  ciclos, fk_id_disc from global_aval "
        . " WHERE `fk_id_agrup` = 4 "        
    . " and ciclos in (1,2,3,4,5,6,7,8,9) "
     . " and fk_id_disc in (6,9) ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($array as $v) {
    $hab[$v['ciclos']][$v['fk_id_disc']] = unserialize($v['perc']);
}


$sql = "SELECT   perc, i.n_inst, ga.ciclos, gt.letra, ga.fk_id_disc, gr.* FROM global_respostas gr "
 . " LEFT JOIN global_aval ga on ga.id_gl = gr.fk_id_gl "
 . " left join ge_turmas gt on gt.id_turma = gr.fk_id_turma "        
 . " left join instancia i on i.id_inst = gr.fk_id_inst "
 . " WHERE `fk_id_agrup` = 4 "
//. "  AND `fk_id_inst` = $id_inst "
// . " and ciclos = $id_ciclo "
 . " and fk_id_disc in (6,9) "
 . " and ciclos in (1,2,3,4,5,6,7,8,9) "
 . " and i.n_inst is not null "
 . " order by n_inst, fk_id_disc, ciclos, letra, perc";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($array as $f) {
    $hab1[$f['ciclos']][$f['fk_id_disc']][$f['letra']] = unserialize($f['perc']);
}

foreach ($array as $v) {
    foreach ($v as $kk => $vv) {
        if (substr($kk, 0, 1) == 'q' && !empty($vv) && $kk != 'quest') {
            $num = intval(substr($kk, 1));
            if ($vv == 1) {
                @$dado[$v['n_inst']][$v['fk_id_disc']][$v['ciclos']][$v['letra']][$num] ++;
            }
            @$total[$v['n_inst']][$v['fk_id_disc']][$v['ciclos']][$v['letra']][$num] ++;
        }
    }
}

ksort($dado);



if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("Apoio")
        ->setTitle("arquivo")
        ->setSubject("")
        ->setDescription("")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("");


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(250);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . '1', 'Escola');

$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(150);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . '1', 'Disciplina');

$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(5);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . '1', 'Ano');

$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(5);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . '1', 'Turma');

$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(5);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . '1', 'Hab');

$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(100);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . '1', 'Hab. Desc');

$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(50);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . '1', '%');

$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(50);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . '1', 'QTD_G1');

$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(50);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . '1', 'QTD_ANO_TURMA_ESCOLA_DISC');

$disciplinas = [6 => 'Matemática', 9 => 'Português'];
$cont = 2;
foreach ($dado as $k => $v) {
    foreach ($v as $kk => $vv) {
        foreach ($vv as $kkk => $vvv) {
            foreach ($vvv as $kkkk => $vvvv) {
                foreach ($vvvv as $kkkkk => $vvvvv) {

                    $totalEsc = $total[$k][$kk][$kkk][$kkkk][$kkkkk];
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $cont, $k);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $cont, $disciplinas[$kk]);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $cont, $kkk);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $cont, $kkkk);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $cont, $kkkkk);
                    @$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $cont, $hab1[$kkk][$kk][$kkkk][$kkkkk]);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $cont, (($vvvvv / $totalEsc) * 100));
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $cont, $vvvvv);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $cont, $totalEsc);
                    $cont++;
                }
            }
        }
    }
}



// Colocar uma borda em torno da área A1:A5
//$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('arquivo');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . 'aval_bim_1' . '_' . date("Y_m_d") . '.xls"');
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

