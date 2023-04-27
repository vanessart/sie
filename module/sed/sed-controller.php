<?php

class sedController extends MainController {

    public function index() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        if (in_array(tool::id_nivel(), [2, 10])) {
            require_once ABSPATH . '/module/sed/views/adm.php';
        } elseif (tool::id_nivel() == 8) {
            require_once ABSPATH . '/module/sed/views/escola.php';
        } elseif (tool::id_nivel() == 44) {
            require_once ABSPATH . '/views/geral/inicio.php';
//           require_once ABSPATH . '/module/sed/views/nono.php';
        }

        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'Gestão Educacional';
        $this->requireLogin();
        $this->requiredPage('sed/def');
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require_once ABSPATH . '/module/sed/views/def/' . $_data;
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pdf() {
        $_data = func_get_args()[0][0];
        $model = $this->load_model('sed/sed-model');
        require_once ABSPATH . '/module/sed/views/pdf/' . $_data;
    }

    public function relat() {
        $_data = func_get_args()[0][0];
        $this->requireLogin();
        $this->requiredPage('sed/def');
        $model = $this->load_model('sed/sed-model');
        require_once ABSPATH . '/module/sed/views/relat/' . $_data;
    }

    public function sed() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/sed');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/sed.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function foto() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/carometro');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require_once ABSPATH . '/views/sed/_carometro/foto.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function sincronizar() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/sincronizar');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/sincronizar.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function convocacao() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/convocacao');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/convocacao.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function eventos() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/convocacao');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/eventos.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function encaminhamento() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/encaminhamento');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/encaminhamento.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function encaminhamentocarta() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/encaminhamentocarta');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/encaminhamentocarta.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function cargaHoraria() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/cargaHoraria');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/cargaHoraria.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function predio() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/predio');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/predio.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function quadro() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/quadro');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/quadro.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function disc() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/disc');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/disc.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function funcSenha() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/funcSenha');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        $id_gr = 1;
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/funcSenha.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function senhaProf() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/senhaProf');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/senhaProf.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function senhafunc() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/senhafunc');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/senhafunc.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function escolaSet() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/escolaSet');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/escolaSet.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function quadroAluno() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/quadroAluno');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/quadroAluno.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function convoca() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/convoca');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/convoca.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function relatProf() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/relatProf');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/relatProf.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function webcamup() {
        $this->title = 'Gestão Educacional';
        //$this->requiredPage('sed/aluno');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/_aluno/_5/webcamup.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function salaAula() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/salaAula');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/salaAula.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function carometro() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/carometro');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/carometro.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pdfcarometro() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/carometro');
        $this->requireLogin();
        $model = $this->load_model('sed/sed-model');
        require_once ABSPATH . '/module/sed/views/pdfcarometro.php';
    }

    public function geraImg() {
        $this->title = 'Gestão Educacional';
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        //require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/geraImg.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function assinatura() {
        require_once ABSPATH . '/module/sed/views/assinatura.php';
    }

    public function google() {
        $model = $this->load_model('sed/sed-model');
        $model->assinatura();
    }

    public function consulta() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/consulta');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/consulta.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function robot() {
        $this->title = 'Gestão Educacional';
        $model = $this->load_model('sed/sed-model');
        require_once ABSPATH . '/module/sed/views/_sincronizar/robot.php';
    }

    public function aluno() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/alunoPesq');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/aluno.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function alunoNovo() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/alunoNovo');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/alunoNovo.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function appRetirada() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/alunoPesq');
        $this->requireLogin();
        $model = $this->load_model('sed/sed-model');
        $model->appRetirada();
    }

    public function alunoPesq() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/alunoPesq');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/alunoPesq.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function alunoimport() {
        $this->requiredPage('sed/alunoPesq');
        $this->requireLogin();
        $model = $this->load_model('sed/sed-model');
        $model->importarAluno(1);
    }

    public function turmas() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/turmas');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/turmas.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function turmaRelat() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/turmaRelat');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/turmaRelat.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function escolaRelat() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/escolaRelat');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/escolaRelat.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function turmaSet() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/turmaSet');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/turmaSet.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function mural() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/mural');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/mural.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function grupo() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/grupo');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/grupo.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function fotossalvar() {

        $this->requireLogin();

        require_once ABSPATH . '/views/sed/fotossalvar.php';
    }

    public function fotossalvarre() {

        $this->requireLogin();

        require_once ABSPATH . '/views/sed/fotossalvarre.php';
    }

    public function alocaProf() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/alocaProf');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/alocaProf.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function profCad() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/profCad');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/profCad.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function autentica() {
        $this->title = 'Gestão Educacional';
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require_once ABSPATH . '/module/sed/views/autentica.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function horario() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/horario');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/horario.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function horarioset() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/horario');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require_once ABSPATH . '/module/sed/views/horarioset.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function calendario() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/calendario');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/calendario.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pl() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/pl');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/pl.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function unidLetiva() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/unidLetiva');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/unidLetiva.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function feriado() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/unidLetiva');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/feriado.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function ensino() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/ensino');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/ensino.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function declaracaovaga() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/declaracaovaga');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/declaracaovaga.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function mala() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/mala');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/mala.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function cadFunc() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/cadFunc');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        $id_gr = 39;
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/cadFunc.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function cid() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/cid');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/cid.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function carteirinha() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/carteirinha');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/carteirinha.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function hist() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/historico');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('historico/historico-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/historico/views/hist.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function foraData() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/foraData');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/foraData.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function foraDataEsc() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/foraDataEsc');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/foraDataEsc.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function googleId() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/googleId');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/googleId.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    
    public function paternidade() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/paternidade');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/paternidade.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    
    public function paternidadeMain() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/paternidadeMain');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/paternidadeMain.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
    
    public function listaLiberadosProfEsc() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/listaLiberadosProfEsc');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/listaLiberadosProfEsc.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

        public function relatProfTurma() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/relatProfTurma');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/relatProfTurma.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
 
        public function carometroProf() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/carometroProf');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/carometroProf.php';
        require ABSPATH . '/includes/structure/footer.php';
    }
         public function consultarm() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('sed/consultarm');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('sed/sed-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/sed/views/consultarm.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function integracaoAlunos() {
        $model = $this->load_model('sed/sed-model');
        $model->integracaoAlunos(1);
    }
    
}
