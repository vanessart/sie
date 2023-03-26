<?php

class dtgpModel extends MainModel {

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

        if (!empty($_POST['esc_vaga'])) {
            foreach ($_POST as $v) {
                if (is_array($v)) {
                    $this->db->ireplace('dtpg_cadamde_esc_vaga', $v, 1);
                }
            }
        }


        if (!empty($_POST['atrib'])) {
            echo '&nbsp;';
            if (!empty($_POST['id'])) {
                if (empty($_POST['m']) && empty($_POST['n']) && empty($_POST['t'])) {
                    tool::alert("Selecione ao menos um período");
                } else {
                    $insert['fk_id_cad'] = @$_POST['id_cad'];
                    $insert['fk_id_cargo'] = @$_POST['id_cargo'];
                    $insert['m'] = @$_POST['m'];
                    $insert['t'] = @$_POST['t'];
                    $insert['n'] = @$_POST['n'];
                    foreach ($_POST['id'] as $k => $v) {
                        $insert['fk_id_inst'] = $k;
                        $this->db->ireplace('dtgp_cadampe_esc', $insert, 1);
                    }
                }
            }
        }
        if (!empty($_POST['atribDel'])) {
            if (!empty($_POST['id'])) {
                foreach ($_POST['id'] as $k => $v) {
                    $insert['fk_id_inst'] = $k;
                    $this->db->delete('dtgp_cadampe_esc', 'id_ce', $k);
                }
            }
        }

        if (DB::sqlKeyVerif('cadCadampe')) {

            if (empty($_POST[1]['id_cad'])) {
                $testeCPF = sql::get('dtgp_cadampe', 'cpf', ['cpf' => @$_POST[1]['cpf']], 'fetch')['cpf'];
            }
            if (empty($testeCPF)) {
                $insert = $_POST[1];
                $insert['dt_nasc'] = data::converteUS($insert['dt_nasc']);
                $insert['dt_cad'] = data::converteUS($insert['dt_cad']);
                if (!empty($_POST['cargos_e'])) {
                    $insert['cargos_e'] = '|';
                    foreach ($_POST['cargos_e'] as $v) {
                        $insert['cargos_e'] .= $v . '|';
                    }
                }
                if (!empty($_POST['dia'])) {
                    $insert['dia'] = '|';
                    foreach ($_POST['dia'] as $k => $v) {
                        if (!empty($v)) {
                            $insert['dia'] .= $k . '|';
                        }
                    }
                }

                if (!empty($_POST['doc'])) {
                    $insert['doc'] = '|';
                    foreach ($_POST['doc'] as $k => $v) {
                        if (!empty($v)) {
                            $insert['doc'] .= $k . '|';
                        }
                    }
                }


                $id_cad_ = $this->db->ireplace('dtgp_cadampe', $insert);

                log::logSet('Cadastrou/Alterou Cadampe Protocolo: ' . $id_cad_);
            } else {
                tool::alert("CPF já cadastrado");
            }
        }
    }

    /**
     * 
     * @param type $id_inst  escola
     * 2º param incluir outra função na listagem. (like '%%')
     */
    public function relatProf($id_inst, $rm) {
        if (empty($rm)) {
            $form['array'] = funcionarios::professores($id_inst);
        } else {
            $form['array'] = funcionarios::Get(['funcao' => 'profess%', 'rm' => intval($rm)]);
            if (empty($form['array'])) {
                echo 'Funcionário Não Encontrado.';
            }
        }
        $form['fields'] = [
            'Matrícula' => 'rm',
            'Nome' => 'n_pessoa',
            'Função' => 'funcao',
            'Situação' => 'situacao'
        ];

        tool::relatSimples($form);
    }

    // Crie seus próprios métodos daqui em diante
}
