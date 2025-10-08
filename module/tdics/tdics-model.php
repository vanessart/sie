<?php

class tdicsModel extends MainModel {

    public $db;
    public static $sistema;
    public static $mongoDataBase;

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
        $this->db = new crud();
        // Configura o controlador
        $this->controller = $controller;

        self::$sistema = $this->controller->controller_name;
        self::$mongoDataBase = ucfirst(self::$sistema);

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
        if ($this->db->tokenCheck('tdics_turmaSet')) {
            $this->tdics_turmaSet();
        } elseif ($this->db->tokenCheck('plAtPv')) {
            $this->plAtPv();
        } elseif ($this->db->tokenCheck('novoAluno')) {
            $this->novoAluno();
        } elseif ($this->db->tokenCheck('chamadaSalvar')) {
            $this->chamadaSalvar();
        } elseif ($this->db->tokenCheck('apagaCh')) {
            $this->apagaCh();
        } elseif ($this->db->tokenCheck('tdics_cursoSet')) {
            $this->tdics_cursoSet();
        } elseif ($this->db->tokenCheck('novoPolo')) {
            $this->novoPolo();
        }
    }

    public function pl($id_pl = null) {
        if (!empty($id_pl)) {
            return $id_pl;
        }

        $sql = "SELECT id_pl FROM `" . self::$sistema . "_pl` WHERE `ativo` = 1 ";
        $query = pdoSis::getInstance()->query($sql);
        return $query->fetch(PDO::FETCH_ASSOC)['id_pl'];
    }

    public function tdics_cursoSet() {
        $ins = @$_POST[1];
        $id = $this->db->ireplace(self::$sistema . '_curso', $ins);
        if ($id) {
            $sql = "UPDATE " . self::$sistema . "_turma t JOIN " . self::$sistema . "_curso c on c.id_curso = t.fk_id_curso AND c.id_curso = $id SET t.n_turma = concat(c.abrev, substring(t.n_turma, 3));";
            $query = pdoSis::getInstance()->query($sql);
        }
    }

    public function apagaCh() {
        $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
        $data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_NUMBER_INT);
        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        $mongo = new mongoCrude( self::$mongoDataBase );
        $mongo->delete('presece_' . $id_pl, ['id_turma' => $id_turma, 'data' => $data]);
    }

    public function chamadaSalvar() {
        $ins = @$_POST;
        unset($ins['path']);
        unset($ins['formToken']);
        $mongo = new mongoCrude( self::$mongoDataBase );
        $mongo->update('presece_' . $ins['id_pl'], ['data' => $ins['data'], 'id_turma' => $ins['id_turma']], $ins, null, 1);
    }

    public function novoAluno() {
        $setup = sql::get(self::$sistema .  '_setup', '*', null, 'fetch');

        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        $id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
        $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $tem = sql::get([self::$sistema . '_turma_aluno', self::$sistema . '_turma', 'pessoa', self::$sistema . '_polo'], 'n_turma, n_pessoa, sexo, n_polo, periodo, dia_sem, horario', ['fk_id_pessoa' => $id_pessoa], 'fetch');
        if (toolErp::id_nilvel() == 8 && $tem) {
            toolErp::alertModal(strtoupper(toolErp::sexoArt($tem['sexo'])) . ' alun' . toolErp::sexoArt($tem['sexo']) . ' ' . $tem['n_pessoa'] . ' está matriculad' . toolErp::sexoArt($tem['sexo']) . ' na turma ' . $tem['n_turma'] . ' do Núcleo ' . $tem['n_polo']);
            return;
        }
        $sql = "SELECT COUNT(fk_id_pessoa) ct FROM `" . self::$sistema . "_turma_aluno` WHERE fk_id_turma = $id_turma ";
        $query = pdoSis::getInstance()->query($sql);
        $ct = $query->fetch(PDO::FETCH_ASSOC)['ct'];
        if ($ct >= $setup['qt_turma'] && toolErp::id_nilvel() == 8) {
            toolErp::alertModal("Não foi possível matricular. <br>Esta turma já está com $ct alunos");
            return;
        }
        $ins['fk_id_pessoa'] = $id_pessoa;
        $ins['fk_id_turma'] = $id_turma;
        if (toolErp::id_nilvel() == 10) {
            $alert = 1;
        } else {
            $alert = null;
        }
        $this->db->ireplace(self::$sistema . '_turma_aluno', $ins, $alert);
    }

    public function plAtPv() {
        $id_plAt = filter_input(INPUT_POST, 'id_plAt', FILTER_SANITIZE_NUMBER_INT);
        $id_plPv = filter_input(INPUT_POST, 'id_plPv', FILTER_SANITIZE_NUMBER_INT);
        $sql = "UPDATE `" . self::$sistema . "_pl` SET `ativo` = '0'";
        $query = pdoSis::getInstance()->query($sql);
        $sql = "UPDATE `" . self::$sistema . "_pl` SET `ativo` = '1' WHERE `id_pl` = " . intval($id_plAt);
        $query = pdoSis::getInstance()->query($sql);
        $sql = "UPDATE `" . self::$sistema . "_pl` SET `ativo` = '2' WHERE `id_pl` = " . intval($id_plPv);
        $query = pdoSis::getInstance()->query($sql);
        toolErp::alert("Concluido");
    }

    public function tdics_turmaSet() {
        $ins = @$_POST[1];
        foreach ($ins as $k => $v) {
            if (empty($v) && $k != 'id_turma') {
                toolErp::alert('Preencha todos os campos');
                return;
            }
        }
        $abrev = sql::get(self::$sistema . '_curso', '*', ['id_curso' => $ins['fk_id_curso']], 'fetch')['abrev'];

        $ins['n_turma'] = $abrev . $ins['periodo'] . $ins['dia_sem'] . $ins['horario'] . str_pad($ins['fk_id_polo'], 2, "0", STR_PAD_LEFT);
        if (empty($ins['id_turma'])) {
            $jaTem = sql::get(self::$sistema . '_turma', '*', ['n_turma' => $ins['n_turma'], 'fk_id_pl' => $ins['fk_id_pl']], 'fetch');
        }
        if (!empty($jaTem)) {
            toolErp::alert('A turma ' . $ins['n_turma'] . ' já existe');
        } else {
            $this->db->ireplace(self::$sistema . '_turma', $ins);
        }
    }

    public function diaSemana($dia = null) {
        $sem = [
            2 => 'Segunda',
            3 => 'Terça',
            4 => 'Quarta',
            5 => 'Quinta',
            6 => 'Sexta'
        ];
        if ($dia) {
            return $sem[$dia];
        } else {
            return $sem;
        }
    }

    public function alunoEsc($id_pl, $id_inst = null, $id_polo = null, $id_turma = null) {
        if ($id_polo) {
            $id_polo = " AND t.fk_id_polo = $id_polo ";
        }
        if ($id_turma) {
            $id_turma = " AND t.id_turma = $id_turma ";
        }
        if ($id_inst) {
            $id_inst = " AND t2.fk_id_inst = $id_inst ";
        }
        $sql = "SELECT "
                . " t.*, ta.id_ta , p.id_pessoa, p.n_pessoa, p.sexo, t2.n_turma as turmaEsc, i.id_inst, i.n_inst "
                . " FROM " . self::$sistema . "_turma_aluno ta "
                . " JOIN " . self::$sistema . "_turma t on t.id_turma = ta.fk_id_turma "
                . " AND t.fk_id_pl = $id_pl "
                . $id_polo
                . $id_turma
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " JOIN ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa "
                . " AND ta2.fk_id_tas = 0 "
                . " JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma "
                . $id_inst
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t2.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN instancia i on i.id_inst = t2.fk_id_inst "
                . " order by p.n_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function escolaTdics($id_pl = null) {
        if (!$id_pl) {
            $id_pl = $this->pl();
        }

        $sql = "SELECT "
                . " distinct i.id_inst, i.n_inst "
                . " FROM " . self::$sistema . "_turma_aluno ta "
                . " JOIN " . self::$sistema . "_turma t on t.id_turma = ta.fk_id_turma "
                . " AND t.fk_id_pl = $id_pl "
                . " JOIN ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa "
                . " AND ta2.fk_id_tas = 0 "
                . " JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t2.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN instancia i on i.id_inst = t2.fk_id_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return toolErp::idName($array);
    }

    public function alunos($id_pl, $id_polo = null, $id_inst = null) {
        if ($id_polo) {
            $id_polo = " AND t.fk_id_polo = $id_polo ";
        }
        if ($id_inst) {
            $id_inst = " AND t2.fk_id_inst = $id_inst ";
        }
        $sql = "SELECT "
                . " t.*, ta.id_ta , p.id_pessoa, p.n_pessoa, p.sexo, po.n_polo "
                . " FROM " . self::$sistema . "_turma_aluno ta "
                . " JOIN " . self::$sistema . "_turma t on t.id_turma = ta.fk_id_turma "
                . " JOIN " . self::$sistema . "_polo po on po.id_polo = t.fk_id_polo AND t.fk_id_pl = $id_pl "
                . $id_polo
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " JOIN ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa AND ta2.fk_id_tas = 0 "
                . " JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma AND t2.fk_id_ciclo <> 32"
                . $id_inst
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t2.fk_id_pl AND pl.at_pl = 1 "
                . " ORDER BY p.n_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function countAlunos($id_plo, $id_pl) {
        $sql = "SELECT t.id_turma, COUNT(ta.id_ta) ct FROM " . self::$sistema . "_turma_aluno ta "
                . " JOIN " . self::$sistema . "_turma t on t.id_turma = ta.fk_id_turma "
                . " AND t.fk_id_polo = $id_plo and t.fk_id_pl = $id_pl"
                . " GROUP BY t.id_turma ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $ct[$v['id_turma']] = $v['ct'];
        }
        if (!empty($ct)) {
            return $ct;
        } else {
            return [];
        }
    }

    public function countAlunosCurso($id_curso, $id_pl) {
        $sql = "SELECT t.id_turma, COUNT(ta.id_ta) ct FROM " . self::$sistema . "_turma_aluno ta "
                . " JOIN " . self::$sistema . "_turma t on t.id_turma = ta.fk_id_turma "
                . " AND t.fk_id_curso = $id_curso "
                . " GROUP BY t.id_turma ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $ct[$v['id_turma']] = $v['ct'];
        }
        if (!empty($ct)) {
            return $ct;
        } else {
            return [];
        }
    }

    public function aluno($id_pessoa) {
        $sql = "SELECT "
                . " ta.id_ta, t.n_turma, po.n_polo, p.id_pessoa, p.n_pessoa, p.sexo, p.dt_nasc, p.cpf "
                . " FROM " . self::$sistema . "_turma_aluno ta "
                . " JOIN " . self::$sistema . "_turma t on t.id_turma = ta.fk_id_turma AND ta.fk_id_pessoa = $id_pessoa "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " JOIN " . self::$sistema . "_polo po on po.id_polo = t.fk_id_polo "
                . " JOIN " . self::$sistema . "_pl pl on pl.id_pl = t.fk_id_pl and pl.ativo = 1 "
                . " ORDER BY n_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function alunosIds($idsPessoa, $id_curso = null, $id_polo = null, $id_sit = null) {
        if ($id_sit) {
            $id_sit = "and cc.fk_id_sit = $id_sit";
        } else {
            $id_sit = "and (cc.fk_id_sit = 0 or cc.fk_id_sit is null) ";
        }
        if ($id_curso) {
            $id_curso = " and t.fk_id_curso = $id_curso ";
        }
        if ($id_polo) {
            $id_polo = " and t.fk_id_polo = $id_polo ";
        }
        $ids = implode(', ', $idsPessoa);
        $sql = "SELECT "
                . " t.*, ta.id_ta , p.id_pessoa, p.n_pessoa, p.sexo, po.n_polo, "
                . " t2.n_turma as turmaEsc, i.id_inst, i.n_inst, t.n_turma, c.n_curso, cc.fk_id_sit "
                . " FROM " . self::$sistema . "_turma_aluno ta "
                . " JOIN " . self::$sistema . "_turma t on t.id_turma = ta.fk_id_turma and ta.fk_id_pessoa in ($ids) $id_curso $id_polo "
                . " join " . self::$sistema . "_polo po on po.id_polo = t.fk_id_polo "
                . " join  " . self::$sistema . "_curso c on c.id_curso = t.fk_id_curso "
                . " JOIN " . self::$sistema . "_pl pl on pl.id_pl = t.fk_id_pl and pl.ativo = 1 "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " JOIN ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa "
                . " AND ta2.fk_id_tas = 0 "
                . " JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma "
                . " JOIN ge_periodo_letivo pl2 on pl2.id_pl = t2.fk_id_pl AND pl2.at_pl = 1 "
                . " JOIN instancia i on i.id_inst = t2.fk_id_inst "
                . " left join " . self::$sistema . "_call_center cc on cc.id_pessoa = ta.fk_id_pessoa "
                . " where 1 "
                . $id_sit
                . " order by cc.fk_id_sit, n_pessoa ";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function alunoCallCenter($id_pessoa) {
        $sql = "SELECT "
                . " t.*, ta.id_ta , p.id_pessoa, p.n_pessoa, p.sexo, po.n_polo, "
                . " t2.n_turma as turmaEsc, i.id_inst, i.n_inst, t.n_turma, c.n_curso, cc.time_stamp,"
                . " cc.obs, cc.contactou, fk_id_sit "
                . " FROM " . self::$sistema . "_turma_aluno ta "
                . " JOIN " . self::$sistema . "_turma t on t.id_turma = ta.fk_id_turma and ta.fk_id_pessoa in ($id_pessoa) "
                . " join " . self::$sistema . "_polo po on po.id_polo = t.fk_id_polo "
                . " join  " . self::$sistema . "_curso c on c.id_curso = t.fk_id_curso "
                . " JOIN " . self::$sistema . "_pl pl on pl.id_pl = t.fk_id_pl and pl.ativo = 1 "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " JOIN ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa "
                . " AND ta2.fk_id_tas = 0 "
                . " JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma "
                . " JOIN ge_periodo_letivo pl2 on pl2.id_pl = t2.fk_id_pl AND pl2.at_pl = 1 "
                . " JOIN instancia i on i.id_inst = t2.fk_id_inst "
                . " left join " . self::$sistema . "_call_center cc on cc.id_pessoa = ta.fk_id_pessoa "
                . " order by n_pessoa ";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function turmaPolo($id_polo) {
        $sql = "SELECT t.*, c.n_curso FROM " . self::$sistema . "_turma t "
                . " JOIN " . self::$sistema . "_pl pl on pl.id_pl = t.fk_id_pl "
                . " JOIN " . self::$sistema . "_curso c on c.id_curso = t.fk_id_curso "
                . " AND pl.ativo = 1 "
                . " AND t.fk_id_polo = $id_polo "
                . " order by n_curso, periodo, horario ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function horario($fk_id_polo, $periodo, $horario) {
        $h = $this->getHorarios($fk_id_polo, $periodo, $horario);
        if (empty($h)) {
            return '';
        }

        $h = current($h);
        return $h['inicio'] .' às '. $h['termino'];
    }

    public function relatFerq($id_polo, $id_inst_sieb, $periodo, $id_curso, $frequencia, $print = null, $dataIni = null, $dataFim = null, $id_pl = null) {
        if (empty($id_pl)) {
            $id_pl = $this->pl();
        }
        if ($id_polo) {
            $id_polo_ = " and t.fk_id_polo = $id_polo ";
        } else {
            $id_polo_ = null;
        }

        if ( !in_array(toolErp::id_nilvel(), [2, 10]) ) {
            if (empty($id_polo) && empty($id_inst_sieb)) {
                toolErp::alertModal('Selecione uma escola ou um Núcleo');
                return;
            }
        }

        $ch = $this->frequeciaAluno($id_pl, NULL, $dataIni, $dataFim);

        if ($periodo) {
            $periodo = " and t.periodo = '$periodo' ";
        }
        if ($id_curso) {
            $id_curso = " and c.id_curso = '$id_curso' ";
        }
        if ($id_inst_sieb) {
            $id_inst_sieb = " and t2.fk_id_inst = $id_inst_sieb ";
        }

        $fields = "p.id_pessoa , p.n_pessoa, i.n_inst, t.n_turma, c.n_curso ";
        $sql = "SELECT "
                . $fields
                . " FROM " . self::$sistema . "_turma t "
                . " join " . self::$sistema . "_turma_aluno ta on ta.fk_id_turma = t.id_turma $id_polo_ and t.fk_id_pl = $id_pl"
                . " JOIN ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa AND ta2.fk_id_tas = 0 "
                . " JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t2.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN instancia i on i.id_inst = t2.fk_id_inst "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " join " . self::$sistema . "_curso c on c.id_curso = t.fk_id_curso "
                . $periodo
                . $id_curso
                . $id_inst_sieb
                . " order by p.n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $k => $v) {
                $idPessoa[$v['id_pessoa']] = $v['id_pessoa'];
            }
            $sql = "SELECT fk_id_pessoa, num, ddd FROM `telefones` WHERE `fk_id_pessoa` IN (" . implode(', ', $idPessoa) . ") AND `num` IS NOT NULL ORDER BY `num` ASC ";
            $query = pdoSis::getInstance()->query($sql);
            $at = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($at as $v) {
                @$tel[$v['fk_id_pessoa']] .= $v['ddd'] . '-' . $v['num'] . '<br />';
            }
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
                    $array[$k]['frenq'] = '<span style="font-weight: bold; color: ' . $cor . '">' . number_format($porc * 100, 2) . '%</span>';
                } else {
                    $array[$k]['frenq'] = (string) number_format($porc * 100, 2);
                }
                $array[$k]['tel'] = @$tel[$v['id_pessoa']];
                if ($frequencia && $porc < ($frequencia / 10)) {
                    unset($array[$k]);
                }
            }
        } else {
            ?>
            <div class="alert alert-danger" style="text-align: center">
                Não há alunos matrículados neste Núcleo
            </div>
            <?php
        }
        $dados['alunos'] = @$array;
        $dados['geral']['id_polo'] = $id_polo;

        return $dados;
    }

    public function frequeciaAluno($id_pl = null, $dataMaiorIgual = null, $dataIni = null, $dataFim = null) {
        if ($dataMaiorIgual) {
            $filter = ['data' => ['$gt' => $dataMaiorIgual]];
        } elseif (!empty($dataIni) && !empty($dataFim)) {
            $filter = ['data' => ['$gte' => $dataIni, '$lte' => $dataFim]]; 
        } else {
            $filter = [];
        }

        if (empty($id_pl)) {
            $id_pl = $this->pl();
        }
        $mongo = new mongoCrude(self::$mongoDataBase);
        $frenq = $mongo->query('presece_' . $id_pl, $filter);
        $ch = [];

        foreach ($frenq as $v) {
            if (!empty($v->ch)) {
                foreach ($v->ch as $id_pessoa => $fp) {
                    @$ch[$id_pessoa][$fp]++;
                }
            }
        }

        return $ch;
    }

    public function freqGraf($periodo = null) {
        $id_pl = $this->pl();
        $mongo = new mongoCrude(self::$mongoDataBase);
        $frenq = $mongo->query('presece_' . $id_pl);
        $cht = [];

        foreach ($frenq as $v) {
            if (!empty($v->ch)) {
                $f = (array) $v->ch;
                @$tt[$v->data][$v->id_polo] += count($f);

                foreach ($f as $vf) {
                    if ($periodo) {
                        if (substr($v->n_turma, 2, 1) == $periodo) {
                            @$ch[$v->data][$v->id_polo] += $vf;
                        }
                    } else {
                        @$ch[$v->data][$v->id_polo] += $vf;
                    }
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

        return $cht;
    }

    public function freqCurGraf($periodo = null) {
        $id_pl = $this->pl();
        $mongo = new mongoCrude(self::$mongoDataBase);
        $frenq = $mongo->query('presece_' . $id_pl);
        $cht = [];
        foreach ($frenq as $v) {
            if (!empty($v->ch)) {
                $f = (array) $v->ch;
                @$tt[$v->data][substr($v->n_turma, 0, 2)] += count($f);

                foreach ($f as $vf) {
                    if ($periodo) {
                        if (substr($v->n_turma, 2, 1) == $periodo) {
                            @$ch[$v->data][substr($v->n_turma, 0, 2)] += $vf;
                        }
                    } else {
                        @$ch[$v->data][substr($v->n_turma, 0, 2)] += $vf;
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
        }
        return $cht;
    }

    public function getInscricoesSQL($id_polo = null, $id_curso = null)
    {
        $sqlAux = '';
        if (!empty($id_polo)) {
            $sqlAux .= " AND tt.fk_id_polo = $id_polo ";
        }
        if (!empty($id_curso)) {
            $sqlAux .= " AND tt.fk_id_curso = $id_curso ";
        }

        $sql = "SELECT tt.*, tc.id_curso, tc.n_curso, tc.icone, tp.id_polo, tp.n_polo, DATE_FORMAT(ti.dt_inscricao, '%d/%m/%Y') AS data_inscricao, th.inicio, th.termino, ti.*, ts.qt_curso_aluno "
            . " FROM " . self::$sistema . "_inscricao ti "
            . " JOIN " . self::$sistema . "_turma tt ON ti.fk_id_turma = tt.id_turma "
            . " JOIN " . self::$sistema . "_curso tc ON tt.fk_id_curso = tc.id_curso "
            . " JOIN " . self::$sistema . "_pl pl ON tt.fk_id_pl = pl.id_pl "
            . " JOIN " . self::$sistema . "_polo tp ON tt.fk_id_polo = tp.id_polo "
            . " LEFT JOIN " . self::$sistema . "_horarios th ON tt.fk_id_polo = th.fk_id_polo AND tt.periodo = th.periodo AND tt.horario = th.horario "
            . " , " . self::$sistema . "_setup ts "
            . " WHERE pl.ativo = 1 $sqlAux ";
        return $sql;
    }

    public function getInscricoes($id_polo = null, $id_curso = null)
    {
        $sql =$this->getInscricoesSQL($id_polo, $id_curso);
        $array = pdoSis::fetch($sql);
        $r = [];
        if (!empty($array)) {
            $r = $array;
        }
        return $r;
    }

    public function getInscrByPolo($id_polo = null, $id_curso = null)
    {
        $sql = "SELECT n_polo, COUNT(id_inscricao) qtde "
            . " FROM (". $this->getInscricoesSQL($id_polo, $id_curso) .") AS z "
            . " GROUP BY n_polo";
        $array = pdoSis::fetch($sql);
        $r = [];
        if (!empty($array)) {
            $r = $array;
        }
        return $r;
    }

    public function getInscrByCurso($id_polo = null, $id_curso = null)
    {
        $sql = "SELECT n_curso, COUNT(id_inscricao) qtde "
            . " FROM (". $this->getInscricoesSQL($id_polo, $id_curso) .") AS z "
            . " GROUP BY n_curso";
        $array = pdoSis::fetch($sql);
        $r = [];
        if (!empty($array)) {
            $r = $array;
        }
        return $r;
    }

    public function getInscrByDia($id_polo = null, $id_curso = null)
    {
        $sql = "SELECT data_inscricao, COUNT(id_inscricao) qtde "
            . " FROM (". $this->getInscricoesSQL($id_polo, $id_curso) .") AS z "
            . " GROUP BY data_inscricao";
        $array = pdoSis::fetch($sql);
        $r = [];
        if (!empty($array)) {
            $r = $array;
        }
        return $r;
    }

    public function getVagas($id_polo = null, $id_curso = null, $id_turma = null)
    {
        $sqlAux = '';
        if (!empty($id_polo)) {
            $sqlAux .= " AND tt.fk_id_polo = $id_polo ";
        }
        if (!empty($id_curso)) {
            $sqlAux .= " AND tt.fk_id_curso = $id_curso ";
        }
        if (!empty($id_turma)) {
            $sqlAux .= " AND tt.id_turma = $id_turma ";
        }

        $sql = "SELECT (ts.qt_turma * COUNT(tt.id_turma)) as vagas, SUM(IFNULL((SELECT COUNT(id_inscricao) FROM " . self::$sistema . "_inscricao WHERE fk_id_turma = tt.id_turma),0)) AS inscritos, tp.id_polo, tp.n_polo, tc.id_curso, tc.n_curso, tt.periodo "
            . " FROM " . self::$sistema . "_turma tt "
            . " JOIN " . self::$sistema . "_curso tc ON tt.fk_id_curso = tc.id_curso "
            . " JOIN " . self::$sistema . "_pl pl ON tt.fk_id_pl = pl.id_pl "
            . " JOIN " . self::$sistema . "_polo tp ON tt.fk_id_polo = tp.id_polo "
            . " LEFT JOIN " . self::$sistema . "_horarios th ON tt.fk_id_polo = th.fk_id_polo AND tt.periodo = th.periodo AND tt.horario = th.horario "
            . " , " . self::$sistema . "_setup ts "
            . " WHERE pl.ativo = 1 $sqlAux "
            . " GROUP BY tp.n_polo, tc.n_curso, tt.periodo ";

        $array = pdoSis::fetch($sql);
        $r = [];
        if (!empty($array)) {
            $r = $array;
        }
        return $r;
    }

    public function getAviseMe($id_polo = null, $id_curso = null)
    {
        $sqlAux = '';
        if (!empty($id_polo)) {
            $sqlAux .= " AND tt.fk_id_polo = $id_polo ";
        }
        if (!empty($id_curso)) {
            $sqlAux .= " AND tt.fk_id_curso = $id_curso ";
        }

        $sql = "SELECT tt.*, tc.id_curso, tc.n_curso, tc.icone, tp.id_polo, tp.n_polo, DATE_FORMAT(ti.dt_avise, '%d/%m/%Y') AS data_avise, th.inicio, th.termino, ti.*, ts.qt_curso_aluno "
            . " FROM " . self::$sistema . "_avise_me ti "
            . " JOIN " . self::$sistema . "_turma tt ON ti.fk_id_turma = tt.id_turma "
            . " JOIN " . self::$sistema . "_curso tc ON tt.fk_id_curso = tc.id_curso "
            . " JOIN " . self::$sistema . "_pl pl ON tt.fk_id_pl = pl.id_pl "
            . " JOIN " . self::$sistema . "_polo tp ON tt.fk_id_polo = tp.id_polo "
            . " LEFT JOIN " . self::$sistema . "_horarios th ON tt.fk_id_polo = th.fk_id_polo AND tt.periodo = th.periodo AND tt.horario = th.horario "
            . " , " . self::$sistema . "_setup ts "
            . " WHERE pl.ativo = 1 $sqlAux ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $r = [];
        if (!empty($array)) {
            $r = $array;
        }
        return $r;
    }

    public function novoPolo() {
        if (!isset($_POST[1])) {
            toolErp::alertModal("Algo de errado não está certo. Os dados esperados não foram enviados.");
            return;
        }

        $horario = [];
        $ins = $_POST[1];

        if (!empty($ins['horario'])) {
            $horario = $ins['horario'];
            unset($ins['horario']);
        }

        $id_polo = $this->db->ireplace(self::$sistema . '_polo', $ins);

        if (!empty($id_polo)) {
            foreach ($horario as $periodo => $value) {
                foreach ($value as $horario => $horas) {
                    $insH = [
                        'id_horarios' => $horas['id_horarios']??null,
                        'fk_id_polo' => $id_polo,
                        'periodo' => $periodo,
                        'horario' => $horario,
                        'inicio' => $horas['inicio'],
                        'termino' => $horas['termino'],
                    ];

                    $this->db->ireplace(self::$sistema . '_horarios', $insH, 1);
                }
            }
        }
    }

    public function getHorarios($fk_id_polo=NULL, $periodo=NULL, $horario=NULL)
    {
        $sqlAux = '';
        if (!empty($fk_id_polo)) {
            $sqlAux .= " AND th.fk_id_polo = $fk_id_polo ";
        }
        if (!empty($periodo)) {
            $sqlAux .= " AND th.periodo = '$periodo' ";
        }
        if (!empty($horario)) {
            $sqlAux .= " AND th.horario = $horario ";
        }

        $sql = "SELECT tp.id_polo, tp.n_polo, th.* "
            . " FROM " . self::$sistema . "_horarios th "
            . " JOIN " . self::$sistema . "_polo tp ON th.fk_id_polo = tp.id_polo "
            . " WHERE th.ativo = 1 $sqlAux "
            . " ORDER BY th.horario, th.periodo";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $r = [];
        if (!empty($array)) {
            $r = $array;
        }
        return $r;
    }

    public function periodoLetivos($ativo = null) {
        $where = "";
        if (!empty($ativo)){
            if (is_array($ativo)){
                $where .= " AND ativo IN(" . implode(",", $ativo) . ") ";
            } else {
                $where .= " AND ativo = {$ativo}";
            }
        }

        $sql = "SELECT id_pl, n_pl FROM `" . self::$sistema . "_pl` WHERE 1 {$where} ";
        $query = pdoSis::getInstance()->query($sql);
        $r = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($r)) {
            $r = toolErp::idName($r);
        } else {
            $r = [];
        }

        return $r;
    }

    public function getPolos() {
        $sql = "SELECT id_polo, n_polo FROM `" . self::$sistema . "_polo` WHERE `ativo` = 1 ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($array)) {
            $r = toolErp::idName($array);
        } else {
            $r = [];
        }
        return $r;
    }

    public function turmasPolo($id_polo = null, $periodo = null, $diaSem = null, $id_pl = null, $fk_id_curso = null, $limit = null, $exibeTransp = false) {
        if (empty($id_pl)) {
            $id_pl = $this->pl();
        }
        if ($periodo) {
            $periodo = "AND periodo LIKE '$periodo' ";
        }
        if ($diaSem) {
            $diaSem = " AND dia_semana like '$diaSem' ";
        }
        if ($fk_id_curso) {
            $fk_id_curso = " AND fk_id_curso = '$fk_id_curso' ";
        }
        if (!empty($limit)) {
            $sql = "SELECT t.id_turma, count(`id_ta`) ct FROM " . self::$sistema . "_turma_aluno ta "
                    . " JOIN " . self::$sistema . "_turma t on t.id_turma = ta.fk_id_turma "
                    . " WHERE fk_id_pl = $id_pl "
                    . $periodo
                    . $fk_id_curso
                    . " GROUP BY `fk_id_turma` "
                    . " HAVING ct >= $limit ";
            $query = pdoSis::getInstance()->query($sql);
            $arr = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($arr) {
                $turmasSim = array_column($arr, 'id_turma');
            }
        }
        if (!empty($turmasSim)) {
            $turmas = " AND id_turma NOT IN (" . implode(', ', $turmasSim) . ") ";
        } else {
            $turmas = null;
        }

        $n_turma = 'n_turma';
        if (!empty($exibeTransp)) {
            $n_turma = " CONCAT(n_turma, IF(transporte = 1, ' (com transporte)', '')) AS n_turma ";
        }
        $sql = "SELECT id_turma, $n_turma FROM " . self::$sistema . "_turma "
                . " WHERE fk_id_polo = $id_polo "
                . " AND fk_id_pl = $id_pl "
                . $turmas
                . $periodo
                . $diaSem
                . $fk_id_curso;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            return toolErp::idName($array);
        }
    }
}
