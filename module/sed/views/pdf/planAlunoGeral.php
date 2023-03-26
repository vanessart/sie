<?php
if (!defined('ABSPATH'))
    exit;

$escola = sqlErp::get('instancia', 'n_inst', ['id_inst'=> toolErp::id_inst()], 'fetch')['n_inst'];

 $sql = "SELECT"
 . " p.id_pessoa AS RSE, p.ra AS RA, ra_dig AS Digito_RA, ta.chamada AS Chamada, p.n_pessoa AS Nome_Aluno,"
 . " p.sus AS SUS, t.codigo AS Cod_Classe, p.rg AS RG, e.logradouro_gdae AS Endereço, "
 . " e.num_gdae AS NR, e.complemento AS Compl, e.cep AS CEP, e.bairro AS Bairro, e.cidade AS Cidade,"
 . " GROUP_CONCAT(tel.num) AS `Telefone(s)`, "
 . " p.emailgoogle AS Email, p.mae AS Mãe, p.pai AS Pai, p.cidade_nasc AS Cid_Nasc,"
 . " p.sexo AS Sexo, p.dt_nasc AS Dt_Nasc, p.emailgoogle AS Email_Google FROM pessoa p"
 . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa"
 . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma and fk_id_tas = 0 "
 . " JOIN telefones tel on tel.fk_id_pessoa = ta.fk_id_pessoa "
 . " LEFT JOIN endereco e on e.fk_id_pessoa = p.id_pessoa"
 . " JOIN ge_periodo_letivo le on le.id_pl = t.fk_id_pl AND le.at_pl = 1"
 . " Where t.fk_id_inst=" . toolErp::id_inst() . " "
 . " GROUP BY ta.fk_id_pessoa"
 . " ORDER BY t.codigo, ta.chamada ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);

ob_clean();

$nomearquivo = $escola;
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