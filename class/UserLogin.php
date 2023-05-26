<?php

/**
 * UserLogin - Manipula os dados de usuários
 *
 * Manipula os dados de usuários, faz login e logout, verifica permissões e 
 * redireciona página para usuários logados.
 *
 * @package TutsupMVC
 * @since 0.1
 */
class UserLogin {

    /**
     * Usuário logado ou não
     *
     * Verdadeiro se ele estiver logado.
     *
     * @public
     * @access public
     * @var bol
     */
    public $logged_in;

    /**
     * Dados do usuário
     *
     * @public
     * @access public
     * @var array
     */
    public $userdata;

    /**
     * Mensagem de erro para o formulário de login
     *
     * @public
     * @access public
     * @var string
     */
    public $login_error;

    /**
     * Verifica o login
     *
     * Configura as propriedades $logged_in e $login_error. Também
     * configura o array do usuário em $userdata
     */
    public function check_userlogin() {

        if (@$_POST['table'] == 'googleLogin') {

            $key = filter_input(INPUT_POST, 'key', FILTER_UNSAFE_RAW);
            $keyJWT = filter_input(INPUT_POST, 'credential', FILTER_UNSAFE_RAW);
            $keyJWT = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $keyJWT)[1]))));
            $userEmail = $keyJWT->email;
            $google_id = $keyJWT->sub;

            @$_SESSION['userdata']['foto'] = $keyJWT->picture;
            if (!empty($userEmail)) {
                $fields = "p.`id_pessoa`, u.google_id ";
                $sql = "SELECT * FROM `pessoa` p "
                        . " JOIN users u ON u.fk_id_pessoa = p.id_pessoa "
                        . " WHERE "
                        . " p.emailgoogle = '$userEmail' "
                        . " AND u.ativo = 1 ";
                $query = pdoSis::getInstance()->query($sql);
                $emailGoogle = $query->fetch(PDO::FETCH_ASSOC);

                if (empty($emailGoogle['id_pessoa'])) {
                    $_SESSION['msgLogin'] = "O E-mail <br />$userEmail<br /> não está cadastrado em nosso sistema.<br /> Por favor contacte o Dep. de Informática"; 
                    header('Location: '.HOME_URI);
                    return;
                } else {
                    if (empty($emailGoogle['google_id'])) {
                        $sql = "UPDATE `users` SET "
                                . " `google_id` = '" . $google_id . "', "
                                . " google_token = '$key'"
                                . " WHERE `fk_id_pessoa` = " . $emailGoogle['id_pessoa'];
                        $query = pdoSis::getInstance()->query($sql);
                        $idx = $emailGoogle['id_pessoa'];
                        #echo 'login';
                    } elseif ($emailGoogle['google_id'] == $google_id) {
                        $sql = "UPDATE `users` SET "
                                . " google_token = '$key'"
                                . " WHERE `fk_id_pessoa` = " . $emailGoogle['id_pessoa'];
                        $query = pdoSis::getInstance()->query($sql);
                        $idx = $emailGoogle['id_pessoa'];
                        #echo 'login';
                    } else {
                        $_SESSION['msgLogin'] = 'Algo deu errado.<br /> Por favor contacte o Dep. de Informática';
                        header('Location: '.HOME_URI);
                        return;
                    }
                }
                $idx = $emailGoogle['id_pessoa'];
                #echo 'login';
            } else {
                $_SESSION['msgLogin'] = "O E-mail não foi informado.<br /> Por favor contacte o Dep. de Informática";
                header('Location: '.HOME_URI);
                return;
            }

            header('Location: '.HOME_URI);
        }

        if (empty($idx)) {
            $idx = @$_POST['idx'];
        }
        if (isset($idx)) {
            if (is_numeric($idx)) {

                $sql = "select user_session_id, id_pessoa, n_pessoa, n_social, emailgoogle as email, id_user, cpf "
                        . " from pessoa "
                        . "join users on users.fk_id_pessoa = pessoa.id_pessoa "
                        . "where id_pessoa = " . $idx;
                        echo '<br>'.$sql.'<br>';
                $query = autenticador::getInstance()->query($sql);
                $user_tmp = $query->fetch(PDO::FETCH_ASSOC);

                if (($user_tmp['user_session_id'] == substr(@$_POST['token'], 4)) || !empty($emailGoogle)) {
                    $sql = "update users set user_session_id = '" . session_id() . "' where fk_id_pessoa = " . $idx;
                    $query = autenticador::getInstance()->query($sql);
                    if ($query) {
                        $_SESSION['userdata']['id_pessoa'] = $user_tmp['id_pessoa'];
                        $_SESSION['userdata']['n_pessoa'] = $user_tmp['n_pessoa'];
                        $_SESSION['userdata']['n_social'] = $user_tmp['n_social'];
                        $_SESSION['userdata']['email'] = $user_tmp['email'];
                        $_SESSION['userdata']['cpf'] = $user_tmp['cpf'];
                        $_SESSION['userdata']['id_user'] = $user_tmp['id_user'];
                        $_SESSION['userdata']['user_password'] = '********';
                        $_SESSION['userdata']['user_session_id'] = session_id();
                        $_SESSION['userdata']['user'] = !empty($user_tmp['cpf']) ? $user_tmp['cpf'] : $user_tmp['email'];
                    }
                }
            } else {
                $this->logout();
            }
        }

        if (@$_REQUEST['logout']) {
            $this->logout();
        }

        // Verifica se existe uma sessão com a chave userdata
        // Tem que ser um array e não pode ser HTTP POST
        if (isset($_SESSION['userdata']) && !empty($_SESSION['userdata']) && is_array($_SESSION['userdata']) && !isset($_POST['userdata'])
        ) {
            // Configura os dados do usuário
            $userdata = $_SESSION['userdata'];

            // Garante que não é HTTP POST
            $userdata['post'] = false;
        }

        // Verifica se existe um $_POST com a chave userdata
        // Tem que ser um array
        if (isset($_POST['userdata']) && !empty($_POST['userdata']) && is_array($_POST['userdata'])
        ) {
            // Configura os dados do usuário
            $userdata = $_POST['userdata'];

            // Garante que é HTTP POST
            $userdata['post'] = true;
        }

        // Verifica se existe algum dado de usuário para conferir
        if (!isset($userdata) || !is_array($userdata)) {

            // Desconfigura qualquer sessão que possa existir sobre o usuário
            $this->logout();

            return;
        }


        // Passa os dados do post para uma variável
        if ($userdata['post'] === true) {
            $post = true;
        } else {
            $post = false;
        }

        // Remove a chave post do array userdata
        unset($userdata['post']);

        // Verifica se existe algo a conferir
        if (empty($userdata)) {
            $this->logged_in = false;
            $this->login_error = null;

            // Desconfigura qualquer sessão que possa existir sobre o usuário
            $this->logout();

            return;
        }

        // Extrai variáveis dos dados do usuário
        extract($userdata);

        // Verifica se existe um usuário e senha
        if (!isset($user) || !isset($user_password)) {
            $this->logged_in = false;
            $this->login_error = null;

            // Desconfigura qualquer sessão que possa existir sobre o usuário
            $this->logout();

            return;
        }

        // Verifica se o usuário existe na base de dados
        //deu um erro ao mostrar a data 
        $fields = "`id_pessoa`, `n_pessoa`, `n_social`, `email`, `cpf`, `id_user`, `user_password`, users.`user_session_id`, `expira`, `horas`, emailgoogle, pessoa.google_user_id ";
        $sql = "SELECT $fields FROM `pessoa` "
                . "JOIN users ON users.fk_id_pessoa = pessoa.id_pessoa "
                . "WHERE "
                . "(((email LIKE '$user' AND email NOT LIKE '' OR emailgoogle LIKE '$user' AND emailgoogle NOT LIKE '' ) )  "
                . "OR (cpf = '$user' AND cpf NOT LIKE ''  ))"
                . "AND users.ativo = 1 ";
        $query = autenticador::getInstance()->query($sql);
        echo '<br>'.$sql.'<br>';

        // Verifica a consulta
        if (!$query) {
            $this->logged_in = false;
            $this->login_error = 'Internal error.';

            // Desconfigura qualquer sessão que possa existir sobre o usuário
            $this->logout();

            return;
        }

        // Obtém os dados da base de usuário
        $fetch = $query->fetch(PDO::FETCH_ASSOC);
        if ($fetch) {
            // Obtém o ID do usuário
            $id_user = (int) $fetch['id_user'];

            if (!empty($fetch['expira'])) {
                $expira = str_replace('-', '', $fetch['expira']);
            } else {
                $expira = $fetch['expira'];
            }

            if ($expira != 0 && $expira > date("Ymd")) {
                tool::alert("Seu Acesso expirou");
            }
        }
        // Verifica se o ID existe
        if (empty($id_user)) {
            $this->logged_in = false;
            $this->login_error = 'Dados Incorretos';

            // Desconfigura qualquer sessão que possa existir sobre o usuário
            $this->logout();

            return;
        }


        // Confere se a senha enviada pelo usuário bate com o hash do BD
        if ($this->phpass->CheckPassword($user_password, $fetch['user_password']) || isset($_SESSION['userdata']['user_session_id'])) {

            // Se for uma sessão, verifica se a sessão bate com a sessão do BD
            if (session_id() != $fetch['user_session_id'] && !$post) {
                $this->logged_in = false;
                $this->login_error = 'Erro ao indentificar a Sessão';

                // Desconfigura qualquer sessão que possa existir sobre o usuário
                $this->logout();

                return;
            }

            // Se for um post
            if ($post) {
                // Recria o ID da sessão
                @session_regenerate_id();
                @$session_id = session_id();

                // Envia os dados de usuário para a sessão
                $_SESSION['userdata'] = $fetch;
                @$_SESSION['userdata']['screenWidth'] = @$_POST['screenWidth'];

                // Atualiza a senha
                $_SESSION['userdata']['user_password'] = '********'; //$user_password;
                // Atualiza o ID da sessão
                $_SESSION['userdata']['user_session_id'] = $session_id;

                //seta user para verificação em cada página acessada
                $_SESSION['userdata']['user'] = $user;

                // Atualiza o ID da sessão na base de dados
                $query = autenticador::getInstance()->query(
                        "UPDATE users SET user_session_id = '$session_id' WHERE id_user = '$id_user'"
                );
                log::logSet('Logou no sistema');
            }
            //configurar acesso a páginas e cabeçalho
            if (!empty($_POST['userdatasis'])) {
                extract($_POST['userdatasis']);

                $sql = "SELECT * FROM `acesso_gr` "
                        . "join acesso_pessoa on acesso_pessoa.fk_id_gr = acesso_gr.fk_id_gr "
                        . "WHERE `fk_id_pessoa` = " . $fetch['id_pessoa']
                        . " AND `fk_id_sistema` = " . $id_sistema
                        . " AND `fk_id_nivel` = " . $id_nivel
                        . " AND `fk_id_inst` = " . $id_inst;
                $query = autenticador::getInstance()->query($sql);
                if ($query) {
                    if (AUT == 1) {
                        $sql = "select id_nivel from nivel where fk_id_nivel = $id_nivel";
                        $query = pdoSis::getInstance()->query($sql);
                        $id_nivel_ = $query->fetch(PDO::FETCH_ASSOC)['id_nivel'];
                        if (!empty($id_nivel_)) {
                            $id_nivel = $id_nivel_;
                        }
                    }

                    $_SESSION['userdata']['arquivo'] = @$arquivo;
                    $_SESSION['userdata']['id_sistema'] = $id_sistema;
                    $_SESSION['userdata']['id_nivel'] = $id_nivel;
                    $_SESSION['userdata']['id_inst'] = $id_inst;
                    $_SESSION['userdata']['n_sistema'] = $n_sistema;
                    $_SESSION['userdata']['n_nivel'] = $n_nivel;
                    $_SESSION['userdata']['n_inst'] = $n_inst;
                    $_SESSION['userdata']['tipo'] = $tipo;
                    if (file_exists(ABSPATH . '/module/' . @$arquivo . '/menu.php')) {
                        $file = ABSPATH . '/module/' . @$arquivo . '/menu.php';
                        include $file;

                        if (isset($menu[$id_nivel])) {
                            foreach ($menu[$id_nivel] as $k => $v) {
                                if (empty($v['page'])) {
                                    if (!empty($v['url'])) {
                                        $pg[] = ['pagina' => substr($v['url'], 1), 'n_pag' => $k];
                                    }
                                } else {
                                    foreach ($v['page'] as $kk => $vv) {
                                        if (!empty($vv['url'])) {
                                            $pg[] = ['pagina' => substr($vv['url'], 1), 'n_pag' => $kk];
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $sql = "SELECT pagina, n_pag FROM pagina "
                                . "join `sis_nivel_pag` on sis_nivel_pag.fk_id_pag = pagina.id_pag "
                                . "WHERE sis_nivel_pag.`fk_id_sistema` = '$id_sistema' "
                                . "AND sis_nivel_pag.`fk_id_nivel` = '$id_nivel' "
                                . "order by ord_pag ";
                        $query = pdoSis::getInstance()->query($sql);
                        if ($query) {
                            $pg = $query->fetchAll(PDO::FETCH_ASSOC);
                        }
                    }

                    if (!empty($pg)) {
                        foreach ($pg as $v) {
                            $pA[$v['n_pag']] = $v['pagina'];
                        }
                        // Obtém um array com as permissões de usuário
                        @$_SESSION['userdata']['user_permissions'] = $pA;
                    }
                }
                log::logSet('Entrou no subsistema');
            }

            // Configura a propriedade dizendo que o usuário está logado
            $this->logged_in = true;

            // Configura os dados do usuário para $this->userdata
            $this->userdata = $_SESSION['userdata'];

            // Verifica se existe uma URL para redirecionar o usuário
            if (isset($_SESSION['goto_url'])) {
                // Passa a URL para uma variável
                $goto_url = urldecode($_SESSION['goto_url']);

                // Remove a sessão com a URL
                unset($_SESSION['goto_url']);
            }

            return;
        } else {
            // O usuário não está logado
            $this->logged_in = false;

            // A senha não bateu
            $this->login_error = 'A senha ou Usuário não confere';

            // Remove tudo
            $this->logout();

            return;
        }
    }

    /**
     * Logout
     *
     * Desconfigura tudo do usuárui.
     *
     * @param bool $redirect Se verdadeiro, redireciona para a página de login
     * @final
     */
    protected function logout($redirect = false) {
        log::logSet('Saiu do sistema');
        // Remove all data from $_SESSION['userdata']
        $_SESSION['userdata'] = array();

        // Only to make sure (it isn't really needed)
        unset($_SESSION['userdata']);

        // Regenerates the session ID
        @session_regenerate_id();

        if ($redirect === true) {
            // Send the user to the login page
            $this->goto_login();
        }
    }

    /**
     * Vai para a página de login
     */
    protected function goto_login() {
        // Verifica se a URL da HOME está configurada
        if (defined('HOME_URI')) {
            // Configura a URL de login
            $login_uri = HOME_URI . '/home/';

            // A página em que o usuário estava
            $_SESSION['goto_url'] = urlencode($_SERVER['REQUEST_URI']);
        }

        return;
    }

    /**
     * Envia para uma página qualquer
     *
     * @final
     */
    final protected function goto_page($page_uri = null) {
        if (isset($_GET['url']) && !empty($_GET['url']) && !$page_uri) {
            // Configura a URL
            $page_uri = urldecode($_GET['url']);
        }

        if ($page_uri) {
            // Redireciona
            echo '<meta http-equiv="Refresh" content="0; url=' . $page_uri . '">';
            echo '<script type="text/javascript">window.location.href = "' . $page_uri . '";</script>';
            //header('location: ' . $page_uri);
            return;
        }
    }

    /**
     * Verifica permissões
     *
     * @param string $required A permissão requerida
     * @param array $user_permissions As permissões do usuário
     * @final
     */
    final protected function check_permissions(
            $required = 'any', $user_permissions = array('any')
    ) {
        if (!is_array($user_permissions)) {
            return;
        }

        // Se o usuário não tiver permissão
        if (!in_array($required, $user_permissions)) {
            // Retorna falso
            return false;
        } else {
            return true;
        }
    }

}
