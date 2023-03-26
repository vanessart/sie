<?php

class infoModel extends MainModel {

    public $db;
    
    public $_pl_ativos;

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
        
        $this->_pl_ativos = ng_main::periodosAtivos();
    }

// Crie seus próprios métodos daqui em diante

    public function resumoinscricao() {

        $sql = "SELECT i.n_inst, v.id_vaga, v.fk_id_inst, v.status, v.seriacao, v.trabalha FROM vagas v"
                . " JOIN instancia i ON i.id_inst = v.fk_id_inst"
                . " WHERE v.seriacao IN ('Berçário', '1ª Fase - Maternal', '2ª Fase - Maternal', '3ª Fase - Maternal')"
                . " AND (v.status = 'Deferido' OR v.status = 'Edição')"
                . " ORDER BY i.n_inst, v.seriacao, v.status";

        $query = $this->db->query($sql);
        $i = $query->fetchAll();

        if (!empty($i)) {
            foreach ($i as $v) {
                @$inscricao[$v['n_inst']][$v['seriacao']][$v['status']][$v['trabalha']]++;
                @$inscricao[$v['n_inst']][$v['status']]++;
            }
        }
        return $inscricao;
    }

    public function resumoinscricaototal() {

        $sql = "SELECT i.n_inst, v.id_vaga, v.fk_id_inst, v.status, v.seriacao, v.trabalha FROM vagas v"
                . " JOIN instancia i ON i.id_inst = v.fk_id_inst"
                . " WHERE v.seriacao IN ('Berçário', '1ª Fase - Maternal', '2ª Fase - Maternal', '3ª Fase - Maternal')"
                . " AND (v.status = 'Deferido' OR v.status = 'Edição')"
                . " ORDER BY i.n_inst, v.seriacao, v.status";

        $query = $this->db->query($sql);
        $i = $query->fetchAll();

        if (!empty($i)) {
            foreach ($i as $v) {
                @$inscricaot[$v['seriacao']][$v['status']][$v['trabalha']]++;
                @$inscricaot[$v['status']]++;
            }
        }
        return $inscricaot;
    }

    public function escolasLatLng($id_tp_ens = NULL, $id_inst = NULL) {
        if (!empty($id_tp_ens)) {
            $seg = " AND fk_id_tp_ens like '%|$id_tp_ens|%' ";
        } else {
            $seg = NULL;
        }
        if (!empty($id_inst)) {
            $id_inst = " and i.id_inst = $id_inst ";
        }
        $sql = "SELECT "
                . " e.`latitude`, e.`longitude`, e.cie_escola, i.n_inst "
                . " FROM `ge_escolas` e  "
                . " join instancia i on i.id_inst = e.fk_id_inst "
                . " WHERE `latitude` IS NOT NULL "
                . " AND `longitude` IS NOT NULL "
                . $seg
                . $id_inst;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function heat($ciclo = NULL, $id_inst = NULL, $id_pl = NULL) {
        if (empty($id_pl)) {
            $id_pl = $tp = sql::get('ge_periodo_letivo', '*', ['at_pl' => 1]);
            $id_pl = implode(',', array_column($id_pl, 'id_pl'));
        }
        if (!empty($ciclo) || !empty($id_inst)) {
            if (!empty($ciclo)) {
                $ciclo = " and t.fk_id_ciclo in (" . (implode(',', $ciclo)) . ") ";
            }
            if (!empty($id_inst)) {
                $id_inst = " and t.fk_id_inst = $id_inst ";
            }
            $sql = "SELECT "
                    . " e.`longitude`, e.`latitude`, ta.fk_id_pessoa "
                    . " FROM `endereco` e "
                    . " join ge_turma_aluno ta on ta.fk_id_pessoa = e.fk_id_pessoa "
                    . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
                    . " WHERE `latitude` IS NOT NULL "
                    . " AND `longitude` IS NOT NULL "
                    . " AND latitude != '' "
                    . " AND longitude != '' "
                    . " AND longitude like '%.%' "
                    . " AND latitude like '%.%' "
                    . " and t.fk_id_pl in (" . ($id_pl) . ")"
                    . $ciclo
                    . $id_inst;
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);

            return $array;
        } else {
            tool::alert("Escolha uma escola ou um ou mais ciclos");
        }
    }

    public function cursoCiclo($id_tp_ens) {
        $sql = "SELECT "
                . " c.`n_curso` , `n_ciclo`, `id_ciclo` "
                . " FROM `ge_cursos` c  "
                . " join ge_ciclos ci on ci.fk_id_curso = c.id_curso "
                . " WHERE `fk_id_tp_ens` = $id_tp_ens "
                . " order by n_curso, n_ciclo ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $ci[$v['n_curso']][$v['n_ciclo']] = $v['id_ciclo'];
        }

        return $ci;
    }

    public function optEscola($id_tp_ens) {
        $sql = "select n_inst, id_inst from instancia "
                . "join ge_escolas on ge_escolas.fk_id_inst = instancia.id_inst "
                . "where instancia.ativo = 1"
                . " AND fk_id_tp_ens like '%|$id_tp_ens|%' "
                . " order by n_inst";
        $query = pdoSis::getInstance()->query($sql);
        $optEscola = $query->fetchALL(PDO::FETCH_ASSOC);
        $optEscola = tool::idName($optEscola);

        return$optEscola;
    }

    public function pegadisciplinasarea() {
        $sql = "SELECT * FROM ge_disciplinas d"
                . " JOIN ge_areas a ON a.id_area = d.fk_id_area"
                . " WHERE d.status_disc = 1 ORDER BY d.n_disc";

        $query = $this->db->query($sql);
        $dados = $query->fetchAll();

        return $dados;
    }

