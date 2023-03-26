<?php

class historicoController extends MainController {

    public function index() {
        $this->title = 'Histórico';
        $this->requiredPage('historico/index');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function hist() {
        $this->title = 'Histórico';
        $this->requiredPage('historico/hist');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('historico/historico-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/historico/views/hist.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'Histórico';
        $this->requireLogin();
       // $this->requiredPage('historico/hist');
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/historico/historico-model');
        require_once ABSPATH . '/module/historico/views/def/' . $_data . '.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pdf() {
        $_data = func_get_args()[0][0];
        //$this->requireLogin();
        //$this->requiredPage('historico/index');
        $model = $this->load_model('/historico/historico-model');
        require_once ABSPATH . '/module/historico/views/pdf/' . $_data . '.php';
    }

}
