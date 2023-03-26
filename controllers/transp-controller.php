<?php

class transpController extends MainController {

    public function index() {
        $this->title = 'Transporte';
        $this->requiredPage('transp/index');
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadveiculo() {
        $this->title = 'Transporte';
        $this->requiredPage('transp/cadveiculo');
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/transp/cadveiculo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadlinha() {
        $this->title = 'Transporte';
        $this->requiredPage('transp/cadveiculo');
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/transp/cadlinha.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function aloca() {
        $this->title = 'Transporte';
        $this->requiredPage('transp/aloca');
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/transp/aloca.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function troca() {
        $this->title = 'Transporte';
        $this->requiredPage('transp/troca');
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/transp/troca.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function deferir() {
        $this->title = 'Transporte';
        $this->requiredPage('transp/deferir');
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/transp/deferir.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function ver() {
        $this->title = 'Transporte';
        //$this->requiredPage('transp/ver');
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/transp/ver.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function veronibus() {
        $this->title = 'Transporte';
        //$this->requiredPage('transp/ver');
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/transp/veronibus.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function listabranca() {
        $this->title = 'Transporte';
        $this->requiredPage('transp/listabranca');
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/transp/listabranca.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function setup() {
        $this->title = 'Transporte';
        $this->requiredPage('transp/setup');
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/transp/setup.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function aluno() {
        $this->title = 'Transporte';
        $this->requiredPage('transp/aluno');
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/transp/aluno.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function falta() {
        $this->title = 'Transporte';
        $this->requiredPage('transp/falta');
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/transp/falta.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function selrelatorio() {
        $this->title = 'Transporte';
        $this->requiredPage('transp/selrelatorio');
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/transp/selrelatorio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function listaalunopdf() {

        $this->title = 'Transporte';
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require_once ABSPATH . '/views/transp/listaalunopdf.php';
    }

    public function selrelatoriorede() {
        $this->title = 'Transporte';
        $this->requiredPage('transp/selrelatoriorede');
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/transp/selrelatoriorede.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function movimentacaopdf() {

        $this->title = 'Transporte';
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require_once ABSPATH . '/views/transp/movimentacaopdf.php';
    }

    public function transportadospdf() {

        $this->title = 'Transporte';
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require_once ABSPATH . '/views/transp/transportadospdf.php';
    }

    public function movimentacaoalunopdf() {

        $this->title = 'Transporte';
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require_once ABSPATH . '/views/transp/movimentacaoalunopdf.php';
    }

    public function frequenciapdf() {

        $this->title = 'Transporte';
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require_once ABSPATH . '/views/transp/frequenciapdf.php';
    }

    public function alunoPlan() {
        require_once ABSPATH . '/views/transp/alunoPlan.php';
    }

    public function controlegeralpdf() {

        $this->title = 'Transporte';
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require_once ABSPATH . '/views/transp/controlegeralpdf.php';
    }

    public function listageralalunopdf() {

        $this->title = 'Transporte';
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require_once ABSPATH . '/views/transp/listageralalunopdf.php';
    }

    public function selterceirizado() {
        $this->title = 'Transporte';
        $this->requiredPage('transp/selterceirizado');
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/transp/selterceirizado.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function tranpsecundariopdf() {

        $this->title = 'Transporte';
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require_once ABSPATH . '/views/transp/tranpsecundariopdf.php';
    }

    public function empresa() {
        $this->title = 'Transporte';
        $this->requiredPage('transp/empresa');
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/transp/empresa.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function selrelveiculo() {
        $this->title = 'Transporte';
        $this->requiredPage('transp/selrelveiculo');
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/transp/selrelveiculo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadadaptado() {
        $this->title = 'Transporte';
        $this->requiredPage('transp/cadadaptado');
        $this->requireLogin();
        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/transp/cadadaptado.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function alocaadaptado() {
        $this->title = 'Transporte';
        $this->requiredPage('transp/alocaadaptado');
        $this->requireLogin();
        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        if (empty($_POST['info'])) {
            require ABSPATH . '/views/_includes/menu.php';
            require_once ABSPATH . '/views/transp/alocaadaptado.php';
        } else {
            require_once ABSPATH . '/views/transp/_alocaadaptado/info.php';
        }
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function protocolo() {
        $this->title = '';
        $this->requireLogin();
        $model = $this->load_model('transp/transp-model');
        require_once ABSPATH . '/views/transp/protocolo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function selrelatorioadap() {

        $this->title = 'Transporte';
        $this->requiredPage('transp/selrelatorioadap');
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/transp/selrelatorioadap.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function listaalunoadappdf() {

        $this->title = 'Transporte';
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require_once ABSPATH . '/views/transp/listaalunoadappdf.php';
    }

    public function frequenciapdfbranco() {

        $this->title = 'Transporte';
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require_once ABSPATH . '/views/transp/frequenciapdfbranco.php';
    }

    public function faltaAnterior() {
        $this->title = 'Transporte';
        $this->requiredPage('transp/faltaAnterior');
        $this->requireLogin();
        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/transp/faltaAnterior.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function carteirinha() {
        $this->title = 'Transporte';
        $this->requiredPage('transp/carteirinha');
        $this->requireLogin();
        $model = $this->load_model('transp/transp-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/transp/carteirinha.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function pdfcrachatransp() {

        $this->title = 'Transporte';
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require_once ABSPATH . '/views/transp/pdfcrachatransp.php';
    }

####################   Mario    ################

    public function linhapdf() {

        $this->title = 'Transporte';
        $this->requireLogin();

        $model = $this->load_model('transp/transp-model');
        require_once ABSPATH . '/views/transp/linhapdf.php';
    }

################################################
}
