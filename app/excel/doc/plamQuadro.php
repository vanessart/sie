<?php
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);
ini_set('memory_limit', '200M');
require_once 'pdoSis.php';

$dados = pegaquadroescola();
$escola = pegaescola();
$total = pegatotalstatus();
$st2 = [
    'e' => 'Escola',
    0 => 'Indefinido',
    1 => 'Regular',
    2 => 'Em_Manutencao',
    3 => 'Emprestado',
    6 => 'Nao_Alocado',
    8 => 'Foi_dado_Baixa',
    't' => 'Total'
];
foreach ($escola as $k => $v) {
    $linha = [];
    $linha['Escola'] = $v;
    $conta[$k] = 0;
    foreach ($st2 as $k2 => $v2) {
        if (is_numeric($k2)) {
            $linha[$v2] = (string) $dados[$k][$k2];
            $conta[$k] = $conta[$k] + $dados[$k][$k2];
        }
    }
    if (!empty($conta[$k])) {
        $linha['Total'] = (string)$conta[$k];
    } else {
        $linha['Total']='x';
    }
    $array[] = $linha;
}

$nomearquivo = 'Arquivo';


if (!empty($array)) {




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

function pegaquadroescola() {
    //cria as variaveis
    $sql = "SELECT DISTINCT lc.fk_id_inst  FROM lab_chrome lc";
    $query = pdoSis::getInstance()->query($sql);
    $escola = $query->fetchAll(PDO::FETCH_ASSOC);

    $st = [
        0 => 'Indefinido',
        1 => 'Regular',
        2 => 'Em Manutenção',
        3 => 'Emprestado',
        4 => 'Quebrado (enviado para manutenção)',
        6 => 'Não Alocado',
        8 => 'Foi dado Baixa',
    ];

    foreach ($escola as $v) {
        foreach ($st as $k => $w) {
            $dados[$v['fk_id_inst']][$k] = 0;
        }
    }

    $sql2 = "SELECT lc.fk_id_inst, lc.fk_id_cs,"
            . " COUNT(lc.fk_id_cs) AS Total FROM lab_chrome lc"
            . " GROUP BY lc.fk_id_inst, lc.fk_id_cs";

    $query = pdoSis::getInstance()->query($sql2);
    $d = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($d as $w) {
        if ($w['fk_id_cs'] == 4) {
            $dados[$w['fk_id_inst']][2] = $dados[$w['fk_id_inst']][2] + $w['Total'];
        } else {
            $dados[$w['fk_id_inst']][$w['fk_id_cs']] = $dados[$w['fk_id_inst']][$w['fk_id_cs']] + $w['Total'];
        }
    }

    return $dados;
}

function pegaescola() {
    $sql = "SELECT DISTINCT i.id_inst, i.n_inst FROM lab_chrome lc"
            . " JOIN instancia i ON i.id_inst = lc.fk_id_inst"
            . " ORDER BY i.n_inst";

    $query = pdoSis::getInstance()->query($sql);
    $e = $query->fetchAll(PDO::FETCH_ASSOC);

    //$es[NULL] = 'NULL';

    foreach ($e as $v) {
        $es[$v['id_inst']] = $v['n_inst'];
    }

    unset($es[1]);

    return $es;
}

function pegatotalstatus() {
    $st = [
        0 => 'Indefinido',
        1 => 'Regular',
        2 => 'Em Manutenção',
        3 => 'Emprestado',
        // 4 => 'Quebrado (enviado para manutenção)',
        6 => 'Não Alocado',
        8 => 'Foi dado Baixa',
    ];

    foreach ($st as $k => $v) {
        $s[$k] = 0;
    }

    $sql = "SELECT lc.fk_id_cs, COUNT(lc.fk_id_cs) AS Total FROM lab_chrome lc"
            . " GROUP BY lc.fk_id_cs, lc.fk_id_inst"
            . " HAVING lc.fk_id_inst != 1 OR lc.fk_id_inst IS NOT NULL";

    $query = pdoSis::getInstance()->query($sql);
    $status = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($status as $k => $v) {
        if ($v['fk_id_cs'] == 4) {
            $s[2] = $s[2] + $v['Total'];
        } else {
            $s[$v['fk_id_cs']] = $s[$v['fk_id_cs']] + $v['Total'];
        }
    }

    return $s;
}
