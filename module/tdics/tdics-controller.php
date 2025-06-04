<?php

class tdicsController extends MainController {

    public $title = 'TDICS';
    public $id_sistema = 3;
    public $sistema = 'tdics';

    public function index() {
        $this->requiredPage($this->sistema.'/index');
        $this->requireLogin();

        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        if (toolErp::id_nilvel() == 10) {
            require_once ABSPATH . '/module/'.$this->sistema.'/views/main.php';
        } else {
            require_once ABSPATH . '/views/geral/inicio.php';
        }
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function run() {
        $this->requiredPage($this->sistema.'/run');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/run.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function poloCad() {
        $this->requiredPage($this->sistema.'/poloCad');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/poloCad.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function setup() {
        $this->requiredPage($this->sistema.'/setup');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/setup.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function chamada() {
        $this->requiredPage($this->sistema.'/chamada');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/chamada.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function alocaAlu() {
        $this->requiredPage($this->sistema.'/alocaAlu');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/alocaAlu.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function turmaCad() {
        $this->requiredPage($this->sistema.'/turmaCad');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/turmaCad.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function cursoCad() {
        $this->requiredPage($this->sistema.'/cursoCad');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/cursoCad.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->requireLogin();
        $this->requiredPage($this->sistema.'/index');

        if (file_exists(ABSPATH . '/module/'.$this->sistema.'/views/def/' . $_data . '.php')) {
            require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
            $model = $this->load_model('/'.$this->sistema.'/tdics-model');
            require_once ABSPATH . '/module/'.$this->sistema.'/views/def/' . $_data . '.php';
            require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
        }
    }

    public function pdf() {
        $_data = func_get_args()[0][0];
        $model = $this->load_model('/'.$this->sistema.'/tdics-model');
        require_once ABSPATH . '/module/'.$this->sistema.'/views/pdf/' . $_data . '.php';
    }

    public function quadro() {
        $this->requiredPage($this->sistema.'/quadro');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/quadro.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function lanche() {
        $model = $this->load_model($this->sistema.'/tdics-model');
        require_once ABSPATH . '/module/'.$this->sistema.'/views/lanche.php';
    }

    public function listaPiloto() {
        $this->requiredPage($this->sistema.'/listaPiloto');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/listaPiloto.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function aee() {
        $this->requiredPage($this->sistema.'/aee');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/aee.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function vagas() {
        $this->requiredPage($this->sistema.'/vagas');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/vagas.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function termoList() {
        $this->requiredPage($this->sistema.'/termoList');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/termoList.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function alunoCad() {
        $this->requiredPage($this->sistema.'/alunoCad');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/alunoCad.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function frequencia() {
        $this->requiredPage($this->sistema.'/frequencia');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/frequencia.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }
    
    public function freqCall() {
        $this->requiredPage($this->sistema.'/freqCall');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/freqCall.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function inscricao() {
        $this->requiredPage($this->sistema.'/inscricao');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/inscricao.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

        public function certif() {
        $this->requiredPage($this->sistema.'/certif');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/certif.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function avalGroup() {
        $this->requiredPage($this->sistema.'/avalGroup');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/avalGroup.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function avalCad() {
        $this->requiredPage($this->sistema.'/avalCad');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/avalCad.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function aval() {
        $this->requiredPage($this->sistema.'/aval');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/aval.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function avalConf() {
        $this->requiredPage($this->sistema.'/aval');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/avalConf.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function avalRelat() {
        $this->requiredPage($this->sistema.'/aval');
        $this->requireLogin();
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
        $model = $this->load_model($this->sistema.'/tdics-model');
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/menu.php';
        require_once ABSPATH . '/module/'.$this->sistema.'/views/avalRelat.php';
        require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/footer.php';
    }

    public function avalAluno() {
        $model = $this->load_model($this->sistema.'/tdics-model');
        require_once ABSPATH . '/module/'.$this->sistema.'/views/avalAluno.php';
    }

    public function avalAlunoQr() {
        $model = $this->load_model($this->sistema.'/tdics-model');
        require_once ABSPATH . '/module/'.$this->sistema.'/views/avalAlunoQr.php';
    }

    public function avalAlunoQrGeral() {
        $model = $this->load_model($this->sistema.'/tdics-model');
        require_once ABSPATH . '/module/'.$this->sistema.'/views/avalAlunoQrGeral.php';
    }
}
