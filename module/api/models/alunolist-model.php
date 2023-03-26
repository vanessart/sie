<?php

class alunolistModel extends MainModel {

    public $db;

    public function __construct($db = false, $controller = null) {
        // Configura o DB (PDO)
        $this->db = new crud();
        // Configura o controlador
        $this->controller = $controller;

        // Configura os parâmetros
        $this->parametros = $this->controller->parametros->variavel;

        // Configura os dados do usuário
        $this->userdata = $this->controller->userdata;
    }

    public function escola() {
        ##################            
        ?>
        <pre>   xxxxxxxxxxxxxx
            <?php
            print_r($_REQUEST);
            ?>
        </pre>
        <?php
###################
    }

}
