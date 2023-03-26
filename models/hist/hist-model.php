<?php

class histModel extends MainModel {

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
        if (DB::sqlKeyVerif('deleteInclui')) {
            $sql = "DELETE FROM `hist_esc` WHERE `hist_esc`.`id_he` = " . @$_POST[1]['id_he'];
            $query = $this->db->query($sql);
            log::logSet('Apagou a permissão da escola  ' . @$_POST['n_inst']);
        }

        if (!empty($_POST['fechaAbre'])) {
            $sql = "UPDATE `hist_` SET `fechado` = '" . $_POST['fechado'] . "', fk_id_inst = '" . $_POST['fk_id_inst'] . "' WHERE `hist_`.`id_pessoa` = " . $_POST['id_pessoa'];
            $query = $this->db->query($sql);

            if ($query) {
                tool::alert("Alterado com sucesso");
            }
        }
        if (!empty($_POST['set'])) {

            $dados = $_POST['dados'];

            foreach ($dados['disciplinas'][3] as $k => $v) {
                $escolas = escolas::idInst('%|4|%', 'fk_id_tp_ens');

                if (!is_numeric($v) && ($k != $v) && !empty($v)) {
                    //mudadar nome disciplina nas notas
                    for ($c = 1; $c <= 9; $c++) {
                        $dados['discNota'][$c][$v] = $dados['discNota'][$c][$k];
                        unset($dados['discNota'][$c][$k]);
                    }
                    //mudadar nome disciplina nas notas parciais
                    for ($c = 1; $c <= 4; $c++) {
                        @$dados['parcial'][$c][$v] = $dados['parcial'][$c][$k];
                        unset($dados['parcial'][$c][$k]);
                    }
                    @$dados['falta'][$v] = $dados['falta'][$k];
                } elseif (empty($v)) {
                    unset($dados['falta'][$k]);
                    $dados['disciplinas'][3][$v] = $v;
                    unset($dados['disciplinas'][3][$k]);
                    //mudadar nome disciplina nas notas
                    for ($c = 1; $c <= 9; $c++) {
                        unset($dados['discNota'][$c][$k]);
                    }
                    //mudadar nome disciplina nas notas parciais
                    for ($c = 1; $c <= 4; $c++) {
                        unset($dados['parcial'][$c][$k]);
                    }
                    $dados['disciplinas'][3][$v] = $v;
                    unset($dados['disciplinas'][3][$k]);
                }
            }
            for ($c = 1; $c <= 9; $c++) {

                for ($cd = 1; $cd <= 5; $cd++) {
                    unset($dados['discNota'][$c]['dn' . $cd]);
                }
            }
            for ($c = 1; $c <= 5; $c++) {
                unset($dados['disciplinas'][3]['dn' . $c]);
            }

            unset($dados['disciplinas'][3]['']);
            foreach ($dados['fk_id_inst'] as $k => $v) {
                if ((empty($v) || $v == 0) && !empty($dados['escola'][$k]) && !is_numeric($dados['escola'][$k])) {
                    $dados['fk_id_inst'][$k] = $dados['escola'][$k];
                } elseif (is_numeric($v)) {
                    $dados['escola'][$k] = $escolas[$v];
                }
            }
            @$dadosSerie = serialize($dados);
            $sql = "REPLACE INTO `hist_` (`id_pessoa`, `dados`, `dt_hist`, `fk_id_pessoa`, `fechado`, `token`, `fk_id_inst`) VALUES ("
                    . "'" . $_POST['id_pessoa'] . "', "
                    . "'$dadosSerie', "
                    . "'" . date("Y-m-d") . "', "
                    . "'" . tool::id_pessoa() . "',"
                    . "'" . $_POST['fechado'] . "',"
                    . "'" . substr(md5(uniqid("")), 0, 4) . "', "
                    . "'" . @$_POST['fk_id_inst'] . "'"
                    . ");";
            $query = pdoSis::getInstance()->query($sql);
            $sql = "INSERT INTO `hist_bk` (`id_hist`, `idpessoa_aluno`, `dados`, `dt_hist`, `fk_id_pessoa`, `n_pessoa`, `fk_id_inst`) VALUES ("
                    . " NULL, "
                    . "'" . $_POST['id_pessoa'] . "', "
                    . "'$dadosSerie', "
                    . "'" . date("Y-m-d H:i:s") . "', "
                    . "'" . tool::id_pessoa() . "', "
                    . "'" . user::session('n_pessoa') . "', "
                    . "'" . @$_POST['fk_id_inst'] . "'"
                    . ");";
            $query = pdoSis::getInstance()->query($sql);
            if ($query) {
                $sql = "delete from AlunoHistorico "
                        . "where IdAluno = " . $_POST['id_pessoa'] . " ";
                //$query = pdoConsulta::getInstance()->query($sql);
            }
            log::logSet("Alterou histórico do aluno(a) com RSE " . $_POST['id_pessoa']);
            tool::alert("Atualizado com sucesso");
        }
    }

    // Crie seus próprios métodos daqui em diante
    public function verificasituacaoaluno($idpessoa) {

        $wsql = "SELECT * FROM ge_aluno_nsdp"
                . " WHERE fk_id_pessoa = $idpessoa AND status_nsdp = 0";

        $query = pdoSis::getInstance()->query($wsql);
        $aluno = $query->fetch(PDO::FETCH_ASSOC);

        return $aluno;
    }

    public function verificaalunoapd($pessoa) {
        $wsql = "SELECT * FROM ge_aluno_apd"
                . " WHERE fk_id_pessoa = $pessoa AND status_apd = '" . 'Sim' . "'";

        $query = pdoSis::getInstance()->query($wsql);
        $aluno = $query->fetch(PDO::FETCH_ASSOC);

        return $aluno;
    }

    public function pegaultimoano($idpessoa, $ano = NULL) {

        if (!empty($ano)) {
            $sql = "SELECT ta.fk_id_pessoa, ta.situacao, ta.situacao_final, t.fk_id_ciclo,"
                    . " t.periodo_letivo, t.codigo FROM ge_turma_aluno ta"
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma"
                    . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                    . " WHERE ta.fk_id_pessoa = " . $idpessoa . " "
                    . " AND pl.at_pl != 2 AND t.fk_id_ciclo = '" . $ano . "'"
                    . " ORDER BY t.fk_id_ciclo, ta.id_turma_aluno";
        } else {
            $sql = "SELECT ta.fk_id_pessoa, ta.situacao, ta.situacao_final, t.fk_id_ciclo,"
                    . " t.periodo_letivo, t.codigo FROM ge_turma_aluno ta"
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma"
                    . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                    . " WHERE ta.fk_id_pessoa = " . $idpessoa . " "
                    . " AND pl.at_pl != 2"
                    . " ORDER BY t.fk_id_ciclo, ta.id_turma_aluno";
        }

        $query = pdoSis::getInstance()->query($sql);
        $dados = $query->fetchAll(PDO::FETCH_ASSOC);

        $pandemia = ['2020', '2021'];
        $pandemiaC = [2020 => 820, 2021 => 862];
        $res['Ciclo'] = 0;
        $res['Texto'] = 'none';
        $res['Situacao'] = '-';
        $res['CargaH'] = 0;
        $res[1] = 0;
        $res[2] = 0;
        $res[3] = 0;
        $res[4] = 0;
        $res[5] = 0;
        $res[6] = 0;
        $res[7] = 0;
        $res[8] = 0;
        $res[9] = 0;
        $c = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $infantil = [19, 20, 21, 22, 23, 24,32];
        
        if (!empty($dados)) {
            foreach ($dados as $v) {
                if (in_array($v['fk_id_ciclo'], $c)) {
                    $res['Ciclo'] = $v['fk_id_ciclo'];
                    $res['Situacao'] = ($v['situacao'] == 'Frequente' ? $v['situacao_final'] : $v['situacao']);

                    if (in_array($v['periodo_letivo'], $pandemia)) {
                        if ($v['situacao'] == 'Frequente') {
                            $res['Texto'] = '';
                            $res[$v['fk_id_ciclo']] = $pandemiaC[$v['periodo_letivo']];
                        }
                    } else {
                        $res[$v['fk_id_ciclo']] = 0;
                    }
                } else {
                    //eja
                    if (in_array($v['fk_id_ciclo'], $infantil)) {
                    } else {
                       // $res['Texto'] = 'none';
                    }
                }
            }
        }
        return $res;
    }

}
