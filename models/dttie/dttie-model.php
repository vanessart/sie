<?php

class dttieModel extends MainModel {

    public $_last_id;
    public $db;

    public function __construct($db = false, $controller = null) {
        // Configura o DB (PDO)
        $this->db = new DB();

        // Configura o controlador
        $this->controller = $controller;

        // Configura os parâmetros
        //$this->parametros = $this->controller->parametros;
        // Configura os dados do usuário
        $this->userdata = $this->controller->userdata;

        if (!empty($_POST['salvSuporte'])) {
            @$this->_last_id = $_POST['last_id'];
            if (!empty($_POST['last_id'])) {
                $texto = $_SESSION['userdata']['n_pessoa'] . " abriu o serviço.";
                $this->logset($_POST['last_id'], $texto);
            } elseif (!empty($_POST[1]['id_sup'])) {

                if ((@$_POST['prev_old'] != @$_POST[1]['dt_prev_sup']) && !empty(@$_POST['prev_old'])) {
                    $texto = $_SESSION['userdata']['n_pessoa'] . " alterou a previsão de entrega para " . @$_POST[1]['dt_prev_sup'];
                    $this->logset($_POST[1]['id_sup'], $texto);
                }
                if ((@$_POST['status_old'] != @$_POST[1]['status_sup']) && !empty(@$_POST['status_old'])) {
                    $texto = $_SESSION['userdata']['n_pessoa'] . " alterou o status do pedido para " . @$_POST[1]['status_sup'];
                    $this->logset($_POST[1]['id_sup'], $texto);
                }
            }
        }
        if (!empty($_POST['cancelaChamado'])) {
            $this->cancelachamadoaluno($_POST['id_sup']);
        }
        if (!empty($_POST['salvEscola'])) {
            @$this->_last_id = $_POST['last_id'];
            @$retform = $_POST['retorno'];
            if (!empty($_POST['last_id'])) {
                $texto = $_SESSION['userdata']['n_pessoa'] . " abriu o serviço.";
                $this->logset($_POST['last_id'], $texto);
            } elseif (@$_POST[1]['status_sup'] == 'Cancelado') {
                $texto = $_SESSION['userdata']['n_pessoa'] . " Cancelou a solicitação ";
                $this->logset($_POST[1]['id_sup'], $texto);
                echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/dttie/' . $retform . '"</script>';
            }
        }


        if (DB::sqlKeyVerif('dttie_suporte_trab')) {
            if (empty($_POST['descr'])) {
                unset($_POST[1]['ultimo_lado']);
            }

            if (!empty($_POST[1]['resp_sup']) AND ($_POST[1]['status_sup'] == 'Não Visualizado')) {
                $_POST[1]['status_sup'] = 'Atribuído';
            }

            $_POST[1]['dt_prev_sup'] = data::converteUS(@$_POST[1]['dt_prev_sup']);
            $this->_last_id = $id_sup = $this->db->ireplace('dttie_suporte_trab', $_POST[1]);

            if (!empty($_POST['id_sup'])) {
                $id = $_POST['id_sup'];
            } else {
                $id = $id_sup;
            }
            if (!empty($_POST['descr'])) {
                $d['fk_id_sup'] = $id_sup;
                $d['lado'] = $_POST[1]['ultimo_lado'];
                $d['fk_id_pessoa'] = tool::id_pessoa();
                if (!empty($_POST['dadosaluno'])) {
                    $d['descr'] = $_POST['dadosaluno'] . '<br />' . $_POST['descr'];
                } else {
                    $d['descr'] = $_POST['descr'];
                }
                $d['data'] = date("Y-m-d H:i:s");
                $this->db->insert('dttie_suport_diag', $d);
            }
            if (@$_POST[1]['status_sup'] == 'Finalizado') {
                echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/dttie/escolapesq";</script>';
            }
        }
    }

    public function logset($id, $texto) {
        $log = [
            'fk_id_sup' => $id,
            'data' => date("y-m-d H:i:s"),
            'log' => $texto,
            'fk_id_pessoa' => $_SESSION['userdata']['id_pessoa']
        ];
        $this->db->ireplace('dttie_suporte_log', $log, 1);
    }

