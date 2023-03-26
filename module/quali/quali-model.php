<?php

/**
  if($this->db->tokenCheck('table')){

  }
 * 
 * quali de select dinâmico
 *    
  public static function avalSelect($id_agrup) {
  ob_end_clean();
  $dados=[1,2,3,4,5];
  echo json_encode($dados);
  exit();
  }
 * 
 */
class qualiModel {

    public $_form;
    public $db;
    public $_formAdm;

    public function __construct($db = false, $controller = null) {

        $this->db = $db;

        $this->controller = $controller;

        //seta o select dinamico
        if ($opt = form::jqOption()) {
            $metodo = $opt[0];
            if (in_array($metodo, get_class_methods($this))) {
                $this->$metodo($opt[1]);
            }
        }
        $this->_formAdm = $this->InscrAtiva();
        if ($this->db->tokenCheck('quali_inscrSalva')) {
            $this->quali_inscrSalva();
        } elseif (!empty($_POST['SalvaFormInscrEdit'])) {
            $this->_form = @$_POST['formulario'];
            $salvo = $this->SalvaFormInscr();
        } elseif ($this->db->tokenCheck('consolidadoPart')) {
            $this->consolidadoPart();
        }
    }

    public function InscrAtiva() {
        $sql = "SELECT id_inscr FROM `quali_inscr` WHERE `at_inscr` = 1";
        $query = pdoSis::getInstance()->query($sql);
        @$result = $query->fetch(PDO::FETCH_ASSOC)['id_inscr'];

        return $result;
    }

    public function quali_inscrSalva() {
        $ins = @$_POST[1];
        $cur = @$_POST['cur'];
        $quant = @$_POST['quant'];
        if (is_array(@$quant)) {
            $ins['quant'] = json_encode($quant);
        }
        foreach ($cur as $k => $v) {
            if ($v == 1) {
                $c[] = $k;
            }
        }
        if (!empty($c)) {
            $ins['cursos'] = implode(',', $c);
        }
        $id_inscr = $this->db->ireplace('quali_inscr', @$ins);
        try {
            $sql = "SELECT * FROM `quali_incritos_" . $id_inscr . "`";
            $query = pdoSis::getInstance()->query($sql);
        } catch (Exception $ex) {
            $sql = "CREATE TABLE IF NOT EXISTS quali_incritos_" . $id_inscr . " SELECT * FROM quali_incritos_; ";
            $query = pdoSis::getInstance()->query($sql);
            $sql = " alter table quali_incritos_" . $id_inscr . " drop column id_inscr;"
                    . " alter table quali_incritos_" . $id_inscr . " add id_inscr INT NOT NULL PRIMARY KEY auto_increment; "
                    . " ALTER TABLE `quali_incritos_" . $id_inscr . "` CHANGE `id_inscr` `id_inscr` INT(11) NOT NULL auto_increment FIRST; ";
            $query = pdoSis::getInstance()->query($sql);
        }
    }

    public function SalvaFormInscr() {
        $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_NUMBER_INT);
        if (!$this->jaInscrito($cpf, $this->_form) || !empty($_POST['SalvaFormInscrEdit'])) {
            $ins = @$_POST[1];
            $fa = filter_input(INPUT_POST, 'fases_ant', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
            if ($fa) {
                foreach ($fa as $k => $v) {
                    if ($v == 1) {
                        $fases_ant[$k] = $k;
                    }
                }
                if (!empty($fases_ant)) {
                    $ins['fases_ant'] = implode(",", $fases_ant);
                }
            }
            $ins['nome'] = strtoupper($ins['nome']);
            try {
                $id = $this->db->ireplace('quali_incritos_' . $this->_form, $ins);
            } catch (Exception $exc) {
                tool::alert("Algo de errado aconteceu.");
            }
            return $id;
        }
    }

    public function jaInscrito($cpf, $form) {
        @$nome = sql::get('quali_incritos_' . $form, 'nome', ['cpf' => $cpf], 'fetch')['nome'];
        if ($nome) {
            return explode(' ', $nome)[0];
        }
    }

