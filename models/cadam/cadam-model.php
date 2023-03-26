<?php

class cadamModel extends MainModel {

    public $db;
    public $_convocado = NULL;

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

        if (!empty($_POST['abf'])) {
            if (!empty($_POST['fechar'])) {
                $sql = "DELETE FROM `cadam_fecha`";
                $query = $this->db->query($sql);
            } else {
                $escolas = escolas::idInst();
                foreach ($escolas as $k => $v) {
                    $sql = "INSERT INTO `cadam_fecha` (`id_inst`, `professor`) VALUES ('$k', '1');";
                    $query = $this->db->query($sql);
                }
            }
        }
        if (!empty($_POST['ab'])) {

            if ($_POST['professor'] == 1) {
                $insert['professor'] = 0;
            } else {
                $insert['professor'] = 1;
            }
            $insert['id_inst'] = $_POST['id_inst'];
            $this->db->ireplace('cadam_fecha', $insert);
        }

        if ($this->db->sqlKeyVerif('finalizarReserva')) {
            $insert['fk_id_inst'] = @$_POST['id_inst'];
            $insert['fk_id_cad'] = @$_POST['id_cad'];
            $insert['mes'] = @$_POST['mesSet'];
            $insert['dia_ini'] = @$_POST['dia_ini'];
            $insert['dia_fim'] = @$_POST['dia_fim'];
            $insert['fk_id_cargo'] = @$_POST['id_cargo'];
            $insert['rm'] = @$_POST['rm'];
            $insert['periodo'] = @$_POST['manha'] . @$_POST['tarde'] . @$_POST['noite'];
            $insert['ano'] = date("Y");
            $insert['fk_id_pessoa'] = tool::id_pessoa();

            $this->db->ireplace('cadam_reserva', $insert);
            unset($_POST['lanc']);
        }
        if ($this->db->sqlKeyVerif('finalizarCadampe')) {

            if (!empty($_POST['idturmas'])) {
                $idturmas = unserialize(str_replace('|', '"', @$_POST['idturmas']));

                foreach ($idturmas as $k => $v) {
                    $id_turma[] = $k;
                    @$aulaTurma[$k] = count($v);
                    @$aulas += count($v);
                    foreach ($v as $vv) {
                        $aulaPosi[$k][] = $vv;
                        @$aulaPosicao[] = $vv;
                    }
                }
                $n_turmas = sql::get('ge_turmas', 'id_turma, n_turma', 'where id_turma in (' . (implode(',', $id_turma)) . ')');
                foreach ($n_turmas as $v) {
                    @$turmas .= $v['n_turma'] . ' ( Aula' . ($aulaTurma[$v['id_turma']] > 1 ? 's' : '') . ' ' . (tool::virgulaE($aulaPosi[$v['id_turma']])) . ')';
                }
            }
            $valores = sql::get('cadam_valores', '*', NULL, 'fetch');
            $insert['fk_id_cad'] = @$_POST['id_cad'];
            $insert['horas'] = @$aulas;
            $insert['rm'] = @$_POST['rm'];
            $insert['turmas'] = @$turmas;
            $insert['idturmas'] = implode(',', $id_turma);
            ;
            $insert['periodo'] = @$_POST['periodo'];
            $insert['fk_id_cargo'] = @$_POST['id_cargo'];
            $insert['fk_id_mot'] = @$_POST['fk_id_mot'];
            $insert['dia'] = @$_POST['dia'];
            $insert['mes'] = @$_POST['mesSet'];
            $insert['ano'] = date("Y");
            $insert['fk_id_inst'] = @$_POST['id_inst'];
            $insert['dt_fr'] = date("Y-m-d H:i:s");
            $insert['fk_id_pessoa'] = tool::id_pessoa();
            $insert['v_hora'] = @$valores['hora'];
            $insert['v_dia'] = @$valores['dia'];
            $insert['aulas'] = implode(',', @$aulaPosicao);
            $this->db->insert('cadam_freq', $insert);

            $this->db->ireplace('cadam_cadastro', ['id_cad' => @$_POST['id_cad'], 'contato' => date("Y-m-d H:i:s")], 1);
            unset($_POST['lanc']);
        }

