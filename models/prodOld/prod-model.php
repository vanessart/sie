<?php

class prodModel extends MainModel {

    public $db;

    /**
     * Construtor para essa classe
     *
     * Configura o DB, o controlador, os parâmetros e dados do usuário.
     *
     * @since 0.1
     * @access public
     * @param object $db Objeto da nossa conexão PDO
     * @param object $controller Objeto do controlador
     */
    public function __construct($db = false, $controller = null) {
        // Configura o DB (PDO)
        $this->db = new DB();

        // Configura o controlador
        $this->controller = $controller;

        // Configura os parâmetros
        //$this->parametros = $this->controller->parametros;
        // Configura os dados do usuário
        $this->userdata = $this->controller->userdata;


        if (!empty($_POST['lancaNotas'])) {
            $fk_id_pv = $_POST['last_id'];
            $obs = @$_POST['obs'];
            $nota = @$_POST['nota'];
            $id_ni = @$_POST['id_ni'];
            $total = 0;
            if (!empty($obs)) {
                foreach ($obs as $ko => $o) {
                    $total += @$nota[$ko];
                    $idNi = empty($id_ni[$ko]) ? 'NULL' : $id_ni[$ko];
                    $sql = "replace INTO `prod1_nota_item` "
                            . "(`id_ni`, `fk_id_pv`, `fk_id_item`, `nota`, `obs`) "
                            . "VALUES ("
                            . " $idNi, "
                            . "'$fk_id_pv', "
                            . "'$ko', "
                            . "'" . @$nota[$ko] . "', "
                            . "'$o'"
                            . ");";
                    $query = pdoSis::getInstance()->query($sql);
                }
                $sql = "UPDATE `prod1_visita` SET `nota` = '$total' WHERE `prod1_visita`.`id_pv` = $fk_id_pv;";
                $query = pdoSis::getInstance()->query($sql);
                if ($query) {
                    tool::alert("Salvo");
                }
            }
        } elseif ($this->db->sqlKeyVerif('nota123')) {
            foreach ($_POST['n'] as $k => $v) {
                unset($ins);
                $ins['fk_id_pessoa'] = tool::id_pessoa();
                $ins['id_turma_nota'] = $k;
                $ins['nota'] = str_replace(',', '.', $v);
                $ins['fk_id_inst'] = @$_POST['id_inst'];
                if ($ins['nota'] > 10) {
                    $turma = sql::get('ge_turmas', 'n_turma', ['id_turma' => $k], 'fetch')['n_turma'];
                    tool::alert("Nota Máxima: 10. Favor refazer a nota da turma $turma");
                } elseif (!empty($ins['nota'])) {
                    $this->db->ireplace('prod_nota_turma', $ins, 1);
                }
            }
        } elseif ($this->db->sqlKeyVerif('assist')) {
            foreach ($_POST['n'] as $k => $v) {
                unset($ins);
                $ins['fk_id_pessoa'] = tool::id_pessoa();
                $ins['id_rm'] = $k;
                $ins['nota'] = str_replace(',', '.', $v);
                $ins['fk_id_inst'] = tool::id_inst();
                if ($ins['nota'] > 10) {
                    $turma = sql::get('ge_turmas', 'n_turma', ['id_turma' => $k], 'fetch')['n_turma'];
                    tool::alert("Nota Máxima: 10. Favor refazer a notas");
                } elseif (!empty($ins['nota'])) {
                    $this->db->ireplace('prod_assist', $ins, 1);
                }
            }
        }
    }

    public function anos123() {
        $sql = " SELECT distinct n_inst, id_inst FROM `ge_turmas` t "
                . " join instancia i on i.id_inst = t.fk_id_inst "
                . " left JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " where fk_id_ciclo in (1,2,3,19,20,21,22,23,24,25,26, 27,28,29,30,31,32,33,34) "
                . " AND pl.at_pl = 1 "
                . " order by n_inst ";
        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        foreach ($array as $v) {
            $esc[$v['id_inst']] = $v['n_inst'];
        }
        return $esc;
    }

