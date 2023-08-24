<?php

class sedModel extends MainModel {

    public $db;
    public $return = [];

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

        //seta o select dinamico
        if ($opt = formErp::jqOption()) {
            $metodo = $opt[0];
            if (in_array($metodo, get_class_methods($this))) {
                $this->$metodo($opt[1]);
            }
        }
        if ($this->db->tokenCheck('salvaQuadro')) {
            $this->salvaQuadro();
        } elseif ($this->db->tokenCheck('sincronizarTurmas')) {
            $this->sincronizarTurmas();
        } elseif ($this->db->tokenCheck('baixarTurmaAluno')) {
            $this->baixarTurmaAluno();
        } elseif ($this->db->tokenCheck('fotoUp')) {
            $this->fotoUp();
        } elseif ($inf = $this->db->tokenCheck('cadastroResponsavelSet')) {
            $this->cadastroResponsavelSet($inf);
        } elseif ($inf = $this->db->tokenCheck('importarAluno')) {
            $this->importarAluno($inf);
        } elseif ($this->db->tokenCheck('incluiGA')) {
            $this->incluiGA();
        } elseif ($this->db->tokenCheck('excluiGA')) {
            $this->excluiGA();
        } elseif ($this->db->tokenCheck('upDoc')) {
            $this->upDoc();
        } elseif ($this->db->tokenCheck('uploadImgCad')) {
            $this->uploadImgCad();
        } elseif ($this->db->tokenCheck('spe')) {
            $this->spe();
        } elseif (!empty($_POST['upFoto'])) {
            $this->upload();
        } elseif ($this->db->tokenCheck('salvaEscInst')) {
            $this->salvaEscInst();
        } elseif ($this->db->tokenCheck('salvaInstCurso')) {
            $this->salvaInstCurso();
        } elseif (!empty($_POST['cadprof'])) {
            $this->cadprof();
        } elseif ($this->db->tokenCheck('alocaProf')) {
            $this->alocaProf();
        } elseif ($this->db->tokenCheck('alocahorario')) {
            $this->alocahorario();
        } elseif ($this->db->tokenCheck('sed_muralSalva')) {
            $this->sed_muralSalva();
        } elseif ($this->db->tokenCheck('mudaAUL')) {
            $this->mudaAUL();
        } elseif ($this->db->tokenCheck('dataLetiva')) {
            $this->dataLetiva();
        } elseif ($this->db->tokenCheck('ge_ciclosSlava')) {
            $this->ge_ciclosSlava();
        } elseif ($this->db->tokenCheck('cicloGradeSalva')) {
            $this->cicloGradeSalva();
        } elseif (!empty($_REQUEST['rotateImg'])) {
            $this->rotateImg();
        } elseif (!empty($_POST['cropImg'])) {
            $this->cropImg();
        } elseif ($this->db->tokenCheck('contatoSet')) {
            $this->contatoSet();
        } elseif ($this->db->tokenCheck('novaPessoa')) {
            $this->novaPessoa();
        } elseif (!empty($_POST['excluirFunc'])) {
            $this->excluirFunc();
        } elseif (!empty($_POST['escluirUser'])) {
            $this->escluirUser();
        } elseif (!empty($_POST['grupoInsert'])) {
            $this->grupoInsert();
        } elseif ($this->db->tokenCheck('excluirResp')) {
            $this->excluirResp();
        } elseif ($this->db->tokenCheck('sed_carga_horaria_plSet')) {
            $this->sed_carga_horaria_plSet();
        } elseif ($this->db->tokenCheck('nono')) {
            $this->nono();
        } elseif ($this->db->tokenCheck('atualizarPat')) {
            $this->atualizarPat();
        }
    }

    public function nono() {
        $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $tp_ensino = @$_POST['tp_ensino'];
        $espanhol = @$_POST['espanhol'];
        $ins['fk_id_turma'] = $id_turma;
        $ins['fk_id_inst'] = $id_inst;
        if (!empty($tp_ensino)) {
            foreach ($tp_ensino as $k => $v) {
                $ins['tp_ensino'] = $v;
                $ins['espanhol'] = @$espanhol[$k];
                $ins['id_pessoa_opt'] = $k;
                $this->db->ireplace('aa_nono_opt', $ins, 1);
            }
        }
        toolErp::alert("Lançado");
    }

    public function sed_carga_horaria_plSet() {
        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9, 25, 26, 27, 28, 29, 30] as $v) {
            unset($ins);
            $ins = @$_POST[$v];
            if ($ins) {
                $ins['fk_id_pl'] = $id_pl;
                $ins['fk_id_ciclo'] = $v;
                $test = sql::get('sed_carga_horaria_pl', 'id_chpl', ['fk_id_pl' => $id_pl, 'fk_id_ciclo' => $v], 'fetch');
                if ($test) {
                    $ins['id_chpl'] = $test['id_chpl'];
                }

                $this->db->ireplace('sed_carga_horaria_pl', $ins, 1);
            }
        }
    }

    public function excluirResp() {
        $fk_id_pessoa_resp = filter_input(INPUT_POST, 'fk_id_pessoa_resp', FILTER_SANITIZE_NUMBER_INT);
        $fk_id_pessoa_aluno = filter_input(INPUT_POST, 'fk_id_pessoa_aluno', FILTER_SANITIZE_NUMBER_INT);
        $sql = "DELETE FROM `ge_aluno_responsavel` WHERE `fk_id_pessoa_aluno` = $fk_id_pessoa_aluno AND `fk_id_pessoa_resp` = $fk_id_pessoa_resp";
        $query = pdoSis::getInstance()->query($sql);
        if ($query) {
            toolErp::alert('Concluído');
        }
    }

    public function grupoInsert() {
        $insert['fk_id_pessoa'] = @$_POST['id_pessoa'];
        $insert['fk_id_inst'] = @$_POST['id_inst'];
        $insert['fk_id_gr'] = @$_POST['fk_id_gr'];
        $this->db->ireplace('acesso_pessoa', $insert);
    }

    public function escluirUser() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $sql = "DELETE FROM `acesso_pessoa` WHERE `fk_id_pessoa` = " . $id_pessoa;
        $query = $this->db->query($sql);
        $array = $query->fetchAll();
    }

    public function excluirFunc() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $fk_id_inst = filter_input(INPUT_POST, 'fk_id_inst', FILTER_SANITIZE_NUMBER_INT);
        $sql = "DELETE FROM `ge_funcionario` WHERE fk_id_pessoa = " . $id_pessoa;
        $query = pdoSis::getInstance()->query($sql);
        $sql = "DELETE FROM `acesso_pessoa` WHERE fk_id_pessoa = " . $id_pessoa
                . " AND fk_id_inst = " . $fk_id_inst;
        $query = pdoSis::getInstance()->query($sql);
        toolErp::alert("Concluído");
    }

    public function novaPessoa() {
        $this->replacePessoa($_POST[1]);
        @$id_pessoa = sqlErp::get('pessoa', 'id_pessoa', ['cpf' => $_POST[1]['cpf']], 'fetch')['id_pessoa'];
        @$funExist = sql::get('ge_funcionario', 'fk_id_pessoa', ['fk_id_pessoa' => $id_pessoa, 'fk_id_inst' => toolErp::id_inst(@$_POST['id_inst'])], 'fetch')['fk_id_pessoa'];
        if (!empty($id_pessoa) && empty($funExist)) {
            $insert['fk_id_pessoa'] = $id_pessoa;
            $insert['rm'] = uniqid();
            $insert['funcao'] = empty($_POST['prof']) ? 'outro' : 'Professor';
            $insert['situacao'] = 'Ativo';
            $insert['fk_id_inst'] = toolErp::id_inst(@$_POST['id_inst']);
            $insert['email'] = @$_POST[1]['email'];
            $insert['tel'] = @$_POST['tel1'];
            $insert['cel'] = @$_POST['tel2'];
            $this->db->ireplace('ge_funcionario', $insert, 1);
        } else {
            toolErp::alert("Erro! Verifique se o funcionário já existe ou o CPF pertença a outra pessoa");
        }
    }

    public function contatoSet() {
        $pess = $_POST[1];
        $this->db->ireplace('pessoa', $pess);
        $tel = $_POST['tel'];
        foreach ($tel as $v) {
            $t = explode(')', $v['num']);
            if (!empty($t[1])) {
                $v['num'] = trim($t[1]);
                $v['ddd'] = substr($t[0], 1);
            } else {
                $v['ddd'] = 11;
            }
            $v['fk_id_pessoa'] = $pess['id_pessoa'];
            $this->db->ireplace('telefones', $v, 1);
        }
    }

    public function cropImg() {

        $x = filter_input(INPUT_POST, 'x', FILTER_SANITIZE_NUMBER_INT);
        $y = filter_input(INPUT_POST, 'y', FILTER_SANITIZE_NUMBER_INT);
        $w = filter_input(INPUT_POST, 'w', FILTER_SANITIZE_NUMBER_INT);
        $h = filter_input(INPUT_POST, 'h', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);

        $im = imagecreatefromjpeg(ABSPATH . '/pub/fotos/' . $id_pessoa . '.jpg');
        $im2 = imagecrop($im, ['x' => $x, 'y' => $y, 'width' => $w, 'height' => $h]);
        if ($im2 !== FALSE) {
            imagejpeg($im2, ABSPATH . '/pub/fotos/' . $id_pessoa . '.jpg');
            imagedestroy($im2);
        }
        imagedestroy($im);
    }

    public function cicloGradeSalva() {
        $ins = @$_POST[1];
        $this->db->ireplace('ge_curso_grade', $ins);
        if ($ins['padrao'] == 1) {
            $ci['id_ciclo'] = $ins['fk_id_ciclo'];
            $ci['fk_id_grade'] = $ins['fk_id_grade'];
            $this->db->ireplace('ge_ciclos', $ci, 1);
        }
    }

    public function ge_ciclosSlava() {
        $ins = @$_POST[1];
        if ($ins['dias_semana']) {
            $ins['dias_semana'] = implode(',', $ins['dias_semana']);
        }
        $this->db->ireplace('ge_ciclos', $ins);
    }

    public function dataLetiva() {
        $dt_inicio = filter_input(INPUT_POST, 'dt_inicio', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $dt_fim = filter_input(INPUT_POST, 'dt_fim', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $dias = filter_input(INPUT_POST, 'dias', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        foreach ($dt_inicio as $k => $v) {
            $sql = "REPLACE INTO `sed_letiva_data` (`fk_id_curso`, `fk_id_pl`, `atual_letiva`, `dt_inicio`, `dt_fim`, `dias`) VALUES ("
                    . " '$id_curso', "
                    . " '$id_pl', "
                    . " '$k', "
                    . " '" . (!empty($v) ? $v : '0000-00-00') . "', "
                    . " '" . (!empty($dt_fim[$k]) ? $dt_fim[$k] : '0000-00-00') . "', "
                    . " '" . $dias[$k] . "' "
                    . ");";
            $query = $this->db->query($sql);
        }
        toolErp::alert("Salvo");
    }

    public function mudaAUL() {
        $ins['id_curso'] = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
        $ins['atual_letiva'] = filter_input(INPUT_POST, 'atual_letiva', FILTER_SANITIZE_NUMBER_INT);
        $mudaAUL = filter_input(INPUT_POST, 'mudaAUL', FILTER_UNSAFE_RAW);
        if ($mudaAUL == 'mais') {
            $ins['atual_letiva']++;
        } elseif ($mudaAUL == 'menos' && $ins['atual_letiva'] > 1) {
            $ins['atual_letiva']--;
        }
        $this->db->ireplace('ge_cursos', $ins);
    }

    public function sed_muralSalva() {
        $ins = @$_POST[1];

        $this->db->ireplace('sed_mural', $ins);
    }

    public function verificaProfAula($id_turma) {
        $sql = " SELECT rm, iddisc FROM `ge_aloca_prof` "
                . " WHERE `fk_id_turma` = $id_turma "
                . " AND rm != '' ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $rmDisc[$v['iddisc']] = $v['rm'];
        }
        $sql = " SELECT "
                . " ap.rm, p.n_pessoa, t.periodo, h.* "
                . " FROM ge_aloca_prof ap "
                . " JOIN ge_horario h on h.fk_id_turma = ap.fk_id_turma AND h.iddisc = ap.iddisc "
                . " join ge_funcionario f on f.rm = ap.rm "
                . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " join ge_turmas t on t.id_turma = ap.fk_id_turma and t.periodo != 'I' "
                . " WHERE ap.rm in( "
                . " SELECT DISTINCT `rm` FROM `ge_aloca_prof` "
                . " WHERE `fk_id_turma` = $id_turma "
                . " AND rm != '' "
                . " ) "
                . " AND h.fk_id_turma != $id_turma ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $tem[$v['dia_semana']][$v['periodo']][$v['aula']][$v['rm']] = $v['n_pessoa'];
        }

        if (!empty($tem)) {
            $result[] = $tem;
        } else {
            $result[] = [];
        }
        if (!empty($rmDisc)) {
            $result[] = $rmDisc;
        } else {
            $result[] = [];
        }
        return $result;
    }

    public function turmaProfAula($rm, $dia, $aula, $periodo) {
        $sql = "SELECT DISTINCT ap.rm, t.n_turma, h.dia_semana, h.aula, i.n_inst FROM ge_aloca_prof ap "
                . "JOIN ge_horario h on h.fk_id_turma = ap.fk_id_turma AND ap.iddisc = h.iddisc AND `rm` LIKE '$rm' AND h.dia_semana = $dia AND h.aula = $aula "
                . "JOIN ge_turmas t on t.id_turma = h.fk_id_turma and t.periodo like '$periodo' "
                . " join instancia i on i.id_inst = t.fk_id_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array['n_turma'].' da '.$array['n_inst'];
    }

    public function alocahorario() {
        $id_turma = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $id_grade = filter_input(INPUT_POST, 'id_grade', FILTER_SANITIZE_NUMBER_INT);
        $verificaProfAula = $this->verificaProfAula($id_turma);
        $periodo = sql::get('ge_turmas', 'periodo', ['id_turma' => $id_turma], 'fetch')['periodo'];

        for ($c = 1; $c <= 5; $c++) {
            for ($y = 1; $y <= 5; $y++) {
                @$disc[@$_POST['aula'][$c][$y]]++;
            }
        }

        $discGrade = sql::get('ge_aloca_disc', '*', ['fk_id_grade' => @$id_grade]);

        foreach ($discGrade as $v) {
            if (@$disc[$v['fk_id_disc']] > $v['aulas'] && $v['nucleo_comum'] != 1) {
                $ndisc = sql::get('ge_disciplinas', 'n_disc', ['id_disc' => $v['fk_id_disc']], 'fetch')['n_disc'];
                toolErp::alert($ndisc . " só tem " . $v['aulas'] . " aula" . ($v['aulas'] > 1 ? 's' : ''));
                $erro = 1;
            }
        }

        if (empty($erro)) {
            $insert['fk_id_turma'] = $id_turma;
            foreach ($_POST['aula'] as $dia => $aulas) {
                $insert['dia_semana'] = $dia;
                foreach ($aulas as $naula => $id_disc) {
                    $insert['aula'] = $naula;
                    $insert['iddisc'] = @$id_disc;
                    if (!empty(@$prof = $verificaProfAula[0][$dia][$periodo][$naula][@$verificaProfAula[1][@$id_disc]])) {
                        $turma = $this->turmaProfAula($verificaProfAula[1][@$id_disc], $dia, $naula, $periodo);
                        toolErp::alert("professor $prof, RM: " . $verificaProfAula[1][@$id_disc] . ", já está alocado neste horário na turma $turma");
                    } else {
                        $this->db->replace('ge_horario', $insert, 1);
                    }
                }
            }
            if (!empty($_POST['reforco'])) {
                $sql = "DELETE FROM `ge_horario_ref` WHERE `ge_horario_ref`.`fk_id_turma` = '" . $id_turma . "' ";
                $query = $this->db->query($sql);
                foreach ($_POST['reforco'] as $dia => $aula) {
                    if (!empty($aula)) {
                        $insert['dia_semana'] = $dia;
                        $this->db->insert('ge_horario_ref', ['dia_semana' => $dia, 'fk_id_turma' => $insert['fk_id_turma']], 1);
                    }
                }
            }
            toolErp::alert("Salvo");
        }
    }

    public function alocaProf() {

        $pr = @$_POST['pr'];
        $pr1 = @$_POST['pr1'];
        $supl = @$_POST['supl'];
        $cit = @$_POST['cit'];

        $idTurma = $_POST['id_turma'];
        $idInst = $_POST['id_inst'];
        if (!empty($pr)) {
            foreach ($pr as $k => $v) {
                $sql = "REPLACE INTO `ge_aloca_prof` (`fk_id_turma`, `iddisc`, `fk_id_inst`, `rm`, prof2, suplementar, cit) VALUES ('$idTurma', '$k', '$idInst', '$v', '1', '" . @$supl[1][$k] . "', '" . @$cit[1][$k] . "');";
                $query = pdoSis::getInstance()->query($sql);
            }
        }
        foreach (range(2, 15) as $p2) {
            if (!empty($_POST['pr' . $p2])) {
                $pr1 = $_POST['pr' . $p2];
                foreach ($pr1 as $k => $v) {
                    if ($v) {
                        $sql = "REPLACE INTO `ge_aloca_prof` (`fk_id_turma`, `iddisc`, `fk_id_inst`, `rm`, prof2, suplementar, cit) VALUES ('$idTurma', '$k', '$idInst', '$v', $p2, '" . @$supl[$p2][$k] . "', '" . @$cit[$p2][$k] . "');";
                    } else {
                        $sql = "DELETE FROM ge_aloca_prof WHERE fk_id_turma = $idTurma AND iddisc = '$k' AND prof2 = $p2 ";
                    }
                    $query = pdoSis::getInstance()->query($sql);
                }
            }
        }

        log::logSet('Alocou professores');
        toolErp::alert("Salvo com Sucesso!");
    }

    public function salvaInstCurso() {
        $ins['fk_id_inst'] = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $c = @$_POST['c'];
        $sql = "DELETE FROM `sed_inst_curso` WHERE fk_id_inst = " . $ins['fk_id_inst'];
        $query = pdoSis::getInstance()->query($sql);
        foreach ($c as $k => $v) {
            if ($v == 1) {
                $ins['fk_id_curso'] = $k;
                $this->db->insert('sed_inst_curso', $ins, 1);
            }
        }
        toolErp::alert('Concluido');
    }

    public function salvaEscInst() {
        $i = @$_POST['i'];
        $e = @$_POST['e'];
        $l = @$_POST['l'];
        $this->db->ireplace('instancia', $i);
        $this->db->ireplace('ge_escolas', $e, 1);
        $id = $this->db->ireplace('predio', $l, 1);
        if (empty($l['id_predio'])) {
            $ip['sede'] = 1;
            $ip['fk_id_predio'] = $id;
            $ip['fk_id_inst'] = $i['id_inst'];
            try {
                $this->db->ireplace('instancia_predio', $ip, 1);
            } catch (Exception $exc) {
                
            }
        }
    }

    public function alunoNovoSet() {
        $ra = filter_input(INPUT_POST, 'ra', FILTER_UNSAFE_RAW);
        $uf = filter_input(INPUT_POST, 'uf', FILTER_UNSAFE_RAW);
        if ($ra && $uf) {
            if (is_numeric($ra)) {
                $ra = intval($ra);
            }
            $sql = "SELECT id_pessoa, n_pessoa, dt_nasc FROM `pessoa` WHERE `ra` = '$ra' AND `ra_uf` LIKE '$uf'";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetch(PDO::FETCH_ASSOC);
            if ($array) {
                return $array;
            } else {
                $dados = restImport::alunoNovoRede($ra, $uf);
                return $dados;
            }
        }
    }

    public function spe() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $ins = $_POST[1];
        $sql = "SELECT * FROM `ge_aluno_responsavel` WHERE `fk_id_pessoa_aluno` = $id_pessoa AND `fk_id_rt` = 2";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if (empty($array['fk_id_pessoa_resp'])) {
            $this->db->ireplace('pessoa', $ins);
        } else {
            toolErp::alertModal('Este aluno tem um PAI cadastrado');
        }
    }

    public function uploadImgCad() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $ins['fk_id_pessoa'] = $id_pessoa;
        $ins['end'] = $id_pessoa . '_' . filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW) . '.jpg';
        $ins['fk_id_pront'] = filter_input(INPUT_POST, 'fk_id_pront', FILTER_SANITIZE_NUMBER_INT);
        $ins['n_pu'] = filter_input(INPUT_POST, 'n_pu', FILTER_UNSAFE_RAW);
        $ins['dt_pu'] = date("Y-m-d");

        $id = $this->db->insert('sed_prontuario_up', $ins);
    }

    public function upDoc() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $file = ABSPATH . '/pub/sed_doc/';
        $prefix = $id_pessoa;
        $up = new upload($file, $prefix);
        $end = $up->up();
        $ins['fk_id_pessoa'] = $id_pessoa;
        $ins['end'] = $end;
        $ins['fk_id_pront'] = filter_input(INPUT_POST, 'fk_id_pront', FILTER_SANITIZE_NUMBER_INT);
        $ins['n_pu'] = filter_input(INPUT_POST, 'n_pu', FILTER_UNSAFE_RAW);
        $ins['dt_pu'] = date("Y-m-d");

        $id = $this->db->insert('sed_prontuario_up', $ins);
    }

    public function excluiGA() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $id_gr = filter_input(INPUT_POST, 'id_gr', FILTER_SANITIZE_NUMBER_INT);
        $sql = "DELETE FROM `sed_grupo_aluno` WHERE `sed_grupo_aluno`.`fk_id_pessoa` = $id_pessoa AND `sed_grupo_aluno`.`fk_id_gr` = $id_gr";
        pdoSis::getInstance()->query($sql);
    }

    public function incluiGA() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $id_gr = filter_input(INPUT_POST, 'id_gr', FILTER_SANITIZE_NUMBER_INT);
        $sql = "REPLACE INTO `sed_grupo_aluno` (`fk_id_pessoa`, `fk_id_gr`) VALUES ('$id_pessoa', '$id_gr');
