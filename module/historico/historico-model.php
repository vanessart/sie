<?php

class historicoModel extends MainModel {

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
        if ($this->db->tokenCheck('ciclosSet')) {
            $this->ciclosSet();
        } elseif ($this->db->tokenCheck('historico_notasSet')) {
            $this->historico_notasSet();
        } elseif ($this->db->tokenCheck('excluirDados')) {
            $this->excluirDados();
        } elseif ($this->db->tokenCheck('historico_cargaSet')) {
            $this->historico_cargaSet();
        } elseif ($this->db->tokenCheck('historico_anosSet')) {
            $this->historico_anosSet();
        } elseif ($this->db->tokenCheck('gradeSet')) {
            $this->gradeSet();
        } elseif ($this->db->tokenCheck('faltaNc')) {
            $this->faltaNc();
        } elseif ($this->db->tokenCheck('apagaCarga')) {
            $this->apagaCarga();
        }
    }

    public function apagaCarga() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $sql = "DELETE FROM historico_carga WHERE fk_id_pessoa = $id_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
    }

    public function faltaNc($id_ciclo = null, $id_pessoa = null, $faltas = null) {
        if ($id_ciclo && $id_pessoa && $faltas) {
            $ins['fk_id_ciclo'] = $id_ciclo;
            $ins['fk_id_pessoa'] = $id_pessoa;
            $alert = 1;
        } else {
            $faltas = @$_POST['f'];
            $ins['id_fn'] = filter_input(INPUT_POST, 'id_fn', FILTER_SANITIZE_NUMBER_INT);
            $ins['fk_id_ciclo'] = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
            $ins['fk_id_pessoa'] = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
            $alert = null;
        }
        foreach ($faltas as $k => $v) {
            $ins['b_' . $k] = $v;
        }
        $this->db->ireplace('historico_faltas_nc', $ins, $alert);
    }

    public function gradeSet() {
        $id_grade = filter_input(INPUT_POST, 'id_grade', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        if ($id_grade) {
            $sql = "SELECT "
                    . " d.id_disc, d.n_disc, ad.fk_id_adb, ad.ordem "
                    . " FROM ge_aloca_disc ad "
                    . " JOIN ge_disciplinas d on d.id_disc = ad.fk_id_disc AND ad.fk_id_grade = $id_grade";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            $ins['fk_id_pessoa'] = $id_pessoa;
            foreach ($array as $v) {
                $ins['fk_id_base'] = $v['fk_id_adb'];
                $ins['ativo'] = 1;
                $ins['ordem'] = $v['ordem'];
                $ins['fk_id_disc'] = $v['id_disc'];
                $ins['n_disc'] = $v['n_disc'];
                $this->db->ireplace('historico_notas', $ins, 1);
            }
            toolErp::alert("Concluído");
        } else {
            toolErp::alert("Selecione uma grade ");
        }
    }

    public function historico_anosSet() {
        $i = @$_POST['i'];
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        ksort($i);

        if ($i) {
            $ano = 0;
            foreach ($i as $k => $v) {
                if (!empty($v['ano']) && $v['ano'] < $ano) {
                    toolErp::alert("A sequência dos anos está errada. Não foi possível salvar.");
                    return;
                }
                $ano = $v['ano'];
            }
            foreach ($i as $k => $v) {
                $v['fk_id_ciclo'] = $k;
                $v['fk_id_pessoa'] = $id_pessoa;
                $v['regime'] = str_replace('X', 0, $v['regime']);
                $this->db->ireplace('historico_anos', $v, 1);
            }
        }
        toolErp::alert("Concluído");
    }

    public function historico_cargaSet() {
        $ins = $_POST[1];
        foreach ($ins as $k => $v) {
            $ar = explode('_', $k);
            if ($ar[0] == 'total') {
                if (!empty($v['bncc_' . $ar[1]]) || !empty($v['bd_' . $ar[1]])) {
                    $ins['total_' . $ar[0]] = $v['bncc_' . $ar[1]] + $v['bd_' . $ar[1]];
                }
            }
        }
        $this->db->ireplace('historico_carga', $ins);
    }

    public function excluirDados() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $sql = "DELETE FROM `historico_faltas_nc` WHERE `fk_id_pessoa` = $id_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $sql = "DELETE FROM `historico_notas_parciais` WHERE `fk_id_pessoa` = $id_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $sql = "DELETE FROM `historico_notas` WHERE `fk_id_pessoa` = $id_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
    }

    public function historico_notasSet() {
        $notas = $_POST[1];
        $notasParc = @$_POST[2];
        $this->db->ireplace('historico_notas', $notas);

        if (!empty($notasParc['fk_id_pessoa'])) {
            if (empty($notasParc['fk_id_ciclo'])) {
                $notasParc['fk_id_ciclo'] = '0';
            }if (empty($notasParc['id_np'])) {
                unset($notasParc['id_np']);
            }
            if (!empty($notasParc['fk_id_ciclo']) && !empty($notasParc['fk_id_disc'])) {
                $this->db->ireplace('historico_notas_parciais', $notasParc, 1);
            }
        }
    }

    public function ciclosSet() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $ci = @$_POST['ciclos'];
        $regime = @$_POST['regime'];

        if (!empty($regime)) {
            $regimeSet = json_encode($regime);
        } else {
            $regimeSet = null;
        }
        foreach ($ci as $k => $v) {
            if (!empty($v)) {
                $ciclos[$k] = $k;
            }
        }
        if (!empty($ciclos)) {
            $cicloSet = json_encode($ciclos);
        } else {
            $cicloSet = null;
        }
        if (!empty($cicloSet) || !empty($cicloSet)) {
            $sql = "UPDATE `historico_dados_gerais` SET "
                    . " `ciclos` = '$cicloSet', "
                    . " `regime` = '$regimeSet' "
                    . " WHERE fk_id_pessoa = $id_pessoa; ";
            pdoSis::getInstance()->query($sql);
        }
    }

    public function faltasNcCalc($id_pessoa, $id_ciclo = null) {
        if (empty($id_ciclo)) {
            $sql = " SELECT "
                    . " ci.fk_id_curso, t.fk_id_pl,ci.id_ciclo "
                    . " FROM ge_turma_aluno ta "
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma AND ta.fk_id_pessoa = $id_pessoa "
                    . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                    . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo ";
        } else {
            $sql = "SELECT "
                    . " ci.fk_id_curso, t.fk_id_pl "
                    . " FROM ge_turma_aluno ta "
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma AND ta.fk_id_pessoa = $id_pessoa "
                    . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo AND ci.id_ciclo = $id_ciclo "
                    . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl ";
        }
        $query = pdoSis::getInstance()->query($sql);
        $cursoPl = $query->fetch(PDO::FETCH_ASSOC);

        if (empty($id_ciclo) && !empty($cursoPl['id_ciclo'])) {
            $id_ciclo = $cursoPl['id_ciclo'];
        }
        if ($cursoPl) {
            $sql = "SELECT * FROM hab.aval_mf_" . $cursoPl['fk_id_curso'] . "_" . $cursoPl['fk_id_pl'] . " WHERE `fk_id_pessoa` = $id_pessoa ";
            try {
                $query = pdoSis::getInstance()->query($sql);
                $parc = $query->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $exc) {
                //echo $exc->getTraceAsString();
            }

            if (!empty($parc)) {
                foreach ($parc as $y) {
                    @$faltasNc[$y['atual_letiva']] = $y['falta_nc'];
                }
            }
        }
        if (!empty($faltasNc)) {
            return $faltasNc;
        }
    }

    public function notasParciais($id_pessoa, $id_ciclo = null) {
        if (empty($id_ciclo)) {
            $sql = " SELECT "
                    . " ci.fk_id_curso, t.fk_id_pl,ci.id_ciclo "
                    . " FROM ge_turma_aluno ta "
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma AND ta.fk_id_pessoa = $id_pessoa "
                    . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                    . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo ";
        } else {
            $sql = "SELECT "
                    . " ci.fk_id_curso, t.fk_id_pl "
                    . " FROM ge_turma_aluno ta "
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma AND ta.fk_id_pessoa = $id_pessoa "
                    . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo AND ci.id_ciclo = $id_ciclo "
                    . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl ";
        }
        $query = pdoSis::getInstance()->query($sql);
        $cursoPl = $query->fetch(PDO::FETCH_ASSOC);

        if (empty($id_ciclo) && !empty($cursoPl['id_ciclo'])) {
            $id_ciclo = $cursoPl['id_ciclo'];
        }
        if ($cursoPl) {
            $sql = "SELECT * FROM hab.aval_mf_" . $cursoPl['fk_id_curso'] . "_" . $cursoPl['fk_id_pl'] . " WHERE `fk_id_pessoa` = $id_pessoa ";
            try {
                $query = pdoSis::getInstance()->query($sql);
                $parc = $query->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $exc) {
                //echo $exc->getTraceAsString();
            }

            if (!empty($parc)) {
                foreach ($parc as $y) {
                    foreach ($y as $k => $v) {
                        if (substr($k, 0, 6) == 'media_' && !empty($v)) {
                            $parciais[substr($k, 6)][$y['atual_letiva']] = $v;
                        } elseif (substr($k, 0, 6) == 'falta_' && !empty($v)) {
                            @$parciais[substr($k, 6)]['faltas'] += $v;
                        }
                    }
                }
            }
        }
        if (!empty($parciais)) {
            $ins['fk_id_pessoa'] = $id_pessoa;
            $ins['fk_id_ciclo'] = $id_ciclo;
            $ins['fk_id_disc'] = null;
            unset($ins['faltas']);
            unset($ins['b_1']);
            unset($ins['b_2']);
            unset($ins['b_3']);
            unset($ins['b_4']);

            foreach ($parciais as $k => $v) {
                $ins['fk_id_disc'] = $k;
                $test = sql::get('historico_notas_parciais', 'id_np', ['fk_id_pessoa' => $id_pessoa, 'fk_id_ciclo' => $id_ciclo, 'fk_id_disc' => $k], 'fetch');
                if (!empty($test['id_np'])) {
                    $ins['id_np'] = $test['id_np'];
                }
                foreach ($v as $ky => $y) {
                    if (is_numeric($ky) && !empty($y)) {
                        $ins['b_' . $ky] = $y;
                    } elseif (!empty($y)) {
                        $ins['faltas'] = $y;
                    }
                }
                $this->db->ireplace('historico_notas_parciais', $ins, 1);
            }
        }
        $teste = sql::get('historico_notas_parciais', '*', ['fk_id_pessoa' => $id_pessoa, 'fk_id_ciclo' => intval($id_ciclo)]);
        if ($teste) {
            foreach ($teste as $v) {

                foreach ($v as $ky => $y) {
                    if (substr($ky, 0, 2) == 'b_' && !empty($y)) {
                        $parciaisDb[$v['fk_id_disc']][substr($ky, 2)] = $y;
                    } elseif ($ky == 'faltas' && !empty($y)) {
                        $parciaisDb[$v['fk_id_disc']]['faltas'] = $y;
                    }
                }
            }
        }
        if (!empty($parciaisDb)) {
            return $parciaisDb;
        }
    }

    public function frequente($id_pessoa, $id_ciclo = null) {
        if ($id_ciclo) {
            $sql = "SELECT "
                    . " t.n_turma, t.fk_id_ciclo, pl.ano "
                    . " FROM ge_turma_aluno ta "
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma and fk_id_ciclo = $id_ciclo and ta.fk_id_tas = 0 "
                    . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                    . " WHERE ta.fk_id_pessoa = $id_pessoa ";
        } else {
            $sql = "SELECT "
                    . " t.n_turma, t.fk_id_ciclo, pl.ano "
                    . " FROM ge_turma_aluno ta "
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma and fk_id_ciclo != 32 "
                    . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1 "
                    . " WHERE ta.fk_id_pessoa = $id_pessoa ";
        }
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if ($array) {
            return $array;
        }
    }

    public function frequenteTexto($id_pessoa, $id_ciclo = null) {
        if ($id_ciclo) {
            $sql = "SELECT "
                    . " t.n_turma, t.fk_id_ciclo, ci.n_ciclo, c.n_curso, id_final, pl.ano, pl.n_pl "
                    . " FROM ge_turma_aluno ta "
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma and fk_id_ciclo = $id_ciclo and ta.fk_id_tas = 0 "
                    . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                    . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                    . " join ge_cursos c on c.id_curso = ci.fk_id_curso "
                    . " left join hab.aval_final af on af.fk_id_ciclo = t.fk_id_ciclo and af.fk_id_pessoa = $id_pessoa "
                    . " WHERE ta.fk_id_pessoa = $id_pessoa ";
        } else {
            $sql = "SELECT "
                    . " t.n_turma, t.fk_id_ciclo, ci.n_ciclo, c.n_curso, id_final, pl.ano, pl.n_pl "
                    . " FROM ge_turma_aluno ta "
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma and fk_id_ciclo != 32 "
                    . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                    . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo and ci.id_ciclo in (1, 2, 3, 4, 5, 6, 7, 8, 9, 25, 26, 27, 28, 29, 30)"
                    . " join ge_cursos c on c.id_curso = ci.fk_id_curso "
                    . " left join hab.aval_final af on af.fk_id_ciclo = t.fk_id_ciclo and af.fk_id_pessoa = $id_pessoa "
                    . " WHERE ta.fk_id_pessoa = $id_pessoa "
                    . " order by t.fk_id_ciclo desc, pl.ano desc ";
        }
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if ($array) {
            return $array;
        }
    }

    public function notasSoParciais($id_pessoa) {
        $sql = "SELECT "
                . " ad.fk_id_disc id_disc, ad.fk_id_adb n_adb "
                . " FROM ge_turma_aluno ta "
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma and fk_id_ciclo != 32 "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1 "
                . " JOIN ge_aloca_disc ad on ad.fk_id_grade = t.fk_id_grade "
                . " WHERE ta.fk_id_pessoa = $id_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $idBase = toolErp::idName($array);
        if (!empty($this->notasParciais($id_pessoa))) {
            $nt = array_keys($this->notasParciais($id_pessoa));
            $disc = sql::idNome('ge_disciplinas', 'where id_disc in (\'' . (implode("','", $nt)) . '\')');
            $ordemDisc = $this->ordemDisc();
            $ordem = count($ordemDisc) + 1;
            foreach ($disc as $k => $v) {
                $ins = [];
                $sql = "SELECT * FROM historico_notas WHERE fk_id_pessoa = $id_pessoa and fk_id_disc = '" . $k . "' ";
                $query = pdoSis::getInstance()->query($sql);
                $test = $query->fetch(PDO::FETCH_ASSOC);
                if (empty($test['id_nota'])) {
                    $ins['fk_id_base'] = $idBase[$k]; //resolver
                    $ins['ativo'] = 1;
                    if (array_key_exists($k, $ordemDisc)) {
                        $ins['ordem'] = $ordemDisc[$k];
                    } else {
                        $ins['ordem'] = $ordem++;
                    }
                    $ins['fk_id_disc'] = $k;
                    $ins['n_disc'] = $v;
                    $ins['fk_id_pessoa'] = $id_pessoa;
                    $this->db->ireplace('historico_notas', $ins, 1);
                }
            }
        }
        $dados = sql::get(['historico_notas', 'historico_base'], '*', ['fk_id_pessoa' => $id_pessoa, '>' => 'ordem, id_base']);

        return $dados;
    }

    public function notas($id_pessoa) {
        $hist = new ng_hist($id_pessoa);
        $notas = $hist->notas();
        if ($notas) {
            $ordemDisc = $this->ordemDisc();
            $ordem = count($ordemDisc) + 1;
            foreach ($notas as $v) {
                if ($v['id_disc'] != 29) {
                    $ins = [];
                    if ($v['id_disc']) {
                        $sql = "SELECT * FROM historico_notas WHERE fk_id_pessoa = $id_pessoa and fk_id_disc = '" . $v['id_disc'] . "' ";
                    } else {
                        $sql = "SELECT * FROM historico_notas WHERE fk_id_pessoa = $id_pessoa and n_disc = '" . $v['n_disc'] . "' ";
                    }
                    $query = pdoSis::getInstance()->query($sql);
                    $test = $query->fetch(PDO::FETCH_ASSOC);
                    if (!empty($test['id_nota'])) {
                        $ins['id_nota'] = $test['id_nota'];
                    } else {

                        $ins['fk_id_base'] = !empty($v['id_base']) ? $v['id_base'] : 2;
                        $ins['ativo'] = 1;
                        if (array_key_exists($v['id_disc'], $ordemDisc)) {
                            $ins['ordem'] = $ordemDisc[$v['id_disc']];
                        } else {
                            $ins['ordem'] = $ordem++;
                        }
                        $ins['fk_id_disc'] = $v['id_disc'];
                        $ins['n_disc'] = $v['n_disc'];
                        $ins['fk_id_pessoa'] = $id_pessoa;
                    }
                    if (!empty($v['notas'])) {
                        foreach ($v['notas'] as $id_ciclo => $n) {
                            $id_ciclo = $this->cicloSet($id_ciclo);
                            if (!empty($n['nota']) && empty($test['n_' . $id_ciclo])) {
                                $ins['n_' . $id_ciclo] = $n['nota'];
                            }
                        }
                    }

                    $this->db->ireplace('historico_notas', $ins, 1);
                }
            }
        }
        $dados = sql::get(['historico_notas', 'historico_base'], '*', ['fk_id_pessoa' => $id_pessoa, '>' => 'ordem, id_base']);

        return $dados;
    }

    public function notaDel($id_pessoa) {
        $sql = "delete from historico_notas WHERE fk_id_pessoa = $id_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
    }

    public function cicloSet($id_ciclo) {
        if (in_array($id_ciclo, [31, 34, 35, 36, 37])) {
            // configurar as multiseriadas
        }

        return $id_ciclo;
    }

    public function ensinoCiclos($id_hte, $inicialFinal = null) {
        if ($inicialFinal) {
            $set[1]['Anos Iniciais'] = [1 => '1º Ano', 2 => '2º Ano', 3 => '3º Ano', 4 => '4º Ano', 5 => '5º Ano'];
            $set[1]['Anos Finais'] = [6 => '6º Ano', 7 => '7º Ano', 8 => '8º Ano', 9 => '9º Ano'];
            $set[2]['Anos Iniciais'] = [25 => 'Termo 1', 26 => 'Termo 2'];
            $set[2]['Anos Finais'] = [27 => 'Termo 1', 28 => 'Termo 2', 29 => 'Termo 3', 30 => 'Termo 4'];
            $set[3]['Anos Iniciais'] = [1 => '1º Ano', 2 => '2º Ano', 3 => '3º Ano', 4 => '4º Ano', 5 => '5º Ano', 25 => 'Termo 1', 26 => 'Termo 2'];
            $set[3]['Anos Finais'] = [6 => '6º Ano', 7 => '7º Ano', 8 => '8º Ano', 9 => '9º Ano', 27 => 'Termo 1', 28 => 'Termo 2', 29 => 'Termo 3', 30 => 'Termo 4'];
        } else {
            $set[1] = [1 => '1º Ano', 2 => '2º Ano', 3 => '3º Ano', 4 => '4º Ano', 5 => '5º Ano', 6 => '6º Ano', 7 => '7º Ano', 8 => '8º Ano', 9 => '9º Ano'];
            $set[2] = [25 => 'Termo 1', 26 => 'Termo 2', 27 => 'Termo 1', 28 => 'Termo 2', 29 => 'Termo 3', 30 => 'Termo 4'];
            $set[3] = [1 => '1º Ano', 2 => '2º Ano', 3 => '3º Ano', 4 => '4º Ano', 5 => '5º Ano', 6 => '6º Ano', 7 => '7º Ano', 8 => '8º Ano', 9 => '9º Ano', 25 => 'Termo 1', 26 => 'Termo 2', 27 => 'Termo 1', 28 => 'Termo 2', 29 => 'Termo 3', 30 => 'Termo 4'];
        }

        if (!empty($set[$id_hte])) {
            return $set[$id_hte];
        }
    }

    public function ordemDisc() {
        return [9 => 1, 10 => 2, 11 => 3, 30 => 4, 6 => 5, 12 => 6, 13 => 7, 14 => 8];
    }

    public function cargaHora($id_pessoa, $id_ciclo = null) {
        $sql = "SELECT * FROM `sed_carga_horaria_pl` WHERE `fk_id_pl` = 0 ";
        $query = pdoSis::getInstance()->query($sql);
        $plP = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($plP as $v) {
            $carga[$v['fk_id_ciclo']] = $v;
        }
        $sql = " SELECT "
                . " t.fk_id_ciclo, t.fk_id_pl, pl.ano, ch.bncc, ch.bd, ch.total "
                . " FROM ge_turma_aluno ta "
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma and ta.fk_id_pessoa = $id_pessoa AND (ta.fk_id_tas = 0 or ta.fk_id_tas is null) "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " left join sed_carga_horaria_pl ch on ch.fk_id_pl = t.fk_id_pl and t.fk_id_ciclo = ch.fk_id_ciclo ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $v) {
                if (!empty($v['bncc']) || !empty($v['bd']) || !empty($v['total'])) {
                    $carga[$v['fk_id_ciclo']] = $v;
                } elseif (!empty($carga[$v['fk_id_ciclo']])) {
                    $carga[$v['fk_id_ciclo']]['ano'] = $v['ano'];
                }
            }
            if ($id_ciclo) {
                foreach ($carga as $k => $v) {
                    if ($v['fk_id_ciclo'] > $id_ciclo) {
                        unset($carga[$k]);
                    }
                }
            }
        }

        return $carga;
    }

    public function anosAnteriores($dados, $regime = null, $restaura = null) {
        $ca = sqlErp::get('sed.ciclo_ano');
        foreach ($ca as $v) {
            $anoCiclo[$v['TipoEnsino']][$v['SerieAno']] = $v['id_ciclo'];
        }
        if (empty($restaura)) {
            $anos = sql::get('historico_anos', '*', ['fk_id_pessoa' => $dados['id_pessoa'], '>' => 'ano']);
            foreach ($anos as $v) {
                $idHab[$v['fk_id_ciclo']] = $v['id_ha'];
            }
        } else {
            $sql = "DELETE FROM `historico_anos` WHERE `fk_id_pessoa` = " . $dados['id_pessoa'];
            $query = pdoSis::getInstance()->query($sql);
        }
        if (empty($anos) && !empty($dados['ra'])) {
            $ant = rest::listarMatriculasRA($dados['ra'], $dados['ra_uf'], $dados['ra_dig']);
            if ($ant) {
                $antEsc = array_reverse($ant['outListaMatriculas']);
                foreach ($antEsc as $v) {
                    @$antEsc_[$anoCiclo[$v['outCodTipoEnsino']][$v['outCodSerieAno']]] = $v;
                }
                foreach ($antEsc_ as $v) {
                    if ($v['outCodSitMatricula'] == 0 && in_array($v['outCodTipoEnsino'], [1, 14, 3, 4])) {
                        $ins['fk_id_ciclo'] = $anoCiclo[$v['outCodTipoEnsino']][$v['outCodSerieAno']];
                        $ins['fk_id_pessoa'] = $dados['id_pessoa'];
                        $ins['ano'] = $v['outAnoLetivo'];
                        $ins['cidade'] = $v['outMunicipio'];
                        $ins['escola'] = $v['outDescNomeAbrevEscola'];
                        $ins['cie'] = $v['outCodEscola'];
                        $ins['uf'] = 'SP';
                        $ins['ativo'] = 1;
                        if (!empty($idHab[$ins['fk_id_ciclo']])) {
                            $ins['id_ha'] = $idHab[$ins['fk_id_ciclo']];
                        }
                        if (@$regime[$ins['fk_id_ciclo']] == 1) {
                            $ins['regime'] = 1;
                        } else {
                            unset($ins['regime']);
                        }
                        $this->db->ireplace('historico_anos', $ins, 1);
                        $anos = sql::get('historico_anos', '*', ['fk_id_pessoa' => $dados['id_pessoa'], '>' => 'ano']);
                    }
                }
            }
        }
        if (!empty($anos)) {
            foreach ($anos as $v) {
                $anosEsc[$v['fk_id_ciclo']] = $v;
            }

            return $anosEsc;
        }
    }

    public function cicloCursando($notas) {
        if ($notas) {
            foreach ($notas as $v) {
                foreach ($v as $k => $y) {
                    if (substr($k, 0, 2) == 'n_' && is_numeric(substr($k, 2)) && $y) {
                        $cur[substr($k, 2)] = substr($k, 2);
                    }
                }
            }
        }

        if (!empty($cur)) {
            ksort($cur);
            return end($cur);
        }
    }

    public function proxCiclo($id_ciclo, $prox = 1) {
        $set = [1 => '1º Ano do Ensino Fundamental', 2 => '2º Ano do Ensino Fundamental', 3 => '3º Ano do Ensino Fundamental', 4 => '4º Ano do Ensino Fundamental', 5 => '5º Ano do Ensino Fundamental', 6 => '6º Ano do Ensino Fundamental', 7 => '7º Ano do Ensino Fundamental', 8 => '8º Ano do Ensino Fundamental', 9 => '9º Ano do Ensino Fundamental', 10 => '1ª série do Ensino Médio', 25 => 'Termo 1 do EJA 1º Segmento', 26 => 'Termo 2 do EJA 1º Segmento', 27 => 'Termo 1 do EJA 2º Segmento', 28 => 'Termo 2 do EJA 2º Segmento', 29 => 'Termo 3 do EJA 2º Segmento', 30 => 'Termo 4 do EJA 2º Segmento', 31 => '1ª série do Ensino Médio'];
        if (!empty($set[$id_ciclo + $prox])) {
            return $set[$id_ciclo + $prox];
        } else {
            return '________________________';
        }
    }

