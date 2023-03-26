<?php

class avsController extends MainController {

    public function index() {
        $this->title = 'Aviso';
        $this->requiredPage('avs/index');
        $this->requireLogin();

        $model = $this->load_model('avs/avs-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }


    public function tipoconf() {
        $this->title = 'Aviso';
        $this->requiredPage('avs/tipoconf');
        $this->requireLogin();

        $model = $this->load_model('avs/avs-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/avs/tipoconf.php';
        require ABSPATH . '/views/_includes/footer.php';
    }


    public function setor() {
        $this->title = 'Aviso';
        $this->requiredPage('avs/setor');
        $this->requireLogin();

        $model = $this->load_model('avs/avs-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/avs/setor.php';
        require ABSPATH . '/views/_includes/footer.php';
    }


    public function setorpessoa() {
        $this->title = 'Aviso';
        $this->requiredPage('avs/setorpessoa');
        $this->requireLogin();

        $model = $this->load_model('avs/avs-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/avs/setorpessoa.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function aviso() {
        $this->title = 'Aviso';
        $this->requiredPage('avs/aviso');
        $this->requireLogin();

        $model = $this->load_model('avs/avs-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/avs/aviso.php';
        require ABSPATH . '/views/_includes/footer.php';
    }



}
