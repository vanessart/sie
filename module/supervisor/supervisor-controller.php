<?php

class supervisorController extends MainController {

    public function index() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/supervisor/supervisor-model');
        require ABSPATH . '/includes/structure/menu.php';
        if (toolErp::id_nilvel() == 22) {
            require_once ABSPATH . '/module/supervisor/views/controDiario.php';
        } else {
            require_once ABSPATH . '/views/geral/inicio.php';
        }
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function supervisor() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/supervisor');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/supervisor.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'Supervisores';
        $this->requireLogin();
        $this->requiredPage('supervisor/supervisor');
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/supervisor/supervisor-model');
        require_once ABSPATH . '/module/supervisor/views/def/' . $_data . '.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function areaResponsavelPesq() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/areaResponsavelPesq');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/areaResponsavelPesq.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function areaForm() {

        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/areaResponsavelPesq');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        require_once ABSPATH . '/module/supervisor/views/areaForm.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function itemOcorrenciaPesq() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/itemOcorrenciaPesq');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/itemOcorrenciaPesq.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function itemOcorrenciaForm() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/itemOcorrenciaPesq');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        require_once ABSPATH . '/module/supervisor/views/itemOcorrenciaForm.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function setoresAtribuicaoEscolaPesq() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/setoresAtribuicaoEscolaPesq');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/setores.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function setoresAtribuicaoEscolaForm() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/setoresAtribuicaoEscolaPesq');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        require_once ABSPATH . '/module/supervisor/views/setoresAtribuicaoEscolaForm.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function escolaParticularPesq() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/escolaParticularPesq');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/escolaParticularPesq.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function escolaParticularForm() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/escolaParticularPesq');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        //require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/escolaParticularForm.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function setoresAtribuicaoEscolaModal() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/escolaParticularPesq');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/setoresAtribuicaoEscolaModal.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function supervisorInstituicaoPesq() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/supervisorInstituicaoPesq');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/supervisorInstituicaoPesq.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function supervisorVisitasPesq() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/supervisorVisitasPesq');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/ocorrencias.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function supervisorVisitasForm() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/supervisorVisitasPesq');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        require_once ABSPATH . '/module/supervisor/views/supervisorVisitasForm.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function supervisorVisitasModalHistorico() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/supervisorVisitasPesq');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        // require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/supervisorVisitasModalHistorico.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function supervisorVisitasModalComentario() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/supervisorVisitasPesq');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        // require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/supervisorVisitasModalComentario.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function relatorioSemAgenda() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/relatorioSemAgenda');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        $relatorioName = 'relatorioSemAgenda';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/relatorios.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function relatorioJustificativaPorAusencia() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/relatorioJustificativaPorAusencia');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        $relatorioName = 'relatorioJustificativaPorAusencia';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/relatorios.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function relatorioPoucasVisitas() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/relatorioPoucasVisitas');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        $relatorioName = 'relatorioPoucasVisitas';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/relatorios.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function relatorioDepartamentosMaisRequisitados() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/relatorioDepartamentosMaisRequisitados');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        $relatorioName = 'relatorioDepartamentosMaisRequisitados';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/relatorios.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function relatorioDepartamentosBaixaResolutiva() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/relatorioDepartamentosBaixaResolutiva');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        $relatorioName = 'relatorioDepartamentosBaixaResolutiva';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/relatorios.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function relatorioDepartamentoMaiorSLA() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/relatorioDepartamentoMaiorSLA');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        $relatorioName = 'relatorioDepartamentoMaiorSLA';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/relatorios.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function ocorrencias() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/supervisorVisitasPesq');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        require_once ABSPATH . '/module/supervisor/views/ocorrencia.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function supervisorVisitasModalUpload() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/supervisorVisitasPesq');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('supervisor/supervisor-model');
        require_once ABSPATH . '/module/supervisor/views/_ocorrencias/upload.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function planoAula() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/habili/views/planoAula.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function plano() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/planoAula');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/habili/views/plano.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function planoCoord() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/planoAula');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/habili/views/planoCoord.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function projetoCoord() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/projetoCoord.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function profList() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/profList.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function consolidadoCoord() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/consolidadoCoord.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function projetoCoordList() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/projetoCoordList.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function consolidado() {
        $n_disc = filter_input(INPUT_POST, 'n_disc', FILTER_SANITIZE_STRING);
        $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
        $turma = sql::get('ge_turmas', 'periodo, letra, periodo_letivo, n_turma, fk_id_ciclo', ['id_turma' => $id_turma], 'fetch');
        $this->title = $turma['n_turma'] . ' - ' . $n_disc;
        $this->requiredPage('supervisor/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require_once ABSPATH . '/module/profe/views/consolidado.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function consolidadoResumo() {
        $n_disc = filter_input(INPUT_POST, 'n_disc', FILTER_SANITIZE_STRING);
        $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
        $turma = sql::get('ge_turmas', 'periodo, letra, periodo_letivo, n_turma, fk_id_ciclo', ['id_turma' => $id_turma], 'fetch');
        $this->title = $turma['n_turma'] . ' - ' . $n_disc;
        $this->requiredPage('supervisor/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require_once ABSPATH . '/module/profe/views/consolidadoResumo.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function apd() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/index');
        $this->requireLogin();
        $model = $this->load_model('apd/apd-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/apd/views/apd.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function coorFund() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/coorFund.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function faltasRede() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/faltasRede.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function controDiario() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/controDiario');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/supervisor/supervisor-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/controDiario.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function controDiarioTurma() {
        $this->title = 'Supervisores';
        $this->requiredPage('supervisor/controDiario');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/supervisor/supervisor-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/controDiarioTurma.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

}
