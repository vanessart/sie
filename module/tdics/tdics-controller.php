<?php

class tdicsController extends MainController {

    public function index() {
        $this->title = 'TDICS';
        $this->requiredPage('tdics/index');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('tdics/tdics-model');
        require ABSPATH . '/includes/structure/menu.php';
        if (toolErp::id_nilvel() == 10) {
            require_once ABSPATH . '/module/tdics/views/main.php';
        } else {
            require_once ABSPATH . '/views/geral/inicio.php';
        }
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function run() {
        $this->title = 'TDICS';
        $this->requiredPage('tdics/run');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('tdics/tdics-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/tdics/views/run.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function poloCad() {
        $this->title = 'TDICS';
        $this->requiredPage('tdics/poloCad');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('tdics/tdics-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/tdics/views/poloCad.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function setup() {
        $this->title = 'TDICS';
        $this->requiredPage('tdics/setup');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('tdics/tdics-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/tdics/views/setup.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function chamada() {
        $this->title = 'TDICS';
        $this->requiredPage('tdics/chamada');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('tdics/tdics-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/tdics/views/chamada.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function alocaAlu() {
        $this->title = 'TDICS';
        $this->requiredPage('tdics/alocaAlu');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('tdics/tdics-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/tdics/views/alocaAlu.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function turmaCad() {
        $this->title = 'TDICS';
        $this->requiredPage('tdics/turmaCad');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('tdics/tdics-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/tdics/views/turmaCad.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function cursoCad() {
        $this->title = 'TDICS';
        $this->requiredPage('tdics/cursoCad');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('tdics/tdics-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/tdics/views/cursoCad.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'TDICS';
        $this->requireLogin();
        $this->requiredPage('tdics/index');
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/tdics/tdics-model');
        require_once ABSPATH . '/module/tdics/views/def/' . $_data . '.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pdf() {
        $_data = func_get_args()[0][0];
        $model = $this->load_model('/tdics/tdics-model');
        require_once ABSPATH . '/module/tdics/views/pdf/' . $_data . '.php';
    }

    public function quadro() {
        $this->title = 'TDICS';
        $this->requiredPage('tdics/quadro');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('tdics/tdics-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/tdics/views/quadro.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function lanche() {
        $this->title = 'TDICS';
        $this->requiredPage('tdics/lanche');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('tdics/tdics-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/tdics/views/lanche.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function listaPiloto() {
        $this->title = 'TDICS';
        $this->requiredPage('tdics/listaPiloto');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('tdics/tdics-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/tdics/views/listaPiloto.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function aee() {
        $this->title = 'TDICS';
        $this->requiredPage('tdics/aee');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('tdics/tdics-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/tdics/views/aee.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function vagas() {
        $this->title = 'TDICS';
        $this->requiredPage('tdics/vagas');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('tdics/tdics-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/tdics/views/vagas.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function termoList() {
        $this->title = 'TDICS';
        $this->requiredPage('tdics/termoList');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('tdics/tdics-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/tdics/views/termoList.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function alunoCad() {
        $this->title = 'TDICS';
        $this->requiredPage('tdics/alunoCad');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('tdics/tdics-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/tdics/views/alunoCad.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function frequencia() {
        $this->title = 'TDICS';
        $this->requiredPage('tdics/frequencia');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('tdics/tdics-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/tdics/views/frequencia.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    
    public function freqCall() {
        $this->title = 'TDICS';
        $this->requiredPage('tdics/freqCall');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('tdics/tdics-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/tdics/views/freqCall.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function inscricao() {
        $this->title = 'TDICS';
        $this->requiredPage('tdics/inscricao');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('tdics/tdics-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/tdics/views/inscricao.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

        public function certif() {
        $this->title = 'TDICS';
        $this->requiredPage('tdics/certif');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('tdics/tdics-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/tdics/views/certif.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    
}
