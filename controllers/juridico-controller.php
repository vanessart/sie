<?php

class juridicoController extends MainController {

    // URL: dominio.com/juridico/

 public function index() {
        $this->title = 'Jurídico';
        require ABSPATH . '/views/_includes/header.php';
        require_once ABSPATH . '/views/juridico/publicacoes_educacao.php';
        require ABSPATH . '/views/_includes/footer.php';
    }

}
