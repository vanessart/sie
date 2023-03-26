<?php

class infoController extends MainController {

    public function index() {
        $this->title = 'Informações';
        $this->requiredPage('info/index');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
       // if (tool::id_nivel() == 43) {
          //  require_once ABSPATH . '/views/info/emailAlunoEscola.php';
     //   } else {
            require_once ABSPATH . '/views/geral/inicio.php';
      //  }
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function prof() {
        $this->title = 'Informações';
        $this->requiredPage('info/prof');
        $this->requireLogin();

        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/prof.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function funcionarios() {
        $this->title = 'Informações';
        $this->requiredPage('info/funcionarios');
        $this->requireLogin();

        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/funcionarios.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function alunos() {
        $this->title = 'Informações';
        $this->requiredPage('info/alunos');
        $this->requireLogin();

        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/alunos.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function qafund() {
        $this->title = 'Informações';
        $this->requiredPage('info/qafund');
        $this->requireLogin();

        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/qafund.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function qafundPdf() {
        $this->requireLogin();
        ob_start();
        $pdf = 1;
        $model = $this->load_model('info/info-model');
        require_once ABSPATH . '/views/info/qafund.php';
        tool::pdfSecretaria('P');
    }

    public function hac() {
        $this->title = 'Informações';
        $this->requiredPage('info/hac');
        $this->requireLogin();

        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/hac.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function turmahac() {
        $this->title = 'Informações';

        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/turmahac.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function lotacao() {
        $this->title = 'Informações';
        $this->requireLogin();
        $this->requiredPage('info/lotacao');

        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/lotacao.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function lotacaoaluno() {
        $this->title = 'Informações';
        $this->requireLogin();
        $this->requiredPage('info/lotacao');
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/info/lotacaoaluno.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function qamat() {
        $this->title = 'Informações';
        $this->requiredPage('info/qamat');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/qamat.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function qamatPdf() {
        $this->requireLogin();
        $this->requiredPage('info/qamat');
        ob_start();
        $pdf = 1;
        $model = $this->load_model('info/info-model');
        require_once ABSPATH . '/views/info/qamat.php';
        tool::pdfSecretaria('P');
    }

    public function qainfaPdf() {
        $this->requireLogin();
        $this->requiredPage('info/qainfa');
        ob_start();
        $pdf = 1;
        $model = $this->load_model('info/info-model');
        require_once ABSPATH . '/views/info/qainfa.php';
        tool::pdfSecretaria('P');
    }

    public function qainfa() {
        $this->title = 'Informações';
        $this->requiredPage('info/qainfa');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/qainfa.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function qaejaPdf() {
        $this->requireLogin();
        $this->requiredPage('info/qaeja');
        ob_start();
        $pdf = 1;
        $model = $this->load_model('info/info-model');
        require_once ABSPATH . '/views/info/qaeja.php';
        tool::pdfSecretaria('P');
    }

    public function qaeja() {
        $this->title = 'Informações';
        $this->requiredPage('info/qaeja');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/qaeja.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function alunoslist() {
        $this->title = 'Informações';
        $this->requiredPage('info/qaeja');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/alunoslist.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function abrange() {
        $this->title = 'Informações';
        $this->requiredPage('info/abrange');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/abrange.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function qaprof() {
        $this->title = 'Informações';
        $this->requiredPage('info/qaprof');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/qaprof.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function brinq() {
        $this->title = 'brinq';
        $this->requiredPage('info/qaprof');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/brinq.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function qaprofe() {
        $this->title = 'Informações';
        $this->requiredPage('info/qaprofe');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        if (empty($_POST['impr'])) {
            require ABSPATH . '/views/_includes/menu.php';
        }
        require_once ABSPATH . '/views/info/qaprofe.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function qains() {
        $this->title = 'Informações';
        $this->requiredPage('info/qains');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/qains.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function exportar() {
        $this->title = 'Informações';
        $this->requiredPage('info/exportar');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/exportar.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function otica() {
        $this->title = 'Informações';
        // $this->requiredPage('info/exportar');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/otica.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function heat() {
        $this->title = 'Informações';
        $this->requiredPage('info/heat');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/heat.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function pdfinscricaovaga() {

        $this->title = 'Informações';
        $this->requiredPage('info/pdfinscricaovaga');
        $this->requireLogin();

        $model = $this->load_model('info/info-model');
        $id_inst = tool::id_inst();
        require_once ABSPATH . '/views/info/pdfinscricaovaga.php';
    }

    public function alunover() {

        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require_once ABSPATH . '/views/info/alunover.php';
    }

    public function qafundprox() {
        $this->title = 'Informações';
        $this->requiredPage('info/qafundprox');
        $this->requireLogin();

        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/qafundprox.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function qafundPdfprox() {
        $this->requireLogin();
        ob_start();
        $pdf = 1;
        $model = $this->load_model('info/info-model');
        require_once ABSPATH . '/views/info/qafundprox.php';
        tool::pdfSecretaria('P');
    }

    public function qainfaprox() {
        $this->title = 'Informações';
        $this->requiredPage('info/qainfaprox');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/qainfaprox.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function qainfaPdfprox() {
        $this->requireLogin();
        ob_start();
        $pdf = 1;
        $model = $this->load_model('info/info-model');
        require_once ABSPATH . '/views/info/qainfaprox.php';
        tool::pdfSecretaria('P');
    }

    public function qamatprox() {
        $this->title = 'Informações';
        $this->requiredPage('info/qamatprox');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/qamatprox.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function qamatPdfprox() {
        $this->requireLogin();
        ob_start();
        $pdf = 1;
        $model = $this->load_model('info/info-model');
        require_once ABSPATH . '/views/info/qamatprox.php';
        tool::pdfSecretaria('P');
    }

    public function qaejaprox() {
        $this->title = 'Informações';
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/qaejaprox.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function qaejaPdfprox() {
        $this->requireLogin();
        $this->requiredPage('info/qaejaprox');
        ob_start();
        $pdf = 1;
        $model = $this->load_model('info/info-model');
        require_once ABSPATH . '/views/info/qaejaprox.php';
        tool::pdfSecretaria('P');
    }

    //Thomas e David - 19/03
    public function buscaEmailAluno() {
        $this->title = 'Informações';
        $this->requiredPage('info/qamatprox');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/buscaEmailAluno.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function buscaEmailProf() {
        $this->title = 'Informações';
        $this->requiredPage('info/qamatprox');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/buscaEmailProf.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function emailProfEscola() {
        $this->title = 'Informações';
        $this->requiredPage('info/qamatprox');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/emailProfEscola.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function emailAlunoEscola() {
        $this->title = 'Informações';
        $this->requiredPage('info/index');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/emailAlunoEscola.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function emailAlunoClasse() {
        $this->title = 'Informações';
        $this->requiredPage('info/index');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/emailAlunoClasse.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function exportaremail() {
        $this->title = 'Informações';
        $this->requiredPage('info/exportaremail');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/exportaremail.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function pesqemail() {
        $this->title = 'Informações';
        $this->requiredPage('info/pesqemail');
        $this->requireLogin();
        $model = $this->load_model('info/info-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/info/pesqemail.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
