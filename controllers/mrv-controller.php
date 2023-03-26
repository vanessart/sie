<?php

class mrvController extends MainController {

    public function index() {
        $this->title = 'MRV';
        $this->requiredPage('mrv/index');
        $this->requireLogin();

        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadastro() {
        $this->title = 'MRV';
        $this->requiredPage('mrv/cadastro');
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/mrv/cadastro.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function inscricao() {
        $this->title = 'MRV';
        $this->requiredPage('mrv/cadastro');
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/mrv/inscricao.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function selecaoatz() {
        $this->title = 'Selecionar';
        $this->requiredPage('mrv/selecaoatz');
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/mrv/selecaoatz.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function atzcadastro() {
        $this->title = 'Editar Dados do Aluno';
        $this->requiredPage('mrv/selecaoatz');
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/mrv/atzcadastro.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function atznotas() {
        $this->title = 'Editar Notas do Aluno';
        $this->requiredPage('mrv/selecaoatz');
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/mrv/atznotas.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function comprovmedia() {
        $this->title = 'Comprovante de Média';
        $this->requiredPage('mrv/comprovmedia');
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/mrv/comprovmedia.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function comprovmediapdf() {

        $this->title = 'Comprovante de Média';
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require_once ABSPATH . '/views/mrv/comprovmediapdf.php';
    }

    public function listadeferimento() {
        $this->title = 'Seleciona Classe';
        $this->requiredPage('mrv/listadeferimento');
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/mrv/listadeferimento.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function listadeferimentopdf() {

        $this->title = 'Lista Deferimento';
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require_once ABSPATH . '/views/mrv/listadeferimentopdf.php';
    }

    public function auditoria() {
        $this->title = 'Auditoria';
        $this->requiredPage('mrv/auditoria');
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/mrv/auditoria.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function pendenciapdf() {

        $this->title = 'Lista de Pendências';
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require_once ABSPATH . '/views/mrv/pendenciapdf.php';
    }

    public function teste() {
        $this->title = 'Auditoria';
        $this->requiredPage('mrv/teste');
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/mrv/teste.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function classificacaopdf() {

        $this->title = 'Classificação';
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require_once ABSPATH . '/views/mrv/classificacaopdf.php';
    }

    public function atzbanco() {
        $this->title = 'Acerto Banco';
        $this->requiredPage('mrv/atzbanco');
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/mrv/atzbanco.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function classificacaogeral() {
        $this->title = 'Classificação Geral';
        $this->requiredPage('mrv/classificacaogeral');
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/mrv/classificacaogeral.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function classificacaogeralpdf() {

        $this->title = 'Classificação Geral';
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require_once ABSPATH . '/views/mrv/classificacaogeralpdf.php';
    }

    public function classificacaogeralpdfr() {

        $this->title = 'Classificação Geral';
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require_once ABSPATH . '/views/mrv/classificacaogeralpdfr.php';
    }

    public function classificacaogeralRedepdf() {

        $this->title = 'Classificação Geral';
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require_once ABSPATH . '/views/mrv/classificacaogeralRedepdf.php';
    }
    public function indeferidospdf() {

        $this->title = 'Indeferidos';
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require_once ABSPATH . '/views/mrv/indeferidospdf.php';
    }

    public function deferidospdf() {

        $this->title = 'Deferidos';
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require_once ABSPATH . '/views/mrv/deferidospdf.php';
    }

    public function capapdf() {

        $this->title = 'Lista';
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require_once ABSPATH . '/views/mrv/capapdf.php';
    }

    public function fichaembrancopdf() {

        $this->title = 'Ficha em Branco';
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require_once ABSPATH . '/views/mrv/fichaembrancopdf.php';
    }

    public function fichainscricaopdf() {

        $this->title = 'Ficha de Inscrição';
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require_once ABSPATH . '/views/mrv/fichainscricaopdf.php';
    }

    public function listacontrolepdf() {

        $this->title = 'Controle de Entrega';
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require_once ABSPATH . '/views/mrv/listacontrolepdf.php';
    }

    public function relatoriorede() {
        $this->title = 'Relatórios';
        $this->requiredPage('mrv/relatoriorede');
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/mrv/relatoriorede.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function relatorioredepdf() {

        $this->title = 'Relatório Situação';
        $this->requireLogin();

        $model = $this->load_model('mrv/mrv-model');
        require_once ABSPATH . '/views/mrv/relatorioredepdf.php';
    }

}
