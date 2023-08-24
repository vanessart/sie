<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <title><?php echo $this->title; ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=300, initial-scale=0.8">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
        <script src="<?php echo HOME_URI; ?>/<?php echo INCLUDE_FOLDER ?>/js/jquery-3.6.0.min.js"></script>
        <script src="<?php echo HOME_URI; ?>/<?php echo INCLUDE_FOLDER ?>/js/popper.min.js"></script>
        <link href="<?php echo HOME_URI; ?>/<?php echo INCLUDE_FOLDER ?>/css/bootstrap5.min.css" rel="stylesheet">
        <script src="<?php echo HOME_URI; ?>/<?php echo INCLUDE_FOLDER ?>/js/bootstrap.min.js"></script>
        <script src="<?php echo HOME_URI; ?>/<?php echo INCLUDE_FOLDER ?>/js/bootstrap5.bundle.min.js"></script>      
        <link rel="stylesheet" href="<?php echo HOME_URI; ?>/<?php echo INCLUDE_FOLDER ?>/css/bootstrap-select.min.css">
        <script src="<?php echo HOME_URI; ?>/<?php echo INCLUDE_FOLDER ?>/js/bootstrap-select.min.js"></script>
        <link rel="stylesheet" href="<?php echo HOME_URI; ?>/<?php echo INCLUDE_FOLDER ?>/css/style.css">
        <script src="<?php echo HOME_URI; ?>/<?php echo INCLUDE_FOLDER ?>/js/scripts.js"></script>
        <link rel='manifest' href='manifest.json'>
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('service-worker.js')
                            .then((reg) => {
                                console.log('Service worker registered.', reg);
                            });
                });
            }
        </script>

        <!--logar com o google-->
        <script src="https://accounts.google.com/gsi/client" async defer></script>
    </head>

    <body>
