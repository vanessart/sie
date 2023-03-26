<?php

class prodController extends MainController {

    public function index() {
        $this->title = 'Produtividade';
        $this->requiredPage('prod/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function planoAula() {
        $this->title = 'Produtividade';
        $this->requiredPage('prod/planoAula');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('habili/habili-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/habili/views/planoAula.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'Produtividade';
        $this->requireLogin();
        $this->requiredPage('sed/prod');
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/prod/prod-model');
        require_once ABSPATH . '/module/prod/views/def/' . $_data;
        require ABSPATH . '/includes/structure/footer.php';
    }

}
