<?php

class pubController extends MainController {

    public function index() {
        $this->title = 'Public';
        $this->requiredPage('pub/index');
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function rastro() {

        $this->title = 'Rastreamento';


        $model = $this->load_model('pub/pub-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/pub/r/rastro.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function arq() {
        $this->title = 'Publicações';

        $model = $this->load_model('pub/pub-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/pub/arq.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function arqcof() {
        $this->title = 'Publicações';
        $this->requiredPage('pub/arqcof');
        $this->requireLogin();
        $model = $this->load_model('pub/pub-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/pub/arqcof.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
