<?php

class gestaoModel extends MainModel {

    public $escola;
    public $db;

    /**
     * Construtor para essa classe
     *
     * Configura o DB, o controlador, os parâmetros e dados do usuário.
     *
     * @since 0.1
     * @access public
     * @param object $this->db Objeto da nossa conexão PDO
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
        /**
         * se form escola seta as informações
         */
######################### NOVO #################################################
        if ($this->db->sqlKeyVerif('editEscola')) {
            $id = $this->db->ireplace('instancia', $_POST[1]);
            $esc = sql::get('ge_escolas', 'fk_id_inst', ['fk_id_inst' => $id], 'fetch')['fk_id_inst'];
            if (empty($esc)) {
                $this->db->ireplace('ge_escolas', ['fk_id_inst' => $id]);
            }
        }
        if (DB::sqlKeyVerif('clonarClasse')) {
            $id_turma = $this->db->ireplace('ge_turmas', $_POST[1], 1);
            $sql = "SELECT ta.fk_id_pessoa, ta.origem_escola FROM ge_turma_aluno ta "
                    . " join pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                    . " WHERE `fk_id_turma` = " . @$_POST['id_turma']
                    . " AND `situacao` LIKE 'Frequente' "
                    . " order by p.n_pessoa ";
            $query = $this->db->query($sql);
            $array = $query->fetchAll();
            $periodo_letivo = sql::get('ge_periodo_letivo', 'n_pl', ['id_pl' => $_POST[1]['fk_id_pl']], 'fetch')['n_pl'];
            $c = 1;
            $sg_ciclo_sg_curso = sql::get(['ge_ciclos', 'ge_cursos'], 'sg_curso,sg_ciclo, n_ciclo', ['id_ciclo' => $_POST[1]['fk_id_ciclo']], 'fetch');
            $codigo = $sg_ciclo_sg_curso['sg_curso'] . $_POST[1]['periodo'] . $sg_ciclo_sg_curso['sg_ciclo'] . $_POST[1]['letra'];

            foreach ($array as $vv) {

                $v['fk_id_pessoa'] = $vv['fk_id_pessoa'];
                $v['origem_escola'] = $vv['origem_escola'];
                $v['id_turma_aluno'] = NULL;
                $v['situacao'] = 'Frequente';
                $v['codigo_classe'] = $codigo;
                $v['fk_id_turma'] = $id_turma;
                $v['periodo_letivo'] = $periodo_letivo;
                $v['fk_id_inst'] = tool::id_inst();
                $v['chamada'] = $c++;
                $v['dt_matricula'] = date("Y-m-d");
                $this->db->ireplace('ge_turma_aluno', $v, 1);
            }
            $_POST['periodoLetivo'] = 2;
        }

        if (DB::sqlKeyVerif('periodo_letivo')) {
            $id = $this->db->ireplace('ge_periodo_letivo', $_POST[1], 1);
            $sql = "DELETE FROM `ge_periodo_ciclo` WHERE `fk_id_pl` = '$id'";
            $query = $this->db->query($sql);
            $array = $query->fetchAll();
            $insert['fk_id_pl'] = $id;
            if (!empty($_POST[2])) {
                foreach ($_POST[2] as $ci) {
                    $insert['fk_id_ciclo'] = $ci;
                    $this->db->ireplace('ge_periodo_ciclo', $insert, 1);
                }
            }
        }

        if (DB::sqlKeyVerif("transfSolicita")) {

            $sql = "select ta.fk_id_pessoa,  p.n_pessoa, t.codigo as codigo_classe_o, t.id_turma as turmaid, ta.chamada, t.periodo_letivo  "
                    . "from pessoa p"
                    . " join ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa "
                    . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
                    . " where id_turma_aluno = " . $_POST['id_turma_aluno'];
            $query = $this->db->query($sql);
            $array = $query->fetch();
            foreach ($array as $k => $v) {
                if (!is_numeric($k)) {
                    $insert[$k] = $v;
                }
            }
            if ($this->verificaalunotrans($insert['fk_id_pessoa']) == "Pendente") {
                tool::alert("Aluno com Transferência Pendente");
            } else {
                $insert['cod_inst_o'] = tool::id_inst();
                $insert['n_escola_origem'] = user::session('n_inst');
                $insert['cod_inst_d'] = $_POST['op'];
                $insert['n_escola_destino'] = sql::get('instancia', 'n_inst', ['id_inst' => $_POST['op']], 'fetch')['n_inst'];
                $insert['dt_solicitacao'] = date("Y-m-d");
                $insert['status_transf'] = "Ag. Aprovação";
                $this->db->ireplace('ge_transf_aluno', $insert);

                log::logSet("Solicita transferência de aluno (RSE " . $insert['fk_id_pessoa'] . ")");
            }
        }
        if (DB::sqlKeyVerif('reclassificado')) {
            $this->remanReclac($_POST['id_turma_aluno'], $_POST['id_turma'], $_POST['situacao']);
        }

        if (DB::sqlKeyVerif('naoApd')) {

            $insert['id_apd'] = $_POST['id_apd'];
            $insert['status_apd'] = 'Não';

            $this->db->ireplace('ge_aluno_apd', $insert, 1);

            $sql = "UPDATE pessoa SET deficiencia = 'Não' WHERE id_pessoa = '" . $_POST['id_pessoa'] . "'";
            $query = pdoSis::getInstance()->query($sql);
        }

        if (DB::sqlKeyVerif('cadastroApd')) {
            $sql = "SELECT * FROM ge_turma_aluno ta"
                    . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                    . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                    . " WHERE ta.fk_id_pessoa = '" . $_POST['id_pessoa'] . "'"
                    . " AND ta.fk_id_inst = '" . tool::id_inst() . "'"
                    . " AND ta.situacao = '" . 'Frequente' . "' AND pl.at_pl = 1"
                    . " AND t.fk_id_ciclo NOT IN('32')";
            $query = $this->db->query($sql);
            $res = $query->fetchAll();
            if (!empty($res)) {
                foreach ($res as $v) {
                    $insert['fk_id_pessoa'] = $v['fk_id_pessoa'];
                    $insert['fk_id_inst'] = $v['fk_id_inst'];
                    $insert['fk_id_turma'] = $v['fk_id_turma'];

                    $this->db->ireplace('ge_aluno_apd', $insert, 1);

                    $sql = "UPDATE pessoa SET deficiencia = 'Sim' WHERE id_pessoa = '" . $v['fk_id_pessoa'] . "'";
                    $query = pdoSis::getInstance()->query($sql);
                }
            } else {
                tool::alert("Aluno não encontrado na turma  !!!! Operação Cancelada");
            }
        }
######################### FIM NOVO #############################################

        if ($_SESSION['userdata']['tipo'] == 1) {
            $this->escola = escolas::get($_SESSION['userdata']['id_inst'], 'id_inst');
        }

        if (DB::sqlKeyVerif("supervisao")) {
            $super = $_POST[1];
            $super['rm'] = explode(' - ', $super['rm'])[1];
            if (!empty(@$_POST['fk_id_tp_ens'])) {
                $super['fk_id_tp_ens'] = '|' . implode('|', $_POST['fk_id_tp_ens']) . '|';
                $this->db->ireplace('ge_supervisao', $super);
                $_POST = NULL;
            } else {
                tool::alert("Escolha pelo menos um Segmento.");
                $_POST['id_super'] = $_POST[1]['id_super'];
            }
        }

//  if (DB::sqlKeyVerif("SalavEscola")) {
        if (!empty($_POST['SalavEscola'])) {

            $esc = $_POST[1];
            if (!empty(@$_POST['fk_id_tp_ens'])) {
                $esc['fk_id_tp_ens'] = '|' . implode('|', $_POST['fk_id_tp_ens']) . '|';
            }
            $this->db->ireplace('ge_escolas', $esc);
        }

