<?php

class avaliaController extends MainController {

    public function index() {
        $this->title = 'Avaliações';
        $this->requiredPage('avalia/index');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function atas() {
        $this->title = 'Avaliações';
        $this->requiredPage('avalia/atas');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('avalia/avalia-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/avalia/views/atas.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function grafTurma() {
        $this->title = 'Avaliações';
        $this->requiredPage('avalia/grafTurma');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('avalia/avalia-model');
        if (empty($_REQUEST['i'])) {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/avalia/views/grafTurma.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function grafPizza() {
        $this->title = 'Avaliações';
        $this->requiredPage('avalia/grafPizza');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('avalia/avalia-model');
        require_once ABSPATH . '/module/avalia/views/grafPizza.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function grafGeral() {
        $this->title = 'Avaliações';
        $this->requiredPage('avalia/grafGeral');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('avalia/avalia-model');
        if (empty($_REQUEST['i'])) {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/avalia/views/grafGeral.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function boletim() {
        $this->title = 'Avaliações';
        $this->requiredPage('avalia/boletim');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('avalia/avalia-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/avalia/views/boletim.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function ataEdit() {
        $this->title = 'Avaliações';
        $this->requiredPage('avalia/atas');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('avalia/avalia-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/avalia/views/ataEdit.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $this->requiredPage('avalia/index');
        $_data = func_get_args()[0][0];
        $this->title = 'Avaliações';
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/avalia/avalia-model');
        require_once ABSPATH . '/module/avalia/views/def/' . $_data . '.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pdf() {
        $_data = func_get_args()[0][0];
        $this->title = 'Avaliações';
        $this->requireLogin();
        $model = $this->load_model('/avalia/avalia-model');
        require_once ABSPATH . '/module/avalia/views/pdf/' . $_data . '.php';
    }

}
