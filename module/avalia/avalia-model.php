<?php

class avaliaModel extends MainModel {

    public $db;
    public $_turma;
    public $_disciplinas;
    public $_alunos;
    public $_fieldsDisc;
    public $_idPessoas;
    public $_pessoa;
    public $_notaFalta;
    public $_notaFaltaFinal;
    public $_aulasDadas;
    public $_id_turma;
    public $_apd = [];
    public $_cargaHoraria;
    public $_sitList;
    public $_supervisor;
    public $_resumo;

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
        if ($this->db->tokenCheck('ementaSet')) {
            $this->ementaSet();
        } elseif ($this->db->tokenCheck('peloConselhoSet')) {
            $this->peloConselhoSet();
        }
    }

    public function peloConselhoSet() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $id_turma = filter_input(INPUT_POST, 'id_turma');
        $ins['fk_id_sf'] = filter_input(INPUT_POST, 'fk_id_sf', FILTER_SANITIZE_NUMBER_INT);
        $ins['situacao_final'] = filter_input(INPUT_POST, 'situacao_final', FILTER_SANITIZE_NUMBER_INT);
        $ins['id_turma_aluno'] = filter_input(INPUT_POST, 'id_turma_aluno', FILTER_SANITIZE_NUMBER_INT);
        $superEmail = filter_input(INPUT_POST, 'superEmail');
        $superNome = filter_input(INPUT_POST, 'superNome');
        $superSexo = filter_input(INPUT_POST, 'superSexo');
        $textTop = filter_input(INPUT_POST, 'textTop');
        $texto = filter_input(INPUT_POST, 'texto');
        $this->db->ireplace('ge_turma_aluno', $ins, 1);
        $em['n_ementa'] = $texto;
        $em['fk_id_turma'] = $id_turma;
        $em['fk_id_pessoa'] = $id_pessoa;
        $this->db->insert('avalia_ementa', $em, 1);
        //$superEmail = 'ti.marcoteixeira@educbarueri.sp.gov.br';
        mailer::enviaEmailAvalia('Prezad' . toolErp::sexoArt($superSexo) . ' ' . $superNome, $superEmail, $textTop . $texto);
        toolErp::alertModal('<p>Concluído.</p><p>Uma cópia da ementa foi eviada para ' . toolErp::sexoArt($superSexo) . ' supervisor' . ($superSexo == 'F' ? 'a' : '') . ' ' . $superNome . ' </p>');
    }

    public function ementaSet() {
        //nota e falta nova
        $nf = @$_POST['nf'];
        //nota e falta anterior
        $na = @$_POST['na'];
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
        $superEmail = filter_input(INPUT_POST, 'superEmail');
        $superNome = filter_input(INPUT_POST, 'superNome');
        $superSexo = filter_input(INPUT_POST, 'superSexo');
        $textTop = filter_input(INPUT_POST, 'textTop');
        $texto = filter_input(INPUT_POST, 'texto');
        $arquivo = "hab.aval_mf_" . $id_curso . "_" . $id_pl;
        $em['dados_antigos'] = json_encode($na);
        $em['dados_novos'] = json_encode($nf);
        $em['n_ementa'] = $texto;
        $em['fk_id_turma'] = $id_turma;
        $em['fk_id_pessoa'] = $id_pessoa;
        $this->db->insert('avalia_ementa', $em, 1);

        foreach ($nf as $k => $v) {
            $set = null;
            foreach ($v as $col => $valor) {
                $set .= $col . " = '" . $valor . "', ";
            }
            $sql = " update $arquivo set "
                    . $set
                    . " fk_id_turma = $id_turma "
                    . " where fk_id_pessoa = $id_pessoa "
                    . " and atual_letiva = $k ";
            $query = pdoSis::getInstance()->query($sql);
        }
        ##################### escapar e-mail ##################################
        $superEmail = 'ti.marcoteixeira@educbarueri.sp.gov.br';
        mailer::enviaEmailAvalia('Prezad' . toolErp::sexoArt($superSexo) . ' ' . $superNome, $superEmail, $textTop . $texto);
        toolErp::alertModal('<p>Concluído.</p><p>Uma cópia da ementa foi eviada para ' . toolErp::sexoArt($superSexo) . ' supervisor' . ($superSexo == 'F' ? 'a' : '') . ' ' . $superNome . ' </p>');
    }

    public function plsValido() {
        $sql = "select id_pl, n_pl from ge_periodo_letivo where ano >= 2017 and at_pl in (1,0) order by ano desc, semestre asc";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return toolErp::idName($array);
    }

    public function turmas($id_inst, $id_pl) {
        $sql = " select t.id_turma, t.n_turma, t.fk_id_ciclo id_ciclo, c.atual_letiva, c.un_letiva, c.qt_letiva, t.fk_id_inst id_inst "
                . " from ge_turmas t "
                . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " and ci.id_ciclo in (1, 2, 3, 4, 5, 6, 7, 8, 9, 25, 26, 35, 36, 37, 27, 28, 29, 30, 34, 31) "
                . " and t.fk_id_inst = $id_inst "
                . " and t.fk_id_pl = $id_pl "
                . " join ge_cursos c on c.id_curso = ci.fk_id_curso "
                . " order by n_turma ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $turmas[$v['id_turma']] = $v;
        }
        if (!empty($turmas)) {
            return $turmas;
        }
    }

    public function turma() {
        $id_turma = $this->_id_turma;
        $fields = "t.id_turma, t.n_turma, t.periodo, t.fk_id_pl as id_pl, t.fk_id_inst id_inst, "
                . " ci.fk_id_curso as id_ciclo, ci.id_ciclo, ci.aulas, "
                . " c.id_curso, c.n_curso, c.un_letiva, c.qt_letiva, c.un_letiva, c.atual_letiva ";
        $sql = " select $fields from ge_turmas t "
                . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo and id_turma = $id_turma "
                . " join ge_cursos c on c.id_curso = ci.fk_id_curso ";
        $query = pdoSis::getInstance()->query($sql);
        $this->_turma = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function alunos() {
        $id_turma = $this->_id_turma;
        $fields = "ta.id_turma_aluno, p.id_pessoa, p.n_pessoa, p.sexo, p.ra, p.ra_uf, ta.chamada, ta.dt_matricula, ta.dt_matricula_fim, ta.dt_transferencia, "
                . " tas.id_tas, sf.id_sf, sf.n_sf, tas.n_tas ";
        $sql = " select $fields from ge_turma_aluno ta "
                . " join pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " join ge_turma_aluno_situacao tas on tas.id_tas = ta.fk_id_tas and ta.fk_id_turma = $id_turma "
                . " join ge_situacao_final sf on sf.id_sf = ta.fk_id_sf "
                . " order by ta.chamada";
        $query = pdoSis::getInstance()->query($sql);
        $this->_alunos = $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ataEdit($id_turma) {
        $this->sitList();
        $this->_id_turma = $id_turma;
        $this->turma();
        $this->alunos();
        if ($this->_alunos) {
            $this->cargaHoraria($this->_turma['id_ciclo'], $this->_turma['id_pl']);
            $this->_disciplinas = ng_main::disciplinas($id_turma);
            $this->_fieldsDisc = array_keys($this->_disciplinas);
            $this->_idPessoas = implode(', ', array_column($this->_alunos, 'id_pessoa'));
            $this->mediaFalta();
            $this->verificaMediaNaoExiste();
            $this->mediaFaltaFinal();
            $this->aulasDadas();
        }
    }

    public function aulasDadas() {
        $id_turma = $this->_id_turma;
        $sql = "SELECT * FROM hab.aval_aula_dadas WHERE fk_id_turma = $id_turma";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $v) {
                @$ad['totalDisc'][$v['fk_id_disc']] += $v['aula_dadas'];
                @$ad['porBim'][$v['fk_id_disc']][$v['atual_letiva']] = $v['aula_dadas'];
            }

            $this->_aulasDadas = $ad;
        }
    }

    public function faltasPorc($faltas, $id_disc, $bimeste = null) {
        if ($bimeste) {
            @$dadas = $this->_aulasDadas['porBim'][$id_disc][$bimeste];
        } elseif ($id_disc) {
            @$dadas = $this->_cargaHoraria;
        } else {
            @$dadas = $this->_aulasDadas['totalDisc'][$id_disc];
        }
        if ($dadas > 0) {
            return round(($faltas / $dadas) * 100);
        }
    }

    public function calculaMedia($alunos) {
        $idPessoas = $this->_idPessoas;
        $apds = $this->alunosApd($idPessoas);
        if (!$apds) {
            $apds = [];
        }
        $disc = $this->_fieldsDisc;
        $turma = $this->_turma;
        if (!is_array($alunos)) {
            return;
        }

        foreach ($alunos as $id_pessoa => $id_final) {
            unset($ignore);
            $bim = @$this->_notaFalta[$id_pessoa];
            if (!empty($bim)) {
                $ins['falta_nc'] = 0;
                foreach ($disc as $d) {
                    $ins['media_' . $d] = 0;
                    $ins['falta_' . $d] = 0;
                }
                foreach ($bim as $v) {
                    $ins['falta_nc'] += $v['falta_nc'];
                    foreach ($disc as $d) {
                        $n = str_replace(',', '.', $v['media_' . $d]);
                        if (is_numeric($n)) {
                            @$ins['media_' . $d] += $n;
                        } elseif ($n == 'i' || $n == 'I') {
                            @$ignore[$d]++;
                        }
                        @$ins['falta_' . $d] += $v['falta_' . $d];
                    }
                }

                foreach ($disc as $d) {
                    $ins['media_' . $d] = round((($ins['media_' . $d] / ($turma['qt_letiva'] - @$ignore[$d])) / 0.5), 0) * 0.5;
                }
                if ($id_final) {
                    $ins['id_final'] = $id_final;
                }
                $ins['fk_id_pl'] = $turma['id_pl'];
                $ins['fk_id_turma'] = $turma['id_turma'];
                $ins['fk_id_ciclo'] = $turma['id_ciclo'];
                $ins['fk_id_pessoa'] = $id_pessoa;
                $this->db->ireplace('hab`.`aval_final', $ins, 1);
            }
        }
    }

    public function verificaMediaNaoExiste() {
        $set = sql::get('ge_setup', '*', ['id_set' => 1], 'fetch');
        $turma = $this->_turma;
        $data = date("Y-m-d");
        $plsAt = ng_main::periodosAtivos();
        if (!in_array($turma['id_pl'], $plsAt) || ($data >= $set['dt_inicio_conselho'] && $data <= $set['dt_fim_conselho']) || $set['at_conselho'] == 1) {
            $freq = $this->sitId_pessoa()[0];
            if ($freq) {
                foreach ($freq as $v) {
                    $alunos[$v] = null;
                }
                $sql = "SELECT "
                        . " fk_id_pessoa, id_final FROM hab.aval_final "
                        . " WHERE fk_id_pessoa IN (" . implode(', ', $freq) . ") "
                        . " AND fk_id_turma = " . $turma['id_turma'];
                $query = pdoSis::getInstance()->query($sql);
                $array = $query->fetchAll(PDO::FETCH_ASSOC);
                if ($array) {
                    foreach ($array as $v) {
                        $alunos[$v['fk_id_pessoa']] = $v['id_final'];
                    }
                }
                $this->calculaMedia($alunos);
            }
        }
    }

    public function sitId_pessoa() {
        foreach ($this->_alunos as $v) {
            $sit[$v['id_tas']][$v['id_pessoa']] = $v['id_pessoa'];
        }

        return $sit;
    }

    public function mediaFaltaFinal() {
        $turma = $this->_turma;
        $fieldsDisc = $this->_fieldsDisc;
        $idPessoas = $this->_idPessoas;
        $apds = $this->alunosApd($idPessoas);
        if (!$apds) {
            $apds = [];
        }
        $sql = "SELECT "
                . " id_final, fk_id_pessoa, falta_nc, media_" . implode(', media_', $fieldsDisc) . ", falta_" . implode(', falta_', $fieldsDisc) . ", cons_" . implode(', cons_', $fieldsDisc) . " "
                . " FROM hab.aval_final "
                . " WHERE fk_id_pessoa IN ($idPessoas) "
                . " AND fk_id_turma = " . $turma['id_turma']
                . " ORDER BY fk_id_pessoa ASC";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $v) {
                if (in_array($v['fk_id_pessoa'], $apds)) {
                    foreach ($fieldsDisc as $d) {
                        $v['media_' . $d] = 'APD';
                    }
                }
                $res[$v['fk_id_pessoa']] = $v;
            }
            unset($array);
            $this->_notaFaltaFinal = $res;
        }
    }

    public function ataFinal($id_turma) {
        $this->_id_turma = $id_turma;
        $this->turma();
        $this->_disciplinas = ng_main::disciplinas($id_turma, 'ge_turmas.fk_id_grade, nucleo_comum, fk_id_adb, n_disc, sg_disc, aulas');
        $this->alunos();
        $this->_fieldsDisc = array_keys($this->_disciplinas);
        $this->_idPessoas = implode(', ', array_column($this->_alunos, 'id_pessoa'));
        $this->mediaFaltaFinal($id_turma);
        $this->mediaFaltaBoletim($id_turma);
    }

    public function boletimBim($id_turma, $bimestre) {
        $this->_id_turma = $id_turma;
        $this->turma();
        $this->_disciplinas = ng_main::disciplinas($id_turma);
        $this->alunos();
        $this->_fieldsDisc = array_keys($this->_disciplinas);
        $this->_idPessoas = implode(', ', array_column($this->_alunos, 'id_pessoa'));
        $this->avalMediaFalta($bimestre);
    }

    public function mediaFaltaAluno($id_pessoa, $id_turma) {
        $this->_id_turma = $id_turma;
        $this->turma();
        $turma = $this->_turma;
        $this->_disciplinas = ng_main::disciplinas($id_turma);
        $fieldsDisc = $this->_fieldsDisc = array_keys($this->_disciplinas);

        $fields = 'id_mf, falta_nc, fk_id_pessoa, atual_letiva, media_' . implode(', media_', $fieldsDisc) . ', falta_' . implode(', falta_', $fieldsDisc);
        $sql = " select $fields from hab.aval_mf_" . $turma['id_curso'] . "_" . $turma['id_pl']
                . " where excluido != 1 "
                . " and fk_id_pessoa = $id_pessoa "
                . " order by fk_id_pessoa, atual_letiva ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $aval[$v['atual_letiva']] = $v;
        }
        if (!empty($aval)) {
            return $aval;
        }
    }

    public function mediaFaltaBoletim($id_turma) {
        $this->_id_turma = $id_turma;
        $turma = $this->_turma;
        $idPessoas = $this->_idPessoas;
        $fieldsDisc = $this->_fieldsDisc = array_keys($this->_disciplinas);

        $fields = 'id_mf, falta_nc, fk_id_pessoa, atual_letiva, media_' . implode(', media_', $fieldsDisc) . ', falta_' . implode(', falta_', $fieldsDisc);
        $sql = " select $fields from hab.aval_mf_" . $turma['id_curso'] . "_" . $turma['id_pl']
                . " where excluido != 1 "
                . " and fk_id_pessoa in ($idPessoas) "
                . " order by fk_id_pessoa, atual_letiva ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $aval[$v['fk_id_pessoa']][$v['atual_letiva']] = $v;
        }
        if (!empty($aval)) {
            $this->_notaFalta = $aval;
        }
    }

    public function mediaFalta() {
        $turma = $this->_turma;
        $fieldsDisc = $this->_fieldsDisc;
        $idPessoas = $this->_idPessoas;
        $apds = $this->alunosApd($idPessoas);
        if (!$apds) {
            $apds = [];
        }
        $fields = 'id_mf, falta_nc, fk_id_pessoa, atual_letiva, media_' . implode(', media_', $fieldsDisc) . ', falta_' . implode(', falta_', $fieldsDisc);
        $sql = " select $fields from hab.aval_mf_" . $turma['id_curso'] . "_" . $turma['id_pl']
                . " where excluido != 1 "
                . " and fk_id_pessoa in ($idPessoas) "
                . " order by fk_id_pessoa, atual_letiva ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($array) {
            $PessoaOld['fk_id_pessoa'] = null;
            $PessoaOld['atual_letiva'] = null;
            foreach ($array as $k => $v) {
                //verifica se tem tupla duplicada e set uma como excluida e a outra receba os maiores valores
                if (($v['fk_id_pessoa'] == $PessoaOld['fk_id_pessoa']) && ($v['atual_letiva'] == $PessoaOld['atual_letiva'])) {
                    //  echo '<br />'.$v['fk_id_pessoa'].' == '.$PessoaOld['fk_id_pessoa'].' && '.$v['atual_letiva'].' == '.$PessoaOld['atual_letiva'];
                    unset($alterar);
                    if ($PessoaOld['falta_nc'] > $v['falta_nc']) {
                        $array[$k]['falta_nc'] = $PessoaOld['falta_nc'];
                        $alterar[] = "falta_nc = '" . $PessoaOld['falta_nc'] . "'";
                    }

                    foreach ($fieldsDisc as $d) {
                        if (str_replace(',', '.', $PessoaOld['media_' . $d]) > str_replace(',', '.', $v['media_' . $d])) {
                            $array[$k]['media_' . $d] = $PessoaOld['media_' . $d];
                            $alterar[] = 'media_' . $d . " = '" . $PessoaOld['media_' . $d] . "'";
                        }
                        if ($PessoaOld['falta_' . $d] > $v['falta_' . $d]) {
                            $array[$k]['falta_' . $d] = $PessoaOld['falta_' . $d];
                            $alterar[] = 'falta_' . $d . " = '" . $PessoaOld['falta_' . $d] . "'";
                        }
                    }
                    if (!empty($alterar)) {
                        $sql = "update hab.aval_mf_" . $turma['id_curso'] . "_" . $turma['id_pl']
                                . " set " . implode(', ', $alterar)
                                . " where id_mf = " . $v['id_mf'];
                    }
                    $query = pdoSis::getInstance()->query($sql);

                    $sql = "update hab.aval_mf_" . $turma['id_curso'] . "_" . $turma['id_pl']
                            . " set excluido = 1 "
                            . " where id_mf = " . $PessoaOld['id_mf'];
                    //$query = pdoSis::getInstance()->query($sql);
                    unset($array[$k - 1]);
                }
                $PessoaOld = $array[$k];
            }
            foreach ($array as $v) {
                if (in_array($v['fk_id_pessoa'], $apds)) {
                    foreach ($fieldsDisc as $d) {
                        $v['media_' . $d] = 'APD';
                    }
                }
                $aval[$v['fk_id_pessoa']][$v['atual_letiva']] = $v;
            }
        }
        $this->_notaFalta = @$aval;
    }

    public function avalMediaFalta($bimestre) {
        $alunosFrequente = $this->sitId_pessoa()[0];
        $turma = $this->_turma;
        $fieldsDisc = $this->_fieldsDisc;
        $idPessoas = $this->_idPessoas;
        $apds = $this->alunosApd($idPessoas);
        if (!$apds) {
            $apds = [];
        }
        $fields = 'id_mf, falta_nc, fk_id_pessoa, media_' . implode(', media_', $fieldsDisc) . ', falta_' . implode(', falta_', $fieldsDisc);
        $sql = " select $fields from hab.aval_mf_" . $turma['id_curso'] . "_" . $turma['id_pl']
                . " where atual_letiva = $bimestre "
                . " and excluido != 1 "
                . " and fk_id_pessoa in ($idPessoas) "
                . " order by fk_id_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($array) {
            $PessoaOld['fk_id_pessoa'] = null;
            foreach ($array as $k => $v) {
                $array[$k]['falta_total'] = 0;
                //verifica se tem tupla duplicada e set uma como excluida e a outra receba os maiores valores
                if ($v['fk_id_pessoa'] == $PessoaOld['fk_id_pessoa']) {
                    unset($alterar);
                    if ($PessoaOld['falta_nc'] > $v['falta_nc']) {
                        $array[$k]['falta_nc'] = $PessoaOld['falta_nc'];
                        $alterar[] = "falta_nc = '" . $PessoaOld['falta_nc'] . "'";
                    }

                    foreach ($fieldsDisc as $d) {
                        if (str_replace(',', '.', $PessoaOld['media_' . $d]) > str_replace(',', '.', $v['media_' . $d])) {
                            $array[$k]['media_' . $d] = $PessoaOld['media_' . $d];
                            $alterar[] = 'media_' . $d . " = '" . $PessoaOld['media_' . $d] . "'";
                        }
                        if ($PessoaOld['falta_' . $d] > $v['falta_' . $d]) {
                            $array[$k]['falta_' . $d] = $PessoaOld['falta_' . $d];
                            $alterar[] = 'falta_' . $d . " = '" . $PessoaOld['falta_' . $d] . "'";
                        }
                    }
                    if (!empty($alterar)) {
                        $sql = "update hab.aval_mf_" . $turma['id_curso'] . "_" . $turma['id_pl']
                                . " set " . implode(', ', $alterar)
                                . " where id_mf = " . $v['id_mf'];
                    }
                    $query = pdoSis::getInstance()->query($sql);

                    $sql = "update hab.aval_mf_" . $turma['id_curso'] . "_" . $turma['id_pl']
                            . " set excluido = 1 "
                            . " where id_mf = " . $PessoaOld['id_mf'];
                    $query = pdoSis::getInstance()->query($sql);
                    unset($array[$k - 1]);
                }
                $PessoaOld = $array[$k];
                if (!empty($array[$k]['falta_nc'])) {
                    $array[$k]['falta_total'] = $array[$k]['falta_nc'];
                } else {
                    foreach ($fieldsDisc as $d) {
                        $array[$k]['falta_total'] += ($array[$k]['falta_' . $d] / 5);
                    }
                    $array[$k]['falta_total'] = intval($array[$k]['falta_total']);
                }
                foreach ($fieldsDisc as $d) {
                    if (empty($array['resumo'][$d]['maiorIgual_5'])) {
                        $array['resumo'][$d]['maiorIgual_5'] = 0;
                    }
                    if (empty($array['resumo'][$d]['menor_5'])) {
                        $array['resumo'][$d]['menor_5'] = 0;
                    }
                    if (empty($array['resumo'][$d]['zero'])) {
                        $array['resumo'][$d]['zero'] = 0;
                    }
                    @$array['resumo']['faltaAula'] += $array[$k]['falta_' . $d];
                    if (in_array($v['fk_id_pessoa'], $apds)) {
                        $array[$k]['media_' . $d] = 'APD';
                    } else {
                        if (in_array($v['fk_id_pessoa'], $alunosFrequente)) {
                            if (str_replace(',', '.', $array[$k]['media_' . $d]) >= 5 || $array[$k]['media_' . $d] == 'APD') {
                                $array['resumo'][$d]['maiorIgual_5']++;
                            } else {
                                $array['resumo'][$d]['menor_5']++;
                            }
                            if (empty($array[$k]['media_' . $d])) {
                                $array['resumo'][$d]['zero']++;
                            }
                        }
                    }
                }
            }
            if (empty($array['resumo']['faltaAula'])) {
                $array['resumo']['faltaAula'] = 0;
            }
            foreach ($array as $k => $v) {
                $aval[!empty($v['fk_id_pessoa']) ? $v['fk_id_pessoa'] : 'resumo'] = $v;
            }
            unset($array);
            $this->_notaFalta = @$aval;
        }
    }

    public function resumo() {
        $array = $this->_notaFaltaFinal;
        $fieldsDisc = $this->_fieldsDisc;
        $idPessoas = $this->_idPessoas;
        $apds = $this->alunosApd($idPessoas);
        if (!$apds) {
            $apds = [];
        }
        if ($array) {
            foreach ($array as $k => $v) {
                $array[$k]['falta_total'] = 0;

                if (!empty($array[$k]['falta_nc'])) {
                    $array[$k]['falta_total'] = $array[$k]['falta_nc'];
                } else {
                    foreach ($fieldsDisc as $d) {
                        $array[$k]['falta_total'] += ($array[$k]['falta_' . $d] / 5);
                    }
                    $array[$k]['falta_total'] = intval($array[$k]['falta_total']);
                }
                foreach ($fieldsDisc as $d) {
                    if (empty($array['resumo'][$d]['maiorIgual_5'])) {
                        $array['resumo'][$d]['maiorIgual_5'] = 0;
                    }
                    if (empty($array['resumo'][$d]['menor_5'])) {
                        $array['resumo'][$d]['menor_5'] = 0;
                    }
                    if (empty($array['resumo'][$d]['zero'])) {
                        $array['resumo'][$d]['zero'] = 0;
                    }
                    @$array['resumo']['faltaAula'] += $array[$k]['falta_' . $d];
                    if (in_array($v['fk_id_pessoa'], $apds)) {
                        $array[$k]['media_' . $d] = 'APD';
                    }
                    if (str_replace(',', '.', $array[$k]['media_' . $d]) >= 5 || $array[$k]['media_' . $d] == 'APD') {
                        $array['resumo'][$d]['maiorIgual_5']++;
                    } else {
                        $array['resumo'][$d]['menor_5']++;
                    }
                    if (empty($array[$k]['media_' . $d])) {
                        $array['resumo'][$d]['zero']++;
                    }
                }
            }
            if (empty($array['resumo']['faltaAula'])) {
                $array['resumo']['faltaAula'] = 0;
            }
            foreach ($array as $k => $v) {
                $aval[!empty($v['fk_id_pessoa']) ? $v['fk_id_pessoa'] : 'resumo'] = $v;
            }
            if ($array['resumo']) {
                return $array['resumo'];
            }
        }
    }

    public function alunosApd($idPessoas = null) {
        if (!empty($this->_apd)) {
            return $this->_apd;
        } else {
            $sql = "SELECT fk_id_pessoa FROM ge_aluno_necessidades_especiais "
                    . " WHERE fk_id_pessoa in ($idPessoas) "
                    . " AND fk_id_porte = 3 ";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            $apd = array_column($array, 'fk_id_pessoa');
            if ($array) {
                $this->_apd = $apd;
                return $apd;
            }
        }
    }

    public function periodoDia($sigla) {
        $p = [
            'T' => 'Tarde',
            'M' => 'Manhã',
            'N' => 'Noite',
            'I' => 'Integral'
        ];
        if (!empty($p[$sigla])) {
            return $p[$sigla];
        }
    }

    public function notaCor($nota) {
        $nota = str_replace(',', '.', $nota);
        if (!is_numeric($nota)) {
            return 'black';
        } elseif ($nota < 5) {
            return 'red';
        } else {
            return 'black';
        }
    }

    public function faltaPorcFinal($id_pessoa) {
        $cargaHor = $this->_cargaHoraria;
        @$faltas = $this->_notaFaltaFinal[$id_pessoa];
        if (!empty($faltas['falta_nc'])) {
            $faltaTotal = $faltas['falta_nc'];
        } else {
            $faltaTotal = 0;
            if ($faltas) {
                foreach ($faltas as $k => $v) {
                    if (substr($k, 0, 6) == 'falta_') {
                        $faltaTotal += $v / $this->_turma['aulas'];
                    }
                }
            }
        }

        return round(($faltaTotal / $cargaHor) * 100);
    }

    public function cargaHoraria($id_ciclo, $id_pl) {
        $sql = "SELECT `total` FROM `sed_carga_horaria_pl` WHERE `fk_id_pl` = $id_pl and fk_id_ciclo = $id_ciclo ";
        $query = pdoSis::getInstance()->query($sql);
        @$ch = $query->fetch(PDO::FETCH_ASSOC)['total'];
        if (empty($ch)) {
            $ch = 1000;
        }
        $this->_cargaHoraria = $ch / $this->_turma['aulas'];
    }

    public function SitFinal($id_pessoa, $freqFinal = null) {
        if (!$freqFinal) {
            $freqFinal = $this->faltaPorcFinal($id_pessoa);
        }
        $sitIdTA = $this->id_sfId_turma_aluno($id_pessoa);
        $n_sf = '<span style="color: red">Indefinida</span>';
        if ($sitIdTA['id_sf'] == 5) {
            //Retido p/ Conselho
            $id_sf = 5;
            $this->limpaConselho($id_pessoa);
            $n_sf = '<span style="color: red">' . $this->_sitList[5] . '</span>';
        } elseif (in_array($id_pessoa, $this->_apd)) {
            $id_sf = 2;
            $n_sf = '<span style="color: blue">' . $this->_sitList[2] . '</span>';
            if ($sitIdTA['id_sf'] != $id_sf) {
                $this->mudaSit($sitIdTA['id_turma_aluno'], $id_sf);
            }
            $this->ConselhoSet($id_pessoa);
        } elseif ($sitIdTA['id_sf'] == 7) {
            //Retido p/ Conselho
            $id_sf = 7;
            $n_sf = '<span style="color: red">' . $this->_sitList[7] . '</span>';
            $this->ConselhoSet($id_pessoa);
        } elseif ($freqFinal > 25) {
            //Retido p/ Frequência
            $id_sf = 4;
            $n_sf = '<span style="color: red">' . $this->_sitList[4] . '</span>';
            if ($sitIdTA['id_sf'] != $id_sf) {
                $this->mudaSit($sitIdTA['id_turma_aluno'], $id_sf);
            }
            $this->limpaConselho($id_pessoa);
        } else {
            $qtVermelho = $this->quantVermelha($id_pessoa);
            if ($qtVermelho) {
                $qtVermelhoNum = count($qtVermelho);
                //Retido p/ Rendimento
                if ($qtVermelhoNum > 3) {
                    $id_sf = 3;
                    $n_sf = '<span style="color: red">' . $this->_sitList[3] . '</span>';
                    if ($sitIdTA['id_sf'] != $id_sf) {
                        $this->mudaSit($sitIdTA['id_turma_aluno'], $id_sf);
                    }
                    $this->limpaConselho($id_pessoa);
                    //Promovido p/ Conselho
                } elseif ($qtVermelhoNum <= 3) {
                    $id_sf = 2;
                    $n_sf = '<span style="color: blue">' . $this->_sitList[2] . '</span>';
                    if ($sitIdTA['id_sf'] != $id_sf) {
                        $this->mudaSit($sitIdTA['id_turma_aluno'], $id_sf);
                    }
                    $this->ConselhoSet($id_pessoa);
                }
            } else {
                $this->limpaConselho($id_pessoa);
                $id_sf = 1;
                if ($sitIdTA['id_sf'] != $id_sf) {
                    $this->mudaSit($sitIdTA['id_turma_aluno'], $id_sf);
                }
                $n_sf = '<span style="color: blue">' . $this->_sitList[1] . '</span>';
            }
        }

        return [$n_sf, $id_sf];
    }

    public function sitList() {
        $this->_sitList = sql::idNome('ge_situacao_final');
    }

    public function mudaSit($id_turma_aluno, $id_sf) {
        $ins['id_turma_aluno'] = $id_turma_aluno;
        $ins['fk_id_sf'] = $id_sf;
        $ins['situacao_final'] = $id_sf;
        $this->db->ireplace('ge_turma_aluno', $ins, 1);
    }

    public function ConselhoSet($id_pessoa) {
        if (!empty($this->_notaFaltaFinal[$id_pessoa])) {
            foreach ($this->_notaFaltaFinal[$id_pessoa] as $k => $v) {
                if ((substr($k, 0, 6) == 'media_') && (str_replace(',', '.', $v) < 5) && $this->_notaFaltaFinal[$id_pessoa]['cons_' . substr($k, 6)] != 5) {
                    $ins['cons_' . substr($k, 6)] = '5';
                    $this->_notaFaltaFinal[$id_pessoa]['cons_' . substr($k, 6)] = 5;
                } elseif ((substr($k, 0, 6) == 'media_') && (str_replace(',', '.', $v) >= 5) && !empty($this->_notaFaltaFinal[$id_pessoa]['cons_' . substr($k, 6)])) {
                    $ins['cons_' . substr($k, 6)] = '0';
                    $this->_notaFaltaFinal[$id_pessoa]['cons_' . substr($k, 6)] = 0;
                }
            }
        }

        $ins['id_final'] = $this->_notaFaltaFinal[$id_pessoa]['id_final'];
        $this->db->ireplace('hab`.`aval_final', $ins, 1);
    }

    public function limpaConselho($id_pessoa) {
        if (!empty($this->_notaFaltaFinal[$id_pessoa])) {
            foreach ($this->_notaFaltaFinal[$id_pessoa] as $k => $v) {
                if (substr($k, 0, 5) == 'cons_' && !empty($v)) {
                    $ins[$k] = '0';
                    $this->_notaFaltaFinal[$id_pessoa][$k] = 0;
                }
            }
        }

        @$ins['id_final'] = $this->_notaFaltaFinal[$id_pessoa]['id_final'];
        $this->db->ireplace('hab`.`aval_final', $ins, 1);
    }

    public function quantVermelha($id_pessoa) {
        if (!empty($this->_notaFaltaFinal[$id_pessoa])) {
            foreach ($this->_notaFaltaFinal[$id_pessoa] as $k => $v) {
                if (str_replace(',', '.', $v) < 5 && substr($k, 0, 6) == 'media_') {
                    $qt[$k] = $v;
                }
            }

            return @$qt;
        }
    }

    public function id_sfId_turma_aluno($id_pessoa) {
        foreach ($this->_alunos as $v) {
            if ($id_pessoa == $v['id_pessoa']) {
                return ['id_turma_aluno' => $v['id_turma_aluno'], 'id_sf' => $v['id_sf']];
            }
        }
    }

    public function supervisor($id_inst, $id_curso) {
        $sql = "SELECT p.id_pessoa, p.n_pessoa, p.sexo, p.emailgoogle FROM vis_setor_instancia i "
                . " JOIN vis_setor s on s.id_setor = i.fk_id_setor "
                . " JOIN pessoa p on p.id_pessoa = s.fk_id_pessoa AND i.fk_id_inst = $id_inst AND i.fk_id_curso = $id_curso "
                . " AND i.at_setor_instancia = 1 ";
        $query = pdoSis::getInstance()->query($sql);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function editNota($id_pessoa, $id_turma) {
        $this->_pessoa = sqlErp::get('pessoa', 'n_pessoa, sexo', ['id_pessoa' => $id_pessoa], 'fetch');
        $this->_notaFalta = $this->mediaFaltaAluno($id_pessoa, $id_turma);
        $this->_supervisor = $this->supervisor($this->_turma['id_inst'], $this->_turma['id_curso']);
        $this->_disciplinas['nc']['n_disc'] = 'Núcleo Comum';
    }

    public function pontVirgulaE($array_, $fixo = NULL) {

        if (!empty($array_)) {
            foreach ($array_ as $v) {
                if (!empty($v)) {
                    $array[] = $v;
                }
            }
            if (!empty($array)) {
                $num = count($array);
                if ($num == 1) {
                    return current($array) . $fixo;
                } elseif ($num == 2) {
                    return implode($fixo . ' e ', $array) . $fixo;
                } else {
                    for ($c = 0; $c < $num; $c++) {
                        if ($c < ($num - 2)) {
                            @$texto .= $array[$c] . $fixo . '; ';
                        } elseif ($c == ($num - 2)) {
                            @$texto .= $array[$c] . $fixo . '; e ';
                        } else {
                            @$texto .= $array[$c] . $fixo;
                        }
                    }
                    return $texto;
                }
            }
        }
    }

    public function aulasDadasNc() {
        $turma = $this->_turma;
        $sql = "SELECT SUM(dias) tdias FROM `sed_letiva_data` WHERE `fk_id_curso` = " . $turma['id_curso'] . " AND `fk_id_pl` = " . $turma['id_curso'];
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if ($array) {
            if ($array['tdias'] > 0) {
                return $array['tdias'];
            } else {
                return 200;
            }
        }
    }

}
