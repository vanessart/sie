<?php

class cadampeController extends MainController {

    public function index() {
        $this->title = 'Cadampe';
        $this->requiredPage('cadampe/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cadampe/views/inicio.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function solicitar() {
        $this->title = 'Cadampe';
        $this->requiredPage('cadampe/index');
        $this->requireLogin();

        $model = $this->load_model('cadampe/cadampe-model');
        $valores = $model->index_solicitar();
        extract($valores);
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/cadampe/views/solicitar.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function inativaCadampe() {
        $this->title = 'Cadampe';
        $this->requiredPage('cadampe/index');
        $this->requireLogin();

        $model = $this->load_model('cadampe/cadampe-model');
        $valores = $model->index_inativaCadampe();
        extract($valores);
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/cadampe/views/inativaCadampe.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

     public function inativaCadampeList() {
        $this->title = 'Cadampe';
        $this->requiredPage('cadampe/index');
        $this->requireLogin();

        $model = $this->load_model('cadampe/cadampe-model');
        $valores = $model->index_inativaCadampeList();
        extract($valores);
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cadampe/views/inativaCadampeList.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function solicitarRel() {
        $this->title = 'Cadampe';
        $this->requiredPage('cadampe/index');
        $this->requireLogin();

        $model = $this->load_model('cadampe/cadampe-model');
        $valores = $model->index_solicitarRel();
        extract($valores);
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/cadampe/views/solicitarRel.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function solicitarList() {
        $this->title = 'Cadampe';
        $this->requiredPage('cadampe/index');
        $this->requireLogin();

        $model = $this->load_model('cadampe/cadampe-model');
        $valores = $model->index_solicitarList();
        extract($valores);
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cadampe/views/solicitarList.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function convocadosList() {
        $this->title = 'Cadampe';
        $this->requiredPage('cadampe/index');
        $this->requireLogin();

        $model = $this->load_model('cadampe/cadampe-model');
        $valores = $model->index_convocadosList();
        extract($valores);
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cadampe/views/convocadosList.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function protocolosList() {
        $this->title = 'Cadampe';
        $this->requiredPage('cadampe/index');
        $this->requireLogin();

        $model = $this->load_model('cadampe/cadampe-model');
        $valores = $model->index_protocolosList();
        extract($valores);
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cadampe/views/protocolosList.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function listCadampe() {
        $this->title = 'Cadampe';
        $this->requiredPage('cadampe/index');
        $this->requireLogin();

        $model = $this->load_model('cadampe/cadampe-model');
        $valores = $model->index_listCadampe();
        extract($valores);
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cadampe/views/listCadampe.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function solicitarListcall() {
        $this->title = 'Cadampe';
        $this->requiredPage('cadampe/index');
        $this->requireLogin();

        $model = $this->load_model('cadampe/cadampe-model');
        $valores = $model->index_solicitarListcall();
        extract($valores);
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cadampe/views/solicitarListcall.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function atribuirCadampe() {
        $this->title = 'Cadampe';
        $this->requiredPage('cadampe/index');
        $this->requireLogin();

        $model = $this->load_model('cadampe/cadampe-model');
        $valores = $model->index_atribuirCadampe();
        extract($valores);
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/cadampe/views/atribuirCadampe.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function naoAtribuirCadampe() {
        $this->title = 'Cadampe';
        $this->requiredPage('cadampe/index');
        $this->requireLogin();

        $model = $this->load_model('cadampe/cadampe-model');
        $valores = $model->index_naoAtribuirCadampe();
        extract($valores);
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/cadampe/views/naoAtribuirCadampe.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function diario() {
        $this->title = 'Cadampe';
        $this->requiredPage('cadampe/index');
        $this->requireLogin();

        $model = $this->load_model('cadampe/cadampe-model');
        $valores = $model->index_diario();
        extract($valores);
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/cadampe/views/diario.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function editCadampe() {
        $this->title = 'Cadampe';
        $this->requiredPage('cadampe/index');
        $this->requireLogin();

        $model = $this->load_model('cadampe/cadampe-model');
        $valores = $model->index_editCadampe();
        extract($valores);
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/cadampe/views/editCadampe.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function historicoCadampe() {
        $this->title = 'Cadampe';
        $this->requiredPage('cadampe/index');
        $this->requireLogin();

        $model = $this->load_model('cadampe/cadampe-model');
        $valores = $model->index_historicoCadampe();
        extract($valores);
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/cadampe/views/historicoCadampe.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function historicoCadampeList() {
        $this->title = 'Cadampe';
        $this->requiredPage('cadampe/index');
        $this->requireLogin();

        $model = $this->load_model('cadampe/cadampe-model');
        $valores = $model->index_historicoCadampeList();
        extract($valores);
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/cadampe/views/historicoCadampeList.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function solicitaSet() {
        $this->requireLogin();
        $model = $this->load_model('cadampe/cadampe-model');
        $model->solicitaSet();
    }

    public function solicitaGet() {
        $this->requireLogin();
        $model = $this->load_model('cadampe/cadampe-model');
        $model->solicitaGet();
    }

    public function cadampeSet() {
        $this->requireLogin();
        $model = $this->load_model('cadampe/cadampe-model');
        $model->cadampeSet();
    }

    public function cadampeGet() {
        $this->requireLogin();
        $model = $this->load_model('cadampe/cadampe-model');
        $model->cadampeGet();
    }

    public function protocoloEdit() {
        $this->title = 'Cadampe';
        $this->requiredPage('cadampe/protocolosList');
        $this->requireLogin();

        $model = $this->load_model('cadampe/cadampe-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/cadampe/views/protocoloEdit.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function professorSubst() {
        $this->title = 'Cadampe';
        $this->requiredPage('cadampe/professorSubst');
        $this->requireLogin();

        $model = $this->load_model('cadampe/cadampe-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/cadampe/views/professorSubst.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function historicoProf() {
        $this->title = 'Cadampe';
        $this->requiredPage('cadampe/professorSubst');
        $this->requireLogin();

        $model = $this->load_model('cadampe/cadampe-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/cadampe/views/historicoProf.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

}
