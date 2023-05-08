<?php

class adminModel extends MainModel {

    public $db;
    public $_id_pessoa;

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
        if ($this->db->tokenCheck('pessoaSet')) {
            $this->pessoaSet();
        } elseif ($this->db->tokenCheck('acessoSet')) {
            $this->acessoSet();
        }
    }

    public function acessoSet() {
        $id_gr = filter_input(INPUT_POST, 'id_gr', FILTER_SANITIZE_NUMBER_INT);
        $sql = "DELETE FROM `acesso_gr` WHERE `fk_id_gr` = " . $id_gr;
        $query = $this->db->query($sql);
        foreach ($_POST as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $kk => $vv) {
                    if ($vv == 1) {
                        $sql = "INSERT INTO `acesso_gr` (`id_ag`, `fk_id_gr`, `fk_id_sistema`, `fk_id_nivel`) VALUES ("
                                . "NULL, "
                                . "'" . $_POST['id_gr'] . "', "
                                . "'$k', "
                                . "'$kk');";
                        $query = pdoSis::getInstance()->query($sql);
                    }
                }
            }
        }
        toolErp::alert("Concluir");
    }

    public function pessoaSet() {
        $dados = @$_POST[1];
        $this->_id_pessoa = $this->db->ireplace('pessoa', $dados);
    }

    public function pesqPessoas($pesq, $func = null, $user = null) {
        if ($user) {
            $user = " join users u on u.fk_id_pessoa = p.id_pessoa ";
        } else {
            $user = null;
        }
        if ($pesq) {
            if (!$func) {
                $left = 'left ';
            } else {
                $left = null;
            }
            $sql = "SELECT "
                   . " p.id_pessoa, p.n_pessoa, p.cpf, p.emailgoogle, GROUP_CONCAT(DISTINCT f.rm) rm "
                    . " FROM pessoa p "
                    . " $left JOIN ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
                    . $user
                    . " WHERE "
                    . " p.id_pessoa = '$pesq' "
                    . " OR p.n_pessoa LIKE '%$pesq%' "
                    . " OR p.cpf = '$pesq' "
                    . " OR p.emailgoogle LIKE '$pesq' "
                    . " OR f.rm LIKE '$pesq' "
                    . " group by p.id_pessoa "
                    . " order by p.n_pessoa "
                    . " limit 0, 100 ";
            $query = pdoSis::getInstance()->query($sql);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function pessoa($id_pessoa) {
        $fields = " id_pessoa, n_pessoa, n_social, dt_nasc, email, cpf, sexo, ra, ra_dig, ra_uf, rg, rg_oe, rg_uf, dt_rg, "
                . " certidao, sus, nacionalidade, uf_nasc, cidade_nasc, cor_pele, obs, "
                . " novacert_cartorio, novacert_acervo, novacert_regcivil, novacert_ano, "
                . " novacert_tipolivro, novacert_numlivro, novacert_folha, novacert_termo, novacert_controle, "
                . " nis, emailgoogle, google_user_id, inep ";
        $sql = "SELECT "
                . $fields
                . " FROM pessoa "
                . " WHERE id_pessoa = $id_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $pess = $query->fetch(PDO::FETCH_ASSOC);

        return $pess;
    }

    public function userGr($id_gr) {
        $sql = "SELECT a.id_ac, p.id_pessoa, p.n_pessoa, p.cpf, i.n_inst FROM acesso_pessoa a "
                . " JOIN pessoa p on p.id_pessoa = a.fk_id_pessoa "
                . " and a.fk_id_gr = $id_gr "
                . " join instancia i on i.id_inst = a.fk_id_inst "
                . " order by n_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $user = $query->fetchAll(PDO::FETCH_ASSOC);

        return $user;
    }

    public function sisUser($id_sistema) {
        $sql = "SELECT "
                . " p.n_pessoa, i.n_inst, p.id_pessoa, p.cpf, p.emailgoogle, "
                . " GROUP_CONCAT(DISTINCT gr.n_gr) n_gr "
                . " FROM acesso_pessoa a "
                . " JOIN acesso_gr g on g.fk_id_gr = a.fk_id_gr "
                . " JOIN grupo gr on gr.id_gr = a.fk_id_gr "
                . " JOIN pessoa p on p.id_pessoa = a.fk_id_pessoa "
                . " JOIN instancia i on i.id_inst = a.fk_id_inst "
                . " WHERE g.fk_id_sistema = $id_sistema "
                . " group by p.id_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

}
