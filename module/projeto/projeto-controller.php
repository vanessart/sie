<?php

class projetoController extends MainController {

    public function index() {
        $this->title = 'Home';
        $this->requiredPage('projeto/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function projeto() {
        $this->title = 'Home';
        $this->requiredPage('projeto/projeto');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('projeto/projeto-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/projeto/views/projeto.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function teste() {
        $this->title = 'Home';
        $this->requiredPage('projeto/teste');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('projeto/projeto-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/projeto/views/teste.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'Home';
        $this->requireLogin();
        //$this->requiredPage('sed/projeto');
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/projeto/projeto-model');
        require_once ABSPATH . '/module/projeto/views/def/' . $_data;
        require ABSPATH . '/includes/structure/footer.php';
    }

}
