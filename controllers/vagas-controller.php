<?php

class vagasController extends MainController {

    public function index() {
        $this->title='Inscrição Maternal';
        $this->requiredPage('vagas/index');
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cad() {
        $this->title='Inscrição Maternal';
        $this->requiredPage('vagas/index');
        $this->requireLogin();

        $model = $this->load_model('vagas/vagas-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/vagas/cad.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cada() {
        $this->title='Inscrição Maternal';
        $this->requiredPage('vagas/index');
        $this->requireLogin();

        $model = $this->load_model('vagas/vagas-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/vagas/cada.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function deferir() {
        $this->title='Inscrição Maternal';
        $this->requiredPage('vagas/deferir');
        $this->requireLogin();

        $model = $this->load_model('vagas/vagas-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/vagas/deferir.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function pesq() {
        $this->title='Inscrição Maternal';
        $this->requiredPage('vagas/pesq');
        $this->requireLogin();

        $model = $this->load_model('vagas/vagas-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/vagas/pesq.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function dados() {
        $this->title='Inscrição Maternal';
        $this->requiredPage('vagas/pesq');
        $this->requireLogin();

        $model = $this->load_model('vagas/vagas-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/vagas/dados.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function protocolo() {
        $this->title = 'Protocolo';
        $this->requiredPage('vagas/cad');
        $this->requireLogin();

        $model = $this->load_model('vagas/vagas-model');
        require_once ABSPATH . '/views/vagas/protocolo.php';
    }

    public function resumo() {
        $this->title = 'Protocolo';
        $this->requiredPage('vagas/cad');
        $this->requireLogin();

        $model = $this->load_model('vagas/vagas-model');
        require_once ABSPATH . '/views/vagas/resumo.php';
    }


       public function pdfclassic() {
        $this->title = 'Classificação';
        $this->requiredPage('vagas/pdfclassic');
        $this->requireLogin();
        
        $model = $this->load_model('vagas/vagas-model');
        $id_inst = tool::id_inst();
        require_once ABSPATH . '/views/vagas/pdfclassic.php';
    } 
    
     public function pdflistatrabalho() {
       
        $this->title = 'Classificação';
        $this->requiredPage('vagas/pdflistatrabalho');
        $this->requireLogin();

        $model = $this->load_model('vagas/vagas-model');
        $id_inst = tool::id_inst();
        require_once ABSPATH . '/views/vagas/pdflistatrabalho.php';
    }
    
    public function atendimento() {
        $this->title='Inscrição Maternal';
        $this->requiredPage('vagas/index');
        $this->requireLogin();

        $model = $this->load_model('vagas/vagas-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/vagas/atendimento.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
}
