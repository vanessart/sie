<?php

class sysController extends MainController {

    public function index() {
        $this->title = 'Gerenciamento do Sistema';
        //$this->requiredPage('sys/index');
        $this->requireLogin();

        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function ajudacad() {
        $this->title = 'Gerenciamento do Sistema';
        $this->requiredPage('sys/ajudacad');
        $this->requireLogin();

        $model = $this->load_model('sys/sys-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        box::title('Cadastro de Ajuda');
        require_once ABSPATH . '/module/sys/views/ajudacad.php';
        require ABSPATH . '/views/_includes/footer.php';
    }



}
