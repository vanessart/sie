<?php

class labModel extends MainModel {

    public $db;
    public $modeloChromeAluno = '1';
    public $_id_hist;

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
//$this->parametros = $this->controller->parametros;
// Configura os dados do usuário
        $this->userdata = $this->controller->userdata;
        if ($this->db->tokenCheck('chromeEdit')) {
            $this->chromeEdit();
        } elseif ($this->db->tokenCheck('chromenovo')) {
            $this->chromenovo();
        } elseif ($this->db->tokenCheck('chromeProfEdit')) {
            $this->chromeProfEdit();
        } elseif ($this->db->tokenCheck('chromeProfDel')) {
            $this->chromeProfDel();
        } elseif ($this->db->tokenCheck('chromeProfnovo')) {
            $this->chromeProfnovo();
        } elseif ($this->db->tokenCheck('chromeProfDelTodos')) {
            $this->chromeProfDelTodos();
        } elseif ($this->db->tokenCheck('emprestaSalva')) {
            $this->emprestaSalva();
        } elseif ($this->db->tokenCheck('finalEmprestimo')) {
            $this->finalEmprestimo();
        } elseif ($this->db->tokenCheck('finalEmprestimoRede')) {
            $this->finalEmprestimoRede();
        } elseif ($this->db->tokenCheck('chromeExcluirEsc')) {
            $this->chromeExcluirEsc();
        } elseif ($this->db->tokenCheck('chromeIncluirEsc')) {
            $this->chromeIncluirEsc();
        } elseif ($this->db->tokenCheck('chromeLibera')) {
            $this->chromeLibera();
        } elseif ($this->db->tokenCheck('chromeBlock')) {
            $this->chromeBlock();
        } elseif ($this->db->tokenCheck('manutSalvar')) {
            $this->manutSalvar();
        }
    }

    public function manutSalvar() {
        $n_doc = filter_input(INPUT_POST, 'n_doc', FILTER_SANITIZE_STRING);
        $ins = @$_POST[1];
        if (!empty($n_doc)) {
            $file = ABSPATH . '/pub/labDoc/';
            $up = new upload($file, $ins['fk_id_ch'], 21000000000000000000000);
            $end = $up->up();
            if ($end) {
                $this->db->ireplace('lab_chrome_doc', ['fk_id_ch' => $ins['fk_id_ch'], 'end' => $end, 'n_doc'=>$n_doc], 1);
            }
        }
        $inst = sql::get('lab_chrome', 'fk_id_inst', ['id_ch' => $ins['fk_id_ch']], 'fetch')['fk_id_inst'];
        if ($ins['fk_id_ms'] == 5) {
            $chrome['fk_id_cs'] = 1;
        } else {
            $chrome['fk_id_cs'] = 4;
        }
        $chrome['id_ch'] = $ins['fk_id_ch'];
        if (in_array($ins['fk_id_ms'], [4, 6])) {
            $chrome['fk_id_inst'] = null;
        }

        $this->db->ireplace('lab_chrome', $chrome, 1);

        $id = $this->db->ireplace('lab_chrome_manutencao', $ins);
        $log['fk_id_manut'] = $id;
        $log['fk_id_ms'] = $ins['fk_id_ms'];
        $log['fk_id_pessoa'] = toolErp::id_pessoa();
        $log['fk_id_inst'] = $inst;
        $this->db->replace('lab_chrome_manutencao_status_log', $log, 1);
    }

    public function chromeBlock() {
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $id_ch = filter_input(INPUT_POST, 'id_ch', FILTER_SANITIZE_NUMBER_INT);
        $ins['id_ch'] = $id_ch;
        $ins['block'] = 1;
        $ins['fk_id_pessoa_lanc'] = tool::id_pessoa();
        $ins['dt_cad'] = date("Y-m-d H:i:s");
        $id = $this->db->ireplace('lab_chrome', $ins);

        $hist['fk_id_ch'] = $id_ch;
        $hist['fk_id_pessoa'] = tool::id_pessoa();
        $hist['fk_id_inst'] = $id_inst;
        $hist['obs'] = 'Bloqueou o chromebook para transferência';
        $id = $this->db->insert('lab_chrome_mov', $hist, 1);
    }

    public function chromeLibera() {
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $id_ch = filter_input(INPUT_POST, 'id_ch', FILTER_SANITIZE_NUMBER_INT);
        $ins['id_ch'] = $id_ch;
        $ins['block'] = 0;
        $ins['fk_id_pessoa_lanc'] = tool::id_pessoa();
        $ins['dt_cad'] = date("Y-m-d H:i:s");
        $id = $this->db->ireplace('lab_chrome', $ins);

        $hist['fk_id_ch'] = $id_ch;
        $hist['fk_id_pessoa'] = tool::id_pessoa();
        $hist['fk_id_inst'] = $id_inst;
        $hist['obs'] = 'Liberou o chromebook para transferência';
        $id = $this->db->insert('lab_chrome_mov', $hist, 1);
    }

    public function movHist($id_inst = null) {
        if ($id_inst) {
            $id_inst = " where m.fk_id_inst = $id_inst";
        }
        $sql = "SELECT "
                . " p.n_pessoa, i.n_inst, c.serial, m.times_stamp, l.n_cm, m.obs "
                . " FROM lab_chrome_mov m "
                . " JOIN lab_chrome c on c.id_ch = m.fk_id_ch "
                . " JOIN instancia i on i.id_inst = m.fk_id_inst "
                . " JOIN pessoa p on p.id_pessoa = m.fk_id_pessoa "
                . " JOIN lab_chrome_modelo l on l.id_cm = c.fk_id_cm "
                . $id_inst
                . " ORDER BY m.times_stamp desc";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function chromeIncluirEsc() {
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $id_ch = filter_input(INPUT_POST, 'id_ch', FILTER_SANITIZE_NUMBER_INT);
        $ins['id_ch'] = $id_ch;
        $ins['fk_id_inst'] = $id_inst;
        $ins['fk_id_pessoa'] = null;
        $ins['block'] = 1;
        $ins['fk_id_cd'] = 1;
        $ins['carrinho'] = null;
        $ins['fk_id_pessoa_lanc'] = tool::id_pessoa();
        $ins['email_google'] = NULL;
        $ins['recadastro'] = 0;
        $ins['dt_cad'] = date("Y-m-d H:i:s");
        $ins['recad_ip'] = null;
        $id = $this->db->ireplace('lab_chrome', $ins);

        $hist['fk_id_ch'] = $id_ch;
        $hist['fk_id_pessoa'] = tool::id_pessoa();
        $hist['fk_id_inst'] = $id_inst;
        $hist['obs'] = 'Cadastrou o chromebook';
        $id = $this->db->insert('lab_chrome_mov', $hist, 1);
    }

    public function chromeExcluirEsc() {
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $id_ch = filter_input(INPUT_POST, 'id_ch', FILTER_SANITIZE_NUMBER_INT);
        $ins['id_ch'] = $id_ch;
        $ins['fk_id_inst'] = null;
        $ins['fk_id_pessoa'] = null;
        $ins['fk_id_cd'] = 5;
        $ins['carrinho'] = null;
        $ins['fk_id_pessoa_lanc'] = tool::id_pessoa();
        $ins['email_google'] = NULL;
        $ins['recadastro'] = 0;
        $ins['dt_cad'] = date("Y-m-d H:i:s");
        $ins['recad_ip'] = null;
        $id = $this->db->ireplace('lab_chrome', $ins);

        $hist['fk_id_ch'] = $id_ch;
        $hist['fk_id_pessoa'] = tool::id_pessoa();
        $hist['fk_id_inst'] = $id_inst;
        $hist['obs'] = 'Descadastrou o chromebook';
        $id = $this->db->insert('lab_chrome_mov', $hist, 1);
    }

    public function salvaCromeRede() {
        if ($this->db->tokenCheck('salvaCromeRede')) {
            $ins = @$_POST[1];
            if (!empty($ins['fk_id_inst'])) {
                $ins['fk_id_cd'] = 1;
            } elseif (@$ins['fk_id_cd'] == 1) {
                $ins['fk_id_cd'] = 5;
            }
            $ins['block'] = 1;
            $id = $this->db->ireplace('lab_chrome', $ins, 1);

            return $id;
        }
    }

    public function finalEmprestimo() {
        $ins = $_POST[1];
        if (!empty($ins['fk_id_modem'])) {
            $fk_id_modem = $ins['fk_id_modem'];
            $ins['fk_id_modem'] = null;
            $modemDevol = filter_input(INPUT_POST, 'modemDevol', FILTER_SANITIZE_NUMBER_INT);
        }
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_STRING);
        $p = sql::get('pessoa', 'n_pessoa, id_pessoa, emailgoogle', ['id_pessoa' => $id_pessoa], 'fetch');
        $obs = filter_input(INPUT_POST, 'obs', FILTER_SANITIZE_STRING);
        $dt_inicio = filter_input(INPUT_POST, 'dt_inicio', FILTER_SANITIZE_STRING);
        $dt_fim = filter_input(INPUT_POST, 'dt_fim', FILTER_SANITIZE_STRING);
        $ch = sql::get('lab_chrome', 'carrinho, serial', ['id_ch' => $ins['id_ch']], 'fetch');
        $ins['dt_inicio'] = null;
        $ins['dt_fim'] = null;
        $this->db->ireplace('lab_chrome', $ins);
        $id_ce = filter_input(INPUT_POST, 'id_ce', FILTER_SANITIZE_NUMBER_INT);
        $emprestimo = [
            'id_ce' => $id_ce,
            'dt_fim' => date("Y-m-d"),
            'obs' => $obs,
            'fk_id_pessoa_lanc' => toolErp::id_pessoa(),
            'fk_id_ces_equip' => 1,
            'fk_id_ces_carr' => $ins['carregador']
        ];
        $this->db->ireplace('lab_chrome_emprestimo', $emprestimo, 1);
        $hist['fk_id_ch'] = $ins['id_ch'];
        $hist['fk_id_inst'] = $id_inst;
        $hist['fk_id_cs'] = 1;
        $hist['fk_id_pessoa'] = $id_pessoa;
        $hist['fk_id_cd'] = 1;
        $hist['devolucao'] = 1;
        $hist['fk_id_pessoa_lanc'] = $ins['fk_id_pessoa_lanc'];
        if ($ins['carregador'] == 1) {
            $carregador = " (COM carregador) ";
        } else {
            $carregador = " (SEM carregador) ";
        }
        $hist['obs'] = $p['n_pessoa'] . ' (' . $p['id_pessoa'] . ') devolveu do Chromebook N/S: ' . $ch['serial'] . $carregador . ' utilizado no período entre ' . data::converteBr($dt_inicio) . ' e ' . data::converteBr($dt_fim);
        if (!empty($fk_id_modem)) {
            $modem = sql::get(['lab_modem', 'lab_modem_modelo'], 'serial, n_mm', ['id_modem' => $fk_id_modem], 'fetch');
            if ($modemDevol == 1) {
                @$hist['obs'] .= '<br>' . $p['n_pessoa'] . ' devolveu o modem N/S ' . $modem['serial'] . ', modelo "' . $modem['n_mm'] . '".';
            } else {
                @$hist['obs'] .= '<br>' . $p['n_pessoa'] . '  NÃO devolveu o modem N/S ' . $modem['serial'] . ', modelo "' . $modem['n_mm'] . '". ';
            }
        }
        $hist['obs'] .= ' <br />' . $obs;
        $this->_id_hist = $this->db->ireplace('lab_chrome_hist', $hist, 1);
    }

    public function finalEmprestimoRede() {

        $id_ce = filter_input(INPUT_POST, 'id_ce', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_STRING);
        $id_ch = filter_input(INPUT_POST, 'id_ch', FILTER_SANITIZE_NUMBER_INT);
        $dt_fim = filter_input(INPUT_POST, 'dt_fim', FILTER_SANITIZE_NUMBER_INT);
        $obs = filter_input(INPUT_POST, 'obs', FILTER_SANITIZE_STRING);
        $fk_id_pessoa_lanc = filter_input(INPUT_POST, 'fk_id_pessoa_lanc', FILTER_SANITIZE_STRING);
        $fk_id_ces_equip = filter_input(INPUT_POST, 'fk_id_ces_equip', FILTER_SANITIZE_NUMBER_INT);
        $fk_id_ces_carr = filter_input(INPUT_POST, 'fk_id_ces_carr', FILTER_SANITIZE_NUMBER_INT);
        if (empty($fk_id_ces_equip) || empty($fk_id_ces_carr)) {
            toolErp::alert('Preencha todos os campos');
            return;
        }
        $emprestimo = [
            'id_ce' => $id_ce,
            'dt_fim' => $dt_fim,
            'obs' => $obs,
            'fk_id_pessoa_lanc' => $fk_id_pessoa_lanc,
            'fk_id_ces_equip' => $fk_id_ces_equip,
            'fk_id_ces_carr' => $fk_id_ces_carr
        ];
        $this->db->ireplace('lab_chrome_emprestimo', $emprestimo, 1);


        ############ versão antiga ##############
        $ins = $_POST[1];
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $dt_inicio = filter_input(INPUT_POST, 'dt_inicio', FILTER_SANITIZE_STRING);
        $dt_fim = filter_input(INPUT_POST, 'dt_fim', FILTER_SANITIZE_STRING);
        $ch = sql::get('lab_chrome', 'carrinho, serial', ['id_ch' => $ins['id_ch']], 'fetch');
        $ins['dt_inicio'] = null;
        $ins['dt_fim'] = null;
        $this->db->ireplace('lab_chrome', $ins, 1);
        $hist['fk_id_ch'] = $ins['id_ch'];
        $hist['fk_id_inst'] = $id_inst;
        $hist['fk_id_cs'] = 1;
        $hist['fk_id_pessoa'] = $id_pessoa;
        $hist['fk_id_cd'] = 5;
        $hist['devolucao'] = 1;
        $hist['fk_id_pessoa_lanc'] = $ins['fk_id_pessoa_lanc'];
        $hist['obs'] = 'Devolução do Chromebook N/S: ' . $ch['serial'] . ' utilizado no período entre ' . data::converteBr($dt_inicio) . ' e ' . data::converteBr($dt_fim) . ' <br />' . $obs;
        $this->_id_hist = $this->db->ireplace('lab_chrome_hist', $hist);
    }

    public function emprestaSalvaRede() {
        if ($this->db->tokenCheck('emprestaSalvaRede')) {
            $ins = @$_POST[1];
            $obs = filter_input(INPUT_POST, 'obs', FILTER_SANITIZE_STRING);
            $p = sql::get('pessoa', 'n_pessoa, id_pessoa, emailgoogle', ['id_pessoa' => $ins['fk_id_pessoa']], 'fetch');
            $id_ch = $this->db->ireplace('lab_chrome', $ins, 1);
            $em = [
                'fk_id_ch' => $ins['id_ch'],
                'dt_inicio' => $ins['dt_inicio'],
                'fk_id_pessoa' => $ins['fk_id_pessoa'],
                'fk_id_pessoa_lanc' => $ins['fk_id_pessoa_lanc'],
                'obs' => $obs,
            ];
            $id_ce = $this->db->ireplace('lab_chrome_emprestimo', $em, 1);
            $ch = sql::get('lab_chrome', 'carrinho, serial', ['id_ch' => $ins['id_ch']], 'fetch');
            $hist['fk_id_ch'] = $ins['id_ch'];
            $hist['fk_id_cs'] = 1;
            $hist['fk_id_pessoa'] = $ins['fk_id_pessoa'];
            $hist['fk_id_pessoa_lanc'] = toolErp::id_pessoa();
            $hist['fk_id_cd'] = 2;
            $hist['obs'] = $p['n_pessoa'] . ' (' . $p['id_pessoa'] . ') emprestou o chromebook N/S ' . $ch['serial'] . ' no dia ' . dataErp::converteBr($ins['dt_inicio']) . '<br />' . $obs;
            $this->db->ireplace('lab_chrome_hist', $hist);

            return $id_ce;
        }
    }

    public function emprestaSalva() {
        $ins = @$_POST[1];
        $obs = filter_input(INPUT_POST, 'obs', FILTER_SANITIZE_STRING);
        $p = sql::get('pessoa', 'n_pessoa, id_pessoa, emailgoogle', ['id_pessoa' => $ins['fk_id_pessoa']], 'fetch');
        @$ins['email_google'] = $p['emailgoogle'];
        if (empty($ins['id_ch'])) {
            tool::alert("Não foi selecionado um chromebook");
            return;
        }

        $this->db->ireplace('lab_chrome', $ins);
        $em = [
            'fk_id_ch' => $ins['id_ch'],
            'dt_inicio' => date("Y-m-d"),
            'fk_id_pessoa' => $ins['fk_id_pessoa'],
            'fk_id_pessoa_lanc' => toolErp::id_pessoa(),
            'obs' => 'Cadastro on-line',
        ];
        try {
            $id_ce = $this->db->ireplace('lab_chrome_emprestimo', $em, 1);
        } catch (Exception $exc) {
            
        }
        $ch = sql::get('lab_chrome', 'carrinho, serial', ['id_ch' => $ins['id_ch']], 'fetch');
        $hist['fk_id_ch'] = $ins['id_ch'];
        $hist['carrinho'] = $ch['carrinho'];
        $hist['fk_id_cs'] = 3;
        $hist['fk_id_pessoa'] = $ins['fk_id_pessoa'];
        $hist['fk_id_pessoa_lanc'] = toolErp::id_pessoa();
        $hist['fk_id_cd'] = 3;
        $hist['obs'] = $p['n_pessoa'] . ' (' . $p['id_pessoa'] . ') emprestou o chromebook N/S ' . $ch['serial'] . ' no dia ' . dataErp::converteBr($ins['dt_inicio']);
        if (!empty($ins['fk_id_modem'])) {
            $modem = sql::get(['lab_modem', 'lab_modem_modelo'], 'serial, n_mm', ['id_modem' => $ins['fk_id_modem']], 'fetch');
            $hist['obs'] .= '<br>' . $p['n_pessoa'] . ' emprestou o modem N/S' . $modem['serial'] . ', modelo "' . $modem['n_mm'] . '".';
        }
        $hist['obs'] .= '<br />' . $obs;
        $this->db->ireplace('lab_chrome_hist', $hist, 1);
    }

    public function chromeProfnovo() {
        $ip = $this->pegaIp();
        $ins = @$_POST[1];
        $id_pessoa = $ins['fk_id_pessoa'];
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        if (!empty($ins['serial'])) {
            $ch = sql::get(['lab_chrome', 'pessoa'], ' pessoa.n_pessoa, pessoa.id_pessoa, lab_chrome.*', ['serial' => $ins['serial']], 'fetch', 'left');
            if (empty($ch['serial'])) {
                toolErp::alert('Não existe Chromebook com o número de série ' . $ins['serial']);
            } elseif ($ch['recadastro'] == 1) {
                if ($ins['email_google'] == $ch['email_google']) {
                    toolErp::alert('Você já cadastrou esse Chromebook.');
                } else {
                    toolErp::alert('Este Chromebook já está relacionado com um funcionário. Registramos o ocorrido e entraremos em contato em breve');

                    $cri['serial'] = $ch['serial'];
                    $cri['fk_id_pessoa'] = $ins['fk_id_pessoa'];
                    $cri['email_google'] = $ins['email_google'];
                    $cri['obs'] = "Dois funcionários reivindicam o chromebook N/S " . $ch['serial'] . ", " . $nome . '(' . $id_pessoa . ') e ' . $ch['n_pessoa'] . ' (' . $ch['id_pessoa'] . ') ';
                    $cri['fk_id_tp'] = 3;
                    $this->db->ireplace('lab_chrome_critica', $cri, 1);
                }
            } elseif (in_array($ch['fk_id_cd'], [1, 3])) {
                if (!empty($ch['fk_id_inst'])) {
                    $escola = sql::get('instancia', 'n_inst', ['id_inst' => $ch['fk_id_inst']], 'fetch')['n_inst'];
                    toolErp::alert("Este chromebook está sob a responsabilidade da $escola. Procure a escola.");
                } else {
                    toolErp::alert("Este chromebook está sob a responsabilidade de uma escola.");
                }
            } else {
                $ins['id_ch'] = $ch['id_ch'];
                $ins['recadastro'] = 1;
                $ins['fk_id_cd'] = 2;
                $ins['fk_id_cs'] = 1;
                $ins['dt_cad'] = date("Y-m-d H:i:s");
                $ins['recad_ip'] = $ip;
                $id = $this->db->ireplace('lab_chrome', $ins);

                $hist['fk_id_ch'] = $ch['id_ch'];
                $hist['fk_id_cs'] = 1;
                $hist['fk_id_cd'] = 2;
                $hist['fk_id_pessoa'] = $id_pessoa;
                $hist['fk_id_pessoa_lanc'] = $id_pessoa;
                $hist['obs'] = $nome . '(' . $id_pessoa . ') recadastrou o Chromebook N/S ' . $ch['serial'];
                $id = $this->db->ireplace('lab_chrome_hist', $hist, 1);

                $em = [
                    'fk_id_ch' => $ins['id_ch'],
                    'dt_inicio' => date("Y-m-d"),
                    'fk_id_pessoa' => $id_pessoa,
                    'fk_id_pessoa_lanc' => $id_pessoa,
                    'obs' => 'Cadastro on-line',
                ];
                try {
                    $this->db->ireplace('lab_chrome_emprestimo', $em, 1);
                } catch (Exception $exc) {
                    
                }
            }
        }
    }

    public function chromeProfDel() {
        $id_ch = filter_input(INPUT_POST, 'id_ch', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $serial = filter_input(INPUT_POST, 'serial', FILTER_SANITIZE_STRING);
        if (!empty($serial)) {
            $ch = sql::get('lab_chrome', '*', ['serial' => $serial], 'fetch');
            if (empty($ch['serial'])) {
                toolErp::alert('Não existe Chromebook com o número de série ' . $ins['serial']);
            } else {
                $ins['id_ch'] = $ch['id_ch'];
                $ins['fk_id_pessoa'] = null;
                $ins['fk_id_cd'] = null;
                $ins['fk_id_pessoa_lanc'] = $id_pessoa;
                $ins['email_google'] = null;
                $id = $this->db->ireplace('lab_chrome', $ins);
                $ch['fk_id_ch'] = $ins['id_ch'];
                $ch['fk_id_pessoa'] = null;
                $ch['fk_id_pessoa_lanc'] = $id_pessoa;
                unset($ch['serial']);
                unset($ch['fk_id_cm']);
                unset($ch['mac']);
                unset($ch['id_ch']);
                unset($ch['recadastro']);
                unset($ch['email_google']);
                $ch['obs'] = 'id_pessoa ' . $id_pessoa . ' descadastrou este chromebook';
                $id = $this->db->ireplace('lab_chrome_hist', $ch, 1);
            }
        }
    }

    public function chromeProfDelTodos() {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $serials = filter_input(INPUT_POST, 'serials', FILTER_SANITIZE_STRING);
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        foreach (explode(',', $serials) as $v) {
            $sql = "UPDATE lab_chrome SET email_google = null, fk_id_pessoa = null WHERE email_google like '$email' and recadastro != 1";
            $query = pdoSis::getInstance()->query($sql);
            $hist['fk_id_pessoa'] = $id_pessoa;
            $hist['fk_id_pessoa_lanc'] = $id_pessoa;
            $hist['obs'] = $nome . '(' . $id_pessoa . ') declarou de o chromebook ' . $v . ' não está sob sua responsabilidade. ';
            $id = $this->db->ireplace('lab_chrome_hist', $hist, 1);
        }
    }

    public function pegaIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = null;
        }

        return $ip;
    }

    public function chromeProfEdit() {
        $ip = $this->pegaIp();
        $id_ch = filter_input(INPUT_POST, 'id_ch', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $serial = filter_input(INPUT_POST, 'serial', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        if (!empty($serial)) {
            $ch = sql::get('lab_chrome', '*', ['serial' => $serial], 'fetch');
            if (empty($ch['serial'])) {
                toolErp::alert('Não existe Chromebook com o número de série ' . $ins['serial']);
            } else {
                $ins['id_ch'] = $ch['id_ch'];
                $ins['fk_id_cd'] = 2;
                $ins['fk_id_cs'] = 1;
                $ins['email_google'] = $email;
                $ins['recadastro'] = 1;
                $ins['fk_id_pessoa'] = $id_pessoa;
                $ins['fk_id_pessoa_lanc'] = $id_pessoa;
                $ins['dt_cad'] = date("Y-m-d H:i:s");
                $ins['recad_ip'] = $ip;
                $id = $this->db->ireplace('lab_chrome', $ins);
                $em = [
                    'fk_id_ch' => $ch['id_ch'],
                    'dt_inicio' => date("Y-m-d"),
                    'fk_id_pessoa' => $id_pessoa,
                    'fk_id_pessoa_lanc' => $id_pessoa,
                    'obs' => 'Cadastro on-line',
                ];
                try {
                    $id_ce = $this->db->ireplace('lab_chrome_emprestimo', $em, 1);
                } catch (Exception $exc) {
                    
                }

                $hist['fk_id_ch'] = $ins['id_ch'];
                $hist['fk_id_cs'] = 1;
                $hist['fk_id_pessoa'] = $id_pessoa;
                $hist['fk_id_cd'] = 2;
                $hist['fk_id_pessoa_lanc'] = $id_pessoa;
                $hist['obs'] = $nome . '(' . $id_pessoa . ') cadastrou para si o chromebook N/S ' . $ch['serial'];
                $id = $this->db->ireplace('lab_chrome_hist', $hist, 1);
            }
            $this->chromeProfDelTodos();
        }
    }

    public function chromenovo() {
        $ins = @$_POST[1];
        if (!empty($ins['serial'])) {
            $ch = sql::get('lab_chrome', '*', ['serial' => $ins['serial']], 'fetch');
            if (empty($ch['serial'])) {
                toolErp::alert('Não existe Chromebook com o número de série ' . $ins['serial']);
            } elseif ($ch['fk_id_inst'] == $ins['fk_id_inst']) {
                toolErp::alert('Este Chromebook já está relacionado à sua escola');
            } else {
                $ins['fk_id_cm'] = $ch['fk_id_cm'];
                $id = $this->db->ireplace('lab_chrome', $ins);
                $ins['fk_id_ch'] = $id;
                unset($ins['serial']);
                unset($ins['fk_id_cm']);
                unset($ins['mac']);
                unset($ins['id_ch']);
                $ch['obs'] = ' cadastrou este chromebook';
                $id = $this->db->ireplace('lab_chrome_hist', $ins, 1);
            }
        }
    }

    public function chromeEdit() {
        $ins = @$_POST[1];
        if (empty($ins['fk_id_cs']) || empty($ins['carrinho'])) {
            tool::alert("Peencha os campos Carrinho e Situação");
            return;
        }
        if (in_array($ins['fk_id_cs'], [5])) {
            $ins['fk_id_inst'] = null;
        }
        $id = $this->db->ireplace('lab_chrome', $ins);
        $ins['fk_id_ch'] = $id;
        unset($ins['serial']);
        unset($ins['fk_id_cm']);
        unset($ins['mac']);
        unset($ins['id_ch']);

        $id = $this->db->ireplace('lab_chrome_hist', $ins, 1);
    }

    /**
     * 
     * @param type $id_inst
     * @return type chromebook da escola por carrinho
     */
    public function chromesEscola($id_inst, $filtro = null) {
        if (!empty($filtro)) {
            $filtro = " and id_ch = $filtro ";
        }
        $sql = "SELECT s.n_cs, p.n_pessoa, c.*, mt.fk_id_ms FROM `lab_chrome` c "
                . " left join lab_chrome_emprestimo e on e.fk_id_ch = c.id_ch and e.dt_fim is null "
                . " left join lab_chrome_manutencao mt on mt.fk_id_ch = c.id_ch and fk_id_ms != 5 "
                . " left join pessoa p on p.id_pessoa = e.fk_id_pessoa "
                . " left join lab_chrome_status s on s.id_cs = c.fk_id_cs "
                . " WHERE `fk_id_inst` = $id_inst "
                . $filtro
                . " ORDER BY carrinho,`serial` ASC";
        $query = pdoSis::getInstance()->query($sql);
        $chr = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($chr)) {
            foreach ($chr as $v) {
                $chromes[$v['carrinho']][$v['serial']] = $v;
            }

            return $chromes;
        } else {
            return;
        }
    }

    public function chromes($id_inst = NULL) {
        if (!empty($id_inst)) {
            $id_inst = " WHERE `fk_id_inst` = $id_inst ";
        }
        $sql = "SELECT l.*, i.id_inst, i.n_inst  FROM `lab_chrome` l "
                . " join instancia i on i.id_inst = l.fk_id_inst  "
                . $id_inst
                . " ORDER BY i.n_inst, carrinho, `serial` ASC";
        $query = pdoSis::getInstance()->query($sql);
        $chr = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($chr)) {
            foreach ($chr as $v) {
                $chromes[$v['n_inst']][$v['carrinho']][$v['serial']] = $v;
            }

            return $chromes;
        } else {
            return;
        }
    }

    /**
     * escolas com chromebook id e nome
     */
    public function escolasOpt() {
        $sql = "select id_inst, n_inst from instancia "
                . " where id_inst in ( "
                . "SELECT DISTINCT `fk_id_inst` FROM `lab_chrome`"
                . ") ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $esc[$v['id_inst']] = $v['n_inst'];
        }

        return $esc;
    }

    public function sitCor() {
        return [
            0 => 'btn btn-outline-danger',
            1 => 'btn btn-success',
            2 => 'btn btn-warning',
            3 => 'btn btn-info',
            4 => 'btn btn-warning',
            6 => 'btn btn-danger',
            8 => 'btn btn-primary'
        ];
    }

    public function nomeAluno() {
        $ra = filter_input(INPUT_POST, 'ra', FILTER_SANITIZE_NUMBER_INT);
        if (!empty($ra)) {
            $nome = sql::get(['pessoa', 'ge_turma_aluno'], 'n_pessoa', ['id_pessoa' => $ra], 'fetch')['n_pessoa'];
        }
        if ($nome) {
            ob_clean();
            ob_start();
            echo $nome;
        }
    }

    public function emprestaAluno($id_inst) {
        $sql = "SELECT "
                . " c.id_ch, c.serial, c.email_google, p.n_pessoa, p.sexo, p.id_pessoa, f.rm, c.fk_id_modem, e.id_ce "
                . " FROM lab_chrome c "
                . " join lab_chrome_emprestimo e on c.id_ch = e.fk_id_ch and e.dt_fim is null "
                . " JOIN lab_chrome_modelo m on m.id_cm = c.fk_id_cm "
                . " JOIN pessoa p on p.id_pessoa = e.fk_id_pessoa "
                . " JOIN ge_funcionario f on f.fk_id_pessoa = c.fk_id_pessoa"
                . " WHERE c.fk_id_inst = $id_inst "
                . " ORDER BY p.n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $v) {
                $alu[$v['id_pessoa']] = $v;
            }
        }


        $sql = "SELECT "
                . " c.id_ch, c.serial, c.email_google, p.n_pessoa, p.sexo, p.id_pessoa, ta.situacao, t.n_turma, pl.at_pl, c.fk_id_modem, e.id_ce "
                . " FROM lab_chrome c "
                . " JOIN lab_chrome_modelo m on m.id_cm = c.fk_id_cm "
                . " join lab_chrome_emprestimo e on c.id_ch = e.fk_id_ch and e.dt_fim is null "
                . " JOIN pessoa p on p.id_pessoa = e.fk_id_pessoa "
                . " LEFT JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa "
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " WHERE c.fk_id_inst = $id_inst "
                . " ORDER BY p.n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $v) {
                $alu[$v['id_pessoa']] = $v;
            }
        }

        $sql = "SELECT "
                . "  p.n_pessoa, p.sexo, p.id_pessoa "
                . " FROM lab_chrome c "
                . " join lab_chrome_emprestimo e on c.id_ch = e.fk_id_ch and e.dt_fim is null "
                . " JOIN pessoa p on p.id_pessoa = e.fk_id_pessoa "
                . " WHERE c.fk_id_inst = $id_inst "
                . " order by p.n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $v) {
                if (!empty($alu[$v['id_pessoa']])) {
                    $pessoa[$v['id_pessoa']] = $alu[$v['id_pessoa']];
                } else {
                    $pessoa['id_pessoa'] = $v;
                }
            }
        }
        if (!empty($pessoa)) {
            return $pessoa;
        }
    }

    public function alunoEscola($id_inst) {
        $sql = " SELECT "
                . " p.n_pessoa, p.id_pessoa, p.sexo, t.n_turma "
                . " FROM pessoa p "
                . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa "
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma and t.fk_id_inst = $id_inst "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1 "
                . " ORDER by p.n_pessoa ";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $alu[$v['id_pessoa']] = $v['n_pessoa'] . ' (' . $v['id_pessoa'] . ') - ' . $v['n_turma'];
        }

        return @$alu;
    }

    public function funcionarios($id_inst) {
        $sql = "SELECT "
                . " p.n_pessoa, p.id_pessoa, f.rm "
                . " FROM ge_funcionario f "
                . " JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " WHERE `fk_id_inst` = $id_inst "
                . " order by p.n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $prof[$v['id_pessoa']] = $v['n_pessoa'] . ' (' . $v['rm'] . ') - Funcionário';
        }

        return @$prof;
    }

    public function chromeRedeWhere($par = []) {
        $where = '';
        extract($par);
        if (!empty($serial)) {
            $where .= " and serial like '$serial' ";
        }

        if (!empty($modelo)) {
            $where .= " and fk_id_cm = $modelo ";
        }

        if (!empty($destino)) {
            $destinoSet = [
                1 => " and e.id_ce is null and c.fk_id_inst is not null ",
                2 => " and e.id_ce is not null and c.fk_id_inst is null ",
                3 => " and e.id_ce is not null and c.fk_id_inst is not null ",
                4 => " and mt.fk_id_ms in (1, 2, 3) ",
                5 => " and e.id_ce is null and c.fk_id_inst is null ",
                6 => "  and mt.fk_id_ms = 6  ",
                7 => "  and mt.fk_id_ms = 4  ",
            ];
            if (in_array($destino, array_keys($destinoSet))) {
                $where .= $destinoSet[$destino];
            } else {
                $where .= " and fk_id_cd = $destino ";
            }
        }

        if (!empty($situacao)) {
            if ($situacao == 'y') {
                $where .= " and fk_id_cs = 0 ";
            } elseif ($situacao == 'x') {
                $where .= " and fk_id_cs = 0 ";
                $where .= " and fk_id_cd in( 1,3 )";
            } elseif ($situacao != 3) {
                $where .= " and fk_id_cs = $situacao ";
            } else {
                $where .= " and fk_id_cs in( 1,3 )";
            }
            if ($situacao != 'y') {
                // $where .= ' and c.fk_id_inst is not null';
            }
        }

        if (!empty($resp)) {
            $pessoa = sql::get('pessoa', 'id_pessoa', " where n_pessoa like '%$resp%' ");
            $ids = array_column($pessoa, 'id_pessoa');
            $where .= " and e.fk_id_pessoa in ('" . implode("','", $ids) . "') ";
        }

        if (!empty($cpf)) {
            $where .= " and p.cpf = '$cpf'";
        }

        if (!empty($rm)) {
            $where .= " and f.rm = '$rm'";
        }

        if (!empty($email)) {
            $where .= " and p.emailgoogle = '$email'";
        }

        if (!empty($id_inst)) {
            $where .= " and c.fk_id_inst = '$id_inst'";
        }

        if (!empty($resumoTp)) {
            if ($resumoTp == 1) {
                $where .= " and c.fk_id_inst is not null";
            } elseif ($resumoTp == 2) {
                $where .= " and c.fk_id_inst is null";
            }
        }
        if (!empty($id_cs) || @$id_cs == '0') {
            $where .= " and fk_id_cs = " . intval($id_cs);
        }
        if (!empty($id_ch)) {
            $where .= " and id_ch = " . $id_ch;
        }
        return $where;
    }

    public function chromeRedeSql($par = [], $fields = null) {
        extract($par);
        if (empty($fields)) {
            $fields = " c.id_ch ID, c.serial Serial, i.n_inst Escola, c.mac MAC, c.carrinho Carrinho, c.dt_cad Cadastro, "
                    . " p.n_pessoa Nome, p.id_pessoa, p.sexo Sexo, p.cpf CPF, p.emailgoogle, m.n_cm Modelo, s.n_cs Status, f.rm";
        }
        $where = $this->chromeRedeWhere($par);

        $sql = "SELECT "
                . $fields
                . " FROM lab_chrome c "
                . " JOIN lab_chrome_modelo m on m.id_cm = c.fk_id_cm "
                . " JOIN lab_chrome_status s on s.id_cs = c.fk_id_cs "
                . " left join lab_chrome_emprestimo e on e.fk_id_ch = c.id_ch and e.dt_fim is null "
                . " left join lab_chrome_manutencao mt on mt.fk_id_ch = c.id_ch and mt.fk_id_ms != 5 "
                . " LEFT JOIN pessoa p on p.id_pessoa = c.fk_id_pessoa "
                . " LEFT JOIN ge_funcionario f on f.fk_id_pessoa = c.fk_id_pessoa "
                . " LEFT join instancia i on  i.id_inst = c.fk_id_inst "
                . " where 1 "
                . $where
                . " "
                . " ORDER BY n_pessoa";

        return $sql;
    }

    public function chromeRedeEmprestado($par = [], $fields = null, $todos = null, $id_inst = null) {
        if ($id_inst) {
            $id_inst = " and c.fk_id_inst = $id_inst";
        } else {
            $id_inst = " and c.fk_id_inst is null";
        }
        if ($todos) {
            $todos = null;
        } else {
            $todos = " AND e.dt_fim is null ";
        }
        extract($par);
        if (empty($fields)) {
            $fields = " e.id_ce, e.dt_inicio, e.dt_fim, e.time_stamp, e.obs, c.id_ch, c.serial, c.fk_id_inst, c.mac, "
                    . " p.n_pessoa, p.id_pessoa, p.sexo, p.cpf, p.emailgoogle, f.rm, n_cm ";
        }
        $where = $this->chromeRedeWhere($par);
        $limit = empty($limit) ? 0 : $limit;

        $sql = "SELECT "
                . $fields
                . " FROM lab_chrome_emprestimo e "
                . " JOIN lab_chrome c on c.id_ch = e.fk_id_ch "
                . " JOIN lab_chrome_modelo cm on cm.id_cm = c.fk_id_cm "
                . " JOIN pessoa p on p.id_pessoa = e.fk_id_pessoa "
                . " left JOIN ge_funcionario f on f.fk_id_pessoa = e.fk_id_pessoa"
                . " where 1 "
                . $where
                . $id_inst
                . $todos
                . " ORDER BY n_pessoa"
                . " limit $limit, $tuplas ";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $cr = [];
        foreach ($array as $k => $v) {
            if (!empty($v['rm'])) {
                if (array_key_exists($v['id_ch'], $cr)) {
                    $cr[$v['id_ch']]['rm'] = $cr[$v['id_ch']]['rm'] . '/' . $v['rm'];
                } else {
                    $cr[$v['id_ch']] = $v;
                }
            } else {
                $cr[$v['id_ch']] = $v;
            }
        }
        unset($array);
        if (!empty($cr)) {
            return $cr;
        }
    }

    public function chromeRedeEmprestadoCount($par = [], $todos = null, $id_inst = null) {
        if ($id_inst) {
            $id_inst = " and c.fk_id_inst = $id_inst";
        } else {
            $id_inst = " and c.fk_id_inst is null";
        }
        if ($todos) {
            $todos = null;
        } else {
            $todos = " AND e.dt_fim is null ";
        }
        extract($par);

        $where = $this->chromeRedeWhere($par);
        $limit = empty($limit) ? 0 : $limit;

        $sql = "SELECT "
                . "count(DISTINCT(id_ce)) ct "
                . " FROM lab_chrome_emprestimo e "
                . " JOIN lab_chrome c on c.id_ch = e.fk_id_ch "
                . " JOIN pessoa p on p.id_pessoa = e.fk_id_pessoa "
                . " left JOIN ge_funcionario f on f.fk_id_pessoa = e.fk_id_pessoa"
                . " where 1 "
                . $where
                . $id_inst
                . $todos
                . " ORDER BY n_pessoa"
                . " limit $limit, $tuplas ";

        $query = pdoSis::getInstance()->query($sql);
        $count = $query->fetch(PDO::FETCH_ASSOC)['ct'];

        return $count;
    }

    public function chromeRede($par = [], $fields = null) {

        extract($par);
        if (empty($fields)) {
            $fields = " DISTINCT c.id_ch, c.serial, c.fk_id_inst, c.mac, c.carrinho, c.recadastro, c.dt_cad, "
                    . " p.n_pessoa, p.id_pessoa, p.sexo, p.cpf, p.emailgoogle, m.n_cm, s.n_cs, f.rm, mt.fk_id_ms ";
        }
        $where = $this->chromeRedeWhere($par);
        $limit = empty($limit) ? 0 : $limit;

        $sql = "SELECT "
                . $fields
                . " FROM lab_chrome c "
                . " JOIN lab_chrome_modelo m on m.id_cm = c.fk_id_cm "
                . " JOIN lab_chrome_status s on s.id_cs = c.fk_id_cs "
                . " left join lab_chrome_emprestimo e on e.fk_id_ch = c.id_ch and e.dt_fim is null "
                . " left join lab_chrome_manutencao mt on mt.fk_id_ch = c.id_ch and mt.fk_id_ms != 5"
                . " LEFT JOIN pessoa p on p.id_pessoa = e.fk_id_pessoa "
                . " LEFT JOIN ge_funcionario f on f.fk_id_pessoa = e.fk_id_pessoa "
                . " where 1 "
                . $where
                . " "
                . " ORDER BY n_pessoa"
                . " limit $limit, $tuplas ";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $cr = [];
        foreach ($array as $k => $v) {
            if (!empty($v['rm'])) {
                if (array_key_exists($v['id_ch'], $cr)) {
                    $cr[$v['id_ch']]['rm'] = $cr[$v['id_ch']]['rm'] . '/' . $v['rm'];
                } else {
                    $cr[$v['id_ch']] = $v;
                }
            } else {
                $cr[$v['id_ch']] = $v;
            }
        }
        unset($array);
        if (!empty($cr)) {
            return $cr;
        }
    }

    public function chromeRedeCount($par = []) {
        extract($par);
        $where = $this->chromeRedeWhere($par);

        $sql = "SELECT count(DISTINCT(serial)) ct FROM lab_chrome c "
                . " JOIN lab_chrome_modelo m on m.id_cm = c.fk_id_cm "
                . " JOIN lab_chrome_status s on s.id_cs = c.fk_id_cs "
                . " left join lab_chrome_emprestimo e on e.fk_id_ch = c.id_ch and e.dt_fim is null "
                . " left join lab_chrome_manutencao mt on mt.fk_id_ch = c.id_ch and mt.fk_id_ms != 5"
                . " LEFT JOIN pessoa p on p.id_pessoa = e.fk_id_pessoa "
                . " LEFT JOIN ge_funcionario f on f.fk_id_pessoa = e.fk_id_pessoa "
                . " WHERE 1 "
                . $where;

        $query = pdoSis::getInstance()->query($sql);
        $count = $query->fetch(PDO::FETCH_ASSOC)['ct'];

        return $count;
    }

    public function relatGeral($par = []) {
        $tuplas = 100;
        $par['tuplas'] = $tuplas;
        $chromeCount = $this->chromeRedeCount($par);


        $par['limit'] = report::pagination($tuplas, $chromeCount, $par);

        $chrome = $this->chromeRede($par);

        if (!empty($chrome)) {
            foreach ($chrome as $k => $v) {
                $chrome[$k]['ac'] = '<button class="btn btn-info" onclick="ch(' . $v['id_ch'] . ')">Acessar</button>';
            }
            $form['array'] = $chrome;
            $form['fields'] = [
                'nº de Série' => 'serial',
                'Modelo' => 'n_cm',
                'Situação' => 'n_cs',
                'Responsável' => 'n_pessoa',
                'Matrícula' => 'rm',
                'E-mail' => 'emailgoogle',
                '||1' => 'ac'
            ];
            echo '<br /><br />';
            report::simple($form);

            if (!empty($chrome)) {
                return 1;
            }
        } else {
            ?>
            <br /><br />
            <div class="alert alert-danger" style="text-align: center; width:  300px; margin: 0 auto">
                Não Encontrado
            </div>
            <?php
        }
    }

    public function dashboard() {
        $sql = "SELECT "
                . " c.id_ch, m.n_cm, s.n_cs, c.fk_id_inst, c.fk_id_pessoa, c.recadastro, c.dt_cad, c.fk_id_cs "
                . " FROM lab_chrome c "
                . " join lab_chrome_modelo m on m.id_cm = c.fk_id_cm "
                . " join lab_chrome_status s on s.id_cs = c.fk_id_cs ";
        $query = pdoSis::getInstance()->query($sql);
        $ch = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($ch as $v) {
            @$modelo[$v['n_cm']]++;
            if ($v['fk_id_cs'] == 3) {
                $n_cs = 'Regular';
            } else {
                $n_cs = $v['n_cs'];
            }
            @$sta[$n_cs]++;
            if ($v['recadastro'] != 1 && !empty($v['fk_id_pessoa'])) {
                @$aluno++;
            }
            if ($v['recadastro'] == 1 && !empty($v['fk_id_pessoa'])) {
                @$func++;
                @$dataCad[substr($v['dt_cad'], 0, 10)]++;
            }
        }
        $mod = '';
        foreach ($modelo as $k => $v) {
            $mod .= $k . ' -> ' . $v . '<br />';
        }
        $status = '';
        foreach ($sta as $k => $v) {
            $status .= $k . ' -> ' . $v . '<br />';
        }
        $dash['terminal'][1] = 'Total de Chromebook: ' . count($ch) . '<br />';
        $dash['terminal'][2] = 'Chromebooks emprestados pela escola:' . intval(@$aluno) . '<br />';
        $dash['terminal'][3] = 'Chromebooks emprestados pela Secretaria:' . intval(@$func) . '<br /><br />';
        $dash['terminal'][4] = 'Total de Chromebook por Modelo: <br />' . $mod . '<br />';
        $dash['terminal'][5] = 'Total de Chromebook por Situação: <br />' . $status . '<br />';
        $dash['dataCad'] = @$dataCad;

        return $dash;
    }

    public function chromebook($id_ch = null, $serial = null) {
        if ($id_ch) {
            $search = " where c.id_ch = $id_ch ";
        } elseif ($serial) {
            $search = " where c.serial like '$serial' ";
        } else {
            return;
        }
        $sql = "SELECT "
                . " c.`id_ch`, c.`serial`, c.`fk_id_cm`, c.`fk_id_inst`, c.`mac`, c.`carrinho`, c.`fk_id_cs`, c.`fk_id_pessoa_lanc`, "
                . " i.n_inst, "
                . " p.n_pessoa, p.id_pessoa, p.sexo, p.cpf, p.emailgoogle, m.n_cm, s.n_cs, f.rm, mt.fk_id_ms "
                . " FROM lab_chrome c "
                . " JOIN lab_chrome_modelo m on m.id_cm = c.fk_id_cm "
                . " JOIN lab_chrome_status s on s.id_cs = c.fk_id_cs "
                . " left join lab_chrome_emprestimo e on e.fk_id_ch = c.id_ch and e.dt_fim is null"
                . " left join lab_chrome_manutencao mt on mt.fk_id_ch = c.id_ch and mt.fk_id_ms != 5 "
                . " LEFT JOIN pessoa p on p.id_pessoa = e.fk_id_pessoa "
                . " LEFT JOIN ge_funcionario f on f.fk_id_pessoa = e.fk_id_pessoa "
                . " left join instancia i on i.id_inst = c.fk_id_inst "
                . $search;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    public function devolveEsc($id_hist) {
        $sql = "SELECT "
                . " p.n_pessoa, p.id_pessoa, p.sexo, p.responsavel, p.cpf, p.emailgoogle, h.obs, h.time_stamp, c.serial "
                . " FROM lab_chrome_hist h "
                . " JOIN pessoa p on p.id_pessoa = h.fk_id_pessoa "
                . " JOIN lab_chrome c on c.id_ch = h.fk_id_ch "
                . " JOIN instancia i on i.id_inst = c.fk_id_inst "
                . " WHERE h.id_hist = $id_hist ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    public function devolveProf($id_hist) {
        $sql = "SELECT "
                . " p.n_pessoa, p.id_pessoa, p.sexo, p.cpf, p.emailgoogle, h.obs, h.time_stamp, c.serial, f.rm, i.n_inst "
                . " FROM lab_chrome_hist h "
                . " JOIN pessoa p on p.id_pessoa = h.fk_id_pessoa "
                . " JOIN lab_chrome c on c.id_ch = h.fk_id_ch "
                . " left join ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
                . " left join instancia i on i.id_inst = f.fk_id_inst "
                . " WHERE h.id_hist = $id_hist ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    public function emprestProf($search) {
        $sql = " SELECT "
                . " p.n_pessoa, p.id_pessoa, p.emailgoogle, p.cpf, f.rm, i.n_inst "
                . " FROM pessoa p "
                . " LEFT JOIN ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
                . " LEFT JOIN instancia i on i.id_inst = f.fk_id_inst "
                . " WHERE p.n_pessoa LIKE '%$search%' "
                . " OR p.emailgoogle LIKE '%$search%' "
                . " OR p.cpf = '$search' "
                . " OR f.rm = '$search' "
                . " order by p.n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function pessoaFunc($id_pessoa) {
        $sql = " SELECT "
                . " p.n_pessoa, p.id_pessoa, p.id_pessoa as fk_id_pessoa, p.emailgoogle, p.cpf, f.rm, i.n_inst "
                . " FROM pessoa p LEFT JOIN ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
                . " LEFT JOIN instancia i on i.id_inst = f.fk_id_inst "
                . " WHERE p.id_pessoa = $id_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    public function pessoaCh($id_ch) {
        $sql = " SELECT "
                . " p.n_pessoa, p.id_pessoa, p.id_pessoa as fk_id_pessoa, p.emailgoogle, p.cpf, f.rm, i.n_inst, cs.n_cs, c.* "
                . " FROM pessoa p LEFT JOIN ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
                . " join lab_chrome c on c.fk_id_pessoa = p.id_pessoa "
                . " join lab_chrome_status cs on cs.id_cs = c.fk_id_cs "
                . " LEFT JOIN instancia i on i.id_inst = f.fk_id_inst "
                . " WHERE c.id_ch = $id_ch ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    public function historico($id_pessoa) {
        $sql = "select o.obs, o.time_stamp from lab_chrome_emprestimo_ocorrencia o "
                . " LEFT join lab_chrome_emprestimo_ocorrencia_tipo ot on ot.id_ceot = o.fk_id_ceot "
                . " where o.fk_id_pessoa = $id_pessoa "
                . " order by time_stamp ";
        $query = pdoSis::getInstance()->query($sql);
        $hist = $query->fetchAll(PDO::FETCH_ASSOC);
        $sql = "SELECT * FROM `lab_chrome_emprestimo` e "
                . " left join lab_chrome c on c.id_ch = e.fk_id_ch "
                . " WHERE e.`fk_id_pessoa` = $id_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $hist2 = $this->histList(null, null, $id_pessoa);

        if (!empty($hist) || !empty($array) || !empty($hist2)) {
            ?>
            <br /><br />
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <td class="text-center" colspan="2">
                        Histórico
                    </td>
                </tr>
                <tr>
                    <td>
                        Data
                    </td>
                    <td>
                        Ocorrência
                    </td>
                </tr>
                <?php
                foreach ($hist as $v) {
                    ?>
                    <tr>
                        <td>
                            <?= data::converteBr($v['time_stamp']) ?>
                        </td>
                        <td>
                            <?= $v['obs'] ?>
                        </td>
                    </tr>
                    <?php
                }
                foreach ($hist2 as $v) {
                    ?>
                    <tr>
                        <td>
                            <?= data::converteBr($v['time_stamp']) ?>
                        </td>
                        <td>
                            <?= $v['obs'] ?>
                        </td>
                    </tr>
                    <?php
                }
                if ($hist2) {
                    foreach ($array as $v) {
                        ?>
                        <tr>
                            <td>
                                <?= data::converteBr($v['time_stamp']) ?>
                            </td>
                            <td>
                                Emprestou chromebook n/s: <?= $v['serial'] ?>
                                <?php
                                if (!empty($v['dt_fim'])) {
                                    echo '<br /> e devolveu em ' . data::converteBr($v['dt_fim']);
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
            <?php
        }
    }

    public function histList($pessoa = null, $id_ch = null, $id_pessoa = null) {
        if ($id_pessoa) {
            $where = " WHERE id_pessoa = $id_pessoa ";

            $pessoa = null;
        } elseif ($pessoa) {
            $pessoa = " or p.n_pessoa LIKE '%$pessoa%' "
                    . " OR p.emailgoogle LIKE '%$pessoa%' "
                    . " OR p.cpf = '$pessoa' "
                    . " OR f.rm = '$pessoa' ";
            $where = " WHERE h.fk_id_ch like '$id_ch'";
        }
        $sql = " SELECT "
                . " p.n_pessoa, p.id_pessoa, p.emailgoogle, p.cpf, f.rm, h.obs, h.devolucao, h.time_stamp, h.id_hist "
                . " FROM pessoa p LEFT JOIN ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
                . " join lab_chrome_hist h on h.fk_id_pessoa = p.id_pessoa "
                . $where
                . $pessoa
                . " order by h.time_stamp desc ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function servidor($id_pessoa = null, $cpf = null, $rm = null) {
        if (!empty($cpf)) {
            $cpf = " and p.cpf = '$cpf' ";
        }
        if (!empty($rm)) {
            $rm = " and f.rm = '$rm' ";
        }
        if ($id_pessoa) {
            $id_pessoa = " and p.id_pessoa = '$id_pessoa' ";
        }
        $sql = " SELECT "
                . " p.n_pessoa, p.id_pessoa, p.emailgoogle, p.cpf, f.rm, i.n_inst  "
                . " FROM pessoa p "
                . " LEFT JOIN ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
                . " LEFT JOIN instancia i on i.id_inst = f.fk_id_inst "
                . " WHERE 1 "
                . $cpf
                . $rm
                . $id_pessoa;

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    public function modemSel($id_inst = null) {
        if ($id_inst) {
            $id_inst = " where m.fk_id_inst = $id_inst ";
        }
        $sql = "select m.id_modem, m.serial, mm.n_mm from lab_modem m "
                . "join lab_modem_modelo mm on mm.id_mm = m.fk_id_mm "
                . $id_inst;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $modem[$v['id_modem']] = $v['n_mm'] . ' - ' . $v['serial'];
        }
        if (!empty($modem)) {
            return $modem;
        }
    }

    public function histEmprestimo($id_pessoa = null, $id_inst = null) {
        if ($id_pessoa) {
            $and = " and h.fk_id_pessoa = $id_pessoa ";
            $limit = null;
        } else {
            if ($id_inst) {
                $and = " and h.fk_id_inst = $id_inst ";
            } else {
                $and = null;
            }
            $limit = " limit 0, 100";
        }
        $sql = "SELECT distinct h.id_hist, h.obs, h.time_stamp, i.n_inst, c.serial, h.devolucao, p.n_pessoa FROM `lab_chrome_hist` h "
                . " left join instancia i on i.id_inst = h.fk_id_inst "
                . " left join lab_chrome c on c.id_ch = h.fk_id_ch "
                . " left join pessoa p on p.id_pessoa = c.fk_id_pessoa_lanc "
                . " WHERE 1 "
                . $and
                . " ORDER BY `time_stamp` DESC"
                . $limit;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function modems($id_inst = null, $serial = null) {
        $sql = "SELECT m.serial, m.at_modem, m.id_modem, mm.n_mm, i.n_inst FROM lab_modem m "
                . " JOIN lab_modem_modelo mm on mm.id_mm = m.fk_id_mm "
                . " join instancia i on i.id_inst = m.fk_id_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function movimenta($liberado = null) {
        if ($liberado) {
            $liberado = " where c.block != 1 or c.block is null ";
        }
        $sql = "SELECT "
                . " m.times_stamp, m.obs, c.id_ch, c.serial, c.block, i.id_inst, i.n_inst, p.id_pessoa, p.n_pessoa "
                . " FROM lab_chrome_mov m "
                . " JOIN lab_chrome c on c.id_ch = m.fk_id_ch "
                . " JOIN instancia i on i.id_inst = m.fk_id_inst "
                . " JOIN pessoa p on p.id_pessoa = m.fk_id_pessoa "
                . $liberado
                . " ORDER BY m.times_stamp "
                . " LIMIT 0, 300";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $mov[$v['id_ch']] = $v;
        }
        if (!empty($mov)) {
            array_reverse($mov);
            return $mov;
        }
    }

    public function emppretimo($id_ce = null, $todos = null, $id_ch = null, $id_pessoa = null) {
        if ($todos) {
            $todos = null;
        } else {
            $todos = " AND e.dt_fim is null ";
        }
        if ($id_ch) {
            $id_ch = " AND e.fk_id_ch = $id_ch ";
        }
        if ($id_pessoa) {
            $id_pessoa = " AND e.fk_id_pessoa = $id_pessoa ";
        }
        if ($id_ce) {
            $id_ce = " AND e.id_ce = $id_ce ";
        }
        $sql = " SELECT "
                . " e.id_ce, e.dt_inicio, e.dt_fim, e.obs, e.fk_id_ces_equip, e.fk_id_ces_carr, "
                . " ese.equipamento, esc.carregador, "
                . " c.serial, c.fk_id_cm, c.fk_id_inst, c.id_ch, c.mac, cm.n_cm, "
                . " p.n_pessoa, p.id_pessoa, p.sexo, p.cpf, p.emailgoogle, f.rm, i.n_inst "
                . " FROM lab_chrome_emprestimo e "
                . " JOIN lab_chrome_emprestimo_sit ese on ese.id_ces = e.fk_id_ces_equip "
                . " JOIN lab_chrome_emprestimo_sit esc on esc.id_ces = e.fk_id_ces_carr "
                . " JOIN lab_chrome c on c.id_ch = e.fk_id_ch "
                . " JOIN lab_chrome_modelo cm on cm.id_cm = c.fk_id_cm "
                . " JOIN pessoa p on p.id_pessoa = e.fk_id_pessoa "
                . " LEFT JOIN ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
                . " LEFT JOIN instancia i on i.id_inst = f.fk_id_inst"
                . " WHERE 1 "
                . $id_pessoa
                . $id_ch
                . $id_ce
                . $todos;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    public function chrmeAtivo($id_pessoa) {
        $sql = "SELECT COUNT(id_ce) ct FROM `lab_chrome_emprestimo` "
                . " WHERE `fk_id_pessoa` = $id_pessoa "
                . " AND `dt_fim` IS NULL";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array['ct'];
    }

    public function ocorrencia($id_ce) {
        $sql = "select * from lab_chrome_emprestimo_ocorrencia o "
                . " LEFT join lab_chrome_emprestimo_ocorrencia_tipo ot on ot.id_ceot = o.fk_id_ceot "
                . " where o.fk_id_ce = $id_ce "
                . " order by time_stamp ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function chromeDisponivel() {
        $sql = "SELECT id_ch, serial n_serial FROM lab_chrome "
                . " WHERE fk_id_inst is null "
                . " AND id_ch not in "
                . " ( "
                . " SELECT fk_id_ch FROM `lab_chrome_emprestimo`"
                . " WHERE `dt_fim` IS NULL "
                . " )";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return toolErp::idName($array);
    }

#####################################   Mario ##################################

    public function pegaquadroescola() {
        //cria as variaveis
        $sql = "SELECT DISTINCT lc.fk_id_inst  FROM lab_chrome lc";
        $query = pdoSis::getInstance()->query($sql);
        $escola = $query->fetchAll(PDO::FETCH_ASSOC);

        $st = [
            0 => 'Indefinido',
            1 => 'Regular',
            2 => 'Em Manutenção',
            3 => 'Emprestado',
            4 => 'Quebrado (enviado para manutenção)',
            6 => 'Não Alocado',
            8 => 'Foi dado Baixa',
        ];

        foreach ($escola as $v) {
            foreach ($st as $k => $w) {
                $dados[$v['fk_id_inst']][$k] = 0;
            }
        }

        $sql2 = "SELECT lc.fk_id_inst, lc.fk_id_cs,"
                . " COUNT(lc.fk_id_cs) AS Total FROM lab_chrome lc"
                . " GROUP BY lc.fk_id_inst, lc.fk_id_cs";

        $query = pdoSis::getInstance()->query($sql2);
        $d = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($d as $w) {
            if ($w['fk_id_cs'] == 4) {
                $dados[$w['fk_id_inst']][2] = $dados[$w['fk_id_inst']][2] + $w['Total'];
            } else {
                $dados[$w['fk_id_inst']][$w['fk_id_cs']] = $dados[$w['fk_id_inst']][$w['fk_id_cs']] + $w['Total'];
            }
        }

        return $dados;
    }

    public function pegaescola() {
        $sql = "SELECT DISTINCT i.id_inst, i.n_inst FROM lab_chrome lc"
                . " JOIN instancia i ON i.id_inst = lc.fk_id_inst"
                . " ORDER BY i.n_inst";

        $query = pdoSis::getInstance()->query($sql);
        $e = $query->fetchAll(PDO::FETCH_ASSOC);

        //$es[NULL] = 'NULL';

        foreach ($e as $v) {
            $es[$v['id_inst']] = $v['n_inst'];
        }

        unset($es[1]);

        return $es;
    }

    public function pegatotalstatus() {
        $st = [
            0 => 'Indefinido',
            1 => 'Regular',
            2 => 'Em Manutenção',
            3 => 'Emprestado',
            // 4 => 'Quebrado (enviado para manutenção)',
            6 => 'Não Alocado',
            8 => 'Foi dado Baixa',
        ];

        foreach ($st as $k => $v) {
            $s[$k] = 0;
        }

        $sql = "SELECT lc.fk_id_cs, COUNT(lc.fk_id_cs) AS Total FROM lab_chrome lc"
                . " GROUP BY lc.fk_id_cs, lc.fk_id_inst"
                . " HAVING lc.fk_id_inst != 1 OR lc.fk_id_inst IS NOT NULL";

        $query = pdoSis::getInstance()->query($sql);
        $status = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($status as $k => $v) {
            if ($v['fk_id_cs'] == 4) {
                $s[2] = $s[2] + $v['Total'];
            } else {
                $s[$v['fk_id_cs']] = $s[$v['fk_id_cs']] + $v['Total'];
            }
        }

        return $s;
    }

    public function manut($id_manut = null, $id_ch = null, $statusNao = null) {
        if ($id_manut) {
            $search = " WHERE m.id_manut = $id_manut ";
        } elseif ($id_ch) {
            $search = " WHERE m.fk_id_ch = $id_ch ";
        }
        if ($statusNao) {
            $statusNao = " and fk_id_ms not in ($statusNao) ";
        }
        if (!empty($search)) {
            $sql = "SELECT * FROM lab_chrome_manutencao m "
                    . " join lab_chrome_manutencao_status s on s.id_ms = m.fk_id_ms "
                    . " $search "
                    . " $statusNao ";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetch(PDO::FETCH_ASSOC);

            return $array;
        }
    }

    public function manuts($status = null, $serial = null) {
        if ($status) {
            $status = " and s.id_ms in ($status) ";
        } else {
            $status = " and s.id_ms not in (4, 5, 6) ";
        }
        if ($serial) {
            $serial = " and c.serial like '$serial' ";
        }
        $sql = " SELECT c.serial, c.id_ch, cm.n_cm, s.n_ms, m.time_stamp, m.id_manut  FROM lab_chrome_manutencao m "
                . " join lab_chrome_manutencao_status s on s.id_ms = m.fk_id_ms "
                . " join  lab_chrome c on c.id_ch = m.fk_id_ch "
                . " join lab_chrome_modelo cm on cm.id_cm = c.fk_id_cm "
                . " where 1 "
                . $status
                . $serial;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function destino($id_inst, $id_pessoa, $id_ms) {
        $manut = sql::idNome('lab_chrome_manutencao_status');
        $destino = sql::idNome('lab_chrome_destino');


        if (!empty($id_ms)) {
            $dest = $manut[$id_ms];
        } elseif (empty($id_inst) && empty($id_pessoa)) {
            $dest = $destino[5];
        } elseif (empty($id_inst) && !empty($id_pessoa)) {
            $dest = $destino[2];
        } elseif (!empty($id_inst) && !empty($id_pessoa)) {
            $dest = $destino[3];
        } elseif (!empty($id_inst) && empty($id_pessoa)) {
            $dest = $destino[1];
        } else {
            $dest = null;
        }

        return $dest;
    }

}