        if (DB::sqlKeyVerif("setPred")) {

            $alert = empty(@$_POST['setPredio']) ? NULL : 1;

            $predio = $_POST[1];
            $id = $this->db->ireplace('predio', $predio, $alert);
            if (!empty($_POST[2])) {
                $instPredio = $_POST[2];
                if (!empty(@$_POST['setPredio'])) {
                    $instPredio['fk_id_predio'] = (empty($instPredio['fk_id_predio']) ? $id : $instPredio['fk_id_predio']);
                    $this->db->ireplace('instancia_predio', $instPredio);
                } else {
                    if (!empty($instPredio['id_ip'])) {
                        $this->db->delete('instancia_predio', 'id_ip', $instPredio['id_ip']);
                    }
                }
            }


            if (!empty($_POST['tel_esc'])) {
                foreach ($_POST['tel_esc'] as $k => $v) {
                    if (!empty($v)) {
                        $sql = "SELECT *  FROM `telefones` WHERE `fkid` = " . $id . " AND `num` LIKE '$v' and fk_id_tp = 1";
                        $query = pdoSis::getInstance()->query($sql);
                        $array = $query->fetch(PDO::FETCH_ASSOC);
                        if (empty($array)) {
                            $sql = "REPLACE INTO `telefones` (`id_tel`, `fkid`, `fk_id_tp`, `num`, `tipo`) VALUES ("
                                    . "'" . @$_POST['id_tel'][$k] . "', "
                                    . "'" . $id . "', "
                                    . "'1', "
                                    . "'$v', "
                                    . "'" . $_POST['tipo'][$k] . "'"
                                    . ");";
                            $query = pdoSis::getInstance()->query($sql);
                        } else {
                            $sql = "UPDATE `telefones` SET `num` = '$v', `tipo` = '" . $_POST['tipo'][$k] . "' WHERE `fkid` = " . $id . " AND `num` LIKE '$v'";
                            $query = pdoSis::getInstance()->query($sql);
                        }
                    }
                }
            }
            log::logSet("Alterou dados da Escola");

            unset($_POST[1]);
            unset($_POST[2]);
        }


        if (DB::sqlKeyVerif('fechaClasses')) {
            $sql = "update ge_escolas set classe = " . $_POST['classe'];
            $query = pdoSis::getInstance()->query($sql);
        }


        if (!empty($_POST['atz_chamada_m'])) {

            @$wturma = $_POST['turma'];

            if (!empty($_POST['chamada_nr'])) {
                $wconta = 0;
                foreach ($_POST['chamada_nr'] as $v) {

                    @$conta[$v]++;
                    if ($conta[$v] > 1) {
                        tool::alert("O número $v está repetido");
                        $wconta = 1;
                    }
                }

                if ($wconta == 0) {
                    foreach ($_POST['chamada_nr'] as $k => $v) {

                        $wsql = "Update ge_turma_aluno Set chamada =  $v Where fk_id_pessoa = $k and fk_id_turma = $wturma";
                        $query = $this->db->query($wsql);
                    }
                    tool::alert("Operação Efetuada com Sucesso");
                }
            }
        }

        if (@$_POST['atz_chamada_a'] == "Alterar Chamada Automática") {

            @$wturma = $_POST['turma'];

            if (!empty($wturma)) {

                $wsql = sql::get(['pessoa', 'ge_turma_aluno'], '*', "Where fk_id_turma ='" . $_POST['turma'] . "' order by n_pessoa");
                $this->ordena_chamada($wsql, $_POST['turma']);
                tool::alert("Operação Efetuada com Sucesso");
                log::logSet("Atualiza chamada automática");
            } else {

                tool::alert("Erro !!!! Operação Cancelada");
            }
        }

        if (@$_POST['atz_ch'] == "Salvar") {

            @$wturma = $_POST['turma'];

            if (!empty($_POST['chamada_nr'])) {
                $wconta = 0;
                foreach ($_POST['chamada_nr'] as $v) {

                    @$conta[$v]++;
                    if ($conta[$v] > 1) {
                        tool::alert("O número $v está repetido");
                        $wconta = 1;
                    }
                }

                if ($wconta == 0) {
                    foreach ($_POST['chamada_nr'] as $k => $v) {

                        $wsql = "Update ge_turma_aluno Set chamada =  $v Where id_turma_aluno = $k and fk_id_turma = $wturma";
                        $query = $this->db->query($wsql);
                        if ($query) {
                            log::logSet("Atualiza chamada manual");
                        }
                    }
                    tool::alert("Operação Efetuada com Sucesso");
                }
            }
        }


        if (!empty($_POST['alocacao'])) {

            @$wsel = $_POST['sel'];

            if (!empty($wsel)) {
                foreach ($wsel as $v) {
                    $wsql = "Update ge_aux_alocacao Set turma = '$_POST[turma]', status_alocacao = True Where id_aluno = $v";
                    $query = $this->db->query($wsql);

                    $wperiodo = date('Y');
                    $date = date('Y-m-d');

                    $wcod_classe = sql::get(['ge_turmas'], '*', 'Where fk_id_inst=' . tool::id_inst() . " and codigo= '" . $_POST['turma'] . "'" . "and periodo_letivo ='" . $wperiodo . "'", 'fetch');

                    $wsql2 = "Insert Into ge_turma_aluno(codigo_classe, fk_id_turma, periodo_letivo, fk_id_pessoa, fk_id_inst, situacao, dt_matricula, turma_status)
                         Values ('" . $_POST['turma'] . "', '" . $wcod_classe['id_turma'] . "', '" . $wperiodo . "', '" . $v . "', '" . $wcod_classe['fk_id_inst'] . "', 'Frequente', '" . $date . "','M')";

                    $query = $this->db->query($wsql2);

                    $wsql3 = sql::get(['pessoa', 'ge_turma_aluno'], '*', 'Where fk_id_inst=' . tool::id_inst() . " and codigo_classe= '" . $_POST['turma'] . "' order by n_pessoa");
                    $this->ordena_chamada($wsql3, $wcod_classe['id_turma']);
                }
                tool::alert("Operação Efetuada com Sucesso");
                log::logSet("Alocação de alunos");
            } else {
                tool::alert("Favor selecionar Aluno(s)");
            }
        }

        if (DB::sqlKeyVerif("excluir")) {

            $wperiodo = date('Y');
            @$wsel = $_POST['sel'];

            if (!empty($wsel)) {
                foreach (@$wsel as $w) {

                    $wsql = "Delete From ge_turma_aluno Where fk_id_pessoa = '" . $w . "' and codigo_classe = '" . $_POST['turma'] . "'";
                    $query = $this->db->query($wsql);

                    $wcod_classe = sql::get(['ge_turmas'], '*', 'Where fk_id_inst=' . tool::id_inst() . " and codigo= '" . $_POST['turma'] . "'" . "and periodo_letivo ='" . $wperiodo . "'", 'fetch');

                    $wsql2 = sql::get(['pessoa', 'ge_turma_aluno'], '*', 'Where fk_id_inst=' . tool::id_inst() . " and fk_id_turma= '" . $wcod_classe['id_turma'] . "' order by n_pessoa");

                    $this->ordena_chamada($wsql2, $wcod_classe['id_turma']);

                    $wsql3 = "Update ge_aux_alocacao Set status_alocacao = False Where id_aluno = '" . $w . "'";
                    $query = $this->db->query($wsql3);
                }
                tool::alert("Operação Efetuada com Sucesso");
                log::logSet("Exclui aluno");
            } else {
                tool::alert("Favor selecionar Aluno(s)");
            }
        }


        if (!empty($_POST['mat_aluno'])) {

            $dados = $this->ultimo_turmaNovaChamada($_POST['turma']);
            $insert['codigo_classe'] = $dados['codigo_classe'];
            $insert['fk_id_turma'] = $_POST['turma'];
            $insert['periodo_letivo'] = $dados['periodo_letivo'];
            $insert['fk_id_pessoa'] = $_POST['id_pessoa'];
            $insert['fk_id_inst'] = tool::id_inst();
            $insert['chamada'] = $dados['chamada'];
            $insert['situacao'] = 'Frequente';
            $insert['dt_matricula'] = date("Y-m-d");
            $insert['turma_status'] = 'M';

            $this->db->ireplace('ge_turma_aluno', $insert);
        }



        if (DB::sqlKeyVerif('remanejar')) {

// Verifica a classe 
//Dados
            $wturma = $_POST['turma'];
            $data = date('Y-m-d');
            $dados = explode('|', $_POST['al']);
            $idaluno = $dados[0];
            $wch = $dados[1];
            $cod = $dados[3];
            $esc = $dados[4];

            $obs = $_POST['obs'];

            $dadosn = explode('|', $_POST['opt']);
            $turman = $dadosn[0];
            $codcn = $dadosn[1];

            $chdn = $this->pega_ultimo_nr($turman);
            $chn = $chdn[1];
            $wperiodo = date('Y');
// remanejado               
            $wsql = "Update ge_turma_aluno Set situacao = 'Remanejado', dt_transferencia = '" . $data . "',"
                    . " justificativa_transf = '$obs', destino_escola = '$turman', turma_status = 'I'"
                    . " Where fk_id_turma = '" . $wturma . "' and fk_id_pessoa = '" . $idaluno . "' and chamada = '$wch'";
            $query = $this->db->query($wsql);

// Frequente
            $wsql2 = "Insert Into ge_turma_aluno(codigo_classe, fk_id_turma, periodo_letivo, fk_id_pessoa, fk_id_inst, chamada, situacao, dt_matricula, origem_escola, turma_status)"
                    . " Values ('" . $codcn . "', '" . $turman . "', '" . $wperiodo . "', '" . $idaluno . "', '" . $esc . "', '" . $chn . "', 'Frequente', '" . $data . "', '" . $cod . "', 'M')";

            $query = $this->db->query($wsql2);

            tool::alert("Operação Efetuada com Sucesso");
            log::logSet("Remaja aluno");
        }




