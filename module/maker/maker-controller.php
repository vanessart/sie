<?php

class makerController extends MainController {

    public function index() {
        $this->title = 'Maker';
        $this->requiredPage('maker/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');

        require ABSPATH . '/includes/structure/menu.php';
        if (toolErp::id_nilvel() == 10) {
            require_once ABSPATH . '/module/maker/views/main.php';
        } elseif (toolErp::id_nilvel() == 2) {
            require_once ABSPATH . '/module/maker/views/adm.php';
        } else {
            require_once ABSPATH . '/module/maker/views/inicio.php';
        }

        require ABSPATH . '/includes/structure/footer.php';
    }

    public function polos() {
        $this->title = 'Maker';
        $this->requiredPage('maker/polos');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/polos.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    
    public function rankingMatr() {
        $this->title = 'Maker';
        $this->requiredPage('maker/index');
        $this->requireLogin();
        $model = $this->load_model('maker/maker-model');
        require_once ABSPATH . '/module/maker/views/rankingMatr.php';
    }
    
    public function rankingMatrVagas() {
        $this->title = 'Maker';
        $this->requiredPage('maker/index');
        $this->requireLogin();
        $model = $this->load_model('maker/maker-model');
        require_once ABSPATH . '/module/maker/views/rankingMatrVagas.php';
    }

    public function rematricula() {
        $this->title = 'Maker';
        $this->requiredPage('maker/rematricula');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/rematricula.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function rematriculaRelat() {
        $this->title = 'Maker';
        $this->requiredPage('maker/rematriculaRelat');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/rematriculaRelat.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function autoriza() {
        $this->requiredPage('maker/rematricula');
        $this->requireLogin();
        $model = $this->load_model('maker/maker-model');
        $model->autoriza();
    }
    
    public function transpote() {
        $this->requiredPage('maker/rematricula');
        $this->requireLogin();
        $model = $this->load_model('maker/maker-model');
        $model->transpote();
    }

    public function espera() {
        $this->title = 'Maker';
        $this->requiredPage('maker/espera');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/espera.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    public function permutaTurma() {
        $this->title = 'Maker';
        $this->requiredPage('maker/permutaTurma');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/permutaTurma.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function contempla() {
        $this->title = 'Maker';
        $this->requiredPage('maker/contempla');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/contempla.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function CriarTurma() {
        $this->title = 'Maker';
        $this->requiredPage('maker/CriarTurma');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/CriarTurma.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function frequencia() {
        $this->title = 'Maker';
        $this->requiredPage('maker/frequencia');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/frequencia.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function transporte() {
        $this->title = 'Maker';
        $this->requiredPage('maker/transporte');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/transporte.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function permuta() {
        $this->title = 'Maker';
        $this->requiredPage('maker/permuta');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/permuta.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function transpGeral() {
        $this->title = 'Maker';
        $this->requiredPage('maker/transpGeral');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/transpGeral.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function quadro() {
        $this->title = 'Maker';
        $this->requiredPage('maker/quadro');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/quadro.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function matr() {
        $this->title = 'Maker';
        $this->requiredPage('maker/matr');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/matr.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function transf() {
        $this->title = 'Maker';
        $this->requiredPage('maker/transf');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/transf.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function aloca() {
        $this->title = 'Maker';
        $this->requiredPage('maker/aloca');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/aloca.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function lache() {
        $this->title = 'Maker';
        $this->requiredPage('maker/lache');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/lache.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function lancheList() {
        $this->requiredPage('maker/lancheList');
        $this->requireLogin();
        $model = $this->load_model('maker/maker-model');
        require_once ABSPATH . '/module/maker/views/lancheList.php';
    }

    public function piloto() {
        $this->title = 'Maker';
        $this->requiredPage('maker/piloto');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/piloto.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function confirma() {
        $this->title = 'Maker';
        $this->requiredPage('maker/confirma');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/confirma.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function transp() {
        $this->title = 'Maker';
        $this->requiredPage('maker/transp');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/transp.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function chamada() {
        $this->title = 'Maker';
        $this->requiredPage('maker/chamada');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/chamada.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function transpEsc() {
        $this->requiredPage('maker/transpEsc');
        $this->requireLogin();
        $model = $this->load_model('maker/maker-model');
        require_once ABSPATH . '/module/maker/views/transpEsc.php';
    }

    public function escolaList() {
        $this->title = 'Maker';
        $this->requiredPage('maker/escolaList');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/escolaList.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function cadAlunos() {
        $this->title = 'Maker';
        $this->requiredPage('maker/cadAlunos');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/cadAlunos.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function turmasEsc() {
        $this->title = 'Maker';
        $this->requiredPage('maker/cadAlunos');
        $this->requireLogin();
        $model = $this->load_model('maker/maker-model');
        require_once ABSPATH . '/module/maker/views/turmasEsc.php';
    }

    public function turmasEscTur() {
        $this->title = 'Maker';
        $this->requiredPage('maker/turmasEscTur');
        $this->requireLogin();
        $model = $this->load_model('maker/maker-model');
        require_once ABSPATH . '/module/maker/views/turmasEscTur.php';
    }

    public function alunoGest() {
        $this->title = 'Maker';
        $this->requiredPage('maker/alunoGest');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/alunoGest.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function setup() {
        $this->title = 'Maker';
        $this->requiredPage('maker/setup');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/setup.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function certif() {
        $this->title = 'Maker';
        $this->requiredPage('maker/certif');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('maker/maker-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/maker/views/certif.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'Maker';
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/maker/maker-model');
        require_once ABSPATH . '/module/maker/views/def/' . $_data . '.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pdf() {
        $_data = func_get_args()[0][0];
        $this->title = 'Maker';
        $this->requireLogin();
        $model = $this->load_model('/maker/maker-model');
        require_once ABSPATH . '/module/maker/views/pdf/' . $_data . '.php';
    }

}