####################Início Mario ###########################

    Public function definevariavel($idpessoa) {
        $sql = "SELECT hn.fk_id_base, COUNT(hn.fk_id_base) AS Qtde FROM historico_notas hn"
                . " GROUP BY hn.fk_id_base, hn.ativo, hn.fk_id_pessoa"
                . " HAVING hn.fk_id_pessoa = " . $idpessoa . " AND hn.ativo = 1";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $l[99] = 4;
        if ($array) {
            foreach ($array as $v) {
                $l[$v['fk_id_base']] = $v['Qtde'];
                $l[99] = $l[99] + $l[$v['fk_id_base']];
            }
            return $l;
        }
    }

    public function pegadadosescola($id_inst = null) {
        if (empty($id_inst)) {
            $id_inst = toolErp::id_inst();
        }
        $sql = "SELECT p.n_predio, p.logradouro, p.num, p.bairro, p.cidade,"
                . " p.tel1, p.tel2, p.tel3, es.ato_cria, es.ato_municipa, i.email FROM instancia i"
                . " JOIN instancia_predio ip ON ip.fk_id_inst = i.id_inst"
                . " JOIN ge_escolas es ON es.fk_id_inst = i.id_inst"
                . " JOIN predio p ON p.id_predio = ip.fk_id_predio"
                . " WHERE i.id_inst = " . $id_inst;
        try {
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $exc) {
            
        }
        if ($array) {
            foreach ($array as $key => $v) {
                $end[$key] = $v;
            }
        }
        $end[1] = $end[0]['logradouro'] . ', ' . $end[0]['num'] . ' - ' . $end[0]['bairro'] . ' - ' . $end[0]['cidade'] . ' - SP';
        if ($end[0]['tel1']) {
            $end[2] = $end[0]['tel1'];
            if ($end[0]['tel2']) {
                $end[2] = $end[0]['tel1'] . '-' . $end[0]['tel2'];
                if ($end[0]['tel3']) {
                    $end[2] = $end[0]['tel1'] . '-' . $end[0]['tel2'] . '-' . $end[0]['tel3'];
                }
            } else {
                if ($end[0]['tel3']) {
                    $end[2] = $end[0]['tel1'] . '-' . $end[0]['tel3'];
                }
            }
        } else {
            if ($end[0]['tel2']) {
                $end[2] = $end[2]['tel1'];
                if ($end[0]['tel3']) {
                    $end[2] = $end[0]['tel2'] . '-' . $end[0]['tel3'];
                } else {
                    if ($end[0]['tel3']) {
                        $end[2] = $end[0]['tel2'] . '-' . $end[0]['tel3'];
                    } else {
                        $end[2] = '-';
                    }
                }
            }
        }

        if ($end[0]['tel3']) {
            $tel3 = 1;
        }
        return $end;
    }

    public function fotoEnd($id_pessoa) {
        if (file_exists(ABSPATH . "/pub/fotos/" . $id_pessoa . ".jpg")) {
            return HOME_URI . '/pub/fotos/' . $id_pessoa . '.jpg?' . uniqid();
        } elseif (file_exists(ABSPATH . "/pub/fotos/" . $id_pessoa . ".png")) {
            return HOME_URI . '/pub/fotos/' . $id_pessoa . '.png?' . uniqid();
        } else {
            return HOME_URI . '/includes/images/anonimo.jpg';
        }
    }

    public function observacaopandemia($ano20, $ano21, $sexo) {
        if ($ano20 == 1) {
            if ($ano21 == 1) {
                // 2020 e 2021
                $obs[0] = ($sexo == 'F' ? "A aluna " : "O aluno ") . "participou da integralização da carga horária
                                mínima do ano letivo afetado pela pandemia da Covid – 19, por
                                meio de reprogramação curricular (2020 – 2021), conforme o
                                disposto na LDB, Art. 23, a BNCC, a Lei no 14.040, de
                                18/08/2020, o Parecer CNE/CP nº 15/2020, de 06/10/2020, 
                                Deliberação CME nº 05/2020 e Deliberação CME nº 04/2021.";
                $obs[3] = "Ano 2020 e 2021:";
                $obs[4] = '';
            } else {
                // 2020
                $obs[0] = ($sexo == 'F' ? "A aluna " : "O aluno ") . "participou da integralização da carga horária
                mínima do ano letivo afetado pela pandemia da Covid – 19, por
                meio de reprogramação curricular (2020 – 2021), conforme o
                disposto na LDB, Art. 23, a BNCC, a Lei nº 14.040, de
                18/08/2020, o Parecer CNE/CP nº 15/2020, de 06/10/2020 e
                a Deliberação CME nº 05/2020.";
                $obs[3] = "Ano 2020:";
                $obs[4] = '';
            }
        } else {
            // 2021
            if ($ano21 == 1) {
                $obs[0] = ($sexo == 'F' ? "A aluna " : "O aluno ") . "participou da integralização da carga horária
                                mínima do ano letivo afetado pela pandemia da Covid – 19, por
                                meio de reprogramação curricular (2020 – 2021), conforme o
                                disposto na LDB, Art. 23, a BNCC, a Lei no 14.040, de
                                18/08/2020, o Parecer CNE/CP nº 15/2020, de 06/10/2020, 
                                Deliberação CME nº 05/2020 e Deliberação CME nº 04/2021.";
                $obs[3] = "Ano 2021:";
                $obs[4] = '';
            } else {
                $obs[4] = 'none';
                $obs[3] = "-";
            }
        }

        $obs[1] = "BNCC - Base Nacional Comum Curricular - BD - Base Diversificada";
        $obs[2] = "Ensino Religioso e Orientação de Estudos (Deliberação CME 05/2001) - 1º Ano - Lei 11274/2006";

        return $obs;
    }

####################Fim Mario ###########################
}
