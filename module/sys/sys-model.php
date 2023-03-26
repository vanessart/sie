<?php

class sysModel extends MainModel {

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

    public function Ajuda($pesq = NULL, $count = NULL) {
        if (!empty($pesq['id_ac'])) {
            $id_ac = ' AND ac.id_ac = ' . $pesq['id_ac'];
        }
        if (!empty($pesq['class'])) {
            $class = " AND a.class = '" . $pesq['class']."' ";
        }
         if (!empty($pesq['criterio'])) {
            $class = " AND "
                    . "("
                    . " a.n_sa like '%".$pesq['criterio']."%' "
                    . " OR "
                    . " a.class like '%".$pesq['criterio']."%' "
                    . " OR "
                    . " a.descr_sa like '%".$pesq['criterio']."%' "
                    . ")";
        }
        $sql = "SELECT a.*, ac.n_ac FROM `sys_ajuda` a "
                . " join sys_ajuda_cat ac on ac.id_ac = a.fk_id_ac "
                . " where 1 "
                . @$id_ac
                . @$class
                . " order by n_sa ";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    // Crie seus próprios métodos daqui em diante
}
