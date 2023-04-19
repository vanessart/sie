<?php

class princController extends MainController {

    public function index() {
        // Título da página
        $this->title = 'Sistema Principal';

        $this->requiredPage('princ/index');


        // Carrega o modelo
        $model = $this->load_model('princ/princ-model');
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
        // Carrega o view
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function paginas() {
        // Título da página
        $this->title = 'Sistema Principal';

        $this->requiredPage('princ/paginas');


        // Carrega o modelo
        $model = $this->load_model('princ/princ-model');
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
        // Carrega o view
        require ABSPATH . '/views/princ/pagina.php';


        include_once ABSPATH . '/views/relat/simples.php';

        require ABSPATH . '/views/_includes/footer.php';
    }

    public function menu() {
        // Título da página
        $this->title = 'Sistema Principal';

        $this->requiredPage('princ/menu');
        // Carrega o modelo
        $model = $this->load_model('princ/princ-model');
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
        // Carrega o view
        require ABSPATH . '/views/princ/menu.php';

        require ABSPATH . '/views/_includes/footer.php';
    }

    public function sistema() {
        // Título da página
        $this->title = 'Sistema Principal';

        $this->requiredPage('princ/sistema');
        // Carrega o modelo
        $model = $this->load_model('princ/princ-model');
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
        // Carrega o view
        require ABSPATH . '/views/princ/sis.php';

        require ABSPATH . '/views/_includes/footer.php';
    }

    public function usuario() {
        // Título da página
        $this->title = 'Sistema Principal';

        $this->requiredPage('princ/usuario');
        // Carrega o modelo
        $model = $this->load_model('princ/princ-model');
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
        $form = $model->usuario(@$_POST['search']);

        $include = ABSPATH . '/views/princ/form-user.php';
        include ABSPATH . '/views/relat/simples.php';
        if (!empty(@$_POST['search']) && isset($form['array'][0]) && $form['array'][0]['ativo'] == 'Sim') {
            echo '<br />';

            $form = $model->acesso($form['array'][0]['id_pessoa']);

            $include = ABSPATH . '/views/princ/form-acesso.php';

            include ABSPATH . '/views/relat/simples.php';
        }

        require ABSPATH . '/views/_includes/footer.php';
    }

    public function usersis() {
        // Título da página
        $this->title = 'Sistema Principal';

        $this->requiredPage('princ/usersis');
        // Carrega o modelo
        $model = $this->load_model('princ/princ-model');
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';

        require ABSPATH . '/views/princ/select-usersis.php';
        if (!empty(@$_POST['fk_id_nivel'])) {
            echo '<br />';

            $form = $model->acesso(['fk_id_sistema' => $_POST['fk_id_sistema'], 'fk_id_nivel' => $_POST['fk_id_nivel']]);


            include ABSPATH . '/views/relat/simples.php';
        }

        require ABSPATH . '/views/_includes/footer.php';
    }

    public function temas() {
        $this->title = 'Sistema Principal';
        $this->requiredPage('princ/temas');
        $this->requireLogin();
        $model = $this->load_model('princ/princ-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/princ/temas.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function frame() {
        $this->title = 'Sistema Principal';
        $this->requiredPage('princ/frame');
        $this->requireLogin();
        $model = $this->load_model('princ/princ-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/princ/frame.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function teste() {
        //$this->title = 'Sistema Principal';
        //$this->requiredPage('princ/teste');
        //$this->requireLogin();
        $model = $this->load_model('princ/princ-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/princ/teste.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function migra() {
        $this->title = 'Sistema Principal';
        $this->requiredPage('princ/migra');
        $this->requireLogin();
        $model = $this->load_model('princ/princ-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/princ/migra.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function funcgrupo() {
        $this->title = 'Sistema Principal';
        $this->requiredPage('princ/funcgrupo');
        $this->requireLogin();
        $model = $this->load_model('princ/princ-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/princ/funcgrupo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function funcsis() {
        $this->title = 'Sistema Principal';
        $this->requiredPage('princ/funcsis');
        $this->requireLogin();
        $model = $this->load_model('princ/princ-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/princ/funcsis.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function correcoes() {
        $this->title = 'Sistema Principal';
        $this->requiredPage('princ/correcoes');
        $this->requireLogin();
        $model = $this->load_model('princ/princ-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/princ/correcoes.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
