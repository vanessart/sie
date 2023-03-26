<?php

if (!empty($_POST['n_pessoa']) && !empty($_POST['email']) && !empty($_POST['id_sup']) && !empty($_POST['rastro_sup'])) {
    sendEmail::suporte($_POST['n_pessoa'], $_POST['email'], $_POST['id_sup'], $_POST['rastro_sup']);
}
