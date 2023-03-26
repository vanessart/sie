<?php

class htpcController extends MainController {

    public function index() {
        $this->title = 'Home';
        $this->requiredPage('htpc/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('htpc/htpc-model');
        if ($model::isProfessor()) {
            $valores = $model->listarAtas(true);
            extract($valores);
            require ABSPATH . '/includes/structure/menuVertProf.php';
            require_once ABSPATH . '/module/htpc/index.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
            require_once ABSPATH . '/views/geral/inicio.php';
        }
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pautas() {
        $this->title = 'Home';
        //$this->requiredPage('htpc/pautas');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('htpc/htpc-model');
        $valores = $model->listarPautas();
        extract($valores);
        if ($model::isProfessor()) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/htpc/views/pautas.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function presenca() {
        $this->title = 'Home';
        $this->requiredPage('htpc/presenca');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('htpc/htpc-model');
        $valores = $model->presenca();
        extract($valores);
        require_once ABSPATH . '/module/htpc/views/presenca.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function proporPauta() {
        $this->title = 'Home';
        $this->requiredPage('htpc/proporPauta');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('htpc/htpc-model');
        $valores = $model->proporPauta();
        extract($valores);
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/htpc/views/proporPauta.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function cadastrarPropostaPauta() {
        $this->title = 'Home';
        $this->requiredPage('htpc/index');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('htpc/htpc-model');
        $valores = $model->cadastrarPropostaPauta();
        extract($valores);
        require_once ABSPATH . '/module/htpc/views/cadastrarPropostaPauta.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pautasCadastro() {
        $this->title = 'Home';
        $this->requiredPage('htpc/pautasCadastro');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('htpc/htpc-model');
        $valores = $model->cadastrarPauta();
        extract($valores);
        require_once ABSPATH . '/module/htpc/views/pautasCadastro.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function atasCadastro() {
        $this->title = 'Home';
        $this->requiredPage('htpc/atasCadastro');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('htpc/htpc-model');
        $valores = $model->cadastrarAta();
        extract($valores);
        if (!empty($id_ata)) {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/htpc/views/atasCadastro.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function ataVisualizar() {
        $this->title = 'Home';
        $this->requiredPage('htpc/ataVisualizar');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('htpc/htpc-model');
        $valores = $model->cadastrarAta();
        extract($valores);
        require_once ABSPATH . '/module/htpc/views/ataVisualizar.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'Home';
        $this->requireLogin();
        //$this->requiredPage('sed/htpc');
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/htpc/htpc-model');
        require_once ABSPATH . '/module/htpc/views/def/' . $_data . '.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function atas() {
        $this->title = 'Home';
        $this->requiredPage('htpc/atas');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('htpc/htpc-model');
        if (!empty($_POST['activeNav']) && $_POST['activeNav'] == 2) {
            $valores = $model->cadastrarAta();
        } else {
            $valores = $model->listarAtas();
        }
        extract($valores);
        if ($model::isProfessor()) {
            require ABSPATH . '/includes/structure/menuVertProf.php';
        } else {
            require ABSPATH . '/includes/structure/menu.php';
        }
        require_once ABSPATH . '/module/htpc/views/atas.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function presencaRemover() {
        $this->title = 'Home';
        $this->requiredPage('htpc/presencaRemover');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('htpc/htpc-model');
        $valores = $model->presencaRemover();
        extract($valores);
        require_once ABSPATH . '/module/htpc/views/presencaRemover.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function presencaGet() {
        $this->title = 'Home';
        $model = $this->load_model('htpc/htpc-model');
        $valores = $model->presencaGet();
        echo json_encode($valores, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function ementaCadastro() {
        $this->title = 'Home';
        $this->requiredPage('htpc/ementaCadastro');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('htpc/htpc-model');
        $valores = $model->ementaCadastro();
        extract($valores);
        require_once ABSPATH . '/module/htpc/views/ementaCadastro.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pautaDesativar() {
        $this->title = 'Home';
        $this->requiredPage('htpc/pautaDesativar');
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('htpc/htpc-model');
        $valores = $model->pautaDesativar();
        extract($valores);
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/htpc/views/pautas.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pautaUpload() {
        $this->title = 'Home';
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('htpc/htpc-model');
        $valores = $model->listarAnexosPauta();
        extract($valores);
        require_once ABSPATH . '/module/htpc/views/pautaUpload.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function saveATAPut() {
        $this->title = 'Home';
        $model = $this->load_model('htpc/htpc-model');
        $valores = $model->saveATAPut();
        echo json_encode($valores, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pautaPDF() {
        $this->title = 'Home';
        $this->requireLogin();

        $model = $this->load_model('htpc/htpc-model');
        $valores = $model->pautaPDF();
        extract($valores);
        require_once ABSPATH . '/module/htpc/views/_pdf/pautaPDF.php';
    }

    public function ataPDF() {
        $this->title = 'Home';
        $this->requireLogin();

        $model = $this->load_model('htpc/htpc-model');
        $valores = $model->ataPDF();
        extract($valores);
        require_once ABSPATH . '/module/htpc/views/_pdf/ataPDF.php';
    }

    public function proporPautaUpload() {
        $this->title = 'Home';
        $this->requireLogin();

        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('htpc/htpc-model');
        $valores = $model->listarAnexosPautaProposta();
        extract($valores);
        require_once ABSPATH . '/module/htpc/views/proporPautaUpload.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

}
