<?php

class citController extends MainController {

    public function index() {
        $this->title = 'Integração';
        $this->requiredPage('cit/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

        public function googleId() {
        $this->title = 'Integração';
        $this->requiredPage('cit/googleId');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/googleId.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    
    public function pesq() {
        $this->title = 'Integração';
        $this->requiredPage('cit/pesq');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('cit/cit-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cit/views/pesq.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function token() {
        $this->title = 'Integração';
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('cit/cit-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cit/views/token.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function rotina() {
        $this->title = 'Integração';
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('cit/cit-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cit/views/rotina.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function alocaProf() {
        $this->title = 'Integração';
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('cit/cit-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cit/views/alocaProf.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function htpc() {
        $this->title = 'Integração';
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('cit/cit-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cit/views/htpc.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function import() {
        $this->title = 'Integração';
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('cit/cit-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cit/views/import.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

     public function importRh() {
        $this->title = 'Integração';
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('cit/cit-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cit/views/importRh.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'Integração';
        $this->requireLogin();
        $this->requiredPage('cit/index');
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/cit/cit-model');
        require_once ABSPATH . '/module/cit/views/def/' . $_data.'.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pdf() {
        $_data = func_get_args()[0][0];
        $this->requireLogin();
        $this->requiredPage('cit/index');
        $model = $this->load_model('/cit/cit-model');
        require_once ABSPATH . '/module/cit/views/pdf/' . $_data.'.php';
    }

    public function teste() {
        $this->title = 'Integração';
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('cit/cit-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cit/views/teste.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function run() {
        $this->title = 'Integração';
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('cit/cit-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cit/views/run.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function email() {
        $this->title = 'Integração';
        $this->requiredPage('cit/email');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('cit/cit-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cit/views/email.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
 
    public function idLotacao() {
        $this->title = 'Integração';
        $this->requiredPage('cit/idLotacao');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('cit/cit-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cit/views/idLotacao.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function rh() {
        $this->title = 'Integração';
        $this->requiredPage('cit/rh');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('cit/cit-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cit/views/rh.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function rhProc() {
        $model = $this->load_model('cit/cit-model');
        require_once ABSPATH . '/module/cit/views/rhProc.php';
    }

}