        if (DB::sqlKeyVerif('lancTea')) {
            $valores = sql::get('cadam_valores', '*', ['id_val' => 1], 'fetch');
            $insert = $_POST[1];
            $cr = explode(';', $_POST['id_cad_rse']);
            $insert['fk_id_cad'] = $cr[0];
            $insert['rse'] = $cr[1];
            $insert['v_dia'] = $valores['dia'];
            $insert['v_hora'] = $valores['hora'];

            $sql = "select id_turma, periodo from ge_turma_aluno ta "
                    . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
                    . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                    . "where pl.at_pl = 1 and ta.fk_id_pessoa = " . $insert['rse'];

            $query = $this->db->query($sql);
            $turma = @$query->fetch();

            $insert['fk_id_turma'] = $turma['id_turma'];
            $insert['periodo'] = $turma['periodo'];

            $sql = "SELECT * FROM `cadam_freq_tea` "
                    . " WHERE `periodo` LIKE '" . $insert['periodo'] . "' "
                    . " AND `dia` =  " . intval($insert['dia'])
                    . " AND `mes` = " . intval($insert['mes'])
                    . " AND `ano` = " . $insert['ano'];

            $query = $this->db->query($sql);
            $jaAlocado = $query->fetchAll();

            if (empty($jaAlocado)) {
                $this->db->ireplace('cadam_freq_tea', $insert);
            } else {
                tool::alert("Este professor já está alocado neste dia e período");
            }
        }

        if (DB::sqlKeyVerif('cancelaRes')) {
            $this->db->ireplace('cadam_reserva', ['id_cr' => @$_POST['id_cr'], 'cancelado' => 1]);
        }
        if (DB::sqlKeyVerif('cancela')) {
            $this->db->ireplace('cadam_freq', ['id_fr' => @$_POST['id_fr'], 'cancelado' => 1]);
        }

        if (DB::sqlKeyVerif('cancelaTea')) {
            $this->db->ireplace('cadam_freq_tea', ['id_fr' => @$_POST['id_fr'], 'cancelado' => 1]);
        }
        ###########################

        if (!empty($_POST['_convocado'])) {
            @$this->_convocado = $_POST['_convocado'];
        }
        if (!empty($_POST['solicitarCapampe'])) {

            $insert['fk_id_inst'] = @$_POST['id_inst'];
            $insert['fk_id_cad'] = @$_POST['id_cad'];
            $insert['dt_con'] = date("Y") . '-' . str_pad(@$_POST['mesSet'], 2, "0", STR_PAD_LEFT) . '-' . str_pad(@$_POST['dia'], 2, "0", STR_PAD_LEFT);
            $insert['data_time'] = date("Y-m-d H:i:s");
            $insert['fk_id_pessoa'] = tool::id_pessoa();
            $insert['contato'] = @$_POST['contato'];
            $insert['justifica'] = @$_POST['justifica'];
            $insert['periodo'] = @$_POST['periodo'];
            $insert['id_con'] = @$_POST['id_con'];
            $insert['uniqid'] = @$_POST['uniqid'];
            $insert['rm_reservado'] = @$_POST['rm_reservado'];

            $this->db->ireplace('cadam_convoca', $insert, 1);
            if ($insert['contato'] == 3) {
                $this->db->update('cadam_cadastro', 'id_cad', $insert['fk_id_cad'], ['contato' => date("Y-m-d H:i:s")]);
            }
            unset($_POST['solicitarCapampe']);
            if ($insert['contato']) {
                $this->_convocado = $insert['fk_id_cad'];
            }
            unset($_POST['id_con']);
        }

        if (!empty($_POST['esc_vaga'])) {
            foreach ($_POST as $v) {
                if (is_array($v)) {
                    $this->db->ireplace('cadam_esc_vaga', $v, 1);
                }
            }
        }

