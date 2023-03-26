<?php

/**
 * home - Controller de exemplo
 *
 * @package TutsupMVC
 * @since 0.1
 */
class HomeController extends MainController {

    /**
     * Carrega a página "/views/home/index.php"
     */
    public function index() {

        // Título da página
        $this->title = 'SIEB';


        $model = $this->load_model('home/home-model');
        ?>
        <form id="frameEscapa" target="_parent" action="<?= HOME_URI ?>" method="POST"></form>
        <script>

            if (window.frameElement) {
                document.getElementById('frameEscapa').submit();
            }
        </script>
        <?php
        /** Carrega os arquivos do view * */
        // /views/_includes/header.php
        if ($this->logged_in) {
            unset($_SESSION['userdata']['id_sistema']);
            unset($_SESSION['userdata']['id_nivel']);
            unset($_SESSION['userdata']['id_inst']);
            unset($_SESSION['userdata']['n_sistema']);
            unset($_SESSION['userdata']['n_nivel']);
            unset($_SESSION['userdata']['n_inst']);
            unset($_SESSION['userdata']['user_permissions']);
        }
        if ($this->logged_in) {
            require ABSPATH . '/views/home/acesso.php';
        } else {
            require ABSPATH . '/views/home/home_login.php';
        }
    }

    public function recupera() {
        if (empty($_REQUEST['vr'])) {
            echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . '">';
            echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '";</script>';
        } else {

            $this->title = 'SIEB';
            $model = $this->load_model('home/home-model');
            require ABSPATH . '/views/_includes/header.php';
            require ABSPATH . '/views/_includes/menu.php';
            require ABSPATH . '/views/home/recupera.php';
        }
    }

    public function google() {
        $model = $this->load_model('home/home-model');
    }

    public function sing() {

        $this->title = 'SIEB';
        // $model = $this->load_model('home/home-model');
        //  require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/app/php-oauth2/index.php';
    }

    public function singa() {

        $this->title = 'SIEB';
        // $model = $this->load_model('home/home-model');
        //   require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/app/php-oauth2/oauth2callback.php';
    }

}

// class HomeController