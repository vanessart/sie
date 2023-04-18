<?php

class adminModel extends MainModel {

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

    public function pesqPessoas($pesq, $func = null) {
        if ($pesq) {
            if (!$func) {
                $left = 'left ';
            } else {
                $left = null;
            }
            $sql = "SELECT "
                    . " p.id_pessoa, p.n_pessoa, p.cpf, p.emailgoogle, f.rm "
                    . " FROM pessoa p "
                    . " $left JOIN ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
                    . " WHERE "
                    . " p.id_pessoa = '$pesq' "
                    . " OR p.n_pessoa LIKE '%$pesq%' "
                    . " OR p.cpf = '$pesq' "
                    . " OR p.emailgoogle LIKE '$pesq' "
                    . " OR f.rm LIKE '$pesq' "
                    . " order by p.n_pessoa "
                    . " limit 0, 40 ";
            $query = pdoSis::getInstance()->query($sql);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
    }

}
