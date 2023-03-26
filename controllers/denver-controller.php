<?php

class denverController extends MainController {

    public function index() {

        $this->title = 'Home';
        $this->requiredPage('denver/index');
        $this->requireLogin();

        // Nível 24 = Professor

        if ($_SESSION['userdata']['id_nivel'] == 24) {
            $model = $this->load_model('denver/denver-model');
            require ABSPATH . '/views/_includes/header.php';
            require ABSPATH . '/views/_includes/menu.php';
            $id_pessoa = tool::id_pessoa();
            require_once ABSPATH . '/views/denver/lancahab.php';
            require ABSPATH . '/views/_includes/footer.php';
        } else {
            $model = $this->load_model('gestao/gestao-model');
            require ABSPATH . '/views/_includes/header.php';
            require ABSPATH . '/views/_includes/menu.php';
            require_once ABSPATH . '/views/geral/inicio.php';
            require ABSPATH . '/views/_includes/footer.php';
        }
    }

    public function lancahab() {

        $this->title = 'Habilidades';
        $this->requiredPage('denver/lancahab');
        $this->requireLogin();

        $model = $this->load_model('denver/denver-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/denver/lancahab.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function lancahabclasse() {

        $this->title = 'Selecionar Aluno';
        $this->requiredPage('denver/index');
        $this->requireLogin();

        $model = $this->load_model('denver/denver-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/denver/lancahabclasse.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function lancahabdig() {

        $this->title = 'Registro de Habilidades';
        $this->requiredPage('denver/index');
        $this->requireLogin();

        $model = $this->load_model('denver/denver-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/denver/lancahabdig.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function registrar() {

        $this->title = 'Digitação';
        $this->requiredPage('denver/index');
        $this->requireLogin();

        $model = $this->load_model('denver/denver-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/denver/registrar.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
