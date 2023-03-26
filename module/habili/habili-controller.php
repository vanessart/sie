<?php

class habiliController extends MainController {

    public function index() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('habili/index');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVert.php';
            require_once ABSPATH . '/module/profe/views/main.php';
        } elseif (toolErp::id_nilvel() == 8) {
            require ABSPATH . '/includes/structure/menu.php';
            require_once ABSPATH . '/module/profe/views/mainAdm.php';
        } elseif (toolErp::id_nilvel() == 48) {
            require ABSPATH . '/includes/structure/menu.php';
            require_once ABSPATH . '/module/profe/views/mainCoord.php';
        } elseif (in_array(toolErp::id_nilvel(), [2, 10])) {
            require ABSPATH . '/includes/structure/menu.php';
            require_once ABSPATH . '/module/profe/views/adm.php';
        }
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function grupo() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('habili/grupo');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/habili/views/grupo.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function habCurso() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('habili/habCurso');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/habili/views/habCurso.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function objtCon() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('habili/objtCon');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/habili/views/objtCon.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function unidTema() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('habili/unidTema');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/habili/views/unidTema.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function habil() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('habili/habil');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/habili/views/habil.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function campExp() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('habili/campExp');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/habili/views/campExp.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'Diário de Classe';
        $this->requireLogin();
        $this->requiredPage('habili/index');
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        require_once ABSPATH . '/module/habili/views/def/' . $_data;
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pdf() {
        $_data = func_get_args()[0][0];
        $this->title = 'Diário de Classe';
        $this->requireLogin();
        $model = $this->load_model('/habili/habili-model');
        require_once ABSPATH . '/module/habili/views/pdf/' . $_data;
    }

    public function planoAula() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('habili/planoAula');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVert.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/habili/views/planoAula.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function planoCoord() {
        $this->title = 'Diário de Classe';
        //$this->requiredPage('habili/planoAula');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/habili/views/planoCoord.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function plano() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('habili/planoAula');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
          if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVert.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/habili/views/plano.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

}
