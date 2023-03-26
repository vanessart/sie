<?php

class formModel extends MainModel {

    public $db;
    public $_pagina;

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
        unset($_SESSION['sqlKey']);
    }

    public function FormView($get) {
        @$formulario = sql::get('pagina', '*', "where pagina = '" . @$get . "'", 'fetch');
        $this->_pagina = $formulario;
        return $formulario['view'];
    }

    public function containers() {
        $containers = sql::get('form_container', '*', ['fk_id_pag' => $this->_pagina['id_pag'], '>' => 'ordem_ctn']);
        return $containers;
    }

    public function qy($id_ctn) {
        $qy = sql::get('form', '*', ['fk_id_ctn' => $id_ctn], 'fetch');
        $tables = explode(',', $qy['tables']);
        //concatena os ? com as variaveis
        if (!empty($qy['var'])) {
            $var = tool::converteArray($qy['var']);
            $wh = explode('?', $qy['wh']);
            $where = $wh[0];
            $c = 1;
            foreach ($var as $v) {
                if ($v[0] == 'php') {
                    $v1 = str_replace("'", '"', $v[1]) . ';';
                    $v = @eval($v1);
                } else {
                    $v = $this->montaCache($v);
                }
                $where .= " '" . $v . "' " . $wh[$c];
                $c++;
            }
        }
        $fields = str_replace('0,', '', $qy['fields']);
        $fields = $fields == '0' ? '*' : $fields;
        $form = sql::get($tables, $fields, @$where, $qy['fh'], $qy['jn']);
        return $form;
    }

    public function getTxt($id_ctn) {
        return sql::get('form_txt', '*', ['fk_id_ctn' => $id_ctn, '>' => 'ordem_txt']);
    }

    public function getRelat($id_ctn) {
        return sql::get('form_relat', '*', "WHERE `fk_id_ctn` = $id_ctn  ORDER BY `ordem_rel` ASC");
    }

    public function montaCache($array) {
        switch ($array[0]) {
            case 'post':
                $vl = @$_POST[$array[1]];
                break;
            case 'userdata':
                $vl = @$_SESSION['userdata'][$array[1]];
                break;
            case 'request':
                $vl = @$_REQUEST[$array[1]];
                break;
            case 'get':
                $vl = @$_GET[$array[1]];
                break;
            case 'cie':
                $vl = escolas::cie();
                break;
            case 'cie':
                $vl = escolas::cie();
                break;
            case 'postcie':
                $vl = empty(@$_POST[$array[1]]) ? escolas::cie() : @$_POST[$array[1]];
                break;
            case 'php':
                $vl = @eval($array[1]);
                break;
        }

        return $vl;
    }

    public function getForm($id_ctn) {
        return sql::get('form_form', '*', "WHERE `fk_id_ctn` = $id_ctn  ORDER BY `ordem_ff` ASC");
    }

    public function retornaForm($form) {
        foreach ($_POST[1] as $k => $v) {
            if (substr($k, 0, 3) == 'id_') {
                $id = $k;
                $id_value = $v;
            }
        }
        foreach ($form as $v) {
            if (@$v[$id] == @$id_value) {
                return $v;
            }
        }
    }

}
