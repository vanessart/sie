<?php

class tdicsModel extends MainModel {

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

    public function pl() {
        $sql = "SELECT id_pl FROM `tdics_pl` WHERE `ativo` = 1 ";
        $query = pdoSis::getInstance()->query($sql);
        return $query->fetch(PDO::FETCH_ASSOC)['id_pl'];
    }

    public function tdics_cursoSet() {
        $ins = @$_POST[1];
        $id = $this->db->ireplace('tdics_curso', $ins);
        if ($id) {
            $sql = "UPDATE tdics_turma t JOIN tdics_curso c on c.id_curso = t.fk_id_curso AND c.id_curso = $id SET t.n_turma = concat(c.abrev, substring(t.n_turma, 3));";
            $query = pdoSis::getInstance()->query($sql);
        }
    }

    public function apagaCh() {
        $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
        $data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_NUMBER_INT);
        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        $mongo = new mongoCrude('Tdics');
        $mongo->delete('presece_' . $id_pl, ['id_turma' => $id_turma, 'data' => $data]);
    }

    public function chamadaSalvar() {
        $ins = @$_POST;
        unset($ins['path']);
        unset($ins['formToken']);
        $mongo = new mongoCrude('Tdics');
        $mongo->update('presece_' . $ins['id_pl'], ['data' => $ins['data'], 'id_turma' => $ins['id_turma']], $ins, null, 1);
    }

    public function novoAluno() {
        $setup = sql::get('tdics_setup', '*', null, 'fetch');

        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        $id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
        $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $tem = sql::get(['tdics_turma_aluno', 'tdics_turma', 'pessoa', 'tdics_polo'], 'n_turma, n_pessoa, sexo, n_polo, periodo, dia_sem, horario', ['fk_id_pessoa' => $id_pessoa], 'fetch');
        if (toolErp::id_nilvel() == 8 && $tem) {
            toolErp::alertModal(strtoupper(toolErp::sexoArt($tem['sexo'])) . ' alun' . toolErp::sexoArt($tem['sexo']) . ' ' . $tem['n_pessoa'] . ' está matriculad' . toolErp::sexoArt($tem['sexo']) . ' na turma ' . $tem['n_turma'] . ' do Núcleo ' . $tem['n_polo']);
            return;
        }
        $sql = "SELECT COUNT(fk_id_pessoa) ct FROM `tdics_turma_aluno` WHERE fk_id_turma = $id_turma ";
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
        $this->db->ireplace('tdics_turma_aluno', $ins, $alert);
    }

    public function plAtPv() {
        $id_plAt = filter_input(INPUT_POST, 'id_plAt', FILTER_SANITIZE_NUMBER_INT);
        $id_plPv = filter_input(INPUT_POST, 'id_plPv', FILTER_SANITIZE_NUMBER_INT);
        $sql = "UPDATE `tdics_pl` SET `ativo` = '0'";
        $query = pdoSis::getInstance()->query($sql);
        $sql = "UPDATE `tdics_pl` SET `ativo` = '1' WHERE `tdics_pl`.`id_pl` = " . intval($id_plAt);
        $query = pdoSis::getInstance()->query($sql);
        $sql = "UPDATE `tdics_pl` SET `ativo` = '2' WHERE `tdics_pl`.`id_pl` = " . intval($id_plPv);
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
        $abrev = sql::get('tdics_curso', '*', ['id_curso' => $ins['fk_id_curso']], 'fetch')['abrev'];

        $ins['n_turma'] = $abrev . $ins['periodo'] . $ins['dia_sem'] . $ins['horario'] . str_pad($ins['fk_id_polo'], 2, "0", STR_PAD_LEFT);
        if (empty($ins['id_turma'])) {
            $jaTem = sql::get('tdics_turma', '*', ['n_turma' => $ins['n_turma'], 'fk_id_pl' => $ins['fk_id_pl']], 'fetch');
        }
        if (!empty($jaTem)) {
            toolErp::alert('A turma ' . $ins['n_turma'] . ' já existe');
        } else {
            $this->db->ireplace('tdics_turma', $ins);
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
                . " FROM tdics_turma_aluno ta "
                . " JOIN tdics_turma t on t.id_turma = ta.fk_id_turma "
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
                . " FROM tdics_turma_aluno ta "
                . " JOIN tdics_turma t on t.id_turma = ta.fk_id_turma "
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

    public function alunos($id_pl, $id_polo = null, $id_turma = null) {
        if ($id_polo) {
            $id_polo = " AND t.fk_id_polo = $id_polo ";
        }
        if ($id_turma) {
            $id_turma = " AND t.id_turma = $id_turma ";
        }
          $sql = "SELECT "
                . " t.*, ta.id_ta , p.id_pessoa, p.n_pessoa, p.sexo, po.n_polo "
                . " FROM tdics_turma_aluno ta "
                . " JOIN tdics_turma t on t.id_turma = ta.fk_id_turma "
                . " JOIN tdics_polo po on po.id_polo = t.fk_id_polo "
                . " AND t.fk_id_pl = $id_pl "
                . $id_polo
                . $id_turma
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " order by p.n_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function countAlunos($id_plo, $id_pl) {
        $sql = "SELECT t.id_turma, COUNT(ta.id_ta) ct FROM tdics_turma_aluno ta "
                . " JOIN tdics_turma t on t.id_turma = ta.fk_id_turma "
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
        $sql = "SELECT t.id_turma, COUNT(ta.id_ta) ct FROM tdics_turma_aluno ta "
                . " JOIN tdics_turma t on t.id_turma = ta.fk_id_turma "
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
                . " ta.id_ta, t.n_turma, po.n_polo, p.id_pessoa, p.n_pessoa, p.sexo "
                . " FROM tdics_turma_aluno ta "
                . " JOIN tdics_turma t on t.id_turma = ta.fk_id_turma AND `fk_id_pessoa` = $id_pessoa "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " JOIN tdics_polo po on po.id_polo = t.fk_id_polo "
                . " JOIN tdics_pl pl on pl.id_pl = t.fk_id_pl and pl.ativo = 1 "
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
                . " FROM tdics_turma_aluno ta "
                . " JOIN tdics_turma t on t.id_turma = ta.fk_id_turma and ta.fk_id_pessoa in ($ids) $id_curso $id_polo "
                . " join tdics_polo po on po.id_polo = t.fk_id_polo "
                . " join  tdics_curso c on c.id_curso = t.fk_id_curso "
                . " JOIN tdics_pl pl on pl.id_pl = t.fk_id_pl and pl.ativo = 1 "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " JOIN ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa "
                . " AND ta2.fk_id_tas = 0 "
                . " JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma "
                . " JOIN ge_periodo_letivo pl2 on pl2.id_pl = t2.fk_id_pl AND pl2.at_pl = 1 "
                . " JOIN instancia i on i.id_inst = t2.fk_id_inst "
                . " left join tdics_call_center cc on cc.id_pessoa = ta.fk_id_pessoa "
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
                . " FROM tdics_turma_aluno ta "
                . " JOIN tdics_turma t on t.id_turma = ta.fk_id_turma and ta.fk_id_pessoa in ($id_pessoa) "
                . " join tdics_polo po on po.id_polo = t.fk_id_polo "
                . " join  tdics_curso c on c.id_curso = t.fk_id_curso "
                . " JOIN tdics_pl pl on pl.id_pl = t.fk_id_pl and pl.ativo = 1 "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " JOIN ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa "
                . " AND ta2.fk_id_tas = 0 "
                . " JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma "
                . " JOIN ge_periodo_letivo pl2 on pl2.id_pl = t2.fk_id_pl AND pl2.at_pl = 1 "
                . " JOIN instancia i on i.id_inst = t2.fk_id_inst "
                . " left join tdics_call_center cc on cc.id_pessoa = ta.fk_id_pessoa "
                . " order by n_pessoa ";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function turmaPolo($id_polo) {
        $sql = "SELECT t.*, c.n_curso FROM tdics_turma t "
                . " JOIN tdics_pl pl on pl.id_pl = t.fk_id_pl "
                . " JOIN tdics_curso c on c.id_curso = t.fk_id_curso "
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

    public function relatFerq($id_polo, $id_inst_sieb, $periodo, $id_curso, $frequencia, $print = null, $dataIni = null, $dataFim = null) {
        $id_pl = $this->pl();
        if ($id_polo) {
            $id_polo_ = " and t.fk_id_polo = $id_polo ";
        } else {
            $id_polo_ = null;
        }
        if (empty($id_polo) && empty($id_inst_sieb)) {
            toolErp::alertModal('Selecione uma escola ou um Núcleo');
            return;
        }

        // if (!empty($dataFim)) {
        //     $ch = $this->frequeciaAlunoTMP($dataIni, $dataFim);
        // } else {
            $ch = $this->frequeciaAluno(NULL, NULL, $dataIni, $dataFim);
        // }

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
                . " FROM tdics_turma t "
                . " join tdics_turma_aluno ta on ta.fk_id_turma = t.id_turma $id_polo_ and t.fk_id_pl = $id_pl"
                . " JOIN ge_turma_aluno ta2 on ta2.fk_id_pessoa = ta.fk_id_pessoa AND ta2.fk_id_tas = 0 "
                . " JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t2.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN instancia i on i.id_inst = t2.fk_id_inst "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " join tdics_curso c on c.id_curso = t.fk_id_curso "
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
                    $array[$k]['frenq'] = '<span style="font-weight: bold; color: ' . $cor . '">' . ceil($porc * 100) . '%</span>';
                } else {
                    $array[$k]['frenq'] = (string) ceil($porc * 100);
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
        $mongo = new mongoCrude('Tdics');
        $frenq = $mongo->query('presece_' . $id_pl, $filter);
        $ch = [];

        if (toolErp::id_pessoa() == 1) {
            pre($frenq);
        }

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
        $mongo = new mongoCrude('Tdics');
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
        $mongo = new mongoCrude('Tdics');
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
            . " FROM tdics_inscricao ti "
            . " JOIN tdics_turma tt ON ti.fk_id_turma = tt.id_turma "
            . " JOIN tdics_curso tc ON tt.fk_id_curso = tc.id_curso "
            . " JOIN tdics_pl pl ON tt.fk_id_pl = pl.id_pl "
            . " JOIN tdics_polo tp ON tt.fk_id_polo = tp.id_polo "
            . " LEFT JOIN tdics_horarios th ON tt.fk_id_polo = th.fk_id_polo AND tt.periodo = th.periodo AND tt.horario = th.horario "
            . " , tdics_setup ts "
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

        $sql = "SELECT (ts.qt_turma * COUNT(tt.id_turma)) as vagas, SUM(IFNULL((SELECT COUNT(id_inscricao) FROM tdics_inscricao WHERE fk_id_turma = tt.id_turma),0)) AS inscritos, tp.id_polo, tp.n_polo, tc.id_curso, tc.n_curso, tt.periodo "
            . " FROM tdics_turma tt "
            . " JOIN tdics_curso tc ON tt.fk_id_curso = tc.id_curso "
            . " JOIN tdics_pl pl ON tt.fk_id_pl = pl.id_pl "
            . " JOIN tdics_polo tp ON tt.fk_id_polo = tp.id_polo "
            . " LEFT JOIN tdics_horarios th ON tt.fk_id_polo = th.fk_id_polo AND tt.periodo = th.periodo AND tt.horario = th.horario "
            . " , tdics_setup ts "
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
            . " FROM tdics_avise_me ti "
            . " JOIN tdics_turma tt ON ti.fk_id_turma = tt.id_turma "
            . " JOIN tdics_curso tc ON tt.fk_id_curso = tc.id_curso "
            . " JOIN tdics_pl pl ON tt.fk_id_pl = pl.id_pl "
            . " JOIN tdics_polo tp ON tt.fk_id_polo = tp.id_polo "
            . " LEFT JOIN tdics_horarios th ON tt.fk_id_polo = th.fk_id_polo AND tt.periodo = th.periodo AND tt.horario = th.horario "
            . " , tdics_setup ts "
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

        $id_polo = $this->db->ireplace('tdics_polo', $ins);

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

                    $this->db->ireplace('tdics_horarios', $insH, 1);
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
            . " FROM tdics_horarios th "
            . " JOIN tdics_polo tp ON th.fk_id_polo = tp.id_polo "
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

    public function frequeciaAlunoTMP($id_pl = null, $dataMaiorIgual = null, $dataIni = null, $dataFim = null) {

        $frenq = '[{"3349":"0","3351":"1","3352":"0","3354":"1","3386":"1","3387":"1","3409":"0","3358":"1","3359":"1","3389":"1","3390":"1","3363":"1","3392":"0","3394":"0","3366":"0","3367":"1","3369":"1","3398":"1","3400":"1","3372":"1","3374":"1","3403":"0"},
{"3350":"1","3382":"0","3383":"1","3384":"1","3355":"1","3356":"0","3357":"1","3388":"0","3360":"1","3361":"1","3362":"1","3391":"1","3364":"1","3395":"0","3396":"1","3368":"0","3370":"0","3371":"1","3399":"0","3401":"1","3402":"1","3375":"0"},
{"3893":"0","3922":"1","3897":"1","3953":"1","3977":"1","3899":"0","3978":"1","3906":"1","3963":"1","3911":"1","3943":"0","3974":"1","3994":"1"},
{"59":"1","62":"1","44":"0","63":"1","66":"0","67":"1","68":"1","69":"0","71":"1","72":"1","73":"1","74":"1","55":"1"},
{"1331":"1","1333":"1","1362":"0","1364":"0","1335":"1","1337":"1","1338":"1","1341":"1","1346":"1","1347":"1","1376":"1","1348":"1","1349":"1","1379":"1","1380":"1"},
{"2254":"1","2222":"0","494":"0","9690":"0","2331":"1","2223":"0","2336":"1","2337":"1","2264":"1","2289":"0","2339":"1","2340":"1","9637":"0","9668":"0","2239":"0","2240":"0","2241":"1","9784":"0","9785":"0","2274":"1","9789":"0","9792":"0"},
{"511":"0","512":"1","514":"1","515":"1","494":"0","516":"0","521":"1","498":"0","523":"1","500":"0","502":"0","505":"1","527":"0","506":"1","508":"1","529":"0","531":"0"},
{"4507":"1","4485":"1","4528":"1","4683":"1","4564":"1","4597":"0","4511":"1","4601":"1","4520":"0","4521":"1","4606":"1","4526":"1","4557":"1"},
{"2376":"0","2351":"0","2353":"1","2355":"1","2379":"1","2358":"1","2360":"0","2363":"1","2385":"1","2391":"0","2393":"1","2372":"1","2394":"0","2373":"1","2374":"1","2396":"1","2398":"0","2401":"0","2402":"0"},
{"888":"1","905":"1","890":"1","892":"1","894":"1","897":"1","898":"1","899":"1","901":"1","902":"1","903":"1","904":"0"},
{"2126":"0","2149":"1","2127":"1","2153":"0","2156":"0","2157":"1","2165":"1","2132":"1","2166":"1","2134":"1","2173":"1","2174":"1","2179":"1","2144":"1"},
{"764":"1","811":"0","815":"0","766":"1","818":"0","771":"0","773":"1","774":"1","777":"1","779":"0","806":"1","787":"0","790":"1","5213":"1"},
{"1011":"1","1012":"0","1047":"1","1017":"1","1018":"1","1024":"1","1027":"1","1029":"1","1067":"1","1030":"1","1070":"1","1033":"1","1034":"1","1035":"0","971":"1","1037":"1","1038":"1","1039":"1","1040":"1","1073":"1","1041":"0","1043":"1"},
{"446":"1","424":"1","425":"1","427":"1","429":"1","432":"1","434":"1","435":"0","436":"1","438":"1","441":"0","442":"0","444":"1"},
{"221":"1","222":"1","224":"0","288":"1","226":"1","227":"1","229":"1","230":"0","232":"1","318":"1","319":"0","250":"1","234":"1","362":"1","326":"0","236":"0","237":"1","330":"0","304":"0","242":"0","243":"1","245":"1"},
{"2049":"1","2050":"1","2051":"1","2052":"1","2053":"0","2055":"1","2056":"1","2058":"0","2059":"0","2060":"1","2061":"0","2063":"1","2064":"1","2068":"0","2070":"1","2071":"0","2073":"1","2074":"0","2043":"1","2075":"1","2076":"1","2078":"1"},
{"4042":"1","4043":"0","4076":"1","4047":"1","4085":"1","4005":"0","4026":"1","4006":"0","4125":"1","4009":"0","4098":"1","4168":"1","4100":"1","4035":"0","4013":"1"},
{"4839":"0","4807":"1","4842":"1","4848":"1","4855":"1","4858":"1","4896":"1","4897":"0","4860":"0","4864":"1","4833":"0","4867":"1","4868":"1"},
{"1643":"0","1611":"0","1693":"0","1551":"0","1618":"1","1669":"1","1651":"0","1524":"0","1653":"1","1656":"0","1593":"1","1557":"1","1597":"1","1598":"1","1628":"0","1634":"0","1566":"0","1640":"0"},
{"2612":"1","2553":"1","2583":"0","7977":"0","2586":"1","2589":"1","2616":"1","2618":"1","2619":"1","2621":"1","2676":"1","2592":"1","2593":"0","2677":"1","2598":"1","2684":"1","2570":"1","7945":"0","2603":"1","2573":"1","2604":"0","2637":"0","7976":"0","2639":"1","2640":"1"},
{"1108":"0","1014":"1","1232":"1","1203":"1","1051":"1","1235":"1","1237":"1","1019":"1","1113":"1","1212":"1","1243":"1","1244":"0","1214":"1","1022":"0","1023":"1","1219":"1","1065":"1","1221":"1","1071":"1","1254":"1","1036":"1","1226":"1"},
{"4263":"0","4302":"1","4237":"1","4304":"1","4311":"1","4216":"1","4275":"1","4251":"1","4316":"1","4145":"1","4165":"0","4167":"1","4279":"1","4282":"1","4200":"1","4202":"1","4259":"1"},
{"252":"0","255":"0","256":"1","257":"1","258":"1","260":"1","262":"1","263":"1","264":"0","266":"1","267":"1","270":"1","271":"1","242":"1","272":"1","275":"1","276":"0","277":"0","278":"1","280":"1"},
{"2878":"1","2880":"1","3045":"1","3073":"1","2901":"1","2823":"1","2824":"1","2461":"0","2850":"0","2827":"1","2892":"0","2830":"1","2962":"0","3052":"1","2840":"1","2856":"1","2944":"0","2866":"1","2867":"1","3065":"1","7870":"0","2873":"1"},
{"3691":"0","3504":"0","3531":"1","3663":"0","3532":"1","3835":"0","3533":"0","3664":"1","3505":"0","3665":"0","3628":"0","3723":"0","3477":"0","3592":"0","3749":"0","3537":"1","3724":"0","3479":"0","3605":"1","3557":"1","3499":"0","3744":"0"},
{"98":"1","100":"1","101":"1","102":"1","104":"1","106":"1","109":"1","118":"0","110":"0","112":"1","114":"1","115":"0","116":"1"},
{"3892":"1","3950":"0","3952":"0","3924":"0","3900":"0","3955":"0","3932":"1","3985":"1","3907":"1","3960":"1","3910":"1","3938":"1","3965":"0","3915":"1","3917":"1","3992":"1"},
{"2351":"1","2352":"1","2354":"1","2378":"1","2356":"0","2380":"0","2357":"1","2382":"1","2383":"1","2364":"0","2403":"1","2365":"1","2366":"1","2387":"1","2388":"0","2389":"1","2392":"1","2400":"1","2401":"0"},
{"1327":"1","1354":"1","1356":"1","7210":"0","1360":"1","1334":"1","1363":"1","1302":"1","1277":"0","1365":"1","1278":"1","1336":"1","7184":"0","1370":"1","1373":"1","1287":"0","1375":"1","1378":"1","7198":"0","7327":"0"},
{"562":"0","535":"0","537":"1","563":"0","540":"1","566":"0","568":"0","548":"0","550":"1","552":"1","554":"1","584":"0","559":"1"},
{"4958":"1","4962":"1","4969":"1"},
{"4541":"0","4522":"0","4500":"1","4610":"0","4504":"1","4525":"0"},
{"834":"0","852":"1","838":"1","839":"1","855":"1","840":"0","858":"1","859":"1","860":"1","845":"0","846":"1","865":"1"},
{"9763":"0","2150":"1","2084":"1","2309":"1","2160":"1","2196":"0","2197":"0","2199":"0","2202":"0","2097":"1","2206":"1","2207":"1","2208":"1","2209":"1","2211":"0","2177":"1","2214":"1","2217":"1"},
{"4537":"1","4542":"1"},
{"4729":"0"},
{"638":"1","640":"1","643":"1","644":"1","5212":"1","647":"1","649":"1","650":"1","651":"1","652":"0","653":"1","658":"1","659":"1","663":"1","664":"1"},
{"1201":"1","1050":"0","1109":"1","1206":"0","1111":"1","1112":"1","1114":"1","1115":"1","1119":"1","1249":"0","1063":"1","1220":"1","1120":"1","1121":"0","1123":"1","1127":"1","1128":"1","1222":"1","1130":"1","1133":"0","1135":"1","1137":"1"},
{"4298":"1","4073":"1","4132":"0","4083":"1","4084":"0","4086":"0","4025":"0","4189":"0","4091":"1","4093":"1","4063":"1","4011":"1","4032":"1","4102":"1","4068":"1","4014":"1","4107":"0"},
{"285":"0","311":"1","287":"1","314":"1","293":"1","294":"1","296":"0","297":"1","298":"0","324":"1","299":"1","300":"1","325":"0","327":"1","302":"1","303":"1","332":"1","335":"1","305":"1","306":"1"},
{"2017":"1","2018":"1","2019":"1","2021":"1","2023":"1","2025":"0","2026":"1","2027":"1","2029":"1","2031":"0","2032":"1","2034":"1","2035":"1","2036":"1","2038":"1","2040":"1","2041":"1","2042":"1","2044":"0","2045":"1","2046":"0","2047":"0"},
{"2671":"0","2785":"1","2555":"1","2559":"1","2760":"0","7966":"0","2791":"0","2565":"0","2765":"1","2681":"0","2711":"0","2743":"0","7971":"0","2599":"0","2747":"0","2804":"1","2778":"1","2748":"0","7911":"0","2668":"1","2754":"0","2580":"0"},
{"1450":"0","1452":"1","1420":"1","1459":"0","7478":"0","1460":"1","1421":"0","1466":"1","1467":"0","1468":"1","1471":"1","1439":"0","1474":"1","1476":"1","1443":"1","7386":"0","1445":"1","1477":"1","1478":"1"},
{"4939":"0","4981":"1","4984":"0","4942":"1","5014":"1","5016":"0","5080":"1","4960":"1","5156":"1","4962":"0","5003":"1","4969":"0","5033":"0","4971":"1","4972":"1","5069":"1"},
{"5142":"1","5171":"1","5151":"0","5115":"1","4955":"1","4961":"0","5183":"1","5192":"1","5194":"1"},
{"4236":"1","4078":"1","4116":"1","4183":"1","4240":"0","4052":"1","4243":"0","4160":"0","4247":"1","4271":"0","4092":"1","4274":"1","4056":"1","4059":"1","4224":"1"},
{"339":"1","341":"0","342":"1","344":"1","347":"1","378":"1","379":"1","386":"0","387":"1","363":"1","364":"1","391":"1","367":"0"},
{"2784":"1","2703":"1","2731":"1","7853":"0","2762":"1","2792":"0","7826":"0","2710":"1","2742":"1","2796":"1","7828":"0","2745":"1","2721":"1","2771":"1","2666":"0","2690":"1","7835":"0","2779":"1","2577":"0","2751":"1","2780":"0","2753":"1"},
{"3281":"1","3316":"1","3318":"1","3284":"1","3322":"0","3326":"0","3287":"1","3328":"1","3330":"1","3289":"0","3291":"0","3292":"0","3294":"1","3332":"1","3297":"1","3299":"0","3333":"1","3303":"1","3334":"0"},
{"3315":"1","3317":"0","3282":"0","3319":"0","3321":"0","3285":"0","3323":"0","3324":"0","3326":"1","3327":"1","3329":"0","3288":"1","3290":"1","3331":"0","3293":"1","3295":"0","3296":"1","3298":"1","3301":"1","3302":"0","3304":"0","3335":"0"},
{"3672":"1","3744":"1"},
{"3190":"0","3124":"0","3191":"0","3125":"0","3130":"0","3131":"0","3196":"0","3133":"0","3174":"0","3201":"0","3157":"0","3202":"1","3178":"0","3158":"0","3179":"0","3138":"1","3140":"0","3204":"0","3163":"0","3164":"0"},
{"3629":"1","3668":"0","3839":"0","3816":"0","3868":"0","3601":"0","3602":"0","3639":"0","3818":"0","3732":"0","3789":"0","3872":"0","3873":"0","3609":"1","3824":"0","3648":"0","3791":"0","3734":"0","3735":"0","3704":"0","3719":"0","3746":"0"},
{"2516":"1","2517":"1","2519":"1","2520":"0","2485":"0","2523":"0","2525":"1","2527":"1","2529":"1","2496":"1","2534":"1","2535":"1","2536":"0","2540":"1","2541":"1","2542":"0","2508":"0","2544":"1","2548":"1"},
{"3949":"0","3896":"1","3902":"0","3904":"1","3933":"1","3934":"1","3909":"0","3912":"0","3966":"1","3967":"0","3944":"1","3916":"0","3947":"1"},
{"38":"0","99":"0","60":"1","41":"1","76":"1","47":"0","51":"1","52":"1","70":"1","92":"0","53":"0","56":"0","96":"1"},
{"59":"1","62":"1","44":"1","63":"1","66":"1","67":"1","68":"1","69":"1","71":"1","72":"1","73":"1","74":"1","55":"1"},
{"4488":"0","4489":"0","4493":"0","4495":"1","4519":"0","4498":"1"},
{"1262":"0","1296":"0","1263":"1","1268":"1","1297":"0","1272":"0","1273":"1","1275":"1","1276":"1","8553":"1","1306":"1","7269":"0","1309":"1","1310":"1","1311":"1","7243":"0","7349":"0","1320":"1","1323":"1","1289":"0","7362":"0","1293":"1"},
{"2220":"1","2305":"1","2224":"1","2227":"1","2228":"1","2229":"0","2230":"1","2231":"1","2262":"1","2312":"0","2313":"0","459":"0","2317":"1","2236":"1","2237":"1","2238":"1","2272":"0","2242":"1","2243":"1","2275":"1","2244":"0"},
{"447":"1","493":"0","450":"1","454":"0","456":"0","458":"1","460":"0","462":"1","485":"0","487":"0","467":"0"},
{"2924":"0","2953":"0","2925":"0","2926":"0","2933":"0","2960":"0","2994":"0","2964":"0","2968":"0","2969":"1","2941":"0","2974":"0","2975":"0","3003":"0","2977":"1","2980":"0"},
{"7206":"0","7207":"1","7212":"1","7173":"0","7336":"0","7338":"0","7180":"0","7342":"0","7309":"0","7185":"0","7157":"0","7279":"1","7166":"1","7167":"0","7322":"1","7359":"0","7360":"0","7203":"1","7294":"1"},
{"538":"1","542":"1","545":"1","569":"0","574":"0","549":"1","555":"1"},
{"2080":"1","5422":"0","5444":"0","5365":"0","2085":"1","2086":"1","2087":"1","2089":"1","2168":"1","2095":"1","2096":"1","2203":"0","2098":"1","2180":"1","2145":"1"},
{"4506":"0","4508":"0","4531":"0","4588":"0","4536":"1","4538":"0","4594":"1","4489":"0","4513":"0","4570":"0","4493":"0","4543":"0","4497":"0","4607":"1","4501":"1","4551":"0","4503":"1","4745":"0","4613":"0","4614":"1"},
{"669":"1","670":"0","671":"1","673":"1","675":"1","676":"0","679":"1","681":"0","684":"1","685":"0","687":"1","689":"0","691":"1","692":"0","696":"1","697":"1","698":"1","699":"1"},
{"642":"1","646":"1","648":"0","655":"1","657":"0","662":"1","665":"0","666":"1","667":"1"},
{"1141":"1","1174":"1","1177":"0","1144":"0","1178":"1","1207":"1","1145":"0","1186":"1","1147":"1","1188":"1","1189":"1","1191":"0","1192":"1","1193":"1","1194":"0","1160":"1","1124":"1","1196":"1","1102":"1","1258":"1","1198":"1","1199":"1"},
{"398":"0","400":"0","402":"1","419":"0","404":"1","406":"0","412":"1","415":"1"},
{"5270":"0","5271":"1","5294":"1","5825":"0","5767":"1","5297":"1","5298":"0","5280":"0","5299":"1","5300":"0","5777":"0","5284":"0","5784":"1","5691":"0","5668":"0","5918":"0","5305":"0","5306":"0","2120":"1","5310":"1","5311":"1","5312":"1"},
{"4017":"1","4075":"1","4114":"1","3997":"1","3998":"1","4079":"1","4048":"1","3999":"0","4136":"1","4050":"1","4119":"1","4029":"1","4030":"1","4101":"1","4033":"1","4103":"1","4036":"1","4070":"0"},
{"172":"1","149":"1","197":"1","124":"1","125":"0","179":"1","198":"1","156":"1","127":"1","199":"1","159":"1","129":"1","131":"1","206":"1","163":"1","211":"1","214":"1","215":"0","144":"1"},
{"1381":"1","1382":"0","1383":"0","1385":"0","1386":"1","1387":"1","1388":"1","1389":"0","1390":"1","1391":"1","1393":"0","1397":"1","1399":"1","1400":"1","1401":"1","1402":"1","1406":"0","1407":"0","1408":"0","1409":"0","1410":"1","1412":"1"},
{"2404":"1","2406":"0","7820":"0","7849":"0","2455":"1","2456":"0","2459":"0","7979":"0","7982":"0","2461":"0","7824":"0","2414":"1","2471":"1","2472":"0","7862":"0","2423":"1","7863":"0","7995":"0","7996":"0","2425":"0","2478":"1","2427":"1","7930":"0","7870":"0","7843":"0"},
{"3245":"1","3210":"1","3212":"1","3246":"0","3213":"0","3214":"1","3249":"0","3250":"0","3251":"1","3216":"0","3217":"1","3258":"0","3259":"0","3221":"0","3222":"1","3223":"1","3267":"1","3226":"0","3227":"0","3229":"0","3270":"1"},
{"1077":"1","980":"1","1083":"1","1085":"1","983":"1","984":"0","1086":"1","988":"0","1090":"0","993":"1","1150":"0","994":"1","995":"1","1098":"1","1162":"1","1000":"1","1003":"1","1006":"1","1101":"1"},
{"4428":"0","4430":"0","4433":"1","4465":"1","4437":"1","4442":"1","4451":"0","4477":"0","4453":"0","4454":"0","4480":"1"},
{"283":"1","251":"1","310":"1","228":"0","349":"1","281":"1","351":"1","235":"1","386":"1","238":"1","239":"1","333":"0","273":"1"},
{"4235":"1","4300":"1","4208":"1","4112":"1","4238":"0","4182":"0","4184":"1","4309":"0","4310":"0","4241":"1","4249":"0","4222":"1","4322":"1","4223":"1","4284":"1","4286":"0","4326":"1","4290":"0","4203":"0"},
{"1513":"0","1547":"1","1691":"0","1518":"1","1694":"0","7061":"0","1697":"0","1571":"0","1494":"0","1589":"1","1498":"0","1595":"1","1709":"0","1559":"0","1710":"0","1503":"0","1630":"1","1562":"0","1713":"1","1565":"0","1567":"0","7082":"0"},
{"2843":"0","2813":"0","2815":"0","2844":"1","2847":"1","2902":"1","2903":"1","2889":"0","2852":"0","2837":"1","2939":"1","2854":"1","2857":"1","2913":"1","2862":"0","2865":"1","2869":"1","7836":"0","10438":"1","2871":"0","2898":"0","2919":"1"},
{"4431":"1","4460":"1","4463":"0","4438":"0","4470":"0","4446":"0","4472":"1","4475":"1","4452":"1","4476":"1","4482":"1"},
{"3252":"1","3253":"1","3254":"0","3255":"1","3256":"0","3257":"0","3218":"1","3219":"0","3220":"1","3260":"1","3261":"0","3263":"0","3264":"0","3265":"1","3266":"0","3224":"0","3225":"0","3268":"1","3228":"0","3269":"0","3271":"0"},
{"8710":"1","8649":"1","8722":"1","8727":"1","8848":"0","8821":"1","8822":"1","8824":"1"},
{"588":"1","610":"0","590":"0","591":"1","593":"0","595":"1","596":"1","597":"1","598":"1","599":"1","601":"1","602":"1","603":"0","604":"1","606":"1","607":"0","608":"1","609":"1"},
{"77":"0","78":"0","79":"1","83":"0","85":"0","86":"0","89":"0","97":"0","93":"0","94":"0","95":"0"},
{"3016":"0","3071":"1","3076":"0","7706":"0","7708":"0","3079":"1","3027":"0","3082":"1","3111":"1","7713":"0","3114":"1","7746":"0","7723":"0","2952":"1"},
{"3894":"0","3895":"1","3976":"1","3926":"1","3930":"1","3954":"1","3982":"0","3903":"1","3973":"1","3961":"0","3988":"1","3989":"0","3918":"1","3971":"0"},
{"869":"1","9652":"0","873":"1","874":"1","891":"1","877":"1","841":"1","878":"1","896":"1","879":"1","880":"0","6897":"0","884":"0","885":"1","849":"1"},
{"1355":"1","1328":"1","1329":"1","1330":"1","1357":"1","1332":"1","7150":"1","1369":"1","1339":"0","1340":"0","1372":"1","1343":"1","1374":"1","1344":"0","1377":"1","1350":"1","1353":"0"},
{"448":"1","472":"1","473":"1","453":"1","455":"0","491":"1","477":"0","457":"1","459":"1","479":"1","461":"0","480":"0","481":"1","482":"1","486":"1","464":"1","488":"0","489":"0","466":"1"},
{"4587":"1","4529":"1","4533":"1","4534":"1","4560":"1","4561":"1","4754":"1","4562":"1","4662":"0","4757":"1","4731":"1","4667":"1","4668":"1","4762":"1","4672":"0","4550":"1","4680":"1","4581":"1","4681":"0","4584":"1","4714":"0"},
{"3097":"0","3074":"0","3075":"1","3102":"0","3103":"1","3025":"0","3048":"0","3081":"0","3116":"1","3061":"0","3036":"0"},
{"867":"1","868":"1","870":"1","871":"1","872":"1","875":"1","876":"1","881":"1","883":"1","887":"1","900":"0","886":"1"},
{"7333":"1","7298":"0","7302":"0","7337":"1","7152":"0","7214":"0","7160":"1","7329":"0","7163":"1","7168":"1","7169":"1"},
{"5594":"1","5596":"1","5571":"0","5606":"0","2093":"0","5582":"1","5583":"1","5584":"1","2147":"0","2100":"0","5588":"1","5615":"0","5618":"0","2146":"1"},
{"700":"1","701":"1","674":"0","702":"1","704":"0","705":"1","707":"0","709":"0","712":"1","682":"1","713":"0","714":"1","686":"1","717":"0","693":"0","719":"0","720":"1","723":"0"},
{"4716":"1","4749":"1","4781":"0","4720":"1","4752":"0","4753":"1","4725":"0","4758":"1","4787":"0","4788":"1","10478":"1","4733":"1","4735":"0","4737":"1","4674":"0","4794":"0","4740":"0","4710":"0","4769":"1","4747":"1"},
{"979":"1","1234":"0","1206":"0","1238":"0","1210":"1","1211":"1","987":"1","1245":"1","1089":"1","990":"1","1059":"0","992":"0","1064":"1","1093":"1","1155":"0","1157":"0","1158":"0","997":"0","1159":"1","1163":"0","1007":"1","1008":"1"},
{"5822":"1","5647":"1","5648":"0","5893":"1","5677":"0","2410":"0","5628":"1","5631":"1","5935":"1","5658":"1","5634":"1","5639":"0","5644":"0","5672":"0"},
{"4039":"0","4077":"1","4156":"1","4082":"1","4157":"1","4087":"1","4088":"0","4161":"1","4120":"1","4060":"0","4144":"1","4094":"1","4126":"1","4012":"0","4149":"1","4066":"0","4171":"1","4177":"1","4175":"0","4154":"1"},
{"120":"1","195":"0","176":"1","177":"1","180":"0","200":"0","128":"1","201":"1","130":"1","132":"1","203":"0","205":"0","134":"1","135":"1","185":"1","136":"1","187":"1","188":"1","189":"1","141":"0","143":"1"},
{"1574":"0","1687":"0","1514":"0","1517":"1","1666":"0","1521":"1","1526":"1","1529":"1","1497":"1","1558":"1","1534":"0","1563":"1","1542":"1","1683":"0","1604":"0","1638":"0"},
{"2406":"0","2408":"1","2409":"0","7823":"1","2410":"1","2460":"0","2439":"1","2465":"1","2466":"1","2418":"0","7986":"1","2441":"1","7972":"1","7861":"1","2444":"0","7926":"0","7833":"1","7958":"1","2476":"1","2479":"1","2480":"0","7841":"1"},
{"4617":"1","4619":"1","4625":"0","4629":"1","4633":"1","4634":"1","4469":"1","4636":"0","4637":"1","4641":"0","4829":"1","4651":"0","4652":"1","4902":"1"},
{"3410":"0","3444":"0","3446":"0","3447":"1","3449":"0","3413":"0","3414":"0","3452":"0","3416":"0","3418":"1","3419":"1","3455":"0","3457":"0","3421":"0","3422":"1","3423":"0","3459":"0","3428":"0","3429":"0","3431":"1","3432":"0","3462":"0"},
{"5826":"0","5793":"0","5828":"0","5930":"0","5650":"1","5832":"0","5931":"0","5896":"0","5973":"0","5708":"0","5837":"0","5738":"0","5655":"0","5739":"0","5941":"0","5842":"0","5742":"0","5911":"0","5987":"0","5664":"1","5723":"0","5758":"0"},
{"4206":"0","4236":"1","4181":"1","4233":"0","4267":"0","4186":"0","4159":"1","4188":"0","4217":"0","4194":"1","4195":"1","4220":"0","4254":"0","4255":"0","4225":"1","4170":"0","4172":"1"},
{"145":"1","119":"1","146":"1","148":"1","151":"1","152":"1","153":"1","123":"0","154":"1","157":"0","158":"1","138":"1","139":"1","166":"1","140":"1","188":"0","142":"1","168":"1","191":"1","169":"1","170":"1"},
{"3443":"0","3445":"1","3411":"0","3448":"1","3412":"0","3450":"1","3451":"0","3415":"1","3417":"0","3453":"1","3454":"0","3456":"0","3420":"0","3458":"1","3424":"0","3425":"0","3426":"0","3427":"1","3461":"0","3430":"1","3433":"0","3434":"0"},
{"2642":"0","2645":"1","7820":"0","2554":"0","2674":"0","2678":"1","2656":"1","2657":"1","2711":"1","2659":"0","2661":"0","2662":"1","2715":"1","7882":"0","7992":"0","2746":"0","2799":"1","7888":"1","7954":"0","2695":"0","7844":"0","2698":"1"},
{"396":"1","397":"1","398":"1","399":"1","401":"1","403":"0","406":"1","407":"1","408":"1","420":"0","409":"1","410":"0","411":"0","413":"1","414":"1","418":"0"},
{"7051":"0","7055":"0","7073":"1"},
{"8812":"1","8636":"1","8762":"1","8819":"1","8873":"0"},
{"4458":"1","4432":"1","4462":"1","4464":"0","4468":"0","4478":"1"},
{"613":"1","615":"1","616":"1","618":"1","619":"1","620":"1","621":"1","624":"1","625":"0","627":"1","629":"1","631":"0","633":"1","635":"1"},
{"561":"0","532":"0","567":"1","544":"1","553":"0","579":"0","580":"0"},
{"57":"1","80":"0","40":"1","82":"0","42":"1","43":"1","45":"0","107":"1","48":"0","64":"1","91":"0"},
{"3948":"0","3898":"0","3927":"1","3901":"1","3956":"1","3957":"0","3931":"0","3986":"1","3962":"1","3937":"0","3964":"0","3987":"1","3913":"1","3919":"1"},
{"2483":"0","2486":"0","2493":"0","2494":"1","2495":"1","7706":"0","7708":"0","2531":"1","2499":"1","7713":"0","2538":"1","2539":"1","2503":"1","2504":"0","2506":"0","2509":"1","2510":"0","2511":"1","2545":"1","2547":"0","2514":"1","2549":"0"},
{"1264":"1","1265":"1","1266":"0","1269":"1","1303":"0","1280":"1","1281":"1","1312":"1","1284":"1","1285":"0","1322":"1","1290":"1","1291":"0","7287":"1","1292":"1","1294":"1"},
{"4527":"0","4507":"0","4509":"1","4540":"1","4592":"1","4563":"1","4593":"1","4487":"0","4571":"1","4736":"1","4573":"1","4601":"1","4546":"1","4499":"0","4549":"1","4575":"1","4579":"1","4582":"0","4556":"1"},
{"2279":"0","2250":"1","2251":"1","2253":"0","5566":"1","2255":"0","2283":"1","2257":"1","2299":"0","2263":"0","2314":"1","2093":"0","2270":"1","2100":"0","2277":"1"},
{"736":"1","737":"1","740":"0"},
{"492":"0","518":"0","495":"0","496":"1","497":"0","498":"0","499":"1","484":"0","507":"1","510":"0"},
{"835":"1","851":"0","836":"1","853":"1","854":"0","856":"1","850":"0","857":"1","843":"1","844":"0","863":"1","848":"1","866":"0"},
{"7334":"1","7299":"0","7176":"1","7177":"1","7339":"1","7179":"1","7316":"1","7356":"1","7326":"1"},
{"7790":"1","7792":"0","7728":"1","7729":"1","7735":"0","7707":"1","7737":"0","7765":"1","7796":"1","7738":"0","7712":"1","7803":"1","7805":"0","7776":"0","7747":"0","7748":"0","7808":"1","7752":"1","7813":"1","7814":"1","7787":"0","7816":"0"},
{"2125":"1","2103":"1","2126":"1","2104":"1","2106":"1","2130":"1","2109":"1","2110":"0","2131":"1","2111":"1","2112":"1","2114":"1","2115":"0","2117":"1","2119":"1","2203":"0","2137":"1","2124":"1","2121":"1","2122":"1","2142":"1","2146":"0"},
{"8544":"0","8546":"0","8551":"0","8601":"0","8602":"0","8604":"0","8582":"0","8607":"1","8558":"1","8561":"0","8565":"1","8567":"0","8613":"1","8614":"1","8568":"1"},
{"728":"1","750":"0","751":"1","732":"1","733":"0","735":"1","752":"1","754":"0","738":"1","757":"0","758":"1","760":"0","745":"1"},
{"4657":"1","4532":"1","4661":"1","4687":"1","4663":"1","4666":"1","4496":"1","4739":"0","4675":"1","4701":"0","4548":"1","4704":"0","4708":"1","4684":"0","4746":"0"},
{"792":"1","794":"1","795":"1","767":"0","775":"0","780":"1","781":"1","782":"0","783":"1","807":"1","784":"1","789":"1","809":"1"},
{"907":"1","1202":"1","981":"0","947":"0","953":"0","1065":"0","955":"1","927":"0","956":"1","961":"1","963":"0","964":"1","968":"1","969":"1","970":"1","937":"1","973":"1","939":"0","974":"1","975":"1","976":"1","977":"1"},
{"421":"1","422":"1","423":"1","425":"0","426":"0","427":"0","428":"0","430":"1","431":"1","433":"1","437":"0","439":"1","440":"0","443":"1","445":"1"},
{"339":"1","370":"1","371":"1","375":"1","343":"0","7596":"0","7599":"0","376":"1","7601":"0","350":"0","380":"0","357":"1","7603":"0","382":"1","383":"1","384":"1","7582":"0","7608":"0","388":"0","390":"1","393":"1","7618":"0","395":"1"},
{"4019":"1","4021":"0","4022":"0","4049":"1","4000":"1","4138":"0","4024":"0","4139":"1","4053":"1","4001":"1","4141":"1","4007":"0","4097":"1","4064":"0"},
{"9825":"1","5651":"1","9860":"1","9832":"1","10279":"1","9835":"1","10185":"0","9836":"1","10309":"1","10311":"1","10025":"0","5666":"1","10197":"1","9848":"1"},
{"7960":"0","2550":"1","2551":"0","7940":"1","2581":"0","2613":"1","2552":"0","2585":"1","2556":"1","2620":"1","7898":"0","2566":"0","2628":"1","2568":"1","2634":"1","2600":"1","2574":"0","2605":"0","2636":"0","7951":"0","2576":"0","7953":"0"},
{"1453":"1","1454":"1","1419":"0","1413":"0","1422":"1","1423":"0","1425":"0","8779":"1","1428":"0","1432":"0","1434":"0","1435":"1","1436":"0","1472":"0","1438":"0","1440":"0","7385":"1","7441":"0","1481":"0"},
{"3349":"0","3351":"1","3352":"0","3354":"1","3386":"1","3355":"1","3387":"1","3358":"1","3359":"1","3389":"1","3390":"0","3363":"1","3392":"0","3394":"0","3366":"0","3367":"1","3369":"1","3398":"1","3400":"0","3372":"1","3374":"1","3403":"1"},
{"906":"1","908":"1","909":"1","911":"1","912":"1","913":"1","1208":"0","915":"1","917":"1","949":"1","924":"1","926":"1","930":"1","931":"1","1004":"0","938":"0","941":"1","942":"1"},
{"3350":"1","3382":"0","3383":"1","3384":"1","3356":"0","3357":"0","3388":"0","3360":"1","3361":"1","3390":"1","3362":"1","3391":"0","3364":"1","3395":"0","3396":"1","3368":"0","3370":"1","3371":"1","3399":"0","3401":"1","3402":"1","3375":"0"},
{"10266":"1","10083":"0","10086":"0","10020":"0","10090":"0","10091":"0","10021":"0","9916":"0","10024":"0","10026":"0","10103":"0"},
{"4301":"0","4212":"1","4308":"1","4309":"1","4121":"1","4256":"1","4278":"0","4320":"0","4283":"1","4285":"1","4201":"0","4287":"0","4258":"1","4292":"0","4331":"0"},
{"194":"1","173":"1","174":"1","175":"1","122":"0","181":"1","171":"0","182":"1","161":"1","203":"0","184":"0","207":"1","208":"1","186":"0","209":"1","164":"1","216":"1","218":"0","192":"1","193":"1"},
{"2405":"1","2453":"0","7977":"1","7822":"1","2435":"0","2411":"1","2412":"0","2413":"1","2415":"0","7967":"1","2416":"1","7858":"1","2420":"1","2421":"1","7990":"1","2422":"1","7909":"1","2424":"0","2445":"1","7930":"1","7839":"0","7840":"1"},
{"7475":"1"},
{"614":"1","589":"1","617":"0","592":"1","622":"1","623":"1","626":"1","637":"1","630":"1","605":"0","634":"1","636":"1"},
{"8618":"1","8680":"1","8648":"1","8651":"0","8624":"1","8625":"1","8653":"0","8631":"1","8869":"1","8656":"1","8633":"1","8634":"1","8665":"1","8637":"1","8639":"1","8669":"0","8875":"1","8643":"1","8741":"0"},
{"2376":"1","2353":"0","2355":"0","2379":"0","2358":"1","2360":"0","2363":"1","2385":"1","2391":"0","2393":"1","2372":"1","2394":"1","2373":"1","2374":"1","2396":"1","2401":"0","2402":"0"},
{"3893":"0","3922":"1","3897":"1","3953":"1","3977":"1","3899":"1","3978":"1","3906":"1","3963":"1","3911":"1","3943":"0","3974":"1","3994":"1"},
{"7145":"1","1331":"1","1358":"0","1333":"1","1362":"1","1364":"1","7181":"1","1335":"1","1337":"1","1338":"1","7158":"1","1341":"1","1346":"1","1347":"1","1376":"1","1348":"1","1349":"1","1379":"1","1380":"1"},
{"2254":"1","5366":"0","2222":"0","494":"0","2331":"1","5371":"1","5397":"0","2336":"1","2337":"1","2264":"1","5377":"1","2289":"0","2339":"1","5409":"0","5410":"1","2340":"1","5412":"1","2239":"0","2240":"0","2241":"1","5415":"1","2274":"1"},
{"511":"0","512":"0","514":"1","515":"1","494":"0","516":"1","521":"1","498":"0","523":"1","500":"1","502":"0","505":"1","527":"0","506":"0","508":"1","529":"1","531":"0"},
{"4485":"0","4528":"1","4683":"1","4564":"1","4597":"0","4511":"1","4601":"0","4520":"0","4521":"1","4522":"0","4606":"1","4610":"1","4526":"0","4557":"0"},
{"7755":"1","7791":"0","7758":"1","7793":"0","7759":"0","7762":"1","7763":"1","7767":"0","7769":"1","7741":"1","7770":"1","7771":"1","7773":"1","7774":"0","7778":"1","7779":"1","7780":"1","7782":"1","7784":"1","7785":"0","7786":"1","7815":"1"},
{"888":"1","905":"1","890":"1","892":"1","894":"1","897":"1","898":"1","899":"1","901":"1","902":"1","903":"0","904":"0"},
{"7182":"0","7183":"0","7159":"0","7313":"0","7252":"1","7324":"0"},
{"8594":"1","8548":"1","8603":"1","8609":"1","8610":"1","8615":"1","8616":"1"},
{"5442":"1","2149":"1","2127":"1","2153":"0","2156":"0","2157":"1","5600":"1","2165":"1","2132":"1","2166":"1","5605":"1","2134":"1","2173":"0","2174":"1","2179":"1","5614":"1","2144":"1","5460":"0","5619":"1"},
{"764":"1","811":"1","815":"0","766":"1","818":"1","771":"1","773":"1","774":"1","777":"1","779":"1","805":"0","806":"1","787":"1","790":"1","5213":"1"},
{"1011":"0","1012":"0","1047":"1","1017":"1","1018":"1","1024":"1","1027":"0","1029":"1","1067":"1","1030":"1","1070":"1","1033":"1","1034":"1","1035":"1","971":"1","1037":"1","1038":"1","1039":"0","1040":"0","1073":"1","1041":"0","1043":"1"},
{"446":"1","424":"0","425":"1","427":"1","429":"1","432":"1","434":"0","435":"0","436":"1","438":"1","441":"0","442":"0","444":"1"},
{"4839":"1","4807":"1","4842":"1","4848":"0","4855":"1","4858":"1","4896":"0","4897":"0","4860":"0","4864":"1","4833":"0","4867":"0","4868":"1"},
{"221":"1","222":"1","224":"1","288":"1","226":"1","227":"1","229":"1","230":"0","232":"1","318":"1","319":"1","250":"1","234":"1","362":"1","326":"1","236":"0","237":"1","330":"0","304":"0","242":"1","243":"1","245":"1"},
{"252":"0","255":"1","256":"1","257":"0","258":"1","260":"1","262":"1","263":"0","264":"1","266":"1","267":"1","270":"1","271":"1","272":"1","275":"1","276":"0","277":"0","278":"1","280":"1"},
{"4042":"1","4043":"0","4076":"1","4047":"0","4085":"0","4005":"0","4026":"0","4006":"0","4125":"1","4009":"1","4098":"0","4168":"0","9353":"0","4100":"1","4035":"0","4013":"1"},
{"2049":"1","2050":"1","2051":"0","2052":"1","2053":"0","2055":"1","2056":"1","2058":"0","2059":"1","2060":"1","2061":"1","2063":"1","2064":"1","2068":"1","2070":"0","2071":"1","2073":"1","2074":"0","2043":"1","2075":"1","2076":"1","2078":"1"},
{"1643":"0","1611":"1","1693":"0","7370":"0","1551":"0","1618":"1","1669":"1","1651":"0","1524":"0","7487":"0","1653":"1","1656":"1","7497":"0","1593":"1","1557":"1","1597":"1","1598":"1","1628":"0","7384":"0","1634":"1","1566":"0","1640":"1"},
{"2612":"0","2553":"1","2583":"1","2586":"1","2589":"1","2616":"1","2618":"1","2619":"1","2621":"1","2676":"1","2592":"1","2593":"1","2677":"0","2598":"1","2684":"1","2570":"1","7945":"0","2603":"1","2573":"0","2604":"1","2637":"0","7976":"0","2639":"1","2640":"1"},
{"3531":"0","3532":"0","3664":"0","3537":"0","3598":"1","3605":"1","3557":"0"},
{"1108":"0","1014":"1","1232":"0","1203":"1","1051":"1","1235":"1","1237":"1","1019":"1","1113":"0","1212":"1","1243":"1","1244":"1","1214":"1","1022":"1","1023":"0","1219":"1","1065":"1","1221":"1","1071":"1","1254":"1","1036":"1","1226":"1"},
{"10266":"1","10083":"1","10086":"1","10091":"1","10094":"1","10026":"0","10103":"1"},
{"4263":"1","4302":"1","4237":"1","4304":"1","4311":"1","4216":"1","4275":"1","4251":"1","4316":"1","4145":"1","4165":"0","4167":"1","4279":"1","4282":"1","4200":"1","4202":"1","4259":"1"},
{"2843":"1","2878":"1","2880":"1","7963":"0","3045":"1","3073":"1","2901":"1","2823":"1","2824":"1","2850":"0","2827":"1","2892":"1","2830":"1","2962":"0","3052":"0","2840":"1","2856":"1","2944":"0","2866":"1","2867":"1","3065":"0","2873":"1"},
{"1776":"0","7118":"0","1725":"1","1667":"0","1727":"1","7453":"0","7485":"0","7094":"0","1847":"0","1704":"0","7378":"0","7494":"0","1852":"0","7069":"0","7461":"0","1857":"0","7383":"0","7499":"0","1861":"0","7502":"0","7442":"0"},
{"8620":"1","8860":"1","8808":"1","8627":"1","8691":"1","8638":"0"},
{"3865":"0","3725":"0","3695":"0","3782":"0","3784":"0","3633":"0","3698":"0","3596":"0","3598":"0","3752":"1","3636":"0","3484":"0","3637":"0","3600":"0","3787":"0","3671":"0","3672":"0","3641":"0","3844":"0","3733":"0","3870":"0","3658":"0"},
{"3126":"0","3148":"1","3170":"1","3149":"1","3128":"0","3167":"0","3153":"1","3132":"0","3200":"0","3175":"1","3137":"0","3159":"0","3162":"1","3203":"0","3181":"0","3182":"0","3183":"0","3206":"0","3143":"0","3207":"1","3144":"0","3188":"0"},
{"3123":"1","3147":"1","3169":"0","3189":"1","3192":"0","3127":"1","3193":"0","3194":"0","3151":"1","3173":"1","3154":"1","3198":"0","3156":"0","3176":"0","3136":"0","3160":"0","3161":"0","3141":"0","3205":"0","3185":"1","3186":"1","3166":"0"},
{"3813":"0","3696":"0","3697":"0","3634":"0","3597":"1","3751":"0","3727":"0","3868":"0","3670":"0","3786":"0","3601":"1","3604":"0","3820":"0","3756":"0","3699":"0","3646":"0","3675":"0","3701":"0","3610":"0","3703":"0","3747":"0","3758":"0"},
{"3122":"0","3145":"0","3146":"1","3168":"1","3150":"0","3171":"0","3172":"1","3129":"0","3152":"1","3823":"0","3195":"0","3134":"1","3199":"1","3155":"0","3135":"0","3177":"0","3159":"0","3139":"0","3180":"1","3142":"0","3184":"0","3208":"0"},
{"3667":"0","3595":"0","3750":"0","3669":"0","3599":"0","3638":"0","3728":"0","3729":"0","3603":"0","3672":"1","3869":"0","3845":"0","3788":"0","3607":"0","3647":"0","3611":"0","3874":"0","3649":"0","3847":"0","3704":"0","3705":"0","3825":"0"},
{"3190":"0","3124":"0","3191":"0","3125":"0","3130":"0","3131":"0","3196":"0","3133":"0","3174":"0","3201":"0","3157":"0","3202":"1","3178":"0","3158":"0","3179":"0","3138":"1","3140":"0","3204":"0","3163":"0","3164":"0"},
{"3635":"0","3753":"0","3787":"0","3640":"0","3843":"0","3755":"0","3673":"1","3674":"0","3872":"0","3757":"0","3759":"0","3612":"0","3613":"0","3877":"0","3651":"0","3616":"1","3655":"1","3891":"0","3801":"0","3745":"1","3587":"0"},
{"98":"1","100":"1","101":"1","102":"1","104":"1","106":"1","109":"1","118":"0","110":"1","112":"1","114":"1","115":"0","116":"1"},
{"7700":"1","2351":"1","2352":"1","2354":"1","2378":"1","2356":"0","2380":"1","2357":"1","2382":"0","2383":"0","2364":"0","2403":"0","2365":"1","2366":"1","2387":"0","2388":"0","2389":"1","2392":"1","2398":"1","7807":"1","2400":"1","2401":"0"},
{"3892":"1","3950":"0","3952":"0","3924":"0","3898":"1","3900":"1","3955":"0","3932":"1","3985":"1","3907":"1","3960":"1","3910":"1","3938":"1","3965":"0","3915":"1","3917":"1","3992":"1"},
{"1327":"1","1354":"1","1356":"0","7210":"0","1360":"1","1334":"1","1363":"1","1302":"1","1277":"0","1365":"0","1278":"0","1336":"1","7343":"0","7184":"1","1370":"1","7186":"1","1373":"1","1287":"1","1375":"0","1378":"1","7327":"0"},
{"562":"0","535":"0","537":"1","563":"0","540":"1","566":"0","568":"0","548":"1","550":"1","552":"1","554":"0","584":"1","559":"0"},
{"9583":"0","9763":"0","9501":"1","9690":"1","9655":"1","9656":"1","9590":"1","9772":"0","9774":"0","9775":"1","9749":"1","9542":"0","9636":"1","9637":"1","9489":"1","9604":"1","9669":"0","9782":"1","9784":"1","9785":"1","9758":"1","9789":"0","9824":"0"},
{"7788":"1","7701":"0","7727":"1","7702":"1","7736":"1","7794":"0","7705":"1","7764":"1","7797":"1","7739":"1","7710":"1","7740":"0","7802":"1","7743":"1","7745":"1","7717":"1","7781":"1","7720":"1","7750":"1","7751":"1","7722":"0","7725":"1"},
{"834":"0","852":"1","838":"0","839":"1","855":"1","840":"1","858":"1","859":"1","860":"1","845":"1","846":"1","865":"1"},
{"7232":"0","7175":"1","7295":"0","7307":"0","7244":"1","7274":"0","7275":"0","7276":"0","7190":"1","7281":"0","7317":"0","7193":"1","7165":"1","7284":"0","7198":"0","7200":"0","7323":"0","7288":"0","7201":"1"},
{"2150":"1","2084":"0","2309":"0","2160":"0","2196":"0","2197":"1","2199":"1","2202":"1","2097":"0","2206":"1","2207":"1","2208":"1","2209":"1","2211":"1","2177":"1","2214":"1","5615":"1","2217":"0"},
{"8543":"0","8595":"0","8598":"1","8550":"1","8576":"1","8605":"1","8606":"0","8559":"0","8608":"1","8564":"1","8612":"1"},
{"4537":"1","4542":"1"},
{"4729":"1"},
{"3629":"1","3609":"1","3791":"1","3658":"1"},
{"3674":"0","3613":"1","3616":"1","3655":"1","3745":"1"},
{"638":"1","640":"1","643":"0","644":"1","5212":"1","647":"0","649":"1","650":"1","651":"1","652":"1","653":"1","658":"0","659":"1","663":"1","664":"1"},
{"8676":"1","8678":"1","8803":"1","8679":"1","8650":"0","8721":"0","8687":"1","8629":"1","8842":"1","8731":"0","8848":"1","8702":"1","8793":"1","8823":"1","8853":"1"},
{"1201":"1","1050":"1","1109":"1","1206":"0","1111":"1","1112":"0","1114":"1","1115":"1","1119":"1","1249":"0","1063":"1","1220":"0","1120":"1","1121":"0","1123":"1","1127":"1","1128":"1","1222":"1","1130":"0","1133":"0","1135":"0","1137":"1"},
{"2017":"0","2018":"1","2019":"1","2021":"1","2023":"0","2025":"1","2026":"1","2027":"1","2029":"1","2031":"1","2032":"1","2034":"0","2035":"1","2036":"1","2038":"0","2040":"1","2041":"0","2042":"1","2044":"1","2045":"1","2046":"0","2047":"1"},
{"283":"0","285":"0","311":"0","287":"1","314":"1","293":"0","294":"1","296":"1","297":"1","298":"1","324":"1","299":"1","300":"1","325":"0","327":"1","302":"0","303":"0","332":"1","335":"1","305":"1","306":"0"},
{"4298":"1","4073":"1","4132":"0","4083":"0","4084":"0","4086":"0","4025":"0","4189":"0","4091":"1","4093":"1","4063":"1","4011":"1","4032":"1","4102":"1","4068":"1","4014":"1","4107":"0"},
{"2671":"0","2644":"0","2785":"1","2555":"1","2559":"1","2760":"0","7966":"0","2791":"1","2565":"0","2765":"1","2681":"0","2711":"0","2743":"1","7971":"1","2599":"0","2747":"0","2804":"1","2778":"1","2748":"0","7911":"0","2668":"1","2754":"0","2580":"0"},
{"3315":"1","3283":"1","3327":"1","3288":"1","3290":"1","3331":"0","3293":"1","3332":"1","3296":"1","3298":"1","3301":"1","3465":"1","3309":"0","3338":"0","3311":"0","3341":"0","3345":"0","3312":"0"},
{"1450":"1","1452":"1","1419":"1","1420":"1","1459":"0","7478":"0","1460":"1","1421":"0","1466":"1","1467":"1","1468":"1","1471":"1","1439":"0","1474":"1","1476":"1","1443":"1","7386":"1","1445":"1","1477":"1","1478":"1"},
{"4939":"0","4981":"1","4984":"1","4942":"1","5014":"1","5016":"0","5080":"1","4960":"1","5156":"0","4962":"0","5003":"1","4969":"0","5033":"0","4971":"1","4972":"1","5069":"0"},
{"5142":"1","5171":"1","5151":"0","5115":"1","4955":"1","4961":"0","5183":"1","5192":"1","5194":"1"},
{"3281":"1","3316":"1","3318":"1","3284":"0","3322":"0","3326":"0","3287":"0","3328":"1","3330":"1","3291":"0","3292":"1","3294":"1","3332":"0","3297":"1","3299":"1","3333":"1","3303":"1"},
{"4078":"1","6813":"0","6789":"0","4116":"1","4183":"1","4240":"0","4052":"1","4243":"0","6765":"0","4160":"0","4247":"1","4271":"0","6819":"0","6797":"0","4092":"1","4274":"1","4056":"1","4059":"1","6801":"0","6842":"0","4224":"1"},
{"341":"0","342":"1","344":"1","347":"1","378":"1","379":"1","386":"0","387":"1","363":"1","364":"1","391":"1","367":"1"},
{"5843":"1","5782":"0"},
{"7051":"0","7053":"0","7029":"1","7366":"1","6999":"1","7118":"0","7373":"1","7064":"1","7094":"0","7069":"0","7411":"1","7388":"1"},
{"2784":"1","2703":"0","2731":"1","2762":"1","2792":"1","7826":"1","7898":"1","2710":"1","2742":"0","2796":"1","7828":"1","2745":"0","2721":"1","2771":"1","2666":"1","2690":"1","7835":"1","2779":"1","2577":"0","2751":"1","2780":"0","2753":"1"},
{"8648":"1"},
{"38":"0","99":"1","82":"1","60":"1","41":"1","76":"1","47":"0","51":"0","52":"1","70":"1","92":"1","53":"0","56":"0","96":"1"},
{"2516":"1","2517":"0","2519":"1","2520":"1","2485":"0","2523":"0","2525":"0","2527":"0","2529":"0","2496":"1","2534":"1","2535":"0","2536":"1","2540":"1","2541":"1","2542":"1","2508":"0","2544":"1","7746":"1","7723":"1","7724":"0","2548":"1"},
{"3949":"0","3896":"1","3902":"0","3904":"1","3933":"1","3934":"1","3909":"0","3912":"0","3966":"0","3967":"0","3944":"0","3916":"1","3947":"1"},
{"1263":"1","1268":"1","1270":"0","1272":"0","1273":"1","1275":"1","1276":"1","8553":"1","1306":"0","7269":"0","1309":"1","1310":"1","1311":"0","7243":"0","7349":"0","1320":"1","1323":"1","1289":"0","7362":"1","1293":"1"},
{"2220":"0","2305":"1","2224":"1","2227":"1","2228":"1","2230":"1","2231":"1","2262":"1","2312":"1","2313":"0","459":"0","2340":"1","2317":"1","2236":"1","2237":"0","2238":"1","2272":"1","2242":"1","2243":"1","2275":"1","2244":"0"},
{"447":"0","493":"0","450":"1","454":"1","456":"0","458":"0","460":"0","8608":"1","462":"1","485":"1","487":"0","467":"0"},
{"2924":"0","2953":"0","2925":"0","2926":"0","2933":"0","2960":"0","2994":"0","2964":"0","2968":"0","2969":"1","2941":"0","2974":"0","2975":"0","3003":"0","2977":"1","2980":"0"},
{"7206":"0","7207":"0","7212":"0","7173":"0","7336":"0","7338":"0","7180":"0","7342":"0","7309":"1","7185":"1","7157":"0","7279":"0","7166":"0","7167":"0","7230":"1","7322":"0","7359":"1","7360":"1","7203":"0","7294":"0"},
{"2080":"0","2081":"0","5444":"0","5365":"0","2085":"1","2086":"0","2087":"1","2089":"1","2168":"1","2095":"1","2096":"1","2201":"1","2098":"0","2180":"1","2145":"1"},
{"538":"1","542":"1","545":"1","569":"0","574":"0","549":"1","555":"1"},
{"669":"1","670":"1","671":"1","673":"1","675":"1","676":"0","679":"1","681":"1","684":"1","685":"0","687":"1","689":"0","691":"1","692":"1","696":"1","697":"1","698":"1","699":"1"},
{"4506":"0","4508":"0","4531":"0","4588":"0","4536":"1","4538":"0","4594":"1","4489":"1","4513":"0","4570":"1","4493":"1","4543":"0","4497":"1","4607":"1","4501":"0","4551":"1","4503":"0","4745":"0","4613":"0","4614":"1"},
{"4488":"0","4495":"0","4519":"0","4498":"0"},
{"642":"1","646":"1","648":"1","655":"1","657":"0","662":"1","665":"0","666":"0","667":"0"},
{"1141":"1","1174":"0","1177":"1","1144":"0","1178":"1","1207":"1","1145":"0","1186":"1","1147":"1","1188":"1","1189":"1","1191":"1","1192":"1","1193":"1","1194":"1","1160":"1","1124":"1","1196":"1","1102":"1","1258":"1","1198":"1","1199":"1"},
{"400":"0","402":"1","419":"0","404":"1","412":"1","415":"1"},
{"5270":"1","5271":"1","5294":"1","5825":"0","5767":"1","5297":"1","5298":"0","5280":"0","5299":"1","5300":"0","5777":"0","5284":"0","5748":"0","5784":"1","5691":"1","5668":"0","5305":"0","5306":"0","2120":"1","5310":"1","5311":"1","5312":"0"},
{"172":"0","149":"1","197":"1","124":"1","125":"1","179":"1","171":"0","198":"0","156":"1","127":"1","199":"1","159":"1","129":"1","131":"1","206":"0","163":"1","211":"0","165":"0","214":"0","215":"0","144":"1"},
{"4017":"1","4075":"0","4114":"1","3997":"1","3998":"1","4079":"1","4048":"1","3999":"0","4136":"0","4050":"1","4119":"1","4029":"1","4030":"1","4101":"1","4033":"1","4103":"1","4036":"1","4070":"0"},
{"3245":"1","3210":"1","3212":"1","3213":"1","3214":"1","3251":"0","3215":"1","3254":"0","3217":"0","3222":"1","3223":"1","3267":"0","3270":"0","3273":"0","3275":"1","3276":"1"},
{"2404":"1","2406":"0","7820":"0","7849":"1","2455":"1","2456":"1","2459":"0","7979":"1","7982":"1","2461":"0","7824":"1","2414":"1","2471":"1","2472":"0","7862":"1","2423":"1","7863":"1","7995":"0","7996":"1","2425":"1","2478":"1","2427":"0","7930":"0","7870":"0","7843":"1"},
{"1381":"1","1383":"0","1385":"0","1386":"1","1387":"1","1388":"1","1389":"1","1390":"0","1391":"1","1393":"1","1397":"1","1399":"1","1400":"1","1401":"1","1402":"1","1406":"1","1407":"0","1408":"0","1409":"0","1410":"1","1414":"1","1412":"1"},
{"4428":"0","4430":"1","4433":"1","4465":"1","4437":"1","4442":"1","4451":"0","4453":"0","4454":"0","4480":"1"},
{"1077":"1","980":"1","1083":"0","1085":"0","983":"1","984":"0","1086":"1","988":"0","1090":"1","993":"1","1150":"0","994":"1","995":"1","1098":"1","1162":"1","1000":"1","1003":"1","1006":"1","1101":"1"},
{"3248":"0","3252":"1","3253":"1","3255":"1","3218":"1","3220":"0","3260":"1","3265":"1","3266":"1","3268":"1","3228":"0","3272":"0","3274":"0","3234":"1","3240":"0"},
{"5829":"1","5894":"1","5735":"1","5737":"1","5711":"1","5716":"0","5953":"0"},
{"251":"1","310":"1","228":"0","349":"1","281":"1","351":"1","235":"1","386":"1","238":"1","239":"1","333":"0","273":"1"},
{"4235":"1","4300":"0","4208":"1","4112":"1","4238":"1","4182":"0","4184":"1","4310":"1","4241":"1","4249":"0","4222":"0","4322":"1","4223":"0","4284":"1","4286":"0","4326":"1","4290":"0","4203":"0"},
{"2813":"0","2815":"0","2844":"1","2847":"1","7875":"0","2902":"0","2903":"1","2824":"1","2889":"0","2852":"1","2837":"1","2939":"1","2854":"1","2857":"0","2913":"1","2862":"0","2865":"1","2869":"0","7836":"1","10438":"0","2871":"0","2898":"0","2919":"0"},
{"1513":"0","1547":"1","1691":"0","1518":"1","1694":"0","1697":"0","1571":"0","1494":"0","1589":"1","1498":"0","1595":"1","1709":"0","1559":"0","1710":"0","1503":"1","1630":"1","1562":"0","1713":"1","1565":"0","1567":"0"},
{"588":"1","610":"0","590":"1","591":"1","593":"0","595":"1","596":"1","597":"1","598":"0","599":"1","601":"1","602":"1","603":"1","604":"1","606":"0","607":"1","608":"1","609":"0"},
{"8710":"1","8682":"0","8649":"1","8722":"1","8783":"1","8727":"1","8657":"1","8705":"1","8817":"0","8734":"1","8821":"1","8822":"1","8670":"1","8824":"1"},
{"4431":"1","4460":"0","4463":"0","4438":"0","4470":"0","4446":"0","4472":"1","4475":"1","4452":"1","4476":"1","4482":"0"},
{"3016":"0","3071":"1","3076":"0","3079":"1","3027":"0","3082":"1","3111":"1","3114":"1","2952":"1"},
{"77":"0","78":"1","79":"0","83":"0","85":"1","86":"0","89":"0","97":"0","93":"0","94":"0","95":"0"},
{"869":"1","9652":"0","873":"1","874":"1","891":"1","877":"0","841":"1","878":"0","896":"1","879":"1","880":"1","6897":"0","884":"0","885":"1","849":"1"},
{"3894":"0","3895":"1","3976":"1","3926":"1","3930":"0","3954":"1","3982":"1","3903":"1","3973":"0","3961":"0","3988":"1","3989":"0","3918":"1","3971":"0"},
{"1355":"0","1328":"1","1329":"1","1330":"1","1357":"0","1332":"1","1369":"1","1339":"0","1340":"1","1372":"1","1343":"1","1374":"1","1344":"0","1377":"1","1350":"0","1353":"1"},
{"9497":"0","9467":"1","9619":"0","9499":"1","9589":"0","9532":"1","9665":"0","9480":"1","9481":"1","9540":"1","9812":"0","9483":"0","9542":"1","9490":"1","9668":"1","9610":"1","9550":"0","9494":"0","9792":"1","9553":"1"},
{"448":"0","472":"1","473":"0","453":"1","455":"1","491":"1","477":"0","457":"1","459":"1","479":"1","461":"0","480":"1","481":"1","482":"0","486":"1","464":"1","488":"1","489":"0","466":"1"},
{"4587":"1","4529":"1","4533":"1","4534":"0","4560":"1","4561":"1","4754":"1","4562":"1","4662":"0","4757":"1","4731":"0","4667":"1","4668":"0","4762":"1","4672":"0","4550":"1","4680":"1","4581":"1","4681":"1","4584":"1","4714":"1"},
{"3097":"0","3074":"0","3075":"1","3102":"1","3103":"1","3025":"0","3048":"0","3081":"0","3116":"1","3061":"0","3036":"0"},
{"5594":"1","5564":"1","5596":"1","5566":"0","5571":"0","5606":"0","5576":"0","5577":"1","5581":"0","5582":"1","5583":"0","5584":"1","2147":"1","5588":"1","5589":"1","5617":"0","5618":"1"},
{"561":"0","532":"1","567":"1","544":"1","553":"0","579":"0","580":"1"},
{"867":"1","868":"1","870":"0","871":"1","872":"1","875":"1","876":"1","881":"1","883":"0","887":"1","900":"0","886":"1"},
{"396":"1","397":"1","398":"1","399":"1","401":"1","403":"0","406":"1","407":"1","408":"1","420":"0","409":"1","410":"0","411":"1","413":"0","414":"1","418":"0"},
{"979":"1","1234":"0","1206":"1","1238":"0","1210":"1","1211":"1","987":"1","1245":"1","1089":"1","990":"1","1059":"1","992":"0","1064":"1","1093":"1","1155":"0","1157":"0","1158":"0","997":"0","1159":"0","1163":"0","1007":"1","1008":"1"},
{"120":"1","195":"0","176":"0","177":"0","180":"0","200":"0","128":"1","201":"0","130":"1","132":"0","205":"0","134":"1","135":"1","185":"1","136":"1","187":"0","189":"0","141":"0","143":"1"},
{"5822":"0","5647":"1","5648":"0","5893":"1","5677":"1","5651":"1","2410":"0","5628":"1","5629":"1","5708":"1","5631":"1","5935":"1","5658":"1","5634":"1","5723":"1","5666":"1","5918":"1","5639":"1","5725":"1","5644":"0","5643":"1","5672":"0"},
{"4039":"0","4077":"1","4156":"1","4082":"1","4157":"1","4087":"1","4088":"0","4161":"0","4120":"0","4060":"1","4144":"1","4094":"1","4126":"1","4012":"1","4149":"1","4066":"0","4171":"1","4177":"1","4175":"0","4154":"1"},
{"2406":"0","2408":"1","2409":"0","7823":"1","2410":"1","2460":"1","2439":"0","2465":"0","2466":"1","2418":"1","7986":"1","2441":"1","7972":"1","7861":"1","2444":"0","7926":"1","7833":"1","7958":"1","2476":"1","2479":"1","2480":"1","7841":"1"},
{"3445":"1","3447":"1","3448":"1","3449":"1","3450":"1","3415":"1","3418":"1","3453":"1","3419":"1","3420":"0","3458":"1","3422":"1","3427":"1","3430":"1","3431":"1","3432":"0","3438":"0","3463":"0","3466":"0","5217":"1","3467":"1","3471":"1"},
{"1574":"0","1687":"0","1514":"0","1517":"1","1666":"0","1521":"1","1526":"1","1529":"1","1497":"1","1558":"1","1534":"0","1563":"1","1542":"0","1683":"0","1604":"0","1638":"0"},
{"4617":"0","4619":"1","4625":"1","4629":"1","4633":"1","4634":"1","4469":"1","4636":"0","4637":"1","4641":"1","4829":"0","4651":"0","4652":"1","4902":"0"},
{"700":"1","701":"1","674":"0","702":"1","704":"0","705":"1","707":"1","709":"1","712":"1","682":"1","713":"1","714":"1","686":"1","717":"0","719":"0","720":"0","723":"0"},
{"4458":"1","4432":"1","4462":"1","4464":"0","4468":"0","4471":"1","4477":"1","4478":"0"},
{"5826":"0","5793":"0","5828":"0","5930":"0","5650":"1","5832":"0","5931":"0","5896":"0","5973":"0","5837":"0","5738":"0","5655":"0","5739":"0","5941":"0","5842":"0","5742":"1","5911":"0","5987":"0","5664":"1","5640":"0","5671":"1"},
{"7024":"1","7055":"1","7061":"0","7036":"1","7020":"0","7068":"1","7045":"1","7073":"1","7410":"1","7082":"0"},
{"145":"1","119":"1","146":"1","148":"1","151":"0","152":"1","153":"1","123":"0","154":"1","157":"1","158":"1","138":"1","139":"1","166":"1","140":"1","188":"1","142":"1","168":"1","191":"0","169":"1","170":"1"},
{"4206":"0","4236":"1","4181":"1","4233":"0","4267":"0","4186":"0","4159":"1","4188":"1","4217":"0","4194":"1","4195":"1","4220":"1","4254":"0","4255":"0","4225":"1","4170":"0","4172":"1"},
{"2642":"0","2645":"0","7820":"1","2554":"0","2674":"0","2678":"1","2656":"1","2657":"1","2711":"1","2659":"0","2661":"0","2662":"0","2715":"1","7882":"1","7992":"1","2746":"0","2799":"0","7888":"1","7954":"1","2695":"0","7844":"1","2698":"0"},
{"613":"1","615":"1","616":"0","618":"1","619":"1","620":"0","621":"1","624":"1","625":"0","627":"0","629":"1","631":"0","633":"1","635":"1"},
{"8681":"1","8812":"1","8636":"1","8762":"1","8819":"0","8873":"1"},
{"57":"1","80":"0","40":"0","43":"0","45":"0","107":"0","48":"0","64":"0","91":"0"},
{"2483":"0","2486":"0","2493":"0","2494":"1","2495":"1","7706":"0","7708":"0","2531":"1","2499":"1","7713":"0","2538":"0","2539":"0","2503":"0","2504":"0","2506":"0","2509":"1","2510":"1","2511":"1","2545":"1","2547":"0","2514":"0","2549":"0"},
{"3948":"0","3927":"1","3901":"1","3956":"0","3957":"1","3931":"0","3986":"1","3962":"1","3937":"0","3964":"0","3987":"0","3913":"1","3919":"0"},
{"1264":"0","1265":"0","1266":"0","1269":"1","1271":"0","1303":"0","1280":"0","1281":"0","1312":"0","1284":"0","1285":"1","1322":"1","1290":"1","1291":"1","1292":"0","1294":"0"},
{"2279":"0","2250":"0","2251":"0","5422":"1","2253":"0","2255":"1","2283":"1","2257":"0","2299":"0","2263":"0","2314":"1","2093":"0","2270":"0","2100":"0","2277":"0"},
{"736":"1","737":"1","740":"1"},
{"492":"0","518":"0","495":"0","496":"0","497":"1","498":"0","499":"0","484":"0","507":"0","510":"0"},
{"7790":"1","7792":"0","7728":"0","7729":"1","7731":"0","7735":"0","7707":"0","7737":"0","7765":"1","7796":"1","7738":"0","7712":"1","7803":"1","7805":"0","7776":"0","7747":"0","7748":"0","7808":"1","7752":"1","7813":"1","7787":"0","7816":"0"},
{"835":"1","851":"0","836":"1","853":"1","854":"0","856":"1","850":"1","857":"1","843":"1","844":"1","863":"1","848":"1","866":"0"},
{"7672":"0","7647":"0","7648":"1","7623":"1","7676":"0","7677":"0","7625":"1","7656":"0","7629":"0","7681":"0","7682":"0","7683":"1","7636":"1","7662":"0","7689":"0","7641":"0","7642":"0","7692":"0","7693":"1","7668":"0","7698":"1"},
{"7334":"0","7299":"0","7176":"0","7237":"1","7339":"1","7243":"0","7316":"1","7356":"0","7321":"1","7287":"0","7326":"1"},
{"4527":"0","4507":"0","4509":"0","4540":"0","4592":"0","4563":"0","4593":"1","4487":"0","4571":"1","4736":"0","4573":"1","4601":"1","4546":"1","4499":"1","4549":"0","4575":"1","4579":"1","4582":"1","4556":"0"},
{"2125":"1","2103":"1","2126":"1","2104":"0","2106":"1","2130":"0","2109":"0","2110":"0","2131":"1","2111":"1","2112":"0","2114":"0","2115":"0","2117":"1","2119":"0","2203":"0","2137":"0","2124":"0","2121":"0","2122":"1","2142":"0","2146":"1"},
{"8544":"0","8546":"1","8551":"1","8601":"0","8602":"0","8604":"0","8582":"0","8607":"1","8558":"1","8561":"1","8565":"0","8567":"0","8613":"1","8614":"1","8568":"1"},
{"4530":"0","4657":"1","4532":"1","4661":"1","4687":"0","4663":"1","4664":"0","4666":"0","4541":"0","4496":"0","4739":"0","4675":"1","4701":"0","4548":"1","4704":"0","4708":"0","4684":"0","4613":"1","4746":"0"},
{"728":"1","750":"0","751":"0","732":"0","733":"0","735":"1","752":"1","754":"0","738":"1","757":"1","758":"0","745":"1"},
{"792":"0","794":"1","795":"1","767":"0","775":"0","823":"0","780":"1","781":"1","782":"1","783":"1","807":"1","784":"1","789":"0","809":"0"},
{"907":"0","1202":"1","981":"0","947":"0","953":"1","1065":"0","955":"1","927":"0","956":"1","961":"0","963":"0","964":"1","968":"1","969":"1","970":"1","937":"1","973":"0","939":"0","974":"1","975":"0","976":"1","977":"1"},
{"421":"1","422":"1","423":"1","425":"0","426":"0","427":"0","428":"1","430":"1","431":"0","433":"1","437":"1","439":"0","440":"1","443":"1","445":"1"},
{"4620":"0","4623":"1","4624":"1","4632":"1","4638":"1","4639":"0","4640":"1","4643":"0","4645":"1","4653":"1"},
{"9825":"1","9860":"1","9832":"1","10090":"0","10279":"1","9835":"1","10185":"0","9836":"1","10309":"1","10311":"1","10025":"0","10197":"0","9848":"0"},
{"339":"0","370":"0","371":"0","375":"0","343":"1","7596":"1","7599":"0","376":"1","7601":"0","350":"0","380":"0","357":"1","382":"1","383":"1","384":"1","7582":"1","7608":"0","388":"1","390":"0","393":"1","7618":"0","395":"0"},
{"4019":"0","4021":"0","4022":"0","9207":"0","4049":"1","4000":"1","4138":"1","4024":"0","9239":"0","4139":"0","4053":"1","4001":"0","4141":"1","4007":"0","4097":"1","4064":"1"},
{"1416":"1","1453":"0","1454":"1","1413":"0","1422":"1","1423":"0","1425":"0","8779":"1","1428":"0","1432":"0","1434":"0","1435":"1","1436":"0","1472":"0","1438":"0","1440":"0","1481":"0"},
{"7960":"0","2550":"0","2551":"0","7940":"1","2581":"1","2613":"1","2552":"0","2585":"1","2556":"1","2620":"1","7853":"1","2566":"0","2628":"0","2568":"0","2634":"1","2600":"1","2574":"0","2605":"0","2636":"0","7951":"0","2576":"0","7953":"1"},
{"906":"1","908":"1","909":"1","911":"0","912":"1","944":"1","913":"1","1208":"0","915":"1","917":"1","949":"1","924":"1","926":"1","930":"1","931":"1","1004":"0","938":"0","941":"0","942":"1"},
{"194":"1","173":"1","174":"1","175":"1","122":"0","181":"1","182":"0","161":"0","203":"1","184":"1","207":"1","208":"1","186":"0","209":"1","164":"0","216":"1","218":"0","192":"0","193":"0"},
{"10020":"0","10021":"0","9916":"0","10024":"1"},
{"4301":"0","9462":"0","4212":"1","4308":"1","4309":"1","9443":"1","9446":"1","4121":"0","4256":"1","9451":"1","4278":"0","4320":"0","4283":"1","4285":"1","4201":"1","4287":"0","9454":"1","4258":"1","4292":"1","9426":"1","4331":"1","9461":"0"},
{"2405":"1","2453":"0","7977":"1","7822":"0","2435":"0","2411":"1","2412":"0","2413":"1","2415":"0","7967":"1","2416":"1","7858":"0","2420":"1","2421":"0","7990":"1","2422":"1","7909":"1","2424":"1","2445":"1","7930":"0","7839":"0","7840":"1"},
{"4617":"1"},
{"7475":"1","7453":"0","7485":"0","7487":"1","7401":"0","7378":"1","7431":"1","7432":"1","7494":"1","7402":"1","7497":"0","7461":"0","7132":"0","7408":"0","7382":"1","7499":"0","7384":"1","7385":"1","7502":"0","7471":"1","7441":"0","7442":"0"},
{"8618":"1","8680":"0","8648":"1","8651":"1","8624":"0","8625":"1","8653":"0","8631":"1","8869":"1","8656":"1","8633":"1","8634":"1","8665":"1","8637":"1","8639":"1","8669":"0","8875":"1","8643":"1","8741":"0"},
{"614":"0","589":"1","617":"1","592":"1","622":"1","623":"0","626":"1","637":"1","630":"0","605":"1","634":"1","636":"0"},
{"3126":"0","3148":"1","3170":"1","3149":"0","3128":"0","3167":"1","3153":"1","3132":"0","3200":"0","3175":"0","3159":"0","3138":"0","3162":"1","3181":"0","3182":"0","3183":"0","3206":"0","3143":"0","3207":"1","3144":"0","3188":"0"},
{"3123":"1","3147":"1","3169":"0","3189":"1","3192":"0","3127":"1","3193":"0","3194":"0","3151":"0","3173":"1","3154":"0","3198":"1","3156":"0","3176":"0","3136":"0","3160":"0","3161":"0","3141":"0","3205":"0","3185":"1","3186":"1","3166":"0"},
{"3145":"0","3146":"0","3168":"1","3150":"0","3171":"0","3172":"0","3129":"0","3152":"0","3823":"0","3195":"0","3134":"0","3199":"1","3155":"0","3135":"0","3202":"1","3177":"0","3159":"0","3180":"1","3142":"0","3184":"0","3208":"1"},
{"59":"1","42":"1","62":"1","44":"1","63":"0","66":"0","67":"1","50":"0","68":"1","69":"0","71":"0","72":"1","73":"1","74":"1","75":"0","55":"1"},
{"2376":"1","2353":"0","2355":"1","2379":"0","2358":"0","2360":"0","2363":"0","2385":"1","2391":"0","2393":"1","2372":"1","2394":"1","2373":"0","2374":"1","2396":"1","2401":"0","2402":"0"},
{"3893":"0","3922":"0","3897":"1","3953":"1","3977":"0","3899":"0","3978":"1","3906":"1","3963":"1","3911":"1","3943":"0","3974":"1","3994":"1"},
{"1331":"1","1358":"0","1333":"1","1362":"1","1364":"1","1335":"1","1337":"1","1338":"1","1341":"0","1346":"1","1347":"1","1376":"0","1348":"1","1349":"0","1379":"1","1380":"1"},
{"511":"0","512":"1","514":"1","515":"0","494":"0","516":"1","521":"1","498":"0","523":"1","500":"1","502":"0","505":"1","527":"0","506":"1","508":"1","529":"0","531":"0"},
{"2254":"1","5366":"0","2222":"0","494":"0","2331":"0","5371":"0","5397":"0","2336":"0","2337":"0","2264":"1","5377":"0","2289":"0","2339":"0","5409":"1","5410":"1","5412":"1","5413":"1","2239":"0","2240":"1","2241":"1","5415":"1","2274":"1"},
{"4485":"1","4528":"0","4683":"1","4564":"1","4597":"0","4511":"1","4601":"0","4520":"1","4521":"0","4522":"1","4606":"1","4610":"1","4526":"1","4557":"1"},
{"7755":"1","7791":"0","7758":"1","7793":"1","7759":"0","7762":"1","7763":"1","7767":"0","7769":"1","7741":"1","7770":"1","7771":"1","7773":"1","7774":"0","7778":"1","7779":"0","7780":"1","7782":"1","7784":"1","7785":"0","7786":"1","7814":"1","7815":"1"},
{"888":"1","905":"1","890":"1","892":"1","894":"1","897":"0","898":"1","899":"1","901":"1","902":"1","903":"1","904":"0"},
{"7145":"1","7210":"1","7181":"1","7182":"1","7343":"0","7183":"1","7184":"1","7186":"1","7158":"1","7349":"0","7159":"0","7313":"0","7229":"1","7252":"1","7169":"1","7324":"0","7327":"0"},
{"5442":"1","2149":"1","2127":"1","2153":"0","2156":"0","2157":"0","5600":"0","2165":"1","2132":"1","2166":"1","5605":"1","2134":"1","2173":"1","2174":"0","2179":"1","5614":"0","2144":"1","5460":"0","5619":"0"},
{"8594":"1","8571":"1","8548":"1","8600":"1","8603":"1","8609":"1","8610":"1","8615":"1","8616":"1"},
{"4690":"1","4541":"0","4500":"1","4610":"0","4504":"1"},
{"4690":"0","4541":"0","4500":"0","4610":"0","4504":"1"},
{"446":"0","424":"0","425":"1","427":"0","429":"1","432":"0","434":"1","435":"0","436":"1","438":"1","441":"0","442":"0","444":"0"},
{"1011":"1","1012":"0","1047":"1","1017":"1","1018":"1","1024":"0","1027":"0","1029":"1","1067":"1","1030":"1","1070":"1","1033":"1","1034":"1","1035":"0","971":"1","1037":"1","1038":"1","1039":"1","1040":"1","1073":"1","1041":"0","1043":"1"},
{"221":"1","222":"0","224":"0","288":"1","226":"1","227":"1","229":"1","230":"0","232":"1","318":"1","319":"1","250":"1","234":"1","362":"1","326":"0","236":"0","237":"1","330":"0","304":"0","242":"1","243":"1","245":"0"},
{"3351":"1","3354":"1","3386":"0","3355":"1","3387":"0","3358":"1","3359":"1","3389":"0","3363":"1","3391":"1","3367":"0","3369":"1","3398":"1","3399":"1","3372":"1","3374":"1","3403":"0"},
{"3350":"0","3383":"1","3384":"1","3357":"0","3360":"0","3361":"1","3390":"1","3362":"1","3363":"0","3364":"1","3396":"1","3371":"1","3401":"1","3402":"1"},
{"2049":"1","2050":"0","2051":"0","2052":"1","2053":"1","2055":"1","2056":"1","2057":"0","2058":"0","2059":"1","2060":"1","2061":"1","2063":"1","2064":"1","2068":"1","2070":"0","2071":"1","2073":"1","2043":"0","2075":"1","2076":"1","2078":"1"},
{"4042":"1","4043":"0","4076":"1","4047":"0","4085":"0","4005":"0","4026":"0","4006":"1","4125":"1","4009":"1","4098":"0","4168":"1","9353":"0","4100":"1","4035":"0","4013":"1","4105":"0"},
{"2612":"0","2553":"1","2583":"0","2586":"1","2589":"1","2616":"0","2618":"1","2619":"1","2621":"1","2676":"1","2592":"1","2593":"0","2677":"1","2598":"1","2684":"1","2570":"1","7945":"0","2603":"1","2573":"1","2604":"1","2637":"0","7976":"1","2639":"1","2640":"1"},
{"764":"0","811":"1","815":"0","766":"1","798":"1","818":"1","771":"1","773":"1","774":"1","777":"1","779":"1","823":"1","805":"0","806":"1","787":"1","790":"1","5213":"1"},
{"1607":"0","1643":"0","1611":"0","1693":"0","1551":"0","1618":"1","1669":"0","1651":"0","1524":"0","1653":"0","1656":"0","1593":"1","1557":"1","1597":"1","1598":"1","1628":"0","1634":"0","1566":"0","1640":"0"},
{"4839":"1","4807":"1","4842":"1","4848":"1","4855":"1","4858":"1","4896":"0","4897":"0","4860":"1","4864":"0","4833":"0","4867":"1","4868":"1"},
{"3531":"1","3532":"1","3664":"0","3535":"1","3476":"0","3537":"0","3567":"1","3598":"1","3570":"0","3605":"0","3488":"0","3589":"0","3578":"0","3579":"0","3553":"0","3886":"1","3557":"1","3525":"0","3560":"0","3498":"0","3585":"0"},
{"252":"0","224":"1","255":"1","256":"1","257":"0","258":"1","259":"0","260":"1","262":"0","263":"0","264":"1","266":"1","267":"1","270":"1","271":"1","272":"0","275":"1","276":"0","277":"0","278":"1","280":"1"},
{"1108":"0","1014":"1","1232":"0","1203":"1","1051":"1","1235":"1","1237":"1","1019":"0","1113":"1","1212":"1","1243":"0","1244":"1","1214":"1","1022":"1","1023":"1","1219":"1","1065":"1","1221":"1","1071":"1","1254":"0","1036":"1","1226":"1"},
{"10266":"1","10083":"1","10086":"1","10091":"1","9864":"1","10094":"1","10026":"0","10103":"0"},
{"1776":"0","1725":"1","1667":"1","1727":"1","1847":"0","1704":"0","1852":"0","1857":"0","1861":"0"},
{"3565":"1","3568":"0","3510":"0","3639":"0","3589":"0","3577":"0","3550":"1","3578":"1","3579":"1","3553":"0","3798":"0","3557":"0","3560":"0","3585":"0","3586":"1"},
{"2843":"1","2878":"1","2880":"1","7963":"1","3045":"1","3073":"1","2901":"1","2823":"1","2850":"0","2827":"0","2892":"1","2830":"1","2962":"0","3052":"0","2840":"1","2856":"1","2944":"0","2866":"0","2867":"1","3065":"0","2873":"1"},
{"3629":"1","3597":"0","3752":"0","3484":"1","3601":"0","3672":"0","3673":"0","3609":"1","3791":"1","3613":"1","3616":"1","3655":"1","3658":"1","3744":"1","3745":"1"},
{"3664":"0","3807":"0","3593":"0","3814":"0","3697":"0","3698":"0","3484":"0","3731":"0","3845":"0","3645":"0","3747":"0","3876":"0","3615":"0","3794":"0","3680":"0","3803":"0","3881":"0","3767":"0","3768":"1","3625":"0","3856":"0"},
{"98":"1","100":"1","101":"1","102":"1","104":"1","106":"0","109":"0","118":"0","110":"1","112":"1","114":"1","115":"0","116":"1"},
{"7700":"1","2351":"1","2352":"1","2354":"1","2378":"1","2356":"0","2380":"1","2357":"1","2382":"0","2383":"1","2364":"0","2403":"1","2365":"1","2366":"1","2387":"1","2388":"0","2389":"1","2392":"1","2398":"1","7807":"1","2400":"1","2401":"0"},
{"3892":"1","3950":"0","3952":"0","3924":"0","3898":"1","3900":"1","3955":"0","3932":"1","3985":"1","3907":"1","3960":"1","3910":"1","3938":"1","3965":"0","3915":"1","3917":"1","3992":"1"},
{"1327":"1","1354":"1","1356":"1","1360":"1","1334":"1","1363":"1","1302":"1","1277":"0","1365":"1","1278":"1","1336":"1","1370":"1","1373":"0","1287":"1","1375":"1","1378":"0"},
{"9583":"0","9763":"0","9501":"1","9690":"1","9655":"1","9656":"1","9590":"0","9772":"0","9774":"1","9775":"1","9749":"0","9636":"0","9637":"1","9489":"1","9604":"1","9669":"0","9544":"0","9782":"0","9784":"1","9785":"1","9758":"0","9789":"0","9824":"0"},
{"562":"0","535":"0","537":"1","563":"0","540":"0","566":"0","8579":"0","568":"0","548":"0","550":"0","552":"0","554":"1","584":"1","559":"1"},
{"4537":"1","4542":"1"},
{"4729":"1"},
{"7788":"0","7701":"1","7727":"1","7702":"1","7736":"1","7794":"1","7705":"1","7764":"0","7797":"1","7739":"1","7710":"1","7740":"1","7802":"1","7743":"1","7745":"1","7717":"1","7781":"1","7720":"1","7750":"0","7751":"0","7722":"0","7725":"1"},
{"834":"0","852":"1","838":"0","839":"0","855":"0","840":"1","858":"0","859":"1","860":"0","845":"1","846":"0","865":"1"},
{"6301":"0","6144":"0","6304":"0","6370":"0","6338":"1","6340":"0","6343":"1","6183":"1","6226":"0","6351":"1","6158":"0","6235":"0","6236":"0","6332":"0"},
{"7232":"1","7175":"1","7177":"1","7179":"1","7295":"1","7307":"0","7244":"0","7274":"1","7276":"1","7190":"1","7314":"0","7281":"0","7317":"1","7193":"1","7165":"1","7284":"1","7198":"1","7200":"1","7323":"1","7288":"1","7201":"1"},
{"8543":"0","8595":"0","8598":"1","8550":"0","8576":"1","8605":"1","8606":"1","8559":"1","8564":"1","8612":"1","8589":"1"},
{"2150":"1","5566":"1","2084":"1","2309":"0","2160":"1","2196":"0","2197":"1","2199":"1","2202":"0","2097":"0","2206":"1","2207":"0","2208":"1","2209":"1","2211":"0","2177":"1","2214":"1","5615":"1","2217":"1"},
{"638":"1","640":"1","643":"1","644":"1","5212":"1","647":"1","649":"1","650":"1","651":"1","652":"1","653":"1","658":"1","659":"1","663":"0","664":"0"},
{"8676":"1","8678":"1","8803":"1","8679":"1","8648":"1","8682":"1","8650":"0","8721":"0","8687":"1","8749":"1","8629":"1","8842":"1","8758":"1","8731":"0","8848":"1","8702":"1","8793":"0","8823":"1","8853":"1"},
{"1201":"1","1050":"1","1109":"1","1206":"0","1111":"1","1112":"0","1114":"1","1115":"1","1119":"1","1249":"0","1063":"0","1220":"0","1120":"0","1121":"0","1123":"1","1127":"1","1128":"1","1222":"1","1130":"1","1133":"0","1135":"1","1137":"1"},
{"283":"1","285":"0","311":"0","287":"1","314":"0","315":"1","293":"0","294":"1","296":"0","297":"1","298":"0","324":"0","299":"1","300":"1","325":"0","327":"1","302":"1","303":"0","332":"0","335":"0","305":"1","306":"1"},
{"2017":"0","2018":"0","2019":"0","2021":"0","2023":"1","2025":"1","2026":"1","2027":"1","2029":"0","2031":"1","2032":"0","2034":"0","2035":"1","2036":"0","2038":"1","2040":"1","2041":"1","2042":"1","2044":"1","2045":"1","2046":"0","2047":"1"},
{"4298":"1","4073":"1","4132":"0","4083":"1","4084":"1","4086":"0","4025":"0","4189":"0","4091":"0","4093":"0","4063":"1","4011":"1","4032":"1","4102":"1","4068":"1","4014":"1","4107":"0"},
{"3315":"1","3283":"0","3284":"1","3326":"1","3327":"1","3288":"1","3290":"1","3331":"0","3293":"1","3332":"1","3296":"1","3298":"1","3301":"0","3308":"1","3309":"0","3338":"0","3311":"1","3341":"0","3345":"1","3347":"1","3312":"1"},
{"2671":"0","2644":"0","2785":"1","2555":"1","2559":"1","2760":"0","7966":"1","2791":"1","2565":"0","2765":"1","2681":"0","2711":"0","2743":"1","7971":"1","2599":"0","2747":"0","2804":"1","2778":"1","2748":"0","7911":"0","2668":"1","2754":"1","2580":"0"},
{"1450":"1","1452":"1","1419":"1","1420":"1","1459":"0","1460":"1","1421":"0","1466":"1","1467":"1","1468":"0","1471":"1","1439":"0","1474":"1","1476":"1","1443":"1","1445":"1","1477":"1","1478":"1"},
{"4939":"0","4981":"1","4984":"1","4942":"1","5014":"1","5016":"0","5080":"0","4960":"0","5156":"0","4962":"1","5003":"1","4969":"0","5033":"0","4971":"1","4972":"0","5069":"1"},
{"3281":"0","3316":"1","3318":"1","3322":"0","3286":"1","3287":"1","3328":"1","3330":"1","3291":"0","3292":"1","3294":"1","3332":"0","3297":"1","3299":"1","3333":"1","3303":"0"},
{"5843":"0","5782":"0","5758":"1"},
{"2784":"1","2703":"1","2731":"1","2762":"1","2792":"1","7826":"1","7898":"0","2710":"1","2742":"1","2796":"1","7828":"1","2745":"0","2721":"1","2771":"1","2666":"1","2690":"1","7835":"1","2779":"0","2577":"0","2751":"0","2780":"0","2753":"0"},
{"7051":"1","7053":"1","7029":"1","7366":"1","7478":"1","6999":"1","7060":"1","7118":"1","7373":"1","7064":"1","7094":"0","7069":"0","7411":"1","7386":"1","7388":"1"},
{"4078":"0","6813":"0","6789":"1","4116":"1","4183":"1","4240":"0","4052":"1","4243":"0","6765":"0","4160":"0","4247":"1","4271":"0","6819":"1","6797":"1","4092":"1","4274":"1","4056":"1","4059":"1","4318":"0","6801":"1","6842":"1","4224":"1"},
{"341":"0","342":"1","344":"0","347":"0","378":"0","379":"0","386":"0","387":"1","363":"0","364":"0","391":"0","367":"0"},
{"38":"0","99":"0","82":"1","60":"1","41":"1","76":"0","47":"0","51":"0","52":"1","70":"1","92":"0","53":"0","56":"0","96":"1"},
{"3949":"0","3896":"1","3902":"0","3904":"0","3933":"1","3934":"1","3909":"0","3912":"0","3966":"0","3967":"0","3944":"0","3916":"0","3947":"1"},
{"2516":"1","2517":"0","2519":"1","2520":"1","2485":"0","2523":"0","2525":"0","2527":"1","2529":"0","2496":"1","2534":"1","2535":"1","2536":"1","2540":"1","2541":"1","2542":"0","2508":"0","2544":"1","7746":"0","7723":"1","7724":"0","2548":"1"},
{"4488":"1","4495":"1","4519":"0","4498":"0"},
{"1263":"1","1268":"0","1270":"0","1272":"0","1273":"1","1275":"1","1276":"0","8553":"1","1306":"0","1309":"1","1310":"1","1311":"0","1320":"1","1323":"1","1289":"0","1293":"1"},
{"2220":"1","2305":"1","2224":"1","2227":"0","2228":"1","2230":"1","2231":"1","2262":"1","2312":"1","2313":"0","459":"0","2340":"1","2317":"1","2236":"1","2237":"1","2238":"1","2272":"1","2242":"1","2243":"1","2275":"1","2244":"0"},
{"447":"0","493":"0","450":"1","454":"0","456":"0","458":"1","460":"0","8608":"1","462":"0","485":"0","487":"0","467":"0"},
{"2924":"0","2953":"0","2925":"0","2926":"0","2933":"0","2960":"0","2994":"0","2964":"0","2968":"0","2969":"1","2941":"0","2974":"0","2975":"0","3003":"0","2977":"1","2980":"0"},
{"7206":"0","7207":"1","7212":"1","7173":"1","7336":"0","7338":"0","7180":"1","7342":"1","7269":"0","7309":"1","7185":"1","7157":"0","7279":"1","7166":"1","7167":"1","7230":"1","7322":"1","7360":"1","7203":"1","7362":"1","7294":"1"},
{"7232":"0","7175":"0","7177":"0","7179":"0","7295":"0","7307":"0","7244":"0","7274":"0","7275":"0","7276":"0","7190":"0","7314":"1","7281":"0","7317":"0","7193":"0","7165":"0","7284":"0","7198":"0","7200":"0","7323":"1","7288":"0","7201":"0"},
{"538":"1","542":"1","545":"1","569":"0","574":"0","549":"1","555":"1"},
{"2080":"1","2081":"1","5444":"0","5365":"0","2085":"1","2086":"1","2087":"0","2089":"0","2168":"1","2095":"1","2096":"1","2201":"1","2098":"1","2180":"1","2145":"0"},
{"669":"1","670":"0","671":"1","673":"1","675":"1","676":"0","679":"1","681":"1","684":"1","685":"1","687":"0","689":"0","691":"1","692":"1","696":"1","697":"0","698":"1","699":"1"},
{"4506":"0","4508":"0","4531":"0","4588":"0","4536":"0","4723":"0","4538":"0","4594":"1","4489":"1","4513":"1","4570":"0","4493":"1","4543":"0","4497":"0","4607":"1","4501":"1","4551":"0","4503":"1","4745":"0","4613":"0","4614":"1"},
{"9149":"1","9179":"0","9152":"0","9156":"0","9157":"0","9158":"0","9160":"0","9161":"0","9192":"0","9164":"0","9166":"0","9196":"0","9169":"0","9170":"0","9171":"0","9200":"0"},
{"3123":"0","3147":"0","3169":"0","3189":"0","3192":"0","3127":"1","3193":"0","3194":"0","3151":"0","3173":"1","3154":"1","3198":"1","3156":"0","3176":"0","3136":"0","3160":"0","3161":"0","3141":"0","3205":"0","3185":"1","3186":"1","3166":"0"},
{"400":"0","402":"1","419":"0","404":"0","412":"0","415":"1"},
{"642":"1","646":"1","648":"1","655":"1","657":"0","662":"1","665":"0","666":"0","667":"1"},
{"1141":"1","1174":"1","1177":"1","1144":"0","1178":"1","1207":"1","1145":"0","1186":"1","1147":"1","1188":"0","1189":"1","1191":"1","1192":"1","1193":"1","1194":"1","1160":"0","1124":"1","1196":"1","1102":"1","1258":"1","1198":"1","1199":"1"},
{"5270":"1","5271":"1","5294":"1","5825":"0","5767":"1","5297":"1","5298":"0","5280":"0","5299":"1","5300":"1","5777":"1","5284":"0","5748":"1","5784":"1","5691":"1","5668":"1","5305":"0","5306":"0","2120":"1","5310":"1","5311":"1","5312":"1"},
{"172":"1","149":"0","197":"1","124":"1","125":"1","179":"1","171":"0","198":"1","156":"1","127":"1","199":"0","159":"1","129":"1","131":"1","206":"1","163":"1","211":"1","165":"0","214":"1","215":"0","144":"1"},
{"4017":"1","4075":"1","4114":"0","3997":"1","3998":"1","4079":"1","4048":"1","3999":"0","4136":"0","4050":"1","4119":"1","4029":"1","4030":"0","4101":"1","4033":"0","4103":"1","4036":"1","4070":"0"},
{"4428":"0","4430":"0","4433":"0","4465":"1","4628":"1","4437":"1","4442":"1","4470":"0","4451":"1","4453":"0","4454":"0","4480":"1"},
{"1381":"1","1383":"0","1385":"0","1386":"1","1387":"1","1388":"1","1389":"1","1390":"1","1391":"1","1393":"0","1397":"1","1399":"1","1400":"1","1401":"0","1402":"1","1406":"0","1407":"1","1408":"0","1409":"0","1410":"0","1414":"1","1412":"0"},
{"2404":"1","7849":"1","2455":"1","2456":"1","2459":"1","7979":"1","7982":"1","2461":"0","7824":"1","2414":"0","2471":"1","2472":"0","7862":"1","2423":"1","7863":"1","7995":"0","7996":"1","2425":"1","2478":"0","2427":"1","7930":"1","7952":"0","7870":"1","7843":"1"},
{"3245":"0","3210":"1","3212":"1","3213":"0","3214":"1","3251":"1","3215":"1","3254":"0","3217":"0","3222":"1","3223":"1","3267":"0","3225":"0","3270":"1","3273":"1","3275":"1","3276":"1"},
{"1077":"0","980":"0","1206":"0","1083":"1","1085":"1","983":"0","984":"0","1086":"1","988":"0","1090":"0","993":"1","1150":"0","994":"1","995":"1","1098":"1","1162":"1","1000":"1","1003":"1","1006":"1","1101":"1"},
{"3248":"1","3252":"1","3253":"1","3255":"1","3218":"1","3220":"1","3260":"1","3265":"1","3266":"1","3268":"1","3228":"0","3272":"1","3274":"0","3234":"1","3240":"0"},
{"5829":"1","5894":"1","5735":"1","5737":"1","5711":"1","5714":"1","5716":"1","5953":"0"},
{"251":"1","310":"1","228":"0","349":"1","281":"1","351":"1","235":"1","386":"1","238":"1","239":"0","332":"1","333":"0","273":"0"},
{"4235":"1","4300":"0","4208":"0","4112":"1","4238":"1","9400":"0","4182":"0","4184":"1","4310":"0","4241":"0","9411":"0","4249":"0","4222":"1","4322":"1","4223":"1","4284":"0","4286":"0","4326":"1","4290":"0","4203":"0"},
{"2813":"0","2815":"0","2844":"1","2847":"1","7875":"0","2902":"0","2903":"1","2824":"1","2889":"0","2852":"1","2837":"1","2939":"0","2854":"1","2857":"1","2913":"1","2862":"0","2865":"0","2869":"0","7836":"0","10438":"1","2871":"0","2898":"0","2919":"1"},
{"588":"1","610":"0","590":"1","591":"0","593":"0","595":"0","596":"1","597":"1","598":"1","599":"1","601":"1","602":"1","603":"0","604":"1","606":"1","607":"1","608":"1","609":"1"},
{"8710":"1","8649":"1","8805":"1","8807":"1","8722":"1","8783":"1","8727":"1","8657":"0","8705":"0","8817":"1","8734":"1","8821":"1","8822":"1","8670":"0","8824":"1"},
{"1513":"0","1547":"1","1691":"0","1518":"1","1694":"0","1697":"0","1571":"0","1494":"0","1527":"0","1589":"0","1498":"0","1595":"1","1709":"0","1559":"1","1710":"0","1503":"1","1630":"0","1562":"0","1713":"1","1565":"0","1567":"0"},
{"4431":"1","4460":"1","4463":"0","4438":"0","4446":"0","4472":"1","4475":"0","4452":"1","4476":"1","4482":"1"},
{"3016":"1","3071":"1","3076":"0","3079":"1","3027":"0","3082":"1","3111":"1","3114":"1","2952":"0"},
{"3894":"0","3895":"1","3976":"1","3926":"0","3930":"0","3954":"1","3982":"1","3903":"1","3973":"0","3961":"0","3988":"1","3989":"0","3918":"1","3971":"0"},
{"869":"1","9652":"1","873":"1","874":"1","891":"1","877":"1","841":"1","878":"1","896":"1","879":"1","880":"1","6897":"0","884":"0","885":"1","849":"1"},
{"1355":"1","1328":"0","1329":"0","1330":"0","1357":"1","1332":"1","1369":"1","1339":"0","1340":"1","1372":"1","1343":"0","1374":"1","1344":"0","1377":"0","1350":"1","1353":"1"},
{"9497":"0","9467":"1","9619":"0","9499":"1","9589":"0","9532":"1","9665":"0","9480":"1","9481":"1","9540":"1","9812":"0","9483":"1","9542":"0","9490":"1","9668":"1","9610":"1","9550":"0","9494":"1","9792":"1","9553":"1"},
{"448":"1","472":"1","473":"0","453":"0","455":"0","491":"0","477":"1","457":"1","459":"1","479":"1","461":"0","480":"0","481":"1","482":"1","486":"0","464":"1","488":"1","489":"0","466":"1"},
{"4587":"1","4529":"1","4533":"0","4534":"1","4560":"1","4561":"1","4754":"0","4725":"1","4562":"0","4662":"0","4757":"1","4731":"0","4667":"1","4668":"0","4762":"1","4672":"0","4550":"1","4680":"0","4581":"0","4681":"1","4584":"1","4714":"1"},
{"867":"1","868":"1","870":"1","871":"1","872":"1","875":"0","876":"1","881":"1","883":"0","887":"1","900":"0","886":"1"},
{"3097":"0","3074":"0","3075":"1","3102":"1","3103":"1","3025":"0","3048":"0","3081":"0","3108":"1","3116":"0","3061":"0","3036":"0"},
{"7333":"1","7298":"1","7237":"1","7302":"0","7150":"1","7337":"1","7152":"0","7214":"0","7348":"1","7160":"0","7329":"1","7163":"1","7197":"1","7168":"1","7325":"0"},
{"5560":"1","5594":"1","5564":"1","5596":"1","5571":"0","5606":"0","5576":"0","5577":"1","5581":"0","5582":"1","5583":"1","5584":"0","2147":"1","5588":"1","5589":"0","5617":"0","5618":"1"},
{"4716":"1","4749":"0","4781":"0","4752":"0","4753":"1","4725":"0","4758":"1","4787":"1","4788":"0","10478":"0","4735":"0","4737":"0","4674":"1","4794":"0","4740":"0","4710":"0","4769":"0","4747":"1"},
{"700":"1","701":"1","674":"0","702":"1","704":"1","705":"1","707":"1","709":"1","712":"1","682":"1","713":"1","714":"1","686":"1","715":"1","717":"0","719":"0","720":"0","721":"1","722":"1","723":"0"},
{"561":"0","532":"1","567":"0","544":"0","553":"0","579":"0","580":"0"},
{"396":"1","397":"1","398":"1","399":"1","401":"1","403":"0","406":"1","407":"1","408":"1","420":"0","409":"1","410":"0","411":"0","413":"1","414":"0","418":"0"},
{"979":"0","1234":"0","1238":"1","1210":"1","1211":"0","987":"1","1245":"1","1089":"1","990":"1","1059":"0","992":"0","1064":"1","1093":"1","1155":"0","1157":"0","1158":"0","997":"0","1159":"1","1163":"0","1007":"1","1008":"1"},
{"5822":"1","5647":"1","5648":"0","5893":"1","5677":"1","5651":"1","2410":"0","5628":"1","5629":"1","5708":"1","5631":"1","5935":"1","5658":"1","5634":"1","5723":"1","5666":"1","5918":"1","5639":"0","5725":"1","5644":"0","5643":"1"},
{"120":"1","195":"0","176":"1","177":"0","180":"0","200":"0","128":"0","201":"0","130":"0","132":"0","205":"0","134":"0","135":"1","185":"1","136":"1","187":"1","189":"0","141":"0","143":"1"},
{"4619":"1","4625":"1","4629":"1","4633":"1","4469":"1","4636":"0","4637":"1","4641":"1","4829":"1","4652":"1","4902":"0"},
{"4039":"0","4077":"1","4156":"1","4082":"1","4157":"0","4087":"1","4088":"0","4161":"0","4120":"1","4060":"1","4144":"0","4094":"1","4126":"1","4012":"1","4149":"1","4066":"0","4171":"1","4177":"1","4175":"0","4154":"0"},
{"2406":"0","2408":"1","2409":"0","7823":"1","2410":"1","2460":"0","2439":"0","2465":"1","2466":"1","2418":"0","7986":"0","2441":"1","7972":"0","7861":"1","2444":"0","7926":"1","7833":"1","7958":"0","2476":"1","2479":"1","2480":"1","7841":"1"},
{"3445":"1","3447":"1","3448":"1","3449":"1","3450":"1","3415":"1","3418":"1","3453":"0","3419":"1","3420":"0","3458":"1","3422":"1","3427":"1","3430":"1","3431":"1","3432":"0","3438":"0","3463":"0","3466":"0","5217":"1","3467":"1","3471":"1"},
{"1574":"0","1687":"0","1514":"0","1517":"1","1666":"0","1521":"1","1526":"0","1529":"1","1497":"1","1558":"0","1534":"0","1563":"1","1542":"1","1683":"0","1604":"0","1638":"0"},
{"2642":"0","2645":"0","7820":"1","2554":"1","2674":"0","2678":"1","2656":"1","2657":"0","2711":"1","2659":"0","2661":"0","2662":"0","2715":"1","7882":"1","7992":"1","2746":"0","2799":"1","7888":"1","7954":"1","2695":"0","7844":"1","2698":"1"},
{"5826":"0","5793":"0","5828":"0","5930":"0","5650":"0","5832":"0","5931":"0","5896":"0","5973":"0","5837":"0","5738":"0","5655":"0","5739":"0","5941":"0","5842":"0","5742":"1","5911":"0","5987":"0","5664":"0","5640":"0","5671":"1","5672":"1"},
{"145":"1","119":"1","146":"1","148":"1","151":"1","152":"0","153":"1","123":"0","154":"1","157":"1","158":"1","138":"0","139":"0","166":"1","140":"1","187":"0","188":"1","142":"0","168":"1","191":"0","169":"1","170":"1"},
{"4206":"0","4236":"1","4181":"1","4233":"0","4267":"0","4186":"0","4159":"0","4188":"1","4217":"1","4194":"1","4195":"1","4220":"1","4254":"0","4255":"0","4225":"1","4170":"0","4172":"0"},
{"7024":"1","7055":"0","7447":"0","7370":"0","7061":"0","7036":"0","7020":"0","7068":"1","7045":"1","7073":"1","7383":"1","7410":"1","7082":"0"},
{"613":"1","615":"1","616":"1","618":"0","619":"1","620":"0","621":"1","624":"1","625":"0","627":"1","629":"1","631":"0","633":"1","635":"0"},
{"8681":"1","8812":"1","8636":"1","8761":"1","8762":"1","8819":"1","8873":"1"},
{"4458":"1","4432":"1","4462":"1","4464":"0","4468":"0","4471":"1","4477":"1","4478":"1"},
{"57":"1","80":"0","40":"1","43":"1","45":"0","107":"0","48":"0","64":"1","91":"0","93":"0","94":"0","95":"0"},
{"2483":"1","2486":"1","2493":"0","2494":"0","2495":"1","7706":"0","7708":"0","2531":"0","2499":"1","7713":"0","2538":"1","2539":"0","2503":"0","2504":"1","2506":"0","2509":"1","2510":"1","2511":"1","2545":"1","2547":"0","2514":"1","2549":"0"},
{"3948":"0","3927":"1","3901":"0","3956":"1","3957":"1","3931":"0","3986":"0","3962":"0","3937":"0","3964":"1","3987":"1","3913":"1","3919":"1"},
{"1264":"1","1265":"1","1266":"0","1269":"1","1271":"1","1303":"0","1280":"1","1281":"1","1312":"0","1284":"1","1285":"1","1322":"1","1290":"1","1291":"0","1292":"1","1294":"1"},
{"5390":"1","2279":"0","5420":"1","2250":"1","2251":"1","5422":"1","2253":"0","2255":"0","2283":"0","2257":"0","2299":"0","2263":"0","2314":"1","2093":"0","2270":"0","2100":"1","2277":"0"},
{"736":"1","737":"0","740":"1"},
{"492":"0","518":"0","495":"0","496":"1","497":"1","498":"0","499":"0","484":"0","507":"0","510":"0"},
{"7672":"0","7647":"0","7648":"1","7623":"1","7676":"0","7677":"0","7625":"1","7656":"0","7629":"0","7681":"0","7682":"0","7683":"0","7636":"1","7662":"0","7689":"0","7641":"0","7642":"1","7692":"0","7693":"1","7668":"0","7698":"1"},
{"4527":"0","4507":"0","4509":"1","4540":"1","4592":"0","4563":"1","4593":"1","4487":"0","4571":"0","4736":"0","4573":"1","4601":"1","4546":"1","4499":"0","4549":"0","4575":"0","4579":"1","4582":"1","4556":"1"},
{"835":"1","851":"0","836":"1","853":"1","854":"0","856":"1","850":"1","857":"1","843":"1","844":"1","863":"0","848":"1","866":"0"},
{"7790":"1","7792":"0","7728":"1","7729":"1","7731":"0","7735":"0","7707":"1","7737":"0","7765":"1","7796":"1","7738":"0","7712":"1","7803":"0","7805":"0","7776":"0","7747":"0","7748":"0","7808":"1","7752":"1","7813":"1","7787":"1","7816":"0"},
{"7334":"1","7299":"0","7176":"0","7339":"1","7243":"0","7316":"1","7356":"1","7321":"1","7287":"1","7326":"1"},
{"2125":"1","2103":"1","2126":"0","2104":"1","2106":"1","2130":"1","2109":"1","2110":"0","2131":"1","2111":"1","2112":"1","2114":"1","2115":"0","2117":"1","2119":"0","2203":"0","2137":"0","2124":"1","2121":"1","2122":"0","2142":"1","2146":"0"},
{"8544":"0","8546":"1","8551":"1","8601":"0","8602":"0","8604":"0","8582":"0","8607":"0","8558":"0","8561":"1","8565":"0","8567":"0","8613":"0","8614":"1","8568":"0"},
{"4530":"1","4657":"1","4532":"1","4661":"1","4687":"0","4663":"0","4664":"1","4666":"0","4541":"1","4496":"0","4739":"0","4675":"0","4701":"0","4548":"1","4704":"0","4708":"0","4684":"0","4613":"1","4746":"0"},
{"728":"1","751":"1","732":"1","733":"0","735":"1","752":"1","738":"1","757":"1","758":"1","745":"0"},
{"907":"1","1202":"1","981":"1","947":"0","953":"0","1065":"0","955":"1","927":"0","956":"1","961":"1","963":"0","964":"1","968":"1","969":"1","970":"1","937":"1","973":"1","939":"0","974":"1","975":"1","976":"1","977":"1"},
{"421":"1","422":"1","423":"1","425":"0","426":"1","427":"0","428":"1","430":"1","431":"1","433":"1","437":"0","439":"0","440":"1","443":"1","445":"1"},
{"9825":"1","9860":"1","9832":"1","10090":"0","10279":"0","9835":"1","10185":"0","9836":"1","10309":"1","10311":"1","10025":"0","10197":"0","9848":"1"},
{"339":"0","370":"1","371":"1","375":"0","343":"0","7596":"1","7599":"0","376":"1","7601":"1","350":"0","380":"1","357":"1","382":"1","383":"1","384":"1","7582":"1","7608":"1","388":"0","390":"1","393":"1","7618":"1","395":"1"},
{"4620":"1","4622":"1","4623":"1","4624":"1","4632":"1","4638":"1","4639":"0","4640":"1","4643":"0","4645":"0","4650":"0","4651":"0","4653":"0"},
{"4019":"1","4021":"0","4022":"0","9207":"1","4049":"0","4000":"1","4138":"0","4024":"1","9239":"0","4139":"0","4053":"1","4001":"1","4141":"1","4007":"0","4097":"1","4064":"1"},
{"1416":"1","1453":"1","1454":"1","1413":"0","1422":"1","1423":"0","1425":"0","8779":"1","1428":"0","1432":"0","1434":"0","1435":"1","1436":"0","1472":"0","1438":"0","1440":"0","1481":"0"},
{"7960":"0","2550":"1","2551":"0","7940":"1","2581":"1","2613":"1","2552":"0","2585":"1","2556":"1","2620":"1","7853":"1","2566":"1","2628":"1","2568":"1","2634":"1","2600":"1","2574":"0","2605":"0","2636":"0","7951":"0","2576":"0","7953":"0"},
{"3351":"1","3354":"1","3386":"0","3355":"1","3387":"1","3358":"1","3359":"1","3389":"1","3363":"0","3391":"1","3367":"1","3369":"0","3398":"0","3399":"1","3372":"1","3374":"1","3403":"0"},
{"4617":"1"},
{"3350":"1","3383":"1","3384":"1","3356":"1","3357":"0","3360":"1","3361":"1","3390":"1","3362":"1","3363":"0","3364":"1","3396":"1","3370":"1","3371":"1","3401":"1","3402":"1"},
{"194":"1","173":"1","174":"0","175":"1","122":"0","181":"1","182":"0","161":"1","203":"1","184":"1","207":"1","208":"0","186":"0","209":"1","164":"1","216":"0","218":"0","192":"1","193":"1"},
{"7475":"1","7453":"1","7485":"1","7487":"1","7401":"0","7378":"1","7431":"0","7432":"1","7494":"1","7402":"1","7497":"0","7461":"0","7132":"0","7408":"0","7382":"1","7499":"1","7384":"1","7385":"1","7502":"1","7471":"0","7441":"1","7442":"0"},
{"792":"1","794":"1","795":"1","767":"0","771":"1","775":"0","780":"0","781":"1","782":"1","783":"0","807":"1","784":"0","789":"0","809":"1","790":"1"},
{"2405":"1","2453":"0","7937":"0","7977":"1","7822":"0","2435":"0","2411":"0","2412":"0","2413":"1","2415":"1","7967":"1","2416":"1","7858":"0","2420":"0","2421":"1","7990":"1","2422":"1","7909":"1","2424":"0","2445":"0","7839":"0","7840":"0"},
{"4298":"0","4301":"0","9462":"0","4212":"0","4308":"1","4309":"1","9443":"1","9446":"1","4121":"1","4256":"1","9451":"1","4278":"0","4320":"0","4283":"0","4285":"1","4201":"0","4287":"0","9454":"1","4258":"1","4292":"1","9426":"1","4331":"0","9461":"0"},
{"10020":"0","10021":"0","9916":"0","10024":"1"},
{"3189":"1","3126":"0","3148":"1","3170":"1","3149":"0","3128":"0","3167":"1","3153":"1","3132":"0","3200":"0","3175":"1","3159":"0","3138":"1","3162":"1","3181":"0","3182":"0","3183":"0","3206":"0","3143":"0","3207":"1","3144":"0","3188":"0"},
{"3145":"0","3146":"0","3168":"1","3150":"0","3171":"0","3172":"0","3129":"0","3152":"0","3823":"0","3195":"0","3134":"1","3199":"1","3155":"0","3135":"1","3202":"0","3177":"0","3159":"0","3180":"1","3142":"0","3208":"1"},
{"614":"1","589":"1","617":"1","592":"1","622":"1","623":"0","626":"1","637":"0","630":"0","605":"1","634":"1","636":"1"},
{"8618":"1","8680":"0","8648":"1","8651":"1","8624":"1","8625":"1","8653":"0","8631":"0","8869":"0","8656":"1","8633":"1","8634":"1","8665":"0","8637":"1","8639":"0","8669":"0","8875":"1","8643":"0","8741":"0"},
{"906":"1","908":"1","909":"1","911":"1","912":"1","944":"1","913":"1","1208":"0","915":"1","917":"1","949":"1","924":"1","926":"1","930":"1","931":"1","1004":"0","938":"0","941":"1","942":"1"},
{"38":"0","77":"0","78":"1","79":"1","99":"0","82":"1","60":"1","41":"1","76":"0","83":"0","85":"0","86":"0","47":"0","89":"0","51":"0","52":"0","70":"1","97":"0","92":"1","53":"0","56":"0","96":"0"},
{"2516":"1","2517":"0","2519":"1","2520":"0","2485":"0","2523":"0","2525":"1","2527":"1","2529":"1","2496":"1","2534":"0","2535":"1","2536":"1","2540":"1","2541":"1","2542":"0","2508":"0","2544":"1","7746":"1","7723":"1","7724":"0","2548":"1"},
{"3949":"0","3896":"1","3902":"0","3904":"0","3933":"1","3934":"0","3909":"0","3912":"0","3966":"0","3967":"0","3944":"0","3916":"1","3947":"1"},
{"1263":"1","1268":"1","1270":"0","1272":"0","1273":"1","1275":"1","1276":"0","8553":"1","1306":"0","1309":"1","1310":"1","1311":"1","1320":"0","1323":"1","1289":"0","1293":"1"},
{"447":"0","493":"0","450":"1","454":"0","456":"0","458":"1","460":"0","8608":"1","462":"1","485":"0","487":"0","467":"0"},
{"2220":"1","2305":"0","2224":"1","2227":"1","2228":"1","2230":"1","2231":"1","2262":"0","2312":"0","2313":"0","459":"0","2340":"1","2317":"1","2236":"1","2237":"0","2238":"0","2239":"1","2272":"0","2242":"1","2243":"1","2275":"1"},
{"9149":"1","9179":"0","9152":"0","9156":"0","9157":"0","9158":"0","9160":"0","9161":"0","9192":"0","9164":"0","9166":"0","9196":"0","9169":"0","9170":"0","9171":"0","9200":"0"},
{"2924":"0","2953":"0","2925":"0","2926":"0","2933":"0","2960":"0","2994":"0","2964":"0","2968":"0","2969":"1","2941":"0","2974":"0","2975":"0","3003":"0","2977":"1","2980":"0"},
{"7206":"0","7207":"0","7212":"1","7173":"1","7336":"0","7338":"0","7180":"0","7342":"1","7269":"0","7309":"0","7185":"1","7157":"1","7279":"1","7166":"1","7167":"0","7230":"1","7322":"1","7360":"1","7203":"1","7362":"1","7294":"1"},
{"538":"0","542":"1","545":"1","569":"0","574":"0","549":"1","555":"1"},
{"2218":"1","2080":"0","2081":"1","5444":"0","5365":"0","2085":"1","2086":"1","2087":"1","2089":"1","2168":"1","2095":"1","2096":"0","2201":"1","2098":"1","2180":"1","2145":"1"},
{"669":"1","670":"1","671":"1","673":"1","675":"1","676":"1","679":"1","681":"1","684":"1","685":"1","687":"1","691":"1","692":"1","696":"1","697":"1","698":"0","699":"1"},
{"4506":"0","4508":"1","4531":"0","4588":"0","4536":"1","4723":"0","4538":"0","4594":"1","4489":"0","4513":"1","4570":"1","4493":"1","4543":"0","4497":"0","4607":"1","4501":"1","4551":"0","4503":"1","4745":"1","4613":"0","4614":"0"},
{"4488":"0","4495":"0","4519":"0","4498":"0"},
{"642":"1","646":"1","648":"0","655":"0","657":"0","662":"1","665":"0","666":"1","667":"1"},
{"400":"0","402":"1","419":"0","404":"1","412":"1","415":"1"},
{"1141":"1","1174":"1","1177":"1","1144":"0","1178":"0","1207":"1","1145":"0","1186":"1","1147":"0","1188":"1","1189":"1","1191":"1","1192":"1","1193":"1","1194":"1","1160":"1","1124":"1","1196":"0","1102":"1","1258":"1","1198":"1","1199":"1"},
{"5270":"1","5271":"0","5294":"0","5825":"0","5767":"0","5297":"0","5298":"0","5280":"0","5299":"1","5300":"1","5777":"1","5284":"0","5748":"1","5784":"1","5691":"1","5668":"1","5305":"0","5306":"0","2120":"1","5310":"1","5311":"1","5312":"1"},
{"172":"1","149":"1","197":"1","124":"1","125":"0","179":"1","171":"0","198":"1","156":"1","127":"1","199":"0","159":"1","129":"1","131":"1","206":"1","163":"1","211":"0","165":"1","214":"0","215":"0","144":"1"},
{"2404":"1","2406":"0","7820":"1","7849":"1","2455":"0","2456":"1","2459":"0","7979":"0","7982":"0","2461":"0","7824":"1","2414":"1","2471":"1","2472":"0","7862":"1","2423":"1","7863":"1","7995":"0","7996":"0","2425":"1","2478":"0","2427":"1","7930":"1","7870":"1","7843":"1"},
{"1381":"1","1383":"0","1385":"0","1386":"1","1387":"1","1388":"1","1389":"0","1390":"0","1391":"1","1393":"1","1397":"0","1399":"1","1400":"1","1401":"1","1402":"1","1406":"0","1407":"0","1408":"0","1409":"0","1410":"0","1414":"1","1412":"1"},
{"4017":"1","4075":"1","4114":"0","3997":"1","3998":"1","4079":"1","4048":"1","3999":"0","4136":"0","4050":"1","4119":"1","4029":"1","4030":"1","4101":"1","4033":"1","4103":"1","4036":"1","4070":"0"},
{"4428":"0","4430":"0","4433":"1","4465":"1","4628":"1","4437":"1","4442":"1","4451":"1","4453":"0","4454":"0","4480":"1"},
{"1077":"0","980":"0","1206":"0","1083":"1","1085":"1","983":"1","984":"0","1086":"1","988":"0","1090":"1","993":"1","1150":"0","994":"1","995":"1","1098":"1","1162":"0","1000":"1","1003":"0","1006":"1","1101":"1"},
{"5829":"1","5894":"1","5735":"1","5737":"1","5711":"1","5714":"0","5716":"1","5953":"0"},
{"251":"1","310":"1","228":"0","349":"1","281":"1","351":"1","235":"1","386":"1","238":"1","239":"1","332":"1","333":"0","273":"1"},
{"339":"1","370":"0","371":"1","375":"1","343":"0","376":"1","350":"0","380":"0","357":"1","382":"0","383":"1","384":"1","388":"1","390":"1","393":"1","395":"0"},
{"2813":"0","2815":"0","2844":"1","2847":"1","7875":"0","2902":"1","2903":"1","2824":"1","2889":"0","2852":"1","2837":"1","2939":"1","2854":"1","2857":"1","2913":"1","2862":"0","2865":"1","2869":"1","7836":"1","10438":"0","2871":"0","2898":"0","2919":"0"},
{"4235":"1","4300":"0","4208":"1","4112":"0","4238":"0","9400":"0","4182":"0","4184":"1","4310":"0","4241":"0","9411":"0","4249":"0","4222":"0","4322":"0","4223":"0","4284":"0","4286":"0","4326":"1","4290":"0","4203":"0"},
{"588":"0","590":"1","591":"1","595":"1","596":"0","597":"1","598":"1","599":"1","601":"1","602":"1","603":"1","604":"0","606":"1","607":"1","608":"1","609":"1"},
{"8710":"1","8649":"1","8805":"1","8807":"1","8722":"1","8783":"1","8727":"1","8657":"0","8705":"0","8817":"1","8734":"1","8821":"1","8822":"0","8670":"0","8824":"1"},
{"3123":"0","3147":"0","3169":"0","3189":"1","3192":"0","3127":"1","3193":"0","3194":"0","3151":"0","3173":"1","3154":"1","3198":"0","3156":"0","3176":"0","3136":"0","3160":"0","3161":"0","3141":"0","3205":"0","3185":"1","3186":"1","3166":"0"},
{"4431":"0","4460":"1","4438":"0","4446":"0","4472":"0","4475":"1","4452":"1","4476":"0","4482":"1"},
{"1513":"0","1547":"1","1691":"0","1518":"1","1694":"0","1697":"0","1571":"0","1494":"0","1527":"1","1589":"0","1498":"0","1595":"0","1709":"0","1559":"1","1710":"0","1503":"1","1630":"1","1562":"0","1713":"0","1565":"0","1567":"0"},
{"3245":"0","3210":"1","3212":"0","3213":"1","3214":"1","3251":"0","3215":"1","3254":"0","3217":"1","3222":"1","3223":"1","3267":"0","3225":"0","3270":"0","3273":"0","3275":"1","3276":"1"},
{"3248":"0","3252":"1","3253":"1","3255":"1","3218":"1","3220":"0","3260":"1","3265":"1","3266":"1","3268":"1","3228":"0","3272":"1","3274":"0","3234":"0","3237":"1","3240":"0"},
{"3016":"0","3071":"1","3076":"0","3079":"1","3027":"0","3082":"0","3111":"1","3114":"1","2952":"1"},
{"3894":"0","3895":"0","3976":"1","3926":"0","3930":"0","3954":"1","3982":"1","3903":"1","3973":"0","3961":"0","3988":"1","3989":"0","3918":"1","3971":"0"},
{"1355":"1","1328":"0","1329":"0","1330":"0","1357":"1","1332":"1","1369":"1","1339":"0","1340":"1","1372":"1","1343":"1","1374":"1","1344":"0","1377":"1","1350":"1","1353":"0"},
{"869":"1","9652":"0","873":"1","874":"1","891":"1","877":"1","841":"1","878":"1","896":"1","879":"1","880":"1","6897":"0","884":"0","885":"1","849":"1"},
{"9497":"0","9467":"1","9619":"0","9499":"1","9589":"0","9532":"0","9665":"0","9480":"1","9481":"1","9540":"1","9812":"0","9483":"1","9542":"0","9490":"1","9668":"1","9610":"1","9550":"0","9494":"0","9792":"1","9553":"1"},
{"448":"0","472":"1","473":"0","453":"1","455":"0","491":"0","477":"0","457":"1","459":"1","479":"1","461":"0","480":"0","481":"1","482":"0","486":"1","464":"1","488":"1","489":"0","466":"1"},
{"4587":"1","4529":"1","4533":"1","4534":"1","4560":"1","4561":"1","4754":"1","4562":"0","4662":"0","4757":"1","4731":"0","4667":"0","4668":"1","4762":"1","4672":"0","4550":"0","4680":"0","4581":"0","4681":"0","4584":"1","4714":"1"},
{"3097":"0","3074":"0","3075":"0","3102":"1","3103":"1","3025":"0","3048":"0","3081":"0","3108":"1","3116":"1","3061":"0","3036":"0"},
{"10505":"0","7333":"0","7298":"0","7237":"1","7302":"0","7150":"0","7337":"1","7152":"0","7214":"0","7348":"1","7160":"1","7329":"1","7163":"1","7197":"1","7168":"1","7325":"0"},
{"867":"0","868":"1","870":"1","871":"1","872":"1","875":"0","876":"1","881":"1","883":"0","887":"1","900":"0","886":"1"},
{"5560":"1","5594":"1","5564":"1","5596":"0","5571":"0","5606":"0","5576":"0","5577":"1","5581":"0","5582":"1","5583":"0","5584":"1","2147":"0","5588":"1","5589":"0","5617":"0","5618":"1"},
{"4716":"1","4749":"1","4781":"0","4752":"0","4753":"1","4725":"1","4758":"1","4787":"1","4788":"0","10478":"0","4735":"0","4737":"0","4674":"1","4794":"1","4740":"0","4710":"0","4769":"1","4747":"1"},
{"561":"0","532":"1","567":"1","544":"0","553":"0","579":"0","580":"0"},
{"700":"1","701":"1","702":"1","704":"1","705":"1","707":"1","709":"0","712":"1","682":"1","713":"1","714":"0","686":"1","715":"0","719":"0","720":"0","721":"1","722":"1","723":"0"},
{"979":"1","1234":"0","1238":"0","1210":"1","1211":"1","987":"0","1245":"1","1089":"0","990":"1","1059":"0","992":"0","1064":"0","1093":"0","1155":"0","1157":"0","1158":"0","997":"0","1159":"1","1163":"0","1007":"1","1008":"1"},
{"396":"1","397":"1","398":"1","399":"1","401":"1","403":"0","406":"0","407":"1","408":"1","420":"0","409":"1","410":"1","411":"1","413":"1","414":"1","418":"0"},
{"5822":"0","5647":"0","5648":"0","5893":"1","5677":"1","5651":"1","2410":"0","5628":"1","5629":"0","5708":"1","5631":"0","5935":"1","5658":"1","5634":"1","5723":"1","5666":"1","5918":"1","5639":"1","5725":"0","5644":"0","5643":"1"},
{"120":"0","195":"0","176":"1","177":"0","180":"0","200":"0","128":"0","201":"0","130":"0","132":"0","205":"0","134":"0","135":"1","185":"0","136":"1","189":"0","141":"0","143":"1"},
{"2408":"0","2409":"0","7823":"1","2410":"1","2460":"0","2439":"0","2465":"1","2466":"1","2418":"0","7986":"1","2419":"0","2441":"0","7972":"0","7861":"1","2444":"0","7926":"1","7833":"1","7958":"1","2476":"1","2479":"1","2480":"1","7841":"1"},
{"4039":"0","4077":"1","4156":"1","4082":"1","4157":"1","4087":"1","4088":"0","4161":"1","4120":"1","4060":"0","4144":"0","4094":"1","4126":"0","4149":"1","4066":"0","4171":"1","4177":"1","4175":"0","4154":"1"},
{"3445":"0","3447":"0","3448":"0","3449":"0","3450":"0","3415":"1","3418":"1","3453":"0","3419":"0","3458":"1","3422":"1","3427":"1","3430":"0","3431":"1","3432":"0","3463":"0","3466":"0","5217":"0","3467":"0","3469":"1","3471":"0","3472":"0"},
{"1574":"0","1687":"0","1514":"0","1517":"1","1666":"0","1521":"0","1526":"0","1529":"0","1497":"1","1558":"0","1534":"0","1563":"0","1542":"0","1683":"0","1604":"0","1638":"0"},
{"145":"0","119":"1","146":"1","148":"1","151":"0","152":"0","153":"0","123":"0","154":"1","157":"1","158":"1","138":"1","139":"1","166":"1","140":"1","187":"1","188":"0","142":"0","168":"1","191":"0","169":"1","170":"0"},
{"5826":"0","5793":"0","5828":"0","5930":"0","5650":"1","5832":"0","5931":"0","5896":"0","5973":"0","5837":"0","5738":"1","5655":"0","5739":"0","5941":"0","5842":"0","5742":"1","5911":"0","5987":"0","5664":"1","5640":"0","5671":"1","5672":"1"},
{"2642":"0","2645":"0","2554":"1","2674":"0","2678":"1","2656":"1","2657":"0","2711":"1","2659":"0","2661":"0","2662":"0","2715":"1","7882":"1","7992":"1","2746":"0","2799":"0","7888":"1","7952":"1","7954":"0","2695":"0","7844":"1","2698":"1"},
{"7024":"1","7055":"0","7447":"0","7370":"1","7061":"0","7036":"0","7020":"1","7068":"1","7045":"1","7073":"0","7383":"1","7410":"1","7082":"0"},
{"4206":"1","4236":"1","4181":"1","4233":"0","4267":"0","4186":"0","4159":"1","4188":"0","4217":"1","4194":"1","4195":"1","4220":"0","4254":"0","4255":"0","4225":"0","4170":"0","4172":"1"},
{"613":"1","615":"1","616":"1","618":"1","619":"1","620":"0","621":"1","624":"1","627":"1","629":"1","633":"1","635":"1"},
{"4619":"1","4625":"0","4463":"1","4629":"1","4633":"1","4634":"1","4469":"1","4636":"0","4637":"1","4470":"1","4641":"1","4829":"1","4652":"1"},
{"4458":"1","4432":"1","4462":"1","4464":"0","4468":"0","4471":"1","4477":"1","4478":"0"},
{"8681":"1","8812":"0","8636":"1","8761":"0","8762":"1","8819":"1","8873":"0"},
{"57":"1","80":"0","40":"1","43":"1","45":"0","107":"0","48":"0","64":"1","91":"0","93":"0","94":"0","95":"0"},
{"3948":"0","3927":"0","3901":"0","3956":"1","3957":"1","3931":"0","3986":"1","3962":"1","3937":"0","3964":"1","3987":"1","3913":"0","3919":"1"},
{"2483":"0","2486":"0","2493":"0","2494":"0","2495":"1","7706":"0","7708":"0","2531":"1","2499":"1","7713":"0","2538":"1","2539":"0","2503":"0","2504":"0","2506":"0","2509":"0","2510":"1","2511":"1","2545":"1","2547":"0","2514":"1","2549":"0"},
{"1264":"1","1265":"1","1266":"0","1269":"1","1271":"1","1303":"0","1280":"1","1281":"1","1312":"0","1284":"0","1285":"1","1322":"1","1290":"1","1291":"1","1292":"1","1294":"1"},
{"5390":"0","2279":"0","5420":"0","2250":"0","2251":"0","5422":"1","2253":"0","2255":"0","2283":"1","2257":"0","2299":"0","2263":"0","2314":"0","2093":"1","2270":"0","2100":"0","2277":"0"},
{"736":"1","737":"1","740":"1"},
{"4527":"0","4507":"0","4509":"1","4540":"1","4592":"0","4563":"1","4593":"1","4487":"0","4571":"1","4736":"0","4573":"1","4601":"1","4546":"0","4499":"0","4549":"0","4575":"0","4579":"1","4582":"0","4556":"1"},
{"492":"0","518":"0","495":"0","496":"0","497":"0","498":"0","499":"0","484":"0","507":"0","510":"0"},
{"7672":"0","7647":"0","7648":"1","7623":"1","7676":"0","7677":"0","7625":"1","7656":"0","7629":"0","7681":"0","7682":"0","7683":"1","7636":"1","7662":"0","7689":"0","7641":"0","7642":"0","7692":"0","7693":"1","7668":"0","7698":"1"},
{"835":"1","851":"0","836":"1","853":"0","854":"0","856":"0","850":"1","857":"1","843":"1","844":"1","863":"1","848":"0","866":"0"},
{"7790":"1","7792":"1","7728":"1","7729":"0","7731":"0","7735":"0","7707":"1","7737":"0","7765":"1","7796":"1","7738":"1","7712":"1","7803":"1","7805":"0","7776":"0","7747":"1","7748":"0","7808":"1","7752":"0","7813":"1","7787":"0","7816":"0"},
{"7334":"1","7299":"0","7176":"0","7339":"1","7243":"1","7316":"1","7356":"0","7321":"0","7287":"1","7326":"1"},
{"2125":"1","2103":"1","2126":"0","2104":"1","2106":"1","2130":"0","2109":"1","2110":"0","2131":"1","2111":"1","2112":"0","2114":"0","2115":"0","2117":"1","2119":"1","2203":"0","2137":"1","2124":"0","2121":"1","2122":"1","2142":"0","2146":"0"},
{"8544":"0","8546":"0","8551":"1","8601":"0","8602":"0","8604":"0","8582":"0","8607":"1","8558":"1","8561":"1","8565":"0","8567":"0","8613":"1","8614":"0","8568":"1"},
{"4530":"1","4657":"1","4532":"0","4661":"1","4687":"0","4663":"1","4664":"0","4666":"0","4541":"1","4496":"0","4739":"0","4675":"1","4701":"0","4548":"1","4704":"0","4708":"0","4684":"0","4613":"1","4746":"0"},
{"728":"1","751":"0","732":"1","733":"0","735":"1","752":"0","738":"1","755":"0","757":"1","758":"0","745":"1"},
{"421":"1","422":"1","423":"1","425":"0","426":"1","427":"0","428":"0","430":"0","431":"1","433":"1","437":"1","439":"0","440":"1","443":"1","445":"0"},
{"907":"0","1202":"1","981":"0","947":"0","953":"1","1065":"0","955":"1","927":"0","956":"1","961":"1","963":"0","964":"1","968":"0","969":"1","970":"1","937":"1","973":"0","939":"0","974":"1","975":"0","976":"1","977":"1"},
{"9825":"1","9860":"1","9832":"1","10090":"0","10279":"1","9835":"1","10185":"0","9836":"1","10309":"1","10311":"1","10025":"0","10197":"0","9848":"1"},
{"4019":"1","4021":"0","4022":"0","9207":"0","4212":"1","4049":"1","4000":"1","4138":"0","4024":"1","9239":"0","4139":"0","4053":"1","4001":"0","4141":"1","4007":"0","4097":"1","4064":"1","4012":"1"},
{"7960":"0","2550":"1","2551":"0","7940":"0","2581":"1","2613":"1","2552":"0","2585":"1","2556":"0","2620":"1","7853":"1","2566":"1","2628":"1","2598":"1","2568":"0","2634":"1","2600":"1","2574":"0","2605":"0","2636":"0","7951":"0","2576":"0","7953":"0"},
{"4620":"0","4622":"0","4623":"1","4624":"1","4631":"1","4632":"1","4638":"1","4639":"0","4640":"1","4643":"1","4645":"1","4650":"1","4651":"0","4653":"1"},
{"1416":"1","1453":"0","1454":"1","1413":"0","1422":"1","1423":"0","1425":"0","8779":"1","1428":"0","1432":"0","1434":"0","1435":"0","1436":"0","1472":"0","1438":"0","1440":"0","1481":"0"},
{"3351":"1","3354":"1","3386":"0","3355":"1","3387":"1","3358":"1","3359":"1","3389":"1","3363":"1","3391":"1","3367":"1","3369":"0","3398":"0","3399":"0","3372":"0","3374":"1","3403":"0"},
{"906":"1","908":"1","909":"1","911":"1","912":"1","944":"0","913":"1","1208":"0","915":"1","917":"0","949":"1","924":"1","926":"1","930":"1","931":"1","1004":"0","938":"0","941":"1","942":"1"},
{"3350":"0","3383":"1","3384":"1","3356":"0","3357":"0","3360":"0","3361":"0","3390":"1","3362":"1","3363":"0","3364":"0","3396":"1","3370":"1","3371":"0","3401":"1","3402":"0"},
{"194":"0","173":"1","174":"1","175":"1","122":"0","181":"1","182":"1","161":"1","203":"1","184":"1","207":"1","208":"0","186":"0","209":"1","164":"1","216":"0","218":"0","192":"1","193":"0"},
{"10020":"0","10021":"0","9916":"0","10024":"1"},
{"2405":"1","2453":"0","7937":"0","7977":"0","7822":"0","2435":"0","2411":"1","2412":"0","2413":"1","2415":"1","7967":"0","2416":"1","7858":"0","2420":"1","2421":"1","7990":"0","2422":"1","7909":"0","2424":"1","2445":"0","7839":"0","7840":"0"},
{"4298":"1","4301":"0","9462":"0","4308":"1","4309":"1","9443":"1","9446":"1","4121":"1","4256":"1","9451":"1","4278":"0","4320":"0","4283":"1","4285":"1","4201":"0","4287":"0","9454":"1","4258":"0","4292":"1","9426":"1","4331":"0","9461":"0"},
{"792":"1","794":"0","795":"1","767":"0","771":"1","775":"0","780":"1","781":"1","782":"0","783":"1","807":"1","784":"0","789":"1","809":"1","790":"1"},
{"7475":"1","7453":"0","7485":"1","7487":"1","7401":"0","7378":"1","7431":"1","7432":"1","7494":"1","7402":"1","7497":"0","7461":"1","7132":"1","7408":"0","7382":"1","7499":"1","7384":"1","7385":"0","7502":"0","7471":"1","7441":"1","7442":"1"},
{"614":"1","589":"1","617":"1","592":"1","622":"1","623":"0","626":"1","637":"1","630":"0","605":"1","634":"1","636":"1"},
{"8618":"1","8680":"0","8648":"1","8651":"1","8624":"1","8625":"1","8653":"0","8631":"1","8869":"1","8656":"1","8633":"1","8634":"0","8665":"0","8637":"1","8639":"1","8669":"0","8875":"1","8643":"1","8741":"0"},
{"4617":"1"},
{"59":"0","42":"0","62":"0","44":"0","63":"1","66":"0","67":"1","50":"0","68":"0","69":"0","71":"1","72":"0","73":"1","74":"1","75":"1","55":"0"},
{"3893":"0","3922":"1","3897":"1","3953":"0","3977":"1","3899":"1","3978":"1","3906":"1","3963":"0","3911":"0","3943":"0","3974":"1","3994":"0"},
{"2376":"1","2353":"0","2355":"1","2379":"0","2358":"1","2360":"0","2383":"1","2363":"1","2385":"0","2391":"0","2393":"0","2372":"1","2394":"1","2373":"1","2374":"0","2396":"1","2401":"0","2402":"0"},
{"1331":"1","1358":"0","1333":"1","1362":"0","1364":"1","1335":"0","1337":"0","1338":"0","1341":"0","1346":"0","1347":"1","1376":"0","1348":"0","1349":"0","1379":"1","1380":"0"},
{"511":"0","512":"0","514":"0","515":"1","494":"0","516":"1","521":"1","498":"0","523":"0","500":"0","502":"0","505":"1","527":"0","506":"0","508":"1","529":"0","531":"0"},
{"2254":"1","5366":"0","494":"0","2331":"1","5371":"0","5397":"0","2335":"0","2336":"0","2337":"1","2264":"0","5377":"1","2289":"1","2339":"1","5409":"0","5410":"1","5412":"1","5413":"1","2240":"1","2241":"1","5415":"1","2274":"1","2346":"1"},
{"4485":"0","4528":"0","4683":"1","4564":"1","4597":"0","4511":"0","4601":"0","4520":"1","4521":"0","4522":"0","4606":"1","4610":"1","4525":"0","4526":"0","4557":"0"},
{"7755":"1","7791":"0","7758":"1","2926":"0","7793":"0","7759":"0","7762":"0","7763":"1","7767":"1","7769":"1","7741":"1","7770":"1","7771":"1","7773":"1","7774":"0","7778":"1","7779":"0","7780":"1","7782":"1","7784":"0","7785":"0","7786":"1","7814":"1","7815":"0"},
{"888":"0","905":"1","890":"1","892":"1","894":"1","897":"0","898":"1","899":"0","901":"0","902":"0","903":"1","904":"0"},
{"7145":"1","7210":"1","7181":"1","7182":"0","7343":"0","7183":"1","7184":"1","7186":"1","7158":"1","7349":"0","7159":"0","7313":"0","7357":"0","7229":"0","7252":"1","7169":"1","7324":"0","7327":"0"},
{"5442":"1","2149":"1","2127":"1","2153":"0","2156":"0","2157":"1","5600":"0","2165":"0","2132":"1","2166":"1","5605":"0","2134":"1","2173":"0","2174":"0","2179":"0","5614":"1","2144":"1","5460":"1","5619":"1"},
{"8594":"0","8571":"0","8548":"1","8600":"0","8579":"0","8603":"0","8609":"1","8610":"1","8615":"1","8616":"0"},
{"4690":"1","4500":"0","4504":"0"},
{"1011":"1","1012":"0","1047":"1","1017":"1","1018":"1","1024":"1","1027":"1","1029":"0","1067":"1","1030":"0","1070":"0","1033":"1","1034":"1","1035":"0","971":"0","1037":"1","1038":"0","1039":"0","1040":"1","1073":"1","1041":"0","1043":"0"},
{"446":"0","424":"1","425":"1","427":"0","429":"1","432":"1","434":"0","435":"0","436":"1","438":"1","441":"0","442":"0","444":"1"},
{"221":"1","222":"0","288":"1","226":"1","227":"1","229":"1","230":"0","232":"0","318":"1","319":"1","250":"0","234":"1","362":"1","326":"1","236":"0","237":"0","330":"0","304":"0","242":"0","243":"0","245":"1"},
{"2049":"1","2050":"1","2051":"0","2052":"0","2053":"1","2055":"1","2056":"0","2057":"1","2058":"0","2059":"0","2060":"0","2061":"1","2063":"1","2064":"1","2068":"1","2070":"0","2071":"1","2073":"1","2043":"0","2075":"1","2076":"1","2078":"0"},
{"2612":"0","7963":"1","2553":"1","2583":"0","2586":"0","2589":"1","2616":"0","2618":"1","2619":"0","2621":"0","2676":"0","2592":"1","2593":"0","2677":"1","2684":"1","2570":"1","7945":"1","2603":"0","2573":"0","2604":"1","2637":"0","7976":"1","2639":"1","2640":"1"},
{"4042":"0","4043":"0","4076":"1","4047":"0","4085":"1","4005":"0","4026":"0","4006":"1","4125":"1","4009":"0","4098":"0","4168":"0","9353":"0","4100":"0","4035":"0","4013":"1","4105":"0"},
{"3531":"0","3532":"1","3664":"0","3535":"1","3476":"0","3537":"0","3567":"0","3598":"1","3570":"0","3605":"0","3488":"0","3589":"1","3578":"0","3579":"1","3553":"0","3886":"0","3557":"0","3525":"0","3560":"0","3498":"0","3585":"0"},
{"4839":"1","4807":"1","4842":"1","4848":"1","4855":"1","4858":"1","4896":"0","4897":"0","4860":"1","4864":"1","4867":"1","4868":"1","4902":"0"},
{"1607":"0","1717":"0","1643":"0","1611":"0","1693":"0","1551":"0","1618":"1","1669":"0","1651":"0","1524":"0","1653":"0","1656":"0","1593":"1","1557":"1","1597":"1","1598":"1","1628":"0","1634":"0","1566":"0","1640":"0"},
{"764":"0","811":"0","766":"0","798":"0","818":"0","773":"1","774":"1","777":"1","779":"0","823":"0","805":"1","806":"1","787":"1","5213":"1"},
{"1108":"0","1014":"1","1232":"0","1203":"1","1051":"1","1235":"1","1237":"1","1019":"1","1113":"1","1212":"1","1243":"0","1244":"1","1214":"1","1022":"1","1023":"1","1219":"0","1065":"1","1221":"0","1071":"1","1254":"0","1036":"0","1226":"0"},
{"3565":"0","3568":"0","3510":"0","3639":"0","3589":"0","3577":"0","3550":"1","3578":"0","3579":"1","3553":"0","3798":"0","3557":"0","3560":"0","3585":"0","3586":"0"},
{"4263":"1","4302":"1","4237":"1","4304":"0","9404":"0","4311":"1","4216":"1","4275":"1","4251":"0","4316":"1","4145":"1","4165":"0","4167":"0","4279":"1","4282":"1","4200":"0","4202":"1","4259":"1"},
{"10266":"1","10083":"0","10086":"1","10091":"1","9864":"1","10094":"1","10026":"0","10103":"1"},
{"2843":"0","2878":"0","2880":"1","3016":"0","3045":"0","3073":"0","2926":"1","2901":"1","2823":"1","2850":"0","2827":"0","2892":"0","2830":"0","2962":"0","3052":"0","2840":"1","2856":"0","2944":"0","2866":"1","2867":"1","3065":"0","2873":"0"},
{"252":"0","224":"1","255":"0","256":"1","257":"0","258":"0","259":"1","260":"1","262":"0","263":"0","264":"0","266":"1","267":"0","270":"1","271":"0","272":"0","275":"1","276":"0","277":"0","278":"1","280":"0"},
{"4263":"1","4302":"1","4237":"1","4304":"0","9404":"0","4311":"1","4216":"0","4275":"1","4251":"0","4316":"1","4145":"1","4165":"0","4167":"0","4279":"0","4282":"1","4200":"0","4202":"0","4259":"1"},
{"10385":"1","10386":"0","10368":"0","10389":"1","10369":"1","10391":"0","10372":"1","10393":"0","10373":"1","10377":"0","10395":"0","10415":"1","10380":"1","10381":"1"},
{"8620":"0","8860":"1","8808":"0","8627":"1","8691":"0","8753":"0","8638":"1","8640":"0"},
{"98":"1","100":"1","101":"0","102":"1","104":"1","106":"1","109":"1","118":"0","110":"1","112":"1","114":"1","115":"1","116":"1"},
{"7700":"1","2351":"0","2352":"1","2354":"1","2378":"1","2356":"0","2380":"0","2357":"0","2382":"1","2364":"0","2403":"1","2365":"1","2366":"0","2387":"1","2388":"0","2389":"1","2392":"1","2398":"1","7807":"1","2400":"1","2401":"0"},
{"1327":"1","1354":"1","1356":"1","1360":"1","1334":"1","1363":"0","1302":"1","1277":"0","1365":"1","1278":"0","1336":"0","1370":"1","1373":"1","1287":"1","1375":"1","1378":"1"},
{"3892":"1","3950":"0","3952":"0","3924":"0","3898":"0","3900":"1","3955":"0","3932":"1","3985":"1","3907":"0","3960":"1","3910":"1","3938":"1","3965":"0","3915":"0","3917":"1","3992":"0"},
{"9583":"0","9763":"0","9501":"0","9690":"1","9655":"1","9656":"1","9590":"0","9772":"0","9774":"1","9775":"1","9636":"1","9637":"1","9489":"1","9604":"0","9669":"0","9544":"0","9782":"1","9784":"1","9785":"1","9758":"1","9789":"0","9824":"0"},
{"4537":"1","4542":"1"},
{"562":"0","535":"0","537":"0","563":"0","540":"0","566":"0","568":"0","548":"1","550":"1","552":"0","554":"0","584":"1","559":"1"},
{"7788":"1","7701":"0","7727":"1","7702":"1","7736":"1","7794":"1","7705":"0","7764":"0","7797":"0","7739":"1","7710":"1","7740":"1","7802":"0","7743":"0","7745":"1","7717":"0","7781":"1","7720":"0","7750":"1","7751":"1","7722":"0","7725":"1"},
{"834":"0","852":"1","838":"0","839":"0","855":"0","840":"1","858":"1","859":"1","860":"1","845":"1","846":"0","865":"1"},
{"6301":"0","6144":"0","6304":"0","6370":"0","6338":"0","6340":"0","6343":"0","6183":"1","6226":"0","6351":"0","6158":"0","6235":"0","6236":"0","6332":"0"},
{"7232":"1","7175":"0","7177":"1","7179":"1","7295":"1","7307":"0","7244":"0","7274":"1","7275":"0","7276":"1","7190":"1","7314":"1","7281":"0","7317":"1","7193":"0","7165":"1","7284":"1","7198":"1","7200":"0","7323":"1","7288":"1","7201":"1"},
{"8543":"0","8595":"0","8598":"0","8550":"0","8576":"1","8605":"0","8606":"1","8559":"1","8564":"0","8612":"1","8589":"1"},
{"2150":"1","2186":"1","5566":"1","2084":"1","2309":"0","2160":"1","2196":"0","2197":"1","2199":"0","2202":"0","2097":"0","2206":"1","2207":"0","2175":"1","2208":"1","2209":"1","2211":"1","2177":"1","2214":"1","5615":"1","2217":"1"},
{"4720":"1","4729":"1","4733":"0"},
{"638":"1","640":"1","10399":"1","643":"0","10419":"1","644":"0","5212":"1","647":"1","649":"1","650":"1","651":"1","652":"1","653":"1","10408":"1","10420":"1","10409":"1","10433":"1","658":"0","659":"1","10413":"1","663":"0","664":"1"},
{"1201":"1","1050":"1","1109":"1","1206":"0","1111":"1","1112":"0","1114":"1","1115":"1","1119":"1","1249":"0","1063":"1","1220":"0","1120":"1","1121":"0","1123":"0","1127":"1","1128":"1","1222":"1","1130":"1","1133":"0","1135":"1","1137":"1"},
{"8676":"1","8678":"1","8803":"0","8679":"1","8682":"1","8650":"1","8721":"0","8687":"1","8749":"1","8629":"0","8842":"0","8758":"1","8731":"0","8848":"1","8702":"0","8793":"0","8823":"0","8853":"1"},
{"283":"1","285":"0","311":"0","287":"1","314":"1","315":"0","293":"0","294":"1","296":"1","297":"1","298":"0","324":"1","299":"1","300":"1","325":"0","327":"0","302":"1","303":"0","333":"1","335":"1","305":"1","306":"0"},
{"3315":"0","3283":"1","3284":"1","3326":"0","3327":"1","3288":"1","3290":"0","3331":"0","3293":"1","3332":"1","3296":"1","3298":"1","3301":"0","3308":"1","3309":"0","3338":"0","3311":"0","3341":"0","3345":"0","3347":"1","3312":"1"},
{"4073":"1","4132":"0","4083":"0","4084":"1","4086":"0","4025":"0","6768":"0","4189":"0","4091":"0","4093":"0","4063":"1","4011":"1","4032":"1","4102":"1","4068":"1","4014":"1","4107":"0"},
{"2017":"1","2018":"1","2019":"1","2021":"0","2023":"1","2025":"1","2026":"1","2027":"1","2029":"1","2031":"1","2032":"1","2034":"1","2035":"1","2036":"1","2038":"0","2040":"1","2041":"1","2042":"1","2044":"1","2045":"1","2046":"0","2047":"1"},
{"2671":"0","2644":"0","2785":"1","2555":"1","2559":"1","2760":"0","7966":"0","2791":"0","2565":"0","2765":"1","2681":"0","2711":"0","2743":"1","7971":"0","2599":"0","2747":"0","2804":"1","2778":"0","2748":"0","7911":"0","2668":"0","2754":"1","2580":"0"},
{"1450":"1","1452":"0","1419":"1","1420":"0","1459":"0","1460":"0","1421":"0","1466":"1","1467":"0","1468":"1","1471":"1","1439":"0","1474":"1","1476":"1","1443":"0","1445":"1","1477":"1","1478":"1"},
{"3281":"1","3316":"0","3318":"1","3322":"0","3286":"1","3287":"0","3328":"1","3330":"0","3291":"0","3292":"1","3294":"1","3332":"0","3297":"0","3299":"0","3333":"1","3303":"1"},
{"172":"0","341":"0","342":"1","344":"1","347":"1","378":"0","379":"0","386":"0","387":"1","363":"1","364":"0","391":"0","367":"1"},
{"2784":"1","2703":"1","2731":"0","2762":"1","2792":"1","7826":"1","7898":"0","2710":"1","2742":"1","2796":"1","7828":"1","2745":"0","2721":"1","2771":"1","2666":"1","2690":"1","7835":"1","2779":"1","2577":"0","2751":"0","2780":"0","2753":"0"},
{"4078":"1","6813":"0","6789":"1","4116":"0","4183":"1","4240":"0","4052":"1","4243":"0","6765":"1","4160":"0","4247":"1","4271":"0","6819":"1","6797":"1","4092":"1","4274":"0","4056":"0","4059":"0","4318":"0","6801":"0","6842":"0","4224":"0"},
{"7051":"1","7053":"0","7029":"0","7366":"1","7478":"1","6999":"1","7060":"1","7118":"0","7373":"1","7064":"1","7094":"0","7069":"1","7411":"1","7386":"1","7388":"1"},
{"5142":"1","5171":"1","5151":"0","5115":"1","4955":"1","4961":"0","5183":"1","5192":"1","5194":"1"},
{"4939":"0","4981":"1","4984":"1","4942":"1","5014":"1","5016":"0","5080":"1","4960":"0","5156":"0","4962":"0","5003":"1","4969":"0","5033":"0","4971":"1","4972":"1","5069":"1"},
{"5142":"1","5171":"1","5151":"0","5115":"1","4955":"1","4961":"0","5183":"1","5192":"1","5194":"1"},
{"8620":"1","8860":"1","8808":"1","8627":"1","8691":"1","8753":"0","8638":"0","8640":"0"}]';
        
        $frenq = json_decode($frenq);
        $ch = [];
        foreach ($frenq as $v) {
            foreach ($v as $id_pessoa => $fp) {
                @$ch[$id_pessoa][$fp]++;
            }
        }

        return $ch;
    }
}
