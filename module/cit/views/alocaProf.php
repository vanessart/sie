<?php
if (!defined('ABSPATH'))
    exit;

$sql = "TRUNCATE ge_aloca_prof_cit";
$query = pdoSis::getInstance()->query($sql);
$prof = rhImport::alocaProf(date("Y"));
##################            
?>
<pre>   
    <?php
    print_r($prof);
    ?>
</pre>
<?php
###################

if (empty($prof)) {
    echo '<br /><br />erro';
    exit();
}
foreach ($prof as $m) {
    $matricula = $m['matricula'];
    foreach ($m['turmas'] as $v) {
        if ($v['id_disc'] == 27 && in_array($v['id_ciclo'], [1, 2, 3, 4, 5, 25, 26, 35, 36, 37])) {
            $v['id_disc'] = 'nc';
        }
        if ($v['id_disc'] == 15 && !in_array($v['id_ciclo'], [1, 2, 3, 4, 5, 27, 28, 29, 30, 34])) {
            $v['id_disc'] = 30;
        }
        if ($v['id_disc'] == 30 && in_array($v['id_ciclo'], [1, 2, 3, 4, 5, 27, 28, 29, 30, 34])) {
            $v['id_disc'] = 15;
        }
        if ($v['id_disc'] == 48) {
            $v['id_disc'] = 27;
        }
        $sql = "insert INTO ge_aloca_prof_cit (`cie`, `fk_id_ciclo`, `letra`, `fk_id_disc`, `matricula`, `suplementar`, `status`) VALUES ("
                . "'" . $v['cie'] . "', "
                . "'" . $v['id_ciclo'] . "', "
                . "'" . $v['letra'] . "', "
                . "'" . $v['id_disc'] . "', "
                . "'" . $m['matricula'] . "', "
                . "'" . $v['carga_suplementar'] . "', "
                . "'" . $v['status'] . "' "
                . ");";
        $query = pdoSis::getInstance()->query($sql);
    }
}

$pls = ng_main::periodosAtivos();
$sql = "SELECT distinct t.id_turma, t.fk_id_ciclo as id_ciclo, t.letra, e.cie_escola as cie, t.fk_id_inst as id_inst FROM ge_turmas t JOIN ge_escolas e on e.fk_id_inst = t.fk_id_inst AND t.fk_id_pl in (" . implode(', ', $pls) . ")";
$query = pdoSis::getInstance()->query($sql);
$t = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($t as $v) {
    $turmas[$v['cie'] . '_' . $v['id_ciclo'] . '_' . strtoupper(trim($v['letra']))] = ['id_turma' => $v['id_turma'], 'id_inst' => $v['id_inst']];
}
if (!empty($turmas)) {
    $cit = sql::get('ge_aloca_prof_cit');
    if ($cit) {
        //Este delete deve ocorrer só no domingo
        $diaSem = date('w', strtotime(date('Y-m-d')));
        if ($diaSem == 0) {
            $sql = "DELETE FROM `ge_aloca_prof` WHERE cit = 1";
            $query = pdoSis::getInstance()->query($sql);
        }
        $sql = "DELETE FROM `ge_aloca_prof_erro`";
        $query = pdoSis::getInstance()->query($sql);
        foreach ($cit as $v) {
            ##################################bambiarra ##########################################
            if ($v['matricula'] == 29801) {
                $citSet = 0;
            } else {
                $citSet = 1;
            }
            ##################################bambiarra ##########################################
            if (!empty($turmas[$v['cie'] . '_' . $v['fk_id_ciclo'] . '_' . $v['letra']])) {
                $turma = $turmas[$v['cie'] . '_' . $v['fk_id_ciclo'] . '_' . $v['letra']];
                @$conta[$turma['id_turma'] . '_' . $v['fk_id_disc']]++;
                $aloca = [
                    'fk_id_turma' => $turma['id_turma'],
                    'iddisc' => $v['fk_id_disc'],
                    'fk_id_inst' => $turma['id_inst'],
                    'rm' => $v['matricula'],
                    'prof2' => $conta[$turma['id_turma'] . '_' . $v['fk_id_disc']],
                    'suplementar' => $v['suplementar'],
                    'cit' => $citSet
                ];
                $sql = "delete from ge_aloca_prof "
                        . " where fk_id_turma = " . $turma['id_turma']
                        . " and iddisc like '" . $v['fk_id_disc'] . "' "
                        . " and  prof2 = " . $conta[$turma['id_turma'] . '_' . $v['fk_id_disc']];
                $query = pdoSis::getInstance()->query($sql);
                $model->db->insert('ge_aloca_prof', $aloca, 1);
            } elseif ($v['status'] == 'A') {
                $err['cie'] = $v['cie'];
                $err['fk_id_ciclo'] = $v['fk_id_ciclo'];
                $err['letra'] = $v['letra'];
                try {
                    $model->db->insert('ge_aloca_prof_erro', $err, 1);
                } catch (Exception $exc) {
                    
                }


                echo '<br />A turma CIE: ' . $v['cie'] . ' id_ciclo: ' . $v['fk_id_ciclo'] . ' Letra: ' . $v['letra'] . ' não existe';
            }
        }

        foreach ($cit as $v) {
            $sql = "SELECT p.id_pe FROM ge_prof_esc p "
                    . " join ge_escolas e on e.fk_id_inst = p.fk_id_inst "
                    . " WHERE e.cie_escola = " . $v['cie']
                    . " AND p.rm = '" . $v['matricula'] . "'";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetch(PDO::FETCH_ASSOC);
            if (!$array) {
                $sql = "select distinct "
                        . " p.n_pessoa, f.fk_id_inst, p.emailgoogle "
                        . " from ge_funcionario f "
                        . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                        . " where f.rm = '" . $v['matricula'] . "'";

                $query = $model->db->query($sql);
                $pf = $query->fetch();
                if (!empty($pf['n_pessoa'])) {
                    $id_inst = sqlErp::get('ge_escolas', 'fk_id_inst', ['cie_escola' => $v['cie']], 'fetch')['fk_id_inst'];
                    $sql = "INSERT INTO `ge_prof_esc` "
                            . " (`n_pe`, `fk_id_inst`, `rm`, `email`) VALUES ("
                            . "'" . $pf['n_pessoa'] . "', "
                            . "'" . $id_inst . "', "
                            . "'" . $v['matricula'] . "', "
                            . "'" . $pf['emailgoogle'] . "'"
                            . ");";
                    try {
                        $query = pdoSis::getInstance()->query($sql);
                    } catch (Exception $exc) {
                        echo '... ';
                    }
                }
            }
        }
    }
    $sql = "UPDATE ge_aloca_prof a JOIN ge_prof_esc p on p.rm = a.rm SET p.disciplinas = concat('|', a.iddisc, '|') WHERE p.disciplinas IS null ";
    $query = pdoSis::getInstance()->query($sql);
    $sql = "UPDATE ge_prof_esc SET disciplinas = '|15|30|' WHERE (disciplinas LIKE '|15|' OR disciplinas LIKE '|30|') ";
    $query = pdoSis::getInstance()->query($sql);
}

