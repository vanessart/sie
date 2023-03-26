<?php

class histController extends MainController {

    public function index() {
        $this->title='Histórico';
        $this->requiredPage('hist/index');
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function pesq() {
        $this->title='Histórico';
        $this->requiredPage('hist/pesq');
        $this->requireLogin();

        $model = $this->load_model('hist/hist-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/hist/pesq.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function migra() {
        $this->title='Histórico';
        $this->requiredPage('hist/migra');
        $this->requireLogin();

        $model = $this->load_model('hist/hist-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/hist/migra.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function pdf() {
        error_reporting(0);
        ini_set('display_errors', 1);

        $this->title='Histórico';
        $this->requireLogin();
        
        $model = $this->load_model('hist/hist-model');
        
        require_once ABSPATH . '/views/hist/pdf.php';
    }

    public function histset() {
        $this->title='Histórico';
        $this->requireLogin();

        $model = $this->load_model('hist/hist-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/hist/histset.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function histpessoa() {
        $this->title='Histórico';
        $this->requireLogin();

        $model = $this->load_model('hist/hist-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/hist/histpessoa.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function preenchecampo() {
        $this->title = 'Preenche Dados Cadastrais';
        $this->requireLogin();

        $model = $this->load_model('hist/hist-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/hist/preenchecampo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
}
