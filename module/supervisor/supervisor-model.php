<?php

class supervisorModel extends MainModel {

    public $db;
    public $path_upload_documentos = ABSPATH . '/pub/fotoSupervisor/';

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
        if ($opt = formErp::jqOption()) {
            $metodo = $opt[0];
            if (in_array($metodo, get_class_methods($this))) {
                $this->$metodo($opt[1]);
            }
        }

        if ($this->db->tokenCheck('supervisorFotoSalvar', true)) {
            $this->supervisorFotoSalvar();
        }
    }

// Crie seus próprios métodos daqui em diante
    public function getAreas($id_area = null, $n_area = null, $at_area = null) {
        $where = "WHERE 1=1 ";
        if (!empty($id_area)) {
            $where .= " and id_area = $id_area ";
        }
        if (!empty($n_area)) {
            $where .= " and n_area LIKE '$n_area%' ";
        }
        if (!is_null($at_area) && is_numeric($at_area)) {
            $where .= " and at_area = '$at_area%' ";
        }
        $sql = "SELECT * FROM `area` "
                . $where
                . " ORDER BY `id_area`";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getItensOcorrencia($id_item_ocorrencia = null, $n_item_ocorrencia = null, $at_item_ocorrencia = null, $fk_id_area = null) {
        $where = "WHERE 1=1 ";

        if (!empty($fk_id_area)) {
            $where .= " and vio.fk_id_area = $fk_id_area ";
        }
        if (!empty($id_item_ocorrencia)) {
            $where .= " and vio.id_item_ocorrencia = $id_item_ocorrencia ";
        }
        if (!empty($n_item_ocorrencia)) {
            $where .= " and vio.n_item_ocorrencia like '$n_item_ocorrencia%' ";
        }
        if (!is_null($at_item_ocorrencia) && is_numeric($at_item_ocorrencia)) {
            $where .= " and vio.at_item_ocorrencia = $at_item_ocorrencia ";
        }
        $sql = "SELECT vio.*, a.n_area FROM `vis_item_ocorrencia` AS vio "
                . " INNER JOIN area a ON a.id_area=vio.fk_id_area "
                . $where
                . " ORDER BY vio.`id_item_ocorrencia`";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getSetorAtribuicaoEscola($id_setor = null, $n_setor = null, $at_setor = null, $fk_id_pessoa = null) {
        $where = "WHERE 1=1 ";
        if (!empty($fk_id_pessoa)) {
            $where .= " and s.fk_id_pessoa = $fk_id_pessoa ";
        }
        if (!empty($id_setor)) {
            $where .= " and s.id_setor = $id_setor ";
        }
        if (!empty($n_setor)) {
            $where .= " and s.n_setor like '$n_setor%' ";
        }
        if (!is_null($at_setor) && is_numeric($at_setor)) {
            $where .= " and s.at_setor = $at_setor ";
        }
        $sql = "SELECT s.*, p.n_pessoa FROM `vis_setor` AS s "
                . " INNER JOIN pessoa p ON p.id_pessoa=s.fk_id_pessoa "
                . $where
                . " ORDER BY s.`id_setor`";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getEscolaParticular($id_inst = null, $n_inst = null, $at_inst = null, $fk_id_tp = null) {
        $where = "WHERE 1=1 ";
        if (!empty($fk_id_tp)) {
            $where .= " and ip.fk_id_tp = $fk_id_tp ";
        }
        if (!empty($id_inst)) {
            $where .= " and ip.id_inst = $id_inst ";
        }
        if (!empty($n_inst)) {
            $where .= " and ip.n_inst like '$n_inst%' ";
        }
        if (!is_null($at_inst) && is_numeric($at_inst)) {
            $where .= " and ip.at_inst = $at_inst ";
        }
        $sql = "SELECT ip.*, ti.n_tp FROM `instancia_particular` AS ip "
                . " INNER JOIN tipo_inst ti ON ti.id_tp=ip.fk_id_tp "
                . $where
                . " ORDER BY ip.`id_inst`";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getCoordenadores() {
        $where = "WHERE 1=1 AND fk_id_nivel = 22";

        $sql = "SELECT id_pessoa, n_pessoa FROM acesso_gr ag " .
                " JOIN acesso_pessoa ap on ap.fk_id_gr = ag.fk_id_gr " .
                " JOIN pessoa p on ap.fk_id_pessoa = p.id_pessoa " .
                $where .
                " GROUP BY p.id_pessoa";
        " ORDER BY p.n_pessoa";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getTipoInstancia() {
        $where = "WHERE 1=1";

        $sql = "SELECT * FROM tipo_inst ti " .
                $where .
                " ORDER BY ti.`id_tp`";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getItensPorVisitaHistorico($id_visita_item_historico = null, $fk_id_pessoa = null, $fk_id_visita_item = null) {
        $where = "WHERE 1=1";
        if (!empty($id_visita_item_historico)) {
            $where .= " and vvih.id_visita_item_historico=$id_visita_item_historico ";
        }
        if (!empty($fk_id_visita_item)) {
            $where .= " and vvih.fk_id_visita_item=$fk_id_visita_item ";
        }
        if (!empty($fk_id_pessoa)) {
            $where .= " and vvih.fk_id_pessoa=$fk_id_pessoa ";
        }

        $sql = "SELECT vvih.*, p.n_pessoa " .
                "FROM vis_visita_item_historico as vvih " .
                "INNER JOIN vis_visita_item as vvi ON vvi.id_visita_item=vvih.fk_id_visita_item " .
                "INNER JOIN pessoa p ON p.id_pessoa=vvih.fk_id_pessoa " .
                $where .
                " ORDER BY vvih.id_visita_item_historico";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getItensStatusPorVisita($fk_id_visita_item = null) {
        $where = "WHERE 1=1";
        if (!empty($fk_id_visita_item)) {
            $where .= " and vvis.fk_id_visita_item=$fk_id_visita_item ";
        }
        $sql = "SELECT vvis.*, '' as n_visita_item_historico, p.n_pessoa, " .
                "CONCAT('<b>',vvils.n_label_status,':</b> ', vvis.comentario) as n_visita_item_historico " .
                "FROM vis_visita_item_status as vvis " .
                "INNER JOIN vis_visita_item_label_status as vvils ON vvils.id_visita_item_label_status=vvis.status " .
                "INNER JOIN vis_visita_item as vvi ON vvi.id_visita_item=vvis.fk_id_visita_item " .
                "INNER JOIN pessoa p ON p.id_pessoa=vvis.fk_id_pessoa " .
                $where .
                " ORDER BY vvis.id_visita_item_status";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getItensPorVisita($id_visita_item = null, $fk_id_visita = null,
            $fk_id_item_ocorrencia = null) {
        $where = "WHERE 1=1";
        if (!empty($id_visita_item)) {
            $where .= " and vvi.id_visita_item=$id_visita_item ";
        }
        if (!empty($fk_id_visita)) {
            $where .= " and vvi.fk_id_visita=$fk_id_visita ";
        }
        if (!empty($fk_id_item_ocorrencia)) {
            $where .= " and vvi.fk_id_item_ocorrencia=$fk_id_item_ocorrencia ";
        }

        $sql = "SELECT vvi.*, vio.n_item_ocorrencia, a.n_area, vio.fk_id_area " .
                "FROM vis_visita_item as vvi " .
                "INNER JOIN vis_item_ocorrencia vio ON vio.id_item_ocorrencia=vvi.fk_id_item_ocorrencia " .
                "INNER JOIN area a ON a.id_area=vio.fk_id_area " .
                $where .
                " ORDER BY vvi.id_visita_item";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getDocumentosPorVisita($id_visita_documento = null, $fk_id_visita = null) {
        $where = "WHERE 1=1";
        if (!empty($id_visita_documento)) {
            $where .= " and vvd.id_visita_documento=$id_visita_documento ";
        }
        if (!empty($fk_id_visita)) {
            $where .= " and vvd.fk_id_visita=$fk_id_visita ";
        }

        $sql = "SELECT vvd.* " .
                "FROM vis_visita_documento as vvd " .
                $where .
                " ORDER BY vvd.id_visita_documento";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function supervisorFotoSalvar() {

        $ins = @$_POST[1];
        $id_foto = $ins['id_visita_documento'];
        if (!empty($_FILES['arquivo']['name'])) {
            @$exten = end(explode('.', $_FILES['arquivo']['name']));
            $imagens = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'];
            $pdf = ['pdf', 'PDF'];
            $docs = ['doc', 'docx', 'DOC', 'DOCX'];
            $allow_extentions = array_merge($imagens, $pdf, $docs);
            if (!in_array($exten, $allow_extentions)) {
                toolErp::alertModal('Só é permitido anexar com extensões jpg, jpeg e png ');
                return;
            }
            $nome_origin = $_FILES['arquivo']['name'];
            $file = $this->path_upload_documentos;

            $up = new upload($file, $id_foto, 15000000, $allow_extentions);
            $end = $up->up();
            if ($end) {
                $ins['n_visita_documento_disco'] = $end;
                $ins['n_visita_documento'] = toolErp::escapaAspa($nome_origin);
                $this->db->ireplace('vis_visita_documento', $ins);
            } else {
                toolErp::alertModal('Erro ao enviar. Tente novamente');
            }
        } elseif (!empty($id_foto)) {
            $this->db->ireplace('vis_visita_documento', $ins);
        }
    }

    public function getVisitaPorPessoa($id_visita = null, $fk_id_pessoa = null, $fk_id_inst = null, $rede = null) {
        $where = "WHERE 1=1";
        if (!empty($id_visita)) {
            $where .= " and vv.id_visita=$id_visita ";
        }
        if (!empty($fk_id_pessoa)) {
            $where .= " and vv.fk_id_pessoa=$fk_id_pessoa ";
        }
        if (!empty($fk_id_inst)) {
            $where .= " and vv.fk_id_inst=$fk_id_inst ";
        }
        if (is_numeric($rede)) {
            if (empty($rede)) {
                $where .= " and i.id_inst=$fk_id_inst ";
            } else {
                $where .= " and ip.id_inst=$fk_id_inst ";
            }
        }

        $sql = "SELECT vv.*, IF(vv.rede = 0, i.n_inst, ip.n_inst)  as n_inst, p.n_pessoa, c.n_curso " .
                "FROM vis_visita vv " .
                "INNER JOIN pessoa p ON p.id_pessoa=vv.fk_id_pessoa " .
                "LEFT JOIN ge_cursos c ON c.id_curso = vv.fk_id_curso " .
                "LEFT JOIN instancia i ON i.id_inst = vv.fk_id_inst " .
                "LEFT JOIN instancia_particular ip ON ip.id_inst = vv.fk_id_inst " .
                $where .
                " ORDER BY vv.`id_visita` DESC";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getSetorPorInstancia($id_setor_instancia = null, $fk_id_setor = null, $fk_id_pessoa = null) {
        $where = "WHERE 1=1";
        if (!empty($id_setor_instancia)) {
            $where .= " and vsi.id_setor_instancia=$id_setor_instancia ";
        }
        if (!empty($fk_id_setor)) {
            $where .= " and vsi.fk_id_setor=$fk_id_setor ";
        }
        if (!empty($fk_id_pessoa)) {
            $where .= " and vs.fk_id_pessoa=$fk_id_pessoa ";
        }
        $sql = "SELECT vsi.*, vs.n_setor, IF(vsi.rede = 0, i.n_inst, ip.n_inst) as n_inst, c.n_curso, " .
                "c.id_curso, p.n_pessoa, p.id_pessoa " .
                "FROM vis_setor_instancia vsi " .
                "INNER JOIN vis_setor vs ON vs.id_setor = vsi.fk_id_setor " .
                "INNER JOIN pessoa p ON p.id_pessoa=vs.fk_id_pessoa " .
                "INNER JOIN ge_cursos c ON c.id_curso = vsi.fk_id_curso " .
                "LEFT JOIN instancia i ON i.id_inst = vsi.fk_id_inst " .
                "LEFT JOIN instancia_particular ip ON ip.id_inst = vsi.fk_id_inst " .
                $where .
                " ORDER BY vsi.id_setor_instancia";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getCurso() {
        $where = "WHERE 1=1";

        $sql = "SELECT id_curso, n_curso FROM ge_cursos " .
                $where .
                " ORDER BY `id_curso`";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getLabelStatusItem() {
        $where = "WHERE 1=1";

        $sql = "SELECT id_visita_item_label_status, n_label_status FROM vis_visita_item_label_status " .
                $where .
                " ORDER BY `id_visita_item_label_status`";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getTodasInstancia() {
        $where = "WHERE 1=1";

        $sql = "select id_inst, n_inst, fkid_inst_aut, ativo, fk_id_tp, email, terceirizada, null, 0 as rede from instancia union " .
                "select id_inst, n_inst, null, at_inst, fk_id_tp, email, null, dt_update, 1 as rede from instancia_particular " .
                $where;

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getRelatorios($relatorioName, $params = null) {
        $sql = null;
        $where = "WHERE 1=1";
        switch ($relatorioName) {
            case 'relatorioSemAgenda':
                break;
            case 'relatorioJustificativaPorAusencia':
                break;
            case 'relatorioPoucasVisitas':
                if (!empty($params['data_inicial'])) {
                    $where .= " and vv.data_visita={$params['data_inicial']} ";
                }

                if (!empty($params['data_final'])) {
                    $where .= " and vv.data_visita={$params['data_final']} ";
                }

                $sql = "SELECT vv.fk_id_inst, vv.rede, IF(vv.rede = 0, i.n_inst, ip.n_inst) as n_inst, count(*) as total FROM vis_visita vv
                    LEFT JOIN instancia i ON i.id_inst = vv.fk_id_inst
                    LEFT JOIN instancia_particular ip ON ip.id_inst = vv.fk_id_inst {$where}
                    GROUP BY vv.fk_id_inst, vv.rede ORDER BY total";
                break;
            case 'relatorioDepartamentosMaisRequisitados':
                if (!empty($params['data_inicial'])) {
                    $where .= " and vv.data_visita={$params['data_inicial']} ";
                }

                if (!empty($params['data_final'])) {
                    $where .= " and vv.data_visita={$params['data_final']} ";
                }

                $sql = "SELECT vvi.*, vio.n_item_ocorrencia, a.n_area, vio.fk_id_area, count(vio.fk_id_area) as total
                    FROM vis_visita as vv
                    INNER JOIN vis_visita_item as vvi ON vvi.fk_id_visita = vv.id_visita
                    INNER JOIN vis_item_ocorrencia vio ON vio.id_item_ocorrencia=vvi.fk_id_item_ocorrencia
                    INNER JOIN area a ON a.id_area=vio.fk_id_area {$where}
                    GROUP BY vio.fk_id_area
                    ORDER BY vvi.id_visita_item";
                break;
            case 'relatorioDepartamentosBaixaResolutiva':
                if (!empty($params['data_inicial'])) {
                    $where .= " and vv.data_visita={$params['data_inicial']} ";
                }

                if (!empty($params['data_final'])) {
                    $where .= " and vv.data_visita={$params['data_final']} ";
                }

                $where .= " AND vvi.at_visita_item <> 6 ";
                $sql = "SELECT vvi.*, vio.n_item_ocorrencia, a.n_area, vio.fk_id_area, count(vio.fk_id_area) as total
                    FROM vis_visita as vv
                    INNER JOIN vis_visita_item as vvi ON vvi.fk_id_visita = vv.id_visita
                    INNER JOIN vis_visita_item_status as vvis ON vvis.fk_id_visita_item = vvi.id_visita_item
                    INNER JOIN vis_item_ocorrencia vio ON vio.id_item_ocorrencia=vvi.fk_id_item_ocorrencia
                    INNER JOIN area a ON a.id_area=vio.fk_id_area {$where}
                    GROUP BY vio.fk_id_area
                    ORDER BY vvi.id_visita_item";
                break;
            case 'relatorioDepartamentoMaiorSLA':
                $sql = "SELECT fk_id_area, n_area, ROUND(AVG(diff), 1) as media from (
                            SELECT vvi.*, vio.n_item_ocorrencia, a.n_area, vio.fk_id_area,
                            vvis1.dt_update as data_abertura, vvis2.dt_update as data_conclusao, DATEDIFF(vvis2.dt_update, vvis1.dt_update) as diff
                            FROM vis_visita as vv
                            INNER JOIN vis_visita_item as vvi ON vvi.fk_id_visita = vv.id_visita
                            LEFT JOIN vis_visita_item_status as vvis1 ON vvis1.fk_id_visita_item = vvi.id_visita_item  and vvis1.status=1
                            LEFT JOIN vis_visita_item_status as vvis2 ON vvis2.fk_id_visita_item = vvi.id_visita_item  and vvis2.status=6
                            INNER JOIN vis_item_ocorrencia vio ON vio.id_item_ocorrencia=vvi.fk_id_item_ocorrencia
                            INNER JOIN area a ON a.id_area=vio.fk_id_area {$where}
                            ORDER BY vvi.id_visita_item
                        ) as tt
                        GROUP BY fk_id_area";

                break;
            default:
                die('Você não tem permissão para acessar esta área!');
        }

        if (!empty($sql)) {
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return $array ?? [];
    }

    public function addStatus($fk_id_visita_item, $fk_id_pessoa, $comentario, $status = 0) {
        $data = [];
        $data['fk_id_visita_item'] = $fk_id_visita_item;
        $data['fk_id_pessoa'] = $fk_id_pessoa;
        $data['status'] = $status;
        $data['comentario'] = toolErp::escapaAspa($comentario);
        $this->db->ireplace('vis_visita_item_status', $data);

// Atualiza o status do item
        $data = [];
        $data['id_visita_item'] = $fk_id_visita_item;
        $data['at_visita_item'] = $status;
        $this->db->ireplace('vis_visita_item', $data);
    }

    public function diasLetivo($id_pl, $id_curso, $mes) {
        $sql = "SELECT * FROM `sed_letiva_data` "
                . " WHERE fk_id_curso = $id_curso "
                . " AND fk_id_pl = $id_pl "
                . " and substring(`dt_inicio`, 6, 2) <= $mes "
                . " AND substring(`dt_fim`, 6, 2) >= $mes ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $dt = [];
        foreach ($array as $v) {
            $dt = array_merge($dt, $this->listar_datas($v['dt_inicio'], $v['dt_fim']));
        }
        $sql = "SELECT dt_feriado FROM `sed_feriados` WHERE `fk_id_curso` = $id_curso AND `fk_id_pl` = $id_pl ";
        $query = pdoSis::getInstance()->query($sql);
        $feriados = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($feriados) {
            $feriados = array_column($feriados, 'dt_feriado');
        } else {
            $feriados = [];
        }
        foreach ($dt as $v) {
            if ((substr($v, 5, 2) == $mes) && !in_array($v, $feriados)) {
                $data[] = (string) $v;
            }
        }
        return @$data;
    }

    public function listar_datas($data_inicial, $data_final) {
// Converte as datas para o formato timestamp
        $timestamp_inicial = strtotime($data_inicial);
        $timestamp_final = strtotime($data_final);

// Inicializa um array para armazenar as datas
        $datas = array();

// Loop pelas datas a partir da data inicial até a data final
        for ($i = $timestamp_inicial; $i <= $timestamp_final; $i = strtotime('+1 day', $i)) {
// Verifica se a data é um sábado ou domingo
            if (date('N', $i) < 6) {
// Adiciona a data no formato americano ao array de datas
                $datas[] = date('Y-m-d', $i);
            }
        }

// Retorna o array de datas
        return $datas;
    }

    public function diasPlanoTurma($id_turma, $mes) {
        $sql = " SELECT dt_inicio, dt_fim, iddisc FROM coord_plano_aula p "
                . " JOIN coord_plano_aula_turmas t on t.fk_id_plano = p.id_plano "
                . " WHERE t.fk_id_turma = $id_turma "
                . " and substring(`dt_inicio`, 6, 2) <= $mes "
                . " AND substring(`dt_fim`, 6, 2) >= $mes ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $dt = [];
        foreach ($array as $v) {
            if (empty($dt[$v['iddisc']])) {
                $dt[$v['iddisc']] = [];
            }
            $dt[$v['iddisc']] = array_merge($dt[$v['iddisc']], $this->listar_datas($v['dt_inicio'], $v['dt_fim']));
        }
        foreach ($dt as $iddisc => $di) {
            foreach ($di as $v) {
                if ((substr($v, 5, 2) == $mes)) {
                    $data[$iddisc][] = (string) substr($v, -2);
                }
            }
        }
        return @$data;
    }

}