";
        pdoSis::getInstance()->query($sql);
    }

    public function importarAluno($json = null) {
        $post = $_POST;
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $ra = filter_input(INPUT_POST, 'ra', FILTER_UNSAFE_RAW);
        $ra_dig = filter_input(INPUT_POST, 'ra_dig', FILTER_UNSAFE_RAW);
        $ra_uf = filter_input(INPUT_POST, 'ra_uf', FILTER_UNSAFE_RAW);

        if (empty($ra_uf)) {
            $ra_uf = 'SP';
        }
        if ($id_pessoa && $ra) {
            session_write_close();
            $pessoa = restImport::alunoNovoRede($ra, trim($ra_uf), trim($ra_dig), $id_pessoa);
            if (empty($json)) {
                toolErp::alertModal('Sincronizado');
            } elseif (!empty($pessoa)) {
                foreach ($post as $k => $v) {
                    if (!empty($pessoa[$k])) {
                        if ($pessoa[$k] != $v && $k != 'dt_gdae') {
                            $teste = 1;
                        }
                    }
                }
                if (!empty($teste)) {
                    echo '1'; //tem alterações;
                } elseif (!empty($pessoa['dt_gdae'])) {
                    echo data::porExtenso($pessoa['dt_gdae']); // não tem alteração    
                }
            } else {
                echo 'error';
            }
        } else {
            echo 'error';
        }
    }

    public function importarTurma() {
        $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
        $codClasse = sql::get('ge_turmas', 'prodesp', ['id_turma' => $id_turma], 'fetch')['prodesp'];
        $dados = restImport::baixarTurmaAluno($codClasse, null, null, 1);

        if (!empty($dados['outAlunos'])) {
            foreach ($dados['outAlunos'] as $v) {
                if (!empty($v)) {
                    @$alu[$v['outNumAluno']] = $v;
                }
            }

            if (!empty($alu)) {
                toolErp::alertModal(count($alu) . ' alunos atualizados com sucesso');
            } else {
                toolErp::alertModal('Não há alunos Nesta Turma');
            }

            unset($dados);
            if (!empty($alu)) {
                return $alu;
            }
        } else {
            toolErp::alert("Erro ao acessar a Prodesp. Tente novamente");
            return;
        }
    }

    public function cadastroResponsavelSet($inf) {
        $hidden = unserialize($inf['hidden_ft']);
        $cpf = $hidden['cpf'];
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $dados = @$_POST[1];
        $cpf = $this->validaCpf($cpf);
        if (is_numeric($cpf)) {
            $dados['cpf'] = $cpf;
        } else {
            toolErp::alertModal($cpf);
            return;
        }
        if (!empty($dados['emailgoogle'])) {
            $email = trim($dados['emailgoogle']);
            @$teste = sql::get('pessoa', 'id_pessoa', ['emailgoogle' => $email], 'fetch')['id_pessoa'];

            if (!empty($teste) && $teste != $dados['id_pessoa']) {
                toolErp::alertModal('Este e-mail está cadastrado para outro usuário');
                return;
            } else {
                $dados['emailgoogle'] = $email;
            }
        }
        $id = $this->db->ireplace('pessoa', $dados);
        if ($id) {
            $tel = @$_POST['tel'];
            if ($tel) {
                foreach ($tel as $v) {
                    if (!empty($v['num'])) {
                        $insTel = [];
                        $dddNum = explode(') ', $v['num']);
                        if (empty($dddNum[1])) {
                            toolErp::alert('Número de telefone Mal formado');
                        } else {
                            $insTel['num'] = str_replace('-', '', $dddNum[1]);
                            $insTel['ddd'] = substr($dddNum[0], 1);
                            $insTel['id_tel'] = $v['id_tel'];
                            $insTel['fk_id_tt'] = $v['fk_id_tt'];
                            $insTel['fk_id_pessoa'] = $id;
                            $this->db->replace('telefones', $insTel, 1);
                        }
                    }
                }
            }
            $resp = @$_POST[2];
            $resp['fk_id_pessoa_resp'] = $id;
            $resp['fk_id_pessoa_aluno'] = $id_pessoa;
            $this->db->replace('ge_aluno_responsavel', $resp, 1);

            if ($resp['fk_id_rt'] == 1) {
                $parente = 'mae';
            } elseif ($resp['fk_id_rt'] == 2) {
                $parente = 'pai';
            } elseif ($resp['fk_id_rt'] == 3) {
                $parente = 'responsavel';
            }
            if (!empty($parente)) {
                $aluno['id_pessoa'] = $id_pessoa;
                $aluno[$parente] = $dados['n_pessoa'];
                $this->db->ireplace('pessoa', $aluno, 1);
            }
        }
    }

    public function fotoUp() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        if (!empty($_FILES['arquivo']['name'])) {
            $this->uploadImg($id_pessoa);
        }
    }

    public function uploadImg($id_pessoa) {
        $file = ABSPATH . '/pub/fotos/';
        $up = new upload($file, $id_pessoa);
        $up->_rename = false;
        $up->_resize = false;
        $end = $up->up();
        $exten = explode('.', $end)[1];
        if (in_array($exten, ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'])) {
            //redimencionar
            $size = new resizeImg($file . $end);
            $size->resizeImage(2000, 472);
            $size->saveImage($file . $id_pessoa . '.jpg');
            //cortar
            $size = new resizeImg($file . $id_pessoa . '.jpg');
            $size->resizeImage(354, 472, 'crop');
            $size->saveImage($file . $id_pessoa . '.jpg');
        }
    }

    public function baixarTurmaAluno() {
        $prodesp = filter_input(INPUT_POST, 'prodesp', FILTER_UNSAFE_RAW);
        $id_pl = gtMain::periodoSet(filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT));
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        restImport::baixarTurmaAluno($prodesp, $id_inst, $id_pl, 1);
    }

    public function sincronizarTurmas() {
        $ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_NUMBER_INT);
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $dados = restImport::baixarClasses($ano, $id_inst);

        $this->return['turmas'] = $dados;
    }

    public function salvaQuadro() {
        $ins = @$_POST[1];
        $tpe = filter_input(INPUT_POST, 'tpe', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $ins['tp_ensino'] = ':';
        if ($tpe) {
            foreach ($tpe as $k => $v) {
                if ($v == 1) {
                    $ins['tp_ensino'] .= $k . ':';
                }
            }
        }
        if (empty($ins['tp_ensino'])) {
            $ins['tp_ensino'] = ':';
        }
        $ins['fk_id_pessoa'] = toolErp::id_pessoa();
        $this->db->ireplace('sed_quadro', $ins);
    }

    public function quadroAviso() {
        $sql = "SELECT * FROM `sed_quadro` "
                . " WHERE `dt_ini` >= '" . date('Y-m-d') . "' "
                . " or dt_fim >= '" . date('Y-m-d') . "' "
                . " order by dt_ini ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function quadroAvisoEscola($id_inst) {
        $sql = "SELECT fk_id_tp_ens FROM instancia i JOIN ge_escolas e on e.fk_id_inst = i.id_inst WHERE i.id_inst = " . $id_inst;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($array['fk_id_tp_ens'])) {
            foreach (explode('|', $array['fk_id_tp_ens']) as $v) {
                @$and[] = " tp_ensino like '%:$v:%' ";
            }
        }
        if (!empty($and)) {
            $where = ' where (' . implode("or", $and) . ')';
        } else {
            $where = 'where 1 ';
        }
        $sql = "SELECT descr_q, n_q FROM `sed_quadro` "
                . $where
                . " and "
                . "("
                . "`dt_ini` >= '" . date('Y-m-d') . "' "
                . " or dt_fim >= '" . date('Y-m-d') . "' "
                . " ) "
                . " and at_q = 1 "
                . " order by dt_ini ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function salaAula($periodo, $id_inst = null) {
        if (!empty($id_inst)) {
            $id_inst = " AND i.id_inst = $id_inst";
        }
        $sql = "SELECT "
        . " t.fk_id_inst as id_inst, t.id_turma, COUNT(ta.fk_id_pessoa) as alunos "
        . " FROM ge_turma_aluno ta "
        . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
        . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
        . " JOIN instancia i on i.id_inst = t.fk_id_inst "
        . " where 1 "
        . $id_inst
        . " GROUP BY t.id_turma";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $alu = [];
        foreach ($array as $v) {
            $alu[$v['id_turma']] = $v['alunos'];
        }
        $sql = "SELECT "
                . " t.n_turma, t.id_turma, t.fk_id_sala, t.fk_id_inst as id_inst, "
                . " ci.n_ciclo, ci.id_ciclo "
                . " FROM ge_turmas t "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " JOIN instancia i on i.id_inst = t.fk_id_inst "
                . " where t.periodo = '$periodo' "
                . $id_inst;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $turmas = [];
        foreach ($array as $v) {
            if (!empty($v['fk_id_sala'])) {
                $turmas[$v['fk_id_sala']] = $v;
            }
        }

        $sql = "SELECT "
                . " s.largura, s.comprimento, s.piso, s.cadeirante, s.n_sala, s.alunos_sala, s.id_sala, "
                . " ts.n_ts, "
                . " i.id_inst, i.n_inst "
                . " FROM salas s "
                . " JOIN tipo_sala ts on ts.id_ts = s.fk_id_ts "
                . " JOIN instancia_predio ip on ip.fk_id_predio = s.fk_id_predio"
                . " JOIN instancia i on i.id_inst = ip.fk_id_inst "
                . " where ts.id_ts in (2, 17, 18) "
                . $id_inst
                . " order by n_inst, n_sala";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $salas = [];
        foreach ($array as $v) {
            $salas[$v['id_inst']][$v['id_sala']] = $v;
            $salas[$v['id_inst']][$v['id_sala']]['turma'] = @$turmas[$v['id_sala']];
            $salas[$v['id_inst']][$v['id_sala']]['turma']['alunos'] = @$alu[@$turmas[$v['id_sala']]['id_turma']];
        }

        return $salas;
    }

    public function assinatura() {
        $permitido = [CLI_MAIL_DOMINIO];
        $email = filter_input(INPUT_POST, 'userEmail', FILTER_UNSAFE_RAW);
        $nome = filter_input(INPUT_POST, 'userName', FILTER_UNSAFE_RAW);
        $pessoa = sql::get('pessoa', 'n_pessoa', ['emailgoogle' => $email], 'fetch');

        if (!empty($pessoa['n_pessoa'])) {
            echo $pessoa['n_pessoa'];
        } elseif (in_array(explode('@', $email)[1], $permitido)) {
            echo $nome;
        } else {
            echo 'erro';
        }
    }

    public function robotLogsErro($data) {
        $sql = " SELECT * FROM `sed_erro` "
                . " WHERE `time_stamp` LIKE '$data%' "
                . " ORDER BY time_stamp ASC ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function robotLogs($data) {
        $sql = "SELECT * FROM `sed_robot` "
                . " WHERE `time_stamp` LIKE '$data%' "
                . " ORDER BY time_stamp ASC ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function validaCpf($cpf = null) {
        $cpf = toolErp::cpfValida($cpf);
        if ($cpf == 0) {
            $erro = explode(' ', toolErp::n_pessoa())[0] . ', o CPF digitado não é válido';
        } elseif (empty($cpf)) {
            $erro = explode(' ', toolErp::n_pessoa())[0] . ', o CPF é obrigatório';
        }
        if (empty($erro)) {
            return $cpf;
        } else {
            return $erro;
        }
    }

    public function resposavel($id_pessoa, $principal = null) {
        if ($principal) {
            $principal = " AND r.responsavel = 1 ";
        }
        $sql = " SELECT "
                . " r.responsavel, p.id_pessoa, p.n_pessoa, p.cpf, p.email, rt.n_rt FROM ge_aluno_responsavel r "
                . " left JOIN ge_responsavel_tipo rt on rt.id_rt = r.fk_id_rt"
                . " JOIN pessoa p on p.id_pessoa = r.fk_id_pessoa_resp "
                . " WHERE r.fk_id_pessoa_aluno = " . $id_pessoa
                . $principal
                . "order by p.n_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        if ($principal) {
            $resp = $query->fetch(PDO::FETCH_ASSOC);
        } else {
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($array as $v) {
                $resp[$v['id_pessoa']] = $v;
            }
        }
        return $resp;
    }

    public function alunosGrupo($id_gr) {
        $sql = "SELECT "
                . " p.n_pessoa, p.id_pessoa, ta.chamada, t.n_turma, i.n_inst FROM sed_grupo_aluno ga "
                . " JOIN pessoa p on p.id_pessoa = ga.fk_id_pessoa "
                . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = ga.fk_id_pessoa "
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " JOIN instancia i ON i.id_inst = t.fk_id_inst "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " WHERE ga.fk_id_gr = $id_gr "
                . " order by n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function mural($id_inst, $antigos = null) {
        if (empty($antigos)) {
            $antigos = " AND m.dt_fim >= '2021-06-29' ";
        } else {
            $antigos = null;
        }
        $sql = "SELECT "
                . " m.*, t.n_turma, g.n_gr "
                . " FROM sed_mural m "
                . " LEFT JOIN ge_turmas t on t.id_turma = m.fk_id_turma "
                . " LEFT JOIN sed_grupo g on g.id_gr = m.fk_id_gr "
                . " WHERE m.fk_id_inst = $id_inst "
                . $antigos
                . " ORDER BY m.dt_inicio";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function getUploads($id_pessoa) {
        $fields = " id_pu, n_pu, end, dt_pu, n_pront ";
        $sql = " SELECT $fields FROM sed_prontuario_up u "
                . " JOIN sed_prontuario p on p.id_pront = u.fk_id_pront "
                . " WHERE u.fk_id_pessoa = $id_pessoa "
                . " ORDER BY p.n_pront ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function appRetirada() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa_resp = filter_input(INPUT_POST, 'id_pessoa_resp', FILTER_SANITIZE_NUMBER_INT);
        $sit = filter_input(INPUT_POST, 'sit', FILTER_SANITIZE_NUMBER_INT);
        $campo = filter_input(INPUT_POST, 'campo', FILTER_UNSAFE_RAW);

        $sql = "UPDATE ge_aluno_responsavel SET $campo = $sit WHERE fk_id_pessoa_aluno = $id_pessoa and fk_id_pessoa_resp = $id_pessoa_resp";
        $query = pdoSis::getInstance()->query($sql);
        $sql = "SELECT $campo FROM `ge_aluno_responsavel` WHERE `fk_id_pessoa_aluno` = $id_pessoa AND `fk_id_pessoa_resp` = $id_pessoa_resp";
        $query = pdoSis::getInstance()->query($sql);
        $sitTeste = $query->fetch(PDO::FETCH_ASSOC)[$campo];
        if ($sit == $sitTeste) {
            echo '1';
        } else {
            echo 'x';
        }
    }

    public function fotoEnd($id_pessoa) {
        if (file_exists(ABSPATH . "/pub/fotos/" . $id_pessoa . ".jpg")) {
            return HOME_URI . '/pub/fotos/' . $id_pessoa . '.jpg?' . uniqid();
        } elseif (file_exists(ABSPATH . "/pub/fotos/" . $id_pessoa . ".png")) {
            return HOME_URI . '/pub/fotos/' . $id_pessoa . '.png?' . uniqid();
        } else {
            return HOME_URI . '/'. INCLUDE_FOLDER .'/images/anonimo.jpg';
        }
    }

    public function upload() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $file = ABSPATH . '/pub/fotos/';
        $up = new upload($file, $id_pessoa, 21000000000000000000000, ['jpg'], null);
        $end = $up->up();
        if ($end) {
            //diminuir a imagem
            $im = @imagecreatefromjpeg($file . $id_pessoa . '.jpg');
            if(empty($im)){
                toolErp::alert("Imagem fora do padrão.");
                return;
            }
            $x = imagesx($im);
            $y = imagesy($im);
            $y = $y / ($x / 320);

            $resizeObj = new resizeImg($file . $id_pessoa . '.jpg');
            $resizeObj->resizeImage(320, $y, 'auto');
            $resizeObj->saveImage($file . $id_pessoa . '.jpg', 100);
        }
    }

    public function rotateImg() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $filename = ABSPATH . '/pub/fotos/' . $id_pessoa . '.jpg';
        shell_exec('chmod 777 ' . $filename);
        $rotang = 90; // Rotation angle
        $source = imagecreatefromjpeg($filename) or die('Erro ao Abrir o Arquivo ' . $filename);

        $rotation = imagerotate($source, $rotang, 0);
        imagejpeg($rotation, ABSPATH . '/pub/fotos/' . $id_pessoa . '.jpg', 100);
        imagedestroy($rotation);
    }

    public function selecaodisciplina() {
        $d = sql::get('ge_disciplinas', 'n_disc', ['>' => 'n_disc']);

        foreach ($d as $v) {
            $di[$v['n_disc']] = $v['n_disc'];
        }
        $di['Núcleo Comum'] = 'Núcleo Comum';

        return $di;
    }

    public function selectPredio($id_inst = NULL, $post = NULL, $form = NULL) {
        $p = predio::instPredio($id_inst);
        if (count($p) > 1) {
            foreach ($p as $v) {
                $options[$v['id_predio']] = $v['n_predio'];
            }

            formulario::select('id_predio', $options, 'Selecione um Prédio:', $post, $form);

            return;
        } else {
            if (!empty($p[0])) {
                return $p[0];
            } else {
                toolErp::alert("Não há prédio cadastrado");
            }
        }
    }

    /**
     * 
     * @param type $id_prediolista salas por predio
     */
    public function lisSalas($id_predio, $id_inst = null) {
        $id_inst = toolErp::id_inst($id_inst);
        $salas = predio::salas($id_predio, 'id_sala, n_sala, n_ts, piso, cadeirante, largura, comprimento, alunos_sala', ['>' => 'id_sala']);
        $sqlkey = formErp::hiddenToken('salas', 'delete');
        foreach ($salas as $k => $v) {

            @$salas[$k]['tm'] = round(($v[largura] * $v['comprimento']), 2) . ' m²';
            @$salas[$k]['razao'] = round((@$salas[$k]['tm'] / $v['alunos_sala']), 2) . ' m²';
            @$salas[$k]['cadeirante'] = toolErp::simnao($v['cadeirante']);
            @$salas[$k]['edit'] = formErp::submit('Editar', null, ['id_predio' => @$id_predio, 'id_inst' => @$id_inst, 'id_sala' => $v['id_sala'], 'aba' => 'sala', 'setSalas' => 1]);
            @$salas[$k]['del'] = formErp::submit('Excluir', $sqlkey, ['1[id_sala]' => $v['id_sala'], 'id_predio' => @$id_predio, 'id_inst' => @$id_inst, 'aba' => 'sala']);
        }

        $form['array'] = $salas;
        $form['fields'] = [
            'Nome' => 'n_sala',
            'Finalidade' => 'n_ts',
            'Piso' => 'piso',
            'Acessibilidade' => 'cadeirante',
            'Capacidade Física' => 'alunos_sala',
            'Razão Aluno/m²' => 'razao',
            'Metragem' => 'tm',
            '||1' => 'del',
            '||2' => 'edit'
        ];
        report::simple($form);
    }

    public function cadProf() {
        $disc = @$_POST['disc'];

        foreach ($disc as $k => $v) {
            if (!empty($v)) {
                $disciplinas[$k] = $v;
            }
        }

        @$prof['disciplinas'] = '|' . implode('|', $disciplinas) . '|';
        $prof['fk_id_inst'] = toolErp::id_inst();
        $prof['rm'] = $_POST['rm'];
        @$prof['n_pe'] = $_POST['n_pe'];
        @$prof['id_pe'] = $_POST['id_pe'];
        @$prof['hac_dia'] = $_POST['hac_dia'];
        @$prof['hac_periodo'] = $_POST['hac_periodo'];
        @$prof['nao_hac'] = $_POST['nao_hac'];
        // @$prof['email'] = $_POST['email'];
        @$prof['fk_id_psc'] = $_POST['fk_id_psc'];
        $rm = sql::get('ge_funcionario', 'rm', ['rm' => $prof['rm']], 'fetch')['rm'];
        if (!empty($prof['email'])) {
            $kbum = explode('@', $prof['email']);
            if (isset($kbum[1]) && $kbum[1] == CLI_MAIL_DOMINIO) {
                $prof['email'] = NULL;
                toolErp::alert("O e-mail não foi salvo. Não é permitido e-mails institucionais");
            }
        }
        if (empty($rm)) {
            toolErp::alert("Não foi encontrado Professor com a matrícula " . $prof['rm']);
        } else {
            $sql = "select p.id_pessoa, p.email, pe.rm, pe.fk_id_inst from ge_prof_esc pe "
                    . " left join ge_funcionario f on f.rm = pe.rm "
                    . " left join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                    . " where f.rm = '" . $rm . "'";
            $query = $this->db->query($sql);
            $dados = $query->fetch();

            if (!empty($dados['rm']) && !empty($dados['fk_id_inst']) && @$dados['fk_id_inst'] == toolErp::id_inst()) {
                /**
                  $sql = "select email from pessoa "
                  . "where email like '" . $prof['email'] . "' "
                  . "and id_pessoa <> " . $dados['id_pessoa'];
                  $query = $this->db->query($sql);
                  $emailVerif = $query->fetch()['email'];
                  if (!empty($emailVerif)) {
                  toolErp::alert("Este e-mail já está cadastrado e não foi salvo");
                  $prof['email'] = NULL;
                  }

                  $sql = "UPDATE `ge_prof_esc` SET `disciplinas` = '" . @$prof['disciplinas'] . "', "
                  . "`email` = '" . @$prof['email'] . "', "
                  . "`fk_id_psc` = '" . @$prof['fk_id_psc'] . "' "
                  . " WHERE `fk_id_inst` = " . toolErp::id_inst() . " AND `rm` = '" . $prof['rm'] . "'";
                 * 
                 */
                $sql = "UPDATE `ge_prof_esc` SET `disciplinas` = '" . @$prof['disciplinas'] . "', "
                        . "`email` = '" . @$prof['email'] . "', "
                        . "`hac_dia` = '" . @$prof['hac_dia'] . "', "
                        . "`hac_periodo` = '" . @$prof['hac_periodo'] . "', "
                        . "`fk_id_psc` = '" . @$prof['fk_id_psc'] . "', "
                        . "`nao_hac` = '" . @$prof['nao_hac'] . "'"
                        . " WHERE `fk_id_inst` = " . toolErp::id_inst() . " AND `rm` = '" . $prof['rm'] . "'";
                $query = pdoSis::getInstance()->query($sql);

                if (!empty($prof['email']) && $prof['email'] <> $dados['email']) {

                    $fields['email'] = $prof['email'];
                    $fields['id_pessoa'] = $dados['id_pessoa'];
                    $sql = "update pessoa set email = '" . $fields['email'] . "' where id_pessoa = " . $fields['id_pessoa'];
                    $query = $this->db->query($sql);
                }
                log::logSet('Alterou as disciplinas do(a) professor(a) ' . funcionarios::Get($prof['rm'], 'rm', 'n_pessoa')[0]['n_pessoa']);
                toolErp::alert(user::session('n_pessoa') . " alterou dados com sucesso!");
            } else {

                $this->db->ireplace('ge_prof_esc', $prof);
                $sql = "select p.id_pessoa, p.email, pe.rm, pe.fk_id_inst from ge_prof_esc pe "
                        . " left join ge_funcionario f on f.rm = pe.rm "
                        . " left join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                        . " where f.rm = '" . $rm . "'";
                $query = $this->db->query($sql);
                $dados = $query->fetch();
                /**
                  $sql = "select email from pessoa "
                  . "where email like '" . $prof['email'] . "' "
                  . "and id_pessoa <> '" . $dados['id_pessoa'] . "'";
                  $query = $this->db->query($sql);
                  $emailVerif = $query->fetch()['email'];
                  if (!empty($emailVerif)) {
                  toolErp::alert("Este e-mail já está cadastrado e não foi salvo");
                  $prof['email'] = NULL;
                  }
                  if (!empty($dados['id_pessoa'])) {
                  $fields['email'] = $prof['email'];
                  $fields['id_pessoa'] = $dados['id_pessoa'];
                  $this->db->ireplace('pessoa', $fields, 1);
                  }
                 * 
                 * @param type $id_pessoa
                 * @param type $dt_nasc
                 * @return type
                 */
            }
        }
    }

    public function declaracaoEscolar($id_pessoa, $dt_nasc) {
        $password_hash = new PasswordHash(8, FALSE);
        $token = $password_hash->HashPassword($dt_nasc);

        $qr = CLI_URL .'/'. HOME_URI . '/sed/pdf/declaracaoQr.php?id=' . $id_pessoa . '&token=' . urlencode($token);
        return urldecode($qr);
    }

    public function relatPer() {
        $todos = filter_input(INPUT_POST, 'todos', FILTER_SANITIZE_NUMBER_INT);
        if (empty($todos)) {
            $sit = " WHERE `at_pl` IN (1, 2)";
        }
        $si = ['Inativo', 'Ativo', 'Previsto'];
        $sql = "SELECT * FROM `ge_periodo_letivo` "
                . @$sit;
        $query = $this->db->query($sql);
        $periodos = $query->fetchAll();
        foreach ($periodos as $k => $v) {
            $periodos[$k]['edit'] = '<button class="btn btn-info" onclick="pl(' . $v['id_pl'] . ')">Editar</button>';
            $periodos[$k]['sit'] = $si[$v['at_pl']];
            $periodos[$k]['pd'] = toolErp::simnao($v['preferencial']);
        }
        $form['array'] = $periodos;
        $form['fields'] = [
            'ID' => 'id_pl',
            'Descrição' => 'n_pl',
            'Situação' => 'sit',
            'Ano' => 'ano',
            'Semastre' => 'semestre',
            'Período Padrão' => 'pd',
            '||1' => 'edit'
        ];

        report::simple($form);
    }

    public function replacePessoa($post, $mini = NULL) {
        @$post['emailgoogle'] = trim(@$post['emailgoogle']);
        @$emailExiste = pessoa::get(@$post['emailgoogle'], 'emailgoogle', 'id_pessoa')['id_pessoa'];
        @$emailExiste = $post['id_pessoa'] == $emailExiste ? NULL : $emailExiste;
        $erro = 1;
        if (empty($mini)) {
            $cpfExiste = pessoa::get(@$post['cpf'], 'cpf', 'id_pessoa');
            if (!empty($cpfExiste['id_pessoa']) && !empty($post['id_pessoa'])) {
                @$cpfExiste = $post['id_pessoa'] == $cpfExiste['id_pessoa'] ? NULL : $cpfExiste['id_pessoa'];
            } else {
                @$cpfExiste = null;
            }

            if (empty($post['n_pessoa'])) {
                toolErp::erro('Preencha o Nome');
            } elseif (empty($post['cpf']) && empty($post['emailgoogle'])) {
                toolErp::erro('Preencha o CPF ou E-mail');
            } elseif ((validar::Cpf($post['cpf']) != 1)) {
                toolErp::erro("CPF Inválido");
            } elseif (!strripos($post['emailgoogle'], '@') && !empty($post['emailgoogle'])) {
                toolErp::erro("E-mail Inválido");
            } elseif ($cpfExiste && !empty($post['cpf'])) {
                toolErp::erro("CPF já existe");
            } elseif ($emailExiste && !empty($post['emailgoogle'])) {
                toolErp::erro("E-mail já existe");
            } else {
                $erro = 0;
            }
        } else {
            if (!strripos($post['emailgoogle'], '@') && !empty($post['emailgoogle'])) {
                toolErp::erro("E-mail Inválido");
            } elseif ($emailExiste && !empty($post['emailgoogle'])) {
                toolErp::erro("E-mail já existe");
            } else {
                $erro = 0;
            }
        }

        if ($erro == 0) {
            if (!empty($post['dt_nasc'])) {
                $post['dt_nasc'] = data::converteUS($post['dt_nasc']);
            }

            if (empty($post['emailgoogle'])) {
                unset($post['emailgoogle']);
            }
            $teste = $this->db->ireplace('pessoa', $post);
            if (empty($post['id_pessoa'])) {
                $id_pessoa = $teste;
            } else {
                $id_pessoa = $post['id_pessoa'];
            }
        }

        return $id_pessoa;
    }

    public function acessoEsc($id_inst) {
        $ac['Diretor(a)'] = [2, 4, 6, 40];
        $ac['Coordenador(a)'] = [8, 9, 10];
        $ac['Orientador(a)'] = [46];
        $ac['Secretário(a)'] = [1, 3, 5, 39];
        $sql = "SELECT p.n_pessoa, p.id_pessoa, a.fk_id_gr FROM acesso_pessoa a "
                . " JOIN pessoa p on p.id_pessoa = a.fk_id_pessoa "
                . " WHERE a.fk_id_inst = $id_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $v) {
                foreach ($ac as $k => $a) {
                    if (in_array($v['fk_id_gr'], $a)) {
                        $acesso[$k][$v['id_pessoa']] = $v['n_pessoa'];
                    }
                }
            }
        }
        if (!empty($acesso)) {
            $acessoEsc['Diretor(a)'] = @$acesso['Diretor(a)'];
            $acessoEsc['Coordenador(a)'] = @$acesso['Coordenador(a)'];
            $acessoEsc['Orientador(a)'] = @$acesso['Orientador(a)'];
            $acessoEsc['Secretário(a)'] = @$acesso['Secretário(a)'];
            unset($acesso);
            return $acessoEsc;
        }
    }

    public function profEscRm($id_inst) {
        $sql = "select distinct "
                . " p.n_pessoa, pe.rm "
                . " from ge_prof_esc pe "
                . " join ge_funcionario f on f.rm = pe.rm and f.at_func = 1 "
                . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " where pe.fk_id_inst = " . tool::id_inst()
                . " order by n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $v) {
                $prof[$v['rm']] = $v['n_pessoa'];
            }
            return $prof;
        }
    }

    Public function pegaalunos($alunos, $turma) {

        $alunos = implode(",", $alunos);

        $sql = "SELECT i.n_inst, p.id_pessoa, p.n_pessoa, p.dt_nasc,"
                . " p.ra, p.ra_dig, p.ra_uf, t.n_turma, t.periodo_letivo,"
                . " t.id_turma, pl.ano FROM pessoa p"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " JOIN instancia i ON i.id_inst = ta.fk_id_inst"
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                . " WHERE t.id_turma = $turma AND p.id_pessoa IN($alunos) ORDER BY ta.chamada";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function foraDataCiclo($ciclos = [], $ano = null) {
        if (empty($ano) || $ano == date("Y")) {
            $ano = date("Y");
            $atPl = 1;
        } else {
            $atPl = 2;
        }
        $anoInicio = $ano - 1;
        $anoFim = $ano;
        $idsCiclos = [0 => 21, 1 => 22, 2 => 23, 3 => 24, 4 => 19, 5 => 20];
        $datas = ['04-01', '03-31'];
        foreach ($idsCiclos as $k => $id_ciclo) {
            if (in_array($id_ciclo, $ciclos)) {
                $anoInicioCiclo = $anoInicio - $k;
                $anoFimCiclo = $anoFim - $k;
                $sql = "SELECT "
                        . " p.dt_nasc , p.n_pessoa, p.id_pessoa, p.ra, p.ra_dig, p.ra_uf, "
                        . " ci.n_ciclo, "
                        . " t.n_turma, i.n_inst "
                        . " FROM ge_turma_aluno ta "
                        . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma AND t.fk_id_ciclo = $id_ciclo "
                        . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                        . " JOIN instancia i on i.id_inst = t.fk_id_inst "
                        . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = $atPl "
                        . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                        . " AND (p.dt_nasc > '$anoFimCiclo-03-31' or p.dt_nasc < '$anoInicioCiclo-04-01') "
                        . " order by p.dt_nasc ";
                $query = pdoSis::getInstance()->query($sql);
                $array = $query->fetchAll(PDO::FETCH_ASSOC);
                if ($array) {
                    foreach ($array as $y) {
                        if ($y['dt_nasc'] > $anoFimCiclo . '-03-31') {
                            $y['sit'] = 'Menor';
                            $y['dif'] = data::calcula($y['dt_nasc'], $anoFimCiclo . '-03-31', 'mes');
                            $y['meses'] = data::calcula($y['dt_nasc'], $anoFimCiclo . '-03-31', 'mes');
                            $y['idade'] = data::idade($y['dt_nasc']);
                            if ($y['dif'] == 0) {
                                $y['dias'] = data::calcula($y['dt_nasc'], $anoInicioCiclo . '-04-01', 'dia');
                            } else {
                                $y['dias'] = '-';
                            }
                        } elseif ($y['dt_nasc'] < $anoInicioCiclo . '-04-01') {
                            $y['sit'] = 'Maior';
                            $y['dif'] = data::calcula($y['dt_nasc'], $anoInicioCiclo . '-04-01', 'mes');
                            $y['meses'] = data::calcula($y['dt_nasc'], $anoInicioCiclo . '-04-01', 'mes');
                            $y['idade'] = data::idade($y['dt_nasc']);
                            if ($y['dif'] == 0) {
                                $y['dias'] = data::calcula($y['dt_nasc'], $anoInicioCiclo . '-04-01', 'dia');
                            } else {
                                $y['dias'] = '-';
                            }
                        }
                        $list[$y['n_ciclo']][$y['n_inst']][$y['id_pessoa']] = $y;
                    }
                }
            }
        }

        return @$list;
    }

    public function foraDataCicloPrevisto($id_inst = null, $ano = null) {
        if (empty($ano) || $ano == date("Y")) {
            $ano = date("Y");
            $atPl = 1;
        } else {
            $atPl = 2;
        }
        if ($id_inst) {
            $id_inst = " AND t.fk_id_inst = $id_inst ";
        }
        $c = sql::get('ge_ciclos', 'id_ciclo', ['fk_id_curso' => [3, 7, 8]]);
        $ciclos = array_column($c, 'id_ciclo');
        $anoInicio = $ano - 1;
        $anoFim = $ano;
        $idsCiclos = [0 => 21, 1 => 22, 2 => 23, 3 => 24, 4 => 19, 5 => 20];
        $datas = ['04-01', '03-31'];
        foreach ($idsCiclos as $k => $id_ciclo) {
            if (in_array($id_ciclo, $ciclos)) {
                $anoInicioCiclo = $anoInicio - $k;
                $anoFimCiclo = $anoFim - $k;
                $sql = "SELECT "
                        . " p.dt_nasc , p.n_pessoa, p.id_pessoa, p.ra, p.ra_dig, p.ra_uf, "
                        . " ci.n_ciclo, "
                        . " t.n_turma, i.n_inst "
                        . " FROM ge_turma_aluno ta "
                        . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma AND t.fk_id_ciclo = $id_ciclo $id_inst "
                        . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                        . " JOIN instancia i on i.id_inst = t.fk_id_inst "
                        . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = $atPl "
                        . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                        . " AND (p.dt_nasc > '$anoFimCiclo-03-31' or p.dt_nasc < '$anoInicioCiclo-04-01') "
                        . " order by p.dt_nasc ";
                $query = pdoSis::getInstance()->query($sql);
                $array = $query->fetchAll(PDO::FETCH_ASSOC);
                if ($array) {
                    foreach ($array as $y) {
                        if ($y['dt_nasc'] > $anoFimCiclo . '-03-31') {
                            $y['sit'] = 'Menor';
                            $y['dif'] = data::calcula($y['dt_nasc'], $anoFimCiclo . '-03-31', 'mes');
                            $y['meses'] = data::calcula($y['dt_nasc'], $anoFimCiclo . '-03-31', 'mes');
                            $y['idade'] = data::idade($y['dt_nasc']);
                            if ($y['dif'] == 0) {
                                $y['dias'] = data::calcula($y['dt_nasc'], $anoInicioCiclo . '-04-01', 'dia');
                            } else {
                                $y['dias'] = '-';
                            }
                        } elseif ($y['dt_nasc'] < $anoInicioCiclo . '-04-01') {
                            $y['sit'] = 'Maior';
                            $y['dif'] = data::calcula($y['dt_nasc'], $anoInicioCiclo . '-04-01', 'mes');
                            $y['meses'] = data::calcula($y['dt_nasc'], $anoInicioCiclo . '-04-01', 'mes');
                            $y['idade'] = data::idade($y['dt_nasc']);
                            if ($y['dif'] == 0) {
                                $y['dias'] = data::calcula($y['dt_nasc'], $anoInicioCiclo . '-04-01', 'dia');
                            } else {
                                $y['dias'] = '-';
                            }
                        }
                        $list[$y['n_ciclo']][$y['n_inst']][$y['id_pessoa']] = $y;
                    }
                }
            }
        }
        return @$list;
    }

    public function paternidade($plsArray, $id_inst = null) {
        $pls = implode(', ', $plsArray);
        if ($id_inst) {
            $inst = " and t.fk_id_inst = $id_inst ";
        } else {
            $inst = null;
        }
        $sql = "SELECT "
                . " DISTINCT p.id_pessoa, p.n_pessoa, p.sexo, p.pai, p.mae, end.cep, p.dt_nasc, p.ra, p.ra_uf, "
                . " end.logradouro, end.num, end.complemento, end.bairro, end.cidade, end.uf "
                . " FROM ge_turma_aluno ta "
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma and fk_id_tas = 0 "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1 "
                . $inst
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " AND (p.pai is null OR p.pai = '' OR p.pai LIKE 'SPE' OR p.mae is null OR p.mae = '') "
                . " LEFT JOIN endereco end on end.fk_id_pessoa = ta.fk_id_pessoa "
                . " WHERE ta.fk_id_pessoa not in ( "
                . " SELECT ta2.fk_id_pessoa FROM ge_turma_aluno ta2 "
                . " JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma "
                . " AND t2.fk_id_pl NOT IN ($pls)"
                . "  ) ";
        $query = pdoSis::getInstance()->query($sql);
        $alunos = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($alunos) {
            $ids = array_column($alunos, 'id_pessoa');
            $sql = "select fk_id_pessoa as id_pessoa, t.periodo_letivo from ge_turma_aluno ta "
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma and t.fk_id_ciclo != 32 and fk_id_pessoa in (" . implode(', ', $ids) . ")"
                    . " order by t.periodo_letivo desc";
            $query = pdoSis::getInstance()->query($sql);
            $insts = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($insts as $v) {
                $aluInst[$v['id_pessoa']] = $v['periodo_letivo'];
            }

            foreach ($alunos as $k => $v) {
                $alunos[$k]['periodo_letivo'] = @$aluInst[$v['id_pessoa']];
            }
            return $alunos;
        }
    }

    public function paternidadePlan($plsArray, $id_inst = null) {
        $pls = implode(', ', $plsArray);
        if ($id_inst) {
            $inst = " and t.fk_id_inst = $id_inst ";
        } else {
            $inst = null;
        }
        $sql = "SELECT "
                . " DISTINCT p.id_pessoa as RSE, p.n_pessoa as Nome, p.sexo as Sexo, "
                . " p.mae as Mãe, end.cep CEP, p.dt_nasc Nasc, p.ra RA, "
                . " end.logradouro Logradouro, end.num Num, end.complemento Complemento, "
                . " end.bairro Bairro, end.cidade Cidade, end.uf UF "
                . " FROM ge_turma_aluno ta "
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma and fk_id_tas = 0 "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1 "
                . $inst
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " AND p.pai LIKE 'SPE' AND p.mae is not null and p.mae != '' "
                . " JOIN endereco end on end.fk_id_pessoa = ta.fk_id_pessoa "
                . " WHERE ta.fk_id_pessoa not in ( "
                . " SELECT ta2.fk_id_pessoa FROM ge_turma_aluno ta2 "
                . " JOIN ge_turmas t2 on t2.id_turma = ta2.fk_id_turma "
                . " AND t2.fk_id_pl NOT IN ($pls)"
                . "  ) "
                . " order by t.periodo_letivo asc ";
        $query = pdoSis::getInstance()->query($sql);
        $alunos = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($alunos) {
            $ids = array_column($alunos, 'RSE');
            $sql = "select fk_id_pessoa as id_pessoa, n_inst from ge_turma_aluno ta "
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma and fk_id_tas = 0 and t.fk_id_ciclo != 32 and fk_id_pessoa in (" . implode(', ', $ids) . ")"
                    . " JOIN instancia i on i.id_inst = t.fk_id_inst "
                    . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1 "
                    . " order by t.periodo_letivo asc";
            $query = pdoSis::getInstance()->query($sql);
            $insts = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($insts as $v) {
                $aluInst[$v['id_pessoa']] = $v['n_inst'];
            }

            foreach ($alunos as $k => $v) {
                $alunos[$k]['Escola'] = @$aluInst[$v['RSE']];
            }
            return $alunos;
        }
    }

    public function atualizarPat() {
        $ra = filter_input(INPUT_POST, 'ra');
        $ra_uf = filter_input(INPUT_POST, 'ra_uf');
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa');
        $dados = rest::exibirFichaAluno($ra, $ra_uf);
        if (!empty($dados['outDadosPessoais']['outNomePai'])) {
            $ins['pai'] = @$dados['outDadosPessoais']['outNomePai'];
        }
        if (!empty($dados['outDadosPessoais']['outNomeMae'])) {
            $ins['mae'] = @$dados['outDadosPessoais']['outNomeMae'];
        }
        $ins['id_pessoa'] = $id_pessoa;
        if (!empty($dados['outDadosPessoais']['outNomeMae']) || !empty($dados['outDadosPessoais']['outNomePai'])) {
            $this->db->ireplace('pessoa', $ins);
        } else {
            toolErp::alertModal('Nada foi alterado');
        }
    }

    public function alunoAceiteSemTurma($id_turma=null) {
        $sql = self::getSQLTermoTurma($id_turma);
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (empty($array)) {
            $array = [];
        }
        return $array;
    }

    public function alunoRecusaComTurma($id_turma=null) {
        $sql = self::getSQLTermoTurma($id_turma,1);
        //echo $sql;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (empty($array)) {
            $array = [];
        }
        return $array;
    }

    public static function getSQLTermoTurma($id_turma=null,$recusa=null)
    {
        $sqlAux = '';
        if (!empty($id_turma)) {
            $sqlAux .= " AND ta.fk_id_turma = $id_turma ";
        }

        if (!empty($recusa)) {
            $tipo = "'R'";
            $matriculadoAEE = ' AND ta.id_turma_aluno IS NOT NULL';
        }else{
            $tipo = "'A'";
            $matriculadoAEE = ' AND ta.id_turma_aluno IS NULL';
        }

        $sql = "SELECT p.id_pessoa, p.n_pessoa, p.id_pessoa AS RSE, IFNULL(t.n_turma, t2.n_turma) AS n_turma "
            . " FROM protocolo_cadastro pc "
            . " JOIN protocolo_termo pt ON pc.id_protocolo = pt.fk_id_protocolo AND pt.tipo = $tipo AND pt.ativo = 1 "
            . " LEFT JOIN ge_turma_aluno ta ON ta.id_turma_aluno = ( SELECT MAX(tax.id_turma_aluno) "
                . " FROM ge_turma_aluno tax "
                . " JOIN ge_turmas tx ON tx.id_turma = tax.fk_id_turma AND tx.fk_id_ciclo = 32"
                . " JOIN ge_periodo_letivo plx ON plx.id_pl = tx.fk_id_pl AND plx.at_pl = 1"
                . " WHERE tax.fk_id_pessoa = pc.fk_id_pessoa AND (tax.fk_id_tas = 0 OR tax.fk_id_tas IS NULL) {$sqlAux} )"
            . " JOIN pessoa p ON p.id_pessoa = pc.fk_id_pessoa "
            . " LEFT JOIN ge_turmas t ON t.id_turma = pc.fk_id_turma_AEE "
            . " LEFT JOIN ge_turmas t2 ON t2.id_turma = ta.fk_id_turma "
            . " WHERE IFNULL(pc.fk_id_inst_AEE, pc.fk_id_inst) = ". toolErp::id_inst()
            . $matriculadoAEE;

        return $sql;
    }

    public function integracaoAlunos($inCodColegio = null, $inNomeAluno = null, $inRa = null) 
    {
        try {
            $agora = new DateTime();

            echo '<pre>integracao</pre>';
            die('stop');
            error_log( $agora->format("Y-m-d H:i:s") . " - integracao\n", 3, "/var/www/html/log-sp.log" );
            $integracao = new integracao();
            if (empty($integracao) || !isset($integracao->dadosCLI)) {
                throw new Exception("Nenhuma resposta da integracao de alunos");
            }

            echo '<pre>dados</pre>';
            die('stop');
            error_log( $agora->format("Y-m-d H:i:s") . " - dados\n", 3, "/var/www/html/log-sp.log" );
            if (isset($integracao->dadosCLI['ret']) && empty($integracao->dadosCLI['ret']['status'])) {
                throw new Exception($integracao->dadosCLI['ret']['message']);
            }

            echo '<pre>escolas</pre>';
            error_log( $agora->format("Y-m-d H:i:s") . " - escolas\n", 3, "/var/www/html/log-sp.log" );
            
            $run = $integracao->escolas();
            if (empty($run['status'])) {
                throw new Exception($run['message'], $run['code']);
            }

            echo '<pre>turmas</pre>';
            error_log( $agora->format("Y-m-d H:i:s") . " - turmas\n", 3, "/var/www/html/log-sp.log" );
            
            $run = $integracao->turmas($inCodColegio, true);
            if (empty($run['status'])) {
                throw new Exception($run['message'], $run['code']);
            }

            echo '<pre>alunos</pre>';
            error_log( $agora->format("Y-m-d H:i:s") . " - alunos\n", 3, "/var/www/html/log-sp.log" );
            
            $run = $integracao->alunos($inCodColegio, $inNomeAluno, $inRa);
            if (empty($run['status'])) {
                throw new Exception($run['message'], $run['code']);
            }

            $r = [
                'status' => true,
                'message' => 'Sucesso',
                'code' => 0,
            ];

        } catch (Exception $e) {
            $r = [
                'status' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        }

        echo '<pre>';
        var_dump($r);
    }
}
