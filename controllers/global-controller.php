<?php

class globalController extends MainController {

    public function index() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/index');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        /**
          if ($_SESSION['userdata']['n_nivel'] <> 'Diretor') {
          require_once ABSPATH . '/views/geral/inicio.php';
          } else {
          require_once ABSPATH . '/views/global/adi.php';
          }
         * 
         */
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function aval() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/aval');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/aval.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function lanca() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/index');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/lanca.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function lancap() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/lancap');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/lancap.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function lancae() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/lancae');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        $escola = 1;
        $id_inst = tool::id_inst();
        require_once ABSPATH . '/views/global/lanca.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function lancaa() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/lanca');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/global/lancaa.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function lancabanca() {
        $this->title='Avaliação Global';
        //$this->requiredPage('global/lanca');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/global/lancabanca.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function relat() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/relat');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/relat.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function docs() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/docs');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/docs.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function loco() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/loco');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/loco.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function locoa() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/loco');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/locoa.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function esc() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/esc');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/esc.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function prof() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/prof');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/prof.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function se() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/se');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/se.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function func() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/func');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/func.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function adi() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/adi');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/adicalc.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function especialista() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/especialista');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/especialista.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function prode() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/prode');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/prode.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function prod() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/prod');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/prod.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function prodpdf() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/prod');
        $this->requireLogin();
        $model = $this->load_model('global/global-model');
        require_once ABSPATH . '/views/global/prodpdf.php';
    }

    public function alupdf() {
        $this->title='Avaliação Global';
        $this->requireLogin();
        $model = $this->load_model('global/global-model');
        require_once ABSPATH . '/views/global/alupdf.php';
    }

    public function prodprof() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/prod');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require_once ABSPATH . '/views/global/_prod/prof.php';
    }

    public function grafesc() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/prod');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require_once ABSPATH . '/views/global/_prod/grafesc.php';
    }

    public function grafescc() {
        $this->title='Avaliação Global';
        $this->requiredPage('global/prod');
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require_once ABSPATH . '/views/global/_prod/grafescc.php';
    }

    public function profpdf() {
        $this->title='Avaliação Global';
        $this->requireLogin();

        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/profpdf.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function devolutiva() {
        $this->title='Avaliação Global';
        $this->requireLogin();
        $this->requiredPage('global/devolutiva');
        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/devolutiva.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function diag() {
        $this->title='Avaliação Global';
        $this->requireLogin();
        //$this->requiredPage('global/diag');
        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/diag.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function diaga() {
        $this->title='Avaliação Global';
        $this->requireLogin();
       // $this->requiredPage('global/diaga');
        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/diaga.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function habilidade() {
        $this->title='Avaliação Global';
        $this->requireLogin();
        $this->requiredPage('global/habilidade');
        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/habilidade.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function classeprof() {
        $this->title='Avaliação Global';
        $this->requireLogin();
        $this->requiredPage('global/classeprof');
        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/classeprof.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function resumo() {
        $this->title='Avaliação Global';
        $this->requireLogin();
        $this->requiredPage('global/index');
        $model = $this->load_model('global/global-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/global/resumo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function resumopdf() {
        $this->title='Avaliação Global';
        $this->requireLogin();
        $this->requiredPage('global/resumo');
        $model = $this->load_model('global/global-model');
        require_once ABSPATH . '/views/global/resumopdf.php';
    }

    public function projeto() {
        $this->title='Avaliação Global';
        $this->requireLogin();
        $this->requiredPage('global/projeto');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        $model = $this->load_model('global/global-model');
        require_once ABSPATH . '/views/global/projeto.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function projetoesc() {
        $this->title='Avaliação Global';
        $this->requireLogin();
        $this->requiredPage('global/projetoesc');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        $model = $this->load_model('global/global-model');
        require_once ABSPATH . '/views/global/projetoesc.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function projetoa() {
        $this->title='Avaliação Global';
        $this->requireLogin();
        $model = $this->load_model('global/global-model');
        require_once ABSPATH . '/views/global/_projeto/1.php';
    }

    public function banca() {
        $this->title='Avaliação Global';
        $this->requireLogin();
        $this->requiredPage('global/index');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        $model = $this->load_model('global/global-model');
        require_once ABSPATH . '/views/global/banca.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    public function devolprof() {
        $this->title='Avaliação Global';
        $this->requireLogin();
        $this->requiredPage('global/index');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        $model = $this->load_model('global/global-model');
        require_once ABSPATH . '/views/global/devolprof.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function aguarde() {
        ?>
        <div style="text-align: center; font-weight: bold; font-size: 28px">
            Aguarde...
        </div>
        <?php
    }
    

}
