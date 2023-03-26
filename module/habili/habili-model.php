<?php

class habiliModel extends MainModel {

    public $db;
    public $id_plano;

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

        $this->id_plano = filter_input(INPUT_POST, 'id_plano', FILTER_SANITIZE_NUMBER_INT);
        //seta o select dinamico
        if ($opt = formErp::jqOption()) {
            $metodo = $opt[0];
            if (in_array($metodo, get_class_methods($this))) {
                $this->$metodo($opt[1]);
            }
        }

        if ($this->db->tokenCheck('SalvarGrupCurso')) {
            $this->SalvarGrupCurso();
        } elseif ($this->db->tokenCheck('salvHab')) {
            $this->salvHab();
        } elseif ($this->db->tokenCheck('criaPlan')) {
            $this->criaPlan();
        } elseif ($this->db->tokenCheck('contrlLac')) {
            $this->contrlLac();
        } elseif ($this->db->tokenCheck('salvaNota')) {
            $this->salvaNota();
        } elseif ($this->db->tokenCheck('salvaNotaNc')) {
            $this->salvaNotaNc();
        } elseif ($this->db->tokenCheck('ge_setupBimSet')) {
            $this->ge_setupBimSet();
        } elseif ($this->db->tokenCheck('clonarPlano')) {
            $this->clonarPlano();
        }
    }

    public function clonarPlano() {
        $id_plano = filter_input(INPUT_POST, 'id_plano', FILTER_SANITIZE_NUMBER_INT);
        $id_planoOld = filter_input(INPUT_POST, 'id_planoOld', FILTER_SANITIZE_NUMBER_INT);
        $planoOld = sql::get('coord_plano_aula', '*', ['id_plano' => $id_planoOld], 'fetch');
        $ins['id_plano'] = $id_plano;
        $ins['metodologia'] = $planoOld['metodologia'];
        $ins['recursos'] = $planoOld['recursos'];
        $ins['avaliacao'] = $planoOld['avaliacao'];
        $ins['adapta_curriculo'] = $planoOld['adapta_curriculo'];
        $ins['reflexao'] = $planoOld['reflexao'];
        $this->db->ireplace('coord_plano_aula', $ins);
        $hab = sql::get('coord_plano_aula_hab', 'fk_id_hab', ['fk_id_plano' => $id_planoOld]);
        if (!empty($hab)) {
            foreach ($hab as $v) {
                $v['fk_id_plano'] = $id_plano;
                $this->db->ireplace('coord_plano_aula_hab', $v, 1);
            }
        }
    }

    public function ge_setupBimSet() {
        $b = $_POST['b'];
        foreach ($b as $k => $v) {
            if ($v == 1) {
                $bims[] = $k;
            }
        }
        if (!empty($bims)) {
            $ins['lanc_bim'] = implode(',', $bims);
            $ins['id_set'] = 1;
            $this->db->ireplace('ge_setup', $ins);
        } else {
            $ins['lanc_bim'] = null;
            $ins['id_set'] = 1;
            $this->db->ireplace('ge_setup', $ins);
        }
    }

    public function salvaNotaNc() {
        $diasQt = filter_input(INPUT_POST, 'diasQt', FILTER_SANITIZE_NUMBER_INT);
        $transpInstr = filter_input(INPUT_POST, 'transpInstr', FILTER_SANITIZE_NUMBER_INT);
        $atual_letiva = filter_input(INPUT_POST, 'atual_letiva', FILTER_SANITIZE_NUMBER_INT);
        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
        $id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
        $id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
        $id_mf = @$_POST['id_mf'];
        $falta = @$_POST['falta'];
        $nota = @$_POST['nota'];
        $instr = @$_POST['instr'];
        $id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_STRING);

        $diaAulaSet = ['fk_id_turma' => $id_turma, 'fk_id_disc' => $id_disc, 'atual_letiva' => $atual_letiva];
        $ad = sql::get('hab`.`aval_aula_dadas', 'id_ad', $diaAulaSet, 'fetch');
        if ($ad) {
            $diaAulaSet['id_ad'] = $ad['id_ad'];
        }
        $diaAulaSet['aula_dadas'] = $diasQt;
        $this->db->ireplace('hab`.`aval_aula_dadas', $diaAulaSet, 1);
        if ($transpInstr) {
            foreach ($nota as $idDisc => $n) {
                foreach ($n as $id_pessoa => $v) {
                    $lanc[$id_pessoa]['fk_id_pessoa'] = $id_pessoa;
                    $lanc[$id_pessoa]['fk_id_turma'] = $id_turma;
                    $lanc[$id_pessoa]['fk_id_ciclo'] = $id_ciclo;
                    $lanc[$id_pessoa]['atual_letiva'] = $atual_letiva;
                    if (!empty($id_mf[$id_pessoa])) {
                        $lanc[$id_pessoa]['id_mf'] = $id_mf[$id_pessoa];
                    }
                    if (!empty($instr[$id_pessoa][$idDisc])) {
                        $valor = $this->valoresValidos($instr[$id_pessoa][$idDisc], $id_ciclo);
                        $lanc[$id_pessoa]['media_' . $idDisc] = $valor;
                    }
                    $lanc[$id_pessoa]['falta_' . $id_disc] = str_replace('-', '', @$falta[$id_pessoa]);
                }
            }
        } else {
            foreach ($nota as $idDisc => $n) {
                foreach ($n as $id_pessoa => $v) {
                    $lanc[$id_pessoa]['fk_id_pessoa'] = $id_pessoa;
                    $lanc[$id_pessoa]['fk_id_turma'] = $id_turma;
                    $lanc[$id_pessoa]['fk_id_ciclo'] = $id_ciclo;
                    $lanc[$id_pessoa]['atual_letiva'] = $atual_letiva;
                    if (!empty($id_mf[$id_pessoa])) {
                        $lanc[$id_pessoa]['id_mf'] = $id_mf[$id_pessoa];
                    }
                    $v = $this->valoresValidos($v, $id_ciclo);
                    $lanc[$id_pessoa]['media_' . $idDisc] = $v;
                    $lanc[$id_pessoa]['falta_' . $id_disc] = str_replace('-', '', @$falta[$id_pessoa]);
                }
            }
        }
        if ($lanc) {
            foreach ($lanc as $v) {
                $this->db->ireplace('hab`.`aval_mf_' . $id_curso . '_' . $id_pl, $v, 1);
            }
        }
        toolErp::alert("Concluido");
    }

    public function salvaNota() {

        $diasQt = filter_input(INPUT_POST, 'diasQt', FILTER_SANITIZE_NUMBER_INT);
        $transpInstr = filter_input(INPUT_POST, 'transpInstr', FILTER_SANITIZE_NUMBER_INT);
        $atual_letiva = filter_input(INPUT_POST, 'atual_letiva', FILTER_SANITIZE_NUMBER_INT);
        $id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
        $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
        $id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
        $id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
        $id_mf = @$_POST['id_mf'];
        $falta = @$_POST['falta'];
        $nota = @$_POST['nota'];
        $instr = @$_POST['instr'];
        $id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_STRING);
        $diaAulaSet = ['fk_id_turma' => $id_turma, 'fk_id_disc' => $id_disc, 'atual_letiva' => $atual_letiva];
        $ad = sql::get('hab`.`aval_aula_dadas', 'id_ad', $diaAulaSet, 'fetch');
        if ($ad) {
            $diaAulaSet['id_ad'] = $ad['id_ad'];
        }
        $diaAulaSet['aula_dadas'] = $diasQt;
        $this->db->ireplace('hab`.`aval_aula_dadas', $diaAulaSet, 1);
        if ($transpInstr) {
            foreach ($nota as $idDisc => $n) {
                foreach ($n as $id_pessoa => $v) {
                    $lanc[$id_pessoa]['fk_id_pessoa'] = $id_pessoa;
                    $lanc[$id_pessoa]['fk_id_turma'] = $id_turma;
                    $lanc[$id_pessoa]['fk_id_ciclo'] = $id_ciclo;
                    $lanc[$id_pessoa]['atual_letiva'] = $atual_letiva;
                    if (!empty($id_mf[$id_pessoa])) {
                        $lanc[$id_pessoa]['id_mf'] = $id_mf[$id_pessoa];
                    }
                    if (!empty($instr[$id_pessoa])) {
                        $valor = $this->valoresValidos($instr[$id_pessoa], $id_ciclo);
                        $lanc[$id_pessoa]['media_' . $idDisc] = $valor;
                    }
                    $lanc[$id_pessoa]['falta_' . $id_disc] = str_replace('-', '', @$falta[$id_pessoa]);
                }
            }
        } else {
            foreach ($nota as $idDisc => $n) {
                foreach ($n as $id_pessoa => $v) {
                    $lanc[$id_pessoa]['fk_id_pessoa'] = $id_pessoa;
                    $lanc[$id_pessoa]['fk_id_turma'] = $id_turma;
                    $lanc[$id_pessoa]['fk_id_ciclo'] = $id_ciclo;
                    $lanc[$id_pessoa]['atual_letiva'] = $atual_letiva;
                    if (!empty($id_mf[$id_pessoa])) {
                        $lanc[$id_pessoa]['id_mf'] = $id_mf[$id_pessoa];
                    }
                    $v = $this->valoresValidos($v, $id_ciclo);
                    $lanc[$id_pessoa]['media_' . $idDisc] = $v;
                    $lanc[$id_pessoa]['falta_' . $id_disc] = str_replace('-', '', @$falta[$id_pessoa]);
                }
            }
        }
        if ($lanc) {
            foreach ($lanc as $v) {
                $this->db->ireplace('hab`.`aval_mf_' . $id_curso . '_' . $id_pl, $v, 1);
            }
        }
        toolErp::alert("Concluido");
    }

    public function contrlLac() {
        $qt_letiva = filter_input(INPUT_POST, 'qt_letiva', FILTER_SANITIZE_NUMBER_INT);

        foreach (range(1, $qt_letiva) as $v) {
            $ins = @$_POST[$v];
            $this->db->ireplace('habili_lancameto_data', $ins, 1);
        }
        toolErp::alert("Salvo");
    }

    public function salvHabCod() {
        $ins = $_POST[1];
        @$codigo = sql::get('coord_hab', 'codigo', ['codigo' => $ins['codigo']], 'fetch')['codigo'];
        if ($codigo) {
            toolErp::alertModal('Códgo já cadastrado');
            return;
        }
        $id = $this->db->ireplace('coord_hab', $ins, 1);

        return $id;
    }

    public function salvHab() {

        $ins = $_POST[1];
        $ciclos = filter_input(INPUT_POST, 'cicl', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $letiva = filter_input(INPUT_POST, 'letiva', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        $id_hab = $this->db->ireplace('coord_hab', $ins);

        $this->db->delete('coord_hab_ciclo', 'fk_id_hab', $id_hab, 1);
        $ins1['fk_id_hab'] = $id_hab;

        if (!empty($ciclos)) {
            foreach ($ciclos as $kci => $ci) {
                $ins1['atual_letiva'] = NULL;
                $ins1['fk_id_ciclo'] = $kci;
                //$ins1['id_hc'] = $ci;
                if (!empty($letiva[$kci])) {
                    $ins1['atual_letiva'] = ',' . (implode(',', $letiva[$kci])) . ',';
                }
                $this->db->insert('coord_hab_ciclo', $ins1, 1);
            }
        }
    }

    public function SalvarGrupCurso() {
        $gr = filter_input(INPUT_POST, 'gr', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        foreach ($gr as $k => $v) {
            if (!empty($v)) {
                unset($ins);
                $ins['fk_id_cur'] = $k;
                $ins['fk_id_gh'] = $v;
                $this->db->replace('coord_set_grupo_curso', $ins, 1);
            } else {
                $this->db->delete('coord_set_grupo_curso', 'fk_id_cur', $k, 1);
            }
        }
        toolErp::alert('Salvo');
    }

    public function retornaHabilidades($id_ciclo, $id_disc, $atualLetiva = null, $id_turma = null, $data = null) {
        if ($id_disc == 'nc') {
            $disc = array_flip(turmas::disciplinas($id_turma)['nucleComum']);
            $id_disc = implode(', ', $disc);
        }
        $sql = " SELECT h.id_hab, h.descricao, h.codigo, c.atual_letiva FROM coord_hab h "
                . " join coord_grup_hab gh on gh.id_gh = h.fk_id_gh "
                . " JOIN coord_hab_ciclo c on c.fk_id_hab = h.id_hab "
                . " WHERE c.fk_id_ciclo =  " . $id_ciclo
                . " and fk_id_disc in ($id_disc) "
                . " and gh.at_gh = 1 "
                . " ORDER BY h.codigo ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            if (strstr($v['atual_letiva'], $atualLetiva)) {
                $h['b'][$v['id_hab']] = $v['codigo'] . ' - ' . $v['descricao'];
            }
            $h['a'][$v['id_hab']] = $v['codigo'] . ' - ' . $v['descricao'];
        }
        $h['p'] = [];

        return $h;
    }

    public function criaPlan() {
        extract($_POST);
        if (empty($fk_id_projeto_status)) {
            $fk_id_projeto_status = 1;
        }
        $turmas = $_POST['turmas'];
        foreach ($turmas as $v) {
            if ($v) {
                $passa = 1;
            }
        }
        if (empty($passa)) {
            toolErp::alertModal("O plano de aula precisa ter, pelo menos uma turma.");
            return;
        } elseif (empty($dt_inicio) || empty($dt_fim) || empty($qt_aulas)) {
            toolErp::alertModal("Preencha todos os campos");
            return;
        } elseif ($dt_inicio > $dt_fim) {
            toolErp::alertModal("A data inicial tem que ser menor que a data final");
            return;
        }
        $texto = '';
        foreach ($turmas as $v) {
            if ($v) {
                if (!empty($this->id_plano)) {
                    $planAt = " and pa.id_plano != " . $this->id_plano;
                } else {
                    $planAt = null;
                }
                $sql = " SELECT pa.dt_inicio, pa.dt_fim FROM coord_plano_aula pa "
                        . " JOIN coord_plano_aula_turmas pt on pt.fk_id_plano = pa.id_plano "
                        . " WHERE pt.fk_id_turma = $v "
                        . $planAt
                        . " and pa.iddisc like '$id_disc' "
                        . " and ("
                        . " '$dt_inicio' BETWEEN pa.dt_inicio AND pa.dt_fim "
                        . " OR '$dt_fim' BETWEEN pa.dt_inicio AND pa.dt_fim)";
                $query = pdoSis::getInstance()->query($sql);
                $verData = $query->fetch(PDO::FETCH_ASSOC);

                if ($verData) {
                    $para = 1;
                    @$nTurma = sql::get('ge_turmas', 'n_turma', ['id_turma' => $v], 'fetch')['n_turma'];
                    $texto .= ''
                            . '<p>Você está tentando criar um Plano de Aula no período de <b>' . data::converteBr($dt_inicio) . '</b> a <b>' . data::converteBr($dt_fim) . '</b> para a turma <b>' . $nTurma . '</b>.</p>'
                            . '<p>Porém já existe um Plano de Aula no período de <b>' . data::converteBr($verData['dt_inicio']) . '</b> a <b>' . data::converteBr($verData['dt_fim']) . '</b></p><br /><br />';
                }
            }
        }
        if (!empty($para)) {
            toolErp::alertModal($texto, null, 'danger');
            return;
        }
        if ($qt_aulas < 0) {
            $qt_aulas *= (-1);
        }
        $ins = [
            'fk_id_pessoa' => $id_pessoa,
            'fk_id_inst' => $id_inst,
            'fk_id_ciclo' => $id_ciclo,
            'iddisc' => $id_disc,
            'fk_id_pl' => $id_pl,
            'atualLetiva' => $atual_letiva,
            'qt_aulas' => ($qt_aulas),
            'dt_inicio' => $dt_inicio,
            'dt_fim' => $dt_fim,
            'id_plano' => @$id_plano,
            'recursos' => @$recursos,
            'reflexao' => @$reflexao,
            'metodologia' => @$metodologia,
            'avaliacao' => @$avaliacao,
            'adapta_curriculo' => @$adapta_curriculo,
            'fk_id_projeto_status' => @$fk_id_projeto_status,
            'coord_vizualizar' => @$coord_vizualizar
        ];
        /**
          if (in_array(tool::id_pessoa(), [1, 5])) {
          $debug = 1;
          } else {
          $debug = null;
          }
         * 
         */
        $id = $this->db->ireplace('coord_plano_aula', $ins, null, null, @$debug);
        $turmasSet['fk_id_plano'] = $id;
        if (!empty($id_plano)) {
            $sql = "DELETE FROM `coord_plano_aula_turmas` WHERE fk_id_plano = $id_plano";
            $query = pdoSis::getInstance()->query($sql);
        }
        foreach ($turmas as $v) {
            if ($v) {
                $turmasSet['fk_id_turma'] = $v;
                $this->db->insert('coord_plano_aula_turmas', $turmasSet, 1);
            }
        }

        $this->id_plano = $id;
    }

    /**
     * 
     * @param type $turmaDisciplina é o método turmaDisciplina
     * @param type $ano
     * @param type $mes
     */
    public function planoProfMes($turmaDisciplina, $ano, $mes) {
        $ano = str_pad($ano, 2, "0", STR_PAD_LEFT);
        $mes = str_pad($mes, 2, "0", STR_PAD_LEFT);
        $turmaDisc = "'" . implode("','", array_keys($turmaDisciplina)) . "'";
        $sql = "SELECT pa.dt_inicio, pa.dt_fim, pa.qt_aulas, pa.iddisc, pt.fk_id_turma FROM coord_plano_aula pa "
                . " JOIN coord_plano_aula_turmas pt on pt.fk_id_plano = pa.id_plano "
                . " WHERE concat(pt.fk_id_turma,'_',pa.iddisc) in ($turmaDisc) "
                . " AND ( pa.dt_inicio LIKE '" . $ano . "-" . $mes . "-%' OR pa.dt_fim LIKE '" . $ano . "-" . $mes . "-%' )";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $dias = dataErp::diferencaDias($v['dt_inicio'], $v['dt_fim']) + 1;
            $v['dias'] = $dias;
            if ($v['dt_inicio'] < $ano . '-' . $mes . '-01') {
                $dt_inicio = 'i';
            } else {
                $dt_inicio = $v['dt_inicio'];
            }
            $planoAula[$v['fk_id_turma'] . '_' . $v['iddisc']][$dt_inicio] = $v;
        }

        if (!empty($planoAula)) {
            return $planoAula;
        }
    }

    public function planoTurmaMes($id_turma, $ano, $mes) {
        $ano = str_pad($ano, 2, "0", STR_PAD_LEFT);
        $mes = str_pad($mes, 2, "0", STR_PAD_LEFT);
        $sql = "SELECT pa.dt_inicio, pa.dt_fim, pa.qt_aulas, pa.iddisc FROM coord_plano_aula pa "
                . " JOIN coord_plano_aula_turmas pt on pt.fk_id_plano = pa.id_plano "
                . " WHERE pt.fk_id_turma = " . $id_turma
                . " AND ( pa.dt_inicio LIKE '" . $ano . "-" . $mes . "-%' OR pa.dt_fim LIKE '" . $ano . "-" . $mes . "-%' )";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $dias = dataErp::diferencaDias($v['dt_inicio'], $v['dt_fim']) + 1;
            $v['dias'] = $dias;
            if ($v['dt_inicio'] < $ano . '-' . $mes . '-01') {
                $dt_inicio = 'i';
            } else {
                $dt_inicio = $v['dt_inicio'];
            }
            $planoAula[$v['iddisc']][$dt_inicio] = $v;
        }
        if (!empty($planoAula)) {
            return $planoAula;
        }
    }

    public function turmaDisciplina($id_pessoa) {
        $disciplinasPorClasse = professores::classesDisc($id_pessoa);
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
                        }
                    }
                }
            }
        } else {
            return;
        }
        return $disciplinasPorClasse;
    }

    public function planoHabil($id_plano) {
        $sql = " SELECT "
                . " hab.descricao, hab.codigo, disc.id_disc, disc.n_disc, pah.id_pah, hab.id_hab,"
                . " ut.n_ut, oc.n_oc, hab.metodologicas, hab.verific_aprendizagem "
                . " FROM coord_plano_aula_hab pah "
                . " JOIN coord_hab hab on hab.id_hab = pah.fk_id_hab "
                . " left join coord_uni_tematica ut on ut.id_ut = hab.fk_id_ut "
                . " left join coord_objeto_conhecimento oc on oc.id_oc = hab.fk_id_oc "
                . " left join ge_disciplinas disc on disc.id_disc = hab.fk_id_disc "
                . " WHERE pah.fk_id_plano = $id_plano "
                . " order by disc.n_disc";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function lancData() {
        $sql = "SELECT * FROM ge_cursos c "
                . "left join habili_lancameto_data l  on l.fk_id_curso = c.id_curso"
                . " WHERE c.id_curso IN (1,5,9)";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $lanc[$v['id_curso']]['n_curso'] = $v['n_curso'];
            $lanc[$v['id_curso']]['qt_letiva'] = $v['qt_letiva'];
            $lanc[$v['id_curso']]['un_letiva'] = $v['un_letiva'];
            $lanc[$v['id_curso']]['letiva'][$v['un_letiva_lanc']] = $v;
        }

        return $lanc;
    }

    public function retornaInstrumentosAvaliativos($idPl, $idTurma, $atualLetiva, $id_disc) {
        $mongo = new mongoCrude('Diario');
        $result = $mongo->query('instrumentos.' . $idPl, ['id_turma' => $idTurma, 'atual_letiva' => $atualLetiva, 'id_disc' => $id_disc]);
        return $result;
    }

    public function letivaDados($id_turma) {
        $sql = "SELECT un_letiva, qt_letiva, atual_letiva FROM ge_ciclos g JOIN ge_cursos gc ON gc.id_curso = g.fk_id_curso " .
                "JOIN ge_turmas gt ON gt.fk_id_ciclo = g.id_ciclo WHERE gt.id_turma = " . $id_turma;

        $query = pdoSis::getInstance()->query($sql);
        $letivas = $query->fetch(PDO::FETCH_ASSOC);
        return $letivas;
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

    public function notaFaltaBim($id_curso, $id_pl, $idsPessoa, $atual_letiva) {
        $sql = "SELECT * FROM hab.aval_mf_" . $id_curso . "_" . $id_pl . " WHERE `fk_id_pessoa` in (" . implode(', ', $idsPessoa) . ") AND `atual_letiva` = $atual_letiva  and  excluido != 1";
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

    public function valoresValidos($v, $id_ciclo = 0) {
        $v = str_replace(['-', ','], ['', '.'], $v);
        if (is_numeric($v)) {
            if ($id_ciclo == 1 && $v < 5) {
                $v = 5;
            }
            $test = round(($v - intval($v)), 1);
            if ($v <= 10) {
                if ($test == 0.5 || $test == 0) {
                    return str_replace('.', ',', $v);
                } elseif ($test < 0.3) {
                    return intval($v);
                } elseif ($test < 0.5) {
                    return intval($v) . ',5';
                } elseif ($test > 0.7) {
                    return intval($v) + 1;
                } else {
                    return intval($v) . ',5';
                }
            } else {
                return '0';
            }
        } else {
            return '0';
        }
    }

    public function notaSet($id_curso, $atual_letiva) {
        $aberto['prof'] = null;
        $aberto['coord'] = null;
        $sql = "SELECT * FROM `habili_lancameto_data` WHERE `fk_id_curso` = $id_curso AND `un_letiva_lanc` = $atual_letiva";
        $query = pdoSis::getInstance()->query($sql);
        $set = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($set['dt_inicio']) && !empty($set['dt_fim'])) {
            $data = date("Y-m-d");
            if ($data >= $set['dt_inicio'] && $data <= $set['dt_fim']) {
                $aberto['prof'] = 1;
                $aberto['coord'] = 1;
            }
        }
        if (!empty($set['aberto_prof'])) {
            $aberto['prof'] = 1;
        }
        if (!empty($set['aberto_coord'])) {
            $aberto['coord'] = 1;
        }

        return $aberto;
    }

    public function aulaDiscDia($id_inst, $diaSemana = null) {
        if ($diaSemana) {
            $diaSemana = " and dia_semana = $diaSemana ";
        } else {
            $diaSemana = null;
        }
        $sql = "SELECT t.n_turma, d.n_disc, h.* FROM ge_turmas t "
                . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1"
                . " JOIN ge_horario h on h.fk_id_turma = t.id_turma "
                . " left join ge_disciplinas d on d.id_disc = h.iddisc "
                . " WHERE t.fk_id_inst = $id_inst "
                . $diaSemana
                . " order by n_turma, aula";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            if ($v['iddisc']) {
                $aulas[$v['fk_id_turma']]['n_turma'] = $v['n_turma'];
                $aulas[$v['fk_id_turma']]['disc'][$v['iddisc']]['aulas'][$v['aula']] = $v['aula'];
                if ($v['iddisc'] == 'nc') {
                    $aulas[$v['fk_id_turma']]['disc'][$v['iddisc']]['n_disc'] = 'Polivalente';
                } else {
                    $aulas[$v['fk_id_turma']]['disc'][$v['iddisc']]['n_disc'] = $v['n_disc'];
                }
            }
        }
        if (!empty($aulas)) {
            return $aulas;
        }
    }

    public function getStatusProjeto($id_status) {

        if ($id_status == 1) {
            $status = "Não enviado ao Coordenador";
        } else if ($id_status == 2) {
            $status = "Enviado ao Coordenador";
        } else if ($id_status == 3) {
            $status = "Aprovado pelo Coordenador";
        } else if ($id_status == 4) {
            $status = "Devolvido pelo Coordenador";
        }

        return $status;
    }

    public function getCiclos($id_inst, $id_curso) {
        $sql = "SELECT "
                . " ci.id_ciclo, ci.n_ciclo "
                . " FROM ge_turmas t "
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " JOIN ge_cursos c on c.id_curso = ci.fk_id_curso AND c.id_curso IN ($id_curso)"
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " WHERE t.fk_id_inst = $id_inst "
                . " AND pl.at_pl = 1 "
                . " ORDER by ci.n_ciclo ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return toolErp::idName($array);
    }

    public function getQtdLetiva($id_curso) {
        $sql = "SELECT qt_letiva, un_letiva, atual_letiva FROM ge_cursos "
                . " WHERE ativo = 1 AND id_curso IN ($id_curso)"
                . "order by qt_letiva desc";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getDiscs($id_inst, $id_ciclo) {
        $sql = "SELECT "
                . "  d.id_disc, d.n_disc, nucleo_comum "
                . " FROM ge_turmas t "
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo AND ci.id_ciclo IN ($id_ciclo)"
                . " JOIN ge_aloca_disc a on a.fk_id_grade = t.fk_id_grade "
                . " JOIN ge_disciplinas d on d.id_disc = a.fk_id_disc "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " WHERE t.fk_id_inst = $id_inst "
                . " AND pl.at_pl = 1 "
                . " order by d.n_disc ";

        //echo $sql;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            if (!empty($v['nucleo_comum'])) {
                $disc['nc'] = 'Polivalente';
            } else {
                $disc[$v['id_disc']] = $v['n_disc'];
            }
        }
        if (!empty($disc)) {
            return $disc;
        }
    }

    public function getCurso($id_ciclo) {
        $sql = "SELECT "
                . " c.n_curso, c.id_curso "
                . " FROM ge_ciclos ci "
                . " JOIN ge_cursos c on c.id_curso = ci.fk_id_curso "
                . " WHERE ci.id_ciclo = $id_ciclo "
                . " ORDER by c.n_curso ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getDisc($id_disc) {
        if ($id_disc == 'nc') {
            return 'Polivalente';
        } else {
            $sql = "SELECT "
                    . " n_disc"
                    . " FROM ge_disciplinas "
                    . " WHERE id_disc = $id_disc ";
            $query = pdoSis::getInstance()->query($sql);
            $n_disc = $query->fetch(PDO::FETCH_ASSOC);
            return $n_disc["n_disc"];
        }
    }

    public function getCiclo($id_ciclo) {
        $sql = "SELECT "
                . " n_ciclo"
                . " FROM ge_ciclos "
                . " WHERE id_ciclo = $id_ciclo ";
        $query = pdoSis::getInstance()->query($sql);
        $n_ciclo = $query->fetch(PDO::FETCH_ASSOC);
        return $n_ciclo["n_ciclo"];
    }

    public function getTurmaPlano($id_plano) {
        $sql = "SELECT t.n_turma"
                . " FROM coord_plano_aula_turmas pt"
                . " JOIN ge_turmas t ON t.id_turma = pt.fk_id_turma "
                . " WHERE fk_id_plano =" . $id_plano;
        $query = pdoSis::getInstance()->query($sql);
        $n_turma = $query->fetchAll(PDO::FETCH_ASSOC);
        return $n_turma;
    }

    public static function sondaAt($quant) {
        ob_end_clean();
        foreach (range(0, $quant) as $v) {
            $qA[$v] = $v;
        }
        echo json_encode($qA);
        exit();
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

}