        if (@$_POST['aprova'] == "Aprovar") {

            $data = date('Y-m-d');
            $a = $_POST['id_transf'];

            if (!empty($a)) {

                $wpes = sql::get(['ge_transf_aluno'], '*', "Where id_transf='" . $a . "'", 'fetch');

                if ($wpes['status_transf'] == 'Ag. Aprovação') {

                    $sal = "Update ge_transf_aluno Set status_transf = 'Aprovado', dt_aprovacao = '$data'"
                            . " where id_transf = '" . $a . "'";

                    $query = $this->db->query($sal);
                    tool::alert("Operação Efetuada com Sucesso");
                    log::logSet("Aprova transferência de aluno");
                } else {
                    tool::alert("Situação inválida");
                }
            }
        }


        if (@$_POST['liberar'] == "Liberar Matricula") {

            $data = date('Y-m-d');
            @$a = $_POST['idtra'];

            if (!empty($a)) {

                $wpes = sql::get(['ge_transf_aluno'], '*', "Where id_transf='" . $a . "'", 'fetch');

                if ($wpes['status_transf'] == 'Aprovado') {

                    $sal = "Update ge_transf_aluno Set status_transf = 'Matricula Liberada', dt_liberacao = '$data'"
                            . " where id_transf = '" . $a . "'";

                    $query = $this->db->query($sal);

                    tool::alert("Operação Efetuada com Sucesso");
                    log::logSet("Libera transferência");
                } else {
                    tool::alert("Situação inválida");
                }
            }
        }


