<?php

class selController extends MainController {

    public function index() {
        $this->title='Seletivas';
        $this->requiredPage('sel/index');
        $this->requireLogin();

        $model = $this->load_model('sel/sel-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function inscricao() {
        $this->title='Seletivas';

        $model = $this->load_model('sel/sel-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/sel/inscricao.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function seletivas() {
        $this->title = 'Seletivas';
        $this->requiredPage('sel/seletivas');
        $this->requireLogin();
        $model = $this->load_model('sel/sel-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/sel/seletivas.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function protocolo() {
        $this->title = 'Protocolo';
        $model = $this->load_model('sel/sel-model');
        require_once ABSPATH . '/views/sel/protocolo.php';
    }

    public function relatorio() {
        $this->title = 'Seletivas';
        $this->requiredPage('sel/relatorio');
        $this->requireLogin();
        $model = $this->load_model('sel/sel-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/sel/relatorio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cargos() {
        $this->title = 'Seletivas';
        $this->requiredPage('sel/cargos');
        $this->requireLogin();
        $model = $this->load_model('sel/sel-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/sel/cargos.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function predios() {
        $this->title = 'Seletivas';
        $this->requiredPage('sel/predios');
        $this->requireLogin();
        $model = $this->load_model('sel/sel-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/sel/predios.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function alocar() {
        $this->title = 'Seletivas';
        $this->requiredPage('sel/alocar');
        $this->requireLogin();
        $model = $this->load_model('sel/sel-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/sel/alocar.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function inscritos() {
        $this->title = 'Seletivas';
        $this->requiredPage('sel/inscritos');
        $this->requireLogin();
        $model = $this->load_model('sel/sel-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/sel/inscritos.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function dados() {
        $this->title = 'Seletivas';
        $this->requiredPage('sel/inscritos');
        $this->requireLogin();
        $model = $this->load_model('sel/sel-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/sel/dados.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function listaparede() {
        $this->title = 'Seletivas';
        $this->requiredPage('sel/inscritos');
        $this->requireLogin();
        $model = $this->load_model('sel/sel-model');
        require_once ABSPATH . '/views/sel/listaparede.php';
    }

    public function listaassina() {
        $this->title = 'Seletivas';
        $this->requiredPage('sel/inscritos');
        $this->requireLogin();
        $model = $this->load_model('sel/sel-model');
        require_once ABSPATH . '/views/sel/listaassina.php';
    }

    public function listas() {
        $this->title = 'Seletivas';
        $this->requiredPage('sel/inscritos');
        $this->requireLogin();
        $model = $this->load_model('sel/sel-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/sel/listas.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
