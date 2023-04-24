<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-5JDQ6EKM6W"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'G-5JDQ6EKM6W');
        </script>
        <script type="text/javascript">
            (function (c, l, a, r, i, t, y) {
                c[a] = c[a] || function () {
                    (c[a].q = c[a].q || []).push(arguments)
                };
                t = l.createElement(r);
                t.async = 1;
                t.src = "https://www.clarity.ms/tag/" + i;
                y = l.getElementsByTagName(r)[0];
                y.parentNode.insertBefore(t, y);
            })(window, document, "clarity", "script", "dxu3040a9l");
        </script>
        <title><?php echo $this->title; ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=300, initial-scale=0.8">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
        <script src="<?php echo HOME_URI; ?>/includes/js/jquery-3.6.0.min.js"></script>
        <script src="<?php echo HOME_URI; ?>/includes/js/popper.min.js"></script>
        <link href="<?php echo HOME_URI; ?>/includes/css/bootstrap5.min.css" rel="stylesheet">
        <script src="<?php echo HOME_URI; ?>/includes/js/bootstrap.min.js"></script>
        <script src="<?php echo HOME_URI; ?>/includes/js/bootstrap5.bundle.min.js"></script>      
        <link rel="stylesheet" href="<?php echo HOME_URI; ?>/includes/css/bootstrap-select.min.css">
        <script src="<?php echo HOME_URI; ?>/includes/js/bootstrap-select.min.js"></script>
        <link rel="stylesheet" href="<?php echo HOME_URI; ?>/includes/css/style.css">
        <script src="<?php echo HOME_URI; ?>/includes/js/scripts.js"></script>
        <link rel='manifest' href='manifest.json'>
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/service-worker.js')
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
