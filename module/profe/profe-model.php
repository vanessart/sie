<?php

class profeModel extends MainModel {

    public $db;
    private $pesoAvaliacao;

    public function getPesoAvaliacao() {
        return $this->pesoAvaliacao;
    }

    /**
     * @param integer $v peso da avaliação
     */
    public function setPesoAvaliacao($v) {
        $this->pesoAvaliacao = $v;
    }

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
        if ($opt = form::jqOption()) {
            $metodo = $opt[0];
            if (in_array($metodo, get_class_methods($this))) {
                $this->$metodo($opt[1]);
            }
        }

        if ($this->db->tokenCheck('salvarFrequencia')) {
            $this->salvarFrequencia();
        } elseif ($this->db->tokenCheck('salvaDiario')) {
            $this->salvaDiario();
        } elseif ($this->db->tokenCheck('cadastraInstrumentoAvaliativo')) {
            $this->cadastraInstrumentoAvaliativo();
        } elseif ($this->db->tokenCheck('cadastroInstrumentoNota')) {
            $this->cadastroInstrumentoNota();
        } elseif ($this->db->tokenCheck('projFotoSalvar', true)) {
            $this->projFotoSalvar();
        } elseif ($this->db->tokenCheck('profe_projeto_regSalva')) {
            $this->profe_projeto_regSalva();
        } elseif ($this->db->tokenCheck('profe_projeto_cadSalva')) {
            $this->profe_projeto_cadSalva();
        } elseif ($this->db->tokenCheck('justificaFaltas')) {
            $this->justificaFaltas();
        } elseif ($this->db->tokenCheck('justificaFaltasDel')) {
            $this->justificaFaltasDel();
        }
    }

    public function justificaFaltasDel() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $atualLetiva = filter_input(INPUT_POST, 'atualLetiva', FILTER_SANITIZE_NUMBER_INT);
        $id_disc = filter_input(INPUT_POST, 'id_disc');
        $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
        $faltasTotal = filter_input(INPUT_POST, 'faltasTotal', FILTER_SANITIZE_NUMBER_INT);
        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        $id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
        $justifica = filter_input(INPUT_POST, 'justifica');
        $id_fj = filter_input(INPUT_POST, 'id_fj', FILTER_SANITIZE_NUMBER_INT);
        $faltas = filter_input(INPUT_POST, 'faltas', FILTER_SANITIZE_NUMBER_INT);

        $faltaAtualizada = $faltasTotal + $faltas;
        $table = 'hab.aval_mf_' . $id_curso . '_' . $id_pl;
        $coluna = 'falta_' . $id_disc;

        $sql = " update $table set "
                . " $coluna = '$faltaAtualizada' "
                . " where fk_id_pessoa = '$id_pessoa' "
                . " and atual_letiva = '$atualLetiva' ";
        $query = pdoSis::getInstance()->query($sql);

        $sql = "DELETE FROM profe_falta_just WHERE `profe_falta_just`.`id_fj` = $id_fj";
        $query = pdoSis::getInstance()->query($sql);
        toolErp::alert("Removido");
    }

    public function justificaFaltas() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $atualLetiva = filter_input(INPUT_POST, 'atualLetiva', FILTER_SANITIZE_NUMBER_INT);
        $id_disc = filter_input(INPUT_POST, 'id_disc');
        $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
        $faltasTotal = filter_input(INPUT_POST, 'faltasTotal', FILTER_SANITIZE_NUMBER_INT);
        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        $id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
        $justifica = filter_input(INPUT_POST, 'justifica');
        $faltasJust = filter_input(INPUT_POST, 'faltasJust', FILTER_SANITIZE_NUMBER_INT);
        if (empty($justifica) || empty($faltasJust)) {
            toolErp::alertModal('O lançamento não foi salvo.<br>Todos os campos são obrigatórios.');
            return;
        } elseif ($faltasJust > $faltasTotal) {
            toolErp::alertModal('O total de faltas justificadas não podem ser maior que a quantidade de faltas');
            return;
        }
        $faltaAtualizada = $faltasTotal - $faltasJust;
        $table = 'hab.aval_mf_' . $id_curso . '_' . $id_pl;
        $coluna = 'falta_' . $id_disc;

        $sql = " update $table set "
                . " $coluna = $faltaAtualizada "
                . " where fk_id_pessoa = $id_pessoa "
                . " and atual_letiva = $atualLetiva ";
        $query = pdoSis::getInstance()->query($sql);
        $ins['fk_id_pessoa'] = $id_pessoa;
        $ins['fk_di_curso'] = $id_curso;
        $ins['fk_id_pl'] = $id_pl;
        $ins['iddisc'] = $id_disc;
        $ins['atual_letiva'] = $atualLetiva;
        $ins['motivo'] = $justifica;
        $ins['faltas'] = $faltasJust;
        $ins['fk_id_pessoa_lanc'] = toolErp::id_pessoa();
        $id = $this->db->insert('profe_falta_just', $ins);
        if ($id) {
            if (!empty($_FILES['arquivo']['name'])) {
                $file = ABSPATH . '/pub/justificaFaltas/';
                $up = new upload($file, $id_pessoa, 15000000, ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG', 'PDF', 'pdf', 'docx']);
                $end = $up->up();
                if ($end) {
                    $insUp['upload'] = $end;
                    $insUp['id_fj'] = $id;
                    $this->db->ireplace('profe_falta_just', $insUp, 1);
                } else {
                    toolErp::alertModal('Erro ao enviar. Tente novamente');
                }
            }
        }
    }

    public static function getAtend($id_pdi, $atualLetiva) {
        $atend = '';
        if (!empty($id_pdi)) {
            $sql = "SELECT"
                    . " id_atend,acao,dt_atend"
                    . " FROM apd_pdi_atend"
                    . " WHERE fk_id_pdi = $id_pdi AND atualLetiva = $atualLetiva";
            $query = pdoSis::getInstance()->query($sql);
            $atend = $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return $atend;
    }

    public function getHabil() {

        $sql = " SELECT  "
                . " h.id_hab, h.codigo, h.descricao, h.fk_id_disc, ci.fk_id_ciclo, ci.atual_letiva "
                . " FROM coord_hab h "
                . " JOIN coord_hab_ciclo ci on ci.fk_id_hab = h.id_hab and h.fk_id_gh = (SELECT `fk_id_gh` FROM `coord_set_grupo_curso` WHERE `fk_id_cur` = 1 ) "
                . " ORDER BY h.codigo ";
                
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {

            $h['a'][$v['id_hab']] = $v['codigo'] . ' - ' . $v['descricao'];
        }
        $h['p'] = [];

        return $h;
    }

    public function profeSala($id_pessoa) {
        $sql = "SELECT DISTINCT prof.rm, p.n_pessoa "
                . " FROM ge_turma_aluno ta "
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN ge_aloca_prof prof on prof.fk_id_turma = t.id_turma "
                . " join ge_funcionario f on f.rm = prof.rm "
                . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " WHERE ta.fk_id_pessoa = $id_pessoa;";
        $query = pdoSis::getInstance()->query($sql);
        $nc = $query->fetchAll(PDO::FETCH_ASSOC);
        $prof_turma = "";
        $vir = "";
        foreach ($nc as $k => $v) {
            $prof_turma = $prof_turma . " " . $vir . $v['n_pessoa'] . ' (' . $v['rm'] . ')';
            $vir = ",";
        }

        return $prof_turma;
    }

    public function profe_projeto_regSalva() {
        $ins = @$_POST[1];
        $id = $this->db->ireplace('profe_projeto_reg', $ins);
        $hab = @$_POST['hab'];
        $sql = "DELETE FROM `profe_projeto_reg_hab` WHERE `fk_id_reg` = $id";
        $query = pdoSis::getInstance()->query($sql);
        if ($hab) {
            $insHab['fk_id_reg'] = $id;
            foreach ($hab as $v) {
                $insHab['fk_id_hab'] = $v;
                $this->db->ireplace('profe_projeto_reg_hab', $insHab, 1);
            }
        }
    }

    public function projFotoSalvar() {

        $id_projeto = filter_input(INPUT_POST, 'fk_id_projeto', FILTER_SANITIZE_NUMBER_INT);
        $temImagem = filter_input(INPUT_POST, 'temImagem', FILTER_SANITIZE_NUMBER_INT);
        $ins = @$_POST[1];
        $id_pf = $ins['id_pf'];

        if (empty($temImagem)) {
            if (!empty($_FILES['arquivo']['name'])) {
                @$exten = end(explode('.', $_FILES['arquivo']['name']));
                if (!in_array($exten, ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'])) {
                    toolErp::alertModal('Só é permitido anexar com extensões jpg, jpeg e png ');
                    return;
                }
                $nome_origin = $_FILES['arquivo']['name'];
                $file = ABSPATH . '/pub/infantilProj/';
                $up = new upload($file, $id_projeto, 15000000, ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG']);
                $end = $up->up();
                if ($end) {
                    $ins['link'] = $end;
                    $ins['id_pf'] = !empty($id_pf) ? $id_pf : null;
                    $ins['nome_origin'] = toolErp::escapaAspa($nome_origin);
                    $this->db->ireplace('profe_projeto_foto', $ins);
                } else {
                    toolErp::alertModal('Erro ao enviar. Tente novamente');
                }
            } elseif (!empty($ins['link_video'])) {
                $this->db->ireplace('profe_projeto_foto', $ins);
            } else {
                toolErp::alert("Não foi possível salvar. Inclua uma foto ou um vídeo!");
            }
        } else {
            $this->db->ireplace('profe_projeto_foto', $ins);
        }
    }

    public function salvaDiario() {
        $criterion = @$_POST[1];
        $set = [
            'id_turma' => $criterion['id_turma'],
            'id_disc' => $criterion['id_disc'],
            'data' => $criterion['data'],
        ];
        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        $mongo = new mongoCrude('Diario');
        $mongo->update('Aula.' . $id_pl, $set, $criterion, null, 1);
    }

    public function indexDBTurma($id_pessoa) {
        $sql = "SELECT "
                . "t.id_turma, t.n_turma, a.iddisc, "
                . " IF(a.iddisc='NC', 'Núcleo Comum', d.n_disc) n_disc, IF(a.iddisc='NC', 'NC', d.sg_disc) sg_disc, "
                . " i.id_inst, i.n_inst, c.id_curso, ci.id_ciclo, t.fk_id_pl, "
                . " c.un_letiva, c.qt_letiva, c.atual_letiva, c.sg_letiva "
                . " FROM ge_aloca_prof a "
                . " JOIN ge_turmas t on t.id_turma = a.fk_id_turma "
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " JOIN ge_cursos c on c.id_curso = ci.fk_id_curso "
                . " JOIN ge_funcionario f on f.rm = a.rm "
                . " JOIN instancia i on i.id_inst = t.fk_id_inst "
                . " LEFT JOIN ge_disciplinas d on d.id_disc = a.iddisc "
                . " WHERE f.fk_id_pessoa = $id_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $tur[$v['id_turma'] . '_' . $v['iddisc']] = $v;
            $id_turma = $v['id_turma'];
            $iddsc = $v['iddisc'];
            $sql = "SELECT * FROM `ge_horario` WHERE `fk_id_turma` = $id_turma and iddisc = '$iddsc'";
            $query = pdoSis::getInstance()->query($sql);
            $array_ = $query->fetchAll(PDO::FETCH_ASSOC);
            $aula = [];
            foreach ($array_ as $h) {
                $aula[$h['dia_semana']][$h['aula']] = $h['aula'];
            }
            if (empty(current($aula))) {
                $aula = false;
            }
            $tur[$v['id_turma'] . '_' . $v['iddisc']]['aulasDia'] = $aula;
            $tur[$v['id_turma'] . '_' . $v['iddisc']]['id_t_d'] = $v['id_turma'] . '_' . $v['iddisc'];
        }

        return $tur;
    }

    public function indexDBAlunos($id_turma) {
        $sql = "SELECT "
                . " ta.id_turma_aluno, ta.chamada, ta.situacao, p.id_pessoa, p.n_pessoa, p.sexo  "
                . " FROM ge_turma_aluno ta "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " WHERE ta.fk_id_turma = $id_turma "
                . " order by ta.chamada";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function indexDBAlunosAEE($id_pessoa) {

        $sql = "SELECT id_pessoa, n_inst, n_pessoa, dt_nasc, fk_id_grade,id_turma, n_turma, n_porte, id_porte, GROUP_CONCAT(n_ne ORDER BY n_ne SEPARATOR ', ') AS n_ne "
                . " FROM ("
                . "SELECT DISTINCT "
                . " p.id_pessoa, p.n_pessoa, p.dt_nasc, t.fk_id_grade,t.id_turma, t.n_turma, po.n_ne, porte.n_porte, porte.id_porte, i.n_inst FROM ge_funcionario f   "
                . " JOIN ge_aloca_prof ap on ap.rm = f.rm  "
                . " JOIN ge_turma_aluno ta on ta.fk_id_turma = ap.fk_id_turma AND (ta.fk_id_tas = 0 or ta.fk_id_tas is null)"
                . " join ge_turmas t on t.id_turma = ta.fk_id_turma and fk_id_ciclo != 32 "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN ge_aluno_necessidades_especiais apd on apd.fk_id_pessoa = ta.fk_id_pessoa AND fk_id_porte <> 1"
                . " JOIN ge_aluno_necessidades_especiais_porte porte on apd.fk_id_porte = porte.id_porte"
                . " JOIN pessoa p on p.id_pessoa = apd.fk_id_pessoa "
                . " JOIN instancia i on i.id_inst = t.fk_id_inst "
                . " JOIN ge_necessidades_especiais po on apd.fk_id_ne = po.id_ne "
                . " WHERE f.fk_id_pessoa = $id_pessoa"
                . ") AS tab1"
                . " GROUP BY  n_inst,id_pessoa, n_pessoa, dt_nasc, fk_id_grade, id_turma, n_turma, n_porte, id_porte ";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function getComponentesAEE($id_aluno_adaptacao) {
        $sql = " SELECT "
                . " sit_didatica,recurso,id_componente, n_componente, unidade, objeto, habilidade, ad.fk_id_pessoa, n_nota FROM "
                . " apd_componente cp "
                . " left join apd_aluno_adaptacao ad ON ad.id_aluno_adaptacao = cp.fk_id_aluno_adaptacao   "
                . " left JOIN apd_nota ON apd_nota.id_nota = cp.fk_id_nota "
                . " WHERE fk_id_aluno_adaptacao =  $id_aluno_adaptacao";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function getUnidadeLetiva($id_pessoa) {
        $sql = " SELECT "
                . " c.un_letiva, c.qt_letiva, c.atual_letiva, ta.id_turma_aluno from "
                . " ge_turma_aluno ta "
                . " join ge_turmas t on t.id_turma = ta.fk_id_turma and t.fk_id_ciclo !=32"
                . " JOIN ge_periodo_letivo plr ON plr.id_pl = t.fk_id_pl AND plr.at_pl = 1"
                . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " join ge_cursos c on c.id_curso = ci.fk_id_curso "
                . " WHERE ta.fk_id_pessoa = $id_pessoa AND (ta.fk_id_tas = 0 or ta.fk_id_tas is null) AND ta.situacao LIKE 'Frequente'";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $sql = " SELECT "
                . " qt_letiva from apd_aluno_adaptacao "
                . " WHERE fk_id_pessoa = $id_pessoa "
                . " ORDER BY qt_letiva";
        $query = pdoSis::getInstance()->query($sql);
        $letiva_aluno = $query->fetchAll(PDO::FETCH_ASSOC);

        $letiva = array();
        for ($i = 0; $i < $array[0]['qt_letiva']; ++$i) {
            $unidade = $i + 1;
            $find = false;

            foreach ($letiva_aluno as $k => $v) {
                if ($v['qt_letiva'] == $unidade) {
                    $find = true;
                    break;
                }
            }

            if (!$find) {
                $letiva[$i]['un_letiva'] = $array[0]['un_letiva'];
                $letiva[$i]['id_turma_aluno'] = $array[0]['id_turma_aluno'];
                $letiva[$i]['qt_letiva'] = $unidade;
            }
        }

        return $letiva;
    }

    public function userSet($id_pessoa) {
        $sql = "SELECT `n_pessoa`, `cpf`, `sexo`, `emailgoogle` FROM `pessoa` WHERE `id_pessoa` = $id_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    public function aulasPorDia($dia, $iddisc, $idturma) {
        $sql = "SELECT aula FROM ge_horario WHERE fk_id_turma = $idturma and
        iddisc like '$iddisc' and dia_semana = $dia ORDER BY aula";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            $newArray = array_column($array, 'aula');
            return $newArray;
        } else {
            return [];
        }
    }

    public function salvarFrequencia() {
        $valor['atual_letiva'] = filter_input(INPUT_POST, 'atual_letiva', FILTER_SANITIZE_STRING);
        $valor['data'] = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING);
        $valor['id_turma'] = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_STRING);
        $valor['id_disc'] = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_STRING);
        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        $valor['time_stamp'] = date("Y-m-d H:i:s");
        $valor['id_pessoa'] = toolErp::id_pessoa();

        $where = [
            'data' => $valor['data'],
            'id_turma' => $valor['id_turma'],
            'id_disc' => $valor['id_disc'],
        ];

        $frequencia = $_POST['frequencia'];

        if ($frequencia) {
            foreach ($frequencia as $k => $v) {
                if ($v) {
                    foreach ($v as $ky => $y) {
                        if ($y) {
                            $y = strtoupper($y);
                            if ($y == "R") {
                                $valor['remoto'][$ky] = $ky;
                                $y = "P";
                            }
                        }
                    }
                    $where['aula'] = $k;
                    $valor['aula'] = $k;
                    $valor['frequencia'] = $v;
                    $mongo = new mongoCrude('Diario');
                    $mongo->update('presence_' . $id_pl, $where, $valor);
                    toolErp::alertModal('Lançado');
                }
            }
        }
    }

    public function turmaDisciplina($id_pessoa) {
        $disciplinasPorClasse = professores::classesDisc($id_pessoa, 1); // 1 = nao mostra turmas AEE
        if ($disciplinasPorClasse) {
            foreach ($disciplinasPorClasse as $key => $value) {
                if (!empty($value['nucleoComum'])) {
                    foreach ($value['nucleoComum'] as $idTurma => $v) {
                        $idDisc = 'nc';
                        $disciplinasPorClasse[$idTurma . '_' . $idDisc]['id_inst'] = $key;
                        $disciplinasPorClasse[$idTurma . '_' . $idDisc]['escola'] = $value['escola'];
                        $disciplinasPorClasse[$idTurma . '_' . $idDisc]['id_turma'] = $idTurma;
                        $disciplinasPorClasse[$idTurma . '_' . $idDisc]['id_disc'] = 'nc';
                        $disciplinasPorClasse[$idTurma . '_' . $idDisc]['nome_disc'] = 'Polivalente';
                        $disciplinasPorClasse[$idTurma . '_' . $idDisc]['nome_turma'] = $value['turmas'][$idTurma];
                        $disciplinasPorClasse[$idTurma . '_' . $idDisc]['id_pl'] = $value['id_pl'][$idTurma];
                        $disciplinasPorClasse[$idTurma . '_' . $idDisc]['id_curso'] = $value['id_curso'][$idTurma];
                        $disciplinasPorClasse[$idTurma . '_' . $idDisc]['id_ciclo'] = $value['id_ciclo'][$idTurma];
                        $disciplinasPorClasse[$idTurma . '_' . $idDisc]['aulas'] = $value['aulas'][$idTurma];
                    }
                }
                if (!empty($value['disciplinas'])) {
                    foreach ($value['disciplinas'] as $idTurma => $v) {
                        foreach ($v as $idDisc => $nomeDisc) {
                            $disciplinasPorClasse[$idTurma . '_' . $idDisc]['id_inst'] = $key;
                            $disciplinasPorClasse[$idTurma . '_' . $idDisc]['escola'] = $value['escola'];
                            $disciplinasPorClasse[$idTurma . '_' . $idDisc]['id_turma'] = $idTurma;
                            $disciplinasPorClasse[$idTurma . '_' . $idDisc]['id_disc'] = $idDisc;
                            $disciplinasPorClasse[$idTurma . '_' . $idDisc]['nome_disc'] = $nomeDisc;
                            $disciplinasPorClasse[$idTurma . '_' . $idDisc]['nome_turma'] = $value['turmas'][$idTurma];
                            $disciplinasPorClasse[$idTurma . '_' . $idDisc]['id_pl'] = $value['id_pl'][$idTurma];
                            $disciplinasPorClasse[$idTurma . '_' . $idDisc]['id_curso'] = $value['id_curso'][$idTurma];
                            $disciplinasPorClasse[$idTurma . '_' . $idDisc]['id_ciclo'] = $value['id_ciclo'][$idTurma];
                            $disciplinasPorClasse[$idTurma . '_' . $idDisc]['aulas'] = $value['aulas'][$idTurma];
                        }
                    }
                }
            }
        } else {
            return;
        }
        return $disciplinasPorClasse;
    }

    public function chamadaControl($id_pl, $id_turma, $id_disc = null) {
        $date = new DateTime('-15 day');
        $dataInicio = $date->format('Y-m-d');
        $alunos = ng_escola::alunoPorTurma($id_turma);
        $mongo = new mongoCrude('Diario');
        if ($id_disc) {
            $filter = [
                'id_turma' => $id_turma,
                'id_disc' => $id_disc
            ];
        } else {
            $filter = [
                'id_turma' => $id_turma,
                'data' => [
                    '$gt' => $dataInicio
                ]
            ];
        }
        $dados = $mongo->query('presence_' . $id_pl, $filter);
        foreach ($dados as $v) {
            $ano = substr($v->data, 0, 4);
            $aulasDadas[substr($v->data, 5, 2)][substr($v->data, 8)] = substr($v->data, 8);
            foreach ($v->frequencia as $id_pessoa => $fp) {
                if ($fp == 'F') {
                    $falta[$id_pessoa][substr($v->data, 5, 2)][substr($v->data, 8)] = substr($v->data, 8);
                }
            }
        }
        if (!empty($alunos)) {
            foreach ($alunos as $v) {
                $ft[$v['id_pessoa']]['dados']['n_pessoa'] = $v['n_pessoa'];
                $ft[$v['id_pessoa']]['dados']['id_pessoa'] = $v['id_pessoa'];
                $ft[$v['id_pessoa']]['dados']['situacao'] = $v['situacao'];
                $ft[$v['id_pessoa']]['dados']['sexo'] = $v['sexo'];
                if (!empty($aulasDadas)) {

                    foreach ($aulasDadas as $mes => $dias) {
                        $contAula = 1;
                        $contseq = 1;
                        ksort($dias);
                        $contFaltaMes = 0;
                        $contAulaOld = 0;
                        $iniciado = 0;
                        foreach ($dias as $d) {
                            $ft[$v['id_pessoa']]['aulasDadas'][$mes] = $dias;
                            if (!empty($falta[$v['id_pessoa']][$mes][$d])) {
                                if (!empty($contAulaOld)) {
                                    if ($contAulaOld == ($contAula - 1)) {
                                        if (empty($iniciado)) {
                                            @$ft[$v['id_pessoa']]['sequencia'][$mes][$contseq]++;
                                        }
                                        $iniciado = 1;
                                        @$ft[$v['id_pessoa']]['sequencia'][$mes][$contseq]++;
                                    } elseif ($iniciado) {
                                        $iniciado = null;
                                        $contseq++;
                                    }
                                }
                                $contAulaOld = $contAula;
                                $ft[$v['id_pessoa']]['faltas'][$mes][$d] = $d;
                                @$ft[$v['id_pessoa']]['faltaDiaSem'][$mes][date('w', strtotime($ano . '-' . $mes . '-' . $d))]++;
                                $contFaltaMes++;
                            }
                            $contAula++;
                        }
                        $ft[$v['id_pessoa']]['faltasMes'][$mes] = $contFaltaMes;
                    }
                }
            }
        }
        if (!empty($ft)) {
            return $ft;
        }
    }

    public function dadosChamada($id_pl, $id_turma, $id_disc, $data) {
        $mongo = new mongoCrude('Diario');
        $dados = $mongo->query('presence_' . $id_pl, ['id_turma' => $id_turma, 'id_disc' => $id_disc, 'data' => $data]);

        if (!empty($dados)) {
            foreach ($dados as $v) {
                $freq[$v->aula] = (array) $v->frequencia;
            }
            if (!empty($freq)) {
                return $freq;
            } else {
                return;
            }
        } else {
            return;
        }
    }

    public function calendarioJS() {
        ?>
        <script language="JavaScript">
            function Calendario() {
                document.getElementById('form_geral').submit()
            }
        </script>
        <?php

    }

    public function dataFiltro($data = null, $id_curso = null, $id_pl = null, $atual_letiva = null) {
        if (!$data) {
            $data = date('Y-m-d');
        } elseif ($data > date('Y-m-d')) {
            $lacFuturo = sql::get('ge_setup', 'lanc_diario_futuro', null, 'fetch')['lanc_diario_futuro'];
            if ($lacFuturo == 0) {
                toolErp::alertModal('Não é permitido datas futuras');
                $data = date('Y-m-d');
            }
        }
        if (empty($atual_letiva)) {
            $sql = "SELECT "
                    . " c.notas, c.corte, c.un_letiva, c.atual_letiva, ld.dt_inicio, ld.dt_fim "
                    . " FROM ge_cursos c "
                    . " JOIN sed_letiva_data ld on ld.fk_id_curso = c.id_curso AND c.atual_letiva = ld.atual_letiva "
                    . " WHERE c.id_curso = $id_curso "
                    . " AND ld.fk_id_pl = $id_pl";
        } else {
            $sql = "SELECT "
                    . " c.notas, c.corte, c.un_letiva, ld.atual_letiva, ld.dt_inicio, ld.dt_fim "
                    . " FROM ge_cursos c "
                    . " JOIN sed_letiva_data ld on ld.fk_id_curso = c.id_curso AND ld.atual_letiva = $atual_letiva "
                    . " WHERE c.id_curso = $id_curso "
                    . " AND ld.fk_id_pl = $id_pl";
        }
        $query = pdoSis::getInstance()->query($sql);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($result['dt_inicio']) && !empty($result['dt_fim'])) {
            if ($result['dt_inicio'] > $data || $result['dt_fim'] < $data) {
                $result['erro'] = "Data fora do " . $result['atual_letiva'] . "º " . $result['un_letiva'] . " que vai de " . data::porExtenso($result['dt_inicio']) . " a " . data::porExtenso($result['dt_fim']);
            } else {
                @$feriado = sql::get('sed_feriados', 'n_feriado', ['fk_id_curso' => $id_curso, 'fk_id_pl' => $id_pl, 'dt_feriado' => $data], 'fetch')['n_feriado'];
                if ($feriado) {
                    $result['erro'] = "Dia não letivo - " . $feriado;
                }
            }
        }
        $result['data'] = $data;
        return $result;
    }

    public function grade($id_grade) {
        $sql = "select * from ge_disciplinas d "
                . " join ge_aloca_disc ad on ad.fk_id_disc = d.id_disc "
                . " where ad.fk_id_grade =  " . $id_grade;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            if ($v['nucleo_comum'] == 1) {
                $disc['nc'] = 'Núcleo Comum';
            } else {
                $disc[$v['id_disc']] = $v['n_disc'];
            }
        }
        if (!empty($disc)) {
            return $disc;
        }
    }

    public function aulaDia($id_pessoa, $diaSem) {
        $sql = "SELECT h.* FROM ge_aloca_prof ap "
                . " JOIN ge_horario h on h.fk_id_turma = ap.fk_id_turma AND ap.iddisc = h.iddisc "
                . " JOIN ge_funcionario f on f.rm = ap.rm "
                . " WHERE f.fk_id_pessoa = $id_pessoa "
                . " AND h.dia_semana = $diaSem "
                . " ORDER BY h.fk_id_turma, h.aula ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $v) {
                $aula[$v['aula']] = $v['fk_id_turma'] . '_' . $v['iddisc'];
            }
            ksort($aula);
            return $aula;
        } else {
            return;
        }
    }

    public function planoHabil($id_turma, $data) {
        $sql = "SELECT h.fk_id_hab FROM coord_plano_aula p "
                . " JOIN coord_plano_aula_hab h on p.id_plano = h.fk_id_plano "
                . " JOIN coord_plano_aula_turmas t on t.fk_id_plano = p.id_plano "
                . " WHERE t.fk_id_turma = $id_turma "
                . " AND p.dt_inicio <= '$data' "
                . " AND p.dt_fim >= '$data'";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            return array_column($array, 'fk_id_hab');
        } else {
            return[];
        }
    }

    public function adaptCurrHabil($id_hab) {
        $sql = " SELECT "
                . " hab.descricao, hab.codigo, disc.id_disc, disc.n_disc, hab.id_hab,"
                . " ut.n_ut, oc.n_oc, hab.metodologicas, hab.verific_aprendizagem "
                . " FROM coord_hab hab "
                . " left join coord_uni_tematica ut on ut.id_ut = hab.fk_id_ut "
                . " left join coord_objeto_conhecimento oc on oc.id_oc = hab.fk_id_oc "
                . " left join ge_disciplinas disc on disc.id_disc = hab.fk_id_disc "
                . " WHERE hab.id_hab = $id_hab"
                . " order by disc.n_disc";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function getHabilidades($id_ciclo, $id_disc) {
        $sql = "SELECT id_hab, n_hab, codigo FROM coord_hab_ciclo "
                . " JOIN coord_hab on coord_hab_ciclo.fk_id_hab = coord_hab.id_hab "
                . " join coord_grup_hab gh on gh.id_gh = coord_hab.fk_id_gh "
                . " WHERE coord_hab_ciclo.fk_id_ciclo = $id_ciclo "
                . " AND coord_hab.fk_id_disc = $id_disc"
                . " and gh.at_gh = 1 "
                . " AND at_hab = 1";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    /**
     * a => habilidades do ANO
     * b => habilidades do BIMESTRE
     * p => habilidades do PLANO DE AULA
     * @param type $id_ciclo
     * @param type $id_disc
     * @param type $atualLetiva
     * @param type $id_turma
     * @param type $data
     * @return array
     */
    public function retornaHabilidades($id_ciclo, $id_disc, $atualLetiva = null, $id_turma = null, $data = null) {
        if (!is_null($data)) {
            $habilPlano = $this->planoHabil($id_turma, $data);
        } else {
            $habilPlano = [];
        }
        if ($id_disc == 'nc') {
            $disc = array_flip(turmas::disciplinas($id_turma)['nucleComum']);
            $id_disc = implode(', ', $disc);
        }
        $sql = " SELECT h.id_hab, h.descricao, h.codigo, c.atual_letiva, disc.n_disc FROM coord_hab h "
                . " join coord_grup_hab gh on gh.id_gh = h.fk_id_gh "
                . " JOIN coord_hab_ciclo c on c.fk_id_hab = h.id_hab "
                . " left join ge_disciplinas disc on disc.id_disc = h.fk_id_disc "
                . " WHERE c.fk_id_ciclo =  " . $id_ciclo
                . " and fk_id_disc in ($id_disc)"
                . " and gh.at_gh = 1 "
                . " ORDER BY disc.n_disc, h.codigo ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $h['p'] = [];
        $h['b'] = [];
        $h['a'] = [];

        foreach ($array as $v) {
            if (in_array($v['id_hab'], $habilPlano)) {
                $h['p'][$v['id_hab']] = $v['codigo'] . ' (' . $v['n_disc'] . ') - ' . $v['descricao'];
            }
            if (strstr($v['atual_letiva'], chr($atualLetiva))) {
                $h['b'][$v['id_hab']] = $v['codigo'] . ' (' . $v['n_disc'] . ') - ' . $v['descricao'];
            }
            $h['a'][$v['id_hab']] = $v['codigo'] . ' (' . $v['n_disc'] . ') - ' . $v['descricao'];
        }

        return $h;
    }

    public function getHabilidadesProjeto($id_ciclo, $id_disc, $atualLetiva = null, $id_turma = null, $data = null, $id_curso = null) {
        if (!is_null($data)) {
            $habilPlano = $this->planoHabil($id_turma, $data);
        } else {
            $habilPlano = [];
        }
        if ($id_disc == 'nc') {
            $disc = array_flip(turmas::disciplinas($id_turma)['nucleComum']);
            $id_disc = implode(', ', $disc);
        }
        $join = '';
        if (!is_null($id_curso)) {
            $join = " JOIN coord_set_grupo_curso sgc ON h.fk_id_gh = sgc.fk_id_gh AND sgc.fk_id_cur = $id_curso";
        }
        $sql = " SELECT h.id_hab, h.descricao, h.codigo, c.atual_letiva, disc.n_disc FROM coord_hab h "
                . $join
                . " join coord_grup_hab gh on gh.id_gh = sgc.fk_id_gh "
                . " JOIN coord_hab_ciclo c on c.fk_id_hab = h.id_hab "
                . " left join ge_disciplinas disc on disc.id_disc = h.fk_id_disc "
                . " WHERE c.fk_id_ciclo =  " . $id_ciclo
                . " and fk_id_disc in ($id_disc)"
                . " and gh.at_gh = 1 "
                . " ORDER BY disc.n_disc, h.codigo ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $h['p'] = [];
        $h['b'] = [];
        $h['a'] = [];

        foreach ($array as $v) {
            if (in_array($v['id_hab'], $habilPlano)) {
                $h['p'][$v['id_hab']] = $v['codigo'] . ' (' . $v['n_disc'] . ') - ' . $v['descricao'];
            }
            if (strstr($v['atual_letiva'], chr($atualLetiva))) {
                $h['b'][$v['id_hab']] = $v['codigo'] . ' (' . $v['n_disc'] . ') - ' . $v['descricao'];
            }
            $h['a'][$v['id_hab']] = $v['codigo'] . ' (' . $v['n_disc'] . ') - ' . $v['descricao'];
        }

        return $h;
    }

    public function alertDanger($message) {
        return '<div class= "alert alert-danger" role="alert"> ' . $message . ' </div> ';
    }

    public function tiposAvaliacao() {
        $sql = "SELECT * FROM profe_tp_aval WHERE at_ta = 1";
        try {
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            return $array;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function cadastraInstrumentoAvaliativo() {
        if (empty($_POST['instrumentoNome'])) {
            toolErp::alertModal('Nome não encontrado');
            return;
        }

        $newArr = [
            'uniqid' => filter_input(INPUT_POST, 'uniqid', FILTER_SANITIZE_STRING),
            'id_pl' => filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_STRING),
            'instrumentoNome' => filter_input(INPUT_POST, 'instrumentoNome', FILTER_SANITIZE_STRING),
            'instrumentoTipo' => filter_input(INPUT_POST, 'instrumentoTipo', FILTER_SANITIZE_STRING),
            'dataAvaliacao' => filter_input(INPUT_POST, 'dataAvaliacao', FILTER_SANITIZE_STRING),
            'peso' => filter_input(INPUT_POST, 'peso', FILTER_VALIDATE_INT),
            'id_ciclo' => filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_STRING),
            'id_inst' => filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_STRING),
            'id_pessoa' => filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_STRING),
            'timeStamp' => filter_input(INPUT_POST, 'timeStamp', FILTER_SANITIZE_STRING),
            'id_turma' => filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_STRING),
            'atual_letiva' => filter_input(INPUT_POST, 'atual_letiva', FILTER_SANITIZE_STRING),
            'id_disc' => filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_STRING),
            'ativo' => filter_input(INPUT_POST, 'ativo', FILTER_VALIDATE_INT),
            'id_disc_nc' => filter_input(INPUT_POST, 'id_disc_nc', FILTER_VALIDATE_INT),
        ];

        $mongo = new mongoCrude('Diario');
        try {
            $mongo->update('instrumentos.' . $newArr['id_pl'], ['uniqid' => $newArr['uniqid']], $newArr, null, 1);
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function retornaInstrumentosAvaliativos(string $idPl, string $idTurma, string $atualLetiva, string $id_disc) {
        $mongo = new mongoCrude('Diario');
        $result = $mongo->query('instrumentos.' . $idPl, ['id_turma' => $idTurma, 'atual_letiva' => $atualLetiva, 'id_disc' => $id_disc]);
        return $result;
    }

    public function dataUnidadeLetiva($id_curso, $id_pl, $atual_letiva) {
        $sql = "SELECT dt_inicio, dt_fim FROM `sed_letiva_data` WHERE `fk_id_curso` = $id_curso AND `fk_id_pl` = $id_pl AND `atual_letiva` = $atual_letiva";
        $query = pdoSis::getInstance()->query($sql);
        $dt_letiva = $query->fetch(PDO::FETCH_ASSOC);
        return $dt_letiva;
    }

    public function cadastroInstrumentoNota() {

        if (!empty($_POST['nota'])) {
            $notas = $_POST['nota'];
            $erro = false;

            foreach ($notas as $k => $v) {
                $v = str_replace(',', '.', $v);
                if (!empty($v)) {
                    if (!is_numeric($v) || $v > 10) {
                        $notas[$k] = null;
                        $erro = true;
                    } else {
                        $notas[$k] = (float) $v;
                    }
                } else {
                    $notas[$k] = 0;
                }
            }
        } else {
            $notas = null;
        }

        if ($erro) {
            toolErp::alertModal('Você inseriu notas invalidas');
        }
        $newArr = [
            'uniqid' => filter_input(INPUT_POST, 'uniqid', FILTER_SANITIZE_STRING),
            'notas' => $notas,
            'id_pl' => filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_STRING),
        ];

        $mongo = new mongoCrude('Diario');
        try {
            $mongo->update('instrumentos.' . $newArr['id_pl'], ['uniqid' => $newArr['uniqid']], $newArr, null, 1);
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function letivas($id_curso, $id_pl) {
        $sql = "SELECT atual_letiva, dt_fim, dt_inicio FROM `sed_letiva_data` "
                . " WHERE `fk_id_curso` = $id_curso "
                . " AND `fk_id_pl` = $id_pl";
        $query = pdoSis::getInstance()->query($sql);
        $letivas = $query->fetchAll(PDO::FETCH_ASSOC);

        return $letivas;
    }

    public function turmas($id_inst) {
        $sql = "SELECT * FROM ge_turmas t JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl WHERE "
                . " pl.at_pl = 1 AND fk_id_inst = $id_inst "
                . " order by n_turma ";

        $query = pdoSis::getInstance()->query($sql);
        $turma = $query->fetchAll(PDO::FETCH_ASSOC);

        return $turma;
    }

    public function id_curso($id_turma) {
        $sql = "SELECT ci.fk_id_curso id_curso FROM ge_turmas t JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo WHERE t.id_turma = $id_turma;";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        if ($array['id_curso']) {
            return $array['id_curso'];
        }
    }

    public function aulaDadasNc($id_pl, $id_curso, $idTurma) {
        if ($id_pl && $id_curso) {
            $dt_dia_un_lt = sql::get('sed_letiva_data', '*', ['fk_id_curso' => $id_curso, 'fk_id_pl' => $id_pl]);
            foreach ($dt_dia_un_lt as $v) {
                $diasQt[$v['atual_letiva']] = $v['dias'];
            }
        }

        if ($idTurma) {
            $ad = sql::get('hab`.`aval_aula_dadas', 'aula_dadas, atual_letiva', ['fk_id_disc' => 'nc', 'fk_id_turma' => $idTurma]);
            foreach ($ad as $v) {
                $diasQt[$v['atual_letiva']] = $v['aula_dadas'];
            }
        }

        return @$diasQt;
    }

    public function aulaDadas($id_pl, $id_curso, $idTurma, $id_disc) {
        $sql = "SELECT ad.aulas FROM ge_turmas t "
                . " JOIN ge_aloca_disc ad on ad.fk_id_grade = t.fk_id_grade AND ad.fk_id_disc = $id_disc "
                . " WHERE id_turma = $idTurma;";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if ($array) {
            $aulasSemana = $array['aulas'];
        }
        if ($id_pl && $id_curso) {
            $dt_dia_un_lt = sql::get('sed_letiva_data', '*', ['fk_id_curso' => $id_curso, 'fk_id_pl' => $id_pl]);
            foreach ($dt_dia_un_lt as $v) {
                $diasQt[$v['atual_letiva']] = ceil(($v['dias'] / 5) * $aulasSemana);
            }
        }

        if ($idTurma) {
            $ad = sql::get('hab`.`aval_aula_dadas', 'aula_dadas, atual_letiva', ['fk_id_disc' => $id_disc, 'fk_id_turma' => $idTurma]);
            foreach ($ad as $v) {
                $diasQt[$v['atual_letiva']] = $v['aula_dadas'];
            }
        }

        return @$diasQt;
    }

    public function aulasDadasGeral($idTurma, $id_disc) {
        if ($idTurma) {
            $ad = sqlErp::get('hab.aval_aula_dadas', 'aula_dadas, atual_letiva', ['fk_id_disc' => $id_disc, 'fk_id_turma' => $idTurma]);
            foreach ($ad as $v) {
                $diasQt[$v['atual_letiva']] = $v['aula_dadas'];
            }
        }

        return @$diasQt;
    }

    public function chamadaDiario($id_pl, $id_turma, $id_disc) {
        $mongo = new mongoCrude('Diario');
        $result = $mongo->query('presence_' . $id_pl, ['id_turma' => $id_turma, 'id_disc' => $id_disc]);

        if ($result) {
            foreach ($result as $y) {
                if ($id_disc == 'nc') {
                    foreach ($y->frequencia as $k => $v) {
                        if (@$ch[$y->atual_letiva][substr($y->data, 5, 2)][substr($y->data, 8)][$k] != 'P') {
                            $ch[$y->atual_letiva][substr($y->data, 5, 2)][substr($y->data, 8)][$k] = $v;
                            $dt[$y->atual_letiva][substr($y->data, 5, 2)][substr($y->data, 8)] = substr($y->data, 8);
                        }
                    }
                } else {
                    foreach ($y->frequencia as $k => $v) {
                        $ch[$y->atual_letiva][substr($y->data, 5, 2)][substr($y->data, 8)][$k] = $v;
                        $dt[$y->atual_letiva][substr($y->data, 5, 2)][substr($y->data, 8)] = substr($y->data, 8);
                    }
                }
            }
        }
        if (!empty($ch)) {
            return ['ch' => $ch, 'dt' => $dt];
        }
    }

    public function chamadaPorAluno($id_pl, $idTurma, $id_disc) {
        $id_curso = $this->id_curso($idTurma);
        $diasQt = $this->aulasDadasGeral($idTurma, $id_disc);

        $mongo = new mongoCrude('Diario');
        $result = $mongo->query('presence_' . $id_pl, ['id_turma' => $idTurma, 'id_disc' => $id_disc]);
        if (in_array($id_disc, ['nc', 27])) {
            foreach ($result as $key => $value) {
                foreach ($value->frequencia as $k => $v) {
                    if ($v == 'P') {
                        @$fd[$value->atual_letiva][$k][$value->data]['P'] = 1;
                    } elseif (empty($fd[$value->atual_letiva][$k][$value->data]['P']) && $v == 'F') {
                        @$fd[$value->atual_letiva][$k][$value->data]['F'] = 1;
                    }
                    @$fd[$value->atual_letiva][$k][$value->data]['T'] = 1;
                }
            }
            if (!empty($fd)) {
                foreach ($fd as $ul => $v) {
                    foreach ($v as $idP => $y) {
                        foreach ($y as $x) {
                            foreach ($x as $fpt => $w) {
                                @$newResult[$ul][$idP][$fpt]++;
                                if (!empty($diasQt[$value->atual_letiva])) {
                                    @$newResult[$ul][$idP]['T'] = @$diasQt[$value->atual_letiva];
                                    @$newResult[$ul][$idP]['P'] = @$diasQt[$value->atual_letiva] - @$newResult[$ul][$idP]['F'];
                                }
                            }
                        }
                    }
                }
            }
        } else {
            foreach ($result as $key => $value) {
                @$total[$value->atual_letiva]++;
                foreach ($value->frequencia as $k => $v) {
                    @$newResult[$value->atual_letiva][$k][$v]++;
                    @$newResult[$value->atual_letiva][$k]['T'] = $total[$value->atual_letiva];
                    if (!empty($diasQt[$value->atual_letiva])) {
                        @$newResult[$value->atual_letiva][$k]['T'] = $diasQt[$value->atual_letiva];
                        @$newResult[$value->atual_letiva][$k]['P'] = $diasQt[$value->atual_letiva] - @$newResult[$value->atual_letiva][$k]['F'];
                    }
                }
            }
        }
        $final = ['aulas' => @$diasQt, 'faltaPresenca' => @$newResult];
        return $final;
    }

    public function letivaDados($id_turma) {
        $sql = "SELECT un_letiva, qt_letiva, atual_letiva FROM ge_ciclos g JOIN ge_cursos gc ON gc.id_curso = g.fk_id_curso " .
                "JOIN ge_turmas gt ON gt.fk_id_ciclo = g.id_ciclo WHERE gt.id_turma = " . $id_turma;

        $query = pdoSis::getInstance()->query($sql);
        $letivas = $query->fetch(PDO::FETCH_ASSOC);
        return $letivas;
    }

    public function habilidadesTrabalhadas($id_turma, $id_pl) {
        $mongo = new mongoCrude('Diario');
        $hab = $mongo->query('habilidades.' . $id_pl, ['id_turma' => $id_turma]);

        foreach ($hab as $key => $value) {
            @$newArr[$value->id_hab] = $value->id_hab;
        }
        return @$newArr;
    }

    public function autores($ids) {
        if ($ids) {
            $sql = "select n_pessoa from pessoa where id_pessoa in ($ids)";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);

            return @ucwords(strtolower(toolErp::virgulaE(array_column($array, 'n_pessoa'))));
        } else {
            return;
        }
    }

    public function checkAutores($ids) {

        if ($ids) {
            $sql = "select id_pessoa, n_pessoa from pessoa where id_pessoa in ($ids)";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);

            return $array;
        } else {
            return [];
        }
    }

    public function initials($n_pessoa) {

        //The strtoupper() function converts a string to uppercase.
        $name = strtoupper($n_pessoa);
        //prefixes that needs to be removed from the name
        $remove = ['.', 'DE', 'DA', 'DOS'];
        $nameWithoutPrefix = str_replace($remove, " ", $name);

        $words = explode(" ", $nameWithoutPrefix);

        //this will give you the first word of the $words array , which is the first name
        $firtsName = reset($words);

        //this will give you the last word of the $words array , which is the last name
        $lastName = end($words);

        $name = $firtsName . " " . $lastName;

        //echo substr($firtsName,0,1); // this will echo the first letter of your first name
        //echo substr($lastName ,0,1); // this will echo the first letter of your last name

        return $name;
    }

    public function getProjetoFotos($id_projeto) {
        $sql = "SELECT pf.link_video, pf.n_pf, pf.descr_pf, pf.link, pf.dt_pf, pf.fk_id_pessoa, p.n_pessoa"
                . " FROM ge2.profe_projeto_foto pf"
                . " LEFT JOIN pessoa p ON p.id_pessoa = pf.fk_id_pessoa"
                . " WHERE fk_id_projeto = " . $id_projeto
                . " ORDER BY dt_pf ";
        $query = pdoSis::getInstance()->query($sql);
        $fotos = $query->fetchAll(PDO::FETCH_ASSOC);

        return $fotos;
    }

    public function getProjetoAval($id_projeto) {
        $sql = "SELECT p.id_pessoa, p.n_pessoa, pa.situacao, pa.dt_ava AS dt_inicio, pa.dt_fim"
                . " FROM profe_projeto_ava pa"
                . " JOIN pessoa p ON p.id_pessoa = pa.fk_id_pessoa"
                . " WHERE fk_id_projeto =" . $id_projeto
                . " ORDER BY dt_inicio";
        $query = pdoSis::getInstance()->query($sql);
        $aval = $query->fetchAll(PDO::FETCH_ASSOC);

        return $aval;
    }

    public function getProjetoReg($id_projeto, $id_reg = null) {
        $WHERE = '';
        if (!empty($id_reg)) {
            $WHERE = $WHERE . " AND pr.id_reg = $id_reg ";
        }
        $sql = "SELECT cc.id_ce, cc.n_ce, pr.dt_fim, pr.dt_inicio, pr.fk_id_pessoa, pr.id_reg, pr.situacao, pr.dt_update, ch.codigo, ch.descricao"
                . " FROM profe_projeto_reg pr"
                . " Left JOIN profe_projeto_reg_hab prh ON prh.fk_id_reg = pr.id_reg"
                . " Left JOIN coord_hab ch ON ch.id_hab = prh.fk_id_hab"
                . " Left JOIN coord_campo_experiencia cc ON ch.fk_id_ce = cc.id_ce"
                . " WHERE fk_id_projeto =" . $id_projeto
                . $WHERE
                . " ORDER BY pr.dt_inicio, id_ce";
        $query = pdoSis::getInstance()->query($sql);
        $reg = $query->fetchAll(PDO::FETCH_ASSOC);

        $registro = [];

        foreach ($reg as $k => $v) {
            if (empty($registro[$v["id_reg"]])) {
                $registro[$v["id_reg"]] = $v;
            }

            if (empty($registro[$v["id_reg"]]["hab"])) {
                $registro[$v["id_reg"]]["hab"] = [];
            }

            $registro[$v["id_reg"]]["hab"][] = [
                "codigo" => $v["codigo"],
                "descricao" => $v["descricao"],
                "n_ce" => $v["n_ce"],
                "id_ce" => $v["id_ce"],
            ];
        }

        return $registro;
    }

    public function habilidades($id_ciclo = null, $id_disc = null, $fields = null) {
        if ($id_ciclo) {
            $id_ciclo = " coord_hab_ciclo.fk_id_ciclo = $id_ciclo ";
        }
        if ($id_disc) {
            $id_disc = " AND coord_hab.fk_id_disc = $id_disc ";
        }
        if (empty($fields)) {
            $fields = " h.id_hab, h.descricao, h.codigo, cii.n_ciclo, d.n_disc ";
        }
        $sql = "SELECT $fields FROM coord_hab h "
                . " JOIN coord_hab_ciclo ci on ci.fk_id_hab = h.id_hab "
                . " join coord_grup_hab gh on gh.id_gh = h.fk_id_gh "
                . " join ge_disciplinas d on d.id_disc = h.fk_id_disc "
                . " join ge_ciclos cii on cii.id_ciclo = ci.fk_id_ciclo "
                . " WHERE 1 "
                . $id_ciclo
                . $id_disc
                . " and fk_id_cur = 1 "
                . " and gh.at_gh = 1 "
                . " AND at_hab = 1";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function habPlan($id_ciclo = null, $id_disc = null, $id_inst = null) {
        
    }

    public function relatHabCont($id_pl, $id_turma, $id_disc, $id_curso) {
        $sql = "SELECT * FROM `sed_letiva_data` WHERE `fk_id_curso` = $id_curso AND `fk_id_pl` = $id_pl ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $bim[$v['atual_letiva']] = [
                'inicio' => $v['dt_inicio'],
                'fim' => $v['dt_fim']
            ];
        }
        if (empty($bim)) {
            return;
        }

        $mongo = new mongoCrude('Diario');
        $filter = [
            "id_turma" => $id_turma,
            "id_disc" => $id_disc
        ];
        $dados_ = $mongo->query('Aula.' . $id_pl, $filter);
        foreach ($dados_ as $v) {
            $dados[$v->data] = $v;
        }

        if (!empty($dados)) {
            ksort($dados);
            foreach ($dados as $v) {
                $bimestre = null;
                foreach ($bim as $kb => $b) {
                    if ($v->data >= $b['inicio'] && $v->data <= $b['fim']) {
                        $bimestre = $kb;
                        break;
                    }
                }
                if ($bimestre) {
                    $hc[$bimestre][$v->data] = [
                        'descritivo' => $v->descritivo,
                        'ocorrencia' => $v->ocorrencia
                    ];
                }
            }
        }
        $filter = [
            "id_turma" => $id_turma,
            "id_disc" => $id_disc
        ];
        $dados = $mongo->query('habilidades.' . $id_pl, $filter);
        foreach ($dados as $v) {
            $h[$v->id_hab] = $v->id_hab;
        }
        if (!empty($h)) {
            $sql = "SELECT id_hab, descricao, codigo FROM `coord_hab` WHERE `id_hab` IN (" . implode(', ', $h) . ") ";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($array as $v) {
                $habil[$v['id_hab']] = $v['codigo'] . ' - ' . $v['descricao'];
            }
        }
        foreach ($dados as $v) {
            $bimestre = null;
            foreach ($bim as $kb => $b) {
                if ($v->data >= $b['inicio'] && $v->data <= $b['fim']) {
                    $bimestre = $kb;
                    break;
                }
            }
            if ($bimestre && !empty($habil[$v->id_hab])) {
                $hc[$bimestre][$v->data]['habilidades'][$v->id_hab] = $habil[$v->id_hab];
            }
        }
        if (!empty($hc)) {
            return $hc;
        }
    }

    public function faltaCritInfantil($id_pl, $id_inst = null) {
        if (!empty($id_inst)) {
            $id_inst = " and t.fk_id_inst = $id_inst";
        }
        $sql = "SELECT "
                . " t.id_turma, t.n_turma, t.fk_id_inst, ci.fk_id_curso "
                . " FROM ge_turmas t "
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo AND ci.fk_id_curso in (3,7,8) $id_inst "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1 "
                . " order by t.n_turma ";
        $query = pdoSis::getInstance()->query($sql);
        $t = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($t as $v) {
            $curso[$v['id_turma']] = $v['fk_id_curso'];
            $inst[$v['id_turma']] = $v['fk_id_inst'];
            $n_turma[$v['id_turma']] = $v['n_turma'];
        }
        $turmas = array_column($t, 'id_turma');

        $date = new DateTime('-15 day');
        $dataInicio = $date->format('Y-m-d');

        $mongo = new mongoCrude('Diario');

        $filter = [
            'data' => [
                '$gt' => $dataInicio
            ],
            'id_turma' => [
                '$in' => $turmas
            ]
        ];

        $freqMain = $mongo->query('presence_' . $id_pl, $filter, ['limit' => 10000, 'sort' => ['data' => -1]]);
        $result['totalAulas'] = count($freqMain);
        foreach ($freqMain as $freq) {
            $alunosft = (array) $freq->frequencia;
            @$result['freqsDatas'][$freq->data] += count($alunosft);
            @$result['freqsPeriodo'] += count($alunosft);
            @$result['aulaDatas'][$freq->data]++;
            $ft = array_count_values($alunosft);
            if (!empty($ft['F'])) {
                @$result['faltasPeriodo'] += $ft['F'];
                @$result['faltasDatas'][$freq->data] += $ft['F'];
            }
            foreach ($alunosft as $id_pessoa => $v) {
                @$contAulaEsc[$inst[$freq->id_turma]][$id_pessoa]++;
                if ($v == 'F') {
                    if (empty($ativo[$id_pessoa])) {
                        @$sequencia[$id_pessoa]++;
                        $ativo[$id_pessoa] = 1;
                        if ($id_inst) {
                            @$resultSeqEsc[$freq->id_turma][$id_pessoa][$sequencia[$id_pessoa]][$freq->data] = substr(data::converteBr($freq->data), 0, 5);
                        }
                        @$resultSeq[$inst[$freq->id_turma]][$curso[$freq->id_turma]][$id_pessoa][$sequencia[$id_pessoa]]++;
                    } elseif (@$contAulaEscOld[$inst[$freq->id_turma]][$id_pessoa] == (@$contAulaEsc[$inst[$freq->id_turma]][$id_pessoa] - 1)) {
                        @$resultSeq[$inst[$freq->id_turma]][$curso[$freq->id_turma]][$id_pessoa][$sequencia[$id_pessoa]]++;
                        if ($id_inst) {
                            @$resultSeqEsc[$freq->id_turma][$id_pessoa][$sequencia[$id_pessoa]][$freq->data] = substr(data::converteBr($freq->data), 0, 5);
                        }
                    } else {
                        $ativo[$id_pessoa] = null;
                    }
                    @$result['faltasCursos'][$curso[$freq->id_turma]]++;
                    @$contAulaEscOld[$inst[$freq->id_turma]][$id_pessoa] = @$contAulaEsc[$inst[$freq->id_turma]][$id_pessoa];
                    if ($id_inst) {
                        @$result['faltaDiaSem'][$freq->id_turma][date('w', strtotime($freq->data))][$freq->data]++;
                    }
                }
            }
        }
        if ($id_inst) {
            if (!empty($resultSeqEsc)) {
                foreach ($resultSeqEsc as $id_turma => $v) {
                    foreach ($v as $id_pessoa => $y) {
                        $ids[$id_pessoa] = $id_pessoa;
                    }
                }
                if (!empty($ids)) {
                    $sql = "select id_pessoa, n_pessoa from pessoa where id_pessoa in (" . implode(', ', $ids) . ")";
                    $query = pdoSis::getInstance()->query($sql);
                    $array = $query->fetchAll(PDO::FETCH_ASSOC);
                    $n_pessoa = toolErp::idName($array);
                }
                foreach ($n_turma as $id_turma => $n_turma) {
                    if (!empty($resultSeqEsc[$id_turma])) {
                        $v = $resultSeqEsc[$id_turma];
                        foreach ($v as $id_pessoa => $y) {
                            foreach ($y as $seq => $w) {
                                if (count($w) >= 3) {
                                    $result['sequencialEsc'][$id_turma]['alunos'][$id_pessoa . ' - ' . $n_pessoa[$id_pessoa]][] = toolErp::virgulaE($w);
                                    $result['sequencialEsc'][$id_turma]['n_turma'] = $n_turma;
                                }
                            }
                        }
                    }
                }
            }
        }
        if (!empty($resultSeq)) {
            foreach (@$resultSeq as $id_inst => $v) {
                foreach ($v as $id_curso => $c) {
                    foreach ($c as $id_pessoa => $j) {
                        foreach ($j as $y) {
                            if ($y >= 3) {
                                $result['sequencial'][$id_inst][$id_curso][$id_pessoa] = $y;
                                break;
                            }
                        }
                    }
                }
            }
        }

        return $result;
    }

    public function sondagens($id_pl, $id_curso) {
        $sql = " SELECT * FROM `profe_sodagem` "
                . " WHERE `fk_id_curso` = $id_curso"
                . "  AND `fk_id_pl` = $id_pl ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    public function sondagensCurso() {
        $sql = " SELECT * FROM profe_sodagem s "
                . " join ge_periodo_letivo pl on pl.id_pl = s.fk_id_pl and pl.at_pl = 1 ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $grupo[$v['fk_id_curso']] = $v['fk_id_gh'];
        }
        return @$grupo;
    }

    public function cadSondagem() {
        ob_clean();
        $data = filter_input(INPUT_POST, 'data');
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_STRING);
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_STRING);
        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        $id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
        $id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
        $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_STRING);
        $at_sonda = filter_input(INPUT_POST, 'at_sonda', FILTER_SANITIZE_NUMBER_INT);
        $id_gh = filter_input(INPUT_POST, 'id_gh', FILTER_SANITIZE_NUMBER_INT);
        $id_hab = filter_input(INPUT_POST, 'id_hab', FILTER_SANITIZE_NUMBER_INT);
        $sondagem = filter_input(INPUT_POST, 'sondagem', FILTER_SANITIZE_NUMBER_INT);

        $id_ce = filter_input(INPUT_POST, 'id_ce', FILTER_SANITIZE_NUMBER_INT);
        $ano = date("Y");

        $collection = 'sondagem.' . $id_curso . '.' . $ano;
        $set = [
            'id_pessoa' => $id_pessoa,
            'id_disc' => $id_disc,
            'id_inst' => $id_inst,
            'id_curso' => $id_curso,
            'id_ciclo' => $id_ciclo,
            'id_turma' => $id_turma,
            'num_sondagem' => $at_sonda,
            'id_gh' => $id_gh,
            'hab.' . $id_hab => (($sondagem) ? $data : null),
            'campoEsp.' . $id_ce . '.' . $id_hab => $sondagem
        ];

        $criterion = [
            'id_pessoa' => $id_pessoa,
            'num_sondagem' => $at_sonda,
            'id_disc' => $id_disc,
        ];
        $mongo = new mongoCrude('Diario');
        $result = $mongo->update($collection, $criterion, $set, null, 2);
        if ($result) {
            echo '1';
        }
        exit();
    }

    public function getPDI($id_pessoa) {

        $sql = "SELECT pdi.id_pdi, pdi.fk_id_turma_AEE"
                . " FROM apd_pdi pdi"
                . " JOIN ge_turmas t on t.id_turma= pdi.fk_id_turma"
                . " JOIN ge_periodo_letivo plr ON plr.id_pl = t.fk_id_pl AND plr.at_pl = 1"
                . " WHERE pdi.fk_id_pessoa = $id_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getAval($id_pessoa) {
        $sql = "SELECT ava.id_aval"
                . " FROM apd_doc_aval ava"
                . " JOIN ge_turmas t on t.id_turma= ava.fk_id_turma"
                . " JOIN ge_periodo_letivo plr ON plr.id_pl = t.fk_id_pl AND plr.at_pl = 1"
                . " WHERE ava.fk_id_pessoa = $id_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getEntre($id_pessoa) {
        $sql = "SELECT en.id_entre"
                . " FROM apd_doc_entrevista en"
                . " JOIN ge_turmas t on t.id_turma= en.fk_id_turma"
                . " JOIN ge_periodo_letivo plr ON plr.id_pl = t.fk_id_pl AND plr.at_pl = 1"
                . " WHERE en.fk_id_pessoa = $id_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        return $array;
    }

    public function turmasProjeto($id_inst) {
        $sql = "SELECT * FROM ge_turmas t "
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl "
                . " JOIN ge_ciclos ON ge_ciclos.id_ciclo =  t.fk_id_ciclo "
                . " JOIN ge_cursos ON ge_cursos.id_curso = ge_ciclos.fk_id_curso "
                . " WHERE pl.at_pl = 1 AND fk_id_inst = $id_inst AND fk_id_ciclo != 32  and id_curso in (3,7,8,10)"
                . " order by n_turma ";

        $query = pdoSis::getInstance()->query($sql);
        $turma = $query->fetchAll(PDO::FETCH_ASSOC);

        return $turma;
    }

    public function getOcorrencias($data1 = null, $data2 = null, $id_turma = null, $id_disc = null, $sem_data = null) {
        if (empty($sem_data)) {
            $WHERE = " AND dt_data BETWEEN '" . $data1 . "' AND '" . $data2 . "'";
        } else {
            $WHERE = "";
        }

        if (!empty($id_turma)) {
            $WHERE = $WHERE . " AND d.fk_id_turma = $id_turma ";
        }

        if (toolErp::id_nilvel() <> 24) {
            $WHERE = $WHERE . " AND t.fk_id_inst =" . tool::id_inst();
        }

        if (!empty($id_disc)) {
            $WHERE = $WHERE . " AND d.fk_id_disc = $id_disc ";
        }
        $sql = "SELECT d.id_profe_diario, t.n_turma, d.ocorrencia, d.dt_data, d.fk_id_pessoa FROM profe_diario d "
                . " JOIN ge_turmas t on t.id_turma= d.fk_id_turma"
                . " WHERE d.at_diario = 1 "
                . $WHERE
                . " ORDER BY d.dt_data DESC ";
        $query = pdoSis::getInstance()->query($sql);
        $ocorrencias = $query->fetchAll(PDO::FETCH_ASSOC);
        return $ocorrencias;
    }

    public function bimestreSelect($id_turma) {
        $letivas = $this->letivaDados($id_turma);
        $bimestres = [];
        for ($i = 1; $i <= $letivas["qt_letiva"]; $i++) {
            $bimestres[$i] = $i . 'º ' . $letivas["un_letiva"];
        }
        $bimestres[-1] = "Todos";
        return $bimestres;
    }

    public function profe_projeto_cadSalva() {
        $ins = @$_POST[1];
        $id = $this->db->ireplace('profe_projeto', $ins);
        $hab = @$_POST['hab'];
        if (!empty($id)) {
            $sql = "DELETE FROM `profe_projeto_hab` WHERE `fk_id_projeto` = $id";
            $query = pdoSis::getInstance()->query($sql);
            if ($hab) {
                $insHab['fk_id_projeto'] = $id;
                foreach ($hab as $v) {
                    $insHab['fk_id_hab'] = $v;
                    $this->db->ireplace('profe_projeto_hab', $insHab, 1);
                }
            }
        } else {
            toolErp::alertModal('Lamento, algo não deu certo!');
        }
    }

    public function habProjeto($id_projeto, $bimestre = null) {
        $WHERE = "";
        if ($bimestre <> -1) {
            $WHERE = $WHERE . " AND atual_letiva like '%$bimestre%' ";
        }
        if (!empty($id_projeto)) {
            $sql = "SELECT p.fk_id_hab FROM profe_projeto_hab p "
                    . " Left JOIN coord_hab_ciclo chc ON p.fk_id_hab = chc.fk_id_hab"
                    . " WHERE p.fk_id_projeto = $id_projeto "
                    . $WHERE;
            $query = pdoSis::getInstance()->query($sql);
            $habs = $query->fetchAll(PDO::FETCH_ASSOC);
            $habil = array_column($habs, 'fk_id_hab');
        } else {
            $habil = 0;
        }
        return $habil;
    }

    public function getProjetoPDF($id_projeto) {

        $sql = "SELECT cc.id_ce, cc.n_ce, ch.codigo, ch.descricao, pr.fk_id_turma,pr.id_projeto, pr.n_projeto, pr.dt_inicio, pr.dt_fim, pr.habilidade, pr.justifica, pr.situacao, pr.recurso, pr.resultado, pr.fonte, pr.autores, pr.avaliacao, pr.devolutiva, pr.fk_id_projeto_status, pr.coord_vizualizar , pr.msg_coord"
                . " FROM profe_projeto pr"
                . " Left JOIN profe_projeto_hab prh ON prh.fk_id_projeto = pr.id_projeto"
                . " Left JOIN coord_hab ch ON ch.id_hab = prh.fk_id_hab"
                . " Left JOIN coord_campo_experiencia cc ON ch.fk_id_ce = cc.id_ce"
                . " WHERE id_projeto = $id_projeto";
        $query = pdoSis::getInstance()->query($sql);
        $reg = $query->fetchAll(PDO::FETCH_ASSOC);
        $registro = [];
        foreach ($reg as $k => $v) {
            if (empty($registro[$v["id_projeto"]])) {
                $registro[$v["id_projeto"]] = $v;
            }

            if (empty($registro[$v["id_projeto"]]["hab"])) {
                $registro[$v["id_projeto"]]["hab"] = [];
            }

            $registro[$v["id_projeto"]]["hab"][] = [
                "codigo" => $v["codigo"],
                "descricao" => $v["descricao"],
                "n_ce" => $v["n_ce"],
                "id_ce" => $v["id_ce"],
            ];
        }
        return $registro;
    }

    public function getHab($bimestre, $id_ciclo, $id_disc, $id_curso) {
        $WHERE = "";
        if ($bimestre <> -1) {
            $WHERE = $WHERE . " AND atual_letiva like '%$bimestre%' ";
        }
        $sql = "SELECT id_hab, descricao, codigo FROM coord_hab ch "
                . " JOIN coord_set_grupo_curso sgc ON ch.fk_id_gh = sgc.fk_id_gh AND sgc.fk_id_cur = $id_curso"
                . " Left JOIN coord_hab_ciclo chc ON ch.id_hab = chc.fk_id_hab"
                . " WHERE chc.fk_id_ciclo =$id_ciclo "
                . " AND fk_id_disc = $id_disc "
                . $WHERE
                . " AND at_hab = 1";
        $query = pdoSis::getInstance()->query($sql);
        $habs = $query->fetchAll(PDO::FETCH_ASSOC);

        $hab = [];
        if ($habs) {
            foreach ($habs as $v) {
                $hab[$v['id_hab']] = $v['codigo'] . ' - ' . $v['descricao'];
            }
        }
        return $hab;
    }

    public function getHabReg($bimestre, $id_reg) {
        $WHERE = "";
        if ($bimestre <> -1) {
            $WHERE = $WHERE . " AND atual_letiva like '%$bimestre%' ";
        }
        $sql = "SELECT ph.fk_id_hab FROM profe_projeto_reg_hab ph "
                . " Left JOIN coord_hab_ciclo chc ON ph.fk_id_hab = chc.fk_id_hab"
                . " WHERE ph.fk_id_reg =$id_reg "
                . $WHERE;
        $query = pdoSis::getInstance()->query($sql);
        $habs = $query->fetchAll(PDO::FETCH_ASSOC);
        $habil = [];
        if ($habs) {
            $habil = array_column($habs, 'fk_id_hab');
        }
        return $habil;
    }

    public function notaFaltaBim($id_curso, $id_pl, $idsPessoa, $atual_letiva) {
        $sql = "SELECT * FROM hab.aval_mf_" . $id_curso . "_" . $id_pl . " WHERE `fk_id_pessoa` in (" . implode(', ', $idsPessoa) . ") AND `atual_letiva` = $atual_letiva ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $pessoa) {
                @$notaFalta[$pessoa['fk_id_pessoa']]['id_mf'] = $pessoa['id_mf'];
                $id_pessoa = $pessoa['fk_id_pessoa'];
                foreach ($pessoa as $k => $v) {
                    if (substr($k, 0, 6) == 'media_' && !empty($v)) {
                        @$notaFalta[$pessoa['fk_id_pessoa']]['nota'][substr($k, 6)] = $v;
                    }
                    if (substr($k, 0, 6) == 'falta_' && !empty($v)) {
                        @$notaFalta[$pessoa['fk_id_pessoa']]['falta'][substr($k, 6)] = $v;
                    }
                }
            }
            if (!empty($notaFalta)) {
                return $notaFalta;
            }
        }
    }

    public function chamadaPorUnidadeLetiva($id_pl, $id_turma, $id_disc, $atualLetiva) {
        $mongo = new mongoCrude('Diario');
        $dados = $mongo->query('presence_' . $id_pl, ['id_turma' => $id_turma, 'id_disc' => $id_disc, 'atual_letiva' => $atualLetiva]);
        $ch['T'] = 0;
        if ($id_disc == 'nc') {
            foreach ($dados as $v) {
                foreach ($v->frequencia as $k => $f) {
                    $data[$v->data] = $v->data;
                    $alu[$k] = $k;
                    if ($f == 'F') {
                        @$ch['F'][$k][$v->data] = 'F';
                    }
                }
            }
            if (!empty($data) && !empty($alu)) {
                foreach ($data as $d) {
                    foreach ($alu as $v) {
                        if (empty($ch['F'][$v][$d])) {
                            @$ch['P'][$v][$d] = 'P';
                        }
                    }
                }
            }
            if (!empty($ch)) {
                foreach ($ch as $kfp => $fp) {
                    if (is_array($fp)) {
                        foreach ($fp as $id_pessoa => $datax) {
                            @$chx[$kfp][$id_pessoa] = count($datax);
                        }
                    }
                }
            }
            if (!empty($data)) {
                $chx['T'] = count($data);
            } else {
                $chx['T'] = 0;
            }
            return @$chx;
        } else {
            foreach ($dados as $v) {
                $ch['T']++;
                foreach ($v->frequencia as $k => $f) {
                    @$ch[$f][$k]++;
                }
            }
            return $ch;
        }
    }

    public function ListAlunosAEE($id_turma) {

        $sql = "SELECT id_pessoa, n_pessoa, n_porte as porte, GROUP_CONCAT(n_ne ORDER BY n_ne SEPARATOR ', ') AS ne "
                . " FROM ("
                . "SELECT DISTINCT "
                . " p.id_pessoa, p.n_pessoa, po.n_ne, porte.n_porte FROM ge_turma_aluno ta "
                . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " JOIN ge_aluno_necessidades_especiais apd on apd.fk_id_pessoa = ta.fk_id_pessoa"
                . " JOIN ge_aluno_necessidades_especiais_porte porte on apd.fk_id_porte = porte.id_porte AND fk_id_porte <> 1"
                . " JOIN pessoa p on p.id_pessoa = apd.fk_id_pessoa "
                . " JOIN ge_necessidades_especiais po on apd.fk_id_ne = po.id_ne "
                . " WHERE t.id_turma = $id_turma"
                . ") AS tab1"
                . " GROUP BY  id_pessoa, n_pessoa, n_porte ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return toolErp::idName($array);
    }

    public function getSistema($nivel_supervisor, $nivel_profe) {
        $nivel_supervisor = explode(',', $nivel_supervisor);
        $nivel_profe = explode(',', $nivel_profe);
        if (in_array(toolErp::id_nilvel(), $nivel_profe)) {
            $sistema = 'profe';
        } elseif (in_array(toolErp::id_nilvel(), $nivel_supervisor)) {
            $sistema = 'supervisor';
        } else {
            toolErp::alert('Sem Permissão.');
            die();
        }
        return $sistema;
    }

    public static function turmasSegAtiva($id_inst = null, $ano = null) {
        $id_inst = toolErp::id_inst($id_inst);
        if ($ano) {
            $sql = "SELECT "
                    . "  t.id_turma, t.n_turma, c.id_curso, c.n_curso "
                    . " FROM ge_turmas t "
                    . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                    . " JOIN ge_cursos c on c.id_curso = ci.fk_id_curso "
                    . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                    . " WHERE t.fk_id_inst = $id_inst "
                    . " AND pl.ano = $ano AND t.fk_id_ciclo != 32"
                    . " order by c.n_curso, t.n_turma ";
        } else {
            $sql = "SELECT "
                    . "  t.id_turma, t.n_turma, c.id_curso, c.n_curso "
                    . " FROM ge_turmas t "
                    . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                    . " JOIN ge_cursos c on c.id_curso = ci.fk_id_curso "
                    . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                    . " WHERE t.fk_id_inst = $id_inst "
                    . " AND pl.at_pl = 1 AND t.fk_id_ciclo != 32"
                    . " order by c.n_curso, t.n_turma ";
        }
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $t[$v['n_curso']][$v['id_turma']] = $v['n_turma'];
        }
        if (!empty($t)) {
            return $t;
        }
    }
    public static function tituloParecer($campo) {
        if ($campo == 'atvd_estudo') {
            $titulo = 'Atividades de Estudo';
        }elseif ($campo == 'instr_avaliacao') {
            $titulo = 'Instrumentos de Avaliação';
        }else{
            $titulo = 'Parecer Descritivo ao longo do Bimestre';
        }
        return $titulo;
    }
}
