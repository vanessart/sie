<?php

class gizController extends MainController {

    public function index() {
        $this->title='Giz de Ouro';
        $this->requiredPage('giz/index');
        $this->requireLogin();

        $model = $this->load_model('giz/giz-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        if (@$_SESSION['userdata']['id_nivel'] == 24) {
            $cand = sql::get('giz_prof', 'fk_id_pessoa');
            $cand = array_column($cand, 'fk_id_pessoa');
            if (in_array(tool::id_pessoa(), $cand)) {
                require_once ABSPATH . '/views/giz/giz.php';
            } else {
                ?>
                <div class="alert alert-warning" style="text-align: center; font-size: 18px">
                    Inscrições Encerradas
                </div>
                <?php
            }
        } elseif (@$_SESSION['userdata']['id_nivel'] == 17 || @$_SESSION['userdata']['id_nivel'] == 36) {
            require_once ABSPATH . '/views/giz/giz_gest.php';
        }
        ?>
        <!--
        <br /><br />
                <div style="text-align: center">
                    <a target="_blank" class="btn btn-info" href="<?php echo HOME_URI ?>/giz/devolutiva">
                        Devolutiva
                    </a>
                </div>
        -->
        <?php
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function inscricao() {
        $this->title='Giz de Ouro';
        $this->requiredPage('giz/index');
        $this->requireLogin();

        $model = $this->load_model('giz/giz-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/giz/inscricao.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function gest() {
        $this->title='Giz de Ouro';
        $this->requiredPage('giz/index');
        $this->requireLogin();

        $model = $this->load_model('giz/giz-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/giz/gest.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function prot() {
        $this->title='Giz de Ouro';
        $this->requiredPage('giz/index');
        $this->requireLogin();

        $model = $this->load_model('giz/giz-model');
        require_once ABSPATH . '/views/giz/prot.php';
    }

    public function aceite() {
        $this->title='Giz de Ouro';
        $this->requiredPage('giz/index');
        $this->requireLogin();

        $model = $this->load_model('giz/giz-model');
        require_once ABSPATH . '/views/giz/aceite.php';
    }

    public function projeto() {
        $this->title='Giz de Ouro';
        $this->requiredPage('giz/index');
        $this->requireLogin();

        $model = $this->load_model('giz/giz-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/giz/projeto.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function banca() {
        $this->title='Giz de Ouro';
        $this->requiredPage('giz/banca');
        $this->requireLogin();

        $model = $this->load_model('giz/giz-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/giz/banca.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function bancap() {
        $this->title='Giz de Ouro';
        $this->requiredPage('giz/banca');
        $this->requireLogin();

        $model = $this->load_model('giz/giz-model');
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/giz/bancap.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function aval() {
        $this->title='Giz de Ouro';
        $this->requiredPage('giz/banca');
        $this->requireLogin();

        $model = $this->load_model('giz/giz-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/giz/aval.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function todos() {
        $this->title='Giz de Ouro';
        $this->requiredPage('giz/banca');
        $this->requireLogin();

        $model = $this->load_model('giz/giz-model');
        require_once ABSPATH . '/views/giz/todos.php';
    }

    public function classifica() {
        $this->title='Giz de Ouro';
        $this->requiredPage('giz/classifica');
        $this->requireLogin();

        $model = $this->load_model('giz/giz-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/giz/classifica.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

    public function devolutiva() {
        $this->title='Giz de Ouro';
        $this->requiredPage('giz/index');
        $this->requireLogin();
        $model = $this->load_model('giz/giz-model');
        require_once ABSPATH . '/views/giz/devolutiva.php';
    }

    public function protentrega() {
        $this->title='Giz de Ouro';
        $this->requiredPage('giz/index');
        $this->requireLogin();
        $model = $this->load_model('giz/giz-model');
        require_once ABSPATH . '/views/giz/protentrega.php';
    }

    public function entrega() {
        $this->title='Giz de Ouro';
        $this->requiredPage('giz/entrega');
        $this->requireLogin();

        $model = $this->load_model('giz/giz-model');
        require ABSPATH . '/views/_includes/header.php';
        require ABSPATH . '/views/_includes/menu.php';
        require_once ABSPATH . '/views/giz/entrega.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
