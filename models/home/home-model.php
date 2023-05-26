<?php

class homeModel extends MainModel {

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



        if (!empty($_REQUEST['logout1'])) {

            // Remove all data from $_SESSION['userdata']
            $_SESSION['userdata'] = array();

            // Only to make sure (it isn't really needed)
            unset($_SESSION['userdata']);

            // Regenerates the session ID
            echo '<br>session_regenerate_id 1<br>';
            @session_regenerate_id();

            // Send the user to the login page
            if (defined('HOME_URI')) {
                // Configura a URL de login
                $login_uri = HOME_URI . '/home/';

                // A página em que o usuário estava
                $_SESSION['goto_url'] = urlencode($_SERVER['REQUEST_URI']);
            }
        }
        if (DB::sqlKeyVerif('recupera')) {
            $this->recuperaSenha();
        }

        // if (DB::sqlKeyVerif('mudaInst')) {
        if (!empty($_POST['mudaInst'])) {
            $sql = "UPDATE `acesso_pessoa` SET fk_id_inst = " . $_POST ['fk_id_inst'] . " WHERE  fk_id_pessoa = " . $_POST ['id_pessoa'] . " AND fk_id_gr  in( 17,26)";
            $query = $this->db->query($sql);
        }

        unset($_SESSION['sqlKey']);
    }

    public function recuperaSenha() {
        $user = filter_input(INPUT_POST, 'user', FILTER_UNSAFE_RAW);
        $sql = "SELECT n_pessoa, id_pessoa, emailgoogle as email, id_user, user_session_id FROM `pessoa` "
                . "JOIN users ON users.fk_id_pessoa = pessoa.id_pessoa "
                . "WHERE "
                . "((emailgoogle LIKE '" . trim($user) . "' AND emailgoogle NOT LIKE '' )  "
                . "OR (cpf = '" . str_pad($user, 11, "0", STR_PAD_LEFT) . "' AND cpf NOT LIKE ''  ))"
                . "AND users.ativo = 1 ";
        $query = autenticador::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($array['id_pessoa'])) {
            if (!empty($array['email'])) {
                $emailExpl = explode('@', $array['email']);
                $email = substr($emailExpl[0], 0, 3);
                foreach (range(1, (strlen($emailExpl[0]) - 4)) as $v) {
                    $email .= '*';
                }
                $email .= substr($emailExpl[0], -1);
                $email .= '@' . $emailExpl[1];

                mailer::recuperaEmail($array['n_pessoa'], $array['email'], $array['id_user'], $array['user_session_id']);
                toolErp::alert("O link para redefinição de senha foi enviada para o email:" . $email);
            } else {
                ?>
                <script>
                    alert("Lamento, você não tem e-mail cadastrado");
                </script>
                <?php

            }
        } else {
            echo "Dados errados ou Usuário Desativado";
            ?>
            <script>
                alert("Dados errados ou Usuário Desativado");
            </script>
            <?php

        }
    }

    public function listSistemas($search = NULL, $fieldWhere = 'fk_id_pessoa') {
        if (is_array($search)) {
            foreach ($search as $k => $v) {
                @$where .= "and $k like '$v' ";
            }
        } else {
            if (empty($search)) {
                $search = $this->userdata['id_pessoa'];
            }
            $where = "WHERE `$fieldWhere` = '$search' ";
        }
        $field = "n_inst, n_nivel, n_sistema, id_inst, id_nivel, id_sistema, arquivo, id_ac, end_fr, id_fr, fkid, n_fr , id_gr, fk_id_tp";
        $sql = "SELECT $field FROM `acesso_pessoa` "
                . "join instancia on instancia.id_inst = acesso_pessoa.fk_id_inst "
                . "join grupo on grupo.id_gr = acesso_pessoa.fk_id_gr "
                . "join acesso_gr on acesso_gr.fk_id_gr = grupo.id_gr "
                . "join nivel on nivel.id_nivel = acesso_gr.fk_id_nivel "
                . "join sistema on sistema.id_sistema = acesso_gr.fk_id_sistema "
                . "join framework on framework.id_fr = sistema.fk_id_fr "
                . "$where "
                . " and sistema.ativo = 1 "
                . " order by n_sistema, n_nivel, id_inst";
        $query = autenticador::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $cont = 1;
        foreach ($array as $v) {
            //setar suporte -  trocar instancia
            if ($v['id_gr'] == 17 || $v['id_gr'] == 26) {
                $list['suporte'] = $v['id_inst'];
            }

            $list[$v['id_sistema']][$cont]['n_fr'] = $v['n_fr'];
            $list[$v['id_sistema']][$cont]['fkid'] = $v['fkid'];
            $list[$v['id_sistema']][$cont]['id_fr'] = $v['id_fr'];
            $list[$v['id_sistema']][$cont]['end_fr'] = $v['end_fr'];
            $list[$v['id_sistema']][$cont]['n_sistema'] = $v['n_sistema'];
            $list[$v['id_sistema']][$cont]['arquivo'] = $v['arquivo'];
            $list[$v['id_sistema']][$cont]['n_nivel'] = $v['n_nivel'];
            $list[$v['id_sistema']][$cont]['n_inst'] = $v['n_inst'];
            $list[$v['id_sistema']][$cont]['id_nivel'] = $v['id_nivel'];
            $list[$v['id_sistema']][$cont]['id_inst'] = $v['id_inst'];
            $list[$v['id_sistema']][$cont]['tipo'] = $v['fk_id_tp'];
            $cont++;
        }

        return $list;
    }

    public function verifRecupera($info) {
        $dados = explode('-', $info);
        $sql = "SELECT id_pessoa, users.ativo FROM `pessoa` "
                . "JOIN users ON users.fk_id_pessoa = pessoa.id_pessoa "
                . "WHERE user_session_id = '" . $dados[1] . "'";
        $query = autenticador::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if (empty($array["id_pessoa"])) {
            if (empty($_POST['dfdfdfd'])) {
                tool::alert("Esta requisição de senha expirou");
            }
            tool::sair();
        } elseif ($array['ativo'] != 1) {
            tool::alert("Usuário desativado");
            tool::sair();
        } else {
            return @openssl_encrypt($dados[0], 'aes128', '150936');
        }
    }

    public function key() {
        $uniqid = uniqid(date("yms"));
        $tempo = date("YmdHis");
        // $sql = "INSERT INTO `logingoogle` (`key`, `tempo`) VALUES ('$uniqid', '$tempo');";
        //  $query = pdoSis::getInstance()->query($sql);
        return $uniqid;
    }

    public function emails() {
        $e = sql::get('user_email_valido', 'n_uev', ['ativo' => 1]);
        $e = array_column($e, 'n_uev');

        return $e;
    }

}
