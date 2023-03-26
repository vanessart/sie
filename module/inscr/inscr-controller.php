<?php

class inscrController extends MainController {

    public function index() {
        $this->title = 'Home';
        $this->requiredPage('inscr/index');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        //require_once ABSPATH . '/module/inscr/views/inicio.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function eventoSet() {
        $this->title = 'Home';
        $this->requiredPage('inscr/eventoSet');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('inscr/inscr-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/inscr/views/eventoSet.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function recurso() {
        $this->title = 'Home';
        $this->requiredPage('inscr/recurso');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('inscr/inscr-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/inscr/views/recurso.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function entregaDoc() {
        $this->title = 'Home';
        $this->requiredPage('inscr/entregaDoc');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('inscr/inscr-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/inscr/views/entregaDoc.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

 
    public function DocOld() {
        $this->title = 'Home';
        $this->requiredPage('inscr/DocOld');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('inscr/inscr-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/inscr/views/DocOld.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function deferi() {
        $this->title = 'Home';
        $this->requiredPage('inscr/deferi');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('inscr/inscr-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/inscr/views/deferi.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function motivo() {
        $this->title = 'Home';
        $this->requiredPage('inscr/motivo');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('inscr/inscr-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/inscr/views/motivo.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function relat() {
        $this->title = 'Home';
        $this->requiredPage('inscr/relat');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('inscr/inscr-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/inscr/views/relat.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function inscr() {
        $form = func_get_args()[0][0];
        $this->title = 'Home';
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('inscr/inscr-model');
        require_once ABSPATH . '/module/inscr/views/inscr.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function documentos() {
        $this->title = 'Cadampe';
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('inscr/inscr-model');
        require_once ABSPATH . '/module/inscr/views/documentos.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'Home';
        $this->requireLogin();
        $this->requiredPage('inscr/index');
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/inscr/inscr-model');
        require_once ABSPATH . '/module/inscr/views/def/' . $_data;
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pdf() {
        $_data = func_get_args()[0][0];
        $model = $this->load_model('/inscr/inscr-model');
        require_once ABSPATH . '/module/inscr/views/pdf/' . $_data . '.php';
    }

    public function recuperaSenha() {
        $this->title = 'Home';
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('inscr/inscr-model');
        require_once ABSPATH . '/module/inscr/views/recuperaSenha.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function main() {
        $this->title = 'Home';
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('inscr/inscr-model');
        require_once ABSPATH . '/module/inscr/views/main.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function migra() {
        $this->title = 'Home';
        $this->requiredPage('inscr/relat');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('inscr/inscr-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/inscr/views/migra.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

}
