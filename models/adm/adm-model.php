<?php

class admModel extends MainModel {

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
        //$this->parametros = $this->controller->parametros;
        // Configura os dados do usuário
        $this->userdata = $this->controller->userdata;
        if (DB::sqlKeyVerif('novaPessoa')) {
            pessoa::replace($_POST[1]);
        }

        if (DB::sqlKeyVerif('insAcesso')) {
            $this->sisAcesso();
        }

        if (DB::sqlKeyVerif('pessoaPermissao')) {
            @$horas = serialize($_POST['horas']);
            $expira = data::converteUS($_POST['expira']);
            $sql = "UPDATE `users` SET "
                    . "`expira`='$expira',"
                    . "`horas`='$horas' "
                    . "WHERE fk_id_pessoa = " . $_POST['id_pessoa'];
            $query = $this->db->query($sql);
            if($query){
                tool::sucesso();
            }
        }
        
        if(!empty($_POST['senha'])){
            sendEmail::enviaEmail($_POST['n_pessoa'], $_POST['email'], $_POST['senha'], $_POST['user'], 'https://portal.educ.net.br/ge/adm/user/');
        }
     

    }

    public function sisAcesso() {
        $sql = "DELETE FROM `acesso_gr` WHERE `fk_id_gr` = " . $_POST['id_gr'];
        $query = $this->db->query($sql);
        foreach ($_POST as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $kk => $vv) {
                    $sql = "INSERT INTO `acesso_gr` (`id_ag`, `fk_id_gr`, `fk_id_sistema`, `fk_id_nivel`) VALUES ("
                            . "NULL, "
                            . "'" . $_POST['id_gr'] . "', "
                            . "'$k', "
                            . "'$kk');";
                    $query = $this->db->query($sql);
                }
            }
        }
    }

}