    public function cursoDashboard($formulario) {
        $ceps = sql::idNome('quali_cepsBairroBarueri');

        $sql = "SELECT cep, fk_id_cur, fk_id_cur_2, conheceu, situacao, fk_id_esc, fk_id_se FROM quali_incritos_$formulario ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            @$dash['escolaridade'][$v['fk_id_esc']]++;
            if (empty($v['conheceu'])) {
                $v['conheceu'] = 'Outros';
            }
            @$dash['conheceu'][$v['conheceu']]++;
            @$dash['emprego'][$v['fk_id_se']]++;
            @$dash['curso'][$v['fk_id_cur']]++;
            if ($v['situacao'] == 1) {
                @$dash['conheceuDef'][$v['conheceu']]++;
                @$dash['empregoDef'][$v['fk_id_se']]++;
                @$dash['cursoAprovado'][$v['fk_id_cur']]++;
                @$dash['cepDef'][$ceps[str_replace('-', '', $v['cep'])]]++;
            }
            if ($v['fk_id_cur'] != $v['fk_id_cur_2']) {
                @$dash['curso2'][$v['fk_id_cur_2']]++;
            }
            @$dash['cep'][$ceps[str_replace('-', '', $v['cep'])]]++;
            @$dash['total']++;
            @$dash['sit'][$v['situacao']]++;
        }

