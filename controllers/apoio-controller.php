<?php

class apoioController extends MainController {

    public function index() {
        $this->title = 'Home';
        $this->requiredPage('apoio/index');
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
