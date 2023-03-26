<?php

class apdController extends MainController {

    public function index() {
        $this->title = 'Alunos com Deficiência';
        $this->requiredPage('apd/index');
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        if (toolErp::id_nilvel() == 48 || toolErp::id_nilvel() == 18) {
            require_once ABSPATH . '/module/apd/views/mainCoord.php';
        }elseif (toolErp::id_nilvel() == 10) {
            require_once ABSPATH . '/module/apd/views/mainGerente.php';
        }else {
            require_once ABSPATH . '/module/apd/views/main.php';
        }
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function apd() {
        $this->title = 'Alunos com Deficiência';
        $this->requiredPage('apd/apd');
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/apd/views/apd.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

  
    public function ajuda() {
        $this->title = 'Alunos com Deficiência';
        $this->requiredPage('apd/ajuda');
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/apd/views/ajuda.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function docRel() {
        $this->title = 'Alunos com Deficiência';
        $this->requiredPage('apd/doc');
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/apd/views/docRel.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function doc() {
        $this->title = 'Alunos com Deficiência';
        $this->requiredPage('apd/doc');
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        $valores = $model->index_doc();
        !empty($valores) ? extract($valores) : "";
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/apd/views/doc.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pdi() {
        $this->title = 'Alunos com Deficiência';
        $this->requiredPage('apd/doc');
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        // $valores = $model->index_doc();
        // !empty($valores) ? extract($valores) : "";
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/apd/views/pdi.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function avaInicial() {
        $this->title = 'Alunos com Deficiência';
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/apd/views/avaInicial.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function apdFoto() {
        $this->title = 'Alunos com Deficiência';
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/apd/views/apdFoto.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function infoInicial() {
        $this->title = 'Alunos com Deficiência';
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/apd/views/_pdi/infoInicial.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function PDFInicio() {
        $this->title = 'Alunos com Deficiência';
        $this->requireLogin();
        
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('apd/apd-model');
        require_once ABSPATH . '/module/apd/views/_pdi/pdiPDFInicio.php';
    }

    public function fotosPDF() {
        $this->title = 'Alunos com Deficiência';
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require_once ABSPATH . '/module/apd/views/fotosPDF.php';
    }

    public function PDFentre() {
        $this->title = 'Alunos com Deficiência';
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require_once ABSPATH . '/module/apd/views/entrevistaPDF.php';
    }

    public function PDFaval() {
        $this->title = 'Alunos com Deficiência';
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require_once ABSPATH . '/module/apd/views/avalInicialPDF.php';
    }

    public function atendimentos() {
        $this->title = 'Alunos com Deficiência';
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/apd/views/_pdi/atendimentos.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function hab() {
        $this->title = 'Alunos com Deficiência';
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/apd/views/_pdi/habilidades.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function habSet() {
        $this->title = 'Alunos com Deficiência';
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/apd/views/_pdi/habPlan.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function entrevista() {
        $this->title = 'Alunos com Deficiência';
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/apd/views/entrevista.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function boletim() {
        $this->title = 'Alunos com Deficiência';
        $this->requiredPage('apd/apd');
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/apd/views/boletim.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function boletimModal() {
        $this->title = 'Alunos com Deficiência';
        //$this->requiredPage('apd/boletim');
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/apd/views/boletimModal.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function boletimPDF() {
        //$this->requiredPage('apd/apd');
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require_once ABSPATH . '/module/apd/views/boletimPDF.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'Alunos com Deficiência';
        $this->requireLogin();
        //$this->requiredPage('sed/apd');
        $model = $this->load_model('/apd/apd-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/apd/views/_abas/' . $_data;
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function atendFaltas() {
        $this->title = 'Alunos com Deficiência';
        $this->requiredPage('apd/atendFaltas');
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/apd/views/atendFaltas.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function histAtend() {
        $this->title = 'Alunos com Deficiência';
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/apd/views/_pdi/histAtend.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function contraturno() {
        $this->title = 'Alunos com Deficiência';
        $this->requiredPage('apd/contraturno');
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/apd/views/contraturno.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function trocaTurma() {
        $this->title = 'Alunos com Deficiência';
        $this->requiredPage('apd/contraturno');
        $this->requireLogin();

        $model = $this->load_model('apd/apd-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/apd/views/trocaTurma.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function alunoNovoList() {
        $this->title = 'Alunos com Deficiência';
        $this->requiredPage('apd/alunoNovoList');
        $this->requireLogin();

        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/apd/views/alunoNovoList.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function termoAceite() {
        $this->title = 'Alunos com Deficiência';
        //$this->requiredPage('apd/termoAceite');
        $this->requireLogin();

        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/header.php';
        // require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/apd/views/termoAceite.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function contato() {
        $this->title = 'Alunos com Deficiência';
        $this->requiredPage('apd/index');
        $this->requireLogin();

        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/protocolo/views/contato.php';
    }

    public function termoRecusa() {
        $this->title = 'Alunos com Deficiência';
        //$this->requiredPage('apd/termoRecusa');
        $this->requireLogin();

        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/header.php';
        // require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/apd/views/termoRecusa.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    //*****PROTOCOLO*****

    public function protocoloList() {
        $this->title = 'Protocolos';
        $this->requiredPage('apd/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/protocolo/views/protocoloList.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function protocolo() {
        $this->title = 'Protocolos';
        $this->requiredPage('apd/protocoloList');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/protocolo/views/protocolo.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function deferidoList() {
        $this->title = 'Protocolos';
        $this->requiredPage('apd/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/protocolo/views/deferidoList.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function protocoloGerente() {
        $this->title = 'Protocolos';
        $this->requiredPage('apd/deferidoList');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/protocolo/views/protocoloGerente.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function capAssinatura() {
        $this->title = 'Protocolos';
        //$this->requiredPage('protocolo/termoRecusa');
        //$this->requireLogin();
        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/apd/views/capAssinatura.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function capAssinaturaAceite() {
        $this->title = 'Protocolos';
        //$this->requiredPage('protocolo/termoRecusa');
        //$this->requireLogin();
        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/apd/views/capAssinaturaAceite.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function protocoloListProf() {
        $this->title = 'Protocolos';
        $this->requiredPage('apd/protocoloListProf');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('protocolo/protocolo-model');
        require ABSPATH . '/includes/structure/menu.php';
        $prof = true;
        require_once ABSPATH . '/module/protocolo/views/protocoloList.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

}