    public function logSuporte($id = NULL) {
        if (!empty($id)) {
            $dados = sql::get('dttie_suporte_log', '*', ['fk_id_sup' => $id]);
            foreach ($dados as $k => $v) {
                $dados[$k]['d'] = data::converteBr(substr($v['data'], 0, 10));
                $dados[$k]['h'] = substr($v['data'], 10);
            }
            $form['array'] = $dados;
            $form['fields'] = [
                'Data' => 'd',
                'Hora' => 'h',
                'Ação' => 'log'
            ];
            $form['titulo'] = "Histórico";
            include ABSPATH . '/views/relat/simples1.php';
        }
    }

    public function listaescoladttie() {

        $sql = "SELECT i.id_inst, n_inst FROM instancia i"
                . " WHERE i.ativo = 1 AND i.fk_id_tp = 1"
                . " ORDER BY i.n_inst";
        $query = $this->db->query($sql);
        $escola = $query->fetchAll();

        foreach ($escola as $v) {
            $esc[$v['id_inst']] = $v['n_inst'];
        }

        return $esc;
    }

    public function listaturmadttie($idinst) {

        $sql = "SELECT t.id_turma, t.n_turma FROM ge_turmas t"
                . " JOIN ge_periodo_letivo pl ON t.fk_id_pl = pl.id_pl"
                . " WHERE pl.at_pl = 1 AND t.fk_id_inst = '" . $idinst . "'"
                . " ORDER BY t.n_turma";

        $query = $this->db->query($sql);
        $turma = $query->fetchAll();

        foreach ($turma as $v) {
            $tur[$v['id_turma']] = $v['n_turma'];
        }

        return $tur;
    }

    public function listaalunodttie($idturma) {
        $sql = "SELECT p.id_pessoa, p.n_pessoa FROM pessoa p"
                . " JOIN ge_turma_aluno ta ON p.id_pessoa = ta.fk_id_pessoa"
                . " WHERE ta.fk_id_turma = '" . $idturma . " AND ta.situacao = " . 'Frequente' . "'"
                . " ORDER BY p.n_pessoa";

        $query = $this->db->query($sql);
        $al = $query->fetchAll();

        foreach ($al as $v) {
            $aluno[$v['id_pessoa']] = $v['n_pessoa'];
        }

        return $aluno;
    }

    public function cancelachamadoaluno($idsup) {
        $sql = "UPDATE dttie_suporte_trab"
                . " SET status_sup = 'Solicitação Cancelada' WHERE id_sup = '" . $idsup . "'";

        $query = $this->db->query($sql);
    }

    public function pegaemailgoogle($idrse) {
        $sql = "SELECT p.id_pessoa, p.n_pessoa, p.emailgoogle FROM pessoa p"
                . " JOIN ge_turma_aluno ta ON p.id_pessoa = ta.fk_id_pessoa"
                . " WHERE p.id_pessoa = '" . $idrse . "'";

        $query = $this->db->query($sql);
        $email = $query->fetch();

        return $email['emailgoogle'];
    }

    public function pegatiposubstituicao() {

        $sql = "SELECT * FROM dttie_tipo_cadamp ORDER BY ordem";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        foreach ($array as $v) {
            $t[$v['id_tipo_cadamp']] = $v['n_tipo_cadamp'];
        }
        return $t;
    }

    public function pegalocalsup() {

        $sql = "SELECT n_inst  FROM instancia"
                . " WHERE ativo = 1 ORDER BY fk_id_tp, n_inst";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        if (!empty($array)) {
            foreach ($array as $v) {
                $e[$v['n_inst']] = $v['n_inst'];
            }
        }

        return $e;
    }

