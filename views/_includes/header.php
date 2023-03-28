<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" lang="pt-BR">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" lang="pt-BR">
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->    <!--<![endif]-->
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
                <!-- <title><?php echo $this->title; ?> - SIEB - 26/12/2020</title> -->
        <title><?= CLI_CIDADE ?> - Educação - <?= SISTEMA_NOME ?></title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width">


        <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">
        <link rel="<?php echo HOME_URI; ?>/template/<?php echo TEMPLETE ?>/shortcut icon" href="favicon.ico" type="image/x-icon">
        <script src="<?php echo HOME_URI; ?>/template/<?php echo TEMPLETE ?>/jquery.js"></script>
        <script src="<?php echo HOME_URI; ?>/template/<?php echo TEMPLETE ?>/script.js"></script>
        <script src="<?php echo HOME_URI; ?>/template/<?php echo TEMPLETE ?>/script.responsive.js"></script>

        <!--[if lt IE 9]><script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <link rel="stylesheet" href="<?php echo HOME_URI; ?>/template/<?php echo TEMPLETE ?>/style.css" media="screen">
        <!--[if lte IE 7]><link rel="stylesheet" href="<?php echo HOME_URI; ?>/template/<?php echo TEMPLETE ?>/style.ie7.css" media="screen" /><![endif]-->
        <link rel="stylesheet" href="<?php echo HOME_URI; ?>/template/<?php echo TEMPLETE ?>/style.responsive.css" media="all">


        <script src="<?php echo HOME_URI; ?>/views/_js/scripts.js"></script>

        <!--<meta name="viewport" content="width=device-width, minimumscale=1.0, maximum-scale=1.0" />-->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="<?php echo HOME_URI; ?>/views/_css/bootstrap-theme.css">
        <link rel="stylesheet" href="<?php echo HOME_URI; ?>/views/_css/style.css">
        <script src="<?php echo HOME_URI; ?>/views/_js/menu.js"></script>
        <script src="<?php echo HOME_URI; ?>/views/_js/bootstrap.js"></script>
<?php
$proc = filter_input(INPUT_POST, 'proc', FILTER_SANITIZE_NUMBER_INT);
if (!$proc) {
    ?>
            <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
            <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>
            <!-------------------------------------------------------------------------------------->

            <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

            <!--------------------------------------------------------------------------------------->
            <style>.art-content .art-postcontent-0 .layout-item-0 {
                    padding-right: 10px;
                    padding-left: 10px;
                }
                .ie7 .art-post .art-layout-cell {
                    border:none !important;
                    padding:0 !important;
                }
                .ie6 .art-post .art-layout-cell {
                    border:none !important;
                    padding:0 !important;
                }

            </style>


            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <!-- JQUERY MASCARAS -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <?php
}
?>
        <script>
            $(document).ready(function () {
<?php
if (@$_SESSION['userdata']['id_pessoa'] != 1 && @$_SESSION['userdata']['id_pessoa'] != 6488 && @$_SESSION['userdata']['id_pessoa'] != 1327) {
    ?>
                    $('input').keypress(function (e) {
                        var code = null;
                        code = (e.keyCode ? e.keyCode : e.which);
                        return (code == 13) ? false : true;
                    });
    <?php
}
?>
            });
        </script>
        <!--script async src="https://www.googletagmanager.com/gtag/js?id=G-NRJGB6T7DX"></script-->
        <!--script>
            window.dataLayer = window.dataLayer || [];
            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'G-NRJGB6T7DX');
        </script-->

        <!-- Seta siebsed global para javascript -->
        <script async type="text/javascript"  crossorigin="anonymous">
            var HOME_URI = "<?php echo HOME_URI; ?>";
            var siebsed = siebsed || {};
        </script>

        <!-- Load script siebsed -->
        <script type="text/javascript" src='<?php echo HOME_URI; ?>/views/_js/siebsed.js'  crossorigin="anonymous"></script>

        <!-- Load script geocode api google -->
        <!-- <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyAuJgECnkb1b8M-KZIinGrRPd98cC_3egs"></script> -->

        <!-- CEP/MAPA -->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-fsEPEgboVq5ChY76XpqRe7ezvnZYxi4"  defer ></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="<?php echo HOME_URI; ?>/views/_js/mapa.js"></script>
        <script src="<?php echo HOME_URI; ?>/views/_js/index.js"></script>

        <!--logar com o google-->
        <script src="https://accounts.google.com/gsi/client" async defer></script>
    </head>

    <body>
