<?php

$name = $_REQUEST['name'];
$file = ABSPATH . '/pub/sed_doc/';
define('UPLOAD_DIR', $file);
if (!empty($_POST['base64image'])) {
    $img = $_POST['base64image'];
    $img = str_replace('data:image/jpeg;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
    $file = UPLOAD_DIR . $name . '.jpg';
    $success = file_put_contents($file, $data);
    print $success ? $file : 'Não é possível salvar o arquivo.';
} else {
    echo 'Não é possível salvar o arquivo.';
}
?>