// Informações E-mail Google de Alunos e Professores - Thomas e David
    public function buscaEmailProf() {
        $sql = "SELECT DISTINCT  pe.n_pe AS professor,pe.fk_id_inst,i.n_inst AS escola ,p.emailgoogle AS email FROM ge_prof_esc pe 
        JOIN ge_aloca_prof ap ON ap.fk_id_inst = pe.fk_id_inst AND ap.rm = pe.rm
        JOIN ge_turmas t ON t.id_turma = ap.fk_id_turma
        JOIN instancia i ON i.id_inst = pe.fk_id_inst
        JOIN ge_funcionario F ON F.rm = pe.rm
        JOIN pessoa p ON p.id_pessoa = F.fk_id_pessoa
        WHERE t.fk_id_pl IN(81,83) AND pe.nao_hac = 0 AND i.ativo = 1 AND i.fk_id_tp = 1";

        try {
            $query = autenticador::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return;
        }

        return $array;
    }

    public function buscaEmailProfEscola($id_inst) {
        $sql = "SELECT DISTINCT  p.id_pessoa,func.rm, pe.n_pe AS professor,pe.fk_id_inst,i.n_inst AS escola ,p.emailgoogle AS email FROM ge_prof_esc pe 
        JOIN ge_aloca_prof ap ON ap.fk_id_inst = pe.fk_id_inst AND ap.rm = pe.rm
        JOIN ge_turmas t ON t.id_turma = ap.fk_id_turma
        JOIN instancia i ON i.id_inst = pe.fk_id_inst
        JOIN ge_funcionario F ON F.rm = pe.rm
        JOIN pessoa p ON p.id_pessoa = F.fk_id_pessoa
        JOIN ge_funcionario func ON func.fk_id_pessoa = p.id_pessoa
        WHERE t.fk_id_pl IN(81,83) AND pe.nao_hac = 0 AND pe.fk_id_inst = $id_inst AND i.ativo = 1 AND i.fk_id_tp = 1 ORDER BY pe.n_pe";

        try {
            $query = autenticador::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return;
        }

        return $array;
    }

    public function cadastrarEmailProf($dados) {
        $ins['id_pessoa'] = $dados['id_pessoa'];
        $ins['emailgoogle'] = $dados['emailgoogle'];

        $this->db->update('pessoa', 'id_pessoa', $ins['id_pessoa'], $ins);
    }

    public function listaEscolas() {
        $sql = "SELECT id_inst, n_inst
        FROM instancia
        WHERE ativo = 1 AND fk_id_tp = 1
        ORDER BY n_inst";

        try {
            $query = autenticador::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return;
        }

        return $array;
    }

    public function listaEscolasFundamental() {

        /*
          $sql = "SELECT id_inst, n_inst
          FROM instancia
          WHERE ativo = 1 AND fk_id_tp = 1 AND n_inst LIKE '%EMEF%' OR n_inst LIKE '%EMEIEF%'
          ORDER BY n_inst";
         */

        $sql = "SELECT DISTINCT i.id_inst, i.n_inst FROM instancia i"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_inst = i.id_inst"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                . " WHERE pl.at_pl = 1 AND t.fk_id_ciclo IN(1,2,3,4,5,6,7,8,9)"
                . " ORDER BY i.n_inst";

        try {
            $query = autenticador::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return;
        }

        return $array;
    }

    public function turmasEscola($id_inst) {
        /*
          $sql = "SELECT i.id_inst, i.n_inst, t.n_turma, ta.codigo_classe
          FROM instancia i
          JOIN ge_turmas t ON t.fk_id_inst = i.id_inst
          JOIN ge_turma_aluno ta ON ta.fk_id_turma = t.id_turma
          JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl
          JOIN ge_ciclos c ON c.id_ciclo = t.fk_id_ciclo
          WHERE i.ativo = 1 AND i.fk_id_tp = 1 AND pl.id_pl IN(84,86) AND i.id_inst = $id_inst AND t.fk_id_ciclo IN (1,2,3,4,5,6,7,8,9,25,26,27,28,29,30,31,34,35)
          GROUP BY t.n_turma, ta.codigo_classe;";

         */
        $sql = "SELECT i.id_inst, i.n_inst, t.n_turma, t.codigo, t.id_turma FROM ge_turmas t"
                . " JOIN instancia i ON i.id_inst = t.fk_id_inst"
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                . " WHERE pl.at_pl = 1 AND i.id_inst = " . $id_inst . " ORDER BY t.n_turma, t.codigo";

        try {
            $query = autenticador::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return;
        }

        return $array;
    }

    public function emailAlunosEscola($id_inst) {
        /*
          $sql = "SELECT DISTINCT p.id_pessoa, p.n_pessoa, ta.codigo_classe, t.n_turma, p.emailgoogle FROM pessoa p
          JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa
          JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma
          JOIN instancia i ON i.id_inst = t.fk_id_inst
          JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl
          JOIN ge_ciclos c ON c.id_ciclo = t.fk_id_ciclo
          WHERE i.id_inst = $id_inst AND ta.situacao = 'Frequente' AND pl.id_pl IN(84,86) AND c.id_ciclo IN (1,2,3,4,5,6,7,8,9,25,26,27,28,29,30,31,34,35)
          ORDER BY t.n_turma";
         */

        try {
            $query = autenticador::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return;
        }

        return $array;
    }

    public function emailAlunosEscolaTotal($id_inst) {
        $sql = "SELECT COUNT(*) as email FROM pessoa p
        JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa
        JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma
        JOIN instancia i ON i.id_inst = t.fk_id_inst
        JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl
        WHERE i.id_inst = $id_inst AND ta.situacao = 'Frequente' AND pl.id_pl IN(". implode(', ', $this->_pl_ativos).") AND p.emailgoogle like '%@%'
        
        UNION 
        
        SELECT COUNT(*) FROM pessoa p
        JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa
        JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma
        JOIN instancia i ON i.id_inst = t.fk_id_inst
        JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl
        WHERE i.id_inst = $id_inst AND ta.situacao = 'Frequente' AND pl.id_pl IN(". implode(', ', $this->_pl_ativos).") AND (p.emailgoogle not like '%@%' or p.emailgoogle is null) ";

        try {
            $query = autenticador::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return;
        }

        return $array;
    }

    public function turmaEscola($id_inst, $codigo_classe) {
        /*
          $sql = "SELECT p.id_pessoa, ta.chamada, p.n_pessoa, p.at_google, p.dt_nasc, p.ra , ta.codigo_classe, t.n_turma, p.emailgoogle, i.n_inst FROM pessoa p
          JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa
          JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma
          JOIN instancia i ON i.id_inst = t.fk_id_inst
          JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl
          WHERE ta.situacao = 'Frequente' AND pl.id_pl IN(84,86) AND i.id_inst = $id_inst AND ta.codigo_classe = '$codigo_classe'
          ORDER BY p.n_pessoa";
         */

        $sql = "SELECT p.id_pessoa, ta.chamada, p.n_pessoa, p.at_google, p.dt_nasc,"
                . " p.ra , ta.codigo_classe, t.n_turma, p.emailgoogle, i.n_inst, t.id_turma FROM pessoa p"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " JOIN instancia i ON i.id_inst = t.fk_id_inst"
                . " WHERE ta.situacao = 'Frequente' AND t.id_turma = '" . $codigo_classe . "' ORDER BY ta.chamada";

        try {
            $query = autenticador::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return;
        }

        return $array;
    }

    public function cadastrarEmailAluno($dados) {
        $ins['id_pessoa'] = $dados['id_pessoa'];
        $ins['emailgoogle'] = $dados['emailgoogle'];
        $ins['at_google'] = $dados['at_google'];

        $this->db->update('pessoa', 'id_pessoa', $ins['id_pessoa'], $ins);

        //Cadastrar na tabela: ge_controle_email
        $id_pessoa = $dados['id_pessoa'];
        $ra = $dados['ra'];
        $emailgoogle = $dados['emailgoogle'];

        //Verifica se e-mail existe na tabela ge_controle_email
        $query = "SELECT * FROM ge_controle_email WHERE fk_id_pessoa = $id_pessoa";
        $aluno = $this->db->query($query);
        $alunoExiste = $aluno->fetchAll();

        if ($alunoExiste) {
            $sql = "UPDATE ge_controle_email SET campo3 = '$emailgoogle', campo17 = '$ra', status = 'Enviar', nome_arquivo = null  WHERE fk_id_pessoa = '$id_pessoa'";
        } else {
            $sql = "INSERT INTO ge_controle_email (fk_id_pessoa, campo3, campo17) VALUES ('$id_pessoa', '$emailgoogle', '$ra')";
        }
        $this->db->query($sql);

        //Formata Nome
        $nomeCompleto = strtoupper($dados['nome']);
        $posEspaco = strpos($nomeCompleto, " ");
        $primeiroNome = substr($nomeCompleto, 0, $posEspaco);
        $restoNome = trim(substr($nomeCompleto, $posEspaco, strlen($nomeCompleto)));

        //Cadastrar e verificar na tabela ge_emailgoogle
        $emailgoogle = $dados['emailgoogle'];
        $query = "SELECT * FROM ge_emailgoogle WHERE primeironome = '$primeiroNome' AND sobrenome = '$restoNome'";
        $email = $this->db->query($query);
        $emailExiste = $email->fetchAll();

        if ($emailExiste) {
            $sql = "UPDATE ge_emailgoogle SET emailgoogle = '$emailgoogle' WHERE primeironome = '$primeiroNome' AND sobrenome = '$restoNome'";
        } else {
            $sql = "INSERT INTO ge_emailgoogle (primeironome, sobrenome, emailgoogle) VALUES ('$primeiroNome', '$restoNome', '$emailgoogle')";
        }
        $this->db->query($sql);
    }

    public function verificaEmail($dados) {

        $emailgoogle = $dados['emailgoogle'];

        $sql = "SELECT id_pessoa FROM pessoa "
                . " WHERE emailgoogle = '$emailgoogle' "
                . " and id_pessoa != " . $dados['id_pessoa'];

        $stmt = $this->db->query($sql);

        $email = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $email;
    }

    public function alunosComEmail() {
        //Não mudei a consulta 

        $sql = "SELECT DISTINCT COUNT(*) AS Total_Alunos
FROM pessoa p
JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa
JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma
JOIN instancia i ON i.id_inst = t.fk_id_inst
JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl
JOIN ge_ciclos c ON c.id_ciclo = t.fk_id_ciclo
WHERE ta.situacao = 'Frequente' AND pl.id_pl IN(". implode(', ', $this->_pl_ativos).") AND c.id_ciclo IN(1, 2, 3, 4, 5, 6, 7, 8, 9, 25, 26, 27, 28, 29, 30, 31, 34, 35)
AND p.emailgoogle like '%@%'

UNION


SELECT DISTINCT COUNT(*) AS Total_Alunos
FROM pessoa p
JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa
JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma
JOIN instancia i ON i.id_inst = t.fk_id_inst
JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl
JOIN ge_ciclos c ON c.id_ciclo = t.fk_id_ciclo
WHERE ta.situacao = 'Frequente' AND pl.id_pl IN(". implode(', ', $this->_pl_ativos).") AND c.id_ciclo IN(1, 2, 3, 4, 5, 6, 7, 8, 9, 25, 26, 27, 28, 29, 30, 31, 34, 35)
AND (p.emailgoogle not like '%@%' or p.emailgoogle is null) ";

        try {
            $query = autenticador::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return;
        }

        return $array;
    }

    public function emailAlunosTurma($id_inst, $codigo_classe) {
        $sql = "SELECT COUNT(*) AS email
FROM pessoa p
JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa
JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma
JOIN instancia i ON i.id_inst = t.fk_id_inst
JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl
WHERE ta.situacao = 'Frequente' AND pl.id_pl IN(". implode(', ', $this->_pl_ativos).") AND i.id_inst = $id_inst AND ta.codigo_classe = '$codigo_classe' AND p.emailgoogle like '%@%'

UNION

SELECT COUNT(*) AS email
FROM pessoa p
JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa
JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma
JOIN instancia i ON i.id_inst = t.fk_id_inst
JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl
WHERE ta.situacao = 'Frequente' AND pl.id_pl IN(". implode(', ', $this->_pl_ativos).") AND i.id_inst = $id_inst AND ta.codigo_classe = '$codigo_classe' "
                . "AND (p.emailgoogle IS NULL or p.emailgoogle not like '%@%' )";

        try {
            $query = autenticador::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return;
        }

        return $array;
    }

