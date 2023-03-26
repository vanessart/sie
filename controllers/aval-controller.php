<?php

class avalController extends MainController {

    public function index() {
        $this->title='Avaliação';
        $this->requiredPage('aval/index');
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function conf() {
        $this->title='Avaliação';
        $this->requiredPage('aval/conf');
        $this->requireLogin();

        $model = $this->load_model('aval/aval-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/aval/conf.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function notaprof() {
        $this->title='Avaliação';
        $this->requiredPage('aval/notaprof');
        $this->requireLogin();

        $model = $this->load_model('aval/aval-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        $id_pessoa = tool::id_pessoa();
        require_once ABSPATH . '/views/aval/notaprof.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function notaprofsecre() {
        $this->title='Avaliação';
        $this->requiredPage('aval/notaprofsecre');
        $this->requireLogin();

        $model = $this->load_model('aval/aval-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/aval/notaprof_secre.php';
        require_once ABSPATH . '/views/aval/notaprof.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function notaturma() {
        $this->title='Avaliação';
        $this->requiredPage('aval/notaturma');
        $this->requireLogin();

        $model = $this->load_model('aval/aval-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/aval/notaturma.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
