<?php

class publicModel extends MainModel {
    
    public $db;

    public $_url;

    public function __construct($db = false, $controller = null) {
        // Configura o DB (PDO)
        $this->db = new DB();

        // Configura o controlador
        $this->controller = $controller;

        // Configura os parâmetros
        $this->parametros = $this->controller->parametros;
        // Configura os parâmetros
        //$this->parametros = $this->controller->parametros;
        // Configura os dados do usuário
        @$this->userdata = $this->controller->userdata;
        
        
    }

    // Crie seus próprios métodos daqui em diante
}