        if (!empty($_POST['atrib'])) {
            echo '&nbsp;';
            if (!empty($_POST['id'])) {
                if (empty($_POST['m']) && empty($_POST['n']) && empty($_POST['t'])) {
                    tool::alert("Selecione ao menos um período");
                } else {
                    $insert['fk_id_cad'] = @$_POST['id_cad'];
                    $insert['fk_id_cargo'] = @$_POST['id_cargo'];
                    $insert['m'] = @$_POST['m'];
                    $insert['t'] = @$_POST['t'];
                    $insert['n'] = @$_POST['n'];
                    foreach ($_POST['id'] as $k => $v) {
                        $insert['fk_id_inst'] = $k;
                        $this->db->ireplace('cadam_escola', $insert, 1);
                    }
                }
            }
        }
        if (!empty($_POST['atribDel'])) {
            if (!empty($_POST['id'])) {
                foreach ($_POST['id'] as $k => $v) {
                    $insert['fk_id_inst'] = $k;
                    $this->db->delete('cadam_escola', 'id_ce', $k);
                }
            }
        }

        if (DB::sqlKeyVerif('cadCadampe')) {
            $fk_id_sel = @$_POST['fk_id_sel'];
            $id_inscr = @$_POST[1]['fk_id_inscr'];

            $insert = $_POST[1];
            $insert['dt_cad'] = data::converteUS($insert['dt_cad']);

            if (!empty($_POST['cargos_e'])) {
                $insert['cargos_e'] = '|';
                foreach ($_POST['cargos_e'] as $v) {
                    $insert['cargos_e'] .= $v . '|';
                    $wcargo = $v;
                }
            } else {
                $insert['cargos_e'] = NULL;
            }

            if (!empty($_POST['dia'])) {
                $insert['dia'] = '|';
                foreach ($_POST['dia'] as $k => $v) {
                    if (!empty($v)) {
                        $insert['dia'] .= $k . '|';
                    }
                }
            }

            if (!empty($_POST['doc'])) {
                $insert['doc'] = '|';
                foreach ($_POST['doc'] as $k => $v) {
                    if (!empty($v)) {
                        $insert['doc'] .= $k . '|';
                    }
                }
            }
            //Guarda variavel recadastramento
            $recadastramento = $insert['check_update'];

            // atualiza apenas se for recadastramento
            $timezone = new DateTimeZone('America/Sao_Paulo');
            $now = new DateTime('now', $timezone);
            $update_at = $now->format('Y-m-d H:i:s');

            if ($insert['check_update'] == 2) {
                $insert['update_at'] = NULL;
            } else {
                $insert['update_at'] = $update_at;
            }

            $id_cad_ = $this->db->ireplace('cadam_cadastro', $insert, 1);

            $insert = $_POST[2];
            $insert['dt_nasc'] = data::converteUS($insert['dt_nasc']);

            $this->db->ireplace('pessoa', $insert);

            $class = $_POST['classDisc'];

            foreach ($class as $k => $v) {
                $sql = "SELECT * FROM `cadam_class` "
                        . " WHERE `class` = $v "
                        . "AND `fk_id_cargo` = $k "
                        . "AND `fk_id_sel` = $fk_id_sel "
                        . " AND fk_id_inscr != $id_inscr ";

                if (!empty($array)) {
                    tool::alert("O Cadampe incr. nº " . $array['fk_id_inscr'] . " já é o " . $v . "º classificado");
                    ?>
                    <div class="alert alert-danger" style="font-weight: bold; font-size: 20px">
                        <?php echo "O Cadampe incr. nº " . $array['fk_id_inscr'] . " já é o " . $v . "º classificado" ?>
                    </div>
                    <?php
                }

                $sql = "update `cadam_class` "
                        . " set class =  '$v' "
                        . " WHERE `fk_id_inscr` = $id_inscr AND `fk_id_cargo` = $k AND `fk_id_sel` = $fk_id_sel";
                $query = pdoSis::getInstance()->query($sql);
            }

            if ($recadastramento == 1) {

                $sql = "SELECT * FROM cadam_classificacao_cargo_geral cg"
                        . " JOIN cadam_cadastro cc ON cc.id_cad = fk_id_cad"
                        . " WHERE cc.fk_id_inscr = '" . $id_inscr . "'"
                        . " AND cc.fk_id_sel = '" . $fk_id_sel . "'";

                $query = pdoSis::getInstance()->query($sql);
                $resul = $query->fetch(PDO::FETCH_ASSOC);

                if (empty($resul)) {

                    $sql = "INSERT INTO cadam_classificacao_cargo_geral(fk_id_cad, seletiva, fk_id_cargo, class, ordem_sel)"
                            . " SELECT c.id_cad, cs.n_sel, cl.fk_id_cargo, cl.class, cs.ordem FROM cadam_cadastro c"
                            . " JOIN cadam_class cl ON cl.fk_id_inscr = c.fk_id_inscr AND cl.fk_id_sel = c.fk_id_sel"
                            . " JOIN cadam_seletivas cs ON cs.id_sel = c.fk_id_sel"
                            . " WHERE c.fk_id_inscr = '" . $id_inscr . "'";

                    $query = pdoSis::getInstance()->query($sql);

                    foreach ($class as $k => $v) {
                        $sql = "SELECT * FROM cadam_classificacao_cargo_geral cg"
                                . " WHERE cg.fk_id_cargo = '" . $k . "'"
                                . " ORDER BY cg.ordem_sel, cg.class";

                        $query = $this->db->query($sql);
                        $atzclass = $query->fetchAll();

                        $wconta = 0;
                        $grava = False;
                        $rodizio = 99999;

                        if (!empty($atzclass)) {
                            foreach ($atzclass as $v) {
                                $wconta++;
                                if ($v['rodizio'] == '9999') {
                                    if ($wconta == 1) {
                                        $w_id = $v['id'];
                                        $grava = True;
                                    } else {
                                        $sql = "UPDATE cadam_classificacao_cargo_geral SET class_geral = '" . $wconta . "',"
                                                . " rodizio = '" . $rodizio . "'"
                                                . " WHERE id = '" . $v['id'] . "'";
                                        $query = pdoSis::getInstance()->query($sql);
                                    }
                                } else {
                                    $rodizio = $v['rodizio'];
                                    $sql = "UPDATE cadam_classificacao_cargo_geral SET class_geral = '" . $wconta . "'"
                                            . " WHERE id = '" . $v['id'] . "'";
                                    $query = pdoSis::getInstance()->query($sql);
                                }

                                if (($grava == True) && ($wconta == 2)) {
                                    $sql = "UPDATE cadam_classificacao_cargo_geral SET class_geral = '1', rodizio = '" . $rodizio . "'"
                                            . " WHERE id = '" . $w_id . "'";
                                    $query = pdoSis::getInstance()->query($sql);
                                }
                            }
                        }
                    }
                    /*
                      foreach ($class as $k => $v) {
                      $sql = "SELECT * FROM cadam_classificacao_cargo_geral cg"
                      . " WHERE cg.fk_id_cargo = '" . $k . "'"
                      . " ORDER BY class_geral";

                      $query = $this->db->query($sql);
                      $rod = $query->fetchAll();
                      foreach ($rod as $v) {
                      $rodizio = $v['rodizio'];
                      if (($v['rodizio'] == '9999') && ($v['class_geral'] == 1)) {
                      $grava = False;
                      } else {

                      }
                      $sql = "UPDATE cadam_classificacao_cargo_geral SET rodizio = '" . $rodizio . "'"
                      . " WHERE id = '" . $v['id'] . "'";
                      $query = pdoSis::getInstance()->query($sql);
                      }
                      }
                     * 
                     */
                }
            }

            log::logSet('Cadastrou/Alterou Cadampe Protocolo: ' . $id_cad_);
        }

