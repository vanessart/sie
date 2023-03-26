<?php

class passelivreModel extends MainModel {

    public $db;
    public $cie;
    public $escola;
 
    
    /**
      if($this->db->tokenCheck('table')){

      }
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
        if ($this->db->tokenCheck('userCad')) {
            $this->userCad();
        } elseif ($this->db->tokenCheck('userCadDel')) {
            $this->userCadDel();
        } elseif ($this->db->tokenCheck('docUp')) {
            $this->docUp();
        }
        $this->cieSet();

        if (!empty($_POST['gravacheck'])) {
            $this->gravachecklist();
        }

        if (!empty($_POST['gravafalta'])) {
            $this->gravafaltas();
        }

        if (!empty($_POST['gravalote'])) {
            $this->gravalotes();
        }
    }

    public function docUp() {
        $id_passelivre = filter_input(INPUT_POST, 'id_passelivre', FILTER_SANITIZE_NUMBER_INT);
        $ins = @$_POST[1];
        $file = ABSPATH . '/pub/passelivre/';
        $prefix = $id_passelivre;
        $up = new upload($file, $prefix);
        $end = $up->up();
        $ins['end'] = $end;

        $id = $this->db->insert('pl_doc', $ins);
    }

    public function requerSED() {
        $ra = filter_input(INPUT_POST, 'ra', FILTER_SANITIZE_STRING);
        $uf = filter_input(INPUT_POST, 'uf', FILTER_SANITIZE_STRING);
        $cie = filter_input(INPUT_POST, 'cie', FILTER_SANITIZE_NUMBER_INT);
        if ($ra && $uf) {
            $pl = sql::get('pl_passelivre', 'cie, id_passelivre', ['ra' => $ra, 'ra_uf' => $uf], 'fetch');
            if ($pl && $cie != $pl['cie']) {
                echo 'Este requerente já solicitou o Passe Livre na escola com o CIE ' . $pl['cie'];
                return;
            } elseif ($pl) {
                echo 'Aluno já Cadastrado';
                return;
            }
            sleep(3);
            $sql = " SELECT "
                    . " p.n_pessoa, p.dt_nasc, p.ra, p.ra_uf, p.sexo, e.* "
                    . " FROM pessoa p "
                    . " LEFT JOIN endereco e on e.fk_id_pessoa = p.id_pessoa "
                    . " WHERE p.ra = $ra "
                    . " AND p.ra_uf LIKE '$uf'";
            $query = pdoSis::getInstance()->query($sql);
            $dados = $query->fetch(PDO::FETCH_ASSOC);
            if ($dados['ra']) {
                $ins['ra'] = $dados['ra'];
                $ins['ra_uf'] = $dados['ra_uf'];
                $ins['nome'] = $dados['n_pessoa'];
                $ins['dt_nasc'] = $dados['dt_nasc'];
                $ins['sexo'] = $dados['sexo'] == 'F' ? 'FEMININO' : 'MASCULINO';
                $ins['logradouro'] = $dados['logradouro_gdae'];
                $ins['num'] = $dados['num_gdae'];
                $ins['bairro'] = $dados['bairro'];
                $ins['complemento'] = $dados['complemento'];
                $ins['cep'] = $dados['cep'];
                $ins['latitude'] = $dados['latitude'];
                $ins['longitude'] = $dados['longitude'];
                $ins['cie'] = $cie;
                $id = $this->db->ireplace('pl_passelivre', $ins, 1);
            }

            if (empty($id)) {
                session_write_close();
                $dados = rest::exibirFichaAluno($ra, $uf);

                if (!empty($dados['outErro'])) {
                    echo 'Não foi possível baixar as informações. Por favor, continue manualmente.<br />' . $dados['outErro'];
                    return;
                } elseif (empty($dados['outDadosPessoais']['outNumRA'])) {
                    echo 'Não foi possível baixar as informações. Por favor, continue manualmente (SED - CURL)';
                    return;
                }
                $ins['ra'] = $dados['outDadosPessoais']['outNumRA'];
                $ins['ra_uf'] = $dados['outDadosPessoais']['outSiglaUFRA'];
                $ins['nome'] = $dados['outDadosPessoais']['outNomeAluno'];
                $ins['dt_nasc'] = $dados['outDadosPessoais']['outDataNascimento'];
                $ins['sexo'] = $dados['outDadosPessoais']['outSexo'];
                $ins['logradouro'] = $dados['outEnderecoResidencial']['outLogradouro'];
                $ins['num'] = $dados['outEnderecoResidencial']['outNumero'];
                $ins['bairro'] = $dados['outEnderecoResidencial']['outBairro'];
                $ins['complemento'] = $dados['outEnderecoResidencial']['outComplemento'];
                $ins['cep'] = $dados['outEnderecoResidencial']['outCep'];
                $ins['latitude'] = $dados['outEnderecoResidencial']['outLatitude'];
                $ins['longitude'] = $dados['outEnderecoResidencial']['outLongitude'];
                $ins['cie'] = $cie;
                $id = $this->db->ireplace('pl_passelivre', $ins, 1);
            }

            if (!empty($id)) {
                ob_start();
                echo $id;
            } else {
                echo 'Não foi possível baixar as informações. Por favor, continue manualmente (SIEB)';
            }
        }
    }

    public function userCadDel() {
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $sql = "DELETE FROM `acesso_pessoa` WHERE fk_id_pessoa = $id_pessoa AND fk_id_gr = 60";
        $query = pdoSis::getInstance()->query($sql);
        $sql = "DELETE FROM `pl_acesso` WHERE `pl_acesso`.`fk_id_pessoa` = $id_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
    }

    public function userCad() {
        $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_NUMBER_INT);
        if ($senha) {
            $senha = 'Br' . substr(uniqid(), -6);
        }
        $ins = $_POST[1];
        $fk_id_ee = filter_input(INPUT_POST, 'fk_id_ee', FILTER_SANITIZE_NUMBER_INT);
        $id = $this->db->ireplace('pessoa', $ins, 1);
        $ac['fk_id_pessoa'] = $id;
        $ac['fk_id_ee'] = $fk_id_ee;
        $ac['senha'] = $senha;
        @$ac['id_acesso'] = sql::get('pl_acesso', 'id_acesso', ['fk_id_pessoa' => $id], 'fetch')['id_acesso'];
        $this->db->ireplace('pl_acesso', $ac, 1);
        @$id_ac = sql::get('acesso_pessoa', 'id_ac', ['fk_id_pessoa' => $id], 'fetch')['id_ac'];
        $a['fk_id_pessoa'] = $id;
        $a['fk_id_gr'] = 60;
        $a['fk_id_inst'] = 13;
        $a['id_ac'] = $id_ac;
        $this->db->ireplace('acesso_pessoa', $a);
        if ($senha) {
            @$id_user = sql::get('users', 'id_user', ['fk_id_pessoa' => $id], 'fetch')['id_user'];
            $u['id_user'] = $id_user;
            $u['fk_id_pessoa'] = $id;
            $u['user_password'] = $senha;
            $this->db->ireplace('users', $u, 1);
        }
    }

    public function cieSet($id_pessoa = null) {
        if (empty($id_pessoa)) {
            $id_pessoa = toolErp::id_pessoa();
        }
        if (toolErp::id_nilvel() == 8) {
            if (toolErp::id_inst() != 13) {
                $esc = sql::get(['ge_escolas', 'instancia'], 'cie_escola, n_inst', ['fk_id_inst' => toolErp::id_inst()], 'fetch');
                $this->cie = $esc['cie_escola'];
                $this->escola = $esc['n_inst'];
            } else {
                $esc = sql::get(['pl_acesso', 'pl_escola_externa'], 'cie, n_ee', ['fk_id_pessoa' => $id_pessoa], 'fetch');
                $this->cie = $esc['cie'];
                $this->escola = $esc['n_ee'];
            }
        }
    }

    public function escolasGeral() {
        $sql = "SELECT cie_escola id_esc, i.n_inst n_esc FROM ge_escolas e "
                . " JOIN instancia i on i.id_inst = e.fk_id_inst "
                . " where i.ativo = 1 "
                . " UNION "
                . " SELECT cie id_esc, concat('Externa: ', n_ee) n_esc FROM pl_escola_externa "
                . " order by n_esc ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return toolErp::idName($array);
    }

    public function requerentes($cie = null, $origem = null) {
        if (empty($cie)) {
            $sql = "SELECT pl.*, s.n_status FROM pl_passelivre pl "
                    . " JOIN pl_status s on s.id_pl_status = pl.fk_id_pl_status "
                    . "WHERE s.id_pl_status = 3";
        } else {
            if (empty($origem)) {
                $sql = "SELECT pl.*, s.n_status FROM pl_passelivre pl "
                        . " JOIN pl_status s on s.id_pl_status = pl.fk_id_pl_status "
                        . " WHERE `cie` LIKE '$cie'";
            } else {
                $sql = "SELECT pl.*, s.n_status FROM pl_passelivre pl "
                        . " JOIN pl_status s on s.id_pl_status = pl.fk_id_pl_status "
                        . " WHERE `cie` LIKE '$cie'  AND s.id_pl_status = 3";
            }
        }
        
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function plEscolas($cie) {
        $sql = " SELECT maps, cie_escola cie, `latitude`, `longitude` FROM ge_escolas "
                . " WHERE cie_escola = $cie "
                . " UNION "
                . " SELECT maps, cie, `latitude`, `longitude` FROM pl_escola_externa "
                . " WHERE cie = $cie";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        return $array;
    }

    public function tpcadastro() {
        $sql = "SELECT * FROM pl_tipo_cadastro ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $k => $v) {
            $c[$v['id_tipo_cadastro']] = $v['n_descricao'];
        }
        return $c;
    }

###################### Consulta #############################

    public function buscapasselivre($b) {

        $sql = "SELECT nome, ra, cpf, lote, dt_inicio_passe, fk_id_pl_status, fk_tipo_cadastro FROM pl_passelivre " . $b;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return ($array);
    }

    public function filtroaluno($b) {

        $sql = "SELECT id_passelivre, nome FROM pl_passelivre where cie = " . $b;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($array)) {

            foreach ($array as $k => $v) {
                $c[$v['id_passelivre']] = $v['nome'];
            }
            return $c;
        } else {
            tool::alert('Unidade Escolar sem Registro de Aluno Inscrito');
        }
    }

    public function cadastrackeck($id) {
        // Verifica Dados
        $sql = "SELECT * FROM pl_passelivre WHERE id_passelivre = $id";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($array)) {

            $i = $this->calculaidade($array['dt_nasc']);
            $idade = ($i < 12 ? 0 : 1);
            $distancia = ($array['distancia_escola'] < 2 ? 0 : 1);

            $wsql = "INSERT INTO pl_checklist(fk_id_passelivre,idade_c,distancia_c) VALUES($id,$idade,$distancia)";
            $query = $this->db->query($wsql);
        }
    }

    public function calculaidade($dn) {

        $dn1 = new DateTime(Date('Y-m-d'));
        $dn2 = new DateTime($dn);

        $def = $dn1->diff($dn2);
        $res = $def->y;

        return $res;
    }

    public function pegastatus() {

        $sql = "SELECT * FROM pl_status ORDER BY ordem";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return toolErp::idName($array);
    }

    public function gravachecklist() {

        $idpasse = filter_input(INPUT_POST, 'id_passelivre', FILTER_SANITIZE_NUMBER_INT);
        $st = filter_input(INPUT_POST, 'fk_id_pl_status', FILTER_SANITIZE_NUMBER_INT);
        $d = date('Y-m-d');

        $sql = "UPDATE pl_passelivre SET fk_id_pl_status = $st, dt_requerimento = '" . $d . "'"
                . " WHERE id_passelivre = $idpasse";

        $query = $this->db->query($sql);
    }

    public function pegaescolas() {

        $sql = " SELECT cie_escola AS cie, n_inst AS n_ee FROM ge_escolas "
                . " JOIN instancia ON id_inst = fk_id_Inst "
                . " UNION "
                . " SELECT cie, n_ee FROM pl_escola_externa ";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $escola[$v['cie']] = $v['n_ee'];
        }

        return $escola;
    }

    public function geraarquivoexcel($crit, $tabela) {

        if ($tabela == "R") {
            $sql = "SELECT pl.cie, i.n_inst AS Escola, pl.ra, pl.nome, pl.dt_nasc, pl.cpf,"
                    . " pl.rg, pl.reg_mun AS Registro_Municipal, pl.acompanhante, pl.renda_familiar,"
                    . " pl.logradouro, pl.num, pl.complemento, pl.bairro, pl.municipio,"
                    . " pl.tel1  FROM pl_passelivre pl"
                    . " JOIN ge_escolas e ON e.cie_escola = pl.cie"
                    . " JOIN instancia i ON i.id_inst = e.fk_id_inst "
                    . $crit;
        } else {
            $sql = "SELECT e.cie, e.n_ee AS Escola, pl.ra, pl.nome, pl.dt_nasc, pl.cpf,"
                    . " pl.rg, pl.reg_mun AS Registro_Municipal, pl.acompanhante, pl.renda_familiar,"
                    . " pl.logradouro, pl.num, pl.complemento, pl.bairro, pl.municipio,"
                    . " pl.tel1  FROM pl_passelivre pl"
                    . " JOIN pl_escola_externa e ON e.cie = pl.cie "
                    . $crit;
        }
        return $sql;
    }

    public function pegames() {

        $qtde = date("n");
        $a = data::mes(date("n"));
        for ($i = 1; $i <= $qtde; $i++) {
            $sel[$i] = data::mes(date("n") - ($qtde - $i));
        }
        return $sel;
    }

    public function povoatabela($mes, $cie) {

        $dt = date("Y") . '-' . $mes . '-1';
        $u = date("Y-m-t", strtotime($dt));

        $sql = "INSERT INTO pl_frequencia(fk_id_passelivre, mes)"
                . " SELECT pl.id_passelivre, $mes FROM pl_passelivre pl"
                . " LEFT JOIN pl_frequencia pf ON pf.fk_id_passelivre = pl.id_passelivre"
                . " WHERE pl.dt_inicio_passe <= '" . $u . "'"
                . " AND (pl.dt_fim_passe >= '" . $u . "' OR pl.dt_fim_passe IS NULL)"
                . " AND pf.mes IS NULL AND pl.cie = $cie";

        $query = $this->db->query($sql);
    }

    public function geralancamento($mes, $cie) {

        $sql = "SELECT * FROM pl_passelivre pl"
                . " JOIN pl_frequencia pf ON pf.fk_id_passelivre = pl.id_passelivre"
                . " WHERE pf.mes = $mes AND pl.cie = $cie ORDER BY nome";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function pegaresumo() {
        //cria as variáveis
        $sql = "SELECT * FROM pl_status";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $sql2 = "SELECT DISTINCT cie FROM pl_passelivre pl";
        $query = pdoSis::getInstance()->query($sql2);
        $e = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($e as $v) {
            foreach ($array as $vv) {
                $d[$v['cie']][$vv['id_pl_status']] = 0;
            }
        }

        $wsql = "SELECT cie, fk_id_pl_status, COUNT(cie) AS Total FROM pl_passelivre pl"
                . " GROUP BY cie, fk_id_pl_status";
        $query = pdoSis::getInstance()->query($wsql);
        $dados = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dados as $v) {
            $d[$v['cie']][$v['fk_id_pl_status']] = $v['Total'];
        }

        return $d;
    }

    public function pegaescolasativo() {

        $sql = "SELECT DISTINCT cie FROM pl_passelivre pl";

        $query = pdoSis::getInstance()->query($sql);
        $e = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($e as $v) {
            $escola[$v['cie']] = $v['cie'];
        }

        return $escola;
    }

    public function pegaresumogeral() {
        //Cria as variaveis
        $wsql = "SELECT * FROM pl_status";

        $query = pdoSis::getInstance()->query($wsql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $r[$v['id_pl_status']] = 0;
        }

        $sql = "SELECT fk_id_pl_status, COUNT(fk_id_pl_status) AS Total FROM pl_passelivre"
                . " GROUP BY fk_id_pl_status";

        $query = pdoSis::getInstance()->query($sql);
        $dados = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dados as $w) {
            $r[$w['fk_id_pl_status']] = $w['Total'];
        }

        return $r;
    }

    public function pegaacompanhante() {

        $sql = "SELECT COUNT(fk_id_pl_status) AS Total FROM pl_passelivre"
                . " GROUP BY fk_id_pl_status, acompanhante"
                . " HAVING fk_id_pl_status = 7 AND acompanhante = 'Sim'";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        if ($array) {
            return $array;
        } else {
            $s = ['Total' => 0];
            return $s;
        }
    }

    public function gravafaltas() {
        foreach ($_POST as $k => $v) {
            if (is_numeric($k)) {
                $this->db->ireplace('pl_frequencia', $v, 1);
            }
        }
        tool::alert('Operação Efetuada com Sucesso');
    }

    public function gravalotes() {
        
        foreach ($_POST as $k => $v) {
            if (is_numeric($k)) {
                if (!empty($v['lote'])){
                    $this->db->ireplace('pl_passelivre', $v, 1);
                }
            }
        }
        tool::alert('Operação Efetuada com Sucesso');
    }

    public function escolasRede() {

        $sql = "SELECT cie_escola AS cie, n_inst AS n_ee FROM ge_escolas "
                . " JOIN instancia ON id_inst = fk_id_Inst";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $escola[$v['cie']] = $v['cie'];
        }

        return $escola;
    }

    Public function pegacota() {
        $sql = "SELECT qtde_passe FROM pl_parametro";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

}
