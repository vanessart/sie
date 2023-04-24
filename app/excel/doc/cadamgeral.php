<?php
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

use PHPExcel_Style_Border;

require_once 'pdoSis.php';
if (empty($_POST['mes'])) {
    echo 'Mês não indentificado';
} else {
    $mes = $_POST['mes'];
    if (empty($_POST['ano'])) {
        $ano = date("Y");
    } else {
        $ano = $_POST['ano'];
    }
    $sql = " SELECT * FROM `cadam_valores` ";

    $query = pdoSis::getInstance()->query($sql);
    $valores = $query->fetch(PDO::FETCH_ASSOC);


    $fields = " `fk_id_cad`, f.`dia`, cad_pmb, f.horas, p.n_pessoa, p.cpf ";
    $sql = "SELECT "
            . " $fields "
            . " FROM cadam_freq f "
            . " JOIN cadam_cadastro c on c.id_cad = f.fk_id_cad "
            . " join pessoa p on p.id_pessoa = c.fk_id_pessoa "
            . " WHERE `mes` = " . $mes
            . " AND `ano` =  " . $ano;
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($array as $v) {
        @$tt[$v['fk_id_cad']]['nome'] = $v['n_pessoa'];
        @$tt[$v['fk_id_cad']]['cpf'] = $v['cpf'];
        @$tt[$v['fk_id_cad']]['cad_pmb'] = $v['cad_pmb'];
        @$tt[$v['fk_id_cad']]['dias'][$v['dia']]=1;
        @$tt[$v['fk_id_cad']]['horas'] += $v['horas'];
    }

    $sql = "SELECT "
            . " $fields "
            . " FROM cadam_freq_tea f "
            . " JOIN cadam_cadastro c on c.id_cad = f.fk_id_cad "
            . " join pessoa p on p.id_pessoa = c.fk_id_pessoa "
            . " WHERE `mes` = " . $mes
            . " AND `ano` =  " . $ano;

    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($array as $k => $v) {
        @$tt[$v['fk_id_cad']]['nome'] = $v['n_pessoa'];
        @$tt[$v['fk_id_cad']]['cpf'] = $v['cpf'];
        @$tt[$v['fk_id_cad']]['cad_pmb'] = $v['cad_pmb'];
        @$tt[$v['fk_id_cad']]['dias'][$v['dia']]=1;
        @$tt[$v['fk_id_cad']]['horas'] += $v['horas'];
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

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth("10");
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth("40");
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth("20");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Matrícula');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Nome');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'CPF');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Dias');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Horas');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'V. Dias');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'V. Horas');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Bruto');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', '11%');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Liquido');
        $c = 2;
        foreach ($tt as $v) {
            $vdia = (count($v['dias'] )* str_replace(',', '.', $valores['dia'])) ;
            $vhora =($v['horas'] * str_replace(',', '.', $valores['hora'])) ;
            $vtotal = $vdia + $vhora;
            $porc = $vtotal * 0.11;
            $liqu = ($vtotal - $porc) ;
            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $c, $v['cad_pmb']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $c, $v['nome']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $c, $v['cpf']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $c, count($v['dias']));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $c, $v['horas']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $c, number_format($vdia, 2, ',', ' '));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $c, number_format($vhora, 2, ',', ' '));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $c, number_format($vtotal, 2, ',', ' '));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $c, number_format($porc, 2, ',', ' '));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $c, number_format($liqu, 2, ',', ' '));
            $c++;
        }
        //   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col1 . $c, $va1);
// Colocar uma borda em torno da área A1:A5
//$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Relatorio_dia_' . $mes);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . 'Relatorio_dia_' . $mes . '.xls"');
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
