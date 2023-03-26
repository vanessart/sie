<?php

class apiModel extends MainModel {

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

    public function token() {
        $erroTp = [
            1 => 'Campos incompletos',
            2 => 'Senha ou usuário incorreto',
        ];
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $password = str_replace("'", '', filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
        if ($id_pessoa && $password) {
            $sql = "SELECT "
                    . " u.user_password "
                    . " FROM pessoa p "
                    . " JOIN api_user api on api.fk_id_pessoa = p.id_pessoa "
                    . " JOIN users u on u.fk_id_pessoa = p.id_pessoa "
                    . " WHERE id_pessoa = $id_pessoa ";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($array) {
                
            } else {
                $erro = 2;
            }
        } else {
            $erro = 1;
        }

        if ($erro) {
            echo json_encode(['erro' => $erroTp[$erro]]);
        }
    }

}