        if (@$_POST['del'] == "Cancelar") {

            $data = date('Y-m-d');
            @$a = $_POST['idtra'];

            $sal = "Update ge_transf_aluno Set status_transf = 'Cancelado', dt_cancelamento = '$data'"
                    . " where id_transf = '" . $a . "'";

            $query = $this->db->query($sal);
            log::logSet("Cancela solicitação de transferência");
            tool::alert("Operação Efetuada com Sucesso");
        }
        if (!empty($_POST['transfereEscola'])) {
            $this->matriculaTransf();
        }
    }

    public function atztabela() {

        $a = "Update ge_aux_alocacao Set status_alocacao = False Where ge_aux_alocacao.cie = '" . tool::cie() . "'";
        $query = $this->db->query($a);

        $a = "Update ge_aux_alocacao"
                . " Join ge_turma_aluno on ge_turma_aluno.fk_id_pessoa = ge_aux_alocacao.id_aluno"
                . " Set ge_aux_alocacao.turma = ge_turma_aluno.codigo_classe, ge_aux_alocacao.status_alocacao = true Where ge_aux_alocacao.cie = '" . tool::cie() . "'";
        $query = $this->db->query($a);
    }

    public function ordena_chamada($wsql, $turma) {

        $ver = sql::get('ge_turmas', 'status', 'where fk_id_inst=' . tool::id_inst() . " and id_turma ='" . $turma . "'", 'fetch');

        $wchamada = 0;
        foreach ($wsql as $a) {
            $wchamada += 1;
            $wgrava = "Update ge_turma_aluno Set chamada = '$wchamada' Where id_turma_aluno = '" . $a['id_turma_aluno'] . "'";
            $query = $this->db->query($wgrava);
        }
    }

    public function alunoMat($id_pessoa) {

        $da = "SELECT * FROM pessoa"
                . " JOIN ge_turma_aluno on ge_turma_aluno.fk_id_pessoa = pessoa.id_pessoa"
                . " JOIN instancia on instancia.id_inst = ge_turma_aluno.fk_id_inst "
                . "WHERE pessoa.id_pessoa = '" . $_POST['id_pessoa'] . "' "
                . "and situacao like 'Frequente'";

        $query = $this->db->query($da);
        $array = $query->fetchAll();

        return $array;
    }

    public function segPorSetor($fk_id_tp_ens) {
        foreach (explode('|', $fk_id_tp_ens) as $v) {
            if (!empty($v)) {
                $segm[] = $v;
            }
        }
        $sql = "SELECT * FROM `ge_tp_ensino` WHERE `id_tp_ens` IN (" . implode(',', $segm) . ")";
        $query = $this->db->query($sql);
        $array = $query->fetchAll();
        foreach ($array as $v) {
            @$seg .= $v['n_tp_ens'] . '<br />';
        }
        if (!empty($seg)) {
            return substr($seg, 0, -6);
        } else {
            return;
        }
    }

    public function listSuper() {
        $sql = "select n_super, ge_supervisao.rm, n_pessoa, id_super, fk_id_tp_ens from ge_supervisao "
                . "join ge_funcionario on ge_funcionario.rm = ge_supervisao.rm "
                . "join pessoa on pessoa.id_pessoa = ge_funcionario.fk_id_pessoa "
                . "order by fk_id_tp_ens, n_super";
        $query = $this->db->query($sql);
        $array = $query->fetchAll();
        foreach ($array as $k => $v) {
            $array[$k]['seg'] = $this->segPorSetor($v['fk_id_tp_ens']);
        }
        $array = formulario::editDel($array, 'ge_supervisao', 'id_super');

        $form['array'] = $array;
        $form['fields'] = [
            'Segmentos' => 'seg',
            'Setor' => 'n_super',
            'Supervisor' => 'n_pessoa',
            'RM' => 'rm',
            '||2' => 'del',
            "||1" => 'edit'
        ];

        tool::relatSimples($form);
    }

    /**
     * lista tipo de ensino
     */
    public function listTpEnsino() {
        $array = sql::get('ge_tp_ensino', '*', ['>' => 'n_tp_ens']);

        $array = formulario::editDel($array, 'ge_tp_ensino', 'id_tp_ens');
        foreach ($array as $k => $v) {
            $array[$k]['acesso'] = formulario::submit('Cursos', NULL, ['id_tp_ens' => $v['id_tp_ens'], 'aba' => 'cursos']);
        }

        $form['array'] = $array;
        $form['fields'] = [
            'Segmento' => 'n_tp_ens',
            'Sigla' => 'sigla',
            '||2' => 'del',
            "||1" => 'edit',
            '||3' => 'acesso'
        ];

        tool::relatSimples($form);
    }

    /**
     * devolve supervisor
     * @param type $id_super
     * @return type
     */
    public function getSuper($id_super) {
        $sql = "select n_super, ge_supervisao.rm, n_pessoa, id_super, fk_id_tp_ens from ge_supervisao "
                . "join ge_funcionario on ge_funcionario.rm = ge_supervisao.rm "
                . "join pessoa on pessoa.id_pessoa = ge_funcionario.fk_id_pessoa "
                . "join ge_tp_ensino on ge_tp_ensino.id_tp_ens = ge_supervisao.fk_id_tp_ens "
                . "where ge_supervisao.id_super = $id_super ";
        $query = $this->db->query($sql);
        $array = $query->fetch();
        return $array;
    }

    /**
     * 
     * @param type $id_inst
     * @param type $searchlista predios por instancia
     */
    public function listPredio($id_inst, $search = NULL) {
        if (empty($search)) {
            $predios = sql::get(['predio', 'instancia_predio'], '*', ['fk_id_inst' => $id_inst, '>' => 'n_predio']);
        } else {
            $predios = sql::get('predio', '*', ['n_predio' => '%' . $search . '%', '>' => 'n_predio']);
        }
        foreach ($predios as $k => $v) {
            @$predios[$k]['sede'] = tool::simnao($v['sede']);
            $predios[$k]['ativo'] = tool::simnao($v['ativo']);
            $predios[$k]['edit'] = formulario::submit('Editar', NULL, ['id_predio' => $v['id_predio'], 'id_inst' => $id_inst, 'aba' => 'predio']);
            if (empty($search)) {
                $predios[$k]['sala'] = formulario::submit('Salas', NULL, ['id_predio' => $v['id_predio'], 'id_inst' => $id_inst, 'aba' => 'sala']);
            }
        }
        $form['array'] = $predios;
        $form['fields'] = [
            'Nome do Prédio' => 'n_predio',
            'Sigla' => 'sigla',
            'Sede' => 'sede',
            'Ativo' => 'ativo',
            "||1" => 'edit',
            '||2' => 'sala'
        ];

        tool::relatSimples($form);
    }

    /**
     * 
     * @param type $id_prediolista salas por predio
     */
    public function lisSalas($id_predio = NULL) {
        if (!empty($id_predio)) {
            $salas = predio::salas($id_predio, 'id_sala, n_sala, n_ts, piso, cadeirante, largura, comprimento, alunos_sala', ['>' => 'id_sala']);
            $sqlkey = $this->db->sqlKey('salas', 'delete');
            foreach ($salas as $k => $v) {

                @$salas[$k]['tm'] = round(($v[largura] * $v['comprimento']), 2) . ' m²';
                @$salas[$k]['razao'] = round((@$salas[$k]['tm'] / $v['alunos_sala']), 2) . ' m²';
                @$salas[$k]['cadeirante'] = tool::simnao($v['cadeirante']);
                @$salas[$k]['edit'] = formulario::submit('Editar', NULL, ['id_predio' => @$id_predio, 'id_inst' => @$_POST['id_inst'], 'id_sala' => $v['id_sala'], 'aba' => 'sala', 'setSalas' => 1]);
                @$salas[$k]['del'] = formulario::submit('Excluir', $sqlkey, ['1[id_sala]' => $v['id_sala'], 'id_predio' => @$_POST['id_predio'], 'id_inst' => @$_POST['id_inst'], 'aba' => 'sala']);
            }

            $form['array'] = $salas;
            $form['fields'] = [
                'Nome' => 'n_sala',
                'Finalidade' => 'n_ts',
                'Piso' => 'piso',
                'Acessibilidade' => 'cadeirante',
                'Capacidade Física' => 'alunos_sala',
                'Razao Aluno/m²' => 'razao',
                'Metragem' => 'tm',
                '||1' => 'del',
                '||2' => 'edit'
            ];
            tool::relatSimples($form);
        }
    }

    /**
     * lista cursos por segmento
     * @param type $id_tp_ens
     */
    public function listCursos($id_tp_ens) {
        $curso = sql::get('ge_cursos', '*', ['fk_id_tp_ens' => $id_tp_ens, '>' => 'n_curso']);

        $array = formulario::editDel($curso, 'ge_cursos', 'id_curso', ['aba' => 'cursos', 'id_tp_ens' => $id_tp_ens]);
        foreach ($array as $k => $v) {
            $array[$k]['acesso'] = formulario::submit('Ciclos', NULL, ['id_curso' => $v['id_curso'], 'id_tp_ens' => $id_tp_ens, 'aba' => 'ciclos']);
            $array[$k]['ativo'] = tool::simnao($v['ativo']);
        }

        $form['array'] = $array;
        $form['fields'] = [
            'Curso' => 'n_curso',
            'Sigla' => 'sg_curso',
            'Ativo' => 'ativo',
            '||2' => 'del',
            "||1" => 'edit',
            '||3' => 'acesso'
        ];
        tool::relatSimples($form);
    }

    public function listAreas() {
        $disc = disciplina::areas();
        $disc = formulario::editDel($disc, 'ge_areas', 'id_area');
        $form['array'] = $disc;
        $form['fields'] = [
            'Área' => 'n_area',
            'Sigla' => 'sg_area',
            '||1' => 'del',
            '||2' => 'edit'
        ];

        tool::relatSimples($form);
    }

    public function listDisc() {
        $disc = disciplina::disc();
        $disc = formulario::editDel($disc, 'ge_disciplinas', 'id_disc', ['aba' => 'disc']);
        $form['array'] = $disc;
        $form['fields'] = [
            'Área' => 'n_disc',
            'Sigla' => 'sg_disc',
            'Área do Conhecimneto' => 'n_area',
            '||1' => 'del',
            '||2' => 'edit'
        ];

        tool::relatSimples($form);
    }

    public function listHab() {
        $hab = disciplina::hab();
        $hab = formulario::editDel($hab, 'ge_habilidades', 'id_hab', ['aba' => 'hab']);
        $form['array'] = $hab;
        $form['fields'] = [
            'Área do Conhacimento' => 'n_hab',
            'Sigla' => 'sg_hab',
            'Área do Conhecimneto' => 'n_area',
            '||1' => 'del',
            '||2' => 'edit'
        ];

        tool::relatSimples($form);
    }

    public function listGrades() {
        $grade = disciplina::grade();
        $grade = formulario::editDel($grade, 'ge_grades', 'id_grade', ['aba' => 'grade']);
        foreach ($grade as $k => $v) {
            $grade[$k]['ativo'] = tool::simnao($v['ativo']);
            $grade[$k]['acesso'] = formulario::submit('Alocar ' . $v['n_ta'], NULL, ['aba' => 'gd', 'id_grade' => $v['id_grade']]);
        }
        $form['array'] = $grade;
        $form['fields'] = [
            'Grade Curricular' => 'n_grade',
            'Avaliar por' => 'n_ta',
            'Ativo' => 'ativo',
            '||1' => 'del',
            '||2' => 'edit',
            '||3' => 'acesso'
        ];

        tool::relatSimples($form);
    }

    public function listAloca($id_grade) {
        $grade = disciplina::alocado($id_grade);

        $sqlkey = DB::sqlKey($grade['table'], 'delete');
        foreach ($grade as $k => $v) {
            if (is_array($v)) {
                $grade_[$k]['del'] = formulario::submit('Apagar', $sqlkey, ['1[id_aloca]' => $v['id_aloca'], 'id_grade' => $id_grade, 'aba' => 'gd']);
                $grade_[$k]['edit'] = formulario::submit('Editar', NULL, ['id_aloca' => $v['id_aloca'], 'id_grade' => $id_grade, 'aba' => 'gd']);
                $grade_[$k]['dh'] = $v[$grade['nome']];
                $grade_[$k]['aulas'] = @$v['aulas'];
                $grade_[$k]['ordem'] = @$v['ordem'];
                $grade_[$k]['nc'] = tool::simnao(@$v['nucleo_comum']);
            }
        }
        $form['array'] = @$grade_;
        $form['fields'] = [
            'Ordem' => 'ordem',
            $grade['tipo'] => 'dh',
            'Aulas' => 'aulas',
            'Núcleo Comum' => 'nc',
            '||1' => 'del',
            '||2' => 'edit'
        ];

        tool::relatSimples($form);
    }

    public function tipoEnsino() {
        $tp = sql::get('ge_tp_ensino');
        foreach ($tp as $v) {
            $tpe[$v['id_tp_ens']] = $v['n_tp_ens'];
        }
        return $tpe;
    }

    public function relat($nome, $tipo) {
        $array = escolas::liste($nome);
        $tipos = $this->tipoEnsino();
        if (!empty($array)) {
            $sqlkey = DB::sqlKey('ge_escolas', 'replace');
            foreach ($array as $k => $v) {
                $tipo_ = NULL;
                $tipo = explode('|', $v['fk_id_tp_ens']);
                foreach ($tipo as $t) {
                    if (!empty($t)) {
                        @$tipo_ .= $tipos[$t] . '; ';
                    }
                }
                @$tipo_ = substr(@$tipo_, 0, -2);
                if ($v['classe'] == 1) {
                    $array[$k]['fclas'] = formulario::submit('Abertas', $sqlkey, ['1[id_escola]' => $v['id_escola'], '1[classe]' => 0, 'n_inst' => @$_POST['n_inst']], NULL, NULL, 'Fechar todas as classes da ' . $v['n_inst'], 'btn btn-danger');
                } else {
                    $array[$k]['fclas'] = formulario::submit('Fechadas', $sqlkey, ['1[id_escola]' => $v['id_escola'], '1[classe]' => 1, 'n_inst' => @$_POST['n_inst']], NULL, NULL, 'Abrir todas as classes da ' . $v['n_inst'], 'btn btn-info');
                }
                $array[$k]['acesso'] = formulario::submit('Acessar', NULL, ['id_inst' => $v['id_inst'], 'aba' => 'escola'], NULL, NULL, NULL, 'btn btn-info');
                $array[$k]['edit'] = formulario::submit('Editar', NULL, $v, NULL, NULL, NULL, 'btn btn-info');
                $array[$k]['ativo'] = tool::simnao($v['ativo']);
                $array[$k]['tipo'] = $tipo_;
            }
            $form['array'] = $array;
            $form['fields'] = [
                'Nome' => 'n_inst',
                'Tipo' => 'tipo',
                'ID' => 'id_inst',
                'Ativo' => 'ativo',
                'Classes Abrir/Fechar' => 'fclas',
                '||2' => 'edit',
                '||3' => 'acesso'
            ];

            tool::relatSimples($form);
        } else {
            echo 'Não encontrado';
        }
    }

    public function listGradeInclui($id_ciclo) {
        $incl = sql::get(['ge_curso_grade', 'ge_grades'], '*', ['fk_id_ciclo' => $id_ciclo]);
        if (!empty($incl)) {
            foreach ($incl as $k => $v) {
                $incl[$k]['padrao'] = tool::simnao($v['padrao']);
            }
            $incl = formulario::editDel($incl, 'ge_curso_grade', 'id_cg', ['id_curso' => $_POST['id_curso'], 'id_ciclo' => $id_ciclo, 'id_tp_ens' => $_POST['id_tp_ens'], 'aba' => 'grade']);
            $form['array'] = $incl;
            $form['fields'] = [
                'Grade Curricular' => 'n_grade',
                'Grade Padrão' => 'padrao',
                '||1' => 'del',
                '||3' => 'edit'
            ];
            tool::relatSimples($form);
        }
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
                tool::alert("Não há prédio cadastrado");
            }
        }
    }

    public function listTurma($id_inst, $periodo = NULL) {


        $t = turma::liste($id_inst, $periodo);

        $sqlkey = DB::sqlKey('ge_turmasxxxxx');
//        $sqlkey = DB::sqlKey('ge_turmas', 'delete');
        foreach ($t as $k => $v) {
            $vazia = sql::get('ge_turma_aluno', 'id_turma_aluno', ['fk_id_turma' => $v['id_turma']], 'fetch');
            if (empty($vazia)) {
                $t[$k]['del'] = formulario::submit('Excluir', $sqlkey, ['1[id_turma]' => $v['id_turma']]);
            } else {
                $t[$k]['del'] = '<img src="' . HOME_URI . '/views/_images/aluno.png"/>';
            }
            $t[$k]['periodoLetivo'] = $periodo;
            $t[$k]['edit'] = formulario::submit('Editar', NULL, ['id_turma' => $v['id_turma'], 'modal' => 1, 'periodoLetivo' => $periodo]);
            $t[$k]['status'] = turma::status($v['id_turma']);
            $t[$k]['ac'] = formulario::submit('Acessar', NULL, ['turma' => $v['id_turma'], 'periodoLetivo' => $periodo], HOME_URI . '/gestao/manutencaoclasse');
            if ($periodo == 1) {
                $v['clonar'] = 1;
                $t[$k]['clone'] = formulario::submit('Clonar', NULL, $v);
            }
        }

        $form['array'] = $t;
        $form['fields'] = [
            'ID' => 'id_turma',
            'Curso' => 'n_curso',
            'Código' => 'codigo',
            'Turma' => 'n_turma',
            'Prédio' => 'n_predio',
            'Sala' => 'n_sala',
            'Status' => 'status',
            '||1' => 'del',
            '||4' => 'clone',
            '||2' => 'edit',
            '||3' => 'ac'
        ];

        tool::relatSimples($form);
    }

    public function completadadossala($idturma) {

        $cab = sql::get(['ge_turmas', 'ge_ciclos'], '*', ['id_turma' => $idturma]);

        foreach ($cab as $b) {

            @$prof = $b['professor'];
            @$cod = $b['codigo'];
            @$periodo = $b['periodo_letivo'];
            @$desc = $b['n_turma'];
            @$dtr = $b['n_ciclo'];

            switch ($b['periodo']) {
                case "M":
                    $p = "Manhã";
                    $pp = "Matutino";
                    break;
                case "T":
                    $p = "Tarde";
                    $pp = "Vespertino";
                    break;
                case "I":
                    $p = "Integral";
                    $pp = "Integral";
                    break;
                case "G":
                    $p = "Geral";
                    $pp = "Integral";
                    break;
                case "N":
                    $p = "Noite";
                    $pp = "Noturno";
                    break;
            }

            switch (substr($b['codigo'], 0, 2)) {
                case "EB":
                case "EM":
                case "EI":
                    $ed = " da Educação Infantil";
                    break;
                case "EF":
                    $ed = " do Ensino Fundamental";
                    break;
                case "EJ":
                    $ed = " Eja do Ensino Fundamental";
                    break;
                case "AE":
                    $ed = " Ensino Fundamental";
                    break;
            }
        }

        $ppp = $cod . '|' . $periodo . '|' . $p . '|' . @$prof . '|' . @$pp . '|' . @$desc . '|' . $ed . '|' . $dtr;

        return $ppp;
    }

    public function contaaluno($idturma) {

        $s = $this->pegasituacaosed();

        $sql = "SELECT * FROM ge_situacao_sed";
        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        foreach ($array as $v) {
            $sit[$v['sit_layout']] = 0;
        }

        $sql = "SELECT situacao, COUNT(fk_id_inst) AS total FROM ge_turma_aluno"
                . " GROUP BY situacao, fk_id_turma"
                . " HAVING fk_id_turma = '" . $idturma . "'";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        if (!empty($array)) {
            foreach ($array as $v) {
                $sit[$s[$v['situacao']]] = $v['total'];
            }
        }

        return $sit;
    }

    public function wpegaferiados($mes, $dia, $tipoesc) {

        $tipo = substr($tipoesc, 0, 2);

        if ($tipo == 'EB') {
            $tipo = 'EM';
        }

        $ano = date('Y');

        $sql = "SELECT tipo_feriado FROM ge_feriados"
                . " WHERE dia = '" . $dia . "' AND mes = '" . $mes . "'"
                . " AND ano = '" . $ano . "' AND tipo_escola = '" . $tipo . "'";

        $query = $this->db->query($sql);
        $array = $query->fetch();

        if (!empty($array)) {
            $fer = $array['tipo_feriado'];
        } else {
            $fer = ' ';
        }

        return $fer;
    }

    public function matriculaTransf() {

        $data = date("Y-m-d");

        $w = $this->ultimo_turmaNovaChamada($_POST['opt']);
        $chnew = $w['chamada'];
        $wpes = sql::get(['ge_transf_aluno'], '*', "Where id_transf='" . $_POST['id_transf'] . "'", 'fetch');

        if ($wpes['status_transf'] == 'Matricula Liberada') {

            $sal = "Update ge_transf_aluno Set status_transf = 'Finalizado', dt_matricula = '" . $data . "', codigo_classe_d = '" . $_POST['opt'] . "'"
                    . " where id_transf = '" . $_POST['id_transf'] . "'";

            $query = $this->db->query($sal);
            //Matricular
            $mat = "Insert Into ge_turma_aluno(codigo_classe, fk_id_turma, periodo_letivo, fk_id_pessoa, fk_id_inst, chamada, situacao, dt_matricula, origem_escola, turma_status)"
                    . " Values ('" . $w['codigo_classe'] . "', '" . $_POST['opt'] . "', '" . $w['periodo_letivo'] . "', '" . $wpes['fk_id_pessoa'] . "', '" . $w['fk_id_inst'] . "', '" . $chnew . "', 'Frequente', '" . $data . "', '" . $wpes['n_escola_origem'] . "', 'M')";

            $query = $this->db->query($mat);
            //Transferir
            $tra = "Update ge_turma_aluno Set situacao = 'Transferido Escola', dt_transferencia = '" . $data . "',"
                    . " justificativa_transf = '" . @$_POST['motivo'] . "', destino_escola = '" . $wpes['n_escola_destino'] . "', turma_status = 'I'"
                    . " Where fk_id_turma = '" . $wpes['turmaid'] . "' and fk_id_pessoa = '" . $wpes['fk_id_pessoa'] . "' and chamada = '" . $wpes['chamada'] . "'";
            $query = $this->db->query($tra);

            $sql = "SELECT * FROM `ge_turma_aluno` WHERE `fk_id_pessoa` = " . $wpes['fk_id_pessoa'] . " AND `situacao` LIKE 'Frequente' ";
            $query = $this->db->query($sql);
            $act = $query->fetchAll();
            if (count($act) > 1) {
                $sql = "INSERT INTO `erro` (`id_erro`, `log`, `fk_id_inst`, `dt_erro`) VALUES (NULL, '$sql', '" . tool::id_inst() . "', '" . date("Y-m-d H:i:s") . "');";
                $query = $this->db->query($sql);
            }
            tool::alert("Operação Efetuada com Sucesso");
            log::logSet("Matricula aluno transferido");
        } else {
            tool::alert("Situação inválida");
        }
    }

    public function peganomeciclo($c = NULL) {

        $cic = [
            0 => '',
            1 => '1º Ano do Ensino Fundamental',
            2 => '2º Ano do Ensino Fundamental',
            3 => '3º Ano do Ensino Fundamental',
            4 => '4º Ano do Ensino Fundamental',
            5 => '5º Ano do Ensino Fundamental',
            6 => '6º Ano do Ensino Fundamental',
            7 => '7º Ano do Ensino Fundamental',
            8 => '8º Ano do Ensino Fundamental',
            9 => '9º Ano do Ensino Fundamental',
            10 => 'Berçário da Educação Infantil',
            11 => '1ª Fase Maternal da Educação Infantil',
            12 => '2ª Fase Maternal da Educação Infantil',
            13 => '3ª Fase Maternal da Educação Infantil',
            14 => '1ª Fase Pré da Educação Infantil',
            15 => '2ª Fase Pré da Educação Infantil',
            16 => 'Eja 1º Segmento Termo 1 do Ensino Fundamental',
            17 => 'Eja 1º Segmento Termo 2 do Ensino Fundamental',
            18 => 'Eja 2º Segmento Termo 1 do Ensino Fundamental',
            19 => 'Eja 2º Segmento Termo 2 do Ensino Fundamental',
            20 => 'Eja 2º Segmento Termo 3 do Ensino Fundamental',
            21 => 'Eja 2º Segmento Termo 4 do Ensino Fundamental',
            22 => 'Multisseriada do Ensino Fundamental'
        ];
        if (!empty($c)) {
            return $cic[$c];
        } else {
            return $cic;
        }
    }

    public function descricaocodigoclasse($cod) {

        switch (substr($cod, 0, 2)) {
            case "EF":
            case "EJ":
                $d = " do Ensino Fundamental";
                break;

            case "EI":
            case "EM":
            case"EB":
                $d = " da Educação Infantil";
                break;
            default :
                $d = "-";
                break;
        }
        $sql = "Select n_turma from ge_turmas Where fk_id_inst = '" . tool::id_inst() . "' And codigo = '" . $cod . "' And periodo_letivo = '" . date('Y') . "'";

        $query = $this->db->query($sql);
        $c = $query->fetch();

        return $c['n_turma'] . $d;
    }

    public function geraquadroaluno($dt_inicio = NULL, $dt_fim = NULL, $id_turma = NULL, $ano = NULL) {

        $s = $this->pegasituacaosed();

        $c = [
            'EF' => '1,2,3,4,5,6,7,8,9',
            'EI' => '19,20',
            'EM' => '21,22,23,24',
            'EJ' => '25,26,27,28,29,30,31,32,33,34,35'
        ];

        if (empty($ano)) {
            $ano = date("Y");
        }

        if (!empty($dt_inicio)) {
            $dt_inicio = data::converteUS($dt_inicio);
            $dt_fim = data::converteUS($dt_fim);
            $dt_inicio = str_replace('-', '', $dt_inicio);
            $dt_fim = str_replace('-', '', $dt_fim);
        }

        $sql = "Select t.fk_id_ciclo, t.codigo as codigo_classe, a.situacao, a.dt_matricula, a.dt_transferencia, t.periodo, t.n_turma from "
                . " ge_turma_aluno a "
                . " Join ge_turmas t on t.id_turma =a.fk_id_turma "
                . " Where t.fk_id_inst=" . tool::id_inst() . " And fk_id_pl IN (" . implode(",", array_keys(gtMain::periodos(1))) . ")"
                . " And t.fk_id_ciclo IN (" . $c[$id_turma] . ") order by t.codigo";

        $query = pdoSis::getInstance()->query($sql);
        $alunos = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($alunos as $v) {
            @$resultado[$v['periodo']][$v['fk_id_ciclo']][$v['codigo_classe']] = $v['codigo_classe'];
            @$resultado[$v['codigo_classe']][total]++;
            @$resultado[$v['codigo_classe']][$s[$v['situacao']]]++;
            if (str_replace('-', '', $v['dt_matricula']) > $dt_inicio && str_replace('-', '', $v['dt_matricula']) < $dt_fim && $v['situacao'] == 'Frequente') {
                @$resultado[$v['codigo_classe']]['suplementar']++;
            }
            if (str_replace('-', '', $v['dt_transferencia']) > $dt_inicio && str_replace('-', '', $v['dt_transferencia']) < $dt_fim && $v['situacao'] == 'Transferido Escola') {
                @$resultado[$v['codigo_classe']]['transferido']++;
            }
        }

        return @$resultado;
    }

    public function geraquadroalunoresumo($dt_inicio = NULL, $dt_fim = NULL, $id_turma = NULL, $ano = NULL) {

        $s = $this->pegasituacaosed();

        $c = [
            'EF' => '1,2,3,4,5,6,7,8,9',
            'EI' => '19,20',
            'EM' => '21,22,23,24',
            'EJ' => '25,26,27,28,29,30,31,32,33,34,35'
        ];

        if (empty($ano)) {
            $ano = date("Y");
        }

        $sql = "SELECT periodo, count(periodo) as conta from ge_turmas"
                . " Where fk_id_inst = " . tool::id_inst() . " And fk_id_pl IN (" . implode(",", array_keys(gtMain::periodos(1))) . ")"
                . " AND fk_id_ciclo IN (" . $c[$id_turma] . ") GROUP by periodo";

        $query = $this->db->query($sql);
        $totalc = $query->fetchAll();

        foreach ($totalc as $v) {

            switch ($v['periodo']) {
                case "M":
                    $totalm = $v['conta'];
                    break;
                case "T":
                    $totalt = $v['conta'];
                    break;
                case "I":
                case "G":
                    $totalig = $v['conta'];
                    break;
                case "N":
                    $totaln = $v['conta'];
                    break;
                default :
                    $r = "indefinido";
                    break;
            }
        }

        if (!empty($dt_inicio)) {
            $dt_inicio = data::converteUS($dt_inicio);
            $dt_fim = data::converteUS($dt_fim);
            $dt_inicio = str_replace('-', '', $dt_inicio);
            $dt_fim = str_replace('-', '', $dt_fim);
        }

        $sql = "Select t.fk_id_ciclo, t.codigo as codigo_classe, a.situacao, a.dt_matricula, a.dt_transferencia, t.periodo, t.n_turma, t.periodo_letivo "
                . "from ge_turma_aluno a"
                . " Join ge_turmas t on t.id_turma =a.fk_id_turma"
                . " Where t.fk_id_inst=" . tool::id_inst() . " And fk_id_pl IN (" . implode(",", array_keys(gtMain::periodos(1))) . ")"
                . " And t.fk_id_ciclo IN (" . $c[$id_turma] . ") order by t.codigo";

        $query = pdoSis::getInstance()->query($sql);
        $alunos = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($alunos as $v) {

            @$resultadoac[$v['periodo']][$s[$v['situacao']]]++;
            @$resultadoac[$v['periodo']]['manha'] = @$totalm;
            @$resultadoac[$v['periodo']]['tarde'] = @$totalt;
            @$resultadoac[$v['periodo']]['integral'] = @$totalig;
            @$resultadoac[$v['periodo']]['noite'] = @$totaln;

            if (str_replace('-', '', $v['dt_matricula']) > $dt_inicio && str_replace('-', '', $v['dt_matricula']) < $dt_fim && $v['situacao'] == 'Frequente') {
                @$resultadoac[$v['periodo']]['suplementarac']++;
            }
            if (str_replace('-', '', $v['dt_transferencia']) > $dt_inicio && str_replace('-', '', $v['dt_transferencia']) < $dt_fim && $v['situacao'] == 'Transferido Escola') {
                @$resultadoac[$v['periodo']]['transferidoac']++;
            }
        }

        return @$resultadoac;
    }

    public function completaperiodo($p) {

        switch ($p) {
            case "M":
                $r = "Manhã";
                break;
            case "T":
                $r = "Tarde";
                break;
            case "I":
            case "G":
                $r = "Integral";
                break;
            case "N":
                $r = "Noite";
                break;
            default :
                $r = "indefinido";
                break;
        }

        return $r;
    }

    public function calcdefas($dn) {

        $dn1 = new DateTime(date('Y') . '-03-31');
        $dn2 = new DateTime($dn);

        $def = $dn1->diff($dn2);
        $res = $def->y . '|' . $def->m . '|' . $def->d;

        return $res;
    }

    public function pegades($x) {

        $sql = "SELECT n_ciclo FROM ge_ciclos WHERE id_ciclo = '" . $x . "'";

        $query = $this->db->query($sql);
        $c = $query->fetch();

        return $c['n_ciclo'];
    }

