<?php

class cadampeModel extends MainModel {

    public $db;
    private static $id_inst;

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
        if ($opt = form::jqOption()) {
            $metodo = $opt[0];
            if (in_array($metodo, get_class_methods($this))) {
                $this->$metodo($opt[1]);
            }
        }

        self::$id_inst = toolErp::id_inst();
        if ($this->db->tokenCheck('cadampe_pedidoSalvar')) {
            $this->cadampe_pedidoSalvar();
        }
    }

    public function cadampe_pedidoSalvar() {
        $ins = $_POST[1];

        if (empty($ins['fk_id_cadampe_motivo'])) {
            ?>
            <form id="erro" action="<?= HOME_URI ?>/cadampe/solicitarList" target="_parent" method="POST"></form>
            <script>
                alert("Para solicitar um CADAMPE é necessário determinar o motivo");
                erro.submit();
            </script>
            <?php
                        exit();
        } else {
            $this->db->ireplace('cadampe_pedido', $ins, 1);
        }
    }

    // Crie seus próprios métodos daqui em diante
    public function index_inativaCadampe() {

        $id_pessoa_cadampe = @$_POST[1]['fk_id_pessoa_cadampe'];
        $inativa = filter_input(INPUT_POST, 'inativa', FILTER_SANITIZE_NUMBER_INT);
        $closeModal = filter_input(INPUT_POST, 'closeModal', FILTER_SANITIZE_NUMBER_INT);

        if (!empty($inativa)) {
            $this->db->delete('cadampe_inscr_evento_cat', 'fk_id_pessoa', $id_pessoa_cadampe, 1);
        }

        $cadampes = $this->getCadampeList();

        return [
            'cadampes' => $cadampes,
            'closeModal' => $closeModal,
        ];
    }

    public function index_inativaCadampeList() {

        $inativos = $this->getInativosCadam();

        if ($inativos) {
            foreach ($inativos as $k => $v) {

                $inativos[$k]['fk_id_ec'] = $v['fk_id_ec'];
                $inativos[$k]['cadampe'] = $v['cadampe'];
                $inativos[$k]['cpf'] = $v['cpf'];
                $inativos[$k]['responsavel'] = $v['responsavel'];
                $inativos[$k]['dt_update'] = $v['dt_update'];
                $inativos[$k]['justificativa'] = $v['justificativa'];
            }
        }
        $formIna['array'] = $inativos;
        $formIna['fields'] = [
            //'Inscrição' => 'fk_id_ec',
            'CADAMPE' => 'cadampe',
            'CPF' => 'cpf',
            'Responsável' => 'responsavel',
            'Data' => 'dt_update',
            'Justificativa' => 'justificativa'
        ];

        return [
            'formIna' => $formIna,
        ];
    }

    public function index_solicitarList() {

        $id_status = filter_input(INPUT_POST, 'id_status', FILTER_SANITIZE_NUMBER_INT);
        $log_cancel = filter_input(INPUT_POST, 'log_cancel', FILTER_SANITIZE_NUMBER_INT);
        $log_id_pedido = filter_input(INPUT_POST, 'id_cadampe_pedido', FILTER_SANITIZE_NUMBER_INT);
        $mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_STRING);

        if (empty($mes)) {
            $mes = date("m");
        }

        $hidden = [
            'id_status' => $id_status,
            'mes' => $mes
        ];

        $WHEREmes = " AND (MONTH(cp.dt_inicio) = '$mes' OR MONTH(cp.dt_fim) = '$mes') ";

        if (!empty($log_cancel)) {
            log::logSet("Cancelou solicitação de CADAMPE - Protocolo: " . $log_id_pedido);
            $n_solic = toolErp::n_pessoa();
            $mensagem['fk_id_pessoa'] = toolErp::id_pessoa();
            $mensagem['mensagem'] = "<strong>" . $n_solic . "</strong> alterou a situação do protocolo <strong>" . $log_id_pedido . "</strong> para <strong>Cancelado</strong>";
            $this->insertMsg($log_id_pedido, $mensagem['fk_id_pessoa'], $mensagem['mensagem']);
        }
        $WHEREstatus = "";
        $WHEREdata = "";
        if (empty($id_status)) {
            $WHEREdata = " AND cp.dt_fim >= CURDATE()";
        }

        // $LIMITE = "";
        if (!empty($id_status)) {
            $WHEREstatus = " AND cp.fk_id_status = " . $id_status;
            // if ($id_status == 2 || $id_status == 3) {
            //         $LIMITE = " LIMIT 5";
            // }
        }

        $sql = "SELECT DISTINCT n_motivo, fk_id_pessoa_cadampe, n_status, id_cadampe_pedido, n_pessoa, dt_inicio, dt_fim, status, GROUP_CONCAT(n_turma ORDER BY n_turma SEPARATOR ', ') AS n_turma, n_disc, visualizou FROM ("
                . "SELECT  m.n_motivo, cp.fk_id_pessoa_cadampe,cs.n_status, cp.id_cadampe_pedido, p.n_pessoa, cp.dt_inicio, cp.dt_fim, cp.fk_id_status status, t.n_turma, d.n_disc, cp.visualizou"
                . " FROM cadampe_pedido cp"
                . " JOIN cadampe_motivo m ON m.id_motivo = cp.fk_id_cadampe_motivo"
                . " LEFT JOIN cadampe_turmas ct ON cp.id_cadampe_pedido = ct.fk_id_cadampe_pedido"
                . " LEFT JOIN pessoa p ON p.id_pessoa = cp.fk_id_pessoa_prof"
                . " LEFT JOIN ge_funcionario f ON f.fk_id_pessoa = cp.fk_id_pessoa_prof AND cp.fk_id_inst = " . self::$id_inst . " AND f.at_func = 1"
                . " LEFT JOIN ge_turmas t ON t.id_turma = ct.fk_id_turma "
                . " LEFT JOIN ge_disciplinas d ON d.id_disc = cp.iddisc"
                . " LEFT JOIN cadampe_status cs ON cs.id_status = cp.fk_id_status"
                . " WHERE 1 AND cp.fk_id_inst = " . self::$id_inst
                . $WHEREstatus
                . $WHEREdata
                . $WHEREmes
                . " ) TAB"
                . " GROUP BY fk_id_pessoa_cadampe, n_status, id_cadampe_pedido, n_pessoa, dt_inicio, dt_fim, status, n_disc, visualizou, n_motivo"
                . " ORDER BY id_cadampe_pedido DESC";
        //. $LIMITE;
        //echo $sql;
        $query = pdoSis::getInstance()->query($sql);
        $arrayList = $query->fetchAll(PDO::FETCH_ASSOC);
        $listPedido = [];
        if ($arrayList) {

            foreach ($arrayList as $k => $v) {
                /* if ($v['status'] == 3) {
                  //$id_pessoa = sql::get('')
                  $cadampe = sql::get('pessoa', 'n_pessoa, tel1, tel2,tel3', ['id_pessoa' => $v['fk_id_pessoa_cadampe']]);
                  if (empty($cadampe[0]['tel1'])) {
                  $tel1 = "null";
                  }else{
                  $tel1 =  $cadampe[0]['tel1'];
                  }
                  if (empty($cadampe[0]['tel2'])) {
                  $tel2 = "null";
                  }else{
                  $tel2 =  $cadampe[0]['tel2'];
                  }
                  if (empty($cadampe[0]['tel3'])) {
                  $tel3 = "null";
                  }else{
                  $tel3 =  $cadampe[0]['tel3'];
                  }
                  if (empty($cadampe[0]['n_pessoa'])) {
                  $n_pessoa = "null";
                  }else{
                  $n_pessoa =  $cadampe[0]['n_pessoa'];
                  }
                  $status_fin = $v['status'];
                  $listPedido[$k]['wpp'] = '<button class="btn btn-info" onclick="diario(' . "'$tel1'" . ',' . "'$tel2'" . ',' . "'$tel3'" . ',\''. $n_pessoa .'\' )">Diário</button>';
                  }else{
                  $listPedido[$k]['wpp'] = "";
                  } */
                $listPedido[$k]['protocolo'] = $v['id_cadampe_pedido'];
                $listPedido[$k]['n_status'] = $v['n_status'];
                $listPedido[$k]['n_motivo'] = $v['n_motivo'];
                $listPedido[$k]['prof_turma'] = (!empty($v['n_pessoa']) ? $v['n_pessoa'] : $v['n_disc'] . ' : ' . $v['n_turma']);
                $listPedido[$k]['periodo'] = (!empty($v['dt_fim']) ? "De " . data::converteBr($v['dt_inicio']) . " a " . data::converteBr($v['dt_fim']) : data::converteBr($v['dt_inicio']));
                $btn_ver = empty($v['visualizou']) ? 'warning' : 'success';
                $listPedido[$k]['edit'] = '<button class="btn btn-' . $btn_ver . '" onclick="edit(' . $v['id_cadampe_pedido'] . ',' . $v['status'] . ',\'' . $v['n_status'] . '\' )">Ver</button>';
            }
        }

        $formPedido['array'] = $listPedido;
        $formPedido['fields'] = [
            'Protocolo' => 'protocolo',
            'CADAMPE para' => 'prof_turma',
            'Motivo' => 'n_motivo',
            'Data' => 'periodo',
            'Situação' => 'n_status',
            '||1' => 'edit',
                //'||2' => 'wpp'
        ];

        return [
            'formPedido' => $formPedido,
            'hidden' => $hidden,
            'mes' => $mes,
            'id_status' => $id_status,
        ];
    }

    public function index_convocadosList() {

        $mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_STRING);
        $ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_STRING);

        if (empty($mes)) {
            $mes = date("m");
        }

        $hidden = [
            'mes' => $mes
        ];

        if (empty($ano)) {
           $ano = date('Y'); 
        }
        
        $WHEREescola = "";
        if (toolErp::id_nilvel() == 8) {//escola
            $WHEREescola = " AND cp.fk_id_inst = " . self::$id_inst;
        }

        $WHEREmes = " AND (MONTH(cc.dt_contactar) = '$mes') ";

        if (!empty($ano)) {
            $WHEREano = " AND (YEAR(cp.dt_inicio) = '$ano' or YEAR(cp.dt_fim) = '$ano') ";
        }

        $sql = "SELECT atendente, n_resposta, dt_contactar, dt_update, cpf, n_inst, fk_id_pessoa_cadampe, n_status, id_cadampe_pedido, n_pessoa, status FROM ("
                . "SELECT  cp.dt_update, cc.dt_contactar, p.cpf, i.n_inst, cc.fk_id_pessoa_cadampe, cs.n_status, cp.id_cadampe_pedido, p.n_pessoa, pp.n_pessoa AS atendente, cp.fk_id_status status, cr.n_resposta"
                . " FROM cadampe_contactar cc"
                . " JOIN cadampe_pedido cp ON cc.fk_id_cadampe_pedido = cp.id_cadampe_pedido"
                . " LEFT JOIN instancia i ON i.id_inst = cp.fk_id_inst"
                . " LEFT JOIN cadampe_resposta cr ON cr.id_cadampe_resposta = cc.fk_id_cadampe_resposta"
                . " LEFT JOIN pessoa p ON p.id_pessoa = cc.fk_id_pessoa_cadampe"
                . " LEFT JOIN pessoa pp ON pp.id_pessoa = cp.fk_id_pessoa_call"
                . " LEFT JOIN cadampe_status cs ON cs.id_status = cp.fk_id_status"
                . " WHERE cp.fk_id_status <> 1"
                . $WHEREmes
                . $WHEREano
                . $WHEREescola
                . " ) TAB"
                . " GROUP BY atendente , dt_contactar , dt_update , cpf , n_inst , n_resposta , fk_id_pessoa_cadampe , n_status , id_cadampe_pedido , n_pessoa , status"
                . " ORDER BY dt_contactar DESC";

        //echo $sql;
        $query = pdoSis::getInstance()->query($sql);
        $arrayList = $query->fetchAll(PDO::FETCH_ASSOC);
        $listPedido = [];
        if ($arrayList) {

            foreach ($arrayList as $k => $v) {

                $listPedido[$k]['protocolo'] = $v['id_cadampe_pedido'];
                $listPedido[$k]['n_pessoa'] = $v['n_pessoa'];
                $listPedido[$k]['n_inst'] = $v['n_inst'];
                $listPedido[$k]['cpf'] = $v['cpf'];
                $listPedido[$k]['n_resposta'] = $v['n_resposta'];
                $listPedido[$k]['dt_update'] = $v['dt_update'];
                $listPedido[$k]['dt_contactar'] = $v['dt_contactar'];
                $listPedido[$k]['dt_horario'] = substr($v['dt_contactar'], -8);
                $listPedido[$k]['atendente'] = $v['atendente'];
                $listPedido[$k]['edit'] = '<button class="btn btn-info" onclick="edit(' . $v['id_cadampe_pedido'] . ',' . $v['status'] . ',\'' . $v['n_status'] . '\' )">Ver</button>';
            }
        }

        $formPedido['array'] = $listPedido;
        $formPedido['fields'] = [
            'Protocolo' => 'protocolo',
            'Escola' => 'n_inst',
            'CADAMPE' => 'n_pessoa',
            'CPF' => 'cpf',
            'Justificativa' => 'n_resposta',
            'Atendente' => 'atendente',
            'Data Escola' => 'dt_update',
            'Data Atendimento' => 'dt_contactar',
            'Horário' => 'dt_horario',
            '||1' => 'edit',
                //'||2' => 'wpp'
        ];

        return [
            'formPedido' => $formPedido,
            'hidden' => $hidden,
            'mes' => $mes,
            'ano' => $ano
        ];
    }

    public function index_protocolosList() {

        $mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_STRING);
        $ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_STRING);
        $iddisc = filter_input(INPUT_POST, 'iddisc', FILTER_SANITIZE_NUMBER_INT);
        $periodo = filter_input(INPUT_POST, 'periodo', FILTER_SANITIZE_STRING);
        $dt_inicio = @$_POST['dt_inicio'];
        $id_status = filter_input(INPUT_POST, 'fk_id_status', FILTER_SANITIZE_NUMBER_INT);
        $disc_ = [];
        $disc_ = $this->getDisc();
        
        if (empty($mes)) {
            $mes = date("m");
        }

        if (empty($ano)) {
            $ano = date("Y");
        }

        if (empty($id_status)) {
            $id_status = 3;
        }

        $hidden = [
            'fk_id_status' => $id_status,
            'iddisc' => $iddisc,
            'periodo' => $periodo,
            'dt_inicio' => $dt_inicio,
            'mes' => $mes,
            'ano' => $ano
        ];

        $arrayList = $this->getPedidoRel($id_status, $iddisc, $periodo, $dt_inicio, $mes,$ano);

        $listPedido = [];
        if ($arrayList) {
            foreach ($arrayList as $k => $v) {
                $listPedido[$k]['protocolo'] = $v['id_cadampe_pedido'];
                $listPedido[$k]['edit'] = '<button class="btn btn-outline-info" onclick="edit(' . $v['id_cadampe_pedido'] . ',' . $v['fk_id_status'] . ',\'' . $v['n_status'] . '\' )">Ver</button>';
                $listPedido[$k]['editAdmin'] = '<button class="btn btn-outline-info" onclick="editAdmin(' . $v['id_cadampe_pedido'] . ',' . $v['fk_id_status'] . ',\'' . $v['n_status'] . '\' )">Editar</button>';
                $listPedido[$k]['prof_turma'] = $v['n_categoria'];
                $listPedido[$k]['periodo'] = (!empty($v['dt_fim']) ? "De " . data::converteBr($v['dt_inicio']) . " a " . data::converteBr($v['dt_fim']) : data::converteBr($v['dt_inicio']));
            }
        }
        $formPedido['array'] = $listPedido;
        if (toolErp::id_nilvel() == 10) {
            $formPedido['fields'] = [
                'Protocolo' => 'protocolo',
                'CADAMPE para' => 'prof_turma',
                'Data' => 'periodo',
                '||1' => 'edit',
                '||2' => 'editAdmin'
            ];
        }else{
            $formPedido['fields'] = [
                'Protocolo' => 'protocolo',
                'CADAMPE para' => 'prof_turma',
                'Data' => 'periodo',
                '||1' => 'edit'
            ];
        }
        
        return [
            'formPedido' => $formPedido,
            'disc_' => $disc_,
            'hidden' => $hidden,
            'mes' => $mes,
            'ano' => $ano,
            'id_status' => $id_status,
            'iddisc' => $iddisc,
            'periodo' => $periodo, //Manha, Tarde, Integral
        ];
    }

    public function index_solicitar() {
        $soliciante = explode(' ', toolErp::n_pessoa())[0];
        $id_pessoa_prof = filter_input(INPUT_POST, 'fk_id_pessoa_prof', FILTER_SANITIZE_NUMBER_INT);
        $id_turma = 0;
        $id_turma = filter_input(INPUT_POST, 'fk_id_turma', FILTER_SANITIZE_STRING);
        $iddisc = filter_input(INPUT_POST, 'iddisc', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa_solic = toolErp::id_pessoa();
        $motivo = filter_input(INPUT_POST, 'fk_id_cadampe_motivo', FILTER_SANITIZE_NUMBER_INT);
        $profs = $this->getProfs();
        $id_inst = self::$id_inst;
        $turmas = ng_escola::turmasSegAtiva(self::$id_inst, $iddisc);
        //$turmas = $this->getTurmas();
        $mostra_linha = 0;
        $disc_ = [];
        $disc_ = $this->getDisc();

        $arrayTurmas = [];
        if (!empty($turmas)) {
            foreach ($turmas as $key => $value) {
                if (!isset($arrayTurmas[$key])) {
                    $arrayTurmas[$key] = $key;
                }
            }
        }

        if ($id_pessoa_prof && $id_pessoa_prof != (-1)) {
            $sql = "SELECT h.fk_id_turma"
                    . " FROM ge2.ge_aloca_prof a"
                    . " JOIN ge_funcionario f on f.rm = a.rm and f.fk_id_pessoa = " . $id_pessoa_prof . " AND a.fk_id_inst = ".self::$id_inst." AND f.at_func = 1"
                    . " JOIN ge_horario h ON h.fk_id_turma = a.fk_id_turma AND h.iddisc = a.iddisc";
            $query = pdoSis::getInstance()->query($sql);
            $arraySolict = $query->fetchAll(PDO::FETCH_ASSOC);

            $discs = $this->getDiscProf($id_inst, $id_pessoa_prof);
            if (!empty($discs)) {
                $iddisc = $discs[0]['iddisc'];
            }

            if (empty($arraySolict)) {
                tool::alert($soliciante . ", verifique se a Grade de Aulas para este professor(a) foi definida.");
            } else {
                $mostra_linha = 1;
            }
        } elseif ($id_pessoa_prof == (-1) && $iddisc) {

            if (empty($turmas)) {
                tool::alert($soliciante . ", verifique se a Grade de Aulas para esta disciplina foi definida.");
            } else {
                $mostra_linha = 1;
            }
        }

        $prof_array = [];

        if (!empty($profs)) {

            $prof_array["-1"] = "Não Há Professor";
            foreach ($profs as $k => $v) {
                $prof_array[$k] = $v;
            }
        }

        return [
            'id_pessoa_prof' => $id_pessoa_prof,
            'turmas' => $turmas,
            'arrayTurmas' => $arrayTurmas,
            'prof_array' => $prof_array,
            'id_turma' => $id_turma,
            'disc_' => $disc_,
            'iddisc' => $iddisc,
            'mostra_linha' => $mostra_linha,
            'motivo' => $motivo,
            'id_inst' => $id_inst,
            'id_pessoa_solic' => $id_pessoa_solic
        ];
    }

    public function index_diario() {
        $data = date("Y-m-d");
        $aulas = $this->aulaDiscDia(toolErp::id_inst(), date('w', strtotime($data)));
        $texto = '';
        foreach ($aulas as $k => $v) {
            if (is_numeric($k)) {
                foreach ($v as $ky => $y) {
                    if (!empty($y)) {
                        $uniqid = uniqid() . uniqid();
                        $turmaDisc[$k . '_' . $ky] = [
                            'id_turma' => $k,
                            'iddisc' => $ky,
                            'titulo' => $y,
                            'uniqid' => $uniqid
                        ];
                    }
                }
            }
        }

        foreach ($turmaDisc as $v) {
            echo "<div class='alert alert-primary' role='alert'>";
            $link = 'https://portal.educ.net.br/ge/profe/cadam?token=' . $v['uniqid'];
            echo tool::n_inst() . "<br>";
            echo '<a target="_blank" href="' . $link . '">' . $link . '</a><br><br><br>';
            $texto .= tool::n_inst() . "\n";
            $texto .= $link . "\n\n";
            echo "</div>";
        }

        $tel1 = filter_input(INPUT_POST, 'tel1', FILTER_SANITIZE_NUMBER_INT);
        $tel2 = filter_input(INPUT_POST, 'tel2', FILTER_SANITIZE_NUMBER_INT);
        $tel3 = filter_input(INPUT_POST, 'tel3', FILTER_SANITIZE_NUMBER_INT);

        return [
            'tel1' => $tel1,
            'tel2' => $tel2,
            'tel3' => $tel3,
            'texto' => $texto
        ];
    }

    public function index_atribuirCadampe() {

        $closeModal = filter_input(INPUT_POST, 'closeModal', FILTER_SANITIZE_NUMBER_INT);
        $alterStatus = filter_input(INPUT_POST, 'alterStatus', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa_respon = @$_POST[1]['fk_id_pessoa_call'];
        $id_pedido_respon = filter_input(INPUT_POST, 'id_pedido_respon', FILTER_SANITIZE_NUMBER_INT);
        $alterRespons = filter_input(INPUT_POST, 'alterRespons', FILTER_SANITIZE_NUMBER_INT);
        $n_pessoa_cadampe_fim = filter_input(INPUT_POST, 'n_pessoa_cadampe_fim', FILTER_SANITIZE_STRING);
        $last_id_mensagem = filter_input(INPUT_POST, 'last_id_mensagem', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa_mensagem = toolErp::id_pessoa();
        $soliciante = explode(' ', toolErp::n_pessoa())[0];
        $id_pedido = filter_input(INPUT_POST, 'id_cadampe_pedido', FILTER_SANITIZE_NUMBER_INT);
        $id_categoria_post = filter_input(INPUT_POST, 'iddisc', FILTER_SANITIZE_NUMBER_INT);
        $id_ec = filter_input(INPUT_POST, 'id_ec', FILTER_SANITIZE_NUMBER_INT);

        $pedido = $this->getPedido($id_pedido);

        if (empty($pedido)) {
            return [];
        }

        $status = $pedido[0]['fk_id_status'];
        $n_status = sql::get('cadampe_status', 'n_status', ['id_status' => $status], 'fetch');
        $n_status_log = $n_status['n_status'];

        $fk_id_cate = $pedido[0]['id_categoria'];
        $visualizou = $pedido[0]['visualizou'];
        $block = false;
        $apenasCancela = false;

        if (empty($visualizou)) {
            $visualizouPed['visualizou'] = 1;
            $visualizouPed['id_cadampe_pedido'] = $id_pedido;
            $this->db->ireplace('cadampe_pedido', $visualizouPed, 1);
        }

        if (!empty($alterRespons)) {
            $n_respons = toolErp::n_pessoa($id_pessoa_respon);
            $mensagem_respons['fk_id_pessoa'] = toolErp::id_pessoa();
            $mensagem_respons['mensagem'] = "<strong>" . $n_respons . "</strong> será responsável por este atendimento  de protocolo <strong>" . $id_pedido_respon . "</strong>";
            $this->insertMsg($id_pedido_respon, $mensagem_respons['fk_id_pessoa'], $mensagem_respons['mensagem']);
        }

        if (!empty($status)) {
            //Cancelado ou Finalizado bloqueia açoes na tela
            if (in_array($status, [2, 3])) {
                $block = true;
            }

            //Atribui responsável quando altera Status
            if (!empty($alterStatus)) {

                $n_call = toolErp::n_pessoa();
                $mensagem_status['fk_id_pessoa'] = toolErp::id_pessoa();
                log::logSet("Alterou a situação do protocolo " . $id_pedido . " para " . $n_status_log);

                if (empty($pedido[0]['fk_id_pessoa_call'])) {
                    $statusPedido['fk_id_pessoa_call'] = toolErp::id_pessoa();
                    $pedido[0]['fk_id_pessoa_call'] = $statusPedido['fk_id_pessoa_call'];
                    $statusPedido['id_cadampe_pedido'] = $id_pedido;
                    $this->db->ireplace('cadampe_pedido', $statusPedido, 1);

                    $mensagem_status['mensagem'] = "<strong>" . $n_call . "</strong> é responsável por este atendimento e  alterou a situação do protocolo <strong>" . $id_pedido . "</strong> para <strong>" . $n_status_log . "</strong>";
                } else {
                    $mensagem_status['mensagem'] = "<strong>" . $n_call . "</strong> alterou a situação do protocolo <strong>" . $id_pedido . "</strong> para <strong>" . $n_status_log . "</strong>";
                }
                $this->insertMsg($id_pedido, $mensagem_status['fk_id_pessoa'], $mensagem_status['mensagem']);
            }
        }

        $mensagem['mensagem'] = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_STRING);
        $mensagem['fk_id_pessoa'] = filter_input(INPUT_POST, 'fk_id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $this->insertMsg($id_pedido, $mensagem['fk_id_pessoa'], $mensagem['mensagem']);

        $situacao = sql::get('cadampe_status', 'n_status, id_status', ['at_status' => 1]);
        $mensagens = $this->getMensagens($id_pedido);
        $callCenter = $this->getFuncionario();

        if (empty($fk_id_cate)) {
            $apenasCancela = true;

            /* foreach ($situacao as $key => $value) {
              if ($value['id_status'] != 2) {
              unset($situacao[$key]);
              }
              } */
        }

        $disc_ = [];
        $disc_ = $this->getCat();
        $n_turma = "";
        $n_disc = "";
        $fim_linha = [];
        $contaDia = [];
        $cor = "";
        $id_categoria = !empty($id_categoria_post) ? $id_categoria_post : $pedido[0]['id_categoria'];

        $hidden = [
            'id_cadampe_pedido' => $id_pedido,
            'fk_id_status' => $status,
            'closeModal' => isset($_POST['last_id']), //se for um insert na tabela pedido, atualiza a pagina solicitarList
        ];

        if (empty($pedido[0]['professor'])) {
            $pedido[0]['professor'] = "Não Selecionado";
        }

        if (empty($pedido[0]['dt_fim'])) {
            $pedido[0]['dt_fim'] = $pedido[0]['dt_inicio'];
        }

        $dias = array();
        for ($i = 1; $i <= 5; $i++) {
            $dias[$i] = [
                'dia' => dataErp::diasDaSemana($i, 1),
                'cor' => null,
            ];
        }

        $diasSemana = $this->DiasSemanaPorPeriodo($pedido[0]['dt_inicio'], $pedido[0]['dt_fim']);

        //monta grade de aulas
        $grade = $this->gradePeriodo($pedido);

        if (!empty($grade)) {
            $fim_linha = $grade['fim_linha'];
            $contaDia = $grade['contaDia'];

            foreach ($dias as $key => $value) {
                if (count($contaDia) == 1 && isset($contaDia[$key])) {
                    $dias[$key]['cor'] = '#5F9EA0';
                } elseif (!isset($diasSemana[$key])) {
                    $dias[$key]['cor'] = '#bfc0c1';
                }
            }
        }

        if (count($contaDia) == 1) {
            $cor = "style='background-color:#5F9EA0;'";
        }

        //monta lista de cadampe
        if (!$block && !empty($id_categoria)) {
            $arrayCadampe = $this->getCadampe($id_categoria, null, $pedido[0]['dt_inicio'], $pedido[0]['dt_fim']);
        } else {
            $arrayCadampe = [];
        }

        $listCadampe = [];
        if ($arrayCadampe) {
            foreach ($arrayCadampe as $k => $v) {
                $categoria_cadampe = $v['n_categoria'];
                $listCadampe[$k]['editC'] = '<button class="btn btn-warning" onclick="editC(' . $v['id_ec'] . ', ' . $v['id_pessoa'] . ',' . $id_pedido . ',\'' . $v['n_pessoa'] . '\')">Editar</button>';
                $listCadampe[$k]['n_pessoa'] = $v['n_pessoa'];
                $listCadampe[$k]['class'] = $v['class'];
                $listCadampe[$k]['atendente'] = $v['atendente'];
                $listCadampe[$k]['obs'] = $v['obs'];
                $listCadampe[$k]['ultimo_contato'] = $v['ultimo_contato'];
                $listCadampe[$k]['n_prioridade'] = $v['n_prioridade'];
                $listCadampe[$k]['seletiva'] = $v['seletiva'];
                $listCadampe[$k]['reg'] = '<button class="btn btn-info" onclick="nao_atr(' . $v['id_ec'] . ', ' . $v['id_pessoa'] . ',' . $id_pedido . ',\'' . $v['n_pessoa'] . '\',\'' . $pedido[0]['periodoProto'] . '\',\'' . $pedido[0]['dt_inicio'] . '\',\'' . $pedido[0]['dt_fim'] . '\')">Contactar</button>';
                $listCadampe[$k]['hist'] = '<button class="btn btn-primary" onclick="hist(' . $id_categoria . ',' . $v['id_pessoa'] . ',\'' . $v['n_pessoa'] . '\')">Ver</button>';
                $listCadampe[$k]['op'] = '<button id="' . $v['id_pessoa'] . '" class="btn btn-link" style="width: 25px; height: 25px; border-radius: 50%"></button>';
            }
        }
        $formCadampe['array'] = $listCadampe;
        $formCadampe['fields'] = [
            'Rodízio' => 'n_prioridade',
            'Seletiva' => 'seletiva',
            'Class' => 'class',
            'Data' => 'ultimo_contato',
            'Atendente' => 'atendente',
            'Nome' => 'n_pessoa',
            'Última Observação' => 'obs',
            '||5' => 'op',
            '||4' => 'editC',
            '||2' => 'hist',
            '||1' => 'reg'
        ];

        return [
            'formCadampe' => $formCadampe,
            'categoria_cadampe' => @$categoria_cadampe,
            'pedido' => $pedido,
            'id_pedido' => $id_pedido,
            'soliciante' => $soliciante,
            'status' => $status,
            'n_status' => $n_status,
            'id_pessoa_mensagem' => $id_pessoa_mensagem,
            'fim_linha' => $fim_linha,
            'cor' => $cor,
            'dias' => $dias,
            'mensagens' => $mensagens,
            'closeModal' => $closeModal,
            'last_id_mensagem' => $last_id_mensagem,
            'hidden' => $hidden,
            'n_pessoa_cadampe_fim' => $n_pessoa_cadampe_fim,
            'situacao' => $situacao,
            'disc_' => $disc_,
            'callCenter' => $callCenter,
            'id_categoria' => $id_categoria,
            'block' => $block,
            'apenasCancela' => $apenasCancela,
        ];
    }

    public function index_historicoCadampe() {

        $n_pessoa = filter_input(INPUT_POST, 'n_pessoa_cadampe', FILTER_SANITIZE_STRING);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa_cadampe_hist', FILTER_SANITIZE_NUMBER_INT);
        $id_categoria = filter_input(INPUT_POST, 'id_categoria', FILTER_SANITIZE_NUMBER_INT);

        $historico = $this->getHistoricoCadam($id_pessoa, null);
        $historico2 = $this->getHistoricoCadam($id_pessoa, $id_categoria);

        if ($historico) {
            foreach ($historico as $k => $v) {
                $obs1 = "";
                $obs2 = "";
                $obs3 = "";
                $obs1 = (!empty($v['tel1Obs'])) ? ": " . $v['tel1Obs'] : "";
                $obs2 = (!empty($v['tel2Obs'])) ? ": " . $v['tel2Obs'] : "";
                $obs3 = (!empty($v['tel3Obs'])) ? ": " . $v['tel3Obs'] : "";
                $historico[$k]['rodizio'] = $v['rodizio'];
                $historico[$k]['categoria'] = $v['n_categoria'];
                $historico[$k]['protocolo'] = $v['fk_id_cadampe_pedido'];
                $historico[$k]['dt_contactar'] = $v['dt_contactar'];
                $historico[$k]['dt_horario'] = substr($v['dt_contactar'], -8);
                $historico[$k]['n_pessoa_call'] = $v['n_pessoa'];
                $historico[$k]['n_resposta'] = $v['n_resposta'];
                $historico[$k]['obs'] = $v['obs'];
                $historico[$k]['tel1'] = $v['tel1'] . $obs1;
                $historico[$k]['tel2'] = $v['tel2'] . $obs2;
                $historico[$k]['tel3'] = $v['tel3'] . $obs3;
            }
        }

        $formhist['array'] = $historico;
        $formhist['fields'] = [
            'Rod' => 'rodizio',
            'Categoria' => 'categoria',
            'Protocolo' => 'protocolo',
            'Data' => 'dt_contactar',
            'Hora' => 'dt_horario',
            'Atendente' => 'n_pessoa_call',
            'Justificativa' => 'n_resposta',
            'Telefone 1' => 'tel1',
            'Telefone 2' => 'tel2',
            'Tel 3' => 'tel3',
            'Obs' => 'obs'
        ];

        $n_categoria = "";
        if ($historico2) {
            foreach ($historico2 as $k => $v) {
                $n_categoria = $v['n_categoria'];
                $obs1 = "";
                $obs2 = "";
                $obs3 = "";
                $obs1 = (!empty($v['tel1Obs'])) ? ": " . $v['tel1Obs'] : "";
                $obs2 = (!empty($v['tel2Obs'])) ? ": " . $v['tel2Obs'] : "";
                $obs3 = (!empty($v['tel3Obs'])) ? ": " . $v['tel3Obs'] : "";
                $historico2[$k]['rodizio'] = $v['rodizio'];
                $historico2[$k]['categoria'] = $v['n_categoria'];
                $historico2[$k]['protocolo'] = $v['fk_id_cadampe_pedido'];
                $historico2[$k]['dt_contactar'] = $v['dt_contactar'];
                $historico2[$k]['dt_horario'] = substr($v['dt_contactar'], -8);
                $historico2[$k]['n_pessoa_call'] = $v['n_pessoa'];
                $historico2[$k]['n_resposta'] = $v['n_resposta'];
                $historico2[$k]['obs'] = $v['obs'];
                $historico2[$k]['tel1'] = $v['tel1'] . $obs1;
                $historico2[$k]['tel2'] = $v['tel2'] . $obs2;
                $historico2[$k]['tel3'] = $v['tel3'] . $obs3;
            }
        }
        $formhist2['array'] = $historico2;
        $formhist2['fields'] = [
            'Rod' => 'rodizio',
            'Categoria' => 'categoria',
            'Protocolo' => 'protocolo',
            'Data' => 'dt_contactar',
            'Hora' => 'dt_horario',
            'Atendente' => 'n_pessoa_call',
            'Justificativa' => 'n_resposta',
            'Telefone 1' => 'tel1',
            'Telefone 2' => 'tel2',
            'Tel 3' => 'tel3',
            'Obs' => 'obs'
        ];

        return [
            'formhist' => $formhist,
            'formhist2' => $formhist2,
            'n_pessoa_cadampe' => $n_pessoa,
            'n_categoria' => $n_categoria
        ];
    }

    public function index_historicoCadampeList() {

        $n_pessoa = filter_input(INPUT_POST, 'n_pessoa_cadampe', FILTER_SANITIZE_STRING);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa_cadampe_hist', FILTER_SANITIZE_NUMBER_INT);

        $historico = $this->getHistoricoCadam($id_pessoa, null);

        if ($historico) {
            foreach ($historico as $k => $v) {
                $obs1 = "";
                $obs2 = "";
                $obs3 = "";
                $obs1 = (!empty($v['tel1Obs'])) ? ": " . $v['tel1Obs'] : "";
                $obs2 = (!empty($v['tel2Obs'])) ? ": " . $v['tel2Obs'] : "";
                $obs3 = (!empty($v['tel3Obs'])) ? ": " . $v['tel3Obs'] : "";
                $historico[$k]['rodizio'] = $v['rodizio'];
                $historico[$k]['categoria'] = $v['n_categoria'];
                $historico[$k]['protocolo'] = $v['fk_id_cadampe_pedido'];
                $historico[$k]['dt_contactar'] = $v['dt_contactar'];
                $historico[$k]['dt_horario'] = substr($v['dt_contactar'], -8);
                $historico[$k]['n_pessoa_call'] = $v['n_pessoa'];
                $historico[$k]['n_resposta'] = $v['n_resposta'];
                $historico[$k]['obs'] = $v['obs'];
                $historico[$k]['tel1'] = $v['tel1'] . $obs1;
                $historico[$k]['tel2'] = $v['tel2'] . $obs2;
                $historico[$k]['tel3'] = $v['tel3'] . $obs3;
            }
        }

        $formhist['array'] = $historico;
        $formhist['fields'] = [
            'Rod' => 'rodizio',
            'Categoria' => 'categoria',
            'Protocolo' => 'protocolo',
            'Data' => 'dt_contactar',
            'Hora' => 'dt_horario',
            'Atendente' => 'n_pessoa_call',
            'Justificativa' => 'n_resposta',
            'Telefone 1' => 'tel1',
            'Telefone 2' => 'tel2',
            'Tel 3' => 'tel3',
            'Obs' => 'obs'
        ];


        return [
            'formhist' => $formhist,
            'n_pessoa_cadampe' => $n_pessoa,
        ];
    }

    public function index_naoAtribuirCadampe() {
        $id_pedido = filter_input(INPUT_POST, 'id_cadampe_pedido', FILTER_SANITIZE_NUMBER_INT);
        $fk_id_pessoa_call = filter_input(INPUT_POST, 'fk_id_pessoa_call', FILTER_SANITIZE_NUMBER_INT);
        $n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
        $fk_id_pessoa_cadampe = filter_input(INPUT_POST, 'fk_id_pessoa_cadampe', FILTER_SANITIZE_NUMBER_INT);
        $id_ec = filter_input(INPUT_POST, 'id_ec', FILTER_SANITIZE_NUMBER_INT);
        $finalizar = filter_input(INPUT_POST, 'finalizar', FILTER_SANITIZE_NUMBER_INT);
        $n_pessoa_cadampe_fim = filter_input(INPUT_POST, 'n_pessoa_cadampe_fim', FILTER_SANITIZE_STRING);
        $mensagemEmail = filter_input(INPUT_POST, 'mensagemEmail', FILTER_SANITIZE_STRING);
        $id_categoria_post = filter_input(INPUT_POST, 'id_categoria', FILTER_SANITIZE_NUMBER_INT);
        $fk_id_cadampe_resposta = null;
        $periodo = filter_input(INPUT_POST, 'periodo', FILTER_SANITIZE_STRING);
        $dt_inicio = filter_input(INPUT_POST, 'dt_inicio', FILTER_SANITIZE_STRING);
        $dt_fim = filter_input(INPUT_POST, 'dt_fim', FILTER_SANITIZE_STRING);

        if (isset($_POST[1])) {
            $fk_id_cadampe_resposta = $_POST[1]['fk_id_cadampe_resposta'];
            $obs = $_POST[1]['obs'];
            if (!empty($_POST[1]['tel1'])) {
                $tel1 = $_POST[1]['tel1'];
            }
            if (!empty($_POST[1]['tel2'])) {
                $tel2 = $_POST[1]['tel2'];
            }
            if (!empty($_POST[1]['tel3'])) {
                $tel3 = $_POST[1]['tel3'];
            }
            if (!empty($_POST[1]['tel1Obs'])) {
                $tel1Obs = $_POST[1]['tel1Obs'];
            }
            if (!empty($_POST[1]['tel2Obs'])) {
                $tel2Obs = $_POST[1]['tel2Obs'];
            }
            if (!empty($_POST[1]['tel3Obs'])) {
                $tel3Obs = $_POST[1]['tel3Obs'];
            }
        }

        $tel = sql::get('pessoa', 'tel1, tel2, tel3, email', ['id_pessoa' => $fk_id_pessoa_cadampe]);
        $fone1 = $tel[0]['tel3'];
        $fone2 = $tel[0]['tel1'];
        $fone3 = $tel[0]['tel2'];

        $pedido = $this->getPedido($id_pedido);
        $fk_id_cate = !empty($id_categoria_post) ? $id_categoria_post : $pedido[0]['id_categoria'];

        if ($fk_id_cadampe_resposta == 1) {
            $email = $tel[0]['email'];
            //$email = "vanessart@gmail.com";

            mailer::enviaEmailCadampeCall($n_pessoa_cadampe_fim, $email, $pedido, $id_pedido, $mensagemEmail,$fk_id_pessoa_cadampe);

            $telefones = '';
            $telVirgu = '';
            $qtdeTel = 0;
            if (!empty($fone3)) {
                $telefones .= $telVirgu . $fone3;
                $telVirgu = ', ';
                $qtdeTel++;
            }
            if (!empty($fone1)) {
                $telefones .= $telVirgu . $fone1;
                $telVirgu = ', ';
                $qtdeTel++;
            }
            if (!empty($fone2)) {
                $telefones .= $telVirgu . $fone2;
                $qtdeTel++;
            }

            if ($qtdeTel > 1) {
                $telefones = "pelos telefones: <strong>" . $telefones . "</strong>";
            } else {
                $telefones = "pelo telefone: <strong>" . $telefones . "</strong>";
            }
            $mensagem_call = '';
            if (!empty($mensagemEmail)) {
                $mensagem_call = "<br>A informação <strong>'" . $mensagemEmail . "'</strong> foi incorporada ao email pelo atendente.";
            }

            $mensagem['fk_id_pessoa'] = toolErp::id_pessoa();
            $mensagem['mensagem'] = "Protocolo " . $id_pedido . " <strong>finalizado</strong>.<br> Este protocolo foi atribuído ao CADAMPE <strong>" . $n_pessoa_cadampe_fim . "</strong>.\nVocê pode entrar em contato com este CADAMPE " . $telefones . ".<br>Um email automático foi enviado para <strong>" . $email . "</strong>" . $mensagem_call;
            $this->insertMsg($id_pedido, $mensagem['fk_id_pessoa'], $mensagem['mensagem']);

            //update tabela pedido para finalizar o potocolo
            $statusPedido['fk_id_status'] = 3;
            $statusPedido['id_cadampe_pedido'] = $id_pedido;
            $statusPedido['fk_id_pessoa_cadampe'] = $fk_id_pessoa_cadampe;
            $this->db->ireplace('cadampe_pedido', $statusPedido, 1);
        }

        if (!empty($fk_id_cadampe_resposta)) {
            $insert['fk_id_cadampe_pedido'] = $id_pedido;
            $insert['fk_id_cadampe_resposta'] = $fk_id_cadampe_resposta;
            $insert['fk_id_pessoa_cadampe'] = $fk_id_pessoa_cadampe;
            $insert['fk_id_pessoa_call'] = $fk_id_pessoa_call;
            $insert['fk_id_categoria'] = $fk_id_cate;
            $insert['tel1'] = @$tel1;
            $insert['tel2'] = @$tel2;
            $insert['tel3'] = @$tel3;
            $insert['tel1Obs'] = @$tel1Obs;
            $insert['tel2Obs'] = @$tel2Obs;
            $insert['tel3Obs'] = @$tel3Obs;
            $insert['obs'] = $obs;

            $this->alterCadampe($insert, $id_ec, $fk_id_cate);
        }

        $resps = $this->getRespostas();
        return [
            'id_pedido' => $id_pedido,
            'fk_id_pessoa_call' => $fk_id_pessoa_call,
            'fk_id_pessoa_cadampe' => $fk_id_pessoa_cadampe,
            'fk_id_cadampe_resposta' => $fk_id_cadampe_resposta,
            'fone1' => $fone1,
            'fone2' => $fone2,
            'fone3' => $fone3,
            'email' => @$tel[0]['email'],
            'n_pessoa' => $n_pessoa,
            'id_ec' => $id_ec,
            'resps' => $resps,
            'id_categoria' => $fk_id_cate,
            'periodo' => $periodo,
            'dt_inicio' => $dt_inicio,
            'dt_fim' => $dt_fim,
        ];
    }

    public function index_editCadampe() {
        $id_pedido = filter_input(INPUT_POST, 'id_cadampe_pedido', FILTER_SANITIZE_NUMBER_INT);
        $fk_id_pessoa_call = filter_input(INPUT_POST, 'fk_id_pessoa_call', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa_cadampe = filter_input(INPUT_POST, 'id_pessoa_cadampe_edit', FILTER_SANITIZE_NUMBER_INT);
        $id_ec = filter_input(INPUT_POST, 'id_ec', FILTER_SANITIZE_NUMBER_INT);
        $closeModal = filter_input(INPUT_POST, 'closeModal', FILTER_SANITIZE_NUMBER_INT);

        foreach ($_POST as $k => $v) {
            if (substr($k, 0, 4) == 'old_') {
                $n = str_replace('old_', '', $k);
                //echo $k."|".$v.'|'. $_POST[1][$n]."<br>";
                if ($_POST[1][$n] <> $v) {
                    $log_contato['fk_id_pessoa'] = $id_pessoa_cadampe;
                    $log_contato['fk_id_pessoa_responsavel'] = toolErp::id_pessoa();
                    $log_contato['dado_anterior'] = $v;
                    $log_contato['dado_novo'] = $_POST[1][$n];
                    $log_contato['nome_campo'] = $n;
                    $log_contato['obs'] = $_POST[1]["obs"];
                    //echo "alterou $n<br>";
                    $this->db->insert('cadampe_contato_log', $log_contato, 1);
                }
            }
        }

        $tel = sql::get('pessoa', 'tel1,tel2,tel3, email', ['id_pessoa' => $id_pessoa_cadampe], 'fetch');

        $log_edit = $this->getContatoLog($id_pessoa_cadampe);

        if ($log_edit) {
            foreach ($log_edit as $k => $v) {

                if (substr($v['nome_campo'], 0, 3) == "tel") {
                    $nome_campo = "Telefone";
                } else {
                    $nome_campo = $v['nome_campo'];
                }

                if (empty($v['dado_anterior'])) {
                    $texto = "Incluiu o " . $nome_campo . " " . $v['dado_novo'];
                } else if (empty($v['dado_novo'])) {
                    $texto = "Apagou o " . $nome_campo . " " . $v['dado_anterior'] . " e deixou o campo vazio";
                } else {
                    $texto = "Altereou o " . $nome_campo . " de " . $v['dado_anterior'] . " para " . $v['dado_novo'];
                }
                $log_edit[$k]['responsavel'] = $v['responsavel'];
                $log_edit[$k]['dt_update'] = $v['dt_update'];
                $log_edit[$k]['hora'] = substr($v['dt_update'], 11, 8);
                $log_edit[$k]['log'] = $texto;
                $log_edit[$k]['obs'] = $v['obs'];
            }
        }

        $formlog['array'] = $log_edit;
        $formlog['fields'] = [
            'Data' => 'dt_update',
            'Hora' => 'hora',
            'Responsável' => 'responsavel',
            'Alteração' => 'log',
            'Observação' => 'obs'
        ];

        return [
            'formlog' => $formlog,
            'id_pedido' => $id_pedido,
            'fk_id_pessoa_call' => $fk_id_pessoa_call,
            'fk_id_pessoa_cadampe' => $id_pessoa_cadampe,
            'tel1' => $tel['tel1'],
            'tel2' => $tel['tel2'],
            'tel3' => $tel['tel3'],
            'email' => $tel['email'],
            'id_ec' => $id_ec,
            'closeModal' => $closeModal,
        ];
    }

    public function index_solicitarRel() {

        $iddisc = filter_input(INPUT_POST, 'iddisc', FILTER_SANITIZE_NUMBER_INT);
        $closeModal = filter_input(INPUT_POST, 'closeModal', FILTER_SANITIZE_NUMBER_INT);
        $last_id_mensagem = filter_input(INPUT_POST, 'last_id_mensagem', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa_mensagem = toolErp::id_pessoa();
        $soliciante = explode(' ', toolErp::n_pessoa())[0];
        $id_pessoa_prof = filter_input(INPUT_POST, 'fk_id_pessoa_prof', FILTER_SANITIZE_NUMBER_INT);

        if (empty($_POST['last_id_mensagem'])) {
            $id_pedido = @$_POST['last_id'];
        }

        if (!empty($id_pessoa_prof)) {
            $arrayTurmaPedido = $this->getTurmasProf(self::$id_inst, $id_pessoa_prof);
            if (!empty($arrayTurmaPedido)) {
                foreach ($arrayTurmaPedido as $key => $value) {
                    $insertTurma['fk_id_cadampe_pedido'] = $id_pedido;
                    $insertTurma['fk_id_turma'] = $value['fk_id_turma'];
                    $this->db->ireplace('cadampe_turmas', $insertTurma, 1);
                }
            }
        }

        if (empty($id_pedido)) { //se não for um pedido novo
            $id_pedido = filter_input(INPUT_POST, 'id_cadampe_pedido', FILTER_SANITIZE_NUMBER_INT);
        } else {
            log::logSet("Abriu nova solicitação de CADAMPE - Protocolo: " . $id_pedido);
            $dt_fim = $_POST[1]['dt_fim'];
            $dt_inicio = $_POST[1]['dt_inicio'];
            $mensagem['mensagem'] = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_STRING);
            $mensagem['fk_id_pessoa'] = filter_input(INPUT_POST, 'fk_id_pessoa', FILTER_SANITIZE_NUMBER_INT);
            $this->insertMsg($id_pedido, $mensagem['fk_id_pessoa'], $mensagem['mensagem']);

            if (!empty($_POST[1]['fk_id_turma'])) {
                $arrayTurmaPedido = $_POST[1]['fk_id_turma'];
                foreach ($arrayTurmaPedido as $key => $value) {
                    $insertTurma['fk_id_cadampe_pedido'] = $id_pedido;
                    $insertTurma['fk_id_turma'] = $value;
                    $this->db->ireplace('cadampe_turmas', $insertTurma, 1);
                }
            }

            if (empty($dt_fim)) {
                $insertPedido['id_cadampe_pedido'] = $id_pedido;
                $insertPedido['dt_fim'] = $dt_inicio;
                $this->db->ireplace('cadampe_pedido', $insertPedido, 1);
            }
        }

        $pedido = $this->getPedido($id_pedido);
        if (empty($pedido)) {
            return [];
        }

        $periodoProtocolo = $this->getPeriodo($pedido[0]['periodoProto']);

        $fk_id_cate = $pedido[0]['id_categoria'];
        $sem_categoria = 0;

        if (empty($fk_id_cate)) {
            $sem_categoria = 1;
        }

        $situacao = sql::get('cadampe_status', 'n_status, id_status', ['at_status' => 1]);
        $mensagens = $this->getMensagens($id_pedido);

        $n_turma = "";
        $n_disc = "";
        $fim_linha = [];
        $contaDia = [];
        $cor = "";
        $status = $pedido[0]['fk_id_status'];
        $n_status = sql::get('cadampe_status', 'n_status', ['id_status' => $status], 'fetch');

        $id_categoria = $pedido[0]['id_categoria'];

        if (empty($pedido[0]['professor'])) {
            $pedido[0]['professor'] = "Não Selecionado";
        }

        if (empty($pedido[0]['dt_fim'])) {
            $pedido[0]['dt_fim'] = $pedido[0]['dt_inicio'];
        }

        $dias = array();
        for ($i = 1; $i <= 5; $i++) {
            $dias[$i] = [
                'dia' => dataErp::diasDaSemana($i, 1),
                'cor' => null,
            ];
        }

        $diasSemana = $this->DiasSemanaPorPeriodo($pedido[0]['dt_inicio'], $pedido[0]['dt_fim']);

        //monta grade de aulas

        $grade = $this->grade($pedido);

        if (!empty($grade)) {
            $fim_linha = $grade['fim_linha'];
            $contaDia = $grade['contaDia'];

            foreach ($dias as $key => $value) {
                if (count($contaDia) == 1 && isset($contaDia[$key])) {
                    $dias[$key]['cor'] = '#5F9EA0';
                } elseif (!isset($diasSemana[$key])) {
                    $dias[$key]['cor'] = '#bfc0c1';
                }
            }
        }

        if (count($contaDia) == 1) {
            $cor = "style='background-color:#5F9EA0;'";
        }

        return [
            'pedido' => $pedido,
            'id_pedido' => $id_pedido,
            'soliciante' => $soliciante,
            'status' => $status,
            'n_status' => $n_status,
            'id_pessoa_prof' => $id_pessoa_prof,
            'id_pessoa_mensagem' => $id_pessoa_mensagem,
            'fim_linha' => $fim_linha,
            'cor' => $cor,
            'dias' => $dias,
            'mensagens' => $mensagens,
            'closeModal' => $closeModal,
            'last_id_mensagem' => $last_id_mensagem,
            'situacao' => $situacao,
            'iddisc' => $iddisc,
            'sem_categoria' => $sem_categoria,
            'periodoProtocolo' => $periodoProtocolo
        ];
    }

    public function index_solicitarListcall() {

        $id_pessoa_call = toolErp::id_pessoa();
        $iddisc = filter_input(INPUT_POST, 'iddisc', FILTER_SANITIZE_NUMBER_INT);
        $periodo = filter_input(INPUT_POST, 'periodo', FILTER_SANITIZE_STRING);
        $dt_inicio = @$_POST['dt_inicio'];
        $activeNav = filter_input(INPUT_POST, 'activeNav', FILTER_SANITIZE_NUMBER_INT);
        $id_status1 = filter_input(INPUT_POST, 'fk_id_status', FILTER_SANITIZE_NUMBER_INT);
        $mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_NUMBER_INT);

        if ($activeNav == 1) {
            if (empty($mes) AND!empty($id_status1)) {
                $mes = date("m");
            }
        }

        if (empty($id_status1)) {
            $id_status1 = 1;
        }
        $id_status2 = filter_input(INPUT_POST, 'fk_id_status', FILTER_SANITIZE_NUMBER_INT);
        if (empty($id_status2)) {
            $id_status2 = 4;
        }

        $hidden1 = [
            'fk_id_status' => $id_status1,
            'iddisc' => $iddisc,
            'periodo' => $periodo,
            'dt_inicio' => $dt_inicio,
            'activeNav' => '1',
            'mes' => $mes
        ];

        $hidden2 = [
            'fk_id_status2' => $id_status2,
            'iddisc' => $iddisc,
            'periodo' => $periodo,
            //'dt_inicio' => $dt_inicio,
            'activeNav' => '2'
        ];

        $disc_ = [];
        $disc_ = $this->getDisc();
        if (empty($activeNav)) {
            $activeNav = 1;
        }
        if ($activeNav == 1) {
            $arrayList = $this->getPedidoList($id_status1, null, $iddisc, $periodo, $dt_inicio, $mes);
            $id_status = $id_status1;
        } else {
            $arrayList = $this->getPedidoList($id_status2, $id_pessoa_call, $iddisc, $periodo, $dt_inicio, $mes);
            $id_status = $id_status2;
        }


        $listPedido = [];
        if ($arrayList) {
            foreach ($arrayList as $k => $v) {
                $periodoNome = $this->getPeriodo($v['periodoProto']);
                $periodoProto = data::converteBr($v['dt_inicio']).' a '.data::converteBr($v['dt_fim']).' - '.$periodoNome;
                $listPedido[$k]['protocolo'] = $v['id_cadampe_pedido'];
                $listPedido[$k]['escola'] = $v['n_inst'];
                $btn_ver = empty($v['visualizou']) ? 'warning' : 'success';
                //$listPedido[$k]['edit'] = '<button class="btn btn-'. $btn_ver .'" onclick="edit(' . $v['id_cadampe_pedido'] . ',' . $v['status'] . ',\''. $v['n_status'] .'\' )">Ver</button>';
                $listPedido[$k]['edit'] = '<button class="btn btn-' . $btn_ver . '" onclick="edit(' . $v['id_cadampe_pedido'] . ',' . $id_status . ',\'' . $periodoProto . '\')">Ver</button>';
                $listPedido[$k]['prof_turma'] = $v['n_categoria'] . ' : ' . $v['n_turma'];
                $listPedido[$k]['periodo'] = (!empty($v['dt_fim']) ? "De " . data::converteBr($v['dt_inicio']) . " a " . data::converteBr($v['dt_fim']) : data::converteBr($v['dt_inicio']));
                $listPedido[$k]['op'] = '<button id="' . $v['id_cadampe_pedido'] . '" class="btn btn-link" style="width: 25px; height: 25px; border-radius: 50%"></button>';
            }
        }
        $formPedido['array'] = $listPedido;
        $formPedido['fields'] = [
            'Protocolo' => 'protocolo',
            'Escola' => 'escola',
            'CADAMPE para' => 'prof_turma',
            'Data' => 'periodo',
            '||2' => 'op',
            '||1' => 'edit'
        ];

        return [
            'formPedido' => $formPedido,
            'id_status1' => $id_status1,
            'id_status2' => $id_status2,
            'disc_' => $disc_,
            'iddisc' => $iddisc,
            'periodo' => $periodo, //Manha, Tarde, Integral
            'iddisc' => $iddisc,
            'hidden1' => $hidden1,
            'hidden2' => $hidden2,
            'dt_inicio' => $dt_inicio,
            'activeNav' => $activeNav,
            'mes' => $mes
        ];
    }

    public function index_listCadampe() {

        $id_categoria = filter_input(INPUT_POST, 'iddisc', FILTER_SANITIZE_NUMBER_INT);

        $disc_ = [];
        $disc_ = $this->getCat();

        //monta lista de cadampe

        $arrayCadampe = $this->getCadampe($id_categoria, null, null, null);

        $listCadampe = [];
        if ($arrayCadampe) {
            foreach ($arrayCadampe as $k => $v) {
                $listCadampe[$k]['editC'] = '<button class="btn btn-warning" onclick="editC(' . $v['id_pessoa'] . ',\'' . $v['n_pessoa'] . '\')">Editar</button>';
                $listCadampe[$k]['n_pessoa'] = $v['n_pessoa'];
                $listCadampe[$k]['n_categoria'] = $v['n_categoria'];
                $listCadampe[$k]['class'] = $v['class'];
                $listCadampe[$k]['hist'] = '<button class="btn btn-primary" onclick="hist(' . $v['id_pessoa'] . ',\'' . $v['n_pessoa'] . '\')">Ver</button>';
            }
        }
        $formCadampe['array'] = $listCadampe;
        $formCadampe['fields'] = [
            'Class' => 'class',
            'Nome' => 'n_pessoa',
            'Categoria' => 'n_categoria',
            '||4' => 'editC',
            '||2' => 'hist'
        ];

        return [
            'formCadampe' => $formCadampe,
            'id_categoria' => $id_categoria,
            'disc_' => $disc_
        ];
    }

    public static function getContatoLog($id_pessoa) {
        $sql = "SELECT cl.obs, p1.n_pessoa as responsavel, p2.n_pessoa as pessoa, cl.dado_anterior, cl.dado_novo, cl.nome_campo, cl.dt_update  "
                . " FROM cadampe_contato_log cl "
                . " JOIN pessoa p1 on p1.id_pessoa = cl.fk_id_pessoa_responsavel "
                . " JOIN pessoa p2 on p2.id_pessoa = cl.fk_id_pessoa "
                . " WHERE cl.fk_id_pessoa = " . $id_pessoa
                . " ORDER BY cl.dt_update DESC";

        $query = pdoSis::getInstance()->query($sql);
        $arrayLog = $query->fetchAll(PDO::FETCH_ASSOC);

        return $arrayLog;
    }

    public static function getProfs() {
        $sql = "Select a.rm, p.n_pessoa, p.id_pessoa from ge_aloca_prof a "
                . " join ge_funcionario f on f.rm = a.rm AND a.fk_id_inst = " . self::$id_inst . " AND f.at_func = 1"
                . " join pessoa p on p.id_pessoa = f.fk_id_pessoa ";

        $query = pdoSis::getInstance()->query($sql);
        $arrayProfs = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($arrayProfs as $v) {
            $prof[$v['id_pessoa']] = $v['rm'] . " - " . $v['n_pessoa'];
        }
        if (!empty($prof)) {
            return $prof;
        }
    }

    public static function getCadampe($id_categoria, $id_ec = null, $dt_inicio = null, $dt_fim = null) {
        $sqlAux = '';

        if (!empty($id_ec)) {
            $sqlAux .= " AND ec.id_ec = $id_ec ";
        } else {
            if (!empty($id_categoria)) {
                $sqlAux .= " AND ec.fk_id_cate = $id_categoria ";
            }
        }

        $sql = "SELECT cco.dt_contactar, IF(cco.dt_contactar IS NULL, 'NÃO CONTACTADO', DATE_FORMAT(cco.dt_contactar, '%d/%m/%Y %H:%i:%s')) ultimo_contato, pp.n_pessoa AS atendente, cco.obs, cc.n_categoria, p.id_pessoa, p.n_pessoa, p.email, p.tel1, p.tel2, p.tel3, ec.id_ec, ec.prioridade, ec.prioridade n_prioridade, cs.seletiva, ec.dt_inscr, ec.class "
                . " FROM cadampe_inscr_evento_cat ec"
                . " JOIN pessoa p on p.id_pessoa = ec.fk_id_pessoa"
                . " LEFT JOIN cadampe_contactar cco ON cco.id_cadampe_contactar = (SELECT MAX(id_cadampe_contactar) FROM cadampe_contactar WHERE fk_id_pessoa_cadampe = p.id_pessoa AND fk_id_categoria = ec.fk_id_cate) "
                . " LEFT JOIN pessoa pp on pp.id_pessoa = cco.fk_id_pessoa_call"
                . " JOIN cadampe_categoria cc ON cc.id_categoria = ec.fk_id_cate"
                . " JOIN cadampe_seletiva cs ON ec.fk_id_seletiva = cs.id_seletiva"
                . " WHERE 1 " . $sqlAux
                . " ORDER BY ec.prioridade, cs.seletiva, ec.class";

        $query = pdoSis::getInstance()->query($sql);
        $arrayCadampe = $query->fetchAll(PDO::FETCH_ASSOC);

        return $arrayCadampe;
    }

    public static function getCadampeList() {

        $sql = "SELECT n_pessoa, id_pessoa, id_ec, cpf "
                . " FROM cadampe_inscr_evento_cat ec"
                . " JOIN pessoa p on p.id_pessoa = ec.fk_id_pessoa"
                . " ORDER BY n_pessoa";

        $query = pdoSis::getInstance()->query($sql);
        $arrayCadampeIn = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($arrayCadampeIn as $v) {
            $listCad[$v['id_pessoa']] = $v['cpf'] . " - " . $v['n_pessoa'];
        }

        return $listCad;
    }

    public static function getPedido($id_pedido, $debug = 0) {
        $arrayPedido = 0;
        if (!empty($id_pedido)) {
            $sql = "SELECT fk_id_pessoa_prof "
                . " FROM cadampe_pedido p "
                . " WHERE p.id_cadampe_pedido = " . $id_pedido;
            //echo $sql;
            $query = pdoSis::getInstance()->query($sql);
            $arrayPedido = $query->fetch(PDO::FETCH_ASSOC);
            $id_pessoa_prof = $arrayPedido['fk_id_pessoa_prof'];


            if ($id_pessoa_prof == -1) {//se nao tem professor na sala
                $sql = "SELECT DISTINCT p.fk_id_pessoa_cadampe, p.fk_id_pessoa_call, p.fk_id_status, p.iddisc, c.id_categoria, c.n_categoria, ct.fk_id_turma, m.n_motivo, i.n_inst, pd.logradouro, pd.num, pd.bairro, pd.tel1, p.dt_inicio, p.dt_fim, p.dt_update, p.periodo as periodoProto, solic.n_pessoa AS solicitante, t.n_turma, h.dia_semana, h.aula, t.periodo, p.visualizou "
                        . " FROM cadampe_pedido p "
                        . " JOIN cadampe_turmas ct ON p.id_cadampe_pedido = ct.fk_id_cadampe_pedido "
                        #. " LEFT JOIN ge_horario h ON h.fk_id_turma = ct.fk_id_turma AND h.iddisc = p.iddisc"
                        . " LEFT JOIN ge_horario h ON h.fk_id_turma = ct.fk_id_turma AND IF(p.iddisc = 27, h.iddisc IN(27, 'nc'), h.iddisc = p.iddisc)"
                        . " JOIN cadampe_cat_disc cd ON cd.iddisc = p.iddisc"
                        . " LEFT JOIN ge_turmas t ON t.id_turma = ct.fk_id_turma"
                        . " JOIN cadampe_categoria c ON c.id_categoria = cd.fk_id_categoria"
                        . " JOIN pessoa solic ON solic.id_pessoa = p.fk_id_pessoa_solic"
                        . " JOIN cadampe_motivo m ON m.id_motivo = p.fk_id_cadampe_motivo"
                        . " JOIN instancia i ON i.id_inst = p.fk_id_inst"
                        . " JOIN ge_escolas e ON e.fk_id_inst = i.id_inst"
                        . " LEFT JOIN instancia_predio ip ON ip.fk_id_inst = p.fk_id_inst"
                        . " LEFT JOIN predio pd ON pd.id_predio = ip.fk_id_predio"
                        . " WHERE p.id_cadampe_pedido = " . $id_pedido
                        . " ORDER BY t.n_turma, h.dia_semana, h.aula";
            } else {
                $sql = "SELECT DISTINCT p.fk_id_pessoa_cadampe,p.fk_id_pessoa_call, p.fk_id_status, a.iddisc, c.id_categoria, c.n_categoria, h.fk_id_turma, m.n_motivo, i.n_inst, pd.logradouro, pd.num, pd.tel1, pd.bairro, p.dt_inicio, p.dt_fim, p.dt_update, p.periodo as periodoProto, solic.n_pessoa AS solicitante, profe.n_pessoa AS professor, t.n_turma, h.dia_semana, h.aula, t.periodo, p.visualizou "
                        . " FROM cadampe_pedido p "
                        . " JOIN ge_funcionario f ON f.fk_id_pessoa = p.fk_id_pessoa_prof"
                        . " LEFT JOIN ge_aloca_prof a ON a.rm = f.rm AND a.fk_id_inst = p.fk_id_inst"
                        . " LEFT JOIN ge_horario h ON h.fk_id_turma = a.fk_id_turma AND h.iddisc = a.iddisc"
                        . " JOIN pessoa profe ON profe.id_pessoa = p.fk_id_pessoa_prof"
                        . " LEFT JOIN cadampe_cat_disc cd ON cd.iddisc = a.iddisc"
                        . " LEFT JOIN ge_turmas t ON t.id_turma = h.fk_id_turma"
                        . " LEFT JOIN cadampe_categoria c ON c.id_categoria = cd.fk_id_categoria"
                        . " JOIN pessoa solic ON solic.id_pessoa = p.fk_id_pessoa_solic"
                        . " JOIN cadampe_motivo m ON m.id_motivo = p.fk_id_cadampe_motivo"
                        . " JOIN instancia i ON i.id_inst = p.fk_id_inst"
                        . " JOIN ge_escolas e ON e.fk_id_inst = i.id_inst"
                        . " LEFT JOIN instancia_predio ip ON ip.fk_id_inst = p.fk_id_inst"
                        . " LEFT JOIN predio pd ON pd.id_predio = ip.fk_id_predio"
                        . " WHERE p.id_cadampe_pedido = " . $id_pedido
                        . " ORDER BY t.n_turma, h.dia_semana, h.aula";
            }

            if (!empty($debug)) {
                echo $sql;
            }
            $query = pdoSis::getInstance()->query($sql);
            $arrayPedido = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        

        return $arrayPedido;
    }

    public static function getMensagens($id_pedido) {
        $sql = "SELECT cm.mensagem, cm.dt_mensagem, p.n_pessoa, cm.fk_id_pessoa "
                . " FROM cadampe_mensagem cm "
                . " JOIN pessoa p on p.id_pessoa = cm.fk_id_pessoa "
                . " WHERE fk_id_cadampe_pedido = " . $id_pedido
                . " ORDER BY dt_mensagem";

        $query = pdoSis::getInstance()->query($sql);
        $arrayMensagem = $query->fetchAll(PDO::FETCH_ASSOC);
        $pessoa = [];
        foreach ($arrayMensagem as $key => $value) {
            $k = array_search($value['fk_id_pessoa'], $pessoa);

            if ($k === false) {
                $pessoa[] = $value['fk_id_pessoa'];
                $k = array_search($value['fk_id_pessoa'], $pessoa);
            }

            $arrayMensagem[$key]['cor'] = $k;
            $arrayMensagem[$key]['dt_mensagem'] = self::converteBr($value['dt_mensagem']);
            $arrayMensagem[$key]['n_pessoa'] = explode(' ', $value['n_pessoa'])[0];
        }
        return $arrayMensagem;
    }

    public static function getPedidoList($id_status, $id_pessoa, $iddisc, $periodo, $dt_inicio, $mes) {
        $WHEREpessoa = "";
        $WHEREdisciplina = "";
        $WHEREperiodo = "";
        $WHEREmes = "";
        if (!empty($mes)) {
            $WHEREmes = " AND (MONTH(cp.dt_inicio) = '$mes' OR MONTH(cp.dt_fim) = '$mes') ";
        }
        if (!empty($id_status)) {
            $WHEREdata = " ";
        } else {
            $WHEREdata = " AND cp.dt_fim >= CURDATE()";
        }

        if (!empty($id_pessoa)) {
            $WHEREpessoa = " AND fk_id_pessoa_call = " . $id_pessoa;
        }
        if (!empty($iddisc)) {
            $WHEREdisciplina = " AND cp.iddisc = " . $iddisc;
        }
        if (!empty($periodo)) {
            $WHEREperiodo = " AND t.periodo = '" . $periodo . "'";
        }
        if (!empty($dt_inicio)) {
            $dia = data::converteUS($dt_inicio);
            $WHEREdata = " AND cp.dt_inicio = '" . $dia . "'";
        }
        $sql = "SELECT n_inst, periodoProto, n_inst, id_cadampe_pedido, n_pessoa, id_categoria, dt_inicio, dt_fim, fk_id_status, GROUP_CONCAT(distinct n_turma ORDER BY n_turma SEPARATOR ', ') AS n_turma, n_categoria, visualizou FROM ("
                . "SELECT i.n_inst, cp.periodo as periodoProto, id_cadampe_pedido, p.n_pessoa, cp.dt_inicio, cp.dt_fim, id_categoria, cp.fk_id_status, CONCAT(t.periodo) n_turma, n_categoria, cp.visualizou"
                . " FROM cadampe_pedido cp"
                . " LEFT JOIN cadampe_turmas ct ON cp.id_cadampe_pedido = ct.fk_id_cadampe_pedido"
                . " LEFT JOIN pessoa p ON p.id_pessoa = cp.fk_id_pessoa_prof"
                . " LEFT JOIN ge_funcionario f ON f.fk_id_pessoa = cp.fk_id_pessoa_prof AND f.at_func = 1"
                . " LEFT JOIN ge_aloca_prof a ON a.rm = f.rm AND a.fk_id_inst = cp.fk_id_inst"
                . " LEFT JOIN ge_horario h ON h.fk_id_turma = a.fk_id_turma AND h.iddisc = a.iddisc"
                . " LEFT JOIN cadampe_cat_disc cd ON cd.iddisc = cp.iddisc"
                . " LEFT JOIN cadampe_categoria cc ON cc.id_categoria = cd.fk_id_categoria"
                . " LEFT JOIN ge_turmas t ON t.id_turma = ct.fk_id_turma "
                . " LEFT JOIN instancia i ON i.id_inst = cp.fk_id_inst "
                . " WHERE cp.fk_id_status = " . $id_status
                . $WHEREpessoa
                . $WHEREdisciplina
                . $WHEREperiodo
                . $WHEREdata
                . $WHEREmes
                . " ) TAB"
                . " GROUP BY id_cadampe_pedido, n_pessoa, dt_inicio, id_categoria, dt_fim, fk_id_status, n_categoria"
                . " ORDER BY id_cadampe_pedido, visualizou";

        //echo $sql;
        $query = pdoSis::getInstance()->query($sql);
        $arrayList = $query->fetchAll(PDO::FETCH_ASSOC);

        return $arrayList;
    }

    public static function getPedidoRel($id_status, $iddisc, $periodo, $dt_inicio, $mes,$ano = null) {

        if (empty($ano)) {
           $ano = date("Y"); 
        }
        
        $WHEREdisciplina = "";
        $WHEREperiodo = "";
        $WHEREdata = "";
        $WHEREstatus = "";
        $WHEREmes = "";
        if (!empty($mes)) {
            $WHEREmes = " AND (MONTH(cp.dt_inicio) = '$mes' or MONTH(cp.dt_fim) = '$mes') ";
        }

        if (!empty($ano)) {
            $WHEREano = " AND (YEAR(cp.dt_inicio) = '$ano' or YEAR(cp.dt_fim) = '$ano') ";
        }

        if (!empty($id_status)) {
            $WHEREstatus = " AND cp.fk_id_status = " . $id_status;
        }

        if (!empty($iddisc)) {
            $WHEREdisciplina = " AND cp.iddisc = " . $iddisc;
        }
        if (!empty($periodo)) {
            $WHEREperiodo = " AND t.periodo = '" . $periodo . "'";
        }
        if (!empty($dt_inicio)) {
            $dia = data::converteUS($dt_inicio);
            $WHEREdata = " AND cp.dt_inicio = '" . $dia . "'";
        }
        $sql = "SELECT id_cadampe_pedido, id_categoria, dt_inicio, dt_fim, fk_id_status, n_status, n_categoria, visualizou"
                . " FROM cadampe_pedido cp"
                . " LEFT JOIN cadampe_cat_disc cd ON cd.iddisc = cp.iddisc"
                . " LEFT JOIN cadampe_categoria cc ON cc.id_categoria = cd.fk_id_categoria"
                . " LEFT JOIN cadampe_status cs ON cs.id_status = cp.fk_id_status"
                . " WHERE 1 = 1"
                . $WHEREstatus
                . $WHEREdisciplina
                . $WHEREperiodo
                . $WHEREdata
                . $WHEREmes
                . $WHEREano
                . " GROUP BY id_cadampe_pedido, dt_inicio, id_categoria, dt_fim, fk_id_status, n_categoria"
                . " ORDER BY id_cadampe_pedido";
        $query = pdoSis::getInstance()->query($sql);
        $arrayList = $query->fetchAll(PDO::FETCH_ASSOC);

        return $arrayList;
    }

    public static function getDisc() {
        $sql = "SELECT min(cd.iddisc) iddisc, c.n_categoria FROM ge_disciplinas d"
                . " JOIN cadampe_cat_disc cd ON cd.iddisc = d.id_disc "
                . " JOIN cadampe_categoria c ON c.id_categoria = cd.fk_id_categoria "
                . " group by c.n_categoria ";

        $query = pdoSis::getInstance()->query($sql);
        $arrayDisc = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($arrayDisc as $v) {
            $disc[$v['iddisc']] = $v['n_categoria'];
        }
        return $disc;
    }

    public static function getCat() {
        $sql = "SELECT c.id_categoria, c.n_categoria FROM ge_disciplinas d"
                . " JOIN cadampe_cat_disc cd ON cd.iddisc = d.id_disc "
                . " JOIN cadampe_categoria c ON c.id_categoria = cd.fk_id_categoria ";

        $query = pdoSis::getInstance()->query($sql);
        $arrayDisc = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($arrayDisc as $v) {
            $disc[$v['id_categoria']] = $v['n_categoria'];
        }
        return $disc;
    }

    public static function getFuncionario() {
        $id_sistema = $_SESSION['userdata']['id_sistema'];

        $sql = "SELECT id_pessoa, n_pessoa FROM acesso_gr ag"
                . " JOIN acesso_pessoa ap on ap.fk_id_gr = ag.fk_id_gr "
                . " JOIN pessoa p on ap.fk_id_pessoa = p.id_pessoa "
                . " WHERE fk_id_sistema = " . $id_sistema
                . " AND fk_id_nivel = 39";
        $query = pdoSis::getInstance()->query($sql);
        $arrayFuncionarios = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($arrayFuncionarios as $v) {
            $func[$v['id_pessoa']] = $v['n_pessoa'];
        }
        return $func;
    }

    public static function getDiscProf($fk_id_inst, $id_pessoa_prof) {
        $sql = "SELECT a.iddisc FROM ge_aloca_prof a"
                . " JOIN ge_funcionario f ON a.rm = f.rm AND a.fk_id_inst =" . $fk_id_inst
                . " AND f.fk_id_pessoa = " . $id_pessoa_prof . " AND a.fk_id_inst = " . $fk_id_inst . " AND f.at_func = 1"
                . " JOIN cadampe_cat_disc cd ON cd.iddisc = a.iddisc "
                . " JOIN cadampe_categoria c ON c.id_categoria = cd.fk_id_categoria ";

        //echo $sql;
        $query = pdoSis::getInstance()->query($sql);
        $arrayDisc = $query->fetchAll(PDO::FETCH_ASSOC);
        return $arrayDisc;
    }

    public static function getTurmasProf($fk_id_inst, $id_pessoa_prof) {
        $sql = "SELECT h.fk_id_turma FROM ge_aloca_prof a"
                . " JOIN ge_funcionario f ON a.rm = f.rm AND a.fk_id_inst =" . $fk_id_inst . " AND f.at_func = 1"
                . " JOIN ge_horario h ON h.fk_id_turma = a.fk_id_turma AND h.iddisc = a.iddisc"
                . " JOIN ge_turmas t ON t.id_turma = h.fk_id_turma"
                . " AND f.fk_id_pessoa= " . $id_pessoa_prof;

        //echo $sql;
        $query = pdoSis::getInstance()->query($sql);
        $arrayTurmas = $query->fetchAll(PDO::FETCH_ASSOC);
        return $arrayTurmas;
    }

    public static function getHistoricoCadam($id_pessoa_cadampe, $categoria) {

        $WHEREcategoria = "";
        if (!empty($categoria)) {
            $WHEREcategoria = " AND cc.fk_id_categoria = " . $categoria;
        }

        $sql = "SELECT cc.rodizio, cc.tel1, cc.tel2, cc.tel3, tel1Obs, tel2Obs, tel3Obs, c.n_categoria, cc.fk_id_cadampe_pedido, dt_contactar, n_pessoa, cc.obs, n_resposta FROM cadampe_contactar cc"
                . " JOIN cadampe_resposta cr ON cr.id_cadampe_resposta = cc.fk_id_cadampe_resposta"
                . " LEFT JOIN pessoa p ON p.id_pessoa = cc.fk_id_pessoa_call"
                . " JOIN cadampe_pedido cp ON cc.fk_id_cadampe_pedido = cp.id_cadampe_pedido"
                . " JOIN cadampe_categoria c ON c.id_categoria = cc.fk_id_categoria"
                . " WHERE cc.fk_id_pessoa_cadampe= " . $id_pessoa_cadampe
                . $WHEREcategoria
                . " ORDER BY cc.dt_contactar DESC, cc.id_cadampe_contactar DESC";

        //echo $sql;
        $query = pdoSis::getInstance()->query($sql);
        $arrayhist = $query->fetchAll(PDO::FETCH_ASSOC);
        return $arrayhist;
    }

    public static function getInativosCadam() {

        $sql = "SELECT fk_id_ec, p1.cpf, p1.n_pessoa as cadampe, p2.n_pessoa as responsavel, dt_update, justificativa FROM cadampe_inativo ci"
                . " JOIN pessoa p1 ON p1.id_pessoa = ci.fk_id_pessoa_cadampe"
                . " JOIN pessoa p2 ON p2.id_pessoa = ci.fk_id_pessoa_responsavel"
                . " ORDER BY dt_update DESC";
        //echo $sql;
        $query = pdoSis::getInstance()->query($sql);
        $arrayInat = $query->fetchAll(PDO::FETCH_ASSOC);
        return $arrayInat;
    }

    public static function converteBr($datai) {
        if (!empty($datai)) {
            if (substr(@$datai, 4, 1) == '-' && substr(@$datai, 7, 1) == '-') {
                $data = substr($datai, 0, 10);
                $hora = substr($datai, 11, 8);
                $dt = explode('-', $data);

                $datai = str_pad($dt[2], 2, "0", STR_PAD_LEFT) . '/' . str_pad($dt[1], 2, "0", STR_PAD_LEFT) . '/' . str_pad($dt[0], 4, "20", STR_PAD_LEFT);
                if (!empty($hora)) {
                    $datai .= ' ' . $hora;
                }
            }
            return $datai;
        }
    }

    private function grade($pedido) {
        $fim_linha = [];
        $contaDia = [];
        foreach ($pedido as $k => $v) {
            if (!isset($fim_linha[$v['iddisc']])) {

                $fim_linha[$v['iddisc']] = $v;
                $fim_linha[$v['iddisc']]['turmas'] = [];
            }

            if (!isset($fim_linha[$v['iddisc']]['turmas'][$v['fk_id_turma']])) {
                $fim_linha[$v['iddisc']]['turmas'][$v['fk_id_turma']] = [];
                $fim_linha[$v['iddisc']]['turmas'][$v['fk_id_turma']]['n_turma'] = $v['n_turma'];
                $fim_linha[$v['iddisc']]['turmas'][$v['fk_id_turma']]['aulas'] = [];
            }

            if (!empty($v['dia_semana'])) {
                $fim_linha[$v['iddisc']]['turmas'][$v['fk_id_turma']]['aulas'][$v['dia_semana']][] = $v['aula'] . "ª aula";
                $contaDia[$v['dia_semana']] = 1;
            }
        }
        return ['fim_linha' => $fim_linha, 'contaDia' => $contaDia];
    }

    private function gradePeriodo($pedido) {
        $fim_linha = [];
        $contaDia = [];
        foreach ($pedido as $k => $v) {
            if (!isset($fim_linha[$v['iddisc']])) {
                $fim_linha[$v['iddisc']] = $v;
                $fim_linha[$v['iddisc']]['periodos'] = [];
            }

            if (!isset($fim_linha[$v['iddisc']]['periodos'][$v['periodo']])) {
                $fim_linha[$v['iddisc']]['periodos'][$v['periodo']] = [];
                $fim_linha[$v['iddisc']]['periodos'][$v['periodo']]['n_periodo'] = !empty($v['periodo']) ? dataErp::periodoDoDia($v['periodo']) : '';
                $fim_linha[$v['iddisc']]['periodos'][$v['periodo']]['aulas'] = [];
            }

            if (!empty($v['dia_semana'])) {
                $fim_linha[$v['iddisc']]['periodos'][$v['periodo']]['aulas'][$v['dia_semana']][] = $v['aula'] . "ª aula";
                $contaDia[$v['dia_semana']] = 1;
            }
        }
        return ['fim_linha' => $fim_linha, 'contaDia' => $contaDia];
    }

    private function DiasSemanaPorPeriodo($dtInicio, $dtFim) {
        $dtInicio = new DateTime($dtInicio);
        $dtFim = new DateTime($dtFim);
        $dateRange = array();
        while ($dtInicio <= $dtFim) {
            $dtInicioStr = $dtInicio->format('Y-m-d');
            $w = date('w', strtotime($dtInicioStr));
            $dateRange[$w] = $w;
            if (count($dateRange) == 7) {
                break;
            }
            $dtInicio = $dtInicio->modify('+1day');
        }

        return $dateRange;
    }

    public function solicitaSet() {
        $id = @$_REQUEST['id'];
        $sit = @$_REQUEST['sit'];
        $conta = @$_REQUEST['conta'];
        if ($sit && $id) {
            $mongo = new mongoCrude();
            $mongo->update('cadampeSol', ['id' => $id], ['id' => $id, 'sit' => $sit, 'conta' => $conta]);
        }
    }

    public function solicitaGet() {

        $mongo = new mongoCrude();
        $dados = $mongo->query('cadampeSol');
        if ($dados) {
            foreach ($dados as $v) {
                $json[$v->id] = [
                    'id' => $v->id,
                    'sit' => $v->sit,
                    'conta' => $v->conta
                ];
            }
        }
        ob_clean();
        if (!empty($json)) {
            echo json_encode($json);
        }
        exit();
    }

    public function cadampeSet() {
        $id = @$_REQUEST['id'];
        $sit = @$_REQUEST['sit'];
        $conta = @$_REQUEST['conta'];
        if ($sit && $id) {
            $mongo = new mongoCrude();
            $mongo->update('profeSol', ['id' => $id], ['id' => $id, 'sit' => $sit, 'conta' => $conta]);
        }
    }

    public function cadampeGet() {

        $mongo = new mongoCrude();
        $dados = $mongo->query('profeSol');
        if ($dados) {
            foreach ($dados as $v) {
                $json[$v->id] = [
                    'id' => $v->id,
                    'sit' => $v->sit,
                    'conta' => $v->conta
                ];
            }
        }
        ob_clean();
        if (!empty($json)) {
            echo json_encode($json);
        }
        exit();
    }

    public function getRespostas($id_cadampe_resposta = null) {
        $sql = "SELECT id_cadampe_resposta, n_resposta FROM cadampe_resposta "
                . " WHERE at_resposta = 1";
        //echo $sql;
        $query = pdoSis::getInstance()->query($sql);
        $arrayResp = $query->fetchAll(PDO::FETCH_ASSOC);
        return $arrayResp;
    }

    public function getContatosCadampe($fk_id_pessoa_cadampe = null, $fk_id_cate = null) {
        $sqlAux = '';
        if (!empty($fk_id_pessoa_cadampe)) {
            $sqlAux .= " AND cc.fk_id_pessoa_cadampe = $fk_id_pessoa_cadampe";
        }

        if (!empty($fk_id_cate)) {
            $sqlAux .= " AND cc.fk_id_categoria = $fk_id_cate";
        }

        $sql = "SELECT cc.id_cadampe_contactar, cc.fk_id_cadampe_pedido, cc.fk_id_cadampe_resposta, cc.fk_id_pessoa_cadampe"
                . " FROM cadampe_contactar cc "
                . " INNER JOIN cadampe_pedido cp ON cc.fk_id_cadampe_pedido = cp.id_cadampe_pedido "
                . " WHERE 1 "
                . $sqlAux
                . " ORDER BY cc.dt_contactar ";
        //echo $sql;
        $query = pdoSis::getInstance()->query($sql);
        $arrayResp = $query->fetchAll(PDO::FETCH_ASSOC);
        return $arrayResp;
    }

    public function insertMsg($id_pedido, $fk_id_pessoa, $mensagem) {
        if (!empty($mensagem)) {
            $insert['mensagem'] = $mensagem;
            $insert['fk_id_cadampe_pedido'] = $id_pedido;
            $insert['fk_id_pessoa'] = $fk_id_pessoa;
            $this->db->insert('cadampe_mensagem', $insert, 1);
        }
    }

    public function alterCadampe($insert, $id_ec, $fk_id_cate) {
        if (!empty($_POST['alterCadampe'])) {
            $id_cadampe_contactar = null;
            //se nao passou o pedido nao faz o insert
            if (!empty($insert['fk_id_cadampe_pedido'])) {
                $id_cadampe_contactar = $this->db->insert('cadampe_contactar', $insert, 1);
            }

            $fk_id_pessoa_cadampe = $insert['fk_id_pessoa_cadampe'];
            $id_cadampe_resposta = $insert['fk_id_cadampe_resposta'];
            $prioridade = 0;
            $prioridadeNew = 0;

            if (!empty($id_cadampe_resposta)) {
                $respostas = $this->getContatosCadampe($fk_id_pessoa_cadampe, $fk_id_cate);
                $cadampe = $this->getCadampe($fk_id_cate, $id_ec);
                $atualiza = false;
                if (!empty($cadampe)) {
                    $prioridade = $cadampe[0]['prioridade'];
                    $prioridadeNew = $prioridade;
                }

                //verifica se nao realizou contato 3x seguidas
                if ($id_cadampe_resposta == 2) {
                    $qtdeSemContato = 0;
                    foreach ($respostas as $key => $value) {
                        if ($value['fk_id_cadampe_resposta'] == 2) {
                            $qtdeSemContato++;
                        } else {
                            $qtdeSemContato = 0;
                        }
                    }
                    if ($qtdeSemContato > 0 && ($qtdeSemContato % 3 == 0 )) {
                        $atualiza = true;
                    }
                } else {
                    $atualiza = true;
                }

                if ($atualiza) {
                    if ($prioridade < 2) {
                        $dados = [];
                        $dados['id_ec'] = $id_ec;
                        $dados['prioridade'] = $prioridade + 1;
                        $prioridadeNew = $prioridade + 1;

                        $this->db->ireplace('cadampe_inscr_evento_cat', $dados, 1);
                    }
                    // Valida se todos já foram contactados para zerar a lista
                    $this->resetListaCadampe($fk_id_cate, $insert['fk_id_cadampe_pedido']);
                }
            }

            if (!empty($id_cadampe_contactar)) {
                $insRod['id_cadampe_contactar'] = $id_cadampe_contactar;
                $insRod['rodizio'] = $prioridadeNew;
                $this->db->ireplace('cadampe_contactar', $insRod, 1);
            }
        }
    }

    public function aulaDiscDia($id_inst, $diaSemana = null) {
        if ($diaSemana) {
            $diaSemana = " and dia_semana = $diaSemana ";
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

    public function resetListaCadampe($id_categoria, $id_cadampe_pedido) {
        $sql = "SELECT ec.id_ec "
                . " FROM cadampe_inscr_evento_cat ec"
                . " JOIN pessoa p on p.id_pessoa = ec.fk_id_pessoa"
                . " JOIN cadampe_categoria cc ON cc.id_categoria = ec.fk_id_cate"
                . " WHERE ec.fk_id_cate = $id_categoria AND ec.prioridade = 0 "
                . " LIMIT 1 ";
        if (in_array(tool::id_pessoa(), [1, 5])) {
            echo $sql;
        }

        // Pesquisa por cadampe da categoria que ainda não tenha sido indicado
        $query = pdoSis::getInstance()->query($sql);
        $arrayCadampe = $query->fetchAll(PDO::FETCH_ASSOC);

        // Caso todos os cadampes já tenha alguma indicação, reseta a lista
        if (empty($arrayCadampe)) {
            $sql1 = "INSERT INTO cadampe_contactar (fk_id_cadampe_pedido, fk_id_cadampe_resposta, dt_contactar, fk_id_pessoa_cadampe, rodizio, fk_id_categoria)"
                    . " SELECT $id_cadampe_pedido, 5, NOW(), ec.fk_id_pessoa, IF(ec.prioridade >= 2, 1, 0) rodizio, ec.fk_id_cate "
                    . " FROM cadampe_inscr_evento_cat ec"
                    . " JOIN pessoa p on p.id_pessoa = ec.fk_id_pessoa"
                    . " JOIN cadampe_categoria cc ON cc.id_categoria = ec.fk_id_cate"
                    . " WHERE ec.fk_id_cate = $id_categoria ";
            if (in_array(tool::id_pessoa(), [1, 5])) {
                echo $sql1;
            }
            $query = pdoSis::getInstance()->query($sql1);

            $sql2 = "UPDATE cadampe_inscr_evento_cat ec"
                    . " JOIN pessoa p on p.id_pessoa = ec.fk_id_pessoa"
                    . " JOIN cadampe_categoria cc ON cc.id_categoria = ec.fk_id_cate"
                    . " SET ec.prioridade = IF(ec.prioridade >= 2, 1, 0) "
                    . " WHERE ec.fk_id_cate = $id_categoria ";
            if (in_array(tool::id_pessoa(), [1, 5])) {
                echo $sql2;
            }
            $query = pdoSis::getInstance()->query($sql2);

            return true;
        }

        return false;
    }

    public function getPeriodo($periodo) {
        $periodo_ = str_split($periodo);
        $arrPeriodo = [];
        foreach ($periodo_ as $v) {
           if ($v == 'N') {
               $arrPeriodo[] = 'Noite';
           }
           if ($v == 'T') {
               $arrPeriodo[] = 'Tarde';
           }
           if ($v == 'M') {
               $arrPeriodo[] = 'Manhã';
           }
           
        }
        $periodo = toolErp::virgulaE($arrPeriodo);
        return $periodo;
    }

    public function getCadampeAlocado($id_pessoa, $dt_inicio, $dt_fim, $periodo) { //variaveis do protocolo
        $periodo_ = str_split($periodo);
        $arrPeriodo = [];
        $or = $per = '';
        foreach ($periodo_ as $v) {
           $per .= $or . "LOCATE('$v',periodo)";
           $or = " or ";
        }

        if (!empty($per)) {
           $per = " AND ($per) ";
        }

        //compara as variaveis do protocolo com os protocolos que o cadampe aceitou e verifica se há conflito de horario
        $sql = "SELECT id_cadampe_pedido, dt_inicio, dt_fim, periodo FROM cadampe_pedido "
                . " WHERE fk_id_pessoa_cadampe = $id_pessoa "
                    . " AND (('$dt_inicio' BETWEEN dt_inicio AND dt_fim or '$dt_fim' BETWEEN dt_inicio AND dt_fim) OR( dt_inicio BETWEEN '$dt_inicio' AND  '$dt_fim' OR  dt_fim BETWEEN '$dt_inicio' AND  '$dt_fim')) "
                    . $per
                    . " AND fk_id_status = 3";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getAnosProtocolos() {
        $sql = "SELECT DISTINCT YEAR(dt_fim) AS ano FROM cadampe_pedido ORDER BY ano";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $anos=[];
        foreach ($array as $v) {
            if (!empty($v['ano'])) { 
                $anos[$v['ano']] = $v['ano'];
            } 
        }
        return $anos;
    }

    public function getProfSubstituido($mes,$ano,$iddisc=null,$id_pessoa = null,$hist = null) {

        if (empty($ano)) {
            $ano = date('Y');
        }
        $wAno = " AND (YEAR(cp.dt_inicio) = '$ano' or YEAR(cp.dt_fim) = '$ano') ";
        $wMes = $wIddisc ='';
        if (!empty($mes)) {
            $wMes = " AND (MONTH(cp.dt_inicio) = '$mes' or MONTH(cp.dt_fim) = '$mes') ";
        }

        if (!empty($iddisc)) {
            $wIddisc = " AND cp.iddisc = " . $iddisc;
        }

        if (!empty($id_pessoa)) {
            $id_pessoa = " AND cp.fk_id_pessoa_prof = " . $id_pessoa;
        }
        $sql = "SELECT f.rm, (TIMESTAMPDIFF(DAY, dt_inicio, dt_fim)+ 1) as dias,cp.id_cadampe_pedido, n_motivo, iddisc, n_disc, i.n_inst, p.id_pessoa, p.n_pessoa, cp.dt_inicio, cp.dt_fim, cp.dt_update, cp.periodo, cp.id_cadampe_pedido"
            . " FROM cadampe_pedido cp"
            . " JOIN pessoa p ON p.id_pessoa = cp.fk_id_pessoa_prof"
            . " JOIN instancia i ON i.id_inst = cp.fk_id_inst"
            . " JOIN cadampe_motivo m ON m.id_motivo = cp.fk_id_cadampe_motivo"
            . " LEFT JOIN ge_funcionario f on p.id_pessoa = f.fk_id_pessoa and f.funcao like 'prof%' "
            . " LEFT JOIN ge_disciplinas d ON d.id_disc = cp.iddisc"
            . " WHERE cp.fk_id_status <> 2"
            . $wAno
            . $wMes
            . $wIddisc
            . $id_pessoa;
        $query = pdoSis::getInstance()->query($sql);
        $listProfs = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($listProfs) {
            foreach ($listProfs as $k => $v) {
                $listProfs[$k]['nome'] = $v['n_pessoa'].' - '.$v['rm'];
                $listProfs[$k]['ausenteAno'] = $this->getfaltasProf($v['id_pessoa'],$iddisc,$ano);
                $listProfs[$k]['ausenteMes'] = $this->getfaltasProf($v['id_pessoa'],$iddisc,$ano,$mes);
                $listProfs[$k]['disc'] = $v['iddisc'] == 'nc' ? 'Núcleo Comum' : $v['n_disc'];
                $listProfs[$k]['his'] = '<button class="btn btn-outline-info" onclick="his(' . $v['id_pessoa'] . ',\'' . $ano . '\',\'' . $v['n_pessoa'] . '\')">Histórico</button>';
                $listProfs[$k]['data'] = (!empty($v['dt_fim']) ? "De " . data::converteBr($v['dt_inicio']) . " a " . data::converteBr($v['dt_fim']) : data::converteBr($v['dt_inicio']));
            }
        }
        $form['array'] = $listProfs;

        if (!empty($hist)) { //se for a pagina de historico, esconde o botao
           $form['fields'] = [
                'Escola' => 'n_inst',
                'Disciplina' => 'disc',
                'Data' => 'data',
                'Período' => 'periodo',
                'Dias Ausentes' => 'dias',
                'Protocolo' => 'id_cadampe_pedido'
            ]; 
        }else{
            $form['fields'] = [
                'Protocolo' => 'id_cadampe_pedido',
                'Professor Efetivo' => 'nome',
                'Escola' => 'n_inst',
                'Disciplina' => 'disc',
                'Data' => 'data',
                'Período' => 'periodo',
                'Aus. Mes' => 'ausenteMes',
                'Aus. Ano' => 'ausenteAno',
                '||1' => 'his'
            ];
        }
        
        
        return $form;
    }

    public function getfaltasProf($id_pessoa,$iddisc=null,$ano,$mes=null) {

        if (empty($ano)) {
            $ano = date('Y');
        }
        $ano = " AND (YEAR(cp.dt_inicio) = '$ano' or YEAR(cp.dt_fim) = '$ano') ";

        if (!empty($mes)) {
            $mes = " AND (MONTH(cp.dt_inicio) = '$mes' or MONTH(cp.dt_fim) = '$mes') ";
        }

        if (!empty($iddisc)) {
            $iddisc = " AND cp.iddisc = " . $iddisc;
        }
        $sql = "SELECT SUM((TIMESTAMPDIFF(DAY, dt_inicio, dt_fim)+ 1)) AS dias "
                . " FROM ge2.cadampe_pedido cp "
                . " WHERE fk_id_pessoa_prof = $id_pessoa AND fk_id_status <> 2"
                . $ano
                . $mes
                . $iddisc;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        
        return $array['dias'];
    }

}
