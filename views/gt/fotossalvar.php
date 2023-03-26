
<?php
   
    
    $name = $_SESSION['tmp']['id_pessoaFoto'];
$file = ABSPATH . '/pub/fotos/';
define('UPLOAD_DIR', $file);
$img = $_POST['base64image'];
$img = str_replace('data:image/jpeg;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$file = UPLOAD_DIR . $name . '.jpg';
$success = file_put_contents($file, $data);
print $success ? $file : 'Não é possível salvar o arquivo.';
    