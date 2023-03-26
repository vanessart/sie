<?php

class recursoController extends MainController {

    private $css_file = '';

    public function index() {
        $this->title = 'Gestão de Recursos';
        $this->requiredPage('recurso/index');
        $this->requireLogin();

        $model = $this->load_model('recurso/recurso-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/recurso/views/main.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function recurso() {
        $this->title = 'Gestão de Recursos';
        $this->requiredPage('recurso/recurso');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('recurso/recurso-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/recurso/views/main.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function resumo() {
        $this->title = 'Gestão de Recursos';
        $this->requiredPage('recurso/index');
        $this->requireLogin();

        $resumo = 1;
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('recurso/recurso-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/recurso/views/resumo.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function termoEmprest() {
        $this->title = 'Gestão de Recursos';
        $model = $this->load_model('recurso/recurso-model');
        require_once ABSPATH . '/module/recurso/views/termoEmprest.php';
    }

    public function ocorrencia() {
        $this->title = 'Gestão de Recursos';
        $this->requiredPage('recurso/index');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('recurso/recurso-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/recurso/views/ocorrencia.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function manutencao() {
        $this->title = 'Gestão de Recursos';
        $this->requiredPage('recurso/index');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('recurso/recurso-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/recurso/views/manutencao.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function cadCate() {
        $this->title = 'Gestão de Recursos';
        $this->requiredPage('recurso/index');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('recurso/recurso-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/recurso/views/cadCate.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function cadEquipamento() {
        $this->title = 'Gestão de Recursos';
        $this->requiredPage('recurso/index');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('recurso/recurso-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/recurso/views/cadEquipamento.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function cadLocal() {
        $this->title = 'Gestão de Recursos';
        $this->requiredPage('recurso/index');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('recurso/recurso-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/recurso/views/cadLocal.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function relGeral() {
        $this->title = 'Gestão de Recursos';
        $this->requiredPage('recurso/relGeral');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('recurso/recurso-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/recurso/views/relGeral.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function movimentRel() {
        $this->title = 'Gestão de Recursos';
        $this->requiredPage('recurso/index');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('recurso/recurso-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/recurso/views/movimentRel.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function cadLote() {
        $this->title = 'Gestão de Recursos';
        $this->requiredPage('recurso/cadLote');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('recurso/recurso-model');
        $model::_setCSS('/module/recurso/recurso.css');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/recurso/views/cadLote.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function empresta() {
        $this->title = 'Gestão de Recursos';
        $this->requiredPage('recurso/empresta');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('recurso/recurso-model');
        $model::_setCSS('/module/recurso/recurso.css');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/recurso/views/emprestimo.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    
    public function aloca() {
        $this->title = 'Gestão de Recursos';
        $this->requiredPage('recurso/aloca');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('recurso/recurso-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/recurso/views/aloca.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'Gestão de Recursos';
        $this->requireLogin();
        $this->requiredPage('recurso/index');
        $model = $this->load_model('recurso/recurso-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/recurso/views/def/' . $_data;
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pdf() {
        $_data = func_get_args()[0][0];
        $this->requireLogin();
        $model = $this->load_model('/recurso/recurso-model');
        require_once ABSPATH . '/module/recurso/views/pdf/' . $_data;
    }

    public function relatEscola() {
        $this->title = 'Gestão de Recursos';
        $model = $this->load_model('recurso/recurso-model');
        require_once ABSPATH . '/module/recurso/views/def/relatEscola.php';
    }

    public function lotePdf() {
        $this->title = 'Gestão de Recursos';
        $model = $this->load_model('recurso/recurso-model');
        require_once ABSPATH . '/module/recurso/views/pdf/lotePdf.php';
    }

    public function termoEmprestimo() {
        $this->title = 'Gestão de Recursos';
        $model = $this->load_model('recurso/recurso-model');
        require_once ABSPATH . '/module/recurso/views/def/termoEmprestimo.php';
    }

    public function protocoloDev() {
        $this->title = 'Gestão de Recursos';
        $model = $this->load_model('recurso/recurso-model');
        require_once ABSPATH . '/module/recurso/views/pdf/protocoloDev.php';
    }
 
    public function relEmprestFuncInat() {
        $this->title = 'Gestão de Recursos';
        $this->requiredPage('recurso/empresta');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('recurso/recurso-model');
        $model::_setCSS('/module/recurso/recurso.css');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/recurso/views/relEmprestimoFuncInativo.php';
        require ABSPATH . '/includes/structure/footer.php';
    }   
}