######################### NOVO #################################################

    public function escolaciclo($id_inst, $id_ciclo) {


        $sel = "SELECT DISTINCT id_inst, n_inst FROM instancia "
                . " JOIN ge_turmas on ge_turmas.fk_id_inst = instancia.id_inst "
                . " Where ge_turmas.fk_id_inst <> '" . $id_inst . "' "
                . "And ge_turmas.fk_id_ciclo = '" . $id_ciclo . "' ORDER BY n_inst";

        $query = $this->db->query($sel);
        $array = $query->fetchAll();

        return $array;
    }

    public function verificaalunotrans($idaluno) {
        /*
          Situação
         * ======
          Ag. Aprovação
          Aprovado
          Matricula Liberada
          Finalizado
          Cancelado
         */
        $wsql = "Select status_transf from ge_transf_aluno "
                . "Where fk_id_pessoa = '" . $idaluno . "' "
                . "AND status_transf not like 'Finalizado' "
                . "AND status_transf not like 'Cancelado'";
        $query = $this->db->query($wsql);
        $array = $query->fetchAll();

        if (empty($array)) {
            return;
        } else {
            return 'Pendente';
        }
    }

    public function remanReclac($id_turma_aluno, $id_turma_d, $situacao) {

        // Verifica a classe 
        $codigo_d = sql::get('ge_turmas', 'codigo', ['id_turma' => $id_turma_d], 'fetch')['codigo'];

        $array = sql::get(['ge_turma_aluno'], '*', ['id_turma_aluno' => $_POST['id_turma_aluno']], 'fetch');
        foreach ($array as $k => $v) {
            $origem[$k] = $v;
        }
        $destino = $origem;
        $origem['situacao'] = $situacao;
        $origem['destino_escola'] = $codigo_d;
        $origem['tp_destino'] = 'TI';
        $origem['dt_transferencia'] = date("Y-m-d");

        $chamada_d = $this->pega_ultimo_nr($id_turma_d);

        $destino['id_turma_aluno'] = NULL;
        $destino['codigo_classe'] = $codigo_d;
        $destino['situacao'] = "Frequente";
        $destino['fk_id_turma'] = $id_turma_d;
        $destino['chamada'] = $chamada_d;
        $destino['origem_escola'] = $origem['codigo_classe'];
        $destino['dt_matricula'] = date("Y-m-d");
        $this->db->ireplace('ge_turma_aluno', $origem, 1);
        $this->db->ireplace('ge_turma_aluno', $destino);

        log::logSet("Reclassifica aluno");
    }

    public function pega_ultimo_nr($idturma) {

        $dados = "Select *  from ge_turma_aluno Where fk_id_turma = " . $idturma . " order by chamada desc limit 0,1";
        $query = $this->db->query($dados);
        $a = $query->fetch()['chamada'];

        $a += 1;

        return $a;
    }

    public function ultimo_turmaNovaChamada($idturma) {

        $dados = "Select *  from ge_turma_aluno Where fk_id_turma = " . $idturma . " order by chamada desc limit 0,1";
        $query = $this->db->query($dados);
        $a = $query->fetch();

        if (!empty($a)) {
            $a['chamada'] += 1;
        } else {
            $dados = "Select *  from ge_turmas Where id_turma = " . $idturma;
            $query = $this->db->query($dados);
            $b = $query->fetch();
            $a['codigo_classe'] = $b['codigo'];
            $a['periodo'] = $b['periodo'];
            $a['periodo_letivo'] = $b['periodo_letivo'];
            $a['chamada'] = 1;
        }
        return $a;
    }

    public function pegacodclasse($id_inst, $id_turma, $todos = NULL) {
        $sql = "select fk_id_ciclo, periodo_letivo from ge_turmas "
                . " where id_turma = $id_turma ";
        $query = pdoSis::getInstance()->query($sql);
        $turma = $query->fetch(PDO::FETCH_ASSOC);

        if (empty($todos)) {
            $ciclo = " and fk_id_ciclo = " . $turma['fk_id_ciclo'];
        } elseif (is_numeric($todos)) {
            $fim = $turma['fk_id_ciclo'] + 2;
            $ciclo = " and fk_id_ciclo > " . $turma['fk_id_ciclo']
                    . " and fk_id_ciclo < $fim";
        } else {
            $ciclo = NULL;
        }

        $sql = "select id_turma, codigo from ge_turmas "
                . "where fk_id_inst = $id_inst "
                . " $ciclo "
                . " And periodo_letivo = '" . $turma['periodo_letivo'] . "' "
                . ' order by codigo';
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $serie[$v['id_turma']] = $v['codigo'];
        }

        return $serie;
    }

    public function alocaPeriodo($id_pl) {
        
    }

    public function listCiclos($id_curso) {
        $ciclos = curso::ciclos($id_curso);

        $ciclos = formulario::editDel($ciclos, 'ge_ciclos', 'id_ciclo', ['aba' => 'ciclos', 'id_curso' => $id_curso, 'id_tp_ens' => $_POST['id_tp_ens']]);
        foreach ($ciclos as $k => $v) {
            $ciclos[$k]['ativo'] = tool::simnao($v['atciclo']);
            $ciclos[$k]['aprova_automatico'] = tool::simnao($v['aprova_automatico']);
            $ciclos[$k]['grade'] = formulario::submit('Grades', NULL, ['id_curso' => $v['fk_id_curso'], 'id_ciclo' => $v['id_ciclo'], 'id_tp_ens' => $_POST['id_tp_ens'], 'aba' => 'grade']);
        }
        $form['array'] = $ciclos;
        $form['fields'] = [
            'Ciclo' => 'n_ciclo',
            'Sigla' => 'sg_ciclo',
            'Grade Curricular Padrão' => 'n_grade',
            'Aprovação Automática' => 'aprova_automatico',
            'Ativo' => 'ativo',
            '||1' => 'del',
            '||2' => 'edit',
            '||3' => 'grade'
        ];

        tool::relatSimples($form);
    }

