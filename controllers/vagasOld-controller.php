<?php

class vagasController extends MainController {

    public function index() {
        $this->title = 'Home';
        $this->requiredPage('vagas/index');
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cad() {
        $this->title = 'Home';
        $this->requiredPage('vagas/cad');
        $this->requireLogin();

        $model = $this->load_model('vagas/vagas-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/vagas/cad.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function pesq() {
        $this->title = 'Home';
        $this->requiredPage('vagas/pesq');
        $this->requireLogin();

        $model = $this->load_model('vagas/vagas-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/vagas/pesq.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function dados() {
        $this->title = 'Home';
        $this->requiredPage('vagas/pesq');
        $this->requireLogin();

        $model = $this->load_model('vagas/vagas-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/vagas/dados.php';
        require ABSPATH . '/views/_includes/footer.php';
    }


}