    public function pegarespon($ativo = NULL) {

        if (!empty($ativo)) {
            $sql = "SELECT id_resp, n_resp FROM dttie_resp_tec"
                    . " WHERE ativo = $ativo ORDER by n_resp";

            $wsql = "SELECT id_terceirizada, n_terceirizada FROM dttie_terceirizada"
                    . " WHERE ativo_terc = $ativo ORDER by n_terceirizada";
        } else {
            $sql = "SELECT id_resp, n_resp FROM dttie_resp_tec"
                    . " ORDER by n_resp";

            $wsql = "SELECT id_terceirizada,n_terceirizada FROM dttie_terceirizada"
                    . " ORDER by n_terceirizada";
        }

        $query = $this->db->query($sql);
        $dt = $query->fetchAll();

        if (!empty($dt)) {
            foreach ($dt as $v) {
                $r[$v['id_resp']] = $v['n_resp'];
            }
        }

        $query = $this->db->query($wsql);
        $array = $query->fetchAll();

        if (!empty($array)) {
            foreach ($array as $v) {
                $r[$v['id_terceirizada']] = $v['n_terceirizada'];
            }
        }

        return $r;
    }

    public function pegastatus() {

        $sql = "SELECT DISTINCT status_sup FROM dttie_suporte_trab ORDER by status_sup";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        if (!empty($array)) {
            foreach ($array as $v) {
                if ($v['status_sup'] != 'Recusado') {
                    $r[$v['status_sup']] = $v['status_sup'];
                }
            }
        }

        return $r;
    }

    public function pegalista() {

        $sql = "SELECT DISTINCT ls.id_list, ls.n_list FROM dttie_suporte_trab ds"
                . " JOIN dttie_list_suporte ls ON ls.id_list = ds.tipo_sup"
                . " ORDER BY ls.n_list";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        if (!empty($array)) {
            foreach ($array as $v) {
                $s[$v['id_list']] = $v['n_list'];
            }
        }

        return $s;
    }

    public function pegadadosconsulta($local, $resp, $tipo2, $status, $dti, $dtf) {

        $r = "SELECT * FROM dttie_terceirizada";
        $query = $this->db->query($r);
        $array = $query->fetchAll();

        foreach ($array as $v) {
            $ter[$v['n_terceirizada']] = $v['id_terceirizada'];
        }

        if (!empty($local)) {
            if ($local == '') {
                
            } else {
                $local_sup = " and local_sup = '" . $local . "'";
            }
        }
        if (!empty($tipo2)) {
            $tipo_sup = " and tipo_sup = '" . $tipo2 . "'";
        } else {
            $tipo_sup = " and tipo_sup != '76'";
        }
        if (!empty($resp)) {
            $id_resp = " and id_resp like '%" . $resp . "%'";
            $id_respt = " and id_terceirizada like '%" . $resp . "%'";
        }
        if (!empty($status)) {
            $status_sup = " and  status_sup like  '%" . $status . "%'";
        }
        if (!empty($dti) && (!empty($dtf))) {
            if ($dti < $dtf) {
                $data_c = " AND dt_sup BETWEEN '" . data::converteUS($dti) . "' AND '" . data::converteUS($dtf) . "'";
            }
        }
        if (in_array($resp, $ter)) {
            $sql = "SELECT t.id_sup, t.local_sup, t.tipo_sup, te.n_terceirizada AS n_resp,"
                    . " t.status_sup, t.dt_sup FROM dttie_suporte_trab t"
                    . " LEFT JOIN dttie_terceirizada te on te.id_terceirizada = t.resp_sup"
                    . " WHERE 1 "
                    . "  " . @$local_sup . ' '
                    . "  " . @$tipo_sup . ' '
                    . "  " . @$id_respt . ' '
                    . "  " . @$status_sup . ' '
                    . "  " . @$data_c . ' '
                    . " ORDER BY t.id_sup";
        } else {
            $sql = "SELECT t.id_sup, t.local_sup, t.tipo_sup, r.n_resp,"
                    . " t.status_sup, t.dt_sup FROM dttie_suporte_trab t"
                    . " LEFT JOIN dttie_resp_tec r on r.id_resp = t.resp_sup"
                    . " WHERE 1 "
                    . "  " . @$local_sup . ' '
                    . "  " . @$tipo_sup . ' '
                    . "  " . @$id_resp . ' '
                    . "  " . @$status_sup . ' '
                    . "  " . @$data_c . ' '
                    . " ORDER BY t.id_sup";
        }

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        return $array;
    }

