<?php

class biroModel extends MainModel {

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
        
        
        if (!empty($_POST['salvBiro'])) {
            @$this->_last_id =  $_POST['last_id'];
            if (!empty($_POST['last_id'])) {
                $texto = $_SESSION['userdata']['n_pessoa'] . " abriu o serviço.";
                $this->logset($_POST['last_id'], $texto);
            } elseif (!empty($_POST[1]['id_sup'])) {

                if ((@$_POST['prev_old'] != @$_POST[1]['dt_prev_sup']) && !empty(@$_POST['prev_old'])) {
                    $texto = $_SESSION['userdata']['n_pessoa'] . " alterou a previsão de entrega para " . @$_POST[1]['dt_prev_sup'];
                    $this->logset($_POST[1]['id_sup'], $texto);
                }
                if ((@$_POST['status_old'] != @$_POST[1]['status_sup']) && !empty(@$_POST['status_old'])) {
                    $texto = $_SESSION['userdata']['n_pessoa'] . " alterou o statur do pedido para " . @$_POST[1]['status_sup'];
                    $this->logset($_POST[1]['id_sup'], $texto);
                }
            }
        }
        
    }

    public function logbiro($id = NULL) {
        if (!empty($id)) {
            $dados = sql::get('biro_log', '*', ['fk_id_trab' => $id]);
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

    public function logset($id, $texto) {
        $log = [
            'fk_id_trab' => $id,
            'data' => date("y-m-d H:i:s"),
            'log' => $texto,
            'fk_id_pessoa' => $_SESSION['userdata']['id_pessoa']
        ];
        $this->db->ireplace('biro_log', $log, 1);
    }

}
