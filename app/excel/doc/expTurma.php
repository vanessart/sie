<?php
ini_set('memory_limit', '20000M');
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

use PHPExcel_Style_Border;

require_once 'pdoSis.php';
 $sql = "SELECT "
        . "t.id_turma, t.n_turma, t.fk_id_inst FROM ge_turmas t"
        . " JOIN ge_periodo_letivo = pl ON pl.id_pl = t.fk_id_pl"
        . " WHERE pl.at_pl = 1 ORDER BY t.fk_id_inst, t.fk_id_ciclo, t.letra";

    @$nomearquivo = 'turmas_';

    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);

if (!empty($array)) {

    foreach ($array as $k => $v){
        if(!empty($v['Nasc'])){
            $array[$k]['Nasc']= substr($v['Nasc'], 8,2).'/'.substr($v['Nasc'], 5,2).'/'.substr($v['Nasc'], 0,4);
        }
    }


    if (PHP_SAPI == 'cli')
        die('This example should only be run from a Web Browser');

    /** Include PHPExcel */
    require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';


// Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

// Set document properties
    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("dttie")
            ->setTitle("arquivo")
            ->setSubject("")
            ->setDescription("")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("");
    $colu = 'A';
    foreach ($array[0] as $k => $v) {
        $objPHPExcel->getActiveSheet()->getColumnDimension($colu)->setAutoSize();

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colu . '1', $k);
        $colu++;
    }

    $c = 2;
    foreach ($array as $key => $va) {
        $col1 = 'A';
        foreach ($array[$key] as $key1 => $va1) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col1 . $c, $va1);
            $col1++;
        }

        $c++;
    }


// Colocar uma borda em torno da área A1:A5
//$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
// Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle($nomearquivo);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $nomearquivo . '_' . date("Y_m_d") . '.xls"');
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
} else {
    ?>
    <div>
        Não Existem Dados referente a esta consulta.
    </div>
    <?php
}