    public function contachamado() {

        $conta['NUll'] = 0;
        $conta['Não Visualizado'] = 0;
        $conta['Atribuído'] = 0;
        $conta['Cancelado'] = 0;
        $conta['Finalizado'] = 0;
        $conta['Recusado'] = 0;

        $sql = "SELECT status_sup, COUNT(*) AS Total FROM dttie_suporte_trab"
                . " GROUP BY status_sup";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        if (!empty($array)) {
            foreach ($array as $v) {
                if (empty($v['status_sup'])) {
                    $conta['NUll'] = $v['Total'];
                } else {
                    $conta[$v['status_sup']] = $v['Total'];
                }
            }
        }

        return $conta;
    }

    public function pegadialogo($idsup) {
        $sql = "SELECT descr FROM dttie_suport_diag"
                . " WHERE fk_id_sup = $idsup AND lado = '" . 'Usuário' . "'" . " LIMIT 1";

        $query = $this->db->query($sql);
        $d = $query->fetch();

        if (!empty($d)) {
            return $d['descr'];
        } else {
            return '-';
        }
    }

    public function pegarelatoriogerencial($local, $resp, $tipo2, $status, $dti, $dtf) {

        $r = "SELECT * FROM dttie_terceirizada";
        $query = $this->db->query($r);
        $array = $query->fetchAll();

        foreach ($array as $v) {
            $ter[$v['n_terceirizada']] = $v['id_terceirizada'];
        }

        if (!empty($local)) {
            if ($local == '') {
                
            } else {
                $local_sup = " and local_sup = '" . $local . "'";
            }
        }
        if (!empty($tipo2)) {
            $tipo_sup = " and tipo_sup = '" . $tipo2 . "'";
        } else {
            $tipo_sup = " and tipo_sup != '76'";
        }
        if (!empty($resp)) {
            $id_resp = " and id_resp like '%" . $resp . "%'";
            $id_respt = " and id_terceirizada like '%" . $resp . "%'";
        }
        if (!empty($status)) {
            $status_sup = " and  status_sup like  '%" . $status . "%'";
        }
        if (!empty($dti) && (!empty($dtf))) {
            if ($dti < $dtf) {
                $data_c = " AND dt_sup BETWEEN '" . data::converteUS($dti) . "' AND '" . data::converteUS($dtf) . "'";
            }
        }
        if (in_array($resp, $ter)) {
            $sql = "SELECT t.id_sup, t.local_sup, t.tipo_sup, te.n_terceirizada AS n_resp,"
                    . " t.status_sup, t.dt_sup FROM dttie_suporte_trab t"
                    . " LEFT JOIN dttie_terceirizada te on te.id_terceirizada = t.resp_sup"
                    . " WHERE 1 "
                    . "  " . @$local_sup . ' '
                    . "  " . @$tipo_sup . ' '
                    . "  " . @$id_respt . ' '
                    . "  " . @$status_sup . ' '
                    . "  " . @$data_c . ' '
                    . " ORDER BY t.id_sup";
        } else {
            $sql = "SELECT t.id_sup, t.local_sup, t.tipo_sup, r.n_resp,"
                    . " t.status_sup, t.dt_sup FROM dttie_suporte_trab t"
                    . " LEFT JOIN dttie_resp_tec r on r.id_resp = t.resp_sup"
                    . " WHERE 1 "
                    . "  " . @$local_sup . ' '
                    . "  " . @$tipo_sup . ' '
                    . "  " . @$id_resp . ' '
                    . "  " . @$status_sup . ' '
                    . "  " . @$data_c . ' '
                    . " ORDER BY t.id_sup";
        }

        return $sql;
    }

    public function pegaterceirizada() {
        
        $sql = "SELECT * FROM dttie_terceirizada";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();
        
        foreach ($array as $v) {
            $ter[$v['id_terceirizada']] = $v['n_terceirizada'];
        }

        return $ter;
    }

}
