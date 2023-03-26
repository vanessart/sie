<?php

class audicaoController extends MainController {
    public function index() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/audicao/audicao-model');
        require ABSPATH . '/includes/structure/menu.php';
        if (toolErp::id_nilvel() == 24) {
            // require_once ABSPATH . '/module/audicao/views/mainProf.php';
            require_once ABSPATH . '/module/audicao/views/inicioProf.php';
        } elseif (toolErp::id_nilvel() == 48) {
            // require_once ABSPATH . '/module/audicao/views/mainCoord.php';
            require_once ABSPATH . '/module/audicao/views/inicioCoord.php';
        } elseif (toolErp::id_nilvel() == 10) {
            require_once ABSPATH . '/module/audicao/views/mainGerente.php';
        } else {
            require_once ABSPATH . '/views/index.php';
        }
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function audicao() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/audicao');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/audicao/views/audicao.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function graficoCoord() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/graficoCoord');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/audicao/views/mainCoord.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function questionario() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/questionario');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/audicao/views/questionario.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'Caminhos do Som';
        $this->requireLogin();
        $this->requiredPage('audicao/index');
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/audicao/audicao-model');
        require_once ABSPATH . '/module/audicao/views/def/' . $_data;
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function formPais() {
        $this->title = 'Caminhos do Som';
        //$this->requiredPage('audicao/questionario');
        //$this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        $model::_setCSS('/module/audicao/audicao.css');
        require_once ABSPATH . '/module/audicao/views/form/formPais.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function formProf() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/questionario');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        $model::_setCSS('/module/audicao/audicao.css');
        require_once ABSPATH . '/module/audicao/views/form/formProf.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function formPaisPDF() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/questionario');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        $model::_setCSS('/module/audicao/audicao.css');
        require_once ABSPATH . '/module/audicao/views/pdf/formPais.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function formProfPDF() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/questionario');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        $model::_setCSS('/module/audicao/audicao.css');
        require_once ABSPATH . '/module/audicao/views/pdf/formProf.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function formPaisTodosPDF() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/questionario');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        $model::_setCSS('/module/audicao/audicao.css');
        require_once ABSPATH . '/module/audicao/views/pdf/formPaisTodos.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function formProfTodosPDF() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/questionario');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        $model::_setCSS('/module/audicao/audicao.css');
        require_once ABSPATH . '/module/audicao/views/pdf/formProfTodos.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function campanha() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/campanha');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        require ABSPATH . '/includes/structure/menu.php';
        $model::_setCSS('/module/audicao/audicao.css');
        require_once ABSPATH . '/module/audicao/views/campanha.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function campanhaCad() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/campanha');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        $model::_setCSS('/module/audicao/audicao.css');
        require_once ABSPATH . '/module/audicao/views/campanhaCad.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function campanhaAt() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/campanha');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        $model::_setCSS('/module/audicao/audicao.css');
        require_once ABSPATH . '/module/audicao/views/campanhaAt.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function aviso() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/aviso');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/audicao/views/aviso.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function encaminhamentoPed() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/aviso');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        $model::_setCSS('/module/audicao/audicao.css');
        require_once ABSPATH . '/module/audicao/views/pdf/encaminhamentoPediatra.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function avisoTriagem() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/aviso');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        $model::_setCSS('/module/audicao/audicao.css');
        require_once ABSPATH . '/module/audicao/views/pdf/avisoTriagem.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function resultado() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/resultado');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/audicao/views/resultados.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function encaminhamentoPedTodos() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/aviso');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        $model::_setCSS('/module/audicao/audicao.css');
        require_once ABSPATH . '/module/audicao/views/pdf/encaminhamentoTodos.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function avisoTriagemTodos() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/aviso');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        $model::_setCSS('/module/audicao/audicao.css');
        require_once ABSPATH . '/module/audicao/views/pdf/avisoTriagemTodos.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function resultadoGrafico() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/index');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/audicao/views/graficoResult.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function formProfResult() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/resultado');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        $model::_setCSS('/module/audicao/audicao.css');
        require_once ABSPATH . '/module/audicao/views/form/formProfResult.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function formPaisResult() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/resultado');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        $model::_setCSS('/module/audicao/audicao.css');
        require_once ABSPATH . '/module/audicao/views/form/formPaisResult.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function campanhaEdit() {
        $this->title = 'Caminhos do Som';
        $this->requiredPage('audicao/campanha');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('audicao/audicao-model');
        $model::_setCSS('/module/audicao/audicao.css');
        require_once ABSPATH . '/module/audicao/views/campanhaEdit.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

}
