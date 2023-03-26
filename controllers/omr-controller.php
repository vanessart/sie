<?php

class omrController extends MainController {

    public function index() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function gabarito() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/gabarito.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function gefolha() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/gefolha.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function geprint() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/geprint.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function imprlog() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require_once ABSPATH . '/views/omr/imprlog.php';
    }

    public function template() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/template.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function templconf() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/templconf.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cliente() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/cliente.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function predio() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/predio.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function prepro() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/prepro.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function dir() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/dir.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function processar() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/processar.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function proc() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/omr/proc.php';
    }

    public function impr() {
        $this->title='OMR';
        $this->requireLogin();
        require_once ABSPATH . '/views/omr/impr.php';
    }

    public function etiqueta() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/etiqueta.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function impretiq() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require_once ABSPATH . '/views/omr/impretiq.php';
    }

    public function questErro() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/questErro.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function questCod() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/questCod.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function questInsc() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/questInsc.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function manual() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/manual.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function calculo() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/calculo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function cal() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/omr/cal.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function resumo() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/resumo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
 public function grafico() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/grafico-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/graficos.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function graficodonut() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/grafico-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/grafico_donut.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function graficoquinto() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/grafico-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/grafico5.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function acertoesctot() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/grafico-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/acerto_escola.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function mediaescola() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/grafico-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/media_escola.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function mediaciclo() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/grafico-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/media_ciclo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function mediaturma() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/grafico-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/media_turma.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function donutrede() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/grafico-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/grafico_donut_rede.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
public function acertoano() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/grafico-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/acerto_por_ano.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    public function mediaano() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/grafico-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/media_total_ano.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function portmat() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/grafico-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/grafico_donut_port_mat.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function relnulo() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/grafico-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/relnulo.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function relresp() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/grafico-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/relresp.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function etiq() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/grafico-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/etiquetas.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function dados() {
        $this->title='OMR';
        $this->requireLogin();

        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/omr/dados.php';
        require ABSPATH . '/views/_includes/footer.php';
    }
    
    public function consulta() {
        $this->title='OMR';
        $model = $this->load_model('omr/omr-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/omr/consulta.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
