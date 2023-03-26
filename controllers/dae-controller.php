<?php

class daeController extends MainController {

    public function index() {

        $this->title = 'DAE';
        $this->requiredPage('dae/index');
        $this->requireLogin();

        $model = $this->load_model('dae/dae-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function atendimento() {

        $this->title = 'Cadastro';
        $this->requiredPage('dae/atendimento');
        $this->requireLogin();

        $model = $this->load_model('dae/dae-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/dae/atendimento.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function atendieditar() {

        $this->title = 'Editar';
        $this->requiredPage('dae/atendieditar');
        $this->requireLogin();

        $model = $this->load_model('dae/dae-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/dae/atendieditar.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function protocolo() {

        $this->title = 'Protocolo';
        $this->requireLogin();
        $model = $this->load_model('dae/dae-model');
        require_once ABSPATH . '/views/dae/protocolo.php';
    }

    public function cadtabela() {

        $this->title = 'Cadastro';
        $this->requiredPage('dae/cadtabela');
        $this->requireLogin();

        $model = $this->load_model('dae/dae-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/dae/cadtabela.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