######################### FIM NOVO #############################################

    public function selecaodisciplina() {
        $d = sql::get('ge_disciplinas', 'n_disc', ['>' => 'n_disc']);

        $x = 0;
        foreach ($d as $v) {
            $di[$x]['n_disc'] = $v['n_disc'];
            $x++;
        }
        $di[$x]['n_disc'] = 'Núcleo Comum';

        return $di;
    }

    public function pegaprof($idturma) {

        $sql = "SELECT p.n_pessoa FROM pessoa p JOIN ge_funcionario f ON f.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turmas t ON t.rm_prof = f.rm"
                . " WHERE t.id_turma  = '" . $idturma . "' AND t.rm_prof != '" . 'NULL' . "'";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($array)) {
            foreach ($array as $v) {
                $p = $v['n_pessoa'];
            }
        } else {
            $p = ' - ';
        }

        return $p;
    }

    public function wlistapilotodocend($t, $campo) {

        $t = implode(",", $t);

        switch ($campo) {

            case "doc":
                $sql = "SELECT p.id_pessoa, p.n_pessoa, p.dt_nasc, p.ra, p.ra_dig, p.sexo, p.rg,"
                        . " p.certidao, p.sus, p.responsavel, p.cpf,"
                        . " t.codigo, ta.situacao, t.id_turma, ta.periodo_letivo, ta.chamada, t.periodo,"
                        . " p.novacert_acervo, p.novacert_ano, p.novacert_cartorio, p.novacert_controle,"
                        . " p.novacert_folha, p.novacert_numlivro, p.novacert_regcivil, p.inep,"
                        . " p.novacert_termo, p.novacert_tipolivro FROM pessoa p"
                        . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa"
                        . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                        . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                        . " WHERE t.id_turma in (" . $t . ") ORDER BY t.codigo, ta.chamada";
                break;

            case "end":
                $sql = "SELECT p.id_pessoa, p.n_pessoa, p.dt_nasc, p.ra, p.ra_dig, p.sexo, p.rg, p.certidao,"
                        . " p.sus, p.responsavel, t.codigo, ta.situacao, t.id_turma, ta.periodo_letivo,"
                        . " ta.chamada, t.periodo, en.logradouro_gdae, en.num_gdae, en.complemento,"
                        . " en.bairro, en.cidade, en.cep FROM pessoa p"
                        . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa"
                        . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                        . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                        . " LEFT JOIN endereco en ON en.fk_id_pessoa = p.id_pessoa"
                        . " WHERE t.id_turma in (" . $t . ") ORDER BY t.codigo, ta.chamada";
                break;
            default :
                $sql = "-";
                break;
        }

        $query = pdoSis::getInstance()->query($sql);
        $lista = $query->fetchAll(PDO::FETCH_ASSOC);

        return $lista;
    }

    public function verificaalunoapd($idpessoa) {

        $sql = "SELECT deficiencia FROM pessoa WHERE id_pessoa = '" . $idpessoa . "'";
        $query = pdoSis::getInstance()->query($sql);
        $apd = $query->fetch()['deficiencia'];

        return $apd;
    }

    public function pessoaGedae($ra, $raDig, $raUF) {


        if (!empty($ra) && !empty($raDig) && !empty($raUF)) {
            $teste = sql::get('pessoa', '*', ['ra' => $ra, 'ra_dig' => $raDig], 'fetch');
            if (!empty($teste)) {
                return $teste;
            } else {
                $gdae = new gdae();
                $p = $gdae->ConsultarFichaAlunoRa($ra, $raDig, $raUF);
                if (is_string($p)) {
                    tool::alert($p);
                } else {

                    $pg = (array) $p['FichasAluno']->FichaAluno;

                    if (!empty($pg['outNumRegNasc'])) {
                        $ins['certidao'] = @$pg['outNumRegNasc'] . ' - ' . @$pg['outCertidaoResp']['outNumLivroReg'] . ' - ' . @$pg['outCertidaoResp']['outFolhaRegNum'];
                    } else {
                        $ins['certidao'] = @$pg['outCertidaoResp']->outCertMatr01 . '-'
                                . @$pg['outCertidaoResp']->outCertMatr02 . '-'
                                . @$pg['outCertidaoResp']->outCertMatr03 . '-'
                                . @$pg['outCertidaoResp']->outCertMatr04 . '-'
                                . @$pg['outCertidaoResp']->outCertMatr05 . '-'
                                . @$pg['outCertidaoResp']->outCertMatr06 . '-'
                                . @$pg['outCertidaoResp']->outCertMatr07 . '-'
                                . @$pg['outCertidaoResp']->outCertMatr08 . '-'
                                . @$pg['outCertidaoResp']->outCertMatr09;
                    }
                    $ins['n_pessoa'] = @$pg['outNomeAluno'];
                    $ins['ra'] = $ra;
                    $ins['ra_dig'] = $raDig;
                    $ins['ra_uf'] = $raUF;
                    $ins['sexo'] = substr($pg['outSexo'], 0, 1);
                    $ins['dt_nasc'] = data::converteUS(@$pg['outDataNascimento']);
                    if (!empty($pg['outNumRG'])) {
                        $ins['rg'] = @$pg['outNumRG'];
                        $ins['rg_dig'] = @$pg['outDigitoRG'];
                        $ins['rg_uf'] = @$pg['outUFRG'];
                        $ins['dt_rg'] = @$pg['outDataEmissaoRG'];
                    }
                    $ins['nacionalidade'] = @$pg['outNacionalidade'];
                    $ins['uf_nasc'] = @$pg['outUFNascimento'];
                    $ins['cidade_nasc'] = @$pg['outDescMunNasc'];
                    $ins['mae'] = @$pg['outNomeMae'];
                    $ins['pai'] = @$pg['outNomePai'];
                    $ins['tel2'] = @$pg['outFoneRecado'];
                    $ins['tel3'] = @$pg['outFoneResidencial'];
                    //endereço
                    $ins['cep'] = @$pg['outCEP'];
                    $ins['logradouro'] = @$pg['outLogradouro'];
                    $ins['num'] = @$pg['outNumero'];
                    $ins['bairro'] = @$pg['outBairro'];
                    $ins['cidade'] = @$pg['outCidade'];
                    $ins['logradouro_gdae'] = @$pg['outLogradouro'];
                    $ins['uf'] = @$pg['outUF'];
                    $ins['complemento'] = @$pg['outComplemento'];

                    tool::alert("Salve para efetivar as alterações.");

                    return $ins;
                }
            }
        } else {
            tool::alert("preencha todos os campos");
        }
    }

    public function pegasituacaosed() {

        $sql = "Select DISTINCT sit_sieb, sit_layout FROM ge_situacao_sed";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        foreach ($array as $v) {
            $sit[$v['sit_sieb']] = $v['sit_layout'];
        }

        return $sit;
    }

    public function pegasituacaosedlayout() {

        $sql = "Select DISTINCT sit_agrupamento, sit_layout FROM ge_situacao_sed";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        foreach ($array as $v) {
            $sit[$v['sit_agrupamento']] = $v['sit_layout'];
        }

        return $sit;
    }

    public function pegatotalalunoclasse($idturma) {

        $sql = "SELECT fk_id_turma, COUNT(situacao) AS total FROM ge_turma_aluno"
                . " WHERE fk_id_turma = '" . $idturma . "'";

        $query = $this->db->query($sql);
        $total = $query->fetch();

        return $total;
    }

    public function cadastraalunoapd() {
        //Campos campos fk_id_inst e fk_id_turma da tabela ge_aluno_apd formam desativadas
        $sql = "SELECT DISTINCT ap.id_apd, p.id_pessoa, p.n_pessoa, t.codigo FROM ge_aluno_apd ap"
                . " JOIN pessoa p ON p.id_pessoa = ap.fk_id_pessoa"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = ap.fk_id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND t.fk_id_inst = ta.fk_id_inst"
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                . " WHERE ta.fk_id_inst = '" . tool::id_inst() . "' AND pl.at_pl = 1 AND t.fk_id_ciclo NOT IN(32)"
                . " AND ta.situacao = 'Frequente' AND ap.status_apd = 'Sim' ORDER BY p.n_pessoa";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $sqlkeyNao = DB::sqlKey('naoApd');
        foreach ($array as $k => $v) {
            $array[$k]['exc'] = form::submit('Excluir', $sqlkeyNao, $v);
        }

        $form['array'] = $array;
        $form['fields'] = [
            'RSE' => 'id_pessoa',
            'Nome Aluno' => 'n_pessoa',
            'Cód.Classe' => 'codigo',
            '||' => 'exc'
        ];
        tool::relatSimples($form);
    }

    public function pegadescricaociclo() {
        $sql = "SELECT id_ciclo, n_ciclo FROM ge_ciclos";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $ci[$v['id_ciclo']] = $v['n_ciclo'];
        }

        $ci[19] = '1ª Fase Pré';
        $ci[20] = '2ª Fase Pré';
        $ci[21] = 'Berçário';
        $ci[22] = '1ª Fase Maternal';
        $ci[23] = '2ª Fase Maternal';
        $ci[24] = '3ª Fase Maternal';

        return $ci;
    }

    public function pegatelefone($criterio) {

        $sql = "SELECT ta.fk_id_pessoa FROM ge_turma_aluno ta"
                . " WHERE ta.fk_id_turma IN($criterio)";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($array)) {
            foreach ($array as $v) {
                // tinha muitos telefones cadastrados limitei a 2 telefones
                $tel[$v['fk_id_pessoa']][1] = NULL;
                $tel[$v['fk_id_pessoa']][2] = NULL;
            }
        }

        $sql = "SELECT ta.fk_id_pessoa, te.tipo, te.num FROM ge_turma_aluno ta"
                . " LEFT JOIN telefones te ON te.fk_id_pessoa = ta.fk_id_pessoa"
                . " WHERE ta.fk_id_turma IN($criterio)";

        $query = pdoSis::getInstance()->query($sql);
        $t = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($t)) {
            foreach ($t as $v) {
                if ($tel[$v['fk_id_pessoa']][1] == NULL) {
                    // Não coloquei o tipo
                    $tel[$v['fk_id_pessoa']][1] = $v['num'];
                } elseif ($tel[$v['fk_id_pessoa']][2] == NULL) {
                    $tel[$v['fk_id_pessoa']][2] = $v['num'];
                    // $tel[$v['fk_id_pessoa']] =  $tel[$v['fk_id_pessoa']] . '-' .$v['num'];
                }
            }
        }
        return $tel;
    }

}
