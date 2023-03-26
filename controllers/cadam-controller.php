<?php

class cadamController extends MainController {

    public function index() {
        $this->title = 'CADAMPE';
        $this->requiredPage('cadam/index');
        $this->requireLogin();

        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function controle() {
        $this->title = 'CADAMPE';
        $this->requiredPage('cadam/controle');
        $this->requireLogin();

        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/cadam/controle.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function freq() {
        $this->title = 'DTGP';
        $this->requiredPage('cadam/freq');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/cadam/freq.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function freqa() {
        $this->title = 'DTGP';
        $this->requiredPage('cadam/freq');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/cadam/_freq/1.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function freqmult() {
        $this->title = 'DTGP';
        $this->requiredPage('cadam/freq');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/cadam/freqmult.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function freqmultform() {
        $this->title = 'DTGP';
        $this->requiredPage('cadam/freq');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/cadam/_freqmult/1.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function continuo() {
        $this->title = 'DTGP';
        $this->requiredPage('cadam/continuo');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/cadam/continuo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function continuoa() {
        $this->title = 'DTGP';
        $this->requiredPage('cadam/continuo');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/cadam/_continuo/1.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    ###########################################################################

    public function cadastro() {
        $this->title = 'CADAMPE';
        $this->requiredPage('cadam/cadastro');
        $this->requireLogin();

        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/cadam/cadastro.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadastrosup() {
        $this->title = 'CADAMPE';
        $this->requiredPage('cadam/cadastrosup');
        $this->requireLogin();

        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/cadam/cadastrosup.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function set() {
        $this->title = 'CADAMPE';
        $this->requiredPage('cadam/cadastro');
        $this->requireLogin();

        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/cadam/set.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function comprovante() {
        $this->title = 'DTGP';
        $this->requireLogin();
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/cadam/comprovante.php';
    }

    public function acumulo() {
        $this->title = 'Acumulo';
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/cadam/acumulo.php';
    }

    public function ficha() {
        $this->title = 'Ficha';
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/cadam/ficha.php';
    }

    public function termo() {
        $this->title = 'Termo';
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/cadam/termo.php';
    }

    public function seletivas() {
        $this->title = 'CADAMPE';
        $this->requiredPage('cadam/seletivas');
        $this->requireLogin();

        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/cadam/seletivas.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function banco() {
        $this->title = 'Banco';
        $this->requiredPage('cadam/banco');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/cadam/banco.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function profesc() {
        $this->title = 'DTGP';
        $this->requiredPage('cadam/profesc');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/cadam/profesc.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function relat() {
        $this->title = 'DTGP';
        $this->requiredPage('cadam/relat');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/cadam/relat.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function relate() {
        $this->title = 'DTGP';
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/cadam/relate.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function tea() {
        $this->title = 'DTGP';
        $this->requireLogin();
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/cadam/tea.php';
    }

    public function cons() {
        $this->title = 'DTGP';
        $this->requireLogin();
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/cadam/cons.php';
    }

    public function consu() {
        $this->title = 'DTGP';
        $this->requireLogin();
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/cadam/consu.php';
    }

    public function constt() {
        $this->title = 'DTGP';
        $this->requireLogin();
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/cadam/constt.php';
    }

    public function lista() {
        $this->title = 'DTGP';
        $this->requireLogin();
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/cadam/lista.php';
    }

    public function freqtea() {
        $this->title = 'DTGP';
        $this->requiredPage('cadam/freqtea');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/cadam/freqtea.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function fichaf() {
        $this->title = 'DTGP';
        $this->requiredPage('cadam/fichaf');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/cadam/fichaf.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function fichapdf() {
        $this->title = 'DTGP';
        $this->requiredPage('cadam/fichaf');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/cadam/fichapdf.php';
    }

    public function extrato() {
        $this->title = 'DTGP';
        $this->requiredPage('cadam/fichaf');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/cadam/extrato.php';
    }

    public function pesqextrato() {
        $this->title = 'DTGP';
        $this->requiredPage('cadam/pesqextrato');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/cadam/pesqextrato.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function valores() {
        $this->title = 'DTGP';
        $this->requiredPage('cadam/valores');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/cadam/valores.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function senha() {
        $this->title = 'DTGP';
        $this->requiredPage('cadam/senha');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/cadam/senha.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function motivo() {
        $this->title = 'DTGP';
        $this->requiredPage('cadam/motivo');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/cadam/motivo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function relatconv() {
        $this->title = 'DTGP';
        $this->requiredPage('cadam/relatconv');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/cadam/relatconv.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function relatsup() {
        $this->title = 'DTGP';
        $this->requiredPage('cadam/relatsup');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/cadam/relatsup.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function relatsupcargo() {
        $this->title = 'DTGP';
        $this->requiredPage('cadam/relatsup');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/cadam/relatsupcargo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function declcomp() {

        $this->title = 'Declaração de Comparecimento';
        $this->requireLogin();

        $model = $this->load_model('cadam/cadam-model');
        require_once ABSPATH . '/views/cadam/decl_comp.php';
    }

    public function desbloqueiatela() {

        $id_sup = $_POST['id_sup'];
        $id_cad = $_POST['id_cad'];

        //desbloqueia tela
        cadamp::desbloqueia_tela($id_sup);
        cadamp::desbloqueia_tela_cadamp($id_cad);

        return 1;
    }

    public function listachamado() {
        $this->title = 'Call Center';
        $this->requiredPage('cadam/listachamado');
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/cadam/listachamado.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function pdfrelatorio() {
        $this->title = 'Relatório';
        $this->requireLogin();
        $model = $this->load_model('cadam/cadam-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/cadam/pdfrelatorio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