    public function prof($id_inst) {
        $sql = "SELECT "
                . " t.id_turma, t.n_turma, ap.iddisc, d.n_disc, "
                . " p.n_pessoa, prod.nota "
                . " FROM ge_turmas t "
                . " left JOIN ge_aloca_prof ap on t.id_turma = ap.fk_id_turma "
                . " left JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " LEFT JOIN ge_disciplinas d on d.id_disc = ap.iddisc "
                . " LEFT JOIN ge_funcionario f on f.rm = ap.rm "
                . " LEFT JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " LEFT JOIN prod_nota_turma prod on prod.id_turma_nota = t.id_turma "
                . " WHERE t.fk_id_inst = $id_inst "
                . " AND t.fk_id_ciclo in (1,2,3,19,20,21,22,23,24,25,26, 27,28,29,30,31,32,33,34) "
                . " AND pl.at_pl = 1 "
                . " ORDER BY t.n_turma ASC ";
        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        foreach ($array as $k => $v) {
            $turmas[$v['id_turma']]['nota'] = $v['nota'];
            $turmas[$v['id_turma']]['n_turma'] = $v['n_turma'];
            if ($v['iddisc'] == 'nc') {
                @$turmas[$v['id_turma']]['n_pessoa'] .= $v['n_pessoa'] . '(Polivalente)<br />';
            } else {
                @$turmas[$v['id_turma']]['n_pessoa'] .= $v['n_pessoa'] . '(' . $v['n_disc'] . ')<br />';
            }
        }
        return $turmas;
    }

    public function assist($id_inst) {
        $sql = "SELECT rm, n_pessoa, nota FROM ge_funcionario f "
                . " join pessoa p on f.fk_id_pessoa = p.id_pessoa "
                . " left join prod_assist pa on pa.id_rm = f.rm "
                . " WHERE ( "
                . " `funcao` LIKE '%AGENTE DE DESENVOLVIMENTO INFANTIL%' "
                . " or `funcao` LIKE '%ASSISTENTE DE MATERNAL%') "
                . " and f.fk_id_inst = " . $id_inst
                . " order by n_pessoa";
        $query = $this->db->query($sql);
        return $query->fetchAll();
    }

    public function notaTurmas($id_inst) {
        $sql = "SELECT t.id_turma, prod.nota, t.n_turma FROM ge_turmas "
                . " t JOIN instancia i on i.id_inst = t.fk_id_inst "
                . " JOIN ge_periodo_letivo pe on pe.id_pl = t.fk_id_pl "
                . " JOIN prod_nota_turma prod on prod. id_turma_nota  = t.id_turma"
                . " WHERE pe.at_pl = 1 "
                . " AND i.id_inst = $id_inst "
                . "order by n_turma";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $total = 0;
        $cont = 0;
        foreach ($array as $v) {
            $tn[$v['id_turma']] = $v;
            $cont++;
            $total += $v['nota'];
        }
        if (!empty($cont)) {
            $tn['escola'] = $total / $cont;
            return $tn;
        }
    }

