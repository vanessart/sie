<?php

class suporteController extends MainController {

    // URL: dominio.com/exemplo/
    public function index() {
// Título da página
        $this->title='Suporte Técnico';
        $this->requiredPage('suporte/index');

        $this->requireLogin();
        // Carrega o modelo
        $model = $this->load_model('suporte/suporte-model');
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
        // Carrega o view
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function inst() {
// Título da página
        $this->title = 'Suporte Técnico';
        $this->requiredPage('suporte/inst');

        $this->requireLogin();
        // Carrega o modelo
        $model = $this->load_model('suporte/suporte-model');
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
        // Carrega o view
        require_once ABSPATH . '/views/suporte/inst.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

     public function func() {
// Título da página
        $this->title = 'Suporte Técnico';
        $this->requiredPage('suporte/func');

        $this->requireLogin();
        // Carrega o modelo
        $model = $this->load_model('suporte/suporte-model');
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';
        // Carrega o view
        require_once ABSPATH . '/views/suporte/func.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

 

}
