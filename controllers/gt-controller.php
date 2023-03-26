<?php

class gtController extends MainController {

    public function index() {
        $this->title='Gestão Educacional';
        $this->requiredPage('gt/index');
        $this->requireLogin();

        $model = $this->load_model('gt/gt-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function classe() {
        $this->title='Gestão Educacional';
        $this->requiredPage('gt/classe');
        $this->requireLogin();

        $model = $this->load_model('gt/gt-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gt/classe.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function classegdae() {
        $this->title='Gestão Educacional';
        $this->requiredPage('gt/classegdae');
        $this->requireLogin();

        $model = $this->load_model('gt/gt-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gt/classegdae.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function classeCiclo() {
        $this->title='Gestão Educacional';
        $this->requireLogin();
        $model = $this->load_model('select/select-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/gt/_classe/ciclo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function classeForm() {
        $this->title='Gestão Educacional';
        $this->requireLogin();
        $model = $this->load_model('select/select-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/gt/_classe/form.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function ciclo() {
        $this->title='Gestão Educacional';
        $this->requireLogin();
        $model = $this->load_model('select/select-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/gt/_classe/ciclo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function form() {
        $this->title='Gestão Educacional';
        $this->requireLogin();
        $model = $this->load_model('select/select-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/gt/_classe/form.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function gdae() {
        $this->title='Gestão Educacional';
        $this->requiredPage('gt/gdae');
        $this->requireLogin();

        $model = $this->load_model('gt/gt-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gt/gdae.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadfun() {
        $this->title='Gestão Educacional';
        // $this->requiredPage('gt/cadfun');
        $this->requireLogin();
        $model = $this->load_model('gt/gt-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        $id_gr = 39;
        require_once ABSPATH . '/views/gt/cadfun.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function caduser() {
        $this->title='Gestão Educacional';
        $this->requiredPage('gt/cadfun');
        $this->requireLogin();
        $model = $this->load_model('gt/gt-model');
        require ABSPATH . '/views/_includes/header.php';
        $id_gr = 39;
        require_once ABSPATH . '/views/gt/_cadfun/caduser.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadusersecre() {
        $this->title='Gestão Educacional';
        // $this->requiredPage('gt/cadfunfund');
        $this->requireLogin();
        $model = $this->load_model('gt/gt-model');
        require ABSPATH . '/views/_includes/header.php';
        $id_gr = 39;
        require_once ABSPATH . '/views/gt/_cadfun/cadusersecre.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadfunmat() {
        $this->title='Gestão Educacional';
        $this->requiredPage('gt/cadfunmat');
        $this->requireLogin();
        $model = $this->load_model('gt/gt-model');
        require ABSPATH . '/views/_includes/header.php';
        $id_gr = 3;
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gt/cadfunsecre.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadfunpre() {
        $this->title='Gestão Educacional';
        $this->requiredPage('gt/cadfunpre');
        $this->requireLogin();
        $model = $this->load_model('gt/gt-model');
        require ABSPATH . '/views/_includes/header.php';
        $id_gr = 5;
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gt/cadfunsecre.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadfunfund() {
        $this->title='Gestão Educacional';
        $this->requiredPage('gt/cadfunfund');
        $this->requireLogin();
        $model = $this->load_model('gt/gt-model');
        require ABSPATH . '/views/_includes/header.php';
        $id_gr = 1;
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gt/cadfunsecre.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function gerirclasse() {
        $this->title='Gestão Educacional';
        $this->requiredPage('gt/gerirclasse');
        $this->requireLogin();
        $model = $this->load_model('gt/gt-model');
        require ABSPATH . '/views/_includes/header.php';
        $id_gr = 1;
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gt/gerirclasse.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function aluno() {
        $this->title='Gestão Educacional';
        $this->requiredPage('gt/gerirclasse');
        $this->requireLogin();
        $model = $this->load_model('gt/gt-model');
        require ABSPATH . '/views/_includes/header.php';
        $id_gr = 1;
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gt/aluno.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function alunopesq() {
        $this->title='Gestão Educacional';
        $this->requiredPage('gt/alunopesq');
        $this->requireLogin();
        $model = $this->load_model('gt/gt-model');
        require ABSPATH . '/views/_includes/header.php';
        $id_gr = 1;
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gt/alunopesq.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function carometro() {
        $this->title='Gestão Educacional';
        $this->requiredPage('gt/carometro');
        $this->requireLogin();
        $model = $this->load_model('gt/gt-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gt/carometro.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function foto() {
        $this->title='Gestão Educacional';
        $this->requiredPage('gt/carometro');
        $this->requireLogin();
        $model = $this->load_model('gt/gt-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/gt/_carometro/foto.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

        public function fotossalvar() {

        $this->requireLogin();

        require_once ABSPATH . '/views/gt/fotossalvar.php';
    }

    public function fotossalvarre() {

        $this->requireLogin();

        require_once ABSPATH . '/views/gt/fotossalvarre.php';
    }
}
