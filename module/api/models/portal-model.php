<?php

class portalModel extends MainModel {

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

    public function escolaDados() {
        $sql = "SELECT 
            n_inst, id_inst, logradouro, num, cep, bairro, 
            latitude, longitude, n_curso, maps, tel1, tel2, tel3, manutencao 
            from instancia as i
        join ge_escolas as escola on
        i.id_inst = escola.fk_id_inst

        join instancia_predio on instancia_predio.fk_id_inst = escola.fk_id_inst
        join predio on predio.id_predio = instancia_predio.fk_id_predio
        join sed_inst_curso on sed_inst_curso.fk_id_inst = instancia_predio.fk_id_inst
        join ge_cursos on ge_cursos.id_curso = sed_inst_curso.fk_id_curso 
        where i.ativo = 1 
        and visualizar = 1
        order by i.n_inst";

        $query = pdoSis::getInstance()->query($sql);
        $dados = $query->fetchAll(pdo::FETCH_ASSOC);

        foreach ($dados as $key => $value) {
            if ($value['manutencao'] == 1) {
                $value['n_inst'] = $value['n_inst'] . ' (em manutenção)';
            }
            $novoArray = $value['n_curso'];
            $arr[$novoArray][$value['id_inst']] = $value;
            $arr['Todas'][$value['id_inst']] = $value;
        }

        echo json_encode($arr);
    }

}
