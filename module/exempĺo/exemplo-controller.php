<?php

class exemploController extends MainController {

    public function index() {
        $this->title = 'Home';
        $this->requiredPage('exemplo/index');
        $this->requireLogin();

        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function exemplo() {
        $this->title = 'Home';
        $this->requiredPage('exemplo/exemplo');
        $this->requireLogin();

        $model = $this->load_model('exemplo/exemplo-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/module/exemplo/views/exemplo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }



}
