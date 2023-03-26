<?php

class vagasModel extends MainModel {

    public $db;

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

        if (empty($_POST[1]['id_vaga']) && !empty($_POST['cadastro'])) {
            $this->logIns($_POST['last_id'], 'Cadastrou a Inscrição');
        } elseif (!empty($_POST[1]['id_vaga']) && !empty($_POST['cadastro'])) {
            $this->logIns($_POST[1]['id_vaga'], 'Alterou a Inscrição');
        } elseif (!empty($_POST['concluiu'])) {
            $this->logIns($_POST[1]['id_vaga'], 'Concluiu a Inscrição');
        } elseif (!empty($_POST['cancela'])) {
            $this->logIns($_POST[1]['id_vaga'], 'Cancelou a Inscrição(' . $_POST[1]['obs_cancela'] . ')');
        } elseif (!empty($_POST['deferir'])) {
            $this->logIns($_POST[1]['id_vaga'], 'Mudou o status para ' . $_POST[1]['status'] . ' ' . (!empty($_POST[1]['obs']) ? '(' . $_POST[1]['obs'] . ')' : ''));
        }

        if (!empty($_POST['finalizar_matricula']) && !empty($_POST['last_id'])) {
            $sql = " update vagas set fk_id_pessoa = " . $_POST['last_id']
                    . ", status = 'Matriculado' "
                    . " where id_vaga = " . $_POST['id_vaga'];
            $query = $this->db->query($sql);
            if ($query) {
                $this->logIns($_POST['id_vaga'], 'Matriculou a Criança');
            }
        }
        if (!empty($_POST['mudaStatusSet'])) {
            $this->logIns($_POST[1]['id_vaga'], 'Alterou Status:' . @$_POST[1]['status']);
        }
        if (DB::sqlKeyVerif('matricular')) {
            $sql = "Select * from vagas "
                    . " where id_vaga = " . $_POST['id_vaga'];
            $query = $this->db->query($sql);
            $array = $query->fetch();

            //Retorna dados de matricula
            if (empty($_POST['id_pessoa'])) {
                $sql = "SELECT * FROM `pessoa` "
                        . " WHERE `n_pessoa` LIKE '%" . $array['n_aluno'] . "%' "
                        . " AND `dt_nasc` = '" . $array['dt_aluno'] . "' "
                        . " AND `certidao` LIKE '" . $array['cn_matricula'] . "' ";
                $query = $this->db->query($sql);
                $verifica = $query->fetch();
                if (!empty($verifica['id_pessoa'])) {
                    @$array['fk_id_pessoa'] = $verifica['id_pessoa'];
                }
            }
            if (!empty($array['fk_id_pessoa'])) {
                $dados['id_pessoa'] = $array['fk_id_pessoa'];
            }
            if (!empty($array['n_aluno'])) {
                $dados['n_pessoa'] = $array['n_aluno'];
            }
            if (!empty($array['sx_aluno'])) {
                $dados['sexo'] = substr($array['sx_aluno'], 0, 1);
            }
            if (!empty($array['mae'])) {
                $dados['mae'] = $array['mae'];
            }
            if (!empty($array['pai'])) {
                $dados['pai'] = $array['pai'];
            }
            if (!empty($array['responsavel'])) {
                $dados['responsavel'] = $array['responsavel'];
            }
            if (!empty($array['tel1'])) {
                $dados['tel1'] = $array['tel1'];
            }
            if (!empty($array['tel2'])) {
                $dados['tel2'] = $array['tel2'];
            }
            if (!empty($array['tel3'])) {
                $dados['tel3'] = $array['tel3'];
            }
            if (!empty($array['dt_aluno'])) {
                $dados['dt_nasc'] = $array['dt_aluno'];
            }
            if (!empty($array['cn_matricula'])) {
                $dados['certidao'] = $array['cn_matricula'];
            }
            if (!empty($array['rg_aluno'])) {
                $dados['rg'] = $array['rg_aluno'];
            }
            if (!empty($array['oe_rg_aluno'])) {
                $dados['rg_oe'] = $array['oe_rg_aluno'];
            }
            if (!empty($array['uf_rg_aluno'])) {
                $dados['rg_uf'] = $array['uf_rg_aluno'];
            }
            if (!empty($array['deficiencia'])) {
                $dados['deficiencia'] = $array['deficiencia'];
            }
            if (!empty($array['nacionalidade'])) {
                $dados['nacionalidade'] = $array['nacionalidade'];
            }
            if (!empty($array['uf_nasc'])) {
                $dados['uf_nasc'] = $array['uf_nasc'];
            }
            if (!empty($array['cidade_nasc'])) {
                $dados['cidade_nasc'] = $array['cidade_nasc'];
            }

            //Datas Vaga para matricula
            if (!empty($array['dt_rg_aluno'])) {
                $dados['dt_rg_aluno'] = $array['dt_rg_aluno'];
            }

            if (!empty($array['dt_resp'])) {
                $dados['dt_resp'] = $array['dt_resp'];
            }

            if (!empty($array['dt_vagas'])) {
                $dados['dt_vagas'] = $array['dt_vagas'];
            }

            if (!empty($array['logradouro'])) {
                $dados['logradouro'] = $array['logradouro'];
            }

            $teste = $this->db->ireplace('pessoa', $dados, 1);
            $_POST['matricular'] = 1;
        }

