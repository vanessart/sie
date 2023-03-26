<?php

class suporteModel extends MainModel {

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


        if (DB::sqlKeyVerif('mudaInst')) {

            $sql = "UPDATE `acesso_pessoa` SET fk_id_inst = " . $_POST ['fk_id_inst'] . " WHERE  fk_id_pessoa = " . $_SESSION['userdata'] ['id_pessoa'] . " AND fk_id_gr = 19";
            $query = $this->db->query($sql);
            ?>
            <script>
                alert("Sua Instância no Grupo DTTIE - Suporte é <?php echo inst::get($_POST ['fk_id_inst'], 'id_inst', 'n_inst')['n_inst'] ?>");
            </script>
            <?php
        }
        @$_SESSION['sqlKey'];
    }

    // Crie seus próprios métodos daqui em diante
}
