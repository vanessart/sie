<style>
    td{
        padding: 4px;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
ini_set('memory_limit', '20000M');
$id_instSet = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
if ($id_instSet) {
    $instSql = " and t.fk_id_inst = $id_instSet ";
} else {
    $instSql = null;
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
$array = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($array as $v) {
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
    $df = [];
    foreach ($array as $y) {
        if (!empty($v['faltas_' . $y['fk_id_disc']])) {
            if ($v['faltas_' . $y['fk_id_disc']] > intval((($tdias / 5) * $y['aulas']) / 4)) {
                $df[$y['fk_id_disc']]['faltas'] = $v['faltas_' . $y['fk_id_disc']];
                $df[$y['fk_id_disc']]['aulas'] = intval(($tdias / 5) * $y['aulas']);
                $df[$y['fk_id_disc']]['porc'] = intval($v['faltas_' . $y['fk_id_disc']] / intval(($tdias / 5) * $y['aulas']) * 100);
            }
        }
    }
    $faltas[$v['fk_id_inst']][$v['n_ciclo']][$v['fk_id_pessoa']]['disc'] = $df;
    $faltas[$v['fk_id_inst']][$v['n_ciclo']][$v['fk_id_pessoa']]['turma'] = [$v['id_turma'], $v['n_turma']];
    $faltas[$v['fk_id_inst']][$v['n_ciclo']][$v['fk_id_pessoa']]['nome'] = $v['n_pessoa'];
}

foreach ($peb1 as $v) {

    $faltas[$v['fk_id_inst']][$v['n_ciclo']][$v['fk_id_pessoa']]['turma'] = [$v['id_turma'], $v['n_turma']];
    $faltas[$v['fk_id_inst']][$v['n_ciclo']][$v['fk_id_pessoa']]['disc']['nc']['faltas'] = $v['faltas'];
    $faltas[$v['fk_id_inst']][$v['n_ciclo']][$v['fk_id_pessoa']]['disc']['nc']['aulas'] = $tdias;
    $faltas[$v['fk_id_inst']][$v['n_ciclo']][$v['fk_id_pessoa']]['disc']['nc']['porc'] = intval(($v['faltas'] / $tdias) * 100);
    $faltas[$v['fk_id_inst']][$v['n_ciclo']][$v['fk_id_pessoa']]['nome'] = $v['n_pessoa'];
}
if (empty($id_instSet)) {
    $escolas = ng_escolas::idEscolas([1]);
} else {
    $escolas = sql::idNome('instancia', ['id_inst' => $id_instSet]);
}
ob_start();
$pdf = new pdf();
$pdf->mgt = '35';
$pdf->headerAlt = '<img style="height: 100px" src="' . ABSPATH . '/'. INCLUDE_FOLDER .'/images/topo.jpg"/>';

foreach ($escolas as $id_inst => $n_inst) {
    if (!empty($faltas[$id_inst])) {
        ?>
        <div style="text-align: center; padding: 30px; font-weight: bold; font-size: 1.3em">
            Relação de Alunos com excesso de faltas    
        </div>
        <div style="text-align: center; padding: 30px; font-weight: bold; font-size: 1.2em; width: 100%">
            <?= $n_inst ?>
        </div>
        <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
            <?php
            ksort($faltas[$id_inst]);
            foreach ($faltas[$id_inst] as $n_ciclo => $a) {
                ?>
                <tr>
                    <td colspan="3"  style="text-align: center; padding: 30px; font-weight: bold; font-size: 1.4em; width: 100%">
                            <?= $n_ciclo ?>
                        <br />
                        <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
                            <tr>
                                <td>
                                    Nome
                                </td>
                                <td>
                                    RSE
                                </td>
                                <td>
                                    Turma
                                </td>
                                <td>
                                    Faltas
                                </td>
                            </tr>
                            <?php
                            foreach ($a as $id_pessoa => $d) {
                                ?>
                                <tr>
                                    <td style="text-align: left">
                                        <?= $d['nome'] ?>
                                    </td>
                                    <td>
                                        <?= $id_pessoa ?>
                                    </td>
                                    <td>
                                        <?= $d['turma'][1] ?>
                                    </td>
                                    <td style="text-align: left">
                                        <?php
                                        foreach ($d['disc'] as $id_disc => $dc) {
                                            ?>
                                            <?= $id_disc == 'nc' ? 'Núcleo Comum' : $disc[$id_disc] ?>:  <?= $dc['faltas'] ?> Faltas<br>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>  
                        </table>
                    </td>
                </tr>

                <?php
            }
            ?>
        </table>
        <br /><br />
        <?php
    }
}
$pdf->exec();
