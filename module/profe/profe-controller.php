<?php

class profeController extends MainController {

    public function index() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        if (toolErp::id_nilvel() == 24) {
            $model = $this->load_model('/profe/profe-model');
            require ABSPATH . '/includes/structure/menuVertProf.php';
            require_once ABSPATH . '/module/profe/views/main.php';
        } elseif (toolErp::id_nilvel() == 8) {
            $model = $this->load_model('/profe/profe-model');
            require ABSPATH . '/includes/structure/menu.php';
            require_once ABSPATH . '/module/profe/views/mainAdm.php';
        } elseif (in_array(toolErp::id_nilvel(), [55, 56])) {
            $model = $this->load_model('/supervisor/supervisor-model');
            require ABSPATH . '/includes/structure/menu.php';
            require_once ABSPATH . '/module/supervisor/views/controDiario.php';
        } elseif (in_array(toolErp::id_nilvel(), [2])) {
            $model = $this->load_model('/profe/profe-model');
            require ABSPATH . '/includes/structure/menu.php';
            require_once ABSPATH . '/module/profe/views/adm.php';
        } elseif (in_array(toolErp::id_nilvel(), [53])) {
            $model = $this->load_model('/profe/profe-model');
            require ABSPATH . '/includes/structure/menu.php';
            require_once ABSPATH . '/module/profe/views/faltaSeq.php';
        } elseif (in_array(toolErp::id_nilvel(), [54])) {
            $model = $this->load_model('/profe/profe-model');
            require ABSPATH . '/includes/structure/menu.php';
            //     require_once ABSPATH . '/module/profe/views/coorFund.php';
        } else {
            $model = $this->load_model('/profe/profe-model');
            require ABSPATH . '/includes/structure/menu.php';
            require_once ABSPATH . '/views/geral/inicio.php';
        }
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function coorFund() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/coorFund.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'Diário de Classe';
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require_once ABSPATH . '/module/profe/views/def/' . $_data;
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pdf() {
        $_data = func_get_args()[0][0];
        $this->title = 'Diário de Classe';
        $model = $this->load_model('/profe/profe-model');
        require_once ABSPATH . '/module/profe/views/pdf/' . $_data;
    }

