<?php

class biroController extends MainController {

    public function index() {
        $this->title = 'Birô';
        $this->requiredPage('biro/index');
        $this->requireLogin();

        $model = $this->load_model('biro/biro-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function trab() {
        $this->title = 'Birô';
        $this->requiredPage('biro/trab');
        $this->requireLogin();

        $model = $this->load_model('biro/biro-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/biro/trab.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function pesq() {
        $this->title = 'Birô';
        $this->requiredPage('biro/pesq');
        $this->requireLogin();

        $model = $this->load_model('biro/biro-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/biro/pesq.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function consumo() {
        $this->title = 'Birô';
        $this->requiredPage('biro/consumo');
        $this->requireLogin();

        $model = $this->load_model('biro/biro-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/biro/consumo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function prot() {
        $this->title = 'Birô';

        $model = $this->load_model('biro/biro-model');
        require_once ABSPATH . '/views/biro/prot.php';
    }


    public function contrato() {
        $this->title = 'Birô';
        $this->requiredPage('biro/contrato');
        $this->requireLogin();

        $model = $this->load_model('biro/biro-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/biro/contrato.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function saldo() {
        $this->title = 'Birô';
        $this->requiredPage('biro/contrato');
        $this->requireLogin();

        $model = $this->load_model('biro/biro-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/biro/saldo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function extrato() {
        $this->title = 'Birô';
        $this->requiredPage('biro/contrato');
        $this->requireLogin();

        $model = $this->load_model('biro/biro-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/biro/extrato.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
