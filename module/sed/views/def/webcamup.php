<?php
$name = $_REQUEST['id_pessoa'];
$file = ABSPATH . '/pub/fotos/';
$img = $_POST['base64image'];
$img = str_replace('data:image/jpeg;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$file = $file . $name . '.jpg';
$success = file_put_contents($file, $data);
print $success ? $file : 'Não é possível salvar o arquivo.';
?>