<?php

class publicController extends MainController {

    public function index() {
        $this->title = 'Public';
        $this->requireLogin();
        //$this->requiredPage('public/index');
        $model = $this->load_model('public/public-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        if (user::session('id_nivel') == 24) {
            require_once ABSPATH . '/views/public/prof.php';
        } elseif (user::session('id_nivel') == 36) {
            require_once ABSPATH . '/views/public/boca.php';
        }
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function fundprof() {
        $this->title = 'Fundamental';
        $this->requireLogin();
        $this->requiredPage('public/fundprof');
        $model = $this->load_model('public/public-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/public/fundprof.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function ejaprof() {
        $this->title = 'EJA';
        $this->requireLogin();
        $this->requiredPage('public/ejaprof');
        $model = $this->load_model('public/public-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/public/ejaprof.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function infprof() {
        $this->title = 'Infantil';
        $this->requiredPage('public/infprof');
        $model = $this->load_model('public/public-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/public/infprof.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function rastro() {

        $this->title = 'Rastreamento';


        $model = $this->load_model('public/public-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/pub/r/rastro.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function arq() {
        $this->title = 'Fundamental';

        $model = $this->load_model('public/public-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/public/arq.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function arqcof() {
        $this->title = 'Publicações';
        $this->requiredPage('public/arqcof');
        $this->requireLogin();
        $model = $this->load_model('public/public-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/public/arqcof.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function fund() {
        $this->title = 'Fundamental';
        $model = $this->load_model('public/public-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/public/fund.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function infantil() {
        $this->title = 'Infantil';
        $model = $this->load_model('public/public-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/public/infantil.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function eja() {
        $this->title = 'EJA';
        $model = $this->load_model('public/public-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/public/eja.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function sb() {
        $this->title = 'Downloads';
        $this->requiredPage('public/sb');
        $this->requireLogin();
        $model = $this->load_model('public/public-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/public/boca.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function sp() {
        $this->title = 'SP';
        $model = $this->load_model('public/public-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/public/beto.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function rh() {
        $this->title = 'RH';
        $model = $this->load_model('public/public-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/public/rh.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function ts() {
        $this->title = 'RH';
        $model = $this->load_model('public/public-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/public/ts.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
}
