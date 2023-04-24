<?php
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

use PHPExcel_Style_Border;

require_once 'pdoSis.php';
if (empty($_POST['mes'])) {
    echo 'Mês não indentificado';
} elseif (empty($_POST['evento'])) {
    echo 'Inclua um evento';
} else {
    $mes = $_POST['mes'];
    $evento = $_POST['evento'];
    if (empty($_POST['ano'])) {
        $ano = date("Y");
    } else {
        $ano = $_POST['ano'];
    }

    $sql = "SELECT "
            . " `fk_id_cad`, f.`dia`, cad_pmb "
            . " FROM cadam_freq f "
            . " JOIN cadam_cadastro c on c.id_cad = f.fk_id_cad "
            . " WHERE `mes` = " . $mes
            . " AND `ano` =  " . $ano;
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($array as $v) {
        @$tt[$v['fk_id_cad']]['cad_pmb'] = $v['cad_pmb'];
        @$tt[$v['fk_id_cad']]['tt'] ++;
    }

 $sql = "SELECT "
            . " `fk_id_cad`, f.`dia`, cad_pmb "
            . " FROM cadam_freq_tea f "
            . " JOIN cadam_cadastro c on c.id_cad = f.fk_id_cad "
            . " WHERE `mes` = " . $mes
            . " AND `ano` =  " . $ano;

    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($array as $k => $v) {
        @$tt[$v['fk_id_cad']]['cad_pmb'] = $v['cad_pmb'];
         @$tt[$v['fk_id_cad']]['tt'] ++;
    }

    if (!empty($tt)) {

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
        //  $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize();
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ANO/MESINICIO');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'ANO/MESFINAL');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'MATRICULA');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'EVENTO');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'QTDE DIAS');
        $c = 2;
        foreach ($tt as $v) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $c, $ano . $mes);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $c, $ano . $mes);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $c, $v['cad_pmb']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $c, $evento);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $c, $v['tt']);
            $c++;
        }
        //   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col1 . $c, $va1);
// Colocar uma borda em torno da área A1:A5
//$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Relatorio_dia_'.$mes);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . 'Relatorio_dia_'.$mes.'.xls"');
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
}