//Mario Inicio

    public function geraarquivoemail($arq) {

        $arquivo = $arq . '_' . date("Y_m_d");

        $sql = "UPDATE ge_controle_email SET status = 'Pendente', nome_arquivo = '" . $arquivo . "' WHERE status = 'Enviar'";
        $query = $this->db->query($sql);

        $sql = "SELECT * FROM ge_controle_email ce"
                . " JOIN pessoa p ON p.id_pessoa = ce.fk_id_pessoa"
                . " WHERE status = 'Pendente' AND nome_arquivo = '" . $arquivo . "'";

        $query = $this->db->query($sql);
        $arq = $query->fetchAll();

        if (!empty($arq)) {
            foreach ($arq as $v) {
                $nome = trim($v['n_pessoa']);
                $separanome = explode(" ", $nome);
                $conta = count($separanome);

                $nome1 = strtoupper($separanome[0]);
                for ($i = 1; $i < $conta; $i++) {
                    if ($i == 1) {
                        $nome2 = $separanome[$i];
                    } else {
                        $nome2 = $nome2 . ' ' . $separanome[$i];
                    }
                }
                $nome2 = strtoupper($nome2);

                $wsql = "UPDATE ge_controle_email SET nome_aluno = '" . $nome . "', campo1 = '" . $nome1 . "', "
                        . " campo2 = '" . $nome2 . "' WHERE id_email = '" . $v['id_email'] . "'";
                $query = $this->db->query($wsql);
            }
            $dados = "SELECT campo1 AS 'First Name [Required]', campo2 AS 'Last Name [Required]', "
                    . " campo3 AS 'Email Address [Required]', campo4 AS 'Password [Required]', "
                    . " campo5 AS 'Password Hash Function [UPLOAD ONLY]', campo6 AS 'Org Unit Path [Required]', "
                    . " campo7 AS 'New Primary Email [UPLOAD ONLY]', campo8 AS 'Recovery Email', "
                    . " campo9 AS 'Home Secondary Email', campo10 AS 'Work Secondary Email', "
                    . " campo11 AS 'Recovery Phone [MUST BE IN THE E.164 FORMAT]', "
                    . " campo12 AS 'Work Phone', campo13 AS 'Home Phone', campo14 AS 'Mobile Phone', "
                    . " campo15 AS 'Work Address', campo16 AS 'Home Address', campo17 AS 'Employee ID', "
                    . " campo18 AS 'Employee Type', campo19 AS 'Employee Title', campo20 AS 'Manager Email', "
                    . " campo21 AS 'Department', campo22 AS 'Cost Center', campo23 AS 'Cost Center', "
                    . " campo24 AS 'Floor Name', campo25 AS 'Floor Section', "
                    . " campo26 AS 'Change Password at Next Sign-In', campo27 AS 'New Status [UPLOAD ONLY]', "
                    . " campo28 AS 'Advanced Protection Program enrollment' FROM ge_controle_email"
                    . " WHERE status = '" . 'Pendente' . "' AND nome_arquivo = '" . $arquivo . "'";

            return $dados;
        } else {
            tool::alert("Não existe alunos para exportação");
        }
    }

    public function peganomearquivo() {

        $sql = "SELECT DISTINCT nome_arquivo FROM ge_controle_email WHERE status = 'Pendente' ORDER BY id_email DESC";
        $query = $this->db->query($sql);
        $nome = $query->fetchAll();

        if (!empty($nome)) {
            foreach ($nome as $v) {
                $lista[$v['nome_arquivo']] = $v['nome_arquivo'];
            }
        } else {
            $lista['Não há arquivo pendente'] = 'Não há arquivo pendente';
        }

        return $lista;
    }

    public function gravaarquivogoogle($arq) {

        $sql = "UPDATE ge_controle_email"
                . " SET status = '" . 'Enviado' . "' WHERE nome_arquivo = '" . $arq . "'";
        $query = $this->db->query($sql);

        tool::alert("Operação Efetuada com Sucesso");
    }

    public function pesquisaemail($c) {

        $sql = "SELECT p.id_pessoa, p.n_pessoa, p.dt_nasc, p.ra, p.emailgoogle, "
                . " i.n_inst, t.n_turma, t.codigo, t.fk_id_pl FROM pessoa p"
                . " LEFT JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa"
                . " LEFT JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " LEFT JOIN instancia i ON i.id_inst = ta.fk_id_inst " . $c
                . " ORDER BY t.fk_id_ciclo DESC";

        $query = $this->db->query($sql);
        $d = $query->fetchAll();

        return $d;
    }

//Mario Fim
}
