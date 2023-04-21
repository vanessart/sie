<?php

class gestModel extends MainModel {

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

        if (!empty($_POST['selecao3'])) {

            if (!empty($_POST['id_turma'])) {
                $t = $_POST['id_turma'];
                if (!empty($_POST['as'])) {
                    foreach ($_POST['as'] as $v) {
                        $cad['id_encam'] = $v;
                        $cad['status'] = 2;
                        $this->db->ireplace('ge_encaminhamento', $cad, 1);

                        $grav = $this->gravaencaminhamento($t, $v, $_POST['escolaorigem']);
                    }
                }
            }
        }

        if (!empty($_POST['selecao4'])) {
            if (!empty($_POST['as2'])) {
                foreach ($_POST['as2'] as $v) {

                    $dado = explode('|', $v);

                    $this->db->delete('ge_turma_aluno', 'id_turma_aluno', $dado[0]);
                    log::logSet(" Exclui aluno " . $v);

                    $sql = "UPDATE ge_encaminhamento SET status = 1 WHERE fk_id_pessoa = '" . $dado[1] . "'";
                    $query = $this->db->query($sql);
                }
            }
        }
    }

// Crie seus próprios métodos daqui em diante

    public function pegaescola($tipo) {

        $sql = "SELECT DISTINCT i.id_inst, i.n_inst FROM instancia i"
                . " JOIN ge_turmas t ON t.fk_id_inst = i.id_inst"
                . " WHERE t.fk_id_ciclo = '" . $tipo . "' ORDER BY i.n_inst";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        return $array;
    }

    public function pegaaluno($idturma) {

        $sql = "SELECT p.id_pessoa, p.n_pessoa, p.ra, e.bairro FROM pessoa p"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " LEFT JOIN endereco e ON e.fk_id_pessoa = p.id_pessoa"
                . " WHERE t.id_turma = '" . $idturma . "' AND ta.situacao = '" . 'Frequente' . "'  ORDER BY p.n_pessoa";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

// status da tabela ge_encaminhamento 0 = encaminhar - 1 = encaminhado - 2 Matriculado
        $sql = "SELECT fk_id_pessoa FROM ge_encaminhamento WHERE fk_id_turma = '" . $idturma . "' AND status = '" . '1' . "'";
        $query = $this->db->query($sql);
        $array2 = $query->fetchAll();

        foreach ($array2 as $v) {
            $ae[] = $v['fk_id_pessoa'];
        }

        foreach ($array as $key => $w) {
            if (!empty($ae)) {
                if (in_array($w['id_pessoa'], $ae)) {
                    unset($array[$key]);
                }
            }
        }
        return $array;
    }

    public function listaencaminhamento($id_esc) {

        $sql = "SELECT DISTINCT p.id_pessoa, p.n_pessoa, p.ra, p.dt_nasc, p.tel1, e.ciclo_futuro AS n_ciclo, i.n_inst FROM pessoa p"
                . " JOIN ge_encaminhamento e ON e.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_turma = e.fk_id_turma AND ta.fk_id_pessoa = e.fk_id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " JOIN instancia i ON i.id_inst = e.escola_origem"
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                . " WHERE e.escola_destino = '" . $id_esc . "'"
                . " AND i.id_inst = '" . tool::id_inst() . "'"
                . " AND e.status = 1"
                . " AND pl.at_pl = 1 ORDER BY p.n_pessoa";


        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        return $array;
    }

    public function nomeescola($id_inst) {

        $e = sql::get('instancia', 'n_inst', ['id_inst' => $id_inst], 'fetch');

        return $e;
    }

    public function anofuturo($idturma) {

        $sql = "SELECT c.n_ciclo FROM ge_turmas t"
                . " JOIN ge_ciclos c ON c.id_ciclo = t.fk_id_ciclo"
                . " WHERE t.id_turma = '" . $idturma . "'";

        $query = $this->db->query($sql);
        $ci = $query->fetch();

        $ano = [
            'Berçário' => '1ª Fase Maternal do Ensino Infantil',
            'Maternal Fase 1' => '2ª Fase Maternal do Ensino Infantil',
            'Maternal Fase 2' => '3ª Fase Maternal do Ensino Infantil',
            'Maternal Fase 3' => '1ª Fase Pré do Ensino Infantil',
            'Pré Fase 1' => '2ª Fase Pré do Ensino Infantil',
            'Pré Fase 2' => '1º Ano do Ensino Fundamental',
            '1º Ano' => '2º Ano do Ensino Fundamental',
            '2º Ano' => '3º Ano do Ensino Fundamental',
            '3º Ano' => '4º Ano do Ensino Fundamental',
            '4º Ano' => '5º Ano do Ensino Fundamental',
            '5º Ano' => '6º Ano do Ensino Fundamental',
            '6º Ano' => '7º Ano do Ensino Fundamental',
            '7º Ano' => '8º Ano do Ensino Fundamental',
            '8º Ano' => '9º Ano do Ensino Fundamental',
            '9º Ano' => '1º Ano do Ensino Médio'
        ];

        if (!empty($ci['n_ciclo'])) {
            return $ano[$ci['n_ciclo']];
        } else {
            return $ci['n_ciclo'];
        }
    }

    public function encaminhamento($idesc, $turma) {

        $sql = "SELECT DISTINCT p.id_pessoa, p.n_pessoa, p.dt_nasc, p.sexo, p.ra, p.ra_dig, p.rg, i.n_inst, e.ciclo_futuro, e.escola_destino FROM pessoa p"
                . " JOIN ge_encaminhamento e ON e.fk_id_pessoa = p.id_pessoa"
                . " JOIN instancia i ON i.id_inst = e.escola_destino"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_turma = e.fk_id_turma AND ta.fk_id_pessoa = e.fk_id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                . " WHERE e.escola_destino = '" . $idesc . "' AND e.escola_origem = '" . tool::id_inst() . "'"
                . " AND pl.at_pl = 1 ORDER BY p.n_pessoa";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        return $array;
    }

    public function encaminhamentocarta($alu) {

        $idaluno = [];
        foreach ($alu as $v) {
            if (!empty($v)) {
                $idaluno[] = $v;
            }
        }

        if (empty($idaluno)) {
            return [];
        }

        $idaluno = implode(",", $idaluno);

        $sql = "SELECT p.id_pessoa, p.n_pessoa, p.dt_nasc, p.sexo, p.ra,p.ra_dig, p.rg, i.n_inst, e.ciclo_futuro, e.escola_destino FROM pessoa p"
                . " JOIN ge_encaminhamento e ON e.fk_id_pessoa = p.id_pessoa"
                . " JOIN instancia i ON i.id_inst = e.escola_destino"
                . " WHERE e.id_encam IN(" . $idaluno . ") ORDER BY p.n_pessoa";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        return $array;
    }

    public function pegaenderecoescola($idinst) {

        $sql = "SELECT p.id_predio, p.logradouro, p.num, p.bairro FROM instancia_predio ip"
                . " JOIN predio p ON p.id_predio = ip.fk_id_predio"
                . " WHERE ip.fk_id_inst = '" . $idinst . "'";

        $query = $this->db->query($sql);
        $ed = $query->fetch();

        $endereco['end'] = $ed['logradouro'] . ' nº ' . $ed['num'] . ' - ' . $ed['bairro'];
        $sql2 = "SELECT num FROM telefones where fkid ='" . $idinst ."'";
      //  $sql2 = "SELECT num FROM telefones WHERE fkid = '" . $ed['id_predio'] . "' AND fk_id_tp = 3";
        $query = $this->db->query($sql2);
        $tel = $query->fetchAll();

        $nt = "";

        foreach ($tel as $v) {
            $nt = $nt . ' - ' . $v['num'];
        }

        $endereco['tel'] = $nt;

        return $endereco;
    }

    public function descricaoano($idturma) {

        $sql = "SELECT c.n_ciclo FROM ge_turmas t"
                . " JOIN ge_ciclos c ON c.id_ciclo = t.fk_id_ciclo"
                . " WHERE t.id_turma = '" . $idturma . "'";

        $query = $this->db->query($sql);
        $ci = $query->fetch();

        $ano = [
            'Berçário' => 'Berçario',
            'Maternal Fase 1' => '1ª Fase Maternal do Ensino Infantil',
            'Maternal Fase 2' => '2ª Fase Maternal do Ensino Infantil',
            'Maternal Fase 3' => '3ª Fase Maternal do Ensino Infantil',
            'Pré Fase 1' => '1ª Fase Pré do Ensino Infantil',
            'Pré Fase 2' => '2ª Fase Pré do Ensino Infantil',
            '1º Ano' => '1º Ano do Ensino Fundamental',
            '2º Ano' => '2º Ano do Ensino Fundamental',
            '3º Ano' => '3º Ano do Ensino Fundamental',
            '4º Ano' => '4º Ano do Ensino Fundamental',
            '5º Ano' => '5º Ano do Ensino Fundamental',
            '6º Ano' => '6º Ano do Ensino Fundamental',
            '7º Ano' => '7º Ano do Ensino Fundamental',
            '8º Ano' => '8º Ano do Ensino Fundamental',
            '9º Ano' => '9º Ano do Ensino Fundamental'
        ];

        if (!empty($ci['n_ciclo'])) {
            return $ano[$ci['n_ciclo']];
        } else {
            return $ci['n_ciclo'];
        }
    }

    public function matriculaencam($idturma, $sit) {

        $sql = "UPDATE ge_turma_aluno ta"
                . " JOIN ge_encaminhamento e ON e.fk_id_pessoa = ta.fk_id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " SET e.status = 2"
                . " WHERE t.fk_id_pl = 84 AND ta.situacao = 'Frequente' AND e.escola_destino = '" . tool::id_inst() . "'";

        $query = $this->db->query($sql);

        $sel = $this->descricaoano($idturma);

        $sql = "SELECT p.id_pessoa, p.n_pessoa, p.ra, p.dt_nasc, e.id_encam, e.escola_origem FROM pessoa p"
                . " JOIN ge_encaminhamento e ON e.fk_id_pessoa = p.id_pessoa"
                . " WHERE e.escola_destino = '" . tool::id_inst() . "' AND e.ciclo_futuro = '" . $sel . "'"
                . " AND e.status = '" . $sit . "' ORDER BY p.n_pessoa";
        $query = $this->db->query($sql);
        $res = $query->fetchAll();

        return $res;
    }

    public function classeselecionada($turma) {

        $sql = "SELECT p.id_pessoa, p.n_pessoa, p.ra, ta.chamada, ta.id_turma_aluno, ta.situacao FROM pessoa p"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa"
                . " WHERE ta.fk_id_turma = '" . $turma . "'";

        $query = $this->db->query($sql);
        $res = $query->fetchAll();

        return $res;
    }

    public function gravaencaminhamento($idturma, $idencam, $origem) {

        $p = sql::get('ge_encaminhamento', 'fk_id_pessoa', ['id_encam' => $idencam], 'fetch');

        $sql = "SELECT * FROM ge_turmas t"
                . " WHERE t.id_turma = '" . $idturma . "'";

        $query = $this->db->query($sql);
        $dados = $query->fetch();

//Matricular
        $ch = $this->ultimo_turmaNovaChamada($idturma);

        $date = date('Y-m-d');

        $wsql = "INSERT INTO ge_turma_aluno (codigo_classe, fk_id_turma, periodo_letivo, fk_id_pessoa, fk_id_inst,"
                . " chamada, situacao, dt_matricula, origem_escola, turma_status) VALUES ('" . $dados['codigo'] . "', '" . $idturma . "',"
                . " '" . $dados['periodo_letivo'] . "', '" . $p['fk_id_pessoa'] . "', '" . $dados['fk_id_inst'] . "', '" . $ch['chamada'] . "',"
                . " '" . 'Frequente' . "', '" . $date . "', '" . $origem . "', '" . 'M' . "')";

        $query = $this->db->query($wsql);
        log::logSet("Grava encaminhamento " . $idencam);
    }

    public function ultimo_turmaNovaChamada($idturma) {

        $dados = "Select *  from ge_turma_aluno Where fk_id_turma = " . $idturma . " order by chamada desc limit 0,1";
        $query = $this->db->query($dados);
        $a = $query->fetch();

        if (!empty($a)) {
            $a['chamada'] += 1;
        } else {
            $dados = "Select *  from ge_turmas Where id_turma = " . $idturma;
            $query = $this->db->query($dados);
            $b = $query->fetch();
            $a['codigo_classe'] = $b['codigo'];
            $a['periodo'] = $b['periodo'];
            $a['periodo_letivo'] = $b['periodo_letivo'];
            $a['chamada'] = 1;
        }
        return $a;
    }

    public function wpegaalunoencaminhamento($iddestino) {

        $sql = "SELECT p.id_pessoa, p.n_pessoa, en.id_encam, en.status, ta.fk_id_turma FROM pessoa p"
                . " JOIN ge_encaminhamento en ON en.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_turma = en.fk_id_turma AND ta.fk_id_pessoa = en.fk_id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma "
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl "
                . " WHERE en.escola_origem = '" . tool::id_inst() . "' AND en.escola_destino = '" . $iddestino . "' AND en.status = 1"
                . " AND pl.at_pl = 1";

        $query = $this->db->query($sql);
        $a = $query->fetchAll();

        return $a;
    }

    public function wpegaalunocarta() {

        $sql = "SELECT p.id_pessoa, p.n_pessoa, p.ra, p.ra_dig, en.id_encam,"
                . " en.status, ta.fk_id_turma FROM pessoa p"
                . " JOIN ge_encaminhamento en ON en.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_turma = en.fk_id_turma AND ta.fk_id_pessoa = en.fk_id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma "
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                . " WHERE en.escola_origem = '" . tool::id_inst() . "' AND en.status = 1"
                . " AND pl.at_pl = 1";

        $query = $this->db->query($sql);
        $a = $query->fetchAll();

        return $a;
    }

    public function listaencaminhamentodestino() {

        $sql = "SELECT p.id_pessoa, p.n_pessoa, p.ra, p.ra_dig, p.dt_nasc, p.tel1, e.ciclo_futuro AS n_ciclo, i.n_inst, i.id_inst FROM pessoa p"
                . " JOIN ge_encaminhamento e ON e.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_turma = e.fk_id_turma AND ta.fk_id_pessoa = e.fk_id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " JOIN instancia i ON i.id_inst = e.escola_origem"
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                . " WHERE e.escola_destino = '" . tool::id_inst() . "' AND e.status = 1"
                . " AND pl.at_pl = 1";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        foreach ($array as $v) {
            @$dadosaluno[$v['n_inst']][] = $v;
        }
        return @$dadosaluno;
    }

   public function pegaescolaterceiropre() {
       // Aproveitando função para todos os ciclos
        /*
          $sql = "SELECT DISTINCT i.id_inst, i.n_inst FROM instancia i"
          . " JOIN ge_turmas t ON t.fk_id_inst = i.id_inst"
          . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
          . " WHERE t.fk_id_ciclo IN (19, 20, 21, 22, 23, 24) AND pl.at_pl = 1  ORDER BY i.n_inst";

         */
        $sql = "SELECT DISTINCT i.id_inst, i.n_inst FROM instancia i"
          . " JOIN ge_turmas t ON t.fk_id_inst = i.id_inst"
          . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
          . " WHERE pl.at_pl = 1  ORDER BY i.n_inst";
        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        return $array;
    }

}
