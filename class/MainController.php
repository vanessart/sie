<?php

/**
 * MainController - Todos os controllers deverão estender essa classe
 *
 * @package TutsupMVC
 * @since 0.1
 */
class MainController extends UserLogin {

    /**
     * $db
     *
     * Nossa conexão com a base de dados. Manterá o objeto PDO
     *
     * @access public
     */
    public $db;

    /**
     * $phpass
     *
     * Classe phpass 
     *
     * @see http://www.openwall.com/phpass/
     * @access public
     */
    public $phpass;

    /**
     * $title
     *
     * Título das páginas 
     *
     * @access public
     */
    public $title;

    /**
     * $login_required
     *
     * Se a página precisa de login
     *
     * @access public
     */
    public $login_required = false;

    /**
     * $permission_required
     *
     * Permissão necessária
     *
     * @access public
     */
    public $permission_required = 'any';
    public $parametros = array();
    public $controller_name = '';

    /**
     * Construtor da classe
     *
     * Configura as propriedades e métodos da classe.
     *
     * @since 0.1
     * @access public
     */
    public function __construct($parametros = array()) {

        // Instancia do DB
        //$this->db = new DB();
        $this->parametros = $parametros;
        // Phpass
        $this->phpass = new PasswordHash(8, false);
        // Verifica o login
        $this->check_userlogin();
    }

// __construct

    /**
     * Load model
     *
     * Carrega os modelos presentes na pasta /models/.
     *
     * @since 0.1
     * @access public
     */
    public function load_model($model_name = false) {

        // Um arquivo deverá ser enviado
        if (!$model_name)
            return;

        // Garante que o nome do modelo tenha letras minúsculas
        $model_name = strtolower($model_name);

        // Inclui o arquivo
        if (file_exists(ABSPATH . '/models/' . $model_name . '.php')) {
            $model_path = ABSPATH . '/models/' . $model_name . '.php';
        } else {
            $model_path = ABSPATH . '/module/' . $model_name . '.php';
        }

        // Verifica se o arquivo existe
        if (file_exists($model_path)) {

            // Inclui o arquivo
            require_once $model_path;

            // Remove os caminhos do arquivo (se tiver algum)
            $model_name = explode('/', $model_name);

            // Pega só o nome final do caminho
            $this->controller_name = $model_name[count($model_name)-2] ?? '';
            $model_name = end($model_name);

            // Remove caracteres inválidos do nome do arquivo
            $model_name = preg_replace('/[^a-zA-Z0-9]/is', '', $model_name);

            // Verifica se a classe existe
            if (class_exists($model_name)) {

                // Retorna um objeto da classe
                return new $model_name($this->db, $this);
            }

            // The end :)
            return;
        } // load_model
    }

// load_model

    public function requiredPage($requi) {

        $this->permission_required = $requi;
        // Verifica se o usuário tem a permissão para acessar essa página
        if (!$this->check_permissions($this->permission_required, @$this->userdata['user_permissions'])) {
            ?>
            <script type="text/javascript"> window.location.href = "<?php echo HOME_URI; ?>/home";</script>                     
            <?php
        }
    }

    public function requireLogin() {

        // Verifica se o usuário está logado
        if (!$this->logged_in) {

            // Se não; garante o logout
            $this->logout();

            // Redireciona para a página de login
            $this->goto_login();

            // Garante que o script não vai passar daqui
            return;
        }
    }

}

// class MainController