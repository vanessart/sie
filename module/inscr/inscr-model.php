<?php

class inscrModel extends MainModel {

    public $db;
    public $evento;
    public $recurso;
    public $publicar;

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

        $this->eventoAtivo();
        //seta o select dinamico
        if ($opt = formErp::jqOption()) {
            $metodo = $opt[0];
            if (in_array($metodo, get_class_methods($this))) {
                $this->$metodo($opt[1]);
            }
        }
        if ($this->db->tokenCheck('eventoSalvar')) {
            $this->eventoSalvar();
        } else if ($this->db->tokenCheck('upDoc')) {
            $this->upDoc();
        } else if ($this->db->tokenCheck('upDocExcluir')) {
            $this->upDocExcluir();
        } else if ($this->db->tokenCheck('fim')) {
            $this->fim();
        } else if ($this->db->tokenCheck('recursoUp')) {
            $this->recursoUp();
        } else if ($this->db->tokenCheck('upFilhos')) {
            $this->upFilhos();
        } else if ($this->db->tokenCheck('upFilhoExcluir')) {
            $this->upFilhoExcluir();
        } else if ($this->db->tokenCheck('SalvaUpFinal')) {
            $this->SalvaUpFinal();
        } else if ($this->db->tokenCheck('excluirUpFinal')) {
            $this->excluirUpFinal();
        } else if ($this->db->tokenCheck('entregaSalva')) {
            $this->entregaSalva();
        } else if ($this->db->tokenCheck('inscr_certificado_deferimentoSet')) {
            $this->inscr_certificado_deferimentoSet();
        }
    }

    public function inscr_certificado_deferimentoSet() {
        $deferimentoFinal = $periodo = filter_input(INPUT_POST, 'deferimentoFinal');
        $fk_id_sit = [
            1 => 3,
            2 => 4
        ];
        $ins = @$_POST[1];
        $this->db->ireplace('inscr_certificado_deferimento', $ins);
        if (!empty($fk_id_sit[$ins['deferido']]) && $deferimentoFinal) {
            $ins2['id_ec'] = $ins['fk_id_ec'];
            $ins2['fk_id_sit'] = $fk_id_sit[$ins['deferido']];
            $this->db->ireplace('inscr_evento_categoria', $ins2, 1);
        }
    }

    public function entregaSalva() {
        $up = @$_POST['up'];
        if ($up) {
            foreach ($up as $k => $v) {
                $verif = sql::get('inscr_final_up', 'ativo', ['id_fu' => $k], 'fetch')['ativo'];
                if ($verif != $v) {
                    $iup['id_fu'] = $k;
                    $iup['ativo'] = $v;
                    $this->db->ireplace('inscr_final_up', $iup, 1);
                }
            }
        }
        $ins = $_POST[1];
        $this->db->ireplace('inscr_incritos_3', $ins);
    }

    public function excluirUpFinal() {
        $id_fu = filter_input(INPUT_POST, 'id_fu', FILTER_SANITIZE_NUMBER_INT);
        $this->db->delete('inscr_final_up', 'id_fu', $id_fu);
    }

    public function SalvaUpFinal() {
        $id_ftu = filter_input(INPUT_POST, 'id_ftu', FILTER_SANITIZE_NUMBER_INT);
        if (!empty($_FILES['arquivo'])) {
            if ($_FILES['arquivo']['size'] > 5000000) {
                toolErp::alertModal('O limite é 5 megabytes');
                return;
            }
            @$exten = end(explode('.', $_FILES['arquivo']['name']));
            if (!in_array($exten, ['pdf', 'PDF'])) {
                toolErp::alertModal('Só é permitido anexar PDF');
                return;
            }
            $nome_origin = $_FILES['arquivo']['name'];
            $cpf = $_SESSION['TMP']['CPF'];
            $file = ABSPATH . '/pub/inscrOnline/';
            $up = new upload($file, $cpf, 5000000, ['pdf', 'PDF']);
            $end = $up->up();
            if ($end) {
                $ins['cpf'] = $cpf;
                $ins['fk_id_ftu'] = $id_ftu;
                $ins['link'] = $end;
                $ins['nome_origin'] = toolErp::escapaAspa($nome_origin);
                $this->db->ireplace('inscr_final_up', $ins);
            } else {
                toolErp::alertModal('Erro ao enviar. Tente novamente');
            }
        } else {
            toolErp::alertModal('Erro ao enviar. Tente novamente');
        }
    }

    public function upFilhoExcluir() {
        $id_iuf = filter_input(INPUT_POST, 'id_iuf', FILTER_SANITIZE_NUMBER_INT);
        $this->db->delete('inscr_upload_filhos', 'id_iuf', $id_iuf);
    }

    public function upFilhos() {
        if (!empty($_FILES['arquivo'])) {
            if ($_FILES['arquivo']['size'] > 5000000) {
                toolErp::alertModal('O limite é 5 megabytes');
                return;
            }
            @$exten = end(explode('.', $_FILES['arquivo']['name']));
            if (!in_array($exten, ['pdf', 'PDF'])) {
                toolErp::alertModal('Só é permitido anexar PDF');
                return;
            }
            $filho = filter_input(INPUT_POST, 'filho', FILTER_SANITIZE_NUMBER_INT);
            $nome_origin = $_FILES['arquivo']['name'];
            $cpf = $_SESSION['TMP']['CPF'];
            $id_evento = $_SESSION['TMP']['FORM'];
            $file = ABSPATH . '/pub/inscrOnline/';
            $up = new upload($file, $cpf, 5000000, ['pdf', 'PDF']);
            $end = $up->up();
            if ($end) {
                $ins['nome_origin'] = toolErp::escapaAspa($nome_origin);
                $ins['filho'] = $filho;
                $ins['link'] = $end;
                $ins['cpf'] = $cpf;
                $ins['fk_id_evento'] = $id_evento;
                $this->db->ireplace('inscr_upload_filhos', $ins);
            } else {
                toolErp::alertModal('Erro ao enviar. Tente novamente');
            }
        } else {
            toolErp::alertModal('Erro ao enviar. Tente novamente');
        }
    }

    public function cabecalhoSecretaria() {

        $header = '<table style="width: 100%; border: 1px solid">'
                . '<tr>'
                . '<td rowspan = "5">'
                . '<img style="width: 70px" src="' . HOME_URI . '/views/_images/brasao.jpg"/>'
                . '</td>'
                . '<td style="font-size: 18px; text-align: Center">'.CLI_NOME.'</td>'
                . '<td rowspan = "5" style=" text-align: right">'
                . '<img style="width: 210px;" src="' . HOME_URI . '/views/_images/logo_relatorio.jpg"/>'
                . '</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 16px; text-align: Center">SE - Secretaria de Educação</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 10px; text-align: Center">'.CLI_END.'</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 10px; text-align: Center">'.CLI_BAIRRO.' - '.CLI_CIDADE.' - '.CLI_UF.' CEP '.CLI_CEP.' Fone '.CLI_FONE.'</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 10px; text-align: Center">'.CLI_URL.' - Email: '.CLI_MAIL.'</td>'
                . '</tr>'
                . '</table>';

        return $header;
    }

    public function recursoUp() {
        $id_ec = filter_input(INPUT_POST, 'id_ec', FILTER_SANITIZE_NUMBER_INT);
        if (!empty($_FILES['arquivo'])) {
            if ($_FILES['arquivo']['size'] > 5000000) {
                toolErp::alertModal('O limite é 5 megabytes');
                return;
            }
            @$exten = end(explode('.', $_FILES['arquivo']['name']));
            if (!in_array($exten, ['pdf', 'PDF'])) {
                toolErp::alertModal('Só é permitido anexar PDF');
                return;
            }
            $id_up = filter_input(INPUT_POST, 'id_up', FILTER_SANITIZE_NUMBER_INT);
            $nome_origin = $_FILES['arquivo']['name'];
            $cpf = $_SESSION['TMP']['CPF'];
            $id_evento = $_SESSION['TMP']['FORM'];
            $id_cate = $_SESSION['TMP']['CATE'];
            $file = ABSPATH . '/pub/inscrOnline/';
            $up = new upload($file, $cpf, 5000000, ['pdf', 'PDF']);
            $end = $up->up();
            if ($end) {
                $ins['nome_origin'] = toolErp::escapaAspa($nome_origin);
                $ins['fk_id_ec'] = $id_ec;
                $ins['link'] = $end;
                $this->db->ireplace('inscr_recurso_up', $ins);
            } else {
                toolErp::alertModal('Erro ao enviar. Tente novamente');
            }
        } else {
            toolErp::alertModal('Erro ao enviar. Tente novamente');
        }
    }

    public function eventoAtivo() {
        $sql = "SELECT id_evento, public, recurso FROM `inscr_evento` WHERE `at_evento` = 1 ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($array['id_evento'])) {
            $this->evento = $array['id_evento'];
            $this->publicar = $array['public'];
            $this->recurso = $array['recurso'];
        }
    }

    public function fim() {
        $cpf = $_SESSION['TMP']['CPF'];
        $sql = "UPDATE `inscr_evento_categoria` SET "
                . " `fk_id_sit` = '2', "
                . " dt_inscr = '" . date("Y-m-d H:i:s") . "' "
                . " WHERE fk_id_cpf = $cpf "
                . " and fk_id_cate = " . $_SESSION['TMP']['CATE'];
        pdoSis::action($sql);
        $_SESSION['TMP']['SIT'] = 2;
    }

    public function upDocExcluir() {
        $id_iu = filter_input(INPUT_POST, 'id_iu', FILTER_SANITIZE_NUMBER_INT);
        $this->db->delete('inscr_inscr_upload', 'id_iu', $id_iu);
    }

    public function upDoc() {
        if (!empty($_FILES['arquivo'])) {
            if ($_FILES['arquivo']['size'] > 5000000) {
                toolErp::alertModal('O limite é 5 megabytes');
                return;
            }
            @$exten = end(explode('.', $_FILES['arquivo']['name']));
            if (!in_array($exten, ['pdf', 'PDF'])) {
                toolErp::alertModal('Só é permitido anexar PDF');
                return;
            }
            $id_up = filter_input(INPUT_POST, 'id_up', FILTER_SANITIZE_NUMBER_INT);
            $nome_origin = $_FILES['arquivo']['name'];
            $cpf = $_SESSION['TMP']['CPF'];
            $id_evento = $_SESSION['TMP']['FORM'];
            $id_cate = $_SESSION['TMP']['CATE'];
            $file = ABSPATH . '/pub/inscrOnline/';
            $up = new upload($file, $cpf, 5000000, ['pdf', 'PDF']);
            $end = $up->up();
            if ($end) {
                $ins['nome_origin'] = toolErp::escapaAspa($nome_origin);
                $ins['fk_id_up'] = $id_up;
                $ins['link'] = $end;
                $ins['cpf'] = $cpf;
                $ins['fk_id_evento'] = $id_evento;
                $ins['fk_id_cate'] = $id_cate;
                $this->db->ireplace('inscr_inscr_upload', $ins);
            } else {
                toolErp::alertModal('Erro ao enviar. Tente novamente');
            }
        } else {
            toolErp::alertModal('Erro ao enviar. Tente novamente');
        }
    }

    public function eventoSalvar() {
        $ins = $_POST[1];
        $id = $this->db->ireplace('inscr_evento', $ins);
        try {
            $sql = "SELECT * FROM `inscr_incritos_" . $id . "`";
            $query = pdoSis::getInstance()->query($sql);
        } catch (Exception $ex) {
            $sql = "CREATE TABLE IF NOT EXISTS inscr_incritos_" . $id . " SELECT * FROM inscr_incritos_; ";
            $query = pdoSis::getInstance()->query($sql);
            $sql = "ALTER TABLE `inscr_incritos_" . $id . "` ADD `id_cpf` varchar(20) NOT NULL FIRST, ADD PRIMARY KEY (`id_cpf`);";
            $query = pdoSis::getInstance()->query($sql);
        }
    }

    public function inscritos($sit = null, $search = null, $id_cate = null, $fk_id_vs = null) {
        $sql = " SELECT cpf FROM `inscr_inscr_upload` ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            @$cpfs[$v['cpf']]++;
        }
        if (!empty($fk_id_vs)) {
            if ($fk_id_vs == 4) {
                $fk_id_vs = " AND (fk_id_vs is null OR fk_id_vs = 0) and pis is not null ";
            } else {
                $fk_id_vs = " AND fk_id_vs = $fk_id_vs ";
            }
        }
        if ($sit) {
            $sit = " AND ic.fk_id_sit = $sit";
        }
        if ($search) {
            $search = " AND (nome like '%$search%' OR id_cpf = '$search' )";
        }
        if ($id_cate) {
            $id_cate = " AND cate.id_cate = $id_cate ";
        }
        $sql = " SELECT * FROM inscr_incritos_" . $this->evento . " i "
                . " JOIN inscr_evento_categoria ic on ic.fk_id_cpf = i.id_cpf "
                . " JOIN inscr_situacao sit on sit.id_sit = ic.fk_id_sit "
                . " JOIN inscr_categoria cate on cate.id_cate = ic.fk_id_cate "
                . " WHERE 1 "
                . $sit
                . $search
                . $id_cate
                . $fk_id_vs
                . " ORDER BY n_cate, nome";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $k => $v) {
            $array[$k]['docs'] = intval(@$cpfs[$v['id_cpf']]);
        }



        return $array;
    }

    public function recursos($resp = null, $search = null, $id_cate = null) {
        if ($resp) {
            $resp = " AND r.deferido != 0";
        } else {
            $resp = " AND r.deferido = 0 ";
        }
        if ($search) {
            $search = " AND (nome like '%$search%' OR id_cpf = '$search' )";
        }
        if ($id_cate) {
            $id_cate = " AND cate.id_cate = $id_cate ";
        }
        $sql = " SELECT * FROM inscr_incritos_" . $this->evento . " i "
                . " JOIN inscr_evento_categoria ic on ic.fk_id_cpf = i.id_cpf "
                . " JOIN inscr_situacao sit on sit.id_sit = ic.fk_id_sit "
                . " JOIN inscr_categoria cate on cate.id_cate = ic.fk_id_cate "
                . " JOIN inscr_recurso r on r.fk_id_ec = ic.id_ec "
                . " WHERE 1 "
                . $resp
                . $search
                . $id_cate
                . " ORDER BY n_cate, nome";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function inscrito($id_ec) {

        $sql = " SELECT * FROM inscr_incritos_" . $this->evento . " i "
                . " JOIN inscr_evento_categoria ic on ic.fk_id_cpf = i.id_cpf "
                . " JOIN inscr_situacao sit on sit.id_sit = ic.fk_id_sit "
                . " left JOIN inscr_categoria ct on ct.id_cate = ic.fk_id_cate "
                . " WHERE id_ec = $id_ec";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    public function inscrUp($cpf, $id_cate) {
        $sql = "SELECT * FROM inscr_inscr_upload "
                . " WHERE fk_id_cate = $id_cate "
                . " AND fk_id_evento = " . $this->evento
                . " AND cpf LIKE '$cpf' ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $v) {
                $up[$v['fk_id_up']][] = $v;
            }
            if (!empty($up)) {
                return $up;
            }
        }
    }

    public function cateUp($id_cate) {
        $sql = "SELECT * FROM inscr_upload WHERE fk_id_cate = $id_cate OR fk_id_cate = 0 ORDER BY `inscr_upload`.`ordem` ASC ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($array) {
            return $array;
        }
    }

    public function classifica($id_cate) {
        $fields = "ec.id_ec as Inscrição, i.nome as Nome, i.nome_social Social, "
                . " i.id_cpf as CPF,"
                . " SUM(c.pontos) Pontos, "
                . " i.dt_nasc as Nascimento, "
                . " i.filhos as Filho ";
        $sql = "SELECT $fields FROM inscr_evento_categoria ec "
                . " JOIN inscr_incritos_" . $this->evento . " i on i.id_cpf = ec.fk_id_cpf "
                . " LEFT JOIN inscr_certificado_deferimento c on c.fk_id_ec = id_ec "
                . " WHERE `fk_id_cate` = $id_cate "
                . " AND `fk_id_sit` = 3 "
                . " GROUP BY i.id_cpf "
                . " ORDER by Pontos DESC, dt_nasc ASC, filhos DESC, dt_inscr ASC  ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $classifica = 1;
        foreach ($array as $k => $v) {
            $array1[$k]['Classificação'] = (string) $classifica++;
            $array1[$k]['Inscrição'] = $v['Inscrição'];
            $array1[$k]['Nome'] = $v['Nome'];
            $array1[$k]['Pontos'] = $v['Pontos'];
        }
        return $array1;
    }

    public function dadosDeferidos($id_cate) {
        $fields = " distinct ec.id_ec as Inscrição, i.*, "
                . " i.id_cpf as CPF,"
                . " SUM(c.pontos) Pontos, "
                . " i.dt_nasc as Nascimento, "
                . " i.filhos as Filho ";
        $sql = "SELECT $fields FROM inscr_evento_categoria ec "
                . " JOIN inscr_incritos_" . $this->evento . " i on i.id_cpf = ec.fk_id_cpf "
                . " LEFT JOIN inscr_certificado_deferimento c on c.fk_id_ec = id_ec "
                . " WHERE `fk_id_cate` = $id_cate "
                . " AND `fk_id_sit` = 3 "
                . " GROUP BY i.id_cpf "
                . " ORDER by Pontos DESC, dt_nasc ASC, filhos DESC, dt_inscr ASC  ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $k => $v) {
            $array[$k]['Nascimento'] = dataErp::converteBr($v['Nascimento']);
        }
        return $array;
    }

    public function indeferido($id_cate) {
        $fields = "ec.id_ec as Inscrição, i.nome as Nome, i.nome_social Social, "
                . " i.id_cpf as CPF,"
                . " i.dt_nasc as Nascimento, obs_ec as obs ";
        $sql = "SELECT $fields FROM inscr_evento_categoria ec "
                . " JOIN inscr_incritos_" . $this->evento . " i on i.id_cpf = ec.fk_id_cpf "
                . " WHERE ec.`fk_id_cate` = $id_cate "
                . " AND `fk_id_sit` = 4 "
                . " ORDER by Nome  ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $sql = "SELECT `fk_id_ec`, `obs_cd` FROM `inscr_certificado_deferimento` WHERE `obs_cd` IS NOT NULL ";
        $query = pdoSis::getInstance()->query($sql);
        $obs1 = $query->fetchAll(PDO::FETCH_ASSOC);

        $sql = "SELECT `cpf`, `obs`, n_mot FROM `inscr_inscr_upload` u "
                . " left join inscr_motivo m on m.id_mot = u.fk_id_mot ";

        $query = pdoSis::getInstance()->query($sql);
        $obs2 = $query->fetchAll(PDO::FETCH_ASSOC);


        foreach ($array as $k => $v) {
            $array[$k]['obs'] = mb_strtoupper($v['obs']);
            foreach ($obs1 as $y) {
                if ($y['fk_id_ec'] == $v['Inscrição']) {
                    $array[$k]['obs'] .= mb_strtoupper($y['obs_cd']) . '; ';
                }
            }
            foreach ($obs2 as $y) {
                if ($y['cpf'] == $v['CPF']) {
                    if (!empty($y['obs'])) {
                        $array[$k]['obs'] .= mb_strtoupper($y['obs']) . '; ';
                    }
                    if (!empty($y['n_mot'])) {
                        $array[$k]['obs'] .= $y['n_mot'] . '; ';
                    }
                }
            }

            $array[$k]['Nascimento'] = dataErp::converteBr($v['Nascimento']);
        }
        return $array;
    }

}
