<?php

class pseController extends MainController {
    public function index() {
        $this->title = 'Programa Saúde na Escola';
        $this->requiredPage('pse/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/pse/pse-model');
        require ABSPATH . '/includes/structure/menu.php';
        if (toolErp::id_nilvel() == 24) {
            // require_once ABSPATH . '/module/pse/views/mainProf.php';
            require_once ABSPATH . '/module/pse/views/inicioProf.php';
        } elseif ((toolErp::id_nilvel() == 48)||(toolErp::id_nilvel() == 55)||(toolErp::id_nilvel() == 56)) {
            // require_once ABSPATH . '/module/pse/views/mainCoord.php';
            require_once ABSPATH . '/module/pse/views/inicioCoord.php';
        } elseif (toolErp::id_nilvel() == 10) {
            require_once ABSPATH . '/module/pse/views/mainGerente.php';
        } elseif (toolErp::id_nilvel() == 58) {
            require_once ABSPATH . '/module/pse/views/inicioOdonto.php';
        } elseif (toolErp::id_nilvel() == 59) {
            require_once ABSPATH . '/module/pse/views/inicioOdonto.php';
        } else {
            require_once ABSPATH . '/module/pse/index.php';
        }
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pse() {
        $this->title = 'Programa Saúde na Escola';
        $this->requiredPage('pse/pse');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('pse/pse-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/pse/views/pse.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function graficoCoord() {
        $this->title = 'Programa Saúde na Escola';
        $this->requiredPage('pse/graficoCoord');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('pse/pse-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/pse/views/mainCoord.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function questionario() {
        $this->title = 'Programa Saúde na Escola';
        $this->requiredPage('pse/questionario');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('pse/pse-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/pse/views/autorizacaoPSE.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'Programa Saúde na Escola';
        $this->requireLogin();
        $this->requiredPage('pse/index');
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/pse/pse-model');
        require_once ABSPATH . '/module/pse/views/def/' . $_data;
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function autorizacaoPSE() {
        $this->title = 'Programa Saúde na Escola';
        //$this->requiredPage('pse/questionario');
        //$this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('pse/pse-model');
        $model::_setCSS('/module/pse/pse.css');
        require_once ABSPATH . '/module/pse/views/form/autorizacaoPSE.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function formProf() {
        $this->title = 'Programa Saúde na Escola';
        $this->requiredPage('pse/questionario');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('pse/pse-model');
        $model::_setCSS('/module/pse/pse.css');
        require_once ABSPATH . '/module/pse/views/form/formProf.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function autorizacaoPSEPDF() {
        $this->title = 'Programa Saúde na Escola';
        $this->requiredPage('pse/questionario');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('pse/pse-model');
        $model::_setCSS('/module/pse/pse.css');
        require_once ABSPATH . '/module/pse/views/pdf/autorizacaoPSE.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function formProfPDF() {
        $this->title = 'Programa Saúde na Escola';
        $this->requiredPage('pse/questionario');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('pse/pse-model');
        $model::_setCSS('/module/pse/pse.css');
        require_once ABSPATH . '/module/pse/views/pdf/formProf.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function autorizacaoPSETodosPDF() {
        $this->title = 'Programa Saúde na Escola';
        $this->requiredPage('pse/questionario');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('pse/pse-model');
        $model::_setCSS('/module/pse/pse.css');
        require_once ABSPATH . '/module/pse/views/pdf/autorizacaoPSETodos.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function formProfTodosPDF() {
        $this->title = 'Programa Saúde na Escola';
        $this->requiredPage('pse/questionario');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('pse/pse-model');
        $model::_setCSS('/module/pse/pse.css');
        require_once ABSPATH . '/module/pse/views/pdf/formProfTodos.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function campanha() {
        $this->title = 'Programa Saúde na Escola';
        $this->requiredPage('pse/campanha');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('pse/pse-model');
        require ABSPATH . '/includes/structure/menu.php';
        $model::_setCSS('/module/pse/pse.css');
        require_once ABSPATH . '/module/pse/views/campanha.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function campanhaCad() {
        $this->title = 'Programa Saúde na Escola';
        $this->requiredPage('pse/campanha');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('pse/pse-model');
        $model::_setCSS('/module/pse/pse.css');
        require_once ABSPATH . '/module/pse/views/campanhaCad.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function campanhaAt() {
        $this->title = 'Programa Saúde na Escola';
        $this->requiredPage('pse/campanha');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('pse/pse-model');
        $model::_setCSS('/module/pse/pse.css');
        require_once ABSPATH . '/module/pse/views/campanhaAt.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function bmi() {
        $this->title = 'Programa Saúde na Escola';
        //$this->requiredPage('pse/index');
        $this->requireLogin();

        //require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/pse/pse-model');
        // require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/pse/views/bmi/bmiCalc.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function bmiList() {
        $this->title = 'Programa Saúde na Escola';
        $this->requiredPage('pse/bmiList');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/pse/pse-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/pse/views/bmi/bmiList.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function cadOdonto() {
        $this->title = 'Programa Saúde na Escola';
        $this->requiredPage('pse/avalOdonto');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/pse/pse-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/pse/views/cadOdonto.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function avalOdonto() {
        $this->title = 'Programa Saúde na Escola';
        $this->requiredPage('pse/avalOdonto');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/pse/pse-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/pse/views/avalOdonto.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function cadAluno() {
        $this->title = 'Programa Saúde na Escola';
        $this->requiredPage('pse/avalOdonto');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/pse/pse-model');
        require_once ABSPATH . '/module/pse/views/cadAluno.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function excelAlunos() {
        $this->title = 'Programa Saúde na Escola';
        $this->requiredPage('pse/avalOdonto');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/pse/pse-model');
        require_once ABSPATH . '/module/pse/views/pdf/plan.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

}
