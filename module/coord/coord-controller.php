<?php

class coordController extends MainController {

    public function index() {
        $this->title = 'Home';
        $this->requiredPage('coord/index');
        $this->requireLogin();

        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function hab() {
        $this->title = 'Home';
        $this->requiredPage('coord/hab');
        $this->requireLogin();

        $model = $this->load_model('coord/coord-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/module/coord/views/hab.php';
        require ABSPATH . '/views/_includes/footer.php';
    }



}
