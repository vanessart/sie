<?php

class admController extends MainController {

    public function index() {
        $this->title = 'ADM';
        $this->requiredPage('adm/index');
        $this->requireLogin();
        $model = $this->load_model('adm/adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }


    public function pessoa() {
        $this->title = 'ADM';
        $this->requiredPage('adm/pessoa');
        $this->requireLogin();
        $model = $this->load_model('adm/adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/adm/pessoa.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function grupo() {
        $this->title = 'ADM';
        $this->requiredPage('adm/grupo');
        $this->requireLogin();
        $model = $this->load_model('adm/adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/adm/grupo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function nivel() {
        $this->title = 'ADM';
        $this->requiredPage('adm/nivel');
        $this->requireLogin();
        $model = $this->load_model('adm/adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/adm/nivel.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function inst() {
        $this->title = 'ADM';
        $this->requiredPage('adm/inst');
        $this->requireLogin();
        $model = $this->load_model('adm/adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/adm/inst.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function grsis() {
        $this->title = 'ADM';
        $this->requiredPage('adm/grsis');
        $this->requireLogin();
        $model = $this->load_model('adm/adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/adm/grsis.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    
    public function user() {
        $this->title = 'ADM';
        $this->requiredPage('adm/user');
        $this->requireLogin();
        $model = $this->load_model('adm/adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/adm/user.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    public function grupouser() {
        $this->title = 'ADM';
        $this->requiredPage('adm/grupouser');
        $this->requireLogin();
        $model = $this->load_model('adm/adm-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/adm/grupouser.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