    public function prof12($id_inst = NULL, $n_pessoa = NULL, $naoAvaliado = NULL) {

        if (!empty($id_inst)) {
            $id_inst_ = " AND i.id_inst = $id_inst ";
        }
        if (!empty($naoAvaliado)) {
            $sql = " SELECT v.rm, i.n_inst, n_pa, p.n_pessoa, aval, nota FROM prod1_visita v "
                    . " join instancia i on i.id_inst = v.fk_id_inst "
                    . " join prod1_aval a on a.id_pa = v.fk_id_pa "
                    . " join pessoa p on p.id_pessoa = v.fk_id_pessoa "
                    . " where 1 "
                    . @$id_inst_;
            $query = $this->db->query($sql);
            $array = $query->fetchAll();
            foreach ($array as $k => $v) {
                if (!empty($v['nota'])) {
                    $avaliados[$v['rm']] = $v['rm'];
                }
            }
            if (!empty($avaliados)) {
                $naoAvaliado = " and f.rm not in (" . (implode(',', $avaliados)) . ") ";
            } else {
                $naoAvaliado = NULL;
            }
        }
        if (!empty($id_inst)) {
            $id_inst = " AND t.fk_id_inst = $id_inst ";
        }
        if (!empty($n_pessoa)) {
            if(is_numeric($n_pessoa)){
                $n_pessoa = " AND f.rm like '%$n_pessoa%'  ";
            } else {
               $n_pessoa = " AND p.n_pessoa like '%$n_pessoa%'  "; 
            }
            
        }
        $sql = "SELECT "
                . " t.id_turma, t.periodo, t.n_turma, ap.iddisc, d.n_disc, i.id_inst, t.fk_id_ciclo, "
                . " p.n_pessoa, p.id_pessoa, ap.rm, i.n_inst, i2.n_inst as inst_trab, psc.n_psc, psc.id_psc, t.fk_id_ciclo "
                . " FROM ge_turmas t "
                . " left JOIN ge_aloca_prof ap on t.id_turma = ap.fk_id_turma "
                . " LEFT JOIN ge_prof_esc pe on pe.rm = ap.rm "
                . " LEFT JOIN ge_prof_sit_classe psc on psc.id_psc = pe.fk_id_psc "
                . " LEFT JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " LEFT JOIN ge_disciplinas d on d.id_disc = ap.iddisc "
                . " LEFT JOIN ge_funcionario f on f.rm = ap.rm "
                . " LEFT JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " JOIN instancia i on i.id_inst = f.fk_id_inst "
                . " left JOIN instancia i2 on i2.id_inst = t.fk_id_inst "
                . " WHERE 1 "
                . " AND t.fk_id_ciclo in (1,2,19,20,21,22,23,24,25,26, 27,28,29,30,31,32,33,34, 35) "
                . " AND pl.at_pl = 1 "
                . " AND ap.rm not like '' "
                . " AND ap.iddisc in ('nc','11','15',27,28) "
                . $id_inst
                . $n_pessoa
                . $naoAvaliado
                . " ORDER BY p.n_pessoa, t.n_turma, d.n_disc ASC ";
        $query = $this->db->query($sql);
        $array1 = $query->fetchAll();
        ########################################################################
        
                $sql = "SELECT "
                . " t.id_turma, t.periodo, t.n_turma, ap.iddisc, d.n_disc, i.id_inst, t.fk_id_ciclo, "
                . " p.n_pessoa, p.id_pessoa, ap.rm, i.n_inst, i2.n_inst as inst_trab, psc.n_psc, psc.id_psc, t.fk_id_ciclo "
                . " FROM ge_turmas t "
                . " left JOIN ge_aloca_prof ap on t.id_turma = ap.fk_id_turma "
                . " LEFT JOIN ge_prof_esc pe on pe.rm = ap.rm "
                . " LEFT JOIN ge_prof_sit_classe psc on psc.id_psc = pe.fk_id_psc "
                . " LEFT JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " LEFT JOIN ge_disciplinas d on d.id_disc = ap.iddisc "
                . " LEFT JOIN ge_funcionario f on f.rm = ap.rm "
                . " LEFT JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " JOIN instancia i on i.id_inst = f.fk_id_inst "
                . " left JOIN instancia i2 on i2.id_inst = t.fk_id_inst "
                . " WHERE 1 "
                . " AND t.fk_id_ciclo in (27,28,29,30,34) "
                . " AND pl.at_pl = 1 "
                . " AND ap.rm not like '' "
                . $id_inst
                . $n_pessoa
                . $naoAvaliado
                . " ORDER BY p.n_pessoa, t.n_turma, d.n_disc ASC ";
        $query = $this->db->query($sql);
        $array = $query->fetchAll();
        
        $array = $array + $array1;
        ########################################################################
        foreach ($array as $k => $v) {
            if ($v['iddisc'] == 'nc') {
                $prof[$v['rm']]['n_disc'] = 'Polivalente';
            }
            $prof[$v['rm']] = $v;
            $idTurma[$v['rm']][] = $v['id_turma'];
            $nTurma[$v['rm']][] = $v['n_turma'];
            $prof[$v['rm']]['id_turma'] = '|' . implode('|', $idTurma[$v['rm']]) . '|';
            $prof[$v['rm']]['n_turma'] = implode('<br>', $nTurma[$v['rm']]);
        }
        return @$prof;
    }

    public function prof12Avaliado($id_inst = NULL) {
        if (!empty($id_inst)) {
            $id_inst = " where i.id_inst = $id_inst ";
        }

        $sql = " SELECT v.rm, i.n_inst, n_pa, p.n_pessoa, aval, nota FROM prod1_visita v "
                . " join instancia i on i.id_inst = v.fk_id_inst "
                . " join prod1_aval a on a.id_pa = v.fk_id_pa "
                . " join pessoa p on p.id_pessoa = v.fk_id_pessoa "
                . " $id_inst ";
        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        foreach ($array as $k => $v) {
            $result[$v['rm']]['rm'] = $v['rm'];
            $result[$v['rm']]['n_pessoa'] = $v['n_pessoa'];
            $result[$v['rm']]['n_pa'] = $v['n_pa'];
            $result[$v['rm']]['n_inst'] = $v['n_inst'];
            $result[$v['rm']]['aval_' . $v['aval']] = $v['nota'];
        }

        return @$result;
    }

