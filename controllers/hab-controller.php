<?php

class habController extends MainController {

    public function index() {
        $this->title='Habilidades';
        $this->requiredPage('hab/index');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/hab/hab.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function alunos() {
        $this->title='Habilidades';
        $this->requiredPage('hab/alunos');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header_grafico.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/hab/alunos.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function alunoshab() {
        $this->title='Habilidades';
        $this->requiredPage('hab/alunoshab');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/hab/alunoshab.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadastrogrupo() {
        $this->title='Habilidades';
        $this->requiredPage('hab/cadastrogrupo');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/hab/cadastrogrupo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadnomenclatura() {
        $this->title = 'Nomenclaturas';
        $this->requiredPage('hab/cadnomenclatura');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/hab/cadnomenclatura.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadhab() {
        $this->title = 'Cadastro de Habilidades';
        $this->requiredPage('hab/cadhab');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/hab/cadhab.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function profescolas() {
        $this->title = 'Escolher Escola';
        $this->requiredPage('hab/profescolas');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        $id_pessoa = tool::id_pessoa();
        require_once ABSPATH . '/views/hab/profescolas.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadcompt() {
        $this->title = 'Cadastro de Competências';
        $this->requiredPage('hab/cadcompt');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/hab/cadcompt.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function escturmarelat() {
        $this->title = 'Habilidades por Turma';
        $this->requiredPage('hab/escturmarelat');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        $id_inst = tool::id_inst();
        require_once ABSPATH . '/views/hab/escturmarelat.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function escolhealuno() {
        $this->title = 'Alunos';
        $this->requiredPage('hab/carometrotab');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        $id_inst = tool::id_inst();
        require_once ABSPATH . '/views/hab/escolhealuno.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function carometrotab() {
        $this->title = 'Carômetro';
        //$this->requiredPage('hab/carometrotab');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        $id_inst = tool::id_inst();
        require_once ABSPATH . '/views/hab/carometro_tab.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function profaval() {
        $this->title = 'Carômetro';
        //$this->requiredPage('hab/carometrotab');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        $id_inst = tool::id_inst();
        require_once ABSPATH . '/views/hab/profaval.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
     public function profaval2() {
        $this->title = 'Carômetro';
        //$this->requiredPage('hab/carometrotab');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        $id_inst = tool::id_inst();
        require_once ABSPATH . '/views/hab/profaval2.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function relturmahab() {
        $this->title = 'competências por Turma';
        //$this->requiredPage('hab/relturmahab');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        $id_inst = tool::id_inst();
        require_once ABSPATH . '/views/hab/relturma_hab.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function relturmahabtot() {
        $this->title = 'competências por Turma';
        //$this->requiredPage('hab/relturmahabtot');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        $id_inst = tool::id_inst();
        require_once ABSPATH . '/views/hab/relturma_hab_total.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function escturmarelathab() {
        $this->title = 'Habilidades por Turma';
        $this->requiredPage('hab/escturmarelathab');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        $id_inst = tool::id_inst();
        require_once ABSPATH . '/views/hab/escturmarelat_hab.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    public function conceito() {
        $this->title = 'Carômetro';
        //$this->requiredPage('hab/carometrotab');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        $id_inst = tool::id_inst();
        require_once ABSPATH . '/views/hab/conceito.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    public function relhabesc() {
        $this->title = 'competências por Turma';
        //$this->requiredPage('hab/relhabesc');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/hab/relturma_hab_total.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    public function habesc() {
        $this->title = 'Habilidades por Escola';
        //$this->requiredPage('hab/habesc');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/hab/hab_escola.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
     public function habcompt() {
        $this->title = 'Competências';
        //$this->requiredPage('hab/habcompt');
        $this->requireLogin();

        $model = $this->load_model('hab/hab-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/hab/compt_escola.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
