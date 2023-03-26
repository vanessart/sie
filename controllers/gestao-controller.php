<?php

class gestaoController extends MainController {

    public function index() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/index');
        $this->requireLogin();
        $ss = 1;
        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function super() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/super');
        $this->requireLogin();
        $sim = 1;

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/super.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function ensino() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/ensino');
        $this->requireLogin();
        $sim = 1;

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/ensino.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadescola() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/cadescola');
        $this->requireLogin();
        $sim = 1;

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/cadescola.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function escola() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/escola');
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/escola.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function disc() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/disc');
        $this->requireLogin();
        $sim = 1;

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/disc.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadclasse() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/cadclasse');
        $this->requireLogin();
        $sim = 1;
        $sim = 1;

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/cadclasse.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    //cadescola_pre_esc

    public function predio() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/predio');
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/predio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function alunoaloca() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/alunoaloca');
        $this->requireLogin();

        $sim = 1;
        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/alunoaloca.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadaluno() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/cadaluno');
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        $nao = 1;
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/cadaluno.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function relatorio() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/relatorio');
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/cabecalho_relatorio.php';
    }

    public function listapiloto() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/lista_piloto.php';
    }

    public function listapresenca() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/lista_presenca.php';
    }

    public function fichacadastral() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/ficha_cadastral.php';
    }

    public function fichacadastralbranco() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/ficha_cadastral_branco.php';
    }

    public function listagemgeral() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/listagem_geral.php';
    }

    public function listpiloto() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/list_piloto.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function classes() {
        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/classes.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function abrangencia() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/abrangencia');
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/abrangencia.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function fotossalvar() {

        $this->requireLogin();

        require_once ABSPATH . '/views/gestao/fotossalvar.php';
    }

    public function manutencaoclasse() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/manutencaoclasse');
        $this->requireLogin();
        $nao = 1;
        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/manutencaoclasse.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function manuttransf() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/manuttransf');
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/manuttransf.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function consaluno() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/consaluno');
        $this->requireLogin();
        $nao = 1;

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/consaluno.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function carometro() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/carometro');
        $this->requireLogin();
        $sim = 1;

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/carometro.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function professores() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/professores');
        $this->requireLogin();
        $sim = 1;

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/professores.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function pdfcarometro() {
        $this->title = 'Gestão Educacional';
        $this->requireLogin();
        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/pdfcarometro.php';
    }

    public function relatalunos() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/relatalunos');
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/relat_alunos.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cadpessoa() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/cadpessoa');
        $this->requireLogin();

        $sim = 1;
        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/cadpessoa.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function listaparede() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/lista_parede.php';
    }
    public function listaparedeFinal() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/lista_paredeFinal.php';
    }

    public function convocacaopdf() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/convocacaopdf.php';
    }

    public function declaracaopdf() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/declaracaopdf.php';
    }

    public function convocacao() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/convocacao');
        $this->requireLogin();
        $sim = 1;

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/convocacao.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function convocacaolista() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/convocacaolista');
        $this->requireLogin();
        $sim = 1;

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/convocacao_lista.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function listachamada() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/lista_chamada.php';
    }

    public function abrangepdf() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/abrangepdf.php';
    }

    public function listapaternidade() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/lista_paternidade.php';
    }

    public function decltransf() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/decl_transf.php';
    }

    public function declvaga() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/decl_vaga.php';
    }

    public function declaracaovaga() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/declaracaovaga');
        $this->requireLogin();
        $sim = 1;

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/declaracaovaga.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function declcomp() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/decl_comp.php';
    }

    public function quadroaluno() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/quadroaluno');
        $this->requireLogin();
        $sim = 1;

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/quadroaluno.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function quadroalunopdf() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/quadroalunopdf.php';
    }

    public function listaidadeano() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/lista_idade_ano.php';
    }

    public function perfildoalunopdf() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/perfildoalunopdf.php';
    }

    public function listaspe() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/listaspe.php';
    }

    public function relatescola() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/relatescola');
        $this->requireLogin();
        $sim = 1;

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/relatescola.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function listapersonalizadapdf() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/listapersonalizadapdf.php';
    }

    public function autorizacaopdf() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/autorizacaopdf.php';
    }

    public function periodo() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/periodo');
        $this->requireLogin();
        $sim = 1;

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/periodo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function letiva() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/letiva');
        $this->requireLogin();
        $sim = 1;

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/letiva.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function listaperassinatura() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/listaperassinatura.php';
    }

    public function listaalunoapd() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/lista_aluno_apd.php';
    }

    public function listaopcaoescola() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/listaopcaoescola.php';
    }

    public function listanaofrequente() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/lista_nao_frequente.php';
    }

    public function consultarm() {

        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/consultarm');
        $this->requireLogin();
        $sim = 1;

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/consultarm.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function declconclusao() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/decl_conclusao.php';
    }

    public function listaconselhopdf() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/lista_conselhopdf.php';
    }

    public function listapilotodoc() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/lista_piloto_doc.php';
    }

    public function listapilotoend() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/lista_piloto_end.php';
    }

    public function salapdf() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/salapdf.php';
    }

    public function perfildoalunopdfbranco() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/perfildoalunopdfbranco.php';
    }

    public function cadastroapd() {
        $this->title = 'Gestão Educacional';
        $this->requiredPage('gestao/cadastroapd');
        $this->requireLogin();
        $sim = 1;

        $model = $this->load_model('gestao/gestao-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/gestao/cadastroapd.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function listaemail() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/lista_email.php';
    }

    public function declconclusaoeja() {

        $this->title = 'Gestão Educacional';
        $this->requireLogin();

        $model = $this->load_model('gestao/gestao-model');
        require_once ABSPATH . '/views/gestao/decl_conclusaoeja.php';
    }

}
