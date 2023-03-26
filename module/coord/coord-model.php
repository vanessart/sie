<?php

class coordModel extends MainModel {

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
        $this->db = new crud();
        // Configura o controlador
        $this->controller = $controller;

        // Configura os parâmetros
        $this->parametros = $this->controller->parametros->variavel;

        // Configura os dados do usuário
        $this->userdata = $this->controller->userdata;
        
        //seta o select dinamico
        if ($opt = form::jqOption()) {
            $metodo = $opt[0];
            if (in_array($metodo, get_class_methods($this))) {
                $this->$metodo($opt[1]);
            }
        }
    }

    public static function turma($id_inst) {
        $turma = turma::option($id_inst);
        echo json_encode($turma);
        // echo json_encode([1=>'aa',3=>'bb']);
        exit();
    }

    public static function aluno($id_turma) {
        $t = gtTurmas::alunos($id_turma);
        foreach ($t as $v) {
            $turma[$v['id_pessoa']] = $v['n_pessoa'];
        }

        echo json_encode($turma);
        exit();
    }

    public static function aluno1($id_turma) {
        $t = gtTurmas::alunos($id_turma);
        foreach ($t as $v) {
            $turma[$v['id_pessoa']] = $v['n_pessoa'];
        }

        return $turma;
    }

    // Crie seus próprios métodos daqui em diante
}
