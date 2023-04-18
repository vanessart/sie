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



}
