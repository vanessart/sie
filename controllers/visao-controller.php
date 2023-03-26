<?php

class visaoController extends MainController {

    public function index() {

        $this->title='Campanha da Visão';
        //$this->requiredPage('visao/index');
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');

        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function listaalunovisao() {

        $this->title='Campanha da Visão';
        $this->requiredPage('visao/listaalunovisao');
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/visao/listaalunovisao.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function testepdf() {

        $this->title = 'Campanha da Visão';
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');
        require_once ABSPATH . '/views/visao/testepdf.php';
    }

    public function retestepdf() {

        $this->title = 'Campanha da Visão';
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');
        require_once ABSPATH . '/views/visao/retestepdf.php';
    }

    public function selecionaaluno() {

        $this->title = 'Campanha da Visão';
        $this->requiredPage('visao/selecionaaluno');
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/visao/selecionaaluno.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function digitacao() {

        $this->title = 'Campanha da Visão';
        $this->requiredPage('visao/selecionaaluno');
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/visao/digitacao.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function testecomputador() {

        $this->title = 'Campanha da Visão';
        $this->requiredPage('visao/testecomputador');
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/visao/testecomputador.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function testepdfresu() {

        $this->title = 'Campanha da Visão';
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');
        require_once ABSPATH . '/views/visao/testepdfresu.php';
    }

    public function retestepdfresu() {

        $this->title = 'Campanha da Visão';
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');
        require_once ABSPATH . '/views/visao/retestepdfresu.php';
    }

    public function testepdfestat() {

        $this->title = 'Campanha da Visão';
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');
        require_once ABSPATH . '/views/visao/testepdfestat.php';
    }

    public function retestepdfestat() {

        $this->title = 'Campanha da Visão';
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');
        require_once ABSPATH . '/views/visao/retestepdfestat.php';
    }

    public function acompanhamentopdf() {

        $this->title = 'Campanha da Visão';
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');
        require_once ABSPATH . '/views/visao/acompanhamentopdf.php';
    }

    public function grafteste() {
        $this->title = 'Campanha da Visão';
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');

        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/visao/grafteste.php';
    }

    public function grafreteste() {
        $this->title = 'Campanha da Visão';
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');

        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/visao/grafreteste.php';
    }

    public function pdfresumorede() {
        $this->title = 'Campanha da Visão';
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');

        //require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/visao/pdfresumorede.php';
    }

    public function graficorede() {
        $this->title = 'Campanha da Visão';
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/visao/graficorede.php';
    }

    public function anosanteriores() {

        $this->title = 'Campanha da Visão';
        $this->requiredPage('visao/index');
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/visao/anosanteriores.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function acompanha() {

        $this->title = 'Campanha da Visão';
        $this->requiredPage('visao/anosanteriores');
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/visao/acompanha.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function escolarelatorio() {

        $this->title = 'Campanha da Visão';
        $this->requiredPage('visao/index');
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/visao/escolarelatorio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function rede() {

        $this->title = 'Campanha da Visão';
        $this->requiredPage('visao/index');
        $this->requireLogin();

        $model = $this->load_model('visao/visao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/visao/rede.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
