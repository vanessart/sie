<?php

class geralController extends MainController {

    public function index() {
        
    }

    public function userconf() {
// Título da página
        $this->title = 'Conf. Usuário';
        // $this->requiredPage('geral');

        $this->requireLogin();
        // Carrega o modelo
        $model = $this->load_model('geral/geral-model');
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
        // Carrega o view
        require_once ABSPATH . '/views/geral/userconf.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function submenu() {
// Título da página
        $this->title = 'SIEB';

        $this->requireLogin();
        // Carrega o modelo
        $model = $this->load_model('geral/geral-model');
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
        // Carrega o view
        require_once ABSPATH . '/views/geral/submenu.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function msg() {
// Título da página
        $this->title = 'SIEB';

        $this->requireLogin();
        // Carrega o modelo
        $model = $this->load_model('geral/geral-model');
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
        // Carrega o view
        require_once ABSPATH . '/views/geral/msg.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function pdf() {
        $this->title = 'SIEB';
        if (empty($_REQUEST['mdl'])) {
            $model_ = 'geral';
        } else {
            $model_ = openssl_decrypt(str_replace('|||', '+', $_REQUEST['mdl']), 'aes128', 150936);
        }

        $page = openssl_decrypt(substr(str_replace('|||', '+', $_REQUEST['pg']), 0, -1), 'aes128', 150936);

        $this->requireLogin();
        $model = $this->load_model("$model_/$model_-model");
        require ABSPATH . '/views/_includes/header.php';
        ob_start();
        if (is_string($_REQUEST['pg'])) {
            require_once ABSPATH . '/views/geral/pdf/' . str_replace("'", '', $page) . '.php';
        }
        tool::pdf();
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function resp() {
        require_once ABSPATH . '/views/geral/resp.php';
    }
    
    public function branco() {
    }

    public function userconfNew() {
        $this->title = 'Conf. Usuário';
        $this->requireLogin();
        $model = $this->load_model('geral/geral-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/views/geral/userconfNew.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }
}
