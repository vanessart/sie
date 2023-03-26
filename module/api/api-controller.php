<?php

class apiController extends MainController {

    public function index() {
        $this->title = 'API';
        $this->requiredPage('api/index');
        $this->requireLogin();
        $this->load_model('/api/models/api-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/api/views/main.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pin() {
        $this->title = 'API';
        $this->requiredPage('api/pin');
        $this->requireLogin();
        $this->load_model('/api/models/api-model');
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/api/views/pin.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'API';
        $this->requireLogin();
        $this->requiredPage('api/index');
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/api/api-model');
        require_once ABSPATH . '/module/api/views/def/' . $_data . '.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function aluno() {
        /**
        $arg = func_get_args()[0];
        if (!empty($arg[0])) {
            ob_start();
            header("Access-Control-Allow-Origin: *");
            $model = $this->load_model('/api/models/aluno-model');
            $metodo = $arg[0];
            $model->$metodo($arg);
        }
         * 
         */
    }

    public function alunoList() {
        $arg = func_get_args()[0];
        if (!empty($arg[0])) {
            ob_start();
            header("Access-Control-Allow-Origin: *");
            $model = $this->load_model('/api/models/alunolist-model');
            $metodo = $arg[0];
            $model->$metodo($arg);
        }
    }

    public function portal() {
        $arg = func_get_args()[0];
        if (!empty($arg[0])) {
            ob_start();
            header("Access-Control-Allow-Origin: *");
            $model = $this->load_model('/api/models/portal-model');
            $metodo = $arg[0];
            $model->$metodo($arg);
        }
    }

    public function aut() {
        require_once ABSPATH . '/module/api/views/aut.php';
    }

    public function calendario() {
        $arg = func_get_args()[0];
        ob_start();
        header("Access-Control-Allow-Origin: *");
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/api/models/api-model');
        require_once ABSPATH . '/module/api/views/calendario.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function chrome() {
        $arg = func_get_args()[0];
        if (!empty($arg[0])) {
            ob_start();
            header("Access-Control-Allow-Origin: *");
            $model = $this->load_model('/api/models/chrome-model');
            $metodo = $arg[0];
            $model->$metodo($arg);
        }
    }

    public function token() {
        ob_start();
        header("Access-Control-Allow-Origin: *");
        $model = $this->load_model('/api/models/api-model');
        $model->token();
    }

    public function serial() {
        $arg = func_get_args()[0];
        if (!empty($arg[0])) {
            ob_clean();
            ob_start();
            header("Access-Control-Allow-Origin: *");
            $model = $this->load_model('/recurso/recurso-model');
            $metodo = $arg[0];
            $id_inst = !empty($arg[1]) ? $arg[1] : null;
            $opt = '';

            $search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);
            $equipamentos = $model->$metodo($id_inst, null, $search);
            if (!empty($equipamentos)){
                foreach ($equipamentos as $v) {
                    $opt .= '<option value="' . $v . '">';
                }
            }
            echo $opt;
        }
    }

}
