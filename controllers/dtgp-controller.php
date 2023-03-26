<?php

class dtgpController extends MainController {

    public function index() {
        $this->title = 'DTGP';
        $this->requiredPage('dtgp/index');

        $this->requireLogin();
        // Carrega o modelo
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
        // Carrega o view
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function titulos() {
        $this->title = 'DTGP';
        $this->requiredPage('dtgp/titulos');

        $this->requireLogin();
        // Carrega o modelo
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
        // Carrega o view
        require_once ABSPATH . '/views/dtgp/titulos.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    //settitulos
    public function settitulos() {
        $this->title = 'DTGP';
        $this->requiredPage('dtgp/settitulos');

        $this->requireLogin();
        // Carrega o modelo
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
        // Carrega o view
        require_once ABSPATH . '/views/dtgp/settitulos.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    //settitulos
    public function cadampecad() {
        $this->title = 'DTGP';
        $this->requiredPage('dtgp/cadampecad');

        $this->requireLogin();
        // Carrega o modelo
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
        // Carrega o view
        require_once ABSPATH . '/views/dtgp/cadampecad.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadampecada() {
        $this->title = 'DTGP';
        // $this->requiredPage('dtgp/cadampecad');

        $this->requireLogin();
        // Carrega o modelo
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
        // Carrega o view
        require_once ABSPATH . '/views/dtgp/cadampecada.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadampeproc() {
        $this->title = 'DTGP';
        $this->requiredPage('dtgp/cadampeproc');

        $this->requireLogin();
        // Carrega o modelo
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
        // Carrega o view
        require_once ABSPATH . '/views/dtgp/cadampeproc.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function banco() {
        $this->title = 'Banco';
        $this->requiredPage('dtgp/banco');
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/dtgp/banco.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function acumulo() {
        $this->title = 'Acumulo';
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/dtgp/acumulo.php';
    }

    public function ficha() {
        $this->title = 'Ficha';
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/dtgp/ficha.php';
    }

    public function termo() {
        $this->title = 'Termo';
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/dtgp/termo.php';
    }

    public function profesc() {
        $this->title = 'DTGP';
        $this->requiredPage('dtgp/profesc');
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/dtgp/profesc.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    public function relat() {
        $this->title = 'DTGP';
        $this->requiredPage('dtgp/relat');
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/dtgp/relat.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function relate() {
        $this->title = 'DTGP';
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/dtgp/relate.php';
        require ABSPATH . '/views/_includes/footer.php';
    }


    public function listProfDisc() {
        $this->title = 'DTGP';
        $this->requireLogin();
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/dtgp/listProfDisc.php';
    }
    public function comprovante() {
        $this->title = 'DTGP';
        $this->requireLogin();
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/dtgp/comprovante.php';
    }
   public function tea() {
        $this->title = 'DTGP';
        $this->requireLogin();
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/dtgp/tea.php';
    }
 
    public function cons() {
        $this->title = 'DTGP';
        $this->requireLogin();
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/dtgp/cons.php';
    }
    
 
    public function consu() {
        $this->title = 'DTGP';
        $this->requireLogin();
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/dtgp/consu.php';
    }
    
    public function constt() {
        $this->title = 'DTGP';
        $this->requireLogin();
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/dtgp/constt.php';
    }
    
    public function lista() {
        $this->title = 'DTGP';
        $this->requireLogin();
        $model = $this->load_model('dtgp/dtgp-model');
        require ABSPATH . '/views/dtgp/lista.php';
    }

}
