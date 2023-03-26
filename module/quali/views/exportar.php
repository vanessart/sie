<?php
//use PHPExcel_Style_Border;


$var = filter_input(INPUT_POST, 'var');
$var = empty($var) ? tool::id_inst() : $var;

$nomearquivo = 'pre-inscricao';
$situacao = filter_input(INPUT_POST, 'situacao', FILTER_SANITIZE_STRING);
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_NUMBER_INT);
$curso = filter_input(INPUT_POST, 'curso', FILTER_SANITIZE_NUMBER_INT);
$opt = filter_input(INPUT_POST, 'opt', FILTER_SANITIZE_NUMBER_INT);
$formulario = filter_input(INPUT_POST, 'formulario', FILTER_SANITIZE_STRING);
if (!empty($nome)) {
    $nome_ = " and (cpf = '$nome' or nome like '%$nome%') ";
} else {
    $nome_ = null;
}
if (!empty($situacao)) {
    if ($situacao == 'X') {
        $situacao_ = " and situacao is null ";
    } else {
        $situacao_ = " and situacao = $situacao ";
    }
} else {
    $situacao_ = null;
}
if (!empty($curso)) {
    if ($opt == 2) {
        $id_cur = 'fk_id_cur_2';
    } else {
        $id_cur = 'fk_id_cur';
    }
    $curso_ = " and $id_cur = $curso ";
} else {
    $curso_ = null;
}
$fields = "i.id_inscr, i.nome, i.cpf, i.rg, i.dt_nasc, "
        . "se.n_se as situacao_emprego,  "
        . "sf.n_sf as  situacao_amilar,  "
        . "esc.n_esc as  escolaridade,  "
        . ""
        . " c.n_cur as curso, c2.n_cur as curso_2_opcao, situacao "
        . " `sexo`, `email` as 'e-mail', `logradouro`, `num`, "
        . "`complemento`,`bairro`,`cidade`,`uf`,`cep`,`fases_ant`,"
        . "`conheceu`,`celular`,`whatsapp`,`recado`,`aluno` as ja_e_aluno,`situacao` ";
$sql = "SELECT $fields FROM `quali_incritos_$formulario` i "
        . " join gt_curso c on c.id_cur = i.fk_id_cur "
        . " left join gt_curso c2 on c2.id_cur = i.fk_id_cur_2 "
        . " left join sit_emprego se on se.id_se = i.fk_id_se "
        . " left join sit_familia sf on sf.id_sf = i.fk_id_sf "
        . " left join escolaridade esc on esc.id_esc = i.fk_id_esc "
        . " where 1 "
        . $nome_
        . $situacao_
        . $curso_;

$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
$sitSet = [null => 'Aguardando Deferimento', 1 => 'Deferido', 2 => 'Indeferido'];
foreach ($array as $k => $v) {
    $array[$k]['situacao'] = $sitSet[$v['situacao']];
    $array[$k]['whatsapp'] = $v['whatsapp'] == 1 ? 'Sim' : 'Não';
    $array[$k]['ja_e_aluno'] = $v['ja_e_aluno'] == 1 ? 'Sim' : 'Não';
}
if (!empty($array)) {




    if (PHP_SAPI == 'cli')
        die('This example should only be run from a Web Browser');

    /** Include PHPExcel */
    require_once ABSPATH . '/app/excel/Classes/PHPExcel.php';

// Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

// Set document properties
    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy(NOME1)
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
