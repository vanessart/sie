<?php
if (!defined('ABSPATH'))
    exit;
ini_set('memory_limit', '20000M');
if (toolErp::id_nilvel() == 55) {
    $id_instSet = toolErp::id_inst();
    $instSql = " and t.fk_id_inst = $id_instSet ";
    $escolas = sql::idNome('instancia', ['id_inst' => $id_instSet]);
} elseif (toolErp::id_nilvel() != 54) {
    exit();
} else {
    $id_instSet = null;
    $instSql = null;
    $escolas = ng_escolas::idEscolas([1]);
}
$disc = sql::idNome('ge_disciplinas');
$id_pl = ng_main::periodoSet();
$bim = sql::get('ge_cursos', 'atual_letiva', ['id_curso' => 1], 'fetch')['atual_letiva'];
foreach (range(1, $bim) as $v) {
    $let[] = $v;
}
$letivas = implode(', ', $let);
$sql = "SELECT SUM(dias) tdias FROM `sed_letiva_data` WHERE fk_id_pl = $id_pl AND atual_letiva in ($letivas) AND fk_id_curso = 1 ";
$query = pdoSis::getInstance()->query($sql);
$tdias = $query->fetch(PDO::FETCH_ASSOC)['tdias'];
$podeFaltar = $tdias / 4;
$sql = "SELECT p.n_pessoa, t.id_turma, t.n_turma,  a.fk_id_ciclo, a.fk_id_pessoa, a.atual_letiva, ci.n_ciclo, t.fk_id_inst, "
        . " SUM(a.falta_nc) faltas "
        . " FROM hab.aval_mf_1_$id_pl a "
        . " join pessoa p on p.id_pessoa = a.fk_id_pessoa "
        . " join ge_turma_aluno ta on ta.fk_id_turma = a.fk_id_turma and a.fk_id_pessoa = ta.fk_id_pessoa and ta.fk_id_tas = 0 "
        . " JOIN ge2.ge_turmas t on t.id_turma = a.fk_id_turma and a.fk_id_ciclo in (1, 2, 3, 4, 5) $instSql "
        . " JOIN ge2.ge_ciclos ci on ci.id_ciclo = a.fk_id_ciclo "
        . " GROUP by t.id_turma, a.fk_id_ciclo, a.fk_id_pessoa "
        . " HAVING faltas > $podeFaltar ";
$query = pdoSis::getInstance()->query($sql);
$peb1 = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT fk_id_disc, aulas FROM `ge_aloca_disc` WHERE `fk_id_grade` = 1 ORDER BY `ge_aloca_disc`.`fk_id_disc` ASC ";
$query = pdoSis::getInstance()->query($sql);
$array_ = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($array_ as $v) {
    if (empty($where)) {
        $or = 'HAVING';
        $where = 1;
    } else {
        $or = 'OR';
    }
    $campos[] = " SUM(a.falta_" . $v['fk_id_disc'] . ") faltas_" . $v['fk_id_disc'];
    $podeFaltarDisc[] = " $or faltas_" . $v['fk_id_disc'] . " > " . intval((($tdias / 5) * $v['aulas']) / 4);
}

$sql = "SELECT p.n_pessoa, t.id_turma, t.n_turma, a.fk_id_ciclo, a.fk_id_pessoa, ci.n_ciclo, t.fk_id_inst, "
        . implode(', ', $campos)
        . " FROM hab.aval_mf_1_$id_pl a "
        . " join ge_turma_aluno ta on ta.fk_id_turma = a.fk_id_turma and a.fk_id_pessoa = ta.fk_id_pessoa and ta.fk_id_tas = 0 "
        . " join pessoa p on p.id_pessoa = a.fk_id_pessoa "
        . " JOIN ge2.ge_turmas t on t.id_turma = a.fk_id_turma and a.fk_id_ciclo in (6, 7, 8, 9) $instSql "
        . " JOIN ge2.ge_ciclos ci on ci.id_ciclo = a.fk_id_ciclo "
        . " GROUP by t.id_turma, a.fk_id_ciclo, a.fk_id_pessoa ";
$sql .= implode('', $podeFaltarDisc);
$query = pdoSis::getInstance()->query($sql);

$peb2 = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($peb2 as $v) {
    $df = '';
    foreach ($array_ as $y) {
        if (!empty($v['faltas_' . $y['fk_id_disc']])) {
            if ($v['faltas_' . $y['fk_id_disc']] > intval((($tdias / 5) * $y['aulas']) / 4)) {
                $df .= $v['faltas_' . $y['fk_id_disc']]. ' em '.($disc[$y['fk_id_disc']]).';';
            }
        }
    }

    $array[] = [
        'Escola' => $escolas[$v['fk_id_inst']],
        'Turma' => $v['n_turma'],
        'Nome' => $v['n_pessoa'],
        'RSE' => $v['fk_id_pessoa'],
        'faltas'=> $df
    ];
}

foreach ($peb1 as $v) {

        $array[] = [
        'Escola' => $escolas[$v['fk_id_inst']],
        'Turma' => $v['n_turma'],
        'Nome' => $v['n_pessoa'],
        'RSE' => $v['fk_id_pessoa'],
        'faltas'=> $v['faltas']. ' em Núcleo Comum '
    ];
}
if (!empty($array)) {

$nomearquivo = 'Faltas';



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

               