    public function chamada() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/chamada');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/profe/views/chamada.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function infoGeral() {
        $this->title = 'Informações';
        $this->requiredPage('profe/infoGeral');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/infoGeral.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function diariotmp() {
        $this->title = 'Diário 2021';
        $this->requiredPage('profe/diariotmp');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/diario2021.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function controlFalta() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/controlFalta');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/controlFalta.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function controlFaltaProf() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/controlFaltaProf');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menuVertProf.php';
        require_once ABSPATH . '/module/profe/views/controlFaltaProf.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function relatFalta() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/relatFalta');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        if (!in_array(toolErp::id_nilvel(), [53])) {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/profe/views/relatFalta.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function faltaSeq() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/faltaSeq');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/faltaSeq.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function controlFaltaRel() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/controlFalta');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/controlFaltaRel.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function adaptCurriculo() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/adaptCurriculo');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/profe/views/adaptCurriculo.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function diarioCoord() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/diarioCoord');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/diarioCoord.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function planoAula() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/planoAula');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/habili/views/planoAula.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function setup() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/setup');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/habili/views/setup.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function profList() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/profList');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/profList.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function profListSupl() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/profListSupl');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/profListSupl.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function cadam() {
        $this->title = 'Diário de Classe';
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require_once ABSPATH . '/module/habili/views/cadam.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function lancNota() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/lancNota');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        require ABSPATH . '/includes/structure/menuVertProf.php';
        require_once ABSPATH . '/module/habili/views/lancNota.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function lancNotaCoord() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/lancNotaCoord');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/habili/views/lancNotaCoord.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function link() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/link');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/habili/views/link.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function lancNotaSet() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/lancNota');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }

        require_once ABSPATH . '/module/habili/views/lancNotaSet.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function plano() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/planoAula');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/habili/views/plano.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function controDiario() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/controDiario');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/supervisor/supervisor-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/controDiario.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function controDiarioTurma() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/controDiario');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/supervisor/supervisor-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/supervisor/views/controDiarioTurma.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function planoVerific() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/planoVerific');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/habili/views/planoVerific.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function chamadaCoord() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/chamadaCoord');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/mainAdm.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function relatorioChamadaProf() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/relatorioChamadaProf');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/profe/views/relatorioChamadaProf.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function relatorioChamada() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/relatorioChamada');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/profe/views/relatorioChamada.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function relatorioOcorrencias() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/relatorioChamada');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/profe/views/relatorioOcorrencias.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function chamadaTabela() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/chamadaTabela');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/profe/views/chamadaTabela.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function diarioPorProf() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/diarioPorProf');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/diarioPorProf.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function diarioPorTurma() {
        $this->title = 'SIEB';
        $this->requiredPage('profe/diarioPorTurma');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/diarioPorTurma.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function notaTabela() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/notaTabela');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/profe/views/notaTabela.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function relatorioNota() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/relatorioNota');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/profe/views/relatorioNota.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function relatorioNotaProf() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/relatorioNotaProf');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/profe/views/relatorioNotaProf.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function relatorioHabProf() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/relatorioHabProf');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/profe/views/relatorioHabProf.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function relatorioHab() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/relatorioHab');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/profe/views/relatorioHab.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function habTabela() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/notaTabela');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/profe/views/habTabela.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function projetoProf() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/projetoProf');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/profe/views/projetoProf.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function projetoCoord() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/projetoCoord');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/profe/views/projetoCoord.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function projeto() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/profe/views/projeto.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function projetoCoordList() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/index');
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
        $this->requiredPage('profe/index');
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
        $this->requiredPage('profe/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require_once ABSPATH . '/module/profe/views/consolidadoResumo.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function consolidadoProf() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/consolidado');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menuVertProf.php';
        require_once ABSPATH . '/module/profe/views/consolidadoProf.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function consolidadoCoord() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/consolidadoCoord');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/consolidadoCoord.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function planoTurma() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/planoAula');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/habili/views/planoTurma.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function planoCoord() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/planoAula');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/habili/habili-model');

        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/habili/views/planoCoord.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function cadSondagem() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/index');
        $this->requireLogin();
        $model = $this->load_model('/profe/profe-model');
        $model->cadSondagem();
    }

    public function acompApr() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/acompApr');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/profe/views/acompApr.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function acompAprGr() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/acompAprGr');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/acompAprGr.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function habilidadesList() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/habilidadesList');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        if (toolErp::id_nilvel() == 24) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/profe/views/habilidadesList.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function habilRanking() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/habilRanking');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/habilRanking.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function projetoPdf() {
        $this->title = 'Diário de Classe';
        $this->requireLogin();
        $model = $this->load_model('profe/profe-model');
        require_once ABSPATH . '/module/profe/views/def/projetoPDF.php';
    }

    public function habilAplic() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/habilAplic');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menuVert.php';
        require_once ABSPATH . '/module/profe/views/habilAplic.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function profMsg() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/profMsg');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/profMsg.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function coordMsg() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/coordMsg');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/coordMsg.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function faltasRede() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/faltasRede');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/faltasRede.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function justificaFaltas() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/justificaFaltas');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/profe/views/justificaFaltas.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function flex() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/projetoProf');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/profe/profe-model');
        require_once ABSPATH . '/module/profe/views/def/projetoFlex.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function relatProfTurma() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/relatProfTurma');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menuVertProf.php';
        require_once ABSPATH . '/module/sed/views/relatProfTurma.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function carometroProf() {
        $this->title = 'Diário de Classe';
        $this->requiredPage('profe/carometroProf');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menuVertProf.php';
        require_once ABSPATH . '/module/sed/views/carometroProf.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

}
