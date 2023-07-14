<?php

$set['localhost'] = 1;
$set['parnaibamakerlabs.app.br'] = 1;
$set['gasparmakerlabs.app.br'] = 2;

$host = $_SERVER['SERVER_NAME'];

if (substr($host, 0, 4) == 'www.') {
    $host = substr($host, 3);
}
if (!empty($set[$host])) {
    define('NUM_SYS', $set[$host]);

    include ABSPATH . '/_config/' . NUM_SYS . '.php';
} else {
    exit();
}
