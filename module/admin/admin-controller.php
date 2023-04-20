<?php

class adminController extends MainController {

    public function index() {
        $this->title = 'Administrador';
        $this->requiredPage('admin/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/views/geral/inicio.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function def() {
        $_data = func_get_args()[0][0];
        $this->title = 'Administrador';
        $this->requireLogin();
        $this->requiredPage('admin/index');
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('/admin/admin-model');
        require_once ABSPATH . '/module/admin/views/def/' . $_data . '.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pessoas() {
        $this->title = 'Administrador';
        $this->requiredPage('admin/pessoas');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('admin/admin-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/admin/views/pessoas.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function pessoaEdit() {
        $this->title = 'Administrador';
        $this->requiredPage('admin/pessoas');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('admin/admin-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/admin/views/pessoaEdit.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function grupoCad() {
        $this->title = 'Administrador';
        $this->requiredPage('admin/grupoCad');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('admin/admin-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/admin/views/grupoCad.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function grupoSet() {
        $this->title = 'Administrador';
        $this->requiredPage('admin/grupoSet');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('admin/admin-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/admin/views/grupoSet.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function grupoUser() {
        $this->title = 'Administrador';
        $this->requiredPage('admin/grupoUser');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('admin/admin-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/admin/views/grupoUser.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

    public function sistemaUser() {
        $this->title = 'Administrador';
        $this->requiredPage('admin/sistemaUser');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        $model = $this->load_model('admin/admin-model');
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/admin/views/sistemaUser.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

}
