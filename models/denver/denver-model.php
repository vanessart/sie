<?php

class denverModel extends MainModel {

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
    }

    public function pegaclasseprof($idpessoa) {

        $sql = "SELECT i.n_inst, t.codigo, t.id_turma FROM ge_aloca_prof ap"
                . " JOIN ge_funcionario f ON f.rm = ap.rm"
                . " JOIN pessoa p ON p.id_pessoa = f.fk_id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ap.fk_id_turma"
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                . " JOIN instancia i ON i.id_inst = t.fk_id_inst"
                . " WHERE p.id_pessoa = '" . $idpessoa . "' AND ap.iddisc = 27 AND pl.at_pl = 1"
                . " ORDER BY t.codigo";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        return $array;
    }

    public function geraclassehab($turma) {

        $sql = "SELECT p.n_pessoa, p.dt_nasc, ta.chamada, ta.situacao, p.id_pessoa, ta.fk_id_turma FROM pessoa p"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa"
                . " WHERE ta.fk_id_turma = '" . $turma . "'";

        $query = $this->db->query($sql);
        $dados = $query->fetchAll();

        if (!empty($dados)) {
            foreach ($dados as $k => $v) {
                if ($v['situacao'] == 'Frequente') {
                    $dados[$k]['registrar'] = formulario::submit('Registrar', null, $v, HOME_URI . '/denver/registrar');
                } else {
                    $dados[$k]['registrar'] = '<button type="button">Registrar</button>';
                }
            }
            $form['array'] = $dados;
            $form['fields'] = [
                'Chamada' => 'chamada',
                'RSE' => 'id_pessoa',
                'Nome Aluno' => 'n_pessoa',
                'Data Nasc.' => 'dt_nasc',
                'Situação' => 'situacao',
                '||1' => 'registrar'
            ];

            tool::relatSimples($form);
        }
    }

    public function pegaalunohab($idpessoa, $idturma) {

        $sql = "SELECT ta.fk_id_pessoa, p.n_pessoa, e.descricao_campo,"
                . " ha.descricao_habilidade FROM dv_aluno_acomp aa"
                . " JOIN dv_acompanhamento a ON aa.fk_id_acomp = a.id_acomp"
                . " JOIN dv_campo_experiencia e ON a.fk_id_campo_exp = e.id_campo_exp"
                . " JOIN dv_habilidade ha ON a.fk_id_habilidade = ha.id_habilidade"
                . " JOIN ge_turmas t ON a.fk_id_ciclo = t.fk_id_ciclo"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_turma = t.id_turma"
                . " JOIN pessoa p ON aa.fk_id_pessoa = p.id_pessoa AND ta.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_periodo_letivo pl ON t.fk_id_pl = pl.id_pl"
                . " WHERE t.id_turma = '" . $idturma . "' AND ta.situacao = '" . 'Frequente' . "'"
                . " AND pl.at_pl = 1 AND p.id_pessoa = '" . $idpessoa . "'"
                . " ORDER BY e.sequencia, a.sequencia";

        $query = $this->db->query($sql);
        $aluno = $query->fetchAll();

        if (empty($aluno)) {

            $wsql = "INSERT INTO dv_aluno_acomp (fk_id_pessoa, fk_id_acomp, fk_id_ciclo, fk_id_pl)"
                    . " SELECT ta.fk_id_pessoa, a.id_acomp,t.fk_id_ciclo,t.fk_id_pl FROM dv_acompanhamento a"
                    . " JOIN dv_campo_experiencia e ON a.fk_id_campo_exp = e.id_campo_exp"
                    . " JOIN dv_habilidade ha ON a.fk_id_habilidade = ha.id_habilidade"
                    . " JOIN ge_turmas t ON a.fk_id_ciclo = t.fk_id_ciclo"
                    . " JOIN ge_turma_aluno ta ON ta.fk_id_turma = t.id_turma"
                    . " JOIN ge_periodo_letivo pl ON t.fk_id_pl = pl.id_pl"
                    . " WHERE ta.fk_id_pessoa = '" . $idpessoa . "' AND ta.fk_id_turma = '" . $idturma . "'"
                    . " AND ta.situacao = 'Frequente' AND pl.at_pl = 1";

            $query = $this->db->query($wsql);
            $sql = "SELECT ta.fk_id_pessoa, p.n_pessoa, e.descricao_campo,"
                    . " ha.descricao_habilidade FROM dv_aluno_acomp aa"
                    . " JOIN dv_acompanhamento a ON aa.fk_id_acomp = a.id_acomp"
                    . " JOIN dv_campo_experiencia e ON a.fk_id_campo_exp = e.id_campo_exp"
                    . " JOIN dv_habilidade ha ON a.fk_id_habilidade = ha.id_habilidade"
                    . " JOIN ge_turmas t ON a.fk_id_ciclo = t.fk_id_ciclo"
                    . " JOIN ge_turma_aluno ta ON ta.fk_id_turma = t.id_turma"
                    . " JOIN pessoa p ON aa.fk_id_pessoa = p.id_pessoa AND ta.fk_id_pessoa = p.id_pessoa"
                    . " JOIN ge_periodo_letivo pl ON t.fk_id_pl = pl.id_pl"
                    . " WHERE t.id_turma = '" . $idturma . "' AND ta.situacao = '" . 'Frequente' . "'"
                    . " AND pl.at_pl = 1 AND p.id_pessoa = '" . $idpessoa . "'"
                    . " ORDER BY e.sequencia, a.sequencia";

            $query = $this->db->query($sql);
            $aluno = $query->fetchAll();
        }

        return $aluno;
    }

    public function pegadadosaluno($idpessoa, $idturma) {
        $sql = "SELECT p.id_pessoa, p.ra, p.ra_dig, p.ra_uf,"
                . " p.n_pessoa, p.dt_nasc, t.fk_id_ciclo, t.letra, t.professor FROM pessoa p"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " WHERE p.id_pessoa = '" . $idpessoa . "' AND t.id_turma = '" . $idturma . "'";
        $query = $this->db->query($sql);
        $a = $query->fetch();
        
        return $a;
    }

}
