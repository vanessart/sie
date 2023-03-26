<?php

class gdaeController extends MainController {

    public function index() {
        $this->title='GDAE';
        $this->requiredPage('gdae/index');
        $this->requireLogin();

        $model = $this->load_model('gdae/gdae-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
      public function import() {
        $this->title='GDAE';
        $this->requiredPage('gdae/import');
        $this->requireLogin();

        $model = $this->load_model('gdae/gdae-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gdae/import.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
     
      public function rest() {
        $this->title='GDAE';
        $this->requiredPage('gdae/rest');
        $this->requireLogin();

        $model = $this->load_model('gdae/gdae-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gdae/rest.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
        public function importClasses() {
        $this->title='GDAE';
        $this->requiredPage('gdae/importClasses');
        $this->requireLogin();

        $model = $this->load_model('gdae/gdae-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gdae/importClasses.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    
        public function atualizarclasseyy() {

        $model = $this->load_model('gdae/gdae-model');
        //require_once ABSPATH . '/views/gdae/atualizarclasse.php';
    }
    
        
        public function atualizarpessoa() {

        $model = $this->load_model('gdae/gdae-model');
        require_once ABSPATH . '/views/gdae/atualizarpessoa.php';
    }
    
    



}
