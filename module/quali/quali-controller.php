<?php

class qualiController extends mainController {

    public $db;
    public $controller;

    public function index() {
        $this->title = SYSNAME;
        $this->requireLogin();
        $this->requiredPage('/quali/index');
        $model = $this->load_model('/module/quali/quali-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        if (tool::id_nilvel() == 13) {
            require ABSPATH . '/module/quali/views/gerente.php';
        } else {
            require ABSPATH . '/includes/views/geral.php';
        }
        require ABSPATH . '/includes/structure/footer.php';
    }

    /**
     * paginas dinamicas ex: HOME_URI . '/gt/def/cadprof.php'
     */
    public function def() {
        $_data = func_get_args()[0];
        $this->title = SYSNAME;
        $this->requireLogin();
       // $this->requiredPage('/' . $_data[0] . '/def/' . implode('/', array_slice($_data, 2)));
        $model = $this->load_model('/module/' . $_data[0] . '/' . $_data[0] . '-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/' . $_data[0] . '/views/def/' . implode('/', array_slice($_data, 2));
        require ABSPATH . '/includes/structure/footer.php';
    }

    /**
     * paginas dinamicas ex: HOME_URI . '/gt/def/cadprof.php'
     */
    public function pdf() {
        $_data = func_get_args()[0];
        $this->title = SYSNAME;
        $this->requireLogin();
        $this->requiredPage('/' . $_data[0] . '/pdf/' . implode('/', array_slice($_data, 2)));
        $model = $this->load_model('/module/' . $_data[0] . '/' . $_data[0] . '-model');
        require_once ABSPATH . '/module/' . $_data[0] . '/views/pdf/' . implode('/', array_slice($_data, 2));
    }

        public function api() {
        $_api = func_get_args()[0];
        $this->title = SYSNAME;
        $this->requireLogin();
        $model = $this->load_model('/module/quali/quali-model');
        require_once ABSPATH . '/module/quali/views/api/' . implode('/', array_slice($_api, 2)).'.php';
    }
    
    public function inscr() {
        $_data = func_get_args()[0];
        $this->title = SYSNAME;
        $model = $this->load_model('/module/quali/quali-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/module/quali/views/inscr.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function inscrSet() {
        $this->title = SYSNAME;
        $this->requireLogin();
        $this->requiredPage('/quali/inscrSet');
        $model = $this->load_model('/module/quali/quali-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require ABSPATH . '/module/quali/views/inscrSet.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function export() {
        $this->title = SYSNAME;
        $this->requireLogin();
        $this->requiredPage('/quali/export');
        $model = $this->load_model('/module/quali/quali-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require ABSPATH . '/module/quali/views/export.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    public function exportarMatric() {
        $this->title = SYSNAME;
        $this->requireLogin();
        $this->requiredPage('/quali/exportarMatric');
        $model = $this->load_model('/module/curso/curso-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/curso/views/exportar.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    
    public function defere() {
        $this->title = SYSNAME;
        $this->requireLogin();
        $this->requiredPage('/quali/defere');
        $model = $this->load_model('/module/quali/quali-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require ABSPATH . '/module/quali/views/defere.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    
    public function deferido() {
        $this->title = SYSNAME;
        $this->requireLogin();
        $this->requiredPage('/quali/deferido');
        $model = $this->load_model('/module/quali/quali-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require ABSPATH . '/module/quali/views/deferido.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    
    public function relatorio() {
        $this->title = SYSNAME;
        $this->requireLogin();
        $this->requiredPage('/quali/relatorio');
        $model = $this->load_model('/module/quali/quali-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require ABSPATH . '/module/quali/views/relatorio.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function exportar() {
        $this->title = SYSNAME;
        $this->requireLogin();
        $this->requiredPage('/quali/defere');
        require ABSPATH . '/module/quali/views/exportar.php';
    }

        
    public function todosPart() {
        $this->title = SYSNAME;
        $this->requireLogin();
        $this->requiredPage('/quali/defere');
        $model = $this->load_model('/module/quali/quali-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require ABSPATH . '/module/quali/views/todosPart.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    
}
