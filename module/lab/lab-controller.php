<?php

class labController extends MainController {

    public function index() {
        $this->title = 'ChromeBook';

        $model = $this->load_model('lab/lab-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        if (empty(toolErp::id_nilvel()) || toolErp::id_nilvel() == 24) {
            require_once ABSPATH . '/module/lab/views/chromeProf.php';
        } else if (toolErp::id_nilvel() == 10) {
            require_once ABSPATH . '/module/lab/views/amd.php';
        } else {
            require_once ABSPATH . '/module/lab/views/inicio.php';
        }
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'ChromeBook';
        $this->requireLogin();
        $this->requiredPage('lab/chrome');
        $model = $this->load_model('lab/lab-model');
        require ABSPATH . '/includes/structure/header.php';
        require_once ABSPATH . '/module/lab/views/def/' . $_data;
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function defProf() {
        $_data = func_get_args()[0][0];
        $this->title = 'ChromeBook';
        $model = $this->load_model('lab/lab-model');
        require ABSPATH . '/includes/structure/header.php';
        ?>
        <div id="art-main">
            <div class="art-sheet clearfix">
                <?php
                require_once ABSPATH . '/module/lab/views/defProf/' . $_data;
                require ABSPATH . '/includes/structure/footer.php';
            }

            public function chrome() {
                $this->title = 'ChromeBook';
                $this->requiredPage('lab/chrome');
                $this->requireLogin();
                require ABSPATH . '/includes/structure/header.php';
                $model = $this->load_model('lab/lab-model');
                require ABSPATH . '/includes/structure/menu.php';
                require_once ABSPATH . '/module/lab/views/chrome.php';
                require ABSPATH . '/includes/structure/footer.php';
            }

            public function historico() {
                $this->title = 'ChromeBook';
                $this->requiredPage('lab/historico');
                $this->requireLogin();
                require ABSPATH . '/includes/structure/header.php';
                $model = $this->load_model('lab/lab-model');
                require ABSPATH . '/includes/structure/menu.php';
                require_once ABSPATH . '/module/lab/views/historico.php';
                require ABSPATH . '/includes/structure/footer.php';
            }

            public function mov() {
                $this->title = 'ChromeBook';
                $this->requiredPage('lab/mov');
                $this->requireLogin();
                require ABSPATH . '/includes/structure/header.php';
                $model = $this->load_model('lab/lab-model');
                require ABSPATH . '/includes/structure/menu.php';
                require_once ABSPATH . '/module/lab/views/mov.php';
                require ABSPATH . '/includes/structure/footer.php';
            }

            public function movLog() {
                $this->title = 'ChromeBook';
                $this->requiredPage('lab/movLog');
                $this->requireLogin();
                require ABSPATH . '/includes/structure/header.php';
                $model = $this->load_model('lab/lab-model');
                require ABSPATH . '/includes/structure/menu.php';
                require_once ABSPATH . '/module/lab/views/movLog.php';
                require ABSPATH . '/includes/structure/footer.php';
            }

            public function modem() {
                $this->title = 'modem';
                $this->requiredPage('lab/mov');
                $this->requireLogin();
                require ABSPATH . '/includes/structure/header.php';
                $model = $this->load_model('lab/lab-model');
                require ABSPATH . '/includes/structure/menu.php';
                require_once ABSPATH . '/module/lab/views/modem.php';
                require ABSPATH . '/includes/structure/footer.php';
            }

            public function emprestimo() {
                $this->title = 'ChromeBook';
                $this->requiredPage('lab/emprestimo');
                $this->requireLogin();
                require ABSPATH . '/includes/structure/header.php';
                $model = $this->load_model('lab/lab-model');
                require ABSPATH . '/includes/structure/menu.php';
                require_once ABSPATH . '/module/lab/views/emprestimo.php';
                require ABSPATH . '/includes/structure/footer.php';
            }

            public function manut() {
                $this->title = 'ChromeBook';
                $this->requiredPage('lab/manut');
                $this->requireLogin();
                require ABSPATH . '/includes/structure/header.php';
                $model = $this->load_model('lab/lab-model');
                require ABSPATH . '/includes/structure/menu.php';
                require_once ABSPATH . '/module/lab/views/manut.php';
                require ABSPATH . '/includes/structure/footer.php';
            }

            public function formChromeProf() {
                $this->title = 'ChromeBook';
                $this->requiredPage('lab/emprestRede');
                $this->requireLogin();
                require ABSPATH . '/includes/structure/header.php';
                $model = $this->load_model('lab/lab-model');
                require_once ABSPATH . '/module/lab/views/formChromeProf.php';
                require ABSPATH . '/includes/structure/footer.php';
            }

            public function emprestRede() {
                $this->title = 'ChromeBook';
                $this->requiredPage('lab/emprestRede');
                $this->requireLogin();

                require ABSPATH . '/includes/structure/header.php';
                $model = $this->load_model('lab/lab-model');
                require ABSPATH . '/includes/structure/menu.php';
                require_once ABSPATH . '/module/lab/views/emprestRede.php';
                require ABSPATH . '/includes/structure/footer.php';
            }

            public function chromeProfList() {
                $this->title = 'ChromeBook';
                require ABSPATH . '/includes/structure/header.php';
                require_once ABSPATH . '/module/lab/views/chromeProfList.php';
                require ABSPATH . '/includes/structure/footer.php';
            }

            public function chromeProf() {
                $this->title = 'ChromeBook';
                require ABSPATH . '/includes/structure/header.php';
                $model = $this->load_model('lab/lab-model');
                require ABSPATH . '/includes/structure/menu.php';
                require_once ABSPATH . '/module/lab/views/chromeProf.php';
                require ABSPATH . '/includes/structure/footer.php';
            }

            public function chromerede() {
                $this->title = 'ChromeBook';
                $this->requiredPage('lab/chrome');
                $this->requireLogin();

                $model = $this->load_model('lab/lab-model');
                require ABSPATH . '/includes/structure/header.php';
                require ABSPATH . '/includes/structure/menu.php';
                require_once ABSPATH . '/module/lab/views/chromerede.php';
                require ABSPATH . '/includes/structure/footer.php';
            }

            public function histEmpr() {
                $this->title = 'histEmpr';
                $this->requiredPage('lab/chrome');
                $this->requireLogin();

                $model = $this->load_model('lab/lab-model');
                require ABSPATH . '/includes/structure/header.php';
                require ABSPATH . '/includes/structure/menu.php';
                require_once ABSPATH . '/module/lab/views/histEmpr.php';
                require ABSPATH . '/includes/structure/footer.php';
            }

            public function chromeResumo() {
                $this->title = 'ChromeBook';
                $this->requiredPage('lab/chrome');
                $this->requireLogin();
                $resumo = 1;
                $model = $this->load_model('lab/lab-model');
                require ABSPATH . '/includes/structure/header.php';
                require ABSPATH . '/includes/structure/menu.php';
                require_once ABSPATH . '/module/lab/views/chromerede.php';
                require ABSPATH . '/includes/structure/footer.php';
            }

            public function nomeAluno() {
                $this->title = 'ChromeBook';
                $this->requiredPage('lab/chrome');
                $this->requireLogin();
                $model = $this->load_model('lab/lab-model');
                $model->nomeAluno();
            }

            public function relatEscola() {
                $this->title = 'ChromeBook';
                $this->requiredPage('lab/chrome');
                $this->requireLogin();
                $model = $this->load_model('lab/lab-model');
                require_once ABSPATH . '/module/lab/views/relatEscola.php';
            }

            public function protAluno() {
                $this->title = 'ChromeBook';
                $model = $this->load_model('lab/lab-model');
                require_once ABSPATH . '/module/lab/views/protAluno.php';
            }

            public function protDevEsc() {
                $this->title = 'ChromeBook';
                $model = $this->load_model('lab/lab-model');
                require_once ABSPATH . '/module/lab/views/protDevEsc.php';
            }

            public function protDevProf() {
                $this->title = 'ChromeBook';
                $model = $this->load_model('lab/lab-model');
                require_once ABSPATH . '/module/lab/views/protDevProf.php';
            }

            public function protMalaRh() {
                $this->title = 'ChromeBook';
                $model = $this->load_model('lab/lab-model');
                require_once ABSPATH . '/module/lab/views/protMalaRh.php';
            }

            public function protDevRh() {
                $this->title = 'ChromeBook';
                $model = $this->load_model('lab/lab-model');
                require_once ABSPATH . '/module/lab/views/protDevRh.php';
            }

            public function protProfRede() {
                $this->title = 'ChromeBook';
                $model = $this->load_model('lab/lab-model');
                require_once ABSPATH . '/module/lab/views/protProfRede.php';
            }

            public function protProf() {
                $this->title = 'ChromeBook';
                $model = $this->load_model('lab/lab-model');
                require_once ABSPATH . '/module/lab/views/protProf.php';
            }

            public function quadro() {
                $this->title = 'ChromeBook';
                $this->requiredPage('lab/quadro');
                $this->requireLogin();
                $model = $this->load_model('lab/lab-model');
                require ABSPATH . '/includes/structure/header.php';
                require ABSPATH . '/includes/structure/menu.php';
                require_once ABSPATH . '/module/lab/views/quadro.php';
            }

            public function formFim() {
                $this->title = 'ChromeBook';
                $this->requiredPage('lab/chrome');
                $this->requireLogin();
                $resumo = 1;
                $model = $this->load_model('lab/lab-model');
                require ABSPATH . '/includes/structure/header.php';
                require_once ABSPATH . '/module/lab/views/formFim.php';
                require ABSPATH . '/includes/structure/footer.php';
            }

            public function formFimManut() {
                $this->title = 'ChromeBook';
                $this->requiredPage('lab/chrome');
                $this->requireLogin();
                $resumo = 1;
                $model = $this->load_model('lab/lab-model');
                require ABSPATH . '/includes/structure/header.php';
                require_once ABSPATH . '/module/lab/views/formFimManut.php';
                require ABSPATH . '/includes/structure/footer.php';
            }

            public function bloqueio() {
                $this->title = 'ChromeBook';
                $this->requiredPage('lab/bloqueio');
                $this->requireLogin();
                $resumo = 1;
                $model = $this->load_model('lab/lab-model');
                require ABSPATH . '/includes/structure/header.php';
                require ABSPATH . '/includes/structure/menu.php';
                require_once ABSPATH . '/module/lab/views/bloqueio.php';
                require ABSPATH . '/includes/structure/footer.php';
            }


            public function quadrogeral() {
                $this->title = 'ChromeBook';
                $this->requiredPage('lab/quadrogeral');
                $this->requireLogin();
                $resumo = 1;
                $model = $this->load_model('lab/lab-model');
                require ABSPATH . '/includes/structure/header.php';
                require ABSPATH . '/includes/structure/menu.php';
                require_once ABSPATH . '/module/lab/views/quadrogeral.php';
                require ABSPATH . '/includes/structure/footer.php';
            }


            public function cadLote() {
                $this->title = 'ChromeBook';
                $this->requiredPage('lab/cadLote');
                $this->requireLogin();
                $resumo = 1;
                $model = $this->load_model('lab/lab-model');
                require ABSPATH . '/includes/structure/header.php';
                require ABSPATH . '/includes/structure/menu.php';
                require_once ABSPATH . '/module/lab/views/cadLote.php';
                require ABSPATH . '/includes/structure/footer.php';
            }


            public function pdf() {
                $_data = func_get_args()[0][0];
                $this->requireLogin();
                $model = $this->load_model('/lab/lab-model');
                require_once ABSPATH . '/module/lab/views/pdf/' . $_data;
            }

        }
        