        if (!empty($_POST['cl'])) {
            if ($_POST['cl'] == 'Classificar') {
                $this->geraclassificacao();
            }
        }
    }

    public function buscar($search, $id_sel = NULL, $id_cargo = NULL, $ativo = NULL, $recadastrado = NULL) {
        if (@$ativo == 1) {
            $ativo = " and c.ativo = 1 ";
        } elseif (@$ativo == 2) {
            $ativo = " and c.ativo = 0 ";
        } else {
            $ativo = NULL;
        }
        if (!empty($id_sel)) {
            $sel = " and fk_id_sel = " . $id_sel . " ";
        }
        if (!empty($id_cargo)) {
            $cargo = " and cargos_e like '%|" . $id_cargo . "|%' ";
        }
        if (!empty($recadastrado)) {
            $str_recadastrado = " and check_update = $recadastrado  ";
        }
        $sql = "select "
                . " s.n_sel, p.n_pessoa, c.fk_id_inscr, c.id_cad, p.cpf, c.classifica,c. nota, c.fk_id_sel, "
                . " c.cad_pmb, p.tel1, p.tel2, p.tel3, c.dia, c.tea, c.check_update, c.update_at "
                . " from cadam_cadastro c "
                . " join pessoa p on p.id_pessoa = c.fk_id_pessoa "
                . " left join cadam_seletivas s on s.id_sel = c.fk_id_sel "
                . "where ("
                . "p.n_pessoa like '%$search%' "
                . "or p.cpf like '$search' "
                . "or fk_id_inscr like '$search' "
                . ") "
                . @$id_sel
                . @$cargo
                . $ativo
                . @$str_recadastrado
                . " order by p.n_pessoa";
        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        return $array;
    }

    public function getCadampe($id_cad) {
        $sql = " select *, c.ativo as ativo from cadam_cadastro c "
                . " join pessoa p on p.id_pessoa = c.fk_id_pessoa "
                . " join cadam_seletivas s on s.id_sel = c.fk_id_sel "
                . " where c.id_cad = " . $id_cad;
        $query = $this->db->query($sql);
        $dados = $query->fetch();
        if (!empty($dados)) {
            return $dados;
        }
    }

    public function alunosTea($id_cad) {
        $sql = " select n_pessoa, id_pessoa, n_inst, n_turma, id_tea from instancia i "
                . " join ge_turmas t on t.fk_id_inst = i.id_inst "
                . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " join ge_turma_aluno ta on ta.fk_id_turma = t.id_turma "
                . " join pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " join cadam_tea te on te.fk_id_pessoa = p.id_pessoa "
                . " where fk_id_cad = " . $id_cad
                . " AND at_pl = 1 ";
        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        return $array;
    }

    public function cadampesEscola($id_inst = NULL) {
        if (empty($id_inst)) {
            $id_inst = tool::id_inst();
        }
        $sql = "SELECT distinct "
                . " p.n_pessoa, p.id_pessoa, c.id_cad, cad_pmb, u.id_user "
                . " FROM cadam_cadastro c "
                . " join pessoa p on p.id_pessoa = c.fk_id_pessoa "
                . " join cadam_escola ce on c.id_cad = ce.fk_id_cad "
                . " left join users u on u.fk_id_pessoa = p.id_pessoa "
                . " WHERE ce.fk_id_inst = " . $id_inst . " "
                . " order by p.n_pessoa asc";
        $query = $this->db->query($sql);
        $da = $query->fetchAll();
        foreach ($da as $v) {
            $dd[$v['id_cad']] = $v;
        }

        $sql = "SELECT distinct "
                . " p.n_pessoa, p.id_pessoa, c.id_cad, cad_pmb , id_tea, u.id_user "
                . " FROM cadam_cadastro c "
                . " join pessoa p on p.id_pessoa = c.fk_id_pessoa "
                . " join cadam_tea ce on c.id_cad = ce.fk_id_cad "
                . " left join users u on u.fk_id_pessoa = p.id_pessoa "
                . " WHERE ce.fk_id_inst = " . $id_inst . " "
                . " order by p.n_pessoa asc";
        $query = $this->db->query($sql);
        $da = $query->fetchAll();
        foreach ($da as $v) {
            $dd[$v['id_cad']] = $v;
        }

        return $dd;
    }

    public function frequencia($id_inst, $mes = null, $ano = NULL) {
        if (!empty($mes)) {
            $mes = " and f.mes = $mes ";
        }
        if (!empty($ano)) {
            $ano = " and f.ano = $ano ";
        }
        $fields = "f.id_fr, f.fk_id_cad, f.rm, f.periodo, "
                . "f.fk_id_cargo,f.fk_id_inst, f.fk_id_inst as id_inst, f.cancelado, "
                . "cg.n_cargo, p.n_pessoa, cad_pmb, turmas, f.fk_id_mot, f.dt_fr, f.fk_id_pessoa, "
                . "f.horas, f.periodo, f.dia, f.mes, f.ano, "
                . "m.n_mot ";
        $sql = 'SELECT ' . $fields . '  FROM `cadam_freq` f '
                . 'join cadam_cadastro c on c.id_cad = f.fk_id_cad '
                . 'join pessoa p on p.id_pessoa = c.fk_id_pessoa '
                . 'join cadam_cargo cg on cg.id_cargo = f.fk_id_cargo '
                . 'left join cadam_motivo m on m.id_mot = f.fk_id_mot'
                . ' WHERE f.fk_id_inst = ' . $id_inst
                . " $ano "
                . " $mes"
                . " order by ano,mes,dia desc, n_pessoa asc";
        $query = $this->db->query($sql);
        $array = $query->fetchAll();
        if ($array) {
            return $array;
        } else {
            return;
        }
    }

    public function frequenciaTea($id_inst = NULL, $mes = null, $ano = NULL, $id_cad = NULL) {
        if (!empty($id_cad)) {
            $id_cad = " and c.id_cad = $id_cad ";
        }
        if (!empty($id_inst)) {
            $id_inst = " and f.fk_id_inst = $id_inst ";
        }
        if (!empty($mes)) {
            $mes = " and f.mes = $mes ";
        }
        if (!empty($ano)) {
            $ano = " and f.ano = $ano ";
        }
        $fields = "f.id_fr, f.fk_id_cad, f.rse, f.periodo, i.n_inst, "
                . "f.motivo,f.fk_id_inst, f.fk_id_inst as id_inst, f.cancelado, "
                . " p.n_pessoa, r.n_pessoa as aluno, cad_pmb, "
                . "f.horas, f.periodo, f.dia, f.mes, f.ano, f.v_dia, f.v_hora , "
                . " t.n_turma, t.id_turma";
        $sql = 'SELECT ' . $fields . '  FROM `cadam_freq_tea` f '
                . ' join cadam_cadastro c on c.id_cad = f.fk_id_cad '
                . ' join pessoa p on p.id_pessoa = c.fk_id_pessoa '
                . ' join pessoa r on r.id_pessoa = f.rse '
                . ' join ge_turma_aluno ta on ta.fk_id_pessoa = r.id_pessoa '
                . ' join ge_turmas t on t.id_turma = ta.fk_id_turma '
                . ' join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl '
                . ' Join instancia i on i.id_inst = f.fk_id_inst '
                . ' WHERE pl.at_pl = 1 '
                . $id_inst . " "
                . $id_cad
                . " $ano "
                . " $mes"
                . " order by ano,mes,dia desc, n_pessoa asc";
        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        return $array;
    }

    public function trabalhado($id_cad, $mes = NULL, $ano = NUll) {
        if (empty($ano)) {
            $ano = date("Y");
        }

        $fields = " f.dia, f.horas, concat('Prof: ',p.n_pessoa) as n_pessoa, concat('RM:', f.rm) as rm, "
                . " f.periodo, f.fk_id_cargo, f.turmas, i.n_inst, m.n_mot, 'Simples' as tipo ";
        $sql = "SELECT $fields FROM cadam_freq f "
                . " join ge_funcionario fu on fu.rm = f.rm "
                . " join pessoa p on p.id_pessoa = fu.fk_id_pessoa "
                . " join instancia i on i.id_inst = f.fk_id_inst "
                . " join cadam_motivo m on m.id_mot = f.fk_id_mot "
                . " WHERE `fk_id_cad` = $id_cad "
                . " AND `mes` = $mes "
                . " AND `ano` = $ano "
                . " AND f.cancelado is NULL "
                . " order by dia";
        $query = $this->db->query($sql);
        $dados = $query->fetchAll();

        $fields = " f.dia, f.horas, concat('Alu: ',p.n_pessoa) as n_pessoa, concat('RSE:', f.rse) as rm,"
                . " f.periodo, NULL as fk_id_cargo, f.motivo as n_mot, i.n_inst, 'TEA' as tipo, f.fk_id_turma as turmas ";
        $sql = "SELECT $fields FROM cadam_freq_tea f "
                . " join pessoa p on p.id_pessoa = f.rse "
                . " join instancia i on i.id_inst = f.fk_id_inst "
                . " WHERE `fk_id_cad` = $id_cad "
                . " AND `mes` = $mes "
                . " AND `ano` = $ano "
                . " order by dia";
        $query = $this->db->query($sql);
        $tea = $query->fetchAll();

        if (!empty($tea)) {
            $dados = array_merge($dados, $tea);
        }

        return $dados;
    }

    public function diasEHoras($trabalhados) {
        foreach ($trabalhados as $v) {

            $dias[$v['dia']] = $v['dia'];
            @$trab['contaHoras'] += $v['horas'];
        }
        @$trab['contaDias'] = count($dias);
        return $trab;
    }

    public function extrato($id_cad, $mes = NULL, $ano = NUll) {
        if (empty($ano)) {
            $ano = date("Y");
        }

        $fields = "f.dia, i.n_inst, concat('Prof: ',p.n_pessoa) as n_pessoa, concat('RM:', f.rm) as rm, f.turmas, f.periodo, "
                . " f.v_hora, f.v_dia,  f.horas, m.n_mot, 'Horas/aulas (Simples)' as tipo, 'simples' as tp ";
        $sql = "SELECT $fields FROM cadam_freq f "
                . " join ge_funcionario fu on fu.rm = f.rm "
                . " join pessoa p on p.id_pessoa = fu.fk_id_pessoa "
                . " join instancia i on i.id_inst = f.fk_id_inst "
                . " join cadam_motivo m on m.id_mot = f.fk_id_mot "
                . " WHERE `fk_id_cad` = $id_cad "
                . " AND `mes` = $mes "
                . " AND `ano` = $ano "
                . " AND f.cancelado is NULL "
                . " order by dia";
        $query = $this->db->query($sql);
        $dados = $query->fetchAll();

        $fields = "f.dia, i.n_inst, concat('Alu: ',p.n_pessoa) as n_pessoa, concat('RSE:', f.rse) as rm, f.motivo as n_mot, f.periodo, "
                . " f.v_hora, f.v_dia,  f.horas, 'Horas/aulas (TEA)' as tipo, 'tea' as tp , f.fk_id_turma as turmas";
        $sql = "SELECT $fields FROM cadam_freq_tea f "
                . " join pessoa p on p.id_pessoa = f.rse "
                . " join instancia i on i.id_inst = f.fk_id_inst "
                . " WHERE `fk_id_cad` = $id_cad "
                . " AND `mes` = $mes "
                . " AND `ano` = $ano "
                . " order by dia";
        $query = $this->db->query($sql);
        $tea = $query->fetchAll();
        if (!empty($tea)) {
            $dados = array_merge($dados, $tea);
        }

        return $dados;
    }

    public function escolaFechaAbre() {
        $sql = "select e.cie_escola, i.n_inst, i.id_inst, professor from ge_escolas e "
                . "join instancia i on i.id_inst = e.fk_id_inst "
                . "left join cadam_fecha c on c.id_inst = i.id_inst ";
        $query = $this->db->query($sql);
        return $array = $query->fetchAll();
    }

    public function geraclassificacao() {

        $sql = "SELECT id_cargo FROM cadam_cargo";
        $query = $this->db->query($sql);
        $cargo = $query->fetchAll();

        foreach ($cargo as $v) {
            $classi[$v['id_cargo']] = 0;
        }

        foreach ($cargo as $v) {

            $sql = "SELECT * FROM cadam_classificacao_cargo_geral"
                    . " WHERE fk_id_cargo = '" . $v['id_cargo'] . "'"
                    . " ORDER BY ordem_sel, class";

            $query = $this->db->query($sql);
            $c = $query->fetchAll();

            foreach ($c as $cc) {
                $classi[$v['id_cargo']] = $classi[$v['id_cargo']] + 1;

                $wsql = "UPDATE cadam_classificacao_cargo_geral SET class_geral = '" . $classi[$v['id_cargo']] . "', rodizio = '1'"
                        . " WHERE id = '" . $cc['id'] . "'";

                $query = pdoSis::getInstance()->query($wsql);
            }
        }
    }

    public function pegarelatorio($dt_inicio, $dt_fim) {

        $dt_inicio = data::converteUS($dt_inicio);
        $dt_fim = data::converteUS($dt_fim);

        $sql = "SELECT *  FROM dttie_suporte_trab"
                . " WHERE (dt_sup BETWEEN '" . $dt_inicio . "' AND " . "'" . $dt_fim . "')"
                . " AND tipo_sup = 76";

        $query = $this->db->query($sql);
        $d = $query->fetchAll();
        
        return $d;
    }

}
