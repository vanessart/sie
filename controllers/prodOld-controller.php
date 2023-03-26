<?php

class prodController extends MainController {

    public function index() {
        $this->title = 'Produtividade';
        $this->requiredPage('prod/index');
        $this->requireLogin();

        $model = $this->load_model('prod/prod-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function lanc() {
        $this->title = 'Produtividade';
        $this->requiredPage('prod/lanc');
        $this->requireLogin();

        $model = $this->load_model('prod/prod-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/prod/lanc.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function assist() {
        $this->title = 'Produtividade';
        $this->requiredPage('prod/assist');
        $this->requireLogin();

        $model = $this->load_model('prod/prod-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/prod/assist.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function adm() {
        $this->title = 'Produtividade';
        $this->requiredPage('prod/relat');
        $this->requireLogin();

        $model = $this->load_model('prod/prod-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/prod/adm.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function relat() {
        $this->title = 'Produtividade';
        $this->requiredPage('prod/relat');
        $this->requireLogin();

        $model = $this->load_model('prod/prod-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/prod/relat.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function relatpdf() {
        $this->title = 'Produtividade';
        $this->requiredPage('prod/relat');
        $this->requireLogin();

        $model = $this->load_model('prod/prod-model');
        require_once ABSPATH . '/views/prod/relatpdf.php';
    }

    public function inloco() {
        $this->title = 'Produtividade';
        $this->requiredPage('prod/inloco');
        $this->requireLogin();

        $model = $this->load_model('prod/prod-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/prod/inloco.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function inlococad() {
        $this->title = 'Produtividade';
        $this->requiredPage('prod/inlococad');
        $this->requireLogin();

        $model = $this->load_model('prod/prod-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/prod/inlococad.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function itenspdf() {
        $this->title = 'Produtividade';
        $this->requiredPage('prod/inlococad');
        $this->requireLogin();

        $model = $this->load_model('prod/prod-model');
        require_once ABSPATH . '/views/prod/_inlococad/itenspdf.php';
    }

    public function inlocopdf() {
        $this->title = 'Produtividade';
        $this->requiredPage('prod/inloco');
        $this->requireLogin();

        $model = $this->load_model('prod/prod-model');
        require_once ABSPATH . '/views/prod/_inloco/inlocopdf.php';
    }
    
        public function relatorios() {
        $this->title = 'Produtividade';
        $this->requiredPage('prod/relatorios');
        $this->requireLogin();
        $model = $this->load_model('prod/prod-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/prod/relatorios.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

        public function fichapdf() {
        $this->title = 'Produtividade';
        $this->requiredPage('prod/relatorios');
        $this->requireLogin();
        $model = $this->load_model('prod/prod-model');
        require_once ABSPATH . '/views/prod/fichapdf.php';
    }
    
        public function finalpdf() {
        $this->title = 'Produtividade';
        $this->requiredPage('prod/relatorios');
        $this->requireLogin();
        $model = $this->load_model('prod/prod-model');
        require_once ABSPATH . '/views/prod/finalpdf.php';
    }
    
        public function finalnopdf() {
        $this->title = 'Produtividade';
        $this->requiredPage('prod/relatorios');
        $this->requireLogin();
        $model = $this->load_model('prod/prod-model');
        require_once ABSPATH . '/views/prod/finalnopdf.php';
    }
     
        public function finalnoadipdf() {
        $this->title = 'Produtividade';
        $this->requiredPage('prod/relatorios');
        $this->requireLogin();
        $model = $this->load_model('prod/prod-model');
        require_once ABSPATH . '/views/prod/finalnoadipdf.php';
    }
     
        public function finalnoinfpdf() {
        $this->title = 'Produtividade';
        $this->requiredPage('prod/relatorios');
        $this->requireLogin();
        $model = $this->load_model('prod/prod-model');
        require_once ABSPATH . '/views/prod/finalnoinfpdf.php';
    }
    
}
