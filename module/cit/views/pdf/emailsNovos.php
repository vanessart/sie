<?php
if (!defined('ABSPATH'))
    exit;
ob_clean();
$nome_arquivo = filter_input(INPUT_POST, 'nome_arquivo', FILTER_SANITIZE_NUMBER_INT);
if ($nome_arquivo) {
    $sql = "SELECT campo1 AS 'First Name [Required]', campo2 AS 'Last Name [Required]', "
            . " campo3 AS 'Email Address [Required]', campo4 AS 'Password [Required]', "
            . " campo5 AS 'Password Hash Function [UPLOAD ONLY]', campo6 AS 'Org Unit Path [Required]', "
            . " campo7 AS 'New Primary Email [UPLOAD ONLY]', campo8 AS 'Recovery Email', "
            . " campo9 AS 'Home Secondary Email', campo10 AS 'Work Secondary Email', "
            . " campo11 AS 'Recovery Phone [MUST BE IN THE E.164 FORMAT]', "
            . " campo12 AS 'Work Phone', campo13 AS 'Home Phone', campo14 AS 'Mobile Phone', "
            . " campo15 AS 'Work Address', campo16 AS 'Home Address', campo17 AS 'Employee ID', "
            . " campo18 AS 'Employee Type', campo19 AS 'Employee Title', campo20 AS 'Manager Email', "
            . " campo21 AS 'Department', campo22 AS 'Cost Center', campo23 AS 'Cost Center', "
            . " campo24 AS 'Floor Name', campo25 AS 'Floor Section', "
            . " campo26 AS 'Change Password at Next Sign-In', campo27 AS 'New Status [UPLOAD ONLY]', "
            . " campo28 AS 'Advanced Protection Program enrollment' FROM ge_controle_email"
            . " WHERE status = '" . 'Pendente' . "' AND nome_arquivo = '" . $nome_arquivo . "'";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    $nomearquivo = 'emails_'.$nome_arquivo;
    @$nomearquivo = preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), end(explode(' ', $nomearquivo)));
    ;
    if (!empty($array)) {




        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');

        /** Include PHPExcel */
        require_once ABSPATH . '/app/excel/Classes/PHPExcel.php';


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
} else {
    echo 'Algo Errado Não Está Certo :(';
}

