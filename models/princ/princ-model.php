<?php

class princModel extends MainModel {

    public $_form;
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
        $this->parametros = $this->controller->parametros;
// Configura os dados do usuário
        $this->userdata = $this->controller->userdata;

        if (!empty($_POST['sistemaIns'])) {
            if (!empty($_POST['niveis'])) {
                $niveis = serialize($_POST['niveis']);
                $_POST[1]['niveis'] = $niveis;
            }
            $this->db->ireplace('sistema', $_POST[1]);
        }

        if (!empty($_POST['lacarPagina'])) {
            $pagina = $_POST;
            if (is_array($pagina['page'])) {
                $pagina['control'] = "geral";
                if (!empty($pagina['page']['model'])) {
                    @$model = "mdl=" . $_POST['page']['model'] . "&";
                }
                $pagina['page'] = "pdf?" . $model . "pg=" . $_POST['page']['page'];
            }

            @$pagina['pagina'] = !empty($pagina['control']) ? $pagina['control'] . '/' . $pagina['page'] : $pagina['page'];
            unset($pagina['lacarPagina']);
            unset($pagina['control']);
            unset($pagina['page']);
            $pagina['id_pag'] = empty($pagina['id_pag']) ? NULL : $pagina['id_pag'];
            $this->db->ireplace('pagina', $pagina);
        }
//configurar menu
        if ($this->db->sqlKeyVerif('mudarMenu')) {
            $pags = sql::get('sis_nivel_pag', 'paginas', ' WHERE `fk_id_sistema` = ' . $_POST['fk_id_sistema'] . ' AND `fk_id_nivel` = ' . $_POST['fk_id_nivel']);
            if (empty($pags)) {
                $sql = "INSERT INTO `sis_nivel_pag` (`id_sn`, `fk_id_sistema`, `fk_id_nivel`, `paginas`) "
                        . "VALUES (NULL, ?, ?, '');";
                $query = $this->db->query($sql, [$_POST['fk_id_sistema'], $_POST['fk_id_nivel']]);
            }

            @$pagArray = unserialize($pags[0]['paginas']);

            if (!empty($pagArray)) {
                if (in_array(@$_POST['pagina'], @$pagArray)) {
                    foreach ($pagArray as $k => $v) {
                        if ($v == @$_POST['pagina']) {
                            unset($pagArray[$k]);
                        }
                    }
                } else {
                    $pagArray[$_POST['n_pag']] = $_POST['pagina'];
                }
            } else {
                $pagArray[$_POST['n_pag']] = $_POST['pagina'];
            }
            $pagSerie = serialize($pagArray);
            $sql = "UPDATE `sis_nivel_pag` "
                    . "SET `paginas` = ? "
                    . "WHERE `fk_id_sistema` = ? "
                    . " AND `fk_id_nivel` = ? ";
            $query = $this->db->query($sql, [$pagSerie, $_POST['fk_id_sistema'], $_POST['fk_id_nivel']]);
        }

        if ($this->db->sqlKeyVerif('SalvaFom')) {
            $in = $_POST;
            unset($in['sqlKey']);
            unset($in['id_ctn']);
            unset($in['fk_id_pag']);
            if (is_array($in['fields'])) {
                $in['fields'] = implode(',', $in['fields']);
            }
            if (empty($in['id_form'])) {
                $in['id_form'] = NULL;
            }
            $this->db->ireplace('form', $in);
        }

        if ($this->db->sqlKeyVerif('limparMenu')) {
            $sql = "UPDATE `sis_nivel_pag` SET `paginas` = '' WHERE `fk_id_sistema` = ? AND  `fk_id_nivel` = ?";
            $query = $this->db->query($sql, [$_POST['fk_id_sistema'], $_POST['fk_id_nivel']]);
        }
        if ($this->db->sqlKeyVerif('lacarPaginaForm')) {
            $d = $_POST;
            $target = $d['target'] == 1 ? '?target' : NULL;
            unset($d['sqlKey']);
            unset($d['target']);
            $id = $this->db->ireplace('pagina', $d);
            if (empty($id)) {
                $id = $d['id_pag'];
            }
            $pag = ['pagina' => 'form/index/' . $id . $target];

            $this->db->update('pagina', 'id_pag', $id, $pag);
        }
        if (!empty($_POST['atzatafinal'])) {
            if ($_POST['atzatafinal'] == 'Atualiza Ata Final') {
                $this->atznotaconselho($_POST['periodo']);
            }
        }
        if (!empty($_POST['prof_ingles'])) {
            if ($_POST['prof_ingles'] == 'Professor Inglês') {
                $this->atzgealocaprof();
            }
        }
        if (!empty($_POST['rodizio'])) {
            $this->atzrodizio();
        }

