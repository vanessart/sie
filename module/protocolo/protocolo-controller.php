<?php

class protocoloController extends MainController {

    public function index() {
        $this->title = 'Protocolos';
        $this->requiredPage('protocolo/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/protocolo/views/main.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function protocolo() {
        $this->title = 'Protocolos';
        $this->requiredPage('protocolo/protocoloList');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/protocolo/views/protocolo.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function protocoloGerente() {
        $this->title = 'Protocolos';
        $this->requiredPage('protocolo/protocoloList');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/protocolo/views/protocoloGerente.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function protocoloList() {
        $this->title = 'Protocolos';
        $this->requiredPage('protocolo/protocoloList');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/protocolo/views/protocoloList.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function teste() {
        $this->title = 'Protocolos';
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/menu.php';
        #require_once ABSPATH . '/module/protocolo/views/teste.php';
        require_once ABSPATH . '/module/protocolo/signature-din.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'Protocolos';
        $this->requireLogin();
        //$this->requiredPage('sed/protocolo');
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/protocolo/protocolo-model');
        require_once ABSPATH . '/module/protocolo/views/def/' . $_data;
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function deferidoList() {
        $this->title = 'Protocolos';
        $this->requiredPage('protocolo/deferidoList');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/protocolo/views/deferidoList.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function contato() {
        $this->title = 'Protocolos';
        $this->requiredPage('protocolo/deferidoList');
        $this->requireLogin();

        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/protocolo/views/contato.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function termoRecusa() {
        $this->title = 'Protocolos';
        $this->requiredPage('protocolo/termoRecusa');
        $this->requireLogin();
        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/apd/views/termoRecusa.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function termoAssinadoGet() {
        $this->requireLogin();
        $model = $this->load_model('protocolo/protocolo-model');
        $model->termoAssinadoGet();
    }

    public function termoRecusaRedirect() {
        $this->requireLogin();
        $model = $this->load_model('protocolo/protocolo-model');
        require_once ABSPATH . '/module/protocolo/views/def/termoRecusa.php';
    }

    public function capAssinatura() {
        $this->title = 'Protocolos';
        $this->requiredPage('protocolo/termoRecusa');
        $this->requireLogin();
        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/apd/views/capAssinatura.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function protocoloListProf() {
        $this->title = 'Protocolos';
        $this->requiredPage('protocolo/protocoloListProf');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/protocolo/views/protocoloList.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

}
