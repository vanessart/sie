<?php

class profController extends MainController {

    public function index() {
        $this->title='Professor';
        $this->requiredPage('princ/index');
        $this->requireLogin();
$sim = 1;
        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function alocar() {
        $this->title='Professor';
        $this->requiredPage('prof/alocar');
        $this->requireLogin();
$sim = 1;

        $model = $this->load_model('prof/prof-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/prof/alocar.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cad() {
        $this->title='Professor';
        $this->requiredPage('prof/cad');
        $this->requireLogin();
$sim = 1;

        $model = $this->load_model('prof/prof-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/prof/cad.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadtmp() {
        $this->title='Professor';
        //$this->requiredPage('prof/cad');
        $this->requireLogin();
$sim = 1;

        $model = $this->load_model('prof/prof-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/prof/cadtmp.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cados() {
        $this->title='Professor';
        $this->requiredPage('prof/cados');
        $this->requireLogin();
$sim = 1;

        $model = $this->load_model('prof/prof-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/prof/cados.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function pdfalocaclasse() {
        $this->title='Professor';
        $this->requireLogin();
        $model = $this->load_model('prof/prof-model');
        require_once ABSPATH . '/views/prof/pdfalocaclasse.php';
    }

    public function pdfalocadisc() {
        $this->title='Professor';
        $this->requireLogin();
        $model = $this->load_model('prof/prof-model');
        require_once ABSPATH . '/views/prof/pdfalocadisc.php';
    }

    public function pdfalocaprof() {
        $this->title='Professor';
        $this->requireLogin();
        $model = $this->load_model('prof/prof-model');
        require_once ABSPATH . '/views/prof/pdfalocaprof.php';
    }

    public function pdfprof() {
        $this->title='Professor';
        $this->requireLogin();
        $model = $this->load_model('prof/prof-model');
        require_once ABSPATH . '/views/prof/pdfprof.php';
    }

    public function pdfprofno() {
        $this->title='Professor';
        $this->requireLogin();
        $model = $this->load_model('prof/prof-model');
        require_once ABSPATH . '/views/prof/pdfprofno.php';
    }

    public function classprof() {
        $this->title='Professor';
        $this->requiredPage('prof/classprof');
        $this->requireLogin();
$sim = 1;

        $model = $this->load_model('prof/prof-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/prof/classprof.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function senha() {
        $this->title='Professor';
        $this->requiredPage('prof/senha');
        $this->requireLogin();
$sim = 1;

        $model = $this->load_model('prof/prof-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/prof/senha.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function quadroprof() {
        $this->title='Professor';
        $this->requireLogin();
        $model = $this->load_model('prof/prof-model');
        require_once ABSPATH . '/views/prof/quadroprof.php';
    }
    
    public function qaprofe() {
        $this->title='Professor';
        $this->requireLogin();
        $model = $this->load_model('prof/prof-model');
        require_once ABSPATH . '/views/prof/qaprofe.php';
    }
    public function horario() {
        $this->title='Professor';
        $this->requiredPage('prof/horario');
        $this->requireLogin();
$sim = 1;

        $model = $this->load_model('prof/prof-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/prof/horario.php';
        require ABSPATH . '/views/_includes/footer.php';
    }    
    
    public function horarioset() {
        $this->title='Professor';
        $this->requiredPage('prof/horario');
        $this->requireLogin();
$sim = 1;

        $model = $this->load_model('prof/prof-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/prof/horarioset.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
}
