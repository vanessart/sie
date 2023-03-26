<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of super--controller
 *
 * @author marco
 */
class superController extends MainController {
//put your code here

    /**
     * $login_required
     *
     * Se a página precisa de login
     *
     * @access public
     */
    public $login_required = false;

    /**
     * $permission_required
     *
     * Permissão necessária
     *
     * @access public
     */
    public $permission_required;

    public function index() {

        $this->title='Supervisor';
        $this->requiredPage('super/index');
        $this->requireLogin();

        $model = $this->load_model('super/super-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

         public function escola() {
        $this->title = 'Suporte';
        $this->login_required;
        $model = $this->load_model('super/super-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/super/escola.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    
    public function suporte() {
        $this->title = 'DTTIE - Suporte';
        $this->requireLogin();
        $model = $this->load_model('super/super-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/super/suporte.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function escolapesq() {
        $this->title = 'Suporte';
        $this->login_required;
        $model = $this->load_model('super/super-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/super/escolapesq.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
        public function suportepesq() {
        $this->title = 'DTTIE';
        $this->requireLogin();
        $model = $this->load_model('super/super-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/super/suportepesq.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    ##########################################################################
    //controle do birô
    public function biro() {
        // Título da página
        $this->title = 'DTTIE - Birô';
        $this->requiredPage('super/biro');
        // Carrega o modelo para este view
        $pessoas = new pessoas();
        $model = $this->load_model('super/super-model');
        $instancias = new instancias();
        /** Carrega os arquivos do view * */
        // /views/_includes/header.php
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';

        // /views/noticias/index.php
        require ABSPATH . '/views/super/super-biro-view.php';

        // /views/_includes/footer.php
        require ABSPATH . '/views/_includes/footer.php';
    }

    //controle do birô
    public function pesq() {
        // Título da página
        $this->title = 'DTTIE - Birô';
        $this->requiredPage('super/biro');
        // Carrega o modelo para este view
        $pessoas = new pessoas();
        $model = $this->load_model('super/super-model');
        $instancias = new instancias();
        /** Carrega os arquivos do view * */
        // /views/_includes/header.php
        // /views/_includes/menu.php
        // /views/noticias/index.php
        require ABSPATH . '/views/super/super-pesq.php';

        // /views/_includes/footer.php
        require ABSPATH . '/views/_includes/footer.php';
    }


    //cadastra
    public function cad() {
        // Título da página
        $this->title = 'DTTIE';
        $this->requiredPage('super/cad');
        // Carrega o modelo para este view
        $model = $this->load_model('super/super-model');

        /** Carrega os arquivos do view * */
        // /views/_includes/header.php
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';

        // /views/noticias/index.php
        require ABSPATH . '/views/general/cad-view.php';

        // /views/_includes/footer.php
        require ABSPATH . '/views/_includes/footer.php';
    }

    //super-biro_trab
    public function birotrab() {
        // Título da página
        $this->title = 'DTTIE';
        $this->requiredPage('super/birotrab');
        // Carrega o modelo para este view
        $model = $this->load_model('super/super-model');

        /** Carrega os arquivos do view * */
        // /views/_includes/header.php
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';

        // /views/noticias/index.php
        require ABSPATH . '/views/super/super-birotrab.php';

        // /views/_includes/footer.php
        require ABSPATH . '/views/_includes/footer.php';
    }



    public function resptec() {
        $this->title = 'Suporte';
        $this->requiredPage('super/resptec');
        // Carrega o modelo para este view
        $model = $this->load_model('super/super-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/super/resptec.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function supprot() {
        $this->title = 'Suporte';
        require ABSPATH . '/views/super/supprot.php';
    }

    public function webprot() {
        $this->title = 'Suporte';
        $model = $this->load_model('super/super-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/super/webprot.php';
        require ABSPATH . '/views/_includes/footer.php';
    }


    public function secretaria() {
        $this->title = 'Suporte';
        $this->requiredPage('super/secretaria');
        $model = $this->load_model('super/super-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/super/secretaria.php';
        require ABSPATH . '/views/_includes/footer.php';
    }


    public function depto() {
        $this->title = 'Suporte';
        $this->requiredPage('super/depto');
        $model = $this->load_model('super/super-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/super/depto.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function deppesq() {
        $this->title = 'Suporte';
        $this->requiredPage('super/deppesq');
        $model = $this->load_model('super/super-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/super/deppesq.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function email() {
        $this->title = 'Suporte';
        $this->requireLogin();
        $model = $this->load_model('super/super-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/super/email.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
