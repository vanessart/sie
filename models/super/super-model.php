<?php

class superModel extends MainModel {

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
        if (!empty($_POST['salvEscola'])) {
            @$this->_last_id = $_POST['last_id'];
            if (!empty($_POST['last_id'])) {
                $texto = $_SESSION['userdata']['n_pessoa'] . " abriu o serviço.";
                $this->logset($_POST['last_id'], $texto);
            } elseif (@$_POST[1]['status_sup'] == 'Cancelado') {
                $texto = $_SESSION['userdata']['n_pessoa'] . " Cancelou a solicitação ";
                $this->logset($_POST[1]['id_sup'], $texto);
                echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/super/escolapesq";</script>';
            }
        }


        if (DB::sqlKeyVerif('super_suporte_trab')) {
            if (empty($_POST['descr'])) {
                unset($_POST[1]['ultimo_lado']);
            }
            
            $this->_last_id = $id_sup = $this->db->ireplace('super_suporte_trab', $_POST[1]);

            if (!empty($_POST['id_sup'])) {
                $id = $_POST['id_sup'];
            } else {
                $id = $id_sup;
            }
            if (!empty($_POST['descr'])) {
                $d['fk_id_sup'] = $id_sup;
                $d['lado'] = $_POST[1]['ultimo_lado'];
                $d['fk_id_pessoa'] = tool::id_pessoa();
                $d['descr'] = $_POST['descr'];
                $d['data'] = date("Y-m-d H:i:s");
                $this->db->insert('super_suport_diag', $d);
            }
            if (@$_POST[1]['status_sup'] == 'Finalizado') {
                echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/super/escolapesq";</script>';
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
        $this->db->ireplace('super_suporte_log', $log, 1);
    }

    public function logSuporte($id = NULL) {
        if (!empty($id)) {
            $dados = sql::get('super_suporte_log', '*', ['fk_id_sup' => $id]);
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

    public function chromeBooks($id_inst) {
        $sql = "SELECT * FROM `lab_chrome` "
                . " WHERE `fk_id_inst` LIKE '$id_inst' "
                . " and `status` LIKE '1' "
                . " ORDER BY `lab_chrome`.`carrinho` ASC ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $ch[$v['id_ch']] = 'PAT:' . $v['patrimonio'] . ' n/s: ' . $v['serial'];
        }
if(!empty($ch)){
     return $ch;
}
       
    }
    
    public function chromeModel(){
        $sql ="SELECT * FROM `lab_chrome_mod` "
                . " WHERE `at_cd` = 1 ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        
        return tool::idName($array);
    }

}
