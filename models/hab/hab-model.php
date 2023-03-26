<?php

class habModel extends MainModel {

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

        if (DB::sqlKeyVerif('cadnomenclatura')) {

            $this->db->ireplace('hab_grupo2', $_POST[1]);
            $this->db->ireplace('hab_competencia', $_POST[2]);
            $this->db->ireplace('hab_habilidade', $_POST[3]);
        }

        if (DB::sqlKeyVerif('inserirHab')) {
            foreach ($_POST[1] as $v) {
                foreach ($v['hab'] as $kk => $vv) {
                    $insert['hab' . $kk] = $vv;
                    $insert['fk_id_competencia_opcoes'] = $v['fk_id_competencia_opcoes'];
                    $insert['fk_id_pessoa'] = $_POST['id_pessoa'];
                    $insert['fk_id_turma'] = $_POST['id_turma'];
                }
                
                $this->db->ireplace('hab_aluno', $insert, 1);
            }
        }

        if (DB::sqlKeyVerif('cadperiodo')) {

            $this->db->insert('hab_periodo', $_POST[1]);
        }
    }

    // Crie seus próprios métodos daqui em diante

    public static function ciclos($id_curso) {
        $sql = "select * from ge_ciclos "
                . "join ge_cursos on  ge_cursos.id_curso = ge_ciclos.fk_id_curso "
                . "left join ge_grades on ge_grades.id_grade = ge_ciclos.fk_id_grade "
                . "where id_curso = $id_curso "
                . "order by n_ciclo ";
        $query = pdoSis::getInstance()->query($sql);
        $ciclos = $query->fetchAll(PDO::FETCH_ASSOC);
        /**
          $ciclos = sql::get(['ge_ciclos', 'ge_cursos'], '*', ['id_curso' => $id_curso, '>' => 'n_ciclo']);
         * 
         */
        return $ciclos;
    }

    public function listnomenclatura($id_curso) {
        $ciclos = curso::ciclos($id_curso);

        $ciclos = formulario::editDel($ciclos, 'ge_ciclos', 'id_ciclo', ['aba' => 'ciclos', 'id_curso' => $id_curso, 'id_tp_ens' => $_POST['id_tp_ens']]);
        foreach ($ciclos as $k => $v) {
            $ciclos[$k]['aprova_automatico'] = tool::simnao($v['aprova_automatico']);
            $ciclos[$k]['grade'] = formulario::submit('Grades', NULL, ['id_curso' => $v['fk_id_curso'], 'id_ciclo' => $v['id_ciclo'], 'id_tp_ens' => $_POST['id_tp_ens'], 'aba' => 'grade']);
        }
        $form['array'] = $ciclos;
        $form['fields'] = [
            'Ciclo' => 'n_ciclo',
            'Sigla' => 'sg_ciclo',
            'Grade Curricular Padrão' => 'n_grade',
            'Aprovação Automática' => 'aprova_automatico',
            '||1' => 'del',
            '||2' => 'edit',
            '||3' => 'grade'
        ];

        tool::relatSimples($form);
    }

    /**
     * lista tipo de ensino
     */
    public function listTpEnsino() {
        $array = sql::get('ge_tp_ensino', '*', ['>' => 'n_tp_ens']);

        $array = formulario::editDel($array, 'ge_tp_ensino', 'id_tp_ens');
        foreach ($array as $k => $v) {
            $array[$k]['acesso'] = formulario::submit('Cursos', NULL, ['id_tp_ens' => $v['id_tp_ens'], 'aba' => 'cursos']);
        }

        $form['array'] = $array;
        $form['fields'] = [
            'Segmento' => 'n_tp_ens',
            'Sigla' => 'sigla',
            '||2' => 'del',
            "||1" => 'edit',
            '||3' => 'acesso'
        ];

        tool::relatSimples($form);
    }

}