        return @$dash;
    }

    public function CursoInscr($insc = null) {
        if (empty($insc)) {
            $insc = 1;
        }
        $inscSet = sql::get('quali_inscr', '*', ['id_inscr' => $insc], 'fetch');
        $sql = "SELECT id_cur, n_cur FROM `gt_curso` WHERE `id_cur` in (" . $inscSet['cursos'] . ") order by n_cur";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return tool::idName($array);
    }

    public function relatGerente1($insc = null) {
        if (empty($insc)) {
            $insc = 1;
        }
        $sql = "SELECT substring(`time_stamp`, 1,10) as id_dia, COUNT(substring(`time_stamp`, 1,10) ) as n_ct FROM `quali_incritos_$insc` GROUP BY substring(`time_stamp`, 1,10)";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return tool::idName($array);
    }

    public function aprovados($formulario) {
        $sql = "SELECT i.id_inscr, i.nome, i.cpf, c.n_cur FROM `quali_incritos_$formulario` i "
                . " join gt_curso c on c.id_cur = i.fk_id_cur "
                . " where situacao = 1 "
                . " order by i.nome ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function PlTurmas() {
        $sql = "SELECT DISTINCT pl.id_pl, pl.n_pl FROM gt_turma t "
                . " JOIN gt_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " WHERE t.fk_id_inst = " . tool::id_inst()
                . " ORDER BY `pl`.`id_pl` ASC";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return tool::idName($array);
    }

    public function cursosTodosPl() {
        $sql = "SELECT DISTINCT ci.n_ciclo, ci.id_ciclo FROM gt_turma t "
                . " JOIN gt_ciclo ci on ci.id_ciclo = t.fk_id_ciclo "
                . " WHERE t.fk_id_inst = " . tool::id_inst()
                . " and ci.n_ciclo not like '%teste%' "
                . " ORDER BY ci.n_ciclo";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return tool::idName($array);
    }

    public function relatGeral() {
        $sql = "SELECT `id_inscr`, `fk_id_pl` FROM `quali_inscr`";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $inscrPl[$v['fk_id_pl']] = $v['id_inscr'];
        }

        $sql = "SELECT id_pl FROM `gt_periodo_letivo` WHERE `n_pl` LIKE '%fase %' ";
        $query = pdoSis::getInstance()->query($sql);
        $pls = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($pls as $v) {
            if (!empty($inscrPl[$v['id_pl']])) {
                $sql = " SELECT "
                        . " q.situacao, ci.id_ciclo "
                        . " FROM quali_incritos_" . $inscrPl[$v['id_pl']] . " q "
                        . " JOIN gt_curso c on c.id_cur = q.fk_id_cur "
                        . " JOIN gt_ciclo ci on ci.fk_id_cur = c.id_cur";
                $query = pdoSis::getInstance()->query($sql);
                $array = $query->fetchAll(PDO::FETCH_ASSOC);
                foreach ($array as $y) {
                    if ($y['situacao'] == 1) {
                        @$geral[$v['id_pl']][$y['id_ciclo']]['DEF']++;
                    } elseif ($y['situacao'] == 2) {
                        @$geral[$v['id_pl']][$y['id_ciclo']]['IND']++;
                    }
                }
            }
            $sql = " SELECT "
                    . " distinct l.fk_id_pessoa, n.fez "
                    . " FROM `curso_aluno_log_" . $v['id_pl'] . "` l "
                    . " left join curso_aluno_nota_aval n on n.fk_id_pessoa = l.fk_id_pessoa "
                    . " left join gt_turma t on t.id_turma = n.fk_id_turma and t.fk_id_pl = " . $v['id_pl']
                    . " GROUP BY `fk_id_pessoa`";
            try {
                $query = pdoSis::getInstance()->query($sql);
            } catch (Exception $exc) {
                $array = null;
                $query = null;
            }
            if ($query) {
                $array = $query->fetchAll(PDO::FETCH_ASSOC);
                foreach ($array as $y) {
                    if ($y['fez'] == 1) {
                        $acesso[$v['id_pl']][$y['fk_id_pessoa']] = $y['fez'];
                    } else {
                        $acesso[$v['id_pl']][$y['fk_id_pessoa']] = 'x';
                    }
                }
            }
        }

        $sql = "SELECT "
                . " ta.fk_id_sf, t.fk_id_ciclo, pl.n_pl, pl.id_pl, ta.fk_id_pessoa "
                . " FROM gt_turma_aluno ta "
                . " JOIN gt_turma t on t.id_turma = ta.fk_id_turma "
                . " JOIN gt_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " WHERE ta.fk_id_inst = " . tool::id_inst()
                . " AND ta.fk_id_sit = 0 "
                . " AND pl.n_pl like 'fase%' ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            if ($v['fk_id_ciclo'] == 102) {
                $v['fk_id_ciclo'] = 65;
            }
            @$geral[$v['id_pl']][$v['fk_id_ciclo']]['MAT']++;
            if (in_array($v['fk_id_sf'], [1, 2, 7])) {
                @$geral[$v['id_pl']][$v['fk_id_ciclo']]['APR']++;
                @$geral[$v['id_pl']][$v['fk_id_ciclo']]['AP']++;
                @$geral[$v['id_pl']][$v['fk_id_ciclo']]['CON']++;
            } elseif (@$acesso[$v['id_pl']][$v['fk_id_pessoa']] == 1) {
                @$geral[$v['id_pl']][$v['fk_id_ciclo']]['REP']++;
                @$geral[$v['id_pl']][$v['fk_id_ciclo']]['AP']++;
                @$geral[$v['id_pl']][$v['fk_id_ciclo']]['CON']++;
            } elseif (@$acesso[$v['id_pl']][$v['fk_id_pessoa']] == 'x') {
                @$geral[$v['id_pl']][$v['fk_id_ciclo']]['DES']++;
                @$geral[$v['id_pl']][$v['fk_id_ciclo']]['AP']++;
            } else {
                @$geral[$v['id_pl']][$v['fk_id_ciclo']]['NAP']++;
            }
        }
        ksort($geral);
        return $geral;
    }

    public function emailExiste($email, $cpf) {
        if (!empty($email) && !empty($cpf)) {
            $sql = "SELECT cpf, email FROM `pessoa` WHERE `email` LIKE '$email' ";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetch(PDO::FETCH_ASSOC);
            if (empty($array['email'])) {
                return null;
            } elseif ($cpf != @$array['cpf']) {
                return true;
            } else {
                return null;
            }
        }
    }

    public function consolidadoPart() {
        //SHOW TABLES LIKE 'myTable'
        $sql = "SELECT q.id_inscr, pl.n_pl, pl.at_pl FROM `quali_inscr` q "
                . " join gt_periodo_letivo pl on pl.id_pl = q.fk_id_pl "
                . " order by id_inscr ";
        $query = pdoSis::getInstance()->query($sql);

        $iq = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($iq as $p) {

            $v = $p['id_inscr'];
            $array = null;
            $sql = "SELECT * FROM `quali_incritos_$v` "
                    . " where situacao like '1' "
                    . " ORDER BY `nome` ASC";
            try {
                $query = pdoSis::getInstance()->query($sql);
                $array = $query->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $exc) {
                
            }
            if ($array) {
                $meta[$v]['fase'] = $p['n_pl'];
                $meta[$v]['id_meta'] = $v;
                foreach ($array as $y) {
                    if (!empty($y['cpf'])) {
                        $sql = "SELECT ta.fk_id_sf FROM pessoa p "
                                . " JOIN gt_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa "
                                . " WHERE `cpf` LIKE '" . $y['cpf'] . "'";
                        try {
                            $query = pdoSis::getInstance()->query($sql);
                            @$id_sf = $query->fetch(PDO::FETCH_ASSOC)['fk_id_sf'];
                        } catch (Exception $exc) {
                            $id_sf = null;
                        }

                        if (@$id_sf == 1 && $p['at_pl'] != 1) {
                            @$meta[$v]['aprovados']++;
                        }
                        @$meta[$v]['participantes']++;
                        if ($y['sexo'] == 'F') {
                            @$meta[$v]['feminino']++;
                        }

                        unset($y['id_inscr']);
                        $this->db->replace('quali_incritos_Consolidado', $y, 1);
                    }
                }
            }
        }
        foreach ($meta as $v) {
            if (empty($v['aprovados'])) {
                $v['aprovados'] = 'Fase Aberta';
            }
            $this->db->replace('quali_incritos_metadados', $v, 1);
        }
    }

}
