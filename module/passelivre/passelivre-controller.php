<?php

class passelivreController extends MainController {

    public function index() {
        $this->title = 'Passe Livre';
        $this->requiredPage('passelivre/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/passelivre/passelivre-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/passelivre/views/main.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function requer() {
        $this->title = 'Passe Livre';
        $this->requiredPage('passelivre/passelivre');
        $this->requireLogin();
        $model = $this->load_model('passelivre/passelivre-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/passelivre/views/requer.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function requerSED() {
        $this->title = 'Passe Livre';
        $model = $this->load_model('passelivre/passelivre-model');
        $model->requerSED();
    }

    public function passelivre() {
        $this->title = 'Passe Livre';
        $this->requiredPage('passelivre/passelivre');
        $this->requireLogin();
        $model = $this->load_model('passelivre/passelivre-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/passelivre/views/passelivre.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function userCad() {
        $this->title = 'Passe Livre';
        $this->requiredPage('passelivre/userCad');
        $this->requireLogin();
        $model = $this->load_model('passelivre/passelivre-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/passelivre/views/userCad.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'Passe Livre';
        $this->requireLogin();
        //$this->requiredPage('sed/passelivre');
        $model = $this->load_model('/passelivre/passelivre-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/passelivre/views/def/' . $_data;
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pdf() {
        $_data = func_get_args()[0][0];
        $this->requireLogin();
        //$this->requiredPage('sed/passelivre');
        $model = $this->load_model('/passelivre/passelivre-model');
        require_once ABSPATH . '/module/passelivre/views/pdf/' . $_data;
    }

    public function escolaCad() {
        $this->title = 'Passe Livre';
        $this->requiredPage('passelivre/escolaCad');
        $this->requireLogin();
        $model = $this->load_model('passelivre/passelivre-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/passelivre/views/escolaCad.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    ####################Mario Inicio #############

    public function relatorio() {
        $this->title = 'Relatório';
        $this->requiredPage('passelivre/relatorio');
        $this->requireLogin();
        $model = $this->load_model('passelivre/passelivre-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/passelivre/views/relatorio.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function parametro() {
        $this->title = 'Cadastro Parâmetro';
        $this->requiredPage('passelivre/parametro');
        $this->requireLogin();
        $model = $this->load_model('passelivre/passelivre-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/passelivre/views/parametro.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    
    public function lancamento() {
        $this->title = 'Lançamentos';
        $this->requiredPage('passelivre/lancamento');
        $this->requireLogin();
        $model = $this->load_model('passelivre/passelivre-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/passelivre/views/lancamento.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    
    public function lote() {
        $this->title = 'Lote';
        $this->requiredPage('passelivre/lote');
        $this->requireLogin();
        $model = $this->load_model('passelivre/passelivre-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/passelivre/views/lote.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    
    public function resumo() {
        $this->title = 'Resumo';
        $this->requiredPage('passelivre/resumo');
        $this->requireLogin();
        $model = $this->load_model('passelivre/passelivre-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/passelivre/views/resumo.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    
    #######################Mario fim ##################

    public function consulta() {
        $this->title = 'Consulta';
        $this->requiredPage('passelivre/consulta');
        $this->requireLogin();
        $model = $this->load_model('passelivre/passelivre-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/passelivre/views/consulta.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    
    
    public function pdfconsulta() {
        $this->title = 'Consulta por Nome';
        $this->requiredPage('passelivre/pdfconsulta');
        $this->requireLogin();
        $model = $this->load_model('passelivre/passelivre-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/passelivre/views/pdfconsulta.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    
    
}

    