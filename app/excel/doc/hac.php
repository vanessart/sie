<?php
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

function profDiscClasse($disc = NULL, $hac_dia = NULL, $hac_periodo = NULL, $id_inst = NULL, $id_ciclo = NULL) {
    $sql = "select * from ge_disciplinas order by n_disc";
    $query = pdoSis::getInstance()->query($sql);
    $disc_ = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($disc_ as $dc) {
        $n_disc[$dc['id_disc']] = $dc['n_disc'];
    }
    if (!empty($disc)) {
        $disc_i = " and (";
        foreach ($disc as $dd) {
            $disc_i .= " iddisc like '" . $dd . "' or ";
        }
        $disc_i = substr($disc_i, 0, -4) . ")";
    } else {
        $disc_i = NULL;
    }
    if (!empty($hac_dia)) {
        $hac_dia = " and hac_dia like '" . $hac_dia . "' ";
    } else {
        $hac_dia = NULL;
    }
    if (!empty($hac_periodo)) {
        $hac_periodo = " and hac_periodo like '" . $hac_periodo . "' ";
    } else {
        $hac_periodo = NULL;
    }
    if (!empty($id_inst)) {
        $id_inst = " and id_inst = '" . $id_inst . "' ";
    } else {
        $id_inst = NULL;
    }
    if (!empty($id_ciclo)) {
        $id_ciclo = " and fk_id_ciclo = '" . $id_ciclo . "' ";
    } else {
        $id_ciclo = NULL;
    }

    $sql = "SELECT n_inst as escola, pe.n_pessoa as Professor, pe.emailgoogle as email, p.rm as matricula, disciplinas, hac_dia, hac_periodo FROM ge_prof_esc p "
            . " join ge_aloca_prof ap on ap.rm = p.rm "
            . " join ge_escolas e on e.fk_id_inst = p.fk_id_inst "
            . " join instancia i on i.id_inst = p.fk_id_inst "
            . " join ge_turmas t on t.id_turma=ap.fk_id_turma "
                               . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
 . " join ge_funcionario f on f.rm = p.rm "
            . " join pessoa pe on pe.id_pessoa = f.fk_id_pessoa "
            . "where 1 "
            . " $disc_i "
            . " $hac_dia "
            . " $hac_periodo "
            . " $id_inst "
            . " $id_ciclo "
           . " and at_pl = 1 "
            . " order by Professor ";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($array)) {
        foreach ($array as $v) {
            $prof[$v['matricula']]['dia'] = $v['hac_dia'];
            $prof[$v['matricula']]['periodo'] = $v['hac_periodo'];
            $prof[$v['matricula']]['nome'] = $v['Professor'];
            $prof[$v['matricula']]['email'] = $v['email'];
            $disc = explode('|', $v['disciplinas']);
            @$disciplinas = NULL;
            foreach ($disc as $d) {
                if (!empty($d)) {
                    @$prof[$v['matricula']]['id_disc'][$d] = $d;
                    @$disciplinas[$n_disc[$d]] = $n_disc[$d];
                }
            }
            if (!empty($disciplinas)) {
                $prof[$v['matricula']]['disciplinas'] = implode(' ', $disciplinas);
            }
            @$prof[$v['matricula']]['escolas'] [$v['escola']] = $v['escola'] . ' ';
            $sql = "SELECT n_turma, fk_id_ciclo, codigo, periodo, n_inst FROM ge_aloca_prof p "
                    . " join ge_turmas t on t.id_turma = p.fk_id_turma "
                    . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                    . " join instancia i on i.id_inst = t.fk_id_inst "
                    . " WHERE `rm` LIKE '" . $v['matricula'] . "' "
                    . " and at_pl = 1";
            $query = pdoSis::getInstance()->query($sql);
            $classe = $query->fetchAll(PDO::FETCH_ASSOC);
            @$classePeriodo = NULL;
            foreach ($classe as $cl) {
                @$prof[$v['matricula']]['codigo'][] = $cl['codigo'];
                if (!empty($id_ciclo)) {
                    if ($cl['fk_id_ciclo'] == $id_ciclo) {
                        @$prof[$v['matricula']]['classes'] .= $cl['n_turma'];
                    }
                } else {
                    @$prof[$v['matricula']]['classes'] .= $cl['n_turma'] . '; ';
                }
                @$classePeriodo [$cl['periodo']][$cl['n_inst']][$cl['n_turma']] = $cl['n_turma'];
            }
            if (!empty($classePeriodo)) {

                foreach ($classePeriodo as $kp => $p) {
                    @$prof[$v['matricula']]['classesPorPeriodo'][$kp] = key($p) . ': ';
                    foreach ($p as $ke => $e) {
                        @$prof[$v['matricula']]['classesPorPeriodo'][$kp] .= (implode('; ', $e)) . '.';
                    }
                }
            }
        }
        return $prof;
    }
}

use PHPExcel_Style_Border;

require_once 'pdoSis.php';
@$disc = @$_POST['$disc_i'];
$hac_dia = @$_POST['hac_dia'];
$hac_periodo = @$_POST['hac_periodo'];
$id_inst = @$_POST['id_inst'];
@$id_ciclo = @$_POST['ciclo'];


$prof = profDiscClasse(@$disc, @$hac_dia, @$hac_periodo, @$id_inst, @$id_ciclo);
if (!empty($prof)) {

    @$nomearquivo = 'hac';

    if (!empty(@$prof)) {


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

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize();
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . '1', 'Matricula');

        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize();
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . '1', 'Nome');

        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize();
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . '1', 'E-mail');

        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize();
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . '1', 'Disciplina');

        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize();
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . '1', 'Escola');

        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize();
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . '1', 'Classes');

        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize();
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . '1', 'Dia');

        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize();
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . '1', 'Periodo');

        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize();
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . '1', 'Manhã');

        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize();
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . '1', 'Tarde');

        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize();
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . '1', 'Integral');

        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize();
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . '1', 'Noite');

        $c = 2;
        foreach ($prof as $k => $v) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $c, @$k);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $c, @$v['nome']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $c, @$v['email']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $c, @$v['disciplinas']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $c, implode(' ', @$v['escolas']));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $c, @$v['classes']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $c, @$v['dia']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $c, @$v['periodo']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $c, @$v['classesPorPeriodo']['M']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $c, @$v['classesPorPeriodo']['T']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $c, @$v['classesPorPeriodo']['I']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $c, @$v['classesPorPeriodo']['N']);
            $c++;
        }
        /**
          $c = 2;
          foreach ($prof as $key => $va) {
          $col1 = 'A';
          foreach ($array[$key] as $key1 => $va1) {
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col1, $va1, $returnCell);
          $col1++;
          }

          $c++;
          }
         * 
         */
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
}
    