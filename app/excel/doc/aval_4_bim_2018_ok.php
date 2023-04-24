<?php
ini_set('memory_limit', '20000M');
ini_set('max_execution_time', 1500);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

function serialize_corrector($data){
    return preg_replace_callback('!s:(\d+):"(.*?)";!', function($m) { return 's:'.strlen($m[2]).':"'.$m[2].'";'; }, $data);
}

use PHPExcel_Style_Border;

require_once 'pdoSis.php';

$sql =    " select perc, ciclos, fk_id_disc from global_aval "
        . " where fk_id_agrup in ('16','17','18') " // 4º Bimestre 2018
        . " and id_gl not in ('183') "
        . " and ciclos in (1,2,3,4,5,6,7,8,9) ";

$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($array as $v) {
    $hab[$v['ciclos']][$v['fk_id_disc']] = unserialize(serialize_corrector($v['perc']));
}


$sql =    " select ga.perc, i.n_inst, ga.ciclos, gt.letra, ga.fk_id_disc, gr.* from global_respostas gr "
        . " left join ge.global_aval as ga on ga.id_gl = gr.fk_id_gl "
        . " left join ge.ge_turmas as gt on gt.id_turma = gr.fk_id_turma "
        . " left join ge.instancia as i on i.id_inst = gr.fk_id_inst "
        . " where ga.fk_id_agrup in ('16','17','18') " // 4º Bimestre 2018
        //. " and ga.id_gl not in ('183','184','185') "
        . " and ga.ciclos in (1,2,3,4,5,6,7,8,9) "
        . " and i.n_inst is not null "
        . " order by i.n_inst, ga.fk_id_disc, ga.ciclos, gt.letra, ga.perc ";

$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($array as $f) {
    $hab1[$f['ciclos']][$f['fk_id_disc']][$f['letra']] = unserialize(serialize_corrector($f['perc']));
}

// $kk => é a variável que acumula as questões (q01, q02,q03,...)
// $vv => é a variável que acumula as respostas (1,2,3,4) para cada questão
// $num => é a variável que isola o número da questão (1,2 3 ...)

foreach ($array as $v) {
    foreach ($v as $kk => $vv) {
        if (substr($kk, 0, 1) == 'q' && !empty($vv) && $kk != 'quest') {
            $num = intval(substr($kk, 1));

            @$dado[$v['n_inst']][$v['fk_id_disc']][$v['ciclos']][$v['letra']][$num][$vv] ++;
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
$objPHPExcel->getProperties()->setCreator("Emerson Ferreira")
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

$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(5);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . '1', 'QTD_G1');

$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(5);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . '1', '%_G1');

$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(5);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . '1', 'QTD_D2');

$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(5);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . '1', '%_D2');

$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(5);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . '1', 'QTD_D3');

$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(5);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . '1', '%_D3');

$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(5);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . '1', 'QTD_D4');

$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(5);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . '1', '%_D4');

$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(5);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . '1', 'QTD_D5');

$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(5);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . '1', '%_D5');

$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(5);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . '1', 'QTD_ANO_TURMA_ESCOLA_DISC');

$disciplinas = [6 => 'Matemática', 
                9 => 'Português', 
               10 => 'Arte', 
               11 => 'Educação Física', 
               12 => 'Ciências Naturais', 
               13 => 'História', 
               14 => 'Geografia', 
               15 => 'L.E.Inglês',
               16 => 'I.Filosofia', 
               17 => 'Música', 
               18 => 'Ativ. Musicais', 
               19 => 'Ativ. Exp. Corporal',
               20 => 'Ativ. Informática', 
               21 => 'Educ. Reflexão', 
               22 => 'L.E.Espanhol', 
               25 => 'Orient. Práticas Operatórias',
               23 => 'Orient. Estudos', 
               24 => 'Orient. Leitura e Escrita', 
               26 => 'Libras',
               27 => 'Infantil', 
               28 => 'AEE', 
               29 => 'Produção de Texto',
               99 => 'Língua Portuguesa e Matemática'];
$cont = 2;

foreach ($dado as $k => $v) {
    foreach ($v as $kk => $vv) {
        foreach ($vv as $kkk => $vvv) {
            foreach ($vvv as $kkkk => $vvvv) {
                foreach ($vvvv as $kkkkk => $vvvvv) {
                    $totalEsc = $total[$k][$kk][$kkk][$kkkk][$kkkkk];
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $cont, $k);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . @$cont, @$disciplinas[@$kk]);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $cont, $kkk);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $cont, $kkkk);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $cont, $kkkkk);
                    @$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $cont, $hab1[$kkk][$kk][$kkkk][$kkkkk]);

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $cont, intval(@$vvvvv[1]));
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $cont, ((@$vvvvv[1] / $totalEsc) * 100));

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $cont, intval(@$vvvvv[2]));
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $cont, ((@$vvvvv[2] / $totalEsc) * 100));

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $cont, intval(@$vvvvv[3]));
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $cont, ((@$vvvvv[3] / $totalEsc) * 100));

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $cont, intval(@$vvvvv[4]));
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $cont, ((@$vvvvv[4] / $totalEsc) * 100));

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $cont, intval(@$vvvvv[5]));
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $cont, ((@$vvvvv[5] / $totalEsc) * 100));
                    
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . $cont, $totalEsc);
                    $cont++;
                }
            }
        }
    }
}

// Colocar uma borda em torno da área A1:A5
//$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('ejf_2018_4_bim');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . 'ejf_2018_4_bim' . '.xls"');
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