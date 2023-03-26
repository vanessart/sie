<?php

class makerModel extends MainModel {

    public $db;

    /**
      if($this->db->tokenCheck('table')){

      }
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
        $this->db = new crud();
        // Configura o controlador
        $this->controller = $controller;

        // Configura os parâmetros
        $this->parametros = $this->controller->parametros->variavel;

        // Configura os dados do usuário
        $this->userdata = $this->controller->userdata;

        //seta o select dinamico
        if ($opt = formErp::jqOption()) {
            $metodo = $opt[0];
            if (in_array($metodo, get_class_methods($this))) {
                $this->$metodo($opt[1]);
            }
        }

        if ($this->db->tokenCheck('transferirAluno')) {
            $this->transferirAluno();
        } elseif ($this->db->tokenCheck('chamadaSalvar')) {
            $this->chamadaSalvar();
        } elseif ($this->db->tokenCheck('apagaCh')) {
            $this->apagaCh();
        } elseif ($this->db->tokenCheck('desativar')) {
            $this->desativar();
        } elseif ($this->db->tokenCheck('permuta')) {
            $this->permuta();
        } elseif ($this->db->tokenCheck('criarTurmas')) {
            $this->criarTurmas();
        } elseif ($this->db->tokenCheck('maker_setupSet')) {
            $this->maker_setupSet();
        } elseif ($this->db->tokenCheck('matriculaAluno')) {
            $this->matriculaAluno();
        }
    }

    public function maker_setupSet() {
        $ins = @$_POST[1];
        $libera_matr = $periodo = filter_input(INPUT_POST, 'libera_matr', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $transf = $periodo = filter_input(INPUT_POST, 'transf', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        if ($libera_matr) {
            $ins['libera_matr'] = implode(',', $libera_matr);
        }
        if ($transf) {
            foreach ($transf as $k => $v) {
                if ($v) {
                    $transf_[] = $k;
                }
            }
            if (!empty($transf_)) {
                $ins['transf'] = implode(',', $transf_);
            } else {
                $ins['transf'] = 'x';
            }
        } else {
            $ins['transf'] = 'x';
        }
        $this->db->ireplace('maker_setup', $ins);
    }

    public function criarTurmas() {
        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        $id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
        $periodo = filter_input(INPUT_POST, 'periodo');
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $ciclos = $_POST[1];
        $ins['fk_id_inst'] = $id_inst;
        $ins['fk_id_pl'] = $id_pl;
        $transporte = @$_POST['transp'];
        $escola = @$_POST['esc'];
        foreach ($ciclos as $k => $v) {
            if ($v) {
                $sql = "SELECT id_turma FROM maker_gt_turma "
                        . " WHERE substring(n_turma, 2,3) LIKE '" . substr($k, 0, 3) . "' "
                        . " AND fk_id_inst = $id_inst "
                        . " AND fk_id_pl = $id_pl";
                $query = pdoSis::getInstance()->query($sql);
                $arr = $query->fetch(PDO::FETCH_ASSOC);
                if (!empty($arr['id_turma'])) {
                    $ins['id_turma'] = $arr['id_turma'];
                } else {
                    unset($ins['id_turma']);
                }
                $ins['fk_id_inst_sieb'] = (string) intval(@$escola[$k]);
                $ins['transporte'] = (string) intval(@$transporte[$k]);
                $ins['n_turma'] = $v . substr($k, 0, 3) . str_pad($id_polo, 2, '0', STR_PAD_LEFT);
                $ins['codigo'] = $v . substr($k, 0, 3) . str_pad($id_polo, 2, '0', STR_PAD_LEFT);
                $ins['fk_id_ciclo'] = $v;
                $ins['fk_id_grade'] = 1;
                $ins['periodo'] = $periodo;
                $ins['letra'] = substr($k, -1);
                $ins['horario_duracao'] = 120;

                $this->db->ireplace('maker_gt_turma', $ins, 1);
            } else {
                $sql = "delete FROM maker_gt_turma "
                        . " WHERE substring(n_turma, 2,3) LIKE '" . substr($k, 0, 3) . "' "
                        . " AND fk_id_inst = $id_inst "
                        . " AND fk_id_pl = $id_pl";
                $query = pdoSis::getInstance()->query($sql);
            }
        }
        toolErp::alert('Concluído');
    }

    public function apagaCh() {
        $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
        $data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_NUMBER_INT);
        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        $mongo = new mongoCrude('Maker');
        $mongo->delete('presece_' . $id_pl, ['id_turma' => $id_turma, 'data' => $data]);
    }

    public function chamadaSalvar() {
        $ins = @$_POST;
        unset($ins['path']);
        unset($ins['formToken']);
        $mongo = new mongoCrude('Maker');
        $mongo->update('presece_' . $ins['id_pl'], ['data' => $ins['data'], 'id_turma' => $ins['id_turma']], $ins, null, 1);
    }

    public function escolaPolo($id_inst) {
        $sql = "SELECT "
                . " ei.n_inst as escola, si.n_inst as sede, e.cota_m, e.cota_t, e.cota_n, p.n_polo, p.id_polo, p.fk_id_inst_maker "
                . " FROM maker_escola e "
                . " JOIN instancia ei on ei.id_inst = e.fk_id_inst "
                . " JOIN maker_polo p on p.id_polo = e.fk_id_polo "
                . " JOIN maker_escola s ON s.fk_id_polo = p.id_polo AND s.sede = 1 "
                . " JOIN instancia si on si.id_inst = s.fk_id_inst "
                . " where e.fk_id_inst = $id_inst";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        if ($array) {
            return $array;
        } else {
            return;
        }
    }

    public function dash($id_ciclo) {
        
        $setup = sql::get(['maker_setup', 'ge_periodo_letivo'], '*', null, 'fetch');
        $id_pl = $setup['fk_id_pl_matr'];
        $sql = "SELECT `id_ta`, `dt_matricula` FROM maker_gt_turma_aluno ta "
                . " JOIN maker_gt_turma t on t.id_turma = ta.fk_id_turma "
                . " and fk_id_pl = $id_pl "
                . " and t.fk_id_ciclo in (". implode(', ', $id_ciclo).")";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            @$dash[substr($v['dt_matricula'], 0, 10)]++;
        }
        if (!empty($dash)) {
            return $dash;
        } else {
            return [];
        }
    }

    public function poloComEscolas() {

        $sql = "SELECT "
                . " ei.id_inst, ei.n_inst, si.n_inst as sede, e.cota_m, e.cota_t, e.cota_n, p.n_polo, p.id_polo, p.fk_id_inst_maker, e.sede "
                . " FROM maker_escola e "
                . " JOIN instancia ei on ei.id_inst = e.fk_id_inst "
                . " JOIN maker_polo p on p.id_polo = e.fk_id_polo "
                . " JOIN maker_escola s ON s.fk_id_polo = p.id_polo AND s.sede = 1 "
                . " JOIN instancia si on si.id_inst = s.fk_id_inst "
                . " order by id_polo asc, e.sede desc";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $p[$v['id_polo']]['nome'] = $v['n_polo'];
            $p[$v['id_polo']]['fk_id_inst_maker'] = $v['fk_id_inst_maker'];
            if ($v['sede'] == 1) {
                $p[$v['id_polo']]['sede'] = $v['n_inst'];
            } else {
                $p[$v['id_polo']]['sede'] = null;
            }
            $p[$v['id_polo']]['escolas'][] = $v;
        }

        return $p;
    }

    public function turmasPolo($id_polo = null, $periodo = null, $diaSem = null, $id_pl = null) {
        if (empty($id_pl)) {
            $id_pl = $this->setup();
        }
        $fk_id_inst_maker = sql::get('maker_polo', 'fk_id_inst_maker', ['id_polo' => $id_polo], 'fetch')['fk_id_inst_maker'];
        if ($periodo) {
            $periodo = "AND periodo LIKE '$periodo' ";
        }
        if ($diaSem) {
            $diaSem = " AND substring(codigo, 3, 1) like '$diaSem' ";
        }
        $sql = "SELECT id_turma, n_turma FROM maker_gt_turma "
                . "WHERE fk_id_inst = $fk_id_inst_maker "
                . " and fk_id_pl = $id_pl "
                . $periodo
                . $diaSem;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            return toolErp::idName($array);
        }
    }

    public function alunoListAutoriza($id_inst) {
        $setup = sql::get('maker_setup', '*', null, 'fetch');
        $fields = ""
                . " po.n_polo Polo, "
                . " p.id_pessoa RSE, "
                . " p.n_pessoa Nome, p.sexo, "
                . " t.n_turma `Turma Maker`, "
                . " concat(substring(t.codigo, 3,1), 'ª') `Dia da Semana` , "
                . " concat(h.incio, ' às ', h.fim) Horário, "
                . " i.n_inst `Escola de Origem`, "
                . " t2.n_turma `Turma de Origem`, "
                . " t2.periodo periodo_origem, "
                . " ta.porc_frequencia_ant ";
        $sql = "SELECT distinct $fields FROM maker_polo po "
                . " JOIN maker_gt_turma t on t.fk_id_inst = po.fk_id_inst_maker and t.fk_id_pl = " . $setup['fk_id_pl_matr']
                . " JOIN maker_horario h on h.periodo = substring(t.codigo, 2,1) AND h.aula = substring(t.codigo, 4,1) "
                . " JOIN maker_gt_turma_aluno ta on ta.fk_id_turma = t.id_turma and ta.confirma like 'MC' "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " JOIN ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa AND (ta2.fk_id_tas LIKE '0' or ta2.fk_id_tas is null) "
                . " JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma AND t2.fk_id_ciclo not in (32) "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t2.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN instancia i on i.id_inst = t2.fk_id_inst "
                . " WHERE t2.fk_id_inst = $id_inst "
                . " order by p.n_pessoa, i.n_inst, t.n_turma ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function alunoList($id_inst) {
        $fields = ""
                . " po.n_polo Polo, "
                . " p.id_pessoa RSE, "
                . " p.n_pessoa Nome, p.sexo, "
                . " t.n_turma `Turma Maker`, "
                . " concat(substring(t.codigo, 3,1), 'ª') `Dia da Semana` , "
                . " concat(h.incio, ' às ', h.fim) Horário, "
                . " i.n_inst `Escola de Origem`, "
                . " t2.n_turma `Turma de Origem`";
        $sql = "SELECT distinct $fields FROM maker_polo po "
                . " JOIN maker_gt_turma t on t.fk_id_inst = po.fk_id_inst_maker "
                . " JOIN maker_horario h on h.periodo = substring(t.codigo, 2,1) AND h.aula = substring(t.codigo, 4,1) "
                . " JOIN maker_gt_turma_aluno ta on ta.fk_id_turma = t.id_turma and ta.confirma like 'MC' "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " JOIN ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa AND (ta2.fk_id_tas LIKE '0' or ta2.fk_id_tas is null) "
                . " JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma AND t2.fk_id_ciclo not in (32) "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t2.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN instancia i on i.id_inst = t2.fk_id_inst "
                . " WHERE po.id_polo = ( "
                . " SELECT fk_id_polo FROM maker_escola "
                . " WHERE fk_id_inst = $id_inst "
                . " ) "
                . " order by p.n_pessoa, i.n_inst, t.n_turma ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function alunoChamada($id_turma) {
        $sql = "SELECT "
                . " p.id_pessoa, p.n_pessoa, ta.confirma "
                . " FROM maker_gt_turma_aluno ta "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " WHERE `fk_id_turma` = $id_turma "
                . " ORDER by ta.confirma, p.n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function faltou2Aulas($id_polo, $id_pl, $id_inst) {
        $mongo = new mongoCrude('Maker');
        $alu = $mongo->query('presece_' . $id_pl, ['id_polo' => (string) $id_polo]);

        foreach ($alu as $v) {
            if ($v->data < '2022-03-20') {
                foreach ($v->ch as $ky => $y) {
                    if ($y == 0) {
                        @$con[$ky]++;
                        if ($con[$ky] >= 2) {
                            $falta2[$ky] = $ky;
                        }
                    }
                }
            }
        }
        if (!empty($falta2)) {
            $sql = "SELECT "
                    . " p.id_pessoa, p.n_pessoa, t.n_turma, t2.n_turma as turmaOrigem, ta.confirma, ta.id_ta "
                    . " FROM maker_gt_turma_aluno ta "
                    . " JOIN maker_gt_turma t on t.id_turma = ta.fk_id_turma "
                    . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                    . " join ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa and (ta2.fk_id_tas = 0 or ta2.fk_id_tas is null) "
                    . " join ge_turmas t2 on t2.id_turma = ta2.fk_id_turma and t2.fk_id_inst = $id_inst "
                    . " join ge_periodo_letivo pl on pl.id_pl = t2.fk_id_pl and pl.at_pl = 1 "
                    . " WHERE ta.fk_id_pessoa in (" . implode(', ', $falta2) . ") "
                    . " order by p.n_pessoa";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            return $array;
        }
    }

    public function falta($id_polo, $id_pl) {
        $mongo = new mongoCrude('Maker');
        $alu = $mongo->query('presece_' . $id_pl, ['id_polo' => (string) $id_polo]);
        if ($alu) {
            foreach ($alu as $v) {
                foreach ($v->ch as $ky => $y) {
                    if ($y == 0) {
                        @$con[$ky]++;
                    }
                }
            }
            if (!empty($con)) {
                return $con;
            }
        }
    }

    public function relatCriarTurmaPQP($id_polo, $id_pl, $periodo = null) {

        if ($periodo) {
            $periodo = " and periodo like '$periodo' ";
        }
        $fk_id_inst_maker = sql::get('maker_polo', 'fk_id_inst_maker', ['id_polo' => $id_polo], 'fetch')['fk_id_inst_maker'];

        $sql = "SELECT t.fk_id_ciclo, ta.transporte, t.fk_id_inst, ta.fk_id_pessoa "
                . " FROM maker_gt_turma t "
                . " JOIN maker_gt_turma_aluno ta on ta.fk_id_turma = t.id_turma "
                . " and t.fk_id_inst = $fk_id_inst_maker "
                . " and t.fk_id_pl = $id_pl "
                . $periodo;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $ids = array_column($array, 'fk_id_pessoa');
        if ($ids) {
            $sql = "select t.fk_id_inst, ta.fk_id_pessoa from ge_turma_aluno ta "
                    . " join ge_turmas t on t.id_turma = ta.fk_id_turma and ta.fk_id_pessoa in (" . implode(', ', $ids) . ") and t.fk_id_ciclo != 32 "
                    . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1 ";
            $query = pdoSis::getInstance()->query($sql);
            $aluIds = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($aluIds as $v) {
                $pi[$v['fk_id_pessoa']] = $v['fk_id_inst'];
            }
        } else {
            $pi = [];
        }

        foreach ($array as $v) {
            @$ciclo['transp'][$pi[$v['fk_id_pessoa']]][$v['fk_id_ciclo']][$v['transporte']]++;
            @$ciclo['total'][$v['fk_id_ciclo']]++;
            @$ciclo['esc'][$pi[$v['fk_id_pessoa']]][$v['fk_id_ciclo']]++;
        }

        return @$ciclo;
    }

    public function relatFerq($id_polo, $id_inst_sieb, $periodo, $id_mc, $transporte, $frequencia, $print = null) {
        $id_pl = $this->setup();

        if (empty($id_polo) && empty($id_inst_sieb)) {
            toolErp::alertModal('Selecione uma escola ou um polo');
            return;
        } elseif (!empty($id_inst_sieb)) {
            $esc = sqlErp::get('maker_escola', 'fk_id_polo', ['fk_id_inst' => $id_inst_sieb], 'fetch');
            if ($esc) {
                $id_polo = $esc['fk_id_polo'];
            } else {
                toolErp::alertModal('Esta ESCOLA não participa do Projeto MAKER');
                return;
            }
        }
        $id_inst_polo = sql::get('maker_polo', 'fk_id_inst_maker', ['id_polo' => $id_polo], 'fetch')['fk_id_inst_maker'];
        $ch = $this->frequeciaAluno();
        if ($transporte == 's') {
            $transporte = " and ma.transporte = 1 ";
        } elseif ($transporte == 'n') {
            $transporte = " and ma.transporte != 1 ";
        }
        if ($periodo) {
            $periodo = " and substring(t.n_turma, 2,1) = '$periodo' ";
        }
        if ($id_mc) {
            $id_mc = " and ci.id_mc = '$id_mc' ";
        }
        if ($id_inst_sieb) {
            $id_inst_sieb = " and t2.fk_id_inst = $id_inst_sieb ";
        }
        $fields = "p.id_pessoa , p.n_pessoa, i.n_inst, t.n_turma, ci.n_mc, ta.transporte";
        $sql = "SELECT "
                . $fields
                . " FROM maker_gt_turma t "
                . " join maker_gt_turma_aluno ta on ta.fk_id_turma = t.id_turma and t.fk_id_inst = $id_inst_polo and t.fk_id_pl = $id_pl"
                . " JOIN ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa AND ta2.fk_id_tas = 0 "
                . " JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t2.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN instancia i on i.id_inst = t2.fk_id_inst "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " join maker_ciclo ci on ci.id_mc = t.fk_id_ciclo "
                . $periodo
                . $id_mc
                . $transporte
                . $id_inst_sieb
                . " order by p.n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $k => $v) {
                $f = @$ch[$v['id_pessoa']];
                if (!empty($f[1])) {
                    $porc = ($f[1] / (intval(@$f[0]) + $f[1]));
                } else {
                    $porc = 0;
                }
                if (empty($print)) {
                    if ($porc < 0.7) {
                        $cor = 'red';
                    } else {
                        $cor = 'blue';
                    }
                    $array[$k]['frenq'] = '<span style="font-weight: bold; color: ' . $cor . '">' . ceil($porc * 100) . '%</span>';
                } else {
                    $array[$k]['frenq'] = (string) ceil($porc * 100);
                }
                $array[$k]['transporte'] = toolErp::simnao($v['transporte']);

                if ($frequencia && $porc < ($frequencia / 10)) {
                    unset($array[$k]);
                }
            }
        } else {
            toolErp::alertModal('Dados não encontrados');
        }
        $dados['alunos'] = @$array;
        $dados['geral']['id_polo'] = $id_polo;

        return $dados;
    }

    public function setup() {
        return sql::get('maker_setup', 'fk_id_pl', ['id_setup' => 1], 'fetch')['fk_id_pl'];
    }

    public function frequeciaAluno($id_pl = null) {
        if (empty($id_pl)) {
            $id_pl = $this->setup();
        }
        $mongo = new mongoCrude('Maker');
        $frenq = $mongo->query('presece_' . $id_pl);
        foreach ($frenq as $v) {
            if (!empty($v->ch)) {
                foreach ($v->ch as $id_pessoa => $fp) {
                    @$ch[$id_pessoa][$fp]++;
                }
            }
        }

        return @$ch;
    }

############################# nova versão ######################################

    public function transporte($id_inst = null) {

        $id_pl = $this->setup();
        if ($id_inst) {
            if ($id_inst) {
                $polo = $this->escolaPolo($id_inst);
                $id_polo = $polo['id_polo'];
                $fk_id_inst_maker = $polo['fk_id_inst_maker'];
                $id_inst = " and ta2.fk_id_inst = $id_inst";
                $and = " and t.fk_id_inst = $fk_id_inst_maker ";
            } else {
                $and = null;
                $id_inst = " and t2.fk_id_inst = $id_inst ";
            }
        } else {
            $and = null;
        }
        $fields = " distinct "
                . " po.n_polo Polo, "
                . " i.n_inst `Escola de Origem`, "
                . " t2.n_turma `Turma de Origem`, "
                . " concat(substring(t.codigo, 3,1), 'ª') `Dia da Semana` , "
                . " concat(h.incio, ' às ', h.fim) Horário, "
                . " t.periodo Período, "
                . " t.n_turma `Turma Maker`, "
                . " p.id_pessoa RSE, "
                . " p.n_pessoa Nome, "
                . " concat(en.logradouro, ', ', en.num, ' - ', en.bairro) Endereço, "
                . " ta.transporte Transporte";
        $sql = "SELECT $fields "
                . "FROM maker_polo po "
                . " JOIN maker_gt_turma t on t.fk_id_inst = po.fk_id_inst_maker and t.fk_id_pl = $id_pl $and "
                . " JOIN maker_horario h on h.periodo = substring(t.codigo, 2,1) AND h.aula = substring(t.codigo, 4,1) "
                . " JOIN maker_gt_turma_aluno ta on ta.fk_id_turma = t.id_turma "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " JOIN ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa AND (ta2.fk_id_tas LIKE '0' or ta2.fk_id_tas is null) "
                . " JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma AND t2.fk_id_ciclo not in (32) "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t2.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN instancia i on i.id_inst = t2.fk_id_inst "
                . " join endereco en on en.fk_id_pessoa = ta.fk_id_pessoa "
                . " where 1 "
                . $id_inst
                . " order by po.id_polo, i.n_inst, t.n_turma ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function transporteEsc($id_inst = null) {

        $id_pl = $this->setup();
        if ($id_inst) {
            $polo = $this->escolaPolo($id_inst);
            $id_polo = $polo['id_polo'];
            $fk_id_inst_maker = $polo['fk_id_inst_maker'];
            $id_inst = "where ta2.fk_id_inst = $id_inst";
            $and = " and t.fk_id_inst = $fk_id_inst_maker ";
        } else {
            $and = null;
        }
        $sql = "SELECT ta.fk_id_pessoa, t.codigo "
                . " FROM maker_gt_turma t "
                . " JOIN maker_gt_turma_aluno ta on ta.fk_id_turma = t.id_turma and t.fk_id_pl = $id_pl and ta.transporte = 1 $and "
                . " JOIN ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa AND (ta2.fk_id_tas LIKE '0' or ta2.fk_id_tas is null) "
                . " JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma AND t2.fk_id_ciclo not in (32) "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t2.fk_id_pl AND pl.at_pl = 1 "
                . $id_inst;
        $query = pdoSis::getInstance()->query($sql);
        $array_ = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array_ as $v) {
            $array[$v['fk_id_pessoa']] = $v;
        }
        if (!empty($array)) {
            foreach ($array as $v) {
                @$ali['aula'][substr($v['codigo'], 1, 1)][substr($v['codigo'], 2, 1)][substr($v['codigo'], 3, 1)]++;
                @$ali['periodo'][substr($v['codigo'], 1, 1)][substr($v['codigo'], 2, 1)]++;
                @$ali['totalDia'][substr($v['codigo'], 2, 1)]++;
            }
        }

        return @$ali;
    }

    public function alimento($id_inst = null) {
        $id_pl = $this->setup();
        if ($id_inst) {
            $id_inst = "where t.fk_id_inst = $id_inst";
        }
        $sql = "SELECT ta.fk_id_pessoa, t.codigo "
                . "FROM maker_gt_turma t "
                . " JOIN maker_gt_turma_aluno ta on ta.fk_id_turma = t.id_turma "
                . " and t.fk_id_pl = $id_pl "
                . " JOIN ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa AND ta2.fk_id_tas = '0' "
                . " JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma AND t2.fk_id_ciclo not in (32) "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t2.fk_id_pl AND pl.at_pl = 1 "
                . $id_inst;

        $query = pdoSis::getInstance()->query($sql);
        $array_ = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array_ as $v) {
            $array[$v['fk_id_pessoa']] = $v;
        }
        foreach ($array as $v) {
            @$ali['aula'][substr($v['codigo'], 1, 1)][substr($v['codigo'], 2, 1)][substr($v['codigo'], 3, 1)]++;
            @$ali['periodo'][substr($v['codigo'], 1, 1)][substr($v['codigo'], 2, 1)]++;
            @$ali['totalDia'][substr($v['codigo'], 2, 1)]++;
            @$ali['totalGeral']++;
        }

        return @$ali;
    }

    public function interna($id_inst, $ordem = 'p.n_pessoa', $id_pl = null) {
        if (empty($id_pl)) {
            $id_pl = $this->setup();
        }

        $fields = " distinct "
                . " t2.n_turma `Turma de Origem`, "
                . " concat(substring(t.codigo, 3,1), 'ª') `Dia da Semana` , "
                . " concat(h.incio, ' às ', h.fim) Horário, "
                . " t.periodo Período, ta.transporte, "
                . " t.n_turma `Turma Maker`, "
                . " p.id_pessoa RSE, "
                . " p.n_pessoa Nome, id_polo as polo, po.n_polo ";
        $sql = "SELECT $fields "
                . " FROM  maker_gt_turma t "
                . " join maker_polo po on po.fk_id_inst_maker = t.fk_id_inst "
                . " JOIN maker_horario h on h.periodo = substring(t.codigo, 2,1) AND h.aula = substring(t.codigo, 4,1) and t.fk_id_pl = $id_pl"
                . " JOIN maker_gt_turma_aluno ta on ta.fk_id_turma = t.id_turma "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " JOIN ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa AND (ta2.fk_id_tas LIKE '0' or ta2.fk_id_tas is null) "
                . " JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma AND t2.fk_id_ciclo not in (32) and t2.fk_id_inst = $id_inst "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t2.fk_id_pl AND pl.at_pl = 1 "
                . " order by $ordem ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function permuta() {
        $id_pessoa1 = filter_input(INPUT_POST, 'id_pessoa1', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa2 = filter_input(INPUT_POST, 'id_pessoa2', FILTER_SANITIZE_NUMBER_INT);
        $id_turma_1 = filter_input(INPUT_POST, 'id_turma_1', FILTER_SANITIZE_NUMBER_INT);
        $id_turma_2 = filter_input(INPUT_POST, 'id_turma_2', FILTER_SANITIZE_NUMBER_INT);
        $sql = " UPDATE maker_gt_turma_aluno "
                . " SET fk_id_turma = '$id_turma_2' "
                . "where fk_id_pessoa = $id_pessoa1 "
                . " and fk_id_turma = $id_turma_1";
        pdoSis::getInstance()->query($sql);
        $sql = " UPDATE maker_gt_turma_aluno "
                . " SET fk_id_turma = '$id_turma_1' "
                . "where fk_id_pessoa = $id_pessoa2 "
                . " and fk_id_turma = $id_turma_2";
        pdoSis::getInstance()->query($sql);
        toolErp::alert("Concluído");
    }

    public function escolasMaker() {
        $sql = "SELECT i.id_inst, i.n_inst FROM maker_escola e JOIN instancia i on i.id_inst = e.fk_id_inst";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return toolErp::idName($array);
    }

    public function alunosNg($id_polo = null, $id_mc = null, $id_turma = null, $periodo = null, $diaSem = null, $fields = null, $id_pl = null) {
        if (empty($id_pl)) {
            $id_pl = $this->setup();
        }
        if (empty($fields)) {
            $fields = " distinct po.id_polo, i.id_inst, po.n_polo, mc.n_mc, p.id_pessoa, p.n_pessoa, p.dt_nasc, i.n_inst, t.n_turma, t.id_turma, t.periodo, ta.id_ta, substring(t.codigo, 3, 1)";
        }
        if ($id_polo) {
            $id_polo = " and po.id_polo = $id_polo ";
        }
        if ($id_mc) {
            $id_mc = " and mc.id_mc = $id_mc ";
        }
        if ($id_turma) {
            $id_turma = " and t.id_turma = $id_turma ";
        }
        if ($periodo) {
            $periodo = " and t.periodo = '$periodo' ";
        }
        if ($diaSem) {
            $diaSem = " and substring(t.codigo, 3,1) like '$diaSem' ";
        }
        $sql = "SELECT "
                . " $fields "
                . " from pessoa p "
                . " JOIN maker_gt_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa "
                . " JOIN maker_gt_turma t on t.id_turma = ta.fk_id_turma and t.fk_id_pl = $id_pl "
                . " join maker_ciclo mc on mc.id_mc = t.fk_id_ciclo "
                . " join ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa and ta2.fk_id_tas = 0 "
                . " join ge_turmas t2 on t2.id_turma = ta2.fk_id_turma and t2.fk_id_ciclo != 32 "
                . " join ge_periodo_letivo pl2 on pl2.id_pl = t2.fk_id_pl and pl2.at_pl = 1 "
                . " join instancia i on i.id_inst = t2.fk_id_inst "
                . " join maker_polo po on po.fk_id_inst_maker =  t.fk_id_inst "
                . "where 1 "
                . $id_polo
                . $id_mc
                . $id_turma
                . $periodo
                . $diaSem
                . " order by n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($array) {
            return $array;
        }
    }

    public function alunosEscNg($id_inst, $id_mc = null, $id_turma = null, $periodo = null, $diaSem = null, $fields = null) {
        $id_pl = $this->setup();
        $sql = "select ta.fk_id_pessoa from ge_turma_aluno ta "
                . " join ge_turmas t on t.id_turma = ta.fk_id_turma and ta.fk_id_tas = 0 and t.fk_id_inst = $id_inst "
                . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1 ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            $idsPessoa = array_column($array, 'fk_id_pessoa');
        } else {
            $idsPessoa = [0];
        }
        if (empty($fields)) {
            $fields = "p.id_pessoa, p.n_pessoa, p.dt_nasc, mc.n_mc, "
                    . " t.n_turma, t.id_turma, t.periodo, ta.id_ta,"
                    . "substring(t.codigo, 3, 1) dia, ta.confirma, ta.transporte, "
                    . " po.id_polo, po.n_polo ";
        }
        if ($id_mc) {
            $id_mc = " and mc.id_mc = $id_mc ";
        }
        if ($id_turma) {
            $id_turma = " and t.id_turma = $id_turma ";
        }
        if ($periodo) {
            $periodo = " and t.periodo = '$periodo' ";
        }
        if ($diaSem) {
            $diaSem = " and substring(t.codigo, 3,1) like '$diaSem' ";
        }

        $sql = "SELECT "
                . " $fields "
                . " FROM maker_gt_turma_aluno ta "
                . " join pessoa p on p.id_pessoa = ta.fk_id_pessoa and ta.fk_id_pessoa in (" . implode(', ', $idsPessoa) . ")"
                . " JOIN maker_gt_turma t on t.id_turma = ta.fk_id_turma and t.fk_id_pl = $id_pl "
                . " join maker_ciclo mc on mc.id_mc = t.fk_id_ciclo "
                . " join maker_polo po on po.fk_id_inst_maker = t.fk_id_inst "
                . $id_mc
                . $id_turma
                . $periodo
                . $diaSem
                . " order by p.n_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($array) {
            return $array;
        }
    }

    public function turmasPoloNg($id_polo = null, $periodo = null, $diaSem = null, $id_pl = null) {
        if (empty($id_pl)) {
            $id_pl = $this->setup();
        }
        $fk_id_inst_maker = sql::get('maker_polo', 'fk_id_inst_maker', ['id_polo' => $id_polo], 'fetch')['fk_id_inst_maker'];
        if ($periodo) {
            $periodo = "AND periodo LIKE '$periodo' ";
        }
        if ($diaSem) {
            $diaSem = " AND substring(codigo, 3, 1) like '$diaSem' ";
        }
        $sql = "SELECT id_turma, n_turma, transporte, fk_id_inst_sieb, fk_id_ciclo FROM maker_gt_turma "
                . "WHERE fk_id_inst = $fk_id_inst_maker "
                . " and fk_id_pl = $id_pl "
                . $periodo
                . $diaSem
                . " order by n_turma ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($array) {
            foreach ($array as $v) {
                $t[$v['id_turma']] = $v;
            }
            return $t;
        }
    }

    public function alunoTurmaNg($id_ta) {
        $id_pl = $this->setup();
        $fields = "p.id_pessoa, p.n_pessoa, p.dt_nasc, mc.n_mc,"
                . " t.n_turma, t.id_turma, "
                . "t.periodo, ta.id_ta, po.n_polo, po.id_polo, mc.id_mc, ta.transporte";
        $sql = "SELECT "
                . " $fields "
                . " FROM  pessoa p "
                . " JOIN maker_gt_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa and id_ta = $id_ta"
                . " JOIN maker_gt_turma t on t.id_turma = ta.fk_id_turma and t.fk_id_pl = $id_pl "
                . " join maker_polo po on po.fk_id_inst_maker = t.fk_id_inst "
                . " join maker_ciclo mc on mc.id_mc = t.fk_id_ciclo ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    public function escolaAlunofrequ($id_pessoa) {
        $sql = "select i.n_inst, i.id_inst from ge_turma_aluno ta "
                . " join ge_turmas t on t.id_turma = ta.fk_id_turma and ta.fk_id_pessoa = $id_pessoa and t.fk_id_ciclo != 32 "
                . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1 "
                . " join instancia i on i.id_inst = t.fk_id_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    public function transferirAluno() {
        $ins = $_POST[1];
        $id_poloSet = filter_input(INPUT_POST, 'id_poloSet', FILTER_SANITIZE_NUMBER_INT);
        $ins['fk_id_inst'] = sql::get('maker_polo', 'fk_id_inst_maker', ['id_polo' => $id_poloSet], 'fetch')['fk_id_inst_maker'];
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $sql = "SELECT COUNT(fk_id_pessoa) as ct FROM `maker_gt_turma_aluno` WHERE fk_id_turma =  " . $ins['fk_id_turma'];
        $query = pdoSis::getInstance()->query($sql);
        $chArr = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($chArr['ct'])) {
            $ins['chamada'] = $chArr['ct'] + 1;
        } else {
            $ins['chamada'] = 1;
        }
        $pdp['fk_id_pessoa_lac'] = toolErp::id_pessoa();
        $pdp['fk_id_pessoa_alu'] = $id_pessoa;
        $pdp['fk_id_ta'] = filter_input(INPUT_POST, 'fk_id_ta', FILTER_SANITIZE_NUMBER_INT);
        $this->db->ireplace('maker_pqp', $pdp, 1);
        $this->db->ireplace('maker_gt_turma_aluno', $ins);
    }

    public function desativar() {

        $id_ta = filter_input(INPUT_POST, 'id_ta', FILTER_SANITIZE_NUMBER_INT);
        $ins['id_ta'] = $id_ta;
        $ta = sql::get('maker_gt_turma_aluno', '*', ['id_ta' => $id_ta], 'fetch');

        $this->db->ireplace('maker_gt_turma_aluno_excluidos', $ta, 1);
        $this->db->delete('maker_gt_turma_aluno', 'id_ta', $id_ta);
    }

    public function alunoTurmaEsc($id_pessoa) {
        $sql = "SELECT p.id_pessoa, p.n_pessoa, p.dt_nasc, t.n_turma, t.id_turma, t.periodo, p.sexo FROM pessoa p "
                . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa AND id_pessoa = $id_pessoa "
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma AND t.fk_id_ciclo != 32 "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 ";
        $query = pdoSis::getInstance()->query($sql);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function matriculaAluno() {
        $ins = $_POST[1];
        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        $id_inst_sieb = filter_input(INPUT_POST, 'id_inst_sieb', FILTER_SANITIZE_NUMBER_INT);
        $id_poloSet = filter_input(INPUT_POST, 'id_poloSet', FILTER_SANITIZE_NUMBER_INT);
        $ins['fk_id_inst'] = sql::get('maker_polo', 'fk_id_inst_maker', ['id_polo' => $id_poloSet], 'fetch')['fk_id_inst_maker'];
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $turma = sql::get('maker_gt_turma', '*', ['id_turma' => $ins['fk_id_turma']], 'fetch');
        $this->db->ireplace('maker_gt_turma_aluno', $ins);
    }

    public function dadosInscr() {
        $setup = sql::get(['maker_setup', 'ge_periodo_letivo'], '*', null, 'fetch');
        $id_pl = $setup['fk_id_pl_matr'];
        $per = ['M' => 'Manhã', 'T' => 'Tarde'];

        $sql = "select distinct ta.fk_id_pessoa, "
                . " t2.fk_id_inst, po.id_polo, c.id_mc, t.periodo, c.n_mc "
                . " from maker_gt_turma_aluno ta "
                . " join maker_gt_turma t on t.id_turma = ta.fk_id_turma  and t.fk_id_pl = $id_pl  "
                . " join maker_ciclo c on c.id_mc = t.fk_id_ciclo "
                . " join ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa and ta2.fk_id_tas = 0 "
                . " join ge_turmas t2 on t2.id_turma = ta2.fk_id_turma and t2.fk_id_ciclo != 32 "
                . " join ge_periodo_letivo pl2 on pl2.id_pl = t2.fk_id_pl and pl2.at_pl = 1 "
                . " join maker_polo po on po.fk_id_inst_maker = t.fk_id_inst ";

        $query = pdoSis::getInstance()->query($sql);
        $array_ = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($array_) {
            foreach ($array_ as $v) {
                $array[$v['fk_id_pessoa']] = $v;
            }
            foreach ($array as $v) {
                if (empty($matriculados) || $v['fk_id_as'] == 1) {
                    @$d['esc'][$v['fk_id_inst']][$per[$v['periodo']]][$v['n_mc']][1]++;
                    @$d['rede'][$per[$v['periodo']]][$v['n_mc']][1]++;
                    @$d['per'][$per[$v['periodo']]][$v['n_mc']]++;
                }
            }
        }
        if (!empty($d)) {
            return $d;
        }
    }

    public function freqGraf($periodo = null) {
        $id_pl = $this->setup();
        $mongo = new mongoCrude('Maker');
        $frenq = $mongo->query('presece_' . $id_pl);

        foreach ($frenq as $v) {
            if (!empty($v->ch)) {
                $f = (array) $v->ch;
                @$tt[$v->data][$v->id_polo] += count($f);

                foreach ($f as $vf) {
                    if ($periodo) {
                        if (substr($v->n_turma, 1, 1) == $periodo) {
                            @$ch[$v->data][$v->id_polo] += $vf;
                        }
                    } else {
                        @$ch[$v->data][$v->id_polo] += $vf;
                    }
                }
            }
            if (!empty($ch)) {
                foreach ($ch as $data => $v) {
                    foreach ($v as $id_polo => $veio) {
                        $cht[$data][$id_polo] = intval((($veio / $tt[$data][$id_polo]) * 100), 2);
                    }
                }
            }
        }

        return @$cht;
    }

    public function freqCurGraf($periodo = null) {
        $id_pl = $this->setup();
        $mongo = new mongoCrude('Maker');
        $frenq = $mongo->query('presece_' . $id_pl);

        foreach ($frenq as $v) {
            if (!empty($v->ch)) {
                $f = (array) $v->ch;
                @$tt[$v->data][substr($v->n_turma, 0, 1)] += count($f);

                foreach ($f as $vf) {
                    if ($periodo) {
                        if (substr($v->n_turma, 1, 1) == $periodo) {
                            @$ch[$v->data][substr($v->n_turma, 0, 1)] += $vf;
                        }
                    } else {
                        @$ch[$v->data][substr($v->n_turma, 0, 1)] += $vf;
                    }
                }
            }
        }
        if (!empty($ch)) {
            foreach ($ch as $data => $v) {
                foreach ($v as $abrev => $veio) {
                    $cht[$data][$abrev] = intval((($veio / $tt[$data][$abrev]) * 100), 2);
                }
            }
        }
        return @$cht;
    }

    public function alunosEscola($id_inst, $id_pl = null) {
        $sql = "SELECT distinct a.*, p.n_pessoa, p.id_pessoa, ci.n_mc FROM maker_aluno a "
                . " join pessoa p on p.id_pessoa = a.fk_id_pessoa "
                . " join maker_ciclo ci on ci.id_mc = a.fk_id_mc "
                . " WHERE a.fk_id_inst = $id_inst "
                . " order by times_stamp ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $v) {
                @$alu['totalPeriodo'][$v['fk_id_as']][$v['periodo']]++;
                @$alu['total'][$v['fk_id_as']]++;
                $alu[$v['fk_id_as']][$v['periodo']][$v['id_pessoa']] = $v;
            }
        }
        if (!empty($alu)) {
            return $alu;
        }
    }

    public function autoriza() {
        ob_clean();
        ob_start();
        $ins['id_pessoa_aluno'] = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $ins['fk_id_pessoa_lanc'] = filter_input(INPUT_POST, 'fk_id_pessoa_lanc', FILTER_SANITIZE_NUMBER_INT);
        $ins['sim'] = filter_input(INPUT_POST, 'sim', FILTER_SANITIZE_NUMBER_INT);
        $ins['fk_id_mc'] = filter_input(INPUT_POST, 'id_mc', FILTER_SANITIZE_NUMBER_INT);
        $ins['td_aut'] = date("Y-m-d");
        if ($this->db->ireplace('maker_autoriza', $ins, 1)) {
            echo '1';
        }
    }

    public function transpote() {
        ob_clean();
        ob_start();
        $ins['id_pessoa_aluno'] = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $ins['fk_id_pessoa_lanc'] = filter_input(INPUT_POST, 'fk_id_pessoa_lanc', FILTER_SANITIZE_NUMBER_INT);
        $ins['transporte'] = filter_input(INPUT_POST, 'transporte', FILTER_SANITIZE_NUMBER_INT);
        $ins['fk_id_mc'] = filter_input(INPUT_POST, 'id_mc', FILTER_SANITIZE_NUMBER_INT);
        $ins['td_aut'] = date("Y-m-d");

        if ($this->db->ireplace('maker_autoriza', $ins, 1)) {
            echo '1';
        }
    }

}
