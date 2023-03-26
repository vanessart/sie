<?php

class chromeModel extends MainModel {

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

    public function serialId() {
        $chromes = sql::get('lab_chrome', 'id_ch, serial n_serial');
        $chromes = toolErp::idName($chromes);

        echo json_encode($chromes);
    }

    public function serialSelect() {
        $sql = "SELECT `id_ch`, `serial` FROM `lab_chrome`";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $opt .= '<option value="' . $v['serial'] . '">';
        }
        echo $opt;
    }

}
//selectpicker