        if (!empty($_POST['salvacan'])) {
            $this->cancelamatricula($_POST['id_vaga'], $_POST['obs_cancela']);
        }
    }

    public function status() {
        return [
            'Matriculado' => 'Matriculado',
            'Deferido' => 'Deferido',
            'Cancelado' => 'Cancelado',
            'Indeferido' => 'Indeferido',
            'Pendente' => 'Pendente',
            'Aguardando Deferimento' => 'Aguardando Deferimento',
            'Contactando' => 'Contactando',
            'Aguardando Matrícula' => 'Aguardando Matrícula',
            'Edição' => 'Edição',
            'Retirado da Lista' => 'Retirado da Lista'
        ];
    }

    public function statusEdit() {
        return [
            'Cancelado' => 'Cancelado',
            'Indeferido' => 'Indeferido',
            'Pendente' => 'Pendente',
            'Aguardando Deferimento' => 'Aguardando Deferimento'
        ];
    }

    public function statusCancelar() {
        return [
            'Edição' => 'Edição',
            'Pendente' => 'Pendente',
            'Aguardando Deferimento' => 'Aguardando Deferimento'
        ];
    }

    public function statusProtocolo() {
        return [
            'Deferido' => 'Deferido',
            'Pendente' => 'Pendente',
            'Aguardando Deferimento' => 'Aguardando Deferimento'
        ];
    }

    public function seriacao() {
        $ser = [
            'Berçário' => 'Berçário',
            '1ª Fase - Maternal' => '1ª Fase - Maternal',
            '2ª Fase - Maternal' => '2ª Fase - Maternal',
            '3ª Fase - Maternal' => '3ª Fase - Maternal'
        ];

        return $ser;
    }

    public function pesq($fk_id_inst = NULL, $status = NULL, $seriacao = NULL, $n_aluno = NULL, $trab = NULL) {

        if (!empty($fk_id_inst)) {
            $fk_id_inst_ = " AND `fk_id_inst` = $fk_id_inst ";
        }
        if (!empty($trab)) {
            $trab = " AND `trabalha` = 1 ";
        } else {
            $trab = NULL;
        }
        if (!empty($status)) {
            if ($status == 'Deferim') {
                $status_ = " AND `status` in ('Aguardando Deferimento','Pendente') ";
            } else {
                $status_ = " AND `status` LIKE '$status' ";
            }
        }
        if (!empty($n_aluno)) {
            if (is_numeric($n_aluno)) {
                $n_aluno_ = " AND `id_vaga` = '$n_aluno' ";
            } else {
                $n_aluno_ = " AND `n_aluno` LIKE '%$n_aluno%' ";
            }
        }
        if (!empty($seriacao)) {
            $seriacao_ = " AND `seriacao` LIKE '$seriacao' ";
        }
        $fields = "id_vaga, seriacao, classifica, n_aluno, dt_aluno, status, n_inst, responsavel, cpf_resp, trabalha, atividade, ultima_tentativa ";
        $sql = "SELECT $fields FROM `vagas` "
                . "join instancia on instancia.id_inst = vagas.fk_id_inst"
                . "  WHERE 1 "
                . @$fk_id_inst_
                . @$n_aluno_
                . @$seriacao_
                . @$status_
                . @$trab
                . "ORDER BY fk_id_inst, seriacao, classifica ASC ";
        $query = pdoSis::getInstance()->query($sql);
        $alunos = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($alunos)) {
            foreach ($alunos as $k => $a) {
                if (empty($a['seriacao'])) {
                    $de = explode('-', $a['dt_aluno']);
                    $seriacao = $this->setSerie($de[0] . $de[1] . $de[1]);
                    $alunos[$k]['seriacao'] = $seriacao;
                }
                $array = [
                    'abad' => 1,
                    'aba' => 1,
                    'id_vaga' => $a['id_vaga'],
                    'id_inst' => $fk_id_inst,
                    'n_aluno' => $n_aluno,
                    'status' => $status,
                    'seriacao' => $seriacao,
                    'ultima_tentativa' => $a['ultima_tentativa']
                ];
                $alunos[$k]['ac'] = formulario::submit('Acessar', NULL, $array, HOME_URI . '/vagas/cada');
                if ($a['status'] == 'Deferido' or $a['status'] == 'Contactando') {
                    $alunos[$k]['at'] = formulario::submit('Atendimento', NULL, $array, HOME_URI . '/vagas/atendimento');
                } else {
                    $alunos[$k]['at'] = '<button type="button">Atendimento</button>';
                }
            }

            $form['array'] = $alunos;
            $form['fields'] = [
                'Inscrição' => 'id_vaga',
                'Seriação' => 'seriacao',
                'Classif.' => 'classifica',
                'Nome' => 'n_aluno',
                'Status' => 'status',
                'Trabalha' => 'atividade',
                'Observação' => 'ultima_tentativa',
                '||1' => 'ac',
                '||2' => 'at'
            ];

            tool::relatSimples($form);

            $sql = "SELECT "
                    . " id_vaga as Inscr, "
                    . " classifica, "
                    . " n_aluno as Criança, "
                    . " seriacao as Seriação, "
                    . " logradouro, "
                    . " num, "
                    . " compl, "
                    . " bairro,"
                    . " cidade, "
                    . " cep, "
                    . " tel1,"
                    . " tel2, "
                    . " tel3, "
                    . " n_inst as Escola, "
                    . " responsavel, "
                    . " cpf_resp, "
                    . " trabalha, "
                    . " atividade "
                    . " FROM `vagas` "
                    . " join instancia on instancia.id_inst = vagas.fk_id_inst"
                    . "  WHERE 1 "
                    . @$fk_id_inst_
                    . @$n_aluno_
                    . @$seriacao_
                    . @$status_
                    . @$trab
                    . "ORDER BY fk_id_inst, seriacao, classifica ASC ";

            return $sql;
        }
    }

    public function dados($id_vaga) {
        $sql = "SELECT * FROM `vagas` "
                . "join instancia on instancia.id_inst = vagas.fk_id_inst"
                . "  WHERE  id_vaga = $id_vaga";
        $query = pdoSis::getInstance()->query($sql);
        $aluno = $query->fetch(PDO::FETCH_ASSOC);

        return $aluno;
    }

    public function setSerie($data) {
        $data = str_replace('-', '', $data);
        $d_b = ((date("Y") - 1) * 10000) + 331;
        $d_1m = ((date("Y") - 2) * 10000) + 331;
        $d_2m = ((date("Y") - 3) * 10000) + 331;
        $d_3m = ((date("Y") - 4) * 10000) + 331;
        $d_1p = ((date("Y") - 5) * 10000) + 331;
        $d_2p = ((date("Y") - 6) * 10000) + 331;


        if ($data > date("Ymd")) {
            return 'Ainda Não Nasceu';
        } elseif ($data > $d_b) {
            return 'Berçário';
        } elseif ($data > $d_1m) {
            return '1ª Fase - Maternal';
        } elseif ($data > $d_2m) {
            return '2ª Fase - Maternal';
        } elseif ($data > $d_3m) {
            return '3ª Fase - Maternal';
        } elseif ($data > $d_1p) {
            return '1ª Fase - Pré';
        } elseif ($data > $d_2p) {
            return '2ª Fase - Pré';
        } else {
            return 'Acima da Idade';
        }
    }

    public function setCiclo($data) {
        $data = str_replace('-', '', $data);
        $d_b = ((date("Y") - 1) * 10000) + 331;
        $d_1m = ((date("Y") - 2) * 10000) + 331;
        $d_2m = ((date("Y") - 3) * 10000) + 331;
        $d_3m = ((date("Y") - 4) * 10000) + 331;
        $d_1p = ((date("Y") - 5) * 10000) + 331;
        $d_2p = ((date("Y") - 6) * 10000) + 331;


        if ($data > date("Ymd")) {
            return NULL;
        } elseif ($data > $d_b) {
            return 21;
        } elseif ($data > $d_1m) {
            return 22;
        } elseif ($data > $d_2m) {
            return 23;
        } elseif ($data > $d_3m) {
            return 24;
        } elseif ($data > $d_1p) {
            return 19;
        } elseif ($data > $d_2p) {
            return 20;
        } else {
            return NULL;
        }
    }

    public function maeFilho($mae, $n_aluno, $cn, $id_vaga) {
        $mae = addslashes($mae);
        $n_aluno = addslashes($n_aluno);

        $sql = "SELECT i.n_inst, status, cn_matricula  FROM vagas v "
                . " join instancia i on i.id_inst = v.fk_id_inst "
                . " WHERE "
                . " ((v.mae like '%$mae%' "
                . " AND v.n_aluno like '%$n_aluno%') "
                . " or cn_matricula = '$cn') "
                . " and status not like 'Cancelado' "
                . " and id_vaga != '$id_vaga' ";

        $query = $this->db->query($sql);
        $array = $query->fetch();
        if ($array) {
            return $array;
        } else {
            return NULL;
        }
    }

    public function log($id_vaga) {
        $sql = "SELECT *  FROM `vagas_log` WHERE `fk_id_vaga` = '$id_vaga'";
        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        foreach ($array as $k => $v) {
            @$array[$k]['dt'] = data::converteBr(substr($v['data'], 0, 10)) . '' . substr($v['data'], 10);
        }
        $form['array'] = $array;
        $form['fields'] = [
            'Data/Hora' => 'dt',
            'Escola' => 'escola',
            'Colaborador' => 'n_pessoa',
            'Ação' => 'msg'
        ];

        return $form;
    }

    public function logIns($id, $msg) {
        $sql = "INSERT INTO `vagas_log` (`id_log`, `n_pessoa`, `data`, `msg`, `escola`, `fk_id_inst`, `fk_id_pessoa`, `fk_id_vaga`) VALUES ("
                . "NULL, "
                . "'" . $_SESSION['userdata']['n_pessoa'] . "', "
                . "'" . date("Y-m-d H:i:s") . "', "
                . "'$msg', "
                . "'" . $_SESSION['userdata']['n_inst'] . "', "
                . "'" . tool::id_inst() . "', "
                . "'" . $_SESSION['userdata']['id_pessoa'] . "', "
                . "'" . $id . "');";
        $query = $this->db->query($sql);
    }

    public function aguardaclassificacao($idinst) {
        $sql = "SELECT v.seriacao, v.trabalha, COUNT(v.seriacao) as Total FROM vagas v"
                . " GROUP BY v.seriacao, v.status, v.classifica, v.fk_id_inst, v.trabalha"
                . " HAVING v.status = '" . 'Deferido' . "' AND v.fk_id_inst = '" . $idinst . "'"
                . " AND v.classifica IS NULL";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        if (!empty($array)) {
            foreach ($array as $v) {
                $def[$v['seriacao']][$v['trabalha']] = $v['Total'];
            }
            return $def;
        }
    }

    public function pegadadoscrianca($idvaga) {

        $sql = $sql = "SELECT * FROM vagas v WHERE v.id_vaga = '" . $idvaga . "'";
        $query = $this->db->query($sql);
        $array = $query->fetch();

        return $array;
    }

    public function pegahistoricocrianca($idvaga) {

        $sql = $sql = "SELECT * FROM vagas_historico vh WHERE vh.fk_id_vaga = '" . $idvaga . "'";
        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        return $array;
    }

    public function gravahistorico($idvaga, $tentativa, $descricao) {

        $te = [
            '1ª Tentativa - Responsável Não Localizado' => '1ª Tentativa',
            '2ª Tentativa - Responsável Não Localizado' => '2ª Tentativa',
            '3ª Tentativa - Responsável Não Localizado' => '3ª Tentativa',
            'Confirmado - Aguardando Matrícula' => 'Confirmado - Aguardando Matrícula',
            'Não Tem Interesse na Vaga' => 'Não Tem Interesse na Vaga',
            'Endereço Atual em Outro Município' => 'Endereço Atual em Outro Município'
        ];

        switch ($tentativa) {

            case "Confirmado - Aguardando Matrícula":
                $st = 'Aguardando Matrícula';
                $msg = "Contactado com sucesso";
                break;
            case "1ª Tentativa - Responsável Não Localizado":
            case "2ª Tentativa - Responsável Não Localizado":
            case "3ª Tentativa - Responsável Não Localizado":
                $st = 'Contactando';
                $msg = $tentativa;
                break;
            case "Não Tem Interesse na Vaga":
            case "Endereço Atual em Outro Município":
            case "Retirar da Lista":
                $st = 'Retirado da Lista';
                $msg = $tentativa;
                break;
        }

        //Atualiza tabela vaga
        $sql = "UPDATE vagas SET status = '" . $st . "', ultima_tentativa = '" . $te[$tentativa] . "'"
                . " WHERE id_vaga = '" . $idvaga . "'";
        $query = $this->db->query($sql);

        //Salva histórico
        $sql = "INSERT INTO vagas_historico (fk_id_vaga, tentativas, descricao)"
                . " VALUES($idvaga, '$tentativa', '$descricao')";

        $query = $this->db->query($sql);
        //verifica quantidade
        $sql = "SELECT id_vagas_hist, tentativas, COUNT(tentativas) AS t FROM vagas_historico"
                . " WHERE fk_id_vaga = '" . $idvaga . "'"
                . " AND tentativas = '" . $tentativa . "'";

        $query = $this->db->query($sql);
        $qtde = $query->fetch();

        $wsql = "UPDATE vagas_historico SET quantidade = '" . $qtde['t'] . "'"
                . " WHERE id_vagas_hist = '" . $qtde['id_vagas_hist'] . "'";

        $query = $this->db->query($wsql);
        tool::alert("Operação Efetuada com Sucesso");
        $this->logIns($idvaga, $msg);

        echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/vagas/pesq";</script>';
    }

    public function verificasituacao($idvaga) {

        $sql = "SELECT id_vagas_hist FROM vagas_historico"
                . " WHERE fk_id_vaga = '" . $idvaga . "'"
                . " AND tentativas = '" . '3ª Tentativa - Responsável Não Localizado' . "'";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        return $array;
    }

    public function cancelamatricula($idvaga, $can) {

        $sql = "UPDATE vagas SET status = 'Cancelado', obs_cancela = '" . $can . "'"
                . " WHERE id_vaga = '" . $idvaga . "'";

        $query = $this->db->query($sql);

        $this->logIns($idvaga, 'Mudou status da Inscrição de Matriculado para Cancelado');
    }

}
