<?php

class gestController extends MainController {

    public function index() {
        $this->title='Gestão Educacional';
        $this->requiredPage('gest/index');
        $this->requireLogin();

        $model = $this->load_model('gest/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function convocacao() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('gest/convocacao');
        $this->requireLogin();

        $model = $this->load_model('gest/gest-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gest/convocacao.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function eventos() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('gest/convocacao');
        $this->requireLogin();

        $model = $this->load_model('gest/gest-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gest/eventos.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function encaminhamento() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('gest/encaminhamento');
        $this->requireLogin();

        $model = $this->load_model('gest/gest-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gest/encaminhamento.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function encaminhamentopdf() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gest/gest-model');
        require_once ABSPATH . '/views/gest/encaminhamentopdf.php';
    }

    public function listapdf() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gest/gest-model');
        require_once ABSPATH . '/views/gest/listapdf.php';
    }

    public function encaminhamentocarta() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('gest/encaminhamentocarta');
        $this->requireLogin();

        $model = $this->load_model('gest/gest-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gest/encaminhamentocarta.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function encaminhamentomat() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('gest/encaminhamentomat');
        $this->requireLogin();

        $model = $this->load_model('gest/gest-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gest/encaminhamentomat.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function listadestpdf() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gest/gest-model');
        require_once ABSPATH . '/views/gest/listadestpdf.php';
    }

}
