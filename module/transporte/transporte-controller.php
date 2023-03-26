<?php

class transporteController extends MainController {

    public function index() {
        $this->title = 'Transporte';
        $this->requiredPage('transporte/index');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        if (user::session('id_nivel') == 8) {
            require_once ABSPATH . '/module/transporte/index.php';
        } else {
            require_once ABSPATH . '/views/geral/inicio.php';
        }
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function cadveiculo() {
        $this->title = 'Transporte';
        $this->requiredPage('transporte/cadveiculo');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/transporte/views/cadveiculo.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function cadlinha() {
        $this->title = 'Transporte';
        $this->requiredPage('transporte/cadveiculo');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/transporte/views/cadlinha.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function aloca() {
        $this->title = 'Transporte';
        $this->requiredPage('transporte/aloca');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/transporte/views/aloca.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function troca() {
        $this->title = 'Transporte';
        $this->requiredPage('transporte/troca');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/transporte/views/troca.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function deferir() {
        $this->title = 'Transporte';
        $this->requiredPage('transporte/deferir');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/transporte/views/deferir.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function ver() {
        $this->title = 'Transporte';
        //$this->requiredPage('transporte/ver');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/transporte/views/ver.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function veronibus() {
        $this->title = 'Transporte';
        //$this->requiredPage('transporte/ver');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/transporte/views/veronibus.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function listabranca() {
        $this->title = 'Transporte';
        $this->requiredPage('transporte/listabranca');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/transporte/views/listabranca.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function setup() {
        $this->title = 'Transporte';
        $this->requiredPage('transporte/setup');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/transporte/views/setup.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function aluno() {
        $this->title = 'Transporte';
        $this->requiredPage('transporte/aluno');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/transporte/views/aluno.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function falta() {
        $this->title = 'Transporte';
        $this->requiredPage('transporte/falta');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/transporte/views/falta.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function selrelatorio() {
        $this->title = 'Seleciona Lista Escola';
        $this->requiredPage('transporte/selrelatorio');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/transporte/views/selrelatorio.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function listaalunopdf() {

        $this->title = 'Lista de Alunos';
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/listaalunopdf.php';
    }

    public function selrelatoriorede() {
        $this->title = 'Seleciona Lista Rede';
        $this->requiredPage('transporte/selrelatoriorede');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/transporte/views/selrelatoriorede.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function selrelatorioespera() {
        $this->title = 'Seleciona Lista Rede';
        $this->requiredPage('transporte/selrelatorioespera');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/transporte/views/selrelatorioespera.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function movimentacaopdf() {

        $this->title = 'Movimentação Geral';
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/movimentacaopdf.php';
    }

    public function transportadospdf() {

        $this->title = 'Alunos Transportados';
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/transportadospdf.php';
    }

    public function movimentacaoalunopdf() {

        $this->title = 'Movimentação Exclusão/Inclusão';
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/movimentacaoalunopdf.php';
    }

    public function transpexcluido() {

        $this->title = 'Movimentação Exclusão';
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/transpexcluido.php';
    }

    public function transpincluido() {

        $this->title = 'Movimentação Inclusão';
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/transpincluido.php';
    }

    public function frequenciapdf() {

        $this->title = 'Controle de Frequência';
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/frequenciapdf.php';
    }

    public function alunoPlan() {
        require_once ABSPATH . '/module/transporte/views/alunoPlan.php';
    }

    public function controlegeralpdf() {

        $this->title = 'Controle Geral de Alunos';
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/controlegeralpdf.php';
    }

    public function listageralalunopdf() {

        $this->title = 'Lista Geral de Alunos';
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/listageralalunopdf.php';
    }

    public function listageralaluno() {

        $this->title = 'Lista Geral de Alunos (Excel)';
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/listageralaluno.php';
    }

    public function selterceirizado() {
        $this->title = 'Seleciona Lista Escola';
        $this->requiredPage('transporte/selterceirizado');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/transporte/views/selterceirizado.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function tranpsecundariopdf() {

        $this->title = 'Transporte Secundário';
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/tranpsecundariopdf.php';
    }

    public function empresa() {
        $this->title = 'Empresa';
        $this->requiredPage('transporte/empresa');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/transporte/views/empresa.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function selrelveiculo() {
        $this->title = 'Empresa';
        $this->requiredPage('transporte/selrelveiculo');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/transporte/views/selrelveiculo.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function cadadaptado() {
        $this->title = 'Adaptado';
        $this->requiredPage('transporte/cadadaptado');
        $this->requireLogin();
        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/transporte/views/cadadaptado.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function alocaadaptado() {
        $this->title = 'Adaptado';
        $this->requiredPage('transporte/alocaadaptado');
        $this->requireLogin();
        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        if (empty($_POST['info'])) {
            require ABSPATH . '/includes/structure/menu.php';
            require_once ABSPATH . '/module/transporte/views/alocaadaptado.php';
        } else {
            require_once ABSPATH . '/module/transporte/views/_alocaadaptado/info.php';
        }
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function protocolo() {
        $this->title = '';
        $this->requireLogin();
        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/protocolo.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function selrelatorioadap() {

        $this->title = 'Seleciona Lista Escola - Adaptado';
        $this->requiredPage('transporte/selrelatorioadap');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/transporte/views/selrelatorioadap.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function listaalunoadappdf() {

        $this->title = 'Lista Alunos - Adaptado';
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/listaalunoadappdf.php';
    }

    public function frequenciapdfbranco() {

        $this->title = 'Controle de Frequência';
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/frequenciapdfbranco.php';
    }

    public function faltaAnterior() {
        $this->title = 'Mês Anterior';
        $this->requiredPage('transporte/faltaAnterior');
        $this->requireLogin();
        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/transporte/views/faltaAnterior.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    public function carteirinha() {
        $this->title = 'Carteirinha';
        $this->requiredPage('transporte/carteirinha');
        $this->requireLogin();
        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/transporte/views/carteirinha.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    
    public function pdfcrachatransp() {

        $this->title = 'Crachá Ônibus';
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/pdfcrachatransp.php';
    }

    public function cadveiculomodal() {
        $this->title = 'Transporte';
        $this->requiredPage('transporte/cadveiculo');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/transporte/views/_modal/cadveiculo.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function cadlinhamodal() {
        $this->title = 'Transporte';
        $this->requiredPage('transporte/cadlinha');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/transporte/views/_modal/cadlinha.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function empresamodal() {
        $this->title = 'Empresa';
        $this->requiredPage('transporte/empresa');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/transporte/views/_modal/empresa.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function listabrancamodal() {
        $this->title = 'Transporte';
        $this->requiredPage('transporte/listabranca');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/transporte/views/_modal/listabranca.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function mudaStatusMult() {
        $this->title = 'Home';
        $model = $this->load_model('transporte/transporte-model');
        $valores = $model->mudaStatusMult();
    }

    public function alocamodal() {
        $this->title = 'Transporte';
        $this->requiredPage('transporte/aloca');
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/transporte/views/_modal/aloca.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function esperapdf() {
        $this->title = 'Lista de Espera';
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/esperapdf.php';
    }

    public function esperaemail() {
        $this->title = 'Lista de Espera';
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/esperaemail.php';
    }

    public function linhasviagenspdf() {
        $this->title = 'Linhas / Viagens';
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/linhasviagenspdf.php';
    }

    public function linhasviagens() {
        $this->title = 'Linhas / Viagens (Excel)';
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/linhasviagens.php';
    }

    public function esperaexcel() {
        $this->title = 'Lista de Espera';
        $this->requireLogin();

        $model = $this->load_model('transporte/transporte-model');
        require_once ABSPATH . '/module/transporte/views/esperaexcel.php';
    }
}
