<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of dttie--controller
 *
 * @author marco
 */
class dttieController extends MainController {
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

        $this->title = 'DTTIE - Suporte';
        $this->requiredPage('dttie/index');
        $this->requireLogin();

        $model = $this->load_model('dttie/dttie-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function escola() {
        $this->title = 'DTTIE - Suporte';
        $this->login_required;
        $model = $this->load_model('dttie/dttie-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/dttie/escola.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function suporte() {
        $this->title = 'DTTIE - Suporte';
        $this->requireLogin();
        $model = $this->load_model('dttie/dttie-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/dttie/suporte.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function escolapesq() {
        $this->title = 'DTTIE - Suporte';
        $this->login_required;
        $model = $this->load_model('dttie/dttie-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/dttie/escolapesq.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function suportepesq() {
        $this->title = 'DTTIE - Suporte';
        $this->requireLogin();
        $model = $this->load_model('dttie/dttie-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/dttie/suportepesq.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function suportetel() {
        $this->title = 'DTTIE - Suporte';
        $this->requireLogin();
        $model = $this->load_model('dttie/dttie-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/dttie/suportetel.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    ##########################################################################

    //controle do birô

    public function biro() {
        // Título da página
        $this->title = 'DTTIE - Suporte';
        $this->requiredPage('dttie/biro');
        // Carrega o modelo para este view
        $pessoas = new pessoas();
        $model = $this->load_model('dttie/dttie-model');
        $instancias = new instancias();
        /** Carrega os arquivos do view * */
        // /views/_includes/header.php
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';

        // /views/noticias/index.php
        require ABSPATH . '/views/dttie/dttie-biro-view.php';

        // /views/_includes/footer.php
        require ABSPATH . '/views/_includes/footer.php';
    }

    //controle do birô
    public function pesq() {
        // Título da página
        $this->title = 'DTTIE - Suporte';
        $this->requiredPage('dttie/biro');
        // Carrega o modelo para este view
        $pessoas = new pessoas();
        $model = $this->load_model('dttie/dttie-model');
        $instancias = new instancias();
        /** Carrega os arquivos do view * */
        // /views/_includes/header.php
        // /views/_includes/menu.php
        // /views/noticias/index.php
        require ABSPATH . '/views/dttie/dttie-pesq.php';

        // /views/_includes/footer.php
        require ABSPATH . '/views/_includes/footer.php';
    }

    //cadastra
    public function cad() {
        // Título da página
        $this->title = 'DTTIE - Suporte';
        $this->requiredPage('dttie/cad');
        // Carrega o modelo para este view
        $model = $this->load_model('dttie/dttie-model');

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

    //dttie-biro_trab
    public function birotrab() {
        // Título da página
        $this->title = 'DTTIE - Suporte';
        $this->requiredPage('dttie/birotrab');
        // Carrega o modelo para este view
        $model = $this->load_model('dttie/dttie-model');

        /** Carrega os arquivos do view * */
        // /views/_includes/header.php
        require ABSPATH . '/views/_includes/header.php';

        // /views/_includes/menu.php
        require ABSPATH . '/views/_includes/menu.php';

        // /views/noticias/index.php
        require ABSPATH . '/views/dttie/dttie-birotrab.php';

        // /views/_includes/footer.php
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function resptec() {
        $this->title = 'DTTIE - Suporte';
        $this->requiredPage('dttie/resptec');
        // Carrega o modelo para este view
        $model = $this->load_model('dttie/dttie-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/dttie/resptec.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function supprot() {
        $this->title = 'DTTIE - Suporte';
        $model = $this->load_model('dttie/dttie-model');
        require ABSPATH . '/views/dttie/supprot.php';
    }

    public function webprot() {
        $this->title = 'DTTIE - Suporte';
        $model = $this->load_model('dttie/dttie-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/dttie/webprot.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function secretaria() {
        $this->title = 'DTTIE - Suporte';
        $this->requiredPage('dttie/secretaria');
        $model = $this->load_model('dttie/dttie-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/dttie/secretaria.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function depto() {
        $this->title = 'DTTIE - Suporte';
        $this->requiredPage('dttie/depto');
        $model = $this->load_model('dttie/dttie-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/dttie/depto.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function deppesq() {
        $this->title = 'DTTIE - Suporte';
        $this->requiredPage('dttie/deppesq');
        $model = $this->load_model('dttie/dttie-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/dttie/deppesq.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function email() {
        $this->title = 'DTTIE - Suporte';
        $this->requireLogin();
        $model = $this->load_model('dttie/dttie-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/dttie/email.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function alunoconectado() {
        $this->title = 'DTTIE - Suporte';
        $this->requiredPage('dttie/alunoconectado');
        $model = $this->load_model('dttie/dttie-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/dttie/alunoconectado.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function consultachamado() {
        $this->title = 'DTTIE - Suporte';
        $this->requiredPage('dttie/consultachamado');
        $model = $this->load_model('dttie/dttie-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/dttie/consultachamado.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function pdfchamado() {
        $this->title = 'DTTIE - Suporte';
        $model = $this->load_model('dttie/dttie-model');
        require ABSPATH . '/views/dttie/pdfchamado.php';
    }

}