    public function informatica($id_inst = NULL, $n_pessoa = NULL, $naoAvaliado = NULL) {
        if (!empty($id_inst)) {
            $id_inst_ = " AND i.id_inst = $id_inst ";
        }
        if (!empty($naoAvaliado)) {
            $sql = " SELECT v.rm, i.n_inst, n_pa, p.n_pessoa, aval, nota FROM prod1_visita v "
                    . " join instancia i on i.id_inst = v.fk_id_inst "
                    . " join prod1_aval a on a.id_pa = v.fk_id_pa "
                    . " join pessoa p on p.id_pessoa = v.fk_id_pessoa "
                    . " where 1 "
                    . @$id_inst_;
            $query = $this->db->query($sql);
            $array = $query->fetchAll();
            foreach ($array as $k => $v) {
                if (!empty($v['nota'])) {
                    $avaliados[$v['rm']] = $v['rm'];
                }
            }
            if (!empty($avaliados)) {
                $naoAvaliado = " and f.rm not in (" . (implode(',', $avaliados)) . ") ";
            } else {
                $naoAvaliado = NULL;
            }
        }

        if (!empty($id_inst)) {
            $id_inst = " AND f.fk_id_inst = $id_inst ";
        }
        if (!empty($n_pessoa)) {
             if(is_numeric($n_pessoa)){
                $n_pessoa = " AND f.rm like '%$n_pessoa%'  ";
            } else {
               $n_pessoa = " AND p.n_pessoa like '%$n_pessoa%'  "; 
            }
        }
        $sql = 'SELECT f.rm, p.n_pessoa, p.id_pessoa, f.funcao as n_disc, i.n_inst, i.id_inst, psc.id_psc, psc.n_psc FROM ge_prof_esc '
                . ' pe JOIN ge_funcionario f on f.rm = pe.rm '
                . " LEFT JOIN ge_prof_sit_classe psc on psc.id_psc = pe.fk_id_psc "
                . " JOIN instancia i on i.id_inst = f.fk_id_inst "
                . ' JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa '
                . ' WHERE pe.disciplinas LIKE \'%|20|%\' '
                . $naoAvaliado
                . $id_inst
                . $n_pessoa 
                . ' order by p.n_pessoa ';
        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        return $array;
    }

    public function adi($id_inst = NULL, $n_pessoa = NULL, $naoAvaliado = NULL) {
        if (!empty($id_inst)) {
            $id_inst_ = " AND i.id_inst = $id_inst ";
        }
        if (!empty($naoAvaliado)) {
            $sql = " SELECT v.rm, i.n_inst, n_pa, p.n_pessoa, aval, nota FROM prod1_visita v "
                    . " join instancia i on i.id_inst = v.fk_id_inst "
                    . " join prod1_aval a on a.id_pa = v.fk_id_pa "
                    . " join pessoa p on p.id_pessoa = v.fk_id_pessoa "
                    . " where 1 "
                    . @$id_inst_;
            $query = $this->db->query($sql);
            $array = $query->fetchAll();
            foreach ($array as $k => $v) {
                if (!empty($v['nota'])) {
                    $avaliados[$v['rm']] = $v['rm'];
                }
            }
            if (!empty($avaliados)) {
                $naoAvaliado = " and f.rm not in (" . (implode(',', $avaliados)) . ") ";
            } else {
                $naoAvaliado = NULL;
            }
        }

        if (!empty($id_inst)) {
            $id_inst = " AND f.fk_id_inst = $id_inst ";
        }
        if (!empty($n_pessoa)) {
              if(is_numeric($n_pessoa)){
                $n_pessoa = " AND f.rm like '%$n_pessoa%'  ";
            } else {
               $n_pessoa = " AND p.n_pessoa like '%$n_pessoa%'  "; 
            }
        }
        $sql = "SELECT p.n_pessoa, p.id_pessoa, f.rm, i.n_inst, i.id_inst, f.funcao as n_disc FROM ge_funcionario f "
                . " JOIN instancia i on i.id_inst = f.fk_id_inst "
                . " JOIN pessoa p on p.id_pessoa= f.fk_id_pessoa "
                . " WHERE funcao LIKE '%AGENTE DE DESENVOLVIMENTO INFANTIL%' "
                . $id_inst
                . $n_pessoa
                . $naoAvaliado
                . " ORDER BY `p`.`n_pessoa` ASC";
        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        return$array;
    }

