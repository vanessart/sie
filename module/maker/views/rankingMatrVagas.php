<?php
ob_clean();
$set = sql::get('maker_setup', '*', ['id_setup' => 1], 'fetch');
$id_pl = $set['fk_id_pl_matr'];
$sql = "SELECT po.n_polo, po.fk_id_inst_maker, t.periodo FROM maker_polo po JOIN maker_gt_turma t on t.fk_id_inst = po.fk_id_inst_maker AND t.fk_id_pl = $id_pl AND t.fk_id_ciclo = 1 order by po.n_polo";
$query = pdoSis::getInstance()->query($sql);
$pTm = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT t.fk_id_inst, t.periodo, COUNT(ta.fk_id_pessoa) ct FROM maker_gt_turma_aluno ta JOIN maker_gt_turma t on t.id_turma = ta.fk_id_turma AND t.fk_id_pl = $id_pl AND t.fk_id_ciclo = 1 GROUP BY t.fk_id_inst, t.periodo ";
$query = pdoSis::getInstance()->query($sql);
$a = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($a as $v) {
    $aluno[$v['fk_id_inst']][$v['periodo']] = $v['ct'];
}
foreach ($pTm as $v) {
    @$polos[$v['fk_id_inst_maker']]['nome'] = $v['n_polo'];
    @$polos[$v['fk_id_inst_maker']]['turmas'][$v['periodo']]++;
    @$polos[$v['fk_id_inst_maker']]['vagasTotal'] += 28;
    @$polos[$v['fk_id_inst_maker']]['vagas'][$v['periodo']] += 28;
    @$polos[$v['fk_id_inst_maker']]['alunos'][$v['periodo']] = @$aluno[$v['fk_id_inst_maker']][$v['periodo']];
}
foreach ($polos as $k => $v) {
@$t['Turmas Manhã'] +=@$v['turmas']['M'];
@$t['Turmas Tarde'] +=@$v['turmas']['T'];
@$t['Matriculados Manhã'] +=@$v['alunos']['M'];
@$t['Matriculados Tarde'] +=@$v['alunos']['T'];
@$t['Vagas Manhã'] +=@$v['vagas']['M'];
@$t['Vagas Tarde'] +=@$v['vagas']['T'];
@$t['Disponível Manhã'] +=(@$v['vagas']['M'] - @$v['alunos']['M']);
@$t['Disponível Tarde'] +=(@$v['vagas']['T'] - @$v['alunos']['T']);
@$t['Total de Turmas'] += (@$v['turmas']['T'] + @$v['turmas']['M']);
@$t['Total de Vagas'] +=@$v['vagasTotal'];
@$t['Total de Matriculados'] +=(@$aluno[$k]['T'] + @$aluno[$k]['M']);
@$t['Total Disponível'] +=($v['vagasTotal'] - (@$aluno[$k]['T'] + @$aluno[$k]['M']));


    $array[] = [
        'Polo' => $v['nome'],
        'Turmas Manhã' => (string) @$v['turmas']['M'],
        'Turmas Tarde' => (string) @$v['turmas']['T'],
        'Matriculados Manhã' => (string) @$v['alunos']['M'],
        'Matriculados Tarde' => (string) @$v['alunos']['T'],
        'Vagas Manhã' => (string) @$v['vagas']['M'],
        'Vagas Tarde' => (string) @$v['vagas']['T'],
        'Disponível Manhã' => (string) (@$v['vagas']['M'] - @$v['alunos']['M']),
        'Disponível Tarde' => (string) (@$v['vagas']['T'] - @$v['alunos']['T']),
        'Total de Turmas' => (string) (@$v['turmas']['T'] + @$v['turmas']['M']),
        'Total de Vagas' => (string) $v['vagasTotal'],
        'Total de Matriculados' => (string) (@$aluno[$k]['T'] + @$aluno[$k]['M']),
        'Total Disponível' => (string) ($v['vagasTotal'] - (@$aluno[$k]['T'] + @$aluno[$k]['M']))
    ];
}
    $array[] = [
        'Polo' => 'Total',
        'Turmas Manhã' => (string) @$t['Turmas Manhã'],
        'Turmas Tarde' =>  (string) @$t['Turmas Tarde'],
        'Matriculados Manhã' => (string) @$t['Matriculados Manhã'],
        'Matriculados Tarde' => (string) @$t['Matriculados Tarde'],
        'Vagas Manhã' => (string) @$t['Vagas Manhã'],
        'Vagas Tarde' => (string) @$t['Vagas Tarde'],
        'Disponível Manhã' => (string) @$t['Disponível Manhã'],
        'Disponível Tarde' => (string) @$t['Disponível Tarde'],
        'Total de Turmas' => (string) @$t['Total de Turmas'],
        'Total de Vagas' => (string) @$t['Total de Vagas'],
        'Total de Matriculados' => (string) @$t['Total de Matriculados'],
        'Total Disponível' => (string) @$t['Total Disponível']
    ];
$nomearquivo = 'Vagas';
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

               