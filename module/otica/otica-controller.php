<?php

class oticaController extends MainController {

    public function index() {
        $this->title = 'Folha Óptica';
        $this->requiredPage('otica/index');
        $this->requireLogin();
        require ABSPATH . '/includes/structure/header.php';
        require ABSPATH . '/includes/structure/menu.php';
        require_once ABSPATH . '/module/otica/views/pdfInd.php';
        require ABSPATH . '/includes/structure/footer.php';
    }

 

}