    public function avaliadores() {
        $a = sql::get(['prod1_avaliador', 'pessoa'], 'n_pessoa, id_pessoa, revisor');
        $avaliadores = tool::idName($a, 'id_pessoa', 'n_pessoa');
        if (!in_array(tool::id_pessoa(), $avaliadores)) {
            $avaliadores [tool::id_pessoa()] = user::session('n_pessoa');
        }
        return $avaliadores;
    }

    public function revisoresIdPessoa() {
        $a = sql::get(['prod1_avaliador', 'pessoa'], 'fk_id_pessoa', ['revisor' => 1]);

        $revisor = array_column($a, 'fk_id_pessoa');

        return $revisor;
    }

    public function avaliouInst($id_inst = NULL) {
        if (!empty($id_inst)) {
            $where = ['fk_id_inst' => $id_inst];
        } else {
            $where = NULL;
        }
        $vis = sql::get('prod1_visita', ' rm, revisor_id_pessoa, avaliador_id_pessoa, aval, nota ', $where);
        foreach ($vis as $v) {
            if ($v['revisor_id_pessoa'] > 0) {
                $aval[$v['aval']][$v['rm']] = 2;
                $aval[$v['aval']]['nota'][$v['rm']] = $v['nota'];
            } elseif ($v['avaliador_id_pessoa'] > 0) {
                $aval[$v['aval']]['nota'][$v['rm']] = $v['nota'];
                $aval[$v['aval']][$v['rm']] = 1;
            } else {
                $aval[$v['aval']][$v['rm']] = NULL;
            }
        }
        return $aval;
    }

    public function avaliouRm($rm) {
        $vis = sql::get('prod1_visita', ' rm, revisor_id_pessoa, avaliador_id_pessoa, aval, nota ', ' where rm in (' . implode(',', $rm) . ')');
        foreach ($vis as $v) {
            if ($v['revisor_id_pessoa'] > 0) {
                $aval[$v['aval']][$v['rm']] = 2;
                $aval[$v['aval']]['nota'][$v['rm']] = $v['nota'];
            } elseif ($v['avaliador_id_pessoa'] > 0) {
                $aval[$v['aval']]['nota'][$v['rm']] = $v['nota'];
                $aval[$v['aval']][$v['rm']] = 1;
            } else {
                $aval[$v['aval']][$v['rm']] = NULL;
            }
        }
        return $aval;
    }

    public function profNotaFinalInLoco() {
        $sql = "SELECT rm, avg(nota) as total FROM ge2.prod1_visita "
                . " where nota is not null "
                . " group by rm";
        $query = $this->db->query($sql);
        $array = $query->fetchAll();
        foreach ($array as $v) {
            $nf[$v['rm']] = $v['total'];
        }

        return @$nf;
    }

    public function buttonSet($aval, $v, $avaliou) {

        if (@$avaliou[$aval][$v['rm']] == 2) {
            $button = 'btn btn-success';
            $titulo = round($avaliou[$aval]['nota'][$v['rm']], 2);
        } elseif (@$avaliou[$aval][$v['rm']] == 1) {
            $button = 'btn btn-warning';
            $titulo = round($avaliou[$aval]['nota'][$v['rm']], 2);
        } else {
            $button = 'btn btn-primary';
            $titulo = 'Não Avaliado';
        }
        $submit = form::submit($titulo, NULL, $v, NULL, NULL, NULL, $button, 'width: 140px');

        return $submit;
    }

    public function eixoItens($tipoAval, $visita = NULL) {
        if(!empty($tipoAval)){
        if(!empty($visita)){
            $valor = 'valor'.$visita;
            $visita = " and $valor != '' ";
        }
        $sql = "SELECT i.*, e.n_eixo FROM prod1_eixo e "
                . " JOIN prod1_item i on i.fk_id_eixo = e.id_eixo "
                . " WHERE i.fk_id_pa =  $tipoAval "
                . $visita
                . " ORDER BY e.ordem_eixo, i.ordem_item";
        $query = pdoSis::getInstance()->query($sql);
        $items = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($items as $v) {
            $itemsOrd[$v['n_eixo']][$v['id_item']] = $v;
        }

        return @$itemsOrd;
        }
    }

}