        if (!empty($_POST['rodizioclas'])) {
            $this->atzrodizioclas();
        }
        unset($_SESSION['sqlKey']);
    }

    public function selectSistemas($name, $form = 1, $post = NULL) {
        $array = sql::get('sistema', 'id_sistema,n_sistema');
        foreach ($array as $v) {
            $options[$v['id_sistema']] = $v['n_sistema'];
        }
        return formOld::select($name, $options, 'required', $form, NULL, $post);
    }

    public function selectInst($name) {
        $array = sql::get('instancia', 'id_inst,n_inst');
        foreach ($array as $v) {
            $options[$v['id_inst']] = $v['n_inst'];
        }
        return formOld::select($name, $options, 'required');
    }

    public function selectNivel($name, $fields = '*', $where = NULL) {
        $array = sql::get('nivel', 'id_nivel,n_nivel');
        foreach ($array as $v) {
            $options[$v['id_nivel']] = $v['n_nivel'];
        }
        $hidden = ['fk_id_sistema' => $_POST['fk_id_sistema']];
        return formOld::select($name, $options, NULL, 1, $hidden);
    }

    public function formPagina($id_sistema) {

        if (!empty($id_sistema)) {

            $form['array'] = sql::get('pagina', '*', ' WHERE `fk_id_sistema` = ' . $id_sistema . ' ORDER BY `pagina`.`n_pag` ASC ');
            foreach ($form['array'] as $k => $v) {
                $hidden = [
                    'id_pag' => $v['id_pag'],
                    'pagina' => $v['pagina'],
                    'n_pag' => $v['n_pag'],
                    'descr_page' => $v['descr_page'],
                    'ord_pag' => $v['ord_pag'],
                    'fk_id_sistema' => $v['fk_id_sistema'],
                    'ativo' => $v['ativo'],
                    'fk_id_sistema' => $id_sistema
                ];

                $form['array'][$k]['edit'] = formOld::submit('Editar', NULL, $hidden);
                $sqlkey = DB::sqlKey('pagina', 'delete');
                $form['array'][$k]['del'] = formOld::submit('Apagar', $sqlkey, array('1[id_pag]' => $v['id_pag'], 'fk_id_sistema' => $id_sistema));
                $form['array'][$k]['ativo'] = tool::simnao($v['ativo']);
            }
            $form['fields'] = [
                'Nome' => 'n_pag',
                'Arquivo' => 'pagina',
                'Ordem' => 'ord_pag',
                'Ativo' => 'ativo',
                'Descrição' => 'descr_page',
                '||2' => 'del',
                '||1' => 'edit'
            ];
        }



        return $form;
    }

    public function formPagNivel($id_sistema, $id_nivel) {

        if (!empty($id_nivel)) {
            $pags = sql::get('sis_nivel_pag', '*', ' WHERE `fk_id_sistema` = ' . $id_sistema . ' AND `fk_id_nivel` = ' . $id_nivel);

            $form['array'] = sql::get('pagina', '*', ' WHERE `fk_id_sistema` = ' . $id_sistema . ' ORDER BY `pagina`.`n_pag` ASC ');


            foreach ($form['array'] as $k => $v) {
                $sit2 = "Desativado";
                $sqlkey = DB::sqlKey('sis_nivel_pag', 'replace');
                $form['array'][$k]['form'] = formOld::submit($sit2, $sqlkey, ['1[fk_id_sistema]' => $id_sistema, '1[fk_id_nivel]' => $id_nivel, '1[fk_id_pag]' => $v['id_pag'], 'fk_id_sistema' => $id_sistema, 'fk_id_nivel' => $id_nivel], NULL, NULL, 'Ativar este item no Menu?');

                foreach ($pags as $pg) {
                    if (@$pg['fk_id_pag'] == $v['id_pag']) {
                        $sit2 = "Ativado";
                        $sqlkey = DB::sqlKey('sis_nivel_pag', 'delete');
                        $form['array'][$k]['form'] = formOld::submit($sit2, $sqlkey, ['1[id_sn]' => $pg['id_sn'], 'fk_id_sistema' => $id_sistema, 'fk_id_nivel' => $id_nivel], NULL, NULL, 'Desativar este item no Menu?');
                    }
                }
            }

            $form['fields'] = [
                'Nome' => 'n_pag',
                'Arquivo' => 'pagina',
                '||' => 'form'
            ];
        }

        return $form;
    }

    public function selectControles() {
        $array = scandir(ABSPATH . '/controllers');
        foreach ($array as $v) {
            if (substr($v, 0, 1) != '.') {
                $v = explode('-', $v)[0];
                $options[$v] = $v;
            }
        }
        $options['Dropdown'] = 'Dropdown';
        $options['link'] = 'Link';
        $options['PDF'] = 'PDF';

        return $options;
    }

    public function selectPage($control) {
        require_once ABSPATH . '/controllers/' . $control . '-controller.php';
        @$page = get_class_methods($control . 'Controller');

        foreach ($page as $v) {
            if ($v != '__construct') {
                $options[$v] = $v;
            } else {
                break;
            }
        }
        return formOld::select('page', $options);
    }

    public function usuario($search = NULL) {
        if (!empty($search)) {
            $sql = "SELECT *, users.ativo as ativo FROM `pessoa` "
                    . "LEFT JOIN users  on pessoa.id_pessoa = users.fk_id_pessoa "
                    . "LEFT JOIN tipo_user  on tipo_user.id_tp = users.fk_id_tp "
                    . "where cpf = '$search' "
                    . "or email like '$search' "
                    . "or id_pessoa like '$search'";
            $query = $this->db->query($sql);
            $form['array'] = $query->fetchAll();

            foreach ($form['array'] as $k => $v) {
                if ($v['ativo'] != 1) {
                    $sqlKey = NULL;
                    $valor = "Ativar";
                    $hidden = [
                        'search' => $search,
                        'id_pessoa' => $form['array'][$k]['id_pessoa'],
                        'fk_id_tp' => $form['array'][$k]['fk_id_tp'],
                        'registro' => $form['array'][$k]['registro'],
                        'redef' => 1
                    ];
                } else {
                    $sqlKey = $this->db->sqlKey('users', 'replace');
                    $valor = "Desativar";
                    $hidden = [
                        'search' => $search,
                        '1[id_user]' => $v['id_user'],
                        '1[ativo]' => '0'
                    ];
                }
                $form['array'][$k]['ativo'] = tool::simnao($v['ativo']);
                $form['array'][$k]['tipo'] = $v['n_tp'];
                $form['array'][$k]['registro'] = $v['registro'];
                $form['array'][$k]['redef'] = formOld::submit($valor, $sqlKey, $hidden);
            }
            $form['fields'] = [
                'Nome' => 'n_pessoa',
                'CPF' => 'cpf',
                'E-mail' => 'email',
                'Tipo' => 'tipo',
                'Registro' => 'registro',
                'Ativado' => 'ativo',
                '||' => 'redef'
            ];
        }
        $form['titulo'] = "Gerenciamento de Usuários";

        return $form;
    }

    public function acesso($id_pessoa) {
        $sqlkey = $this->db->sqlKey('acesso', 'delete');
        $form['titulo'] = "Acessos aos Sistemas";
        $form['array'] = $this->listAcesso($id_pessoa);
        $form['id_pessoa'] = $id_pessoa;
        foreach ($form['array'] as $k => $v) {
            $form['array'][$k]['del'] = formOld::submit('Excluir', $sqlkey, ['1[id_acesso]' => $v['id_acesso'], 'search' => @$_POST['search'], 'id_pessoa' => $v['id_pessoa'], 'fk_id_nivel' => @$_POST['fk_id_nivel'], 'fk_id_sistema' => @$_POST['fk_id_sistema']]);
        }
        if (is_array($id_pessoa)) {
            $form['fields'] = [
                'Subsistema' => 'n_sistema',
                'Nível de Acesso' => 'n_nivel',
                'Instância' => 'n_inst',
                'Usuário' => 'n_pessoa',
                '||' => 'del'
            ];
        } else {

            $form['fields'] = [
                'Subsistema' => 'n_sistema',
                'Nível de Acesso' => 'n_nivel',
                'Instância' => 'n_inst',
                '||' => 'del'
            ];
        }

        return $form;
    }

    public function tableSelecte($dados = NULL) {
        $tables = sql::tables();

        return formOld::select('tables[]', $tables, NULL, NULL, NULL, $dados);
    }

    public function getPagina($id_pag) {
        $dados = sql::get('pagina', '*', "where id_pag = '$id_pag'", 'fetch');

        return $dados;
    }

    public function grupUser($id_gr) {
        $sql = "SELECT "
                . " p.n_pessoa, i.n_inst, p.id_pessoa, p.cpf, p.emailgoogle "
                . " FROM acesso_pessoa a "
                . " JOIN pessoa p on p.id_pessoa = a.fk_id_pessoa "
                . " JOIN instancia i on i.id_inst = a.fk_id_inst "
                . " WHERE a.fk_id_gr = $id_gr";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function sisUser($id_sistema) {
        $sql = "SELECT "
                . " p.n_pessoa, i.n_inst, p.id_pessoa, p.cpf, p.emailgoogle, "
                . " gr.n_gr "
                . " FROM acesso_pessoa a "
                . " JOIN acesso_gr g on g.fk_id_gr = a.fk_id_gr "
                . " JOIN grupo gr on gr.id_gr = a.fk_id_gr "
                . " JOIN pessoa p on p.id_pessoa = a.fk_id_pessoa "
                . " JOIN instancia i on i.id_inst = a.fk_id_inst "
                . " WHERE g.fk_id_sistema = $id_sistema";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function atznotaconselho($per) {
        $cons = [6 => 6, 9 => 9, 10 => 10, 11 => 11, 12 => 12, 13 => 13, 14 => 14, 15 => 15, 16 => 16, 17 => 17];

        foreach ($cons as $v) {
            $sql = "UPDATE ge_turma_aluno ta"
                    . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                    . " JOIN hab.aval_final af ON af.fk_id_pessoa = ta.fk_id_pessoa"
                    . " SET af.cons_" . $v . " = 5, ta.conselho = '1'"
                    . " WHERE ta.situacao_final = '2' AND t.fk_id_pl = '" . $per . "'"
                    . " AND af.media_" . $v . " < 5";

            $query = $this->db->query($sql);

            $sql = "UPDATE ge_turma_aluno ta"
                    . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                    . " JOIN hab.aval_final af ON af.fk_id_pessoa = ta.fk_id_pessoa"
                    . " SET af.cons_" . $v . " = NULL, ta.conselho = '1'"
                    . " WHERE ta.situacao_final = '5' AND t.fk_id_pl = '" . $per . "'";

            $query = $this->db->query($sql);
        }
        tool::alert('OK');
    }

    public function atzgealocaprof() {

        $sql = "SELECT * FROM ge_aloca_prof af"
                . " JOIN ge_turmas t ON t.id_turma = af.fk_id_turma"
                . " WHERE t.fk_id_ciclo IN (6,7,8,9) AND t.fk_id_pl = 81"
                . " AND af.iddisc = 15";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($array)) {
            foreach ($array as $v) {
               $wsql = "SELECT * FROM ge_aloca_prof af"
                        . " WHERE fk_id_turma = '" . $v['fk_id_turma'] . "'"
                        . " AND iddisc = 30 AND prof2 = 1";

                $query = $this->db->query($wsql);
                $resp = $query->fetch()['fk_id_turma'];
              
                if (!empty($resp)) {
                    $del_alt = "DELETE FROM ge_aloca_prof WHERE fk_id_turma = '" . $v['fk_id_turma'] . "'"
                            . " AND iddisc = '" . '15' . "' AND prof2 = 1";
                } else {
                    $del_alt = "UPDATE ge_aloca_prof af SET af.iddisc = 30"
                            . " WHERE fk_id_turma = '" . $v['fk_id_turma'] . "'"
                            . " AND iddisc = 15 AND prof2 = 1";
                }
               // echo $del_alt;
               $query = $this->db->query($del_alt);
            }
        }
        tool::alert("Operação Efetuada com Sucesso");
    }
public function atzrodizio() {

        $sql = "SELECT * FROM cadam_cargo";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $a = 1;
            $wsql = "SELECT * FROM cadam_classificacao_cargo_geral"
                    . " WHERE fk_id_cargo = " . $v['id_cargo']
                    . " ORDER BY rodizio, class_geral";

            $query = pdoSis::getInstance()->query($wsql);
            $prof = $query->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($prof)) {
                foreach ($prof as $w) {
                    $sql2 = "UPDATE cadam_classificacao_cargo_geral"
                            . " SET rodizio = 2, tmp =" . $a
                            . " WHERE id = " . $w['id'];

                    $query = $this->db->query($sql2);

                    $a = $a + 1;
                }
            }
        }
    }

    public function atzrodizioclas() {
        $sql = "SELECT * FROM cadam_cargo";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
           $wsql = "SELECT * FROM cadam_classificacao_cargo_geral"
                    . " WHERE fk_id_cargo = " . $v['id_cargo']
                    . " ORDER BY tmp";

            $query = pdoSis::getInstance()->query($wsql);
            $cla = $query->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($cla)) {
                foreach ($cla as $v) {
                    if ($v['tmp'] == 1) {
                        $cla = $v['class_geral'];
                    } else {
                        if ($v['class_geral'] < $cla) {
                           $sql2 = "UPDATE cadam_classificacao_cargo_geral"
                                    . " SET rodizio = " . 3
                                    . " WHERE id = " . $v['id'];

                            $query = $this->db->query($sql2);
                        }
                    }
                }
            }
        }
    }

}
