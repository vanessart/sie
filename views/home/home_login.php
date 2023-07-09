<?php
if (!defined('ABSPATH'))
    exit;
$key = $model->key();
$token = DB::sqlKey('googleLogin');
?>
<!DOCTYPE html>
<html lang="pt-br" >
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
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Secretaria de Educação - <?= CLI_NOME ?></title>

        <style>
            body {
                /*background-color: #edf2f9 !important;*/
                font-family: 'Roboto', sans-serif !important;
                background-color: #fff !important;
                position: absolute;    
                width: 100%;
            }

            footer{
                position: fixed;
                bottom: 0 !important;
                width: 100%;
            }

            .bg-box{
                margin-top: 13vh !important;
                background-color: #FFF5;
                border-bottom-left-radius: 0.375rem;
                border-bottom-right-radius: 0.375rem;
                position: relative;
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-orient: vertical;
                -webkit-box-direction: normal;
                -ms-flex-direction: column;
                flex-direction: column;
                min-width: 0;
                word-wrap: break-word;
                background-clip: border-box;
                border: 2px solid #333;
                border-radius: 0.375rem;
                box-shadow: 0 7px 14px 0 rgba(65, 69, 88, 0.1), 0 3px 6px 0 rgba(0, 0, 0, 0.07);;
            }

            .title-erp{
                font-size: 1.3em;
                color: #87BA33;
                text-align: center;
                font-weight: bold;
            }

            .bg-card-gradient {
                background-position: center;
                color: #FFF;
            }

            .bg-card-inputs{
                background-color: #333;
                color: #FFF !important;

            }
            .bg-color{
                background-color: #333 !important;
                height: 0.5px;
                color: #333 !important;
            }

            .btn-erp{
                /*border: 1px solid #000  !important;*/
                background: #333 !important;
                color: #FFF !important;
                font-size: 1.1em !important;
            }
            .link-senha {
                text-align: right;
            }

            .link-senha a{
                text-decoration: none;
                color: #333;
            }

            .link-senha a:hover{
                text-decoration: none;
                color: #333 !important;
                font-weight: 700;
                text-decoration: underline;
            }

            .btn-login-google {
                margin-top: 20px;
                padding: 0px !important;
            }

            .bg-google {
                background-color: #FFF !important;
                padding: 9px;
                width: 50px;
                display: inline-block;
                height: 50px;
                margin: -1px 19px -1px -1px;
                border: 1px solid #333;
            }

            .btn-login {
                background: #333;
                padding: 15px;
                color: #FFF;
                font-size: 18px;
                margin-top: 2rem !important;
            }

            label {
                display: inline-block;
                color: #333;
            }

            .text-seja-bem{
                font-size: 1.2em;
                font-weight: 700;
            }


            /*VERTICAL*/

            .bg-box-vertical{
                margin-top: 5vh;
                background-color: #FFF;
                border-bottom-left-radius: 0.375rem;
                border-bottom-right-radius: 0.375rem;
                position: relative;
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-orient: vertical;
                -webkit-box-direction: normal;
                -ms-flex-direction: column;
                flex-direction: column;
                min-width: 0;
                word-wrap: break-word;
                background-clip: border-box;
                border: 0px solid #fff;
                border-radius: 0.375rem;
                box-shadow: 0 7px 14px 0 rgba(65, 69, 88, 0.1), 0 3px 6px 0 rgba(0, 0, 0, 0.07);;
            }

            .zoom {
                transition: transform .1s; /* Animation */
            }

            .zoom:hover {
                -ms-transform: scale(1.04); /* IE 9 */
                -webkit-transform: scale(1.04); /* Safari 3-8 */
                transform: scale(1.04);
            }

            .bg-login{
                /*background-image: url('<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/login/bg/bg-login.png');*/
                height: 100vh;
                background-position: bottom center;
                background-size: cover;
                background-repeat: no-repeat;
                padding-bottom: 10px;
            }

            .text-para-acessar{
                font-weight: 300;
            }
            .text-login-google{
                margin-top: 11px;
                margin-right: 49px;
                margin-bottom: 0 !important;
            }

            .bg-footer {
                color: #FFF !important;
                background-color: #333 !important;
            }

            .bg-roxo {
                color: #FFF !important;
                background-color: #333 !important;
            }

            .logo-sie-footer{
                width: 100%;
            }

            .align-mudar {
                justify-content: center;
            }

            /*RESPONSIVO*/
            @media screen and (max-width: 1199px){
            }

            @media screen and (max-width: 991px){
                .logo-sie-footer {
                    width: 149%;
                }
            }

            @media screen and (max-width: 767px){
            }	

            @media screen and (max-width: 321px){

            }	

            @media screen and (max-width: 576px){
                .bg-box {
                    margin-top: 0vh !important;
                }

                .bg-login {
                    height: auto;
                    padding-bottom: 147px;
                }

                .logo-sie-footer {
                    width: 28%;
                    padding-bottom: 11px;
                    margin-right: 18px;
                }

                .align-logo-footer{
                    text-align: right;
                }
                .text-login-google {
                    font-size: 0.8em !important;
                }




                /*GE*/
                .logo-sie-faixa{
                    width: 100% !important;
                    margin-bottom: 23px;
                }
                .bg-instancia {
                    background-color: #FFF;
                    border-radius: 5px 5px 5px 5px;
                    width: 100%;
                    font-size: 1em;
                    font-weight: bold;
                    padding: 7px 5px;
                }

                .align-mudar {
                    justify-content: flex-start !important;
                    width: 100% !important;
                }

                .form-select {
                    font-size: 0.9rem !important;
                    text-align: center;
                }
                .text-icones-geral {
                    font-size: 0.9em;
                }

                .align-logo-faixa{
                    text-align: center;
                }
                .logo-sie-faixa {
                    width: 50% !important;
                }

                .bg-instancia {
                    background-color: #FFF;
                    border-radius: 5px;
                    width: 100%;
                    font-size: 1em;
                    font-weight: bold;
                    padding: 7px 7px;
                }

                .text-acesso {
                    font-size: 0.9em !important;
                }
                .text-usuario {
                    font-size: 0.9em !important;
                }
                .text-copyright{
                    font-size: 0.9em !important;
                }

            }


            /*GE*/

            .img-logo{
                margin-top: 25px;
                margin-bottom: 20px;
            }

            .img-logo-sie{
                align-content: center;
            }

            .img-logo-sie-topo{
                align-content: center;
                width: 70px !important;
                margin-bottom: 15px;
            }
        </style>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

        <!-- CSS Libraries -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
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
        <?php
        if ($this->login_error) {
            ?>
            <div class="alert alert-warning alert-dismissible" role="alert">
                <strong>Aviso!</strong> <?php echo $this->login_error; ?>
            </div>
            <?php
        }
        ?>
        <div class="bg-login">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-7 bg-box">
                        <div class="row">
                            <div class="col-12 col-sm-8 col-md-6 col-lg-6 col-xl-6 px-0 bg-card-inputs bg-card-gradient ">
                                <div class="bg-white px-3 " style="border-right: 2px solid #333;border-radius: 4px 2px 0px 0px;">
                                    <img src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/login/logotipos/logo_Educ_PNG.png" class="w-100 img-logo" alt="">
                                </div>
                                <div class="px-4">
                                    <p class="text-white text-center mt-4 text-seja-bem ">
                                        Seja bem vindo a Plataforma
                                    </p>
                                </div>
                                <div class="text-center mb-2">
                                    <?= SISTEMA_NOME ?>
                                    <div class="d-block d-sm-none mb-4"></div>
                                </div>
                                <div class="px-4 mt-4 d-none d-sm-block">
                                    <hr class="text-white " style="height: 2px;opacity: 1;">
                                    <?php
                                    $temErroLogin = false;
                                    if (!empty($_SESSION['msgLogin'])) {
                                        $temErroLogin = true;
                                        ?>
                                        <?= $_SESSION['msgLogin'] ?>
                                        <?php
                                        unset($_SESSION['msgLogin']);
                                    } else {
                                        ?>
                                        <p class="text-white text-para-acessar mt-4 mb-1">
                                            Para acessar a Plataforma com login do Google use seu e-mail institucional:
                                        </p>
                                        <p class="text-white mt-2">
                                            <small>
                                                email@<?= CLI_MAIL_DOMINIO ?>
                                            </small>
                                        </p>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 py-3">
                                <div class="">
                                    <div class="d-flex justify-content-end">
                                        <!-- <img src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/login/logotipos/logo-sie-vertical.png" class=" text-center img-logo-sie-topo" alt=""> -->
                                    </div>
                                    <div id="g_id_onload"
                                         data-client_id="<?= API_GOOGLE ?>"
                                         data-context="signin"
                                         data-ux_mode="popup"
                                         <?php if (empty($temErroLogin)) { ?>data-auto_select="true" <?php } ?>
                                         data-login_uri="<?php echo HOME_URI ?>/home/google"
                                         data-sqlToken="<?php echo $token['sqlToken']; ?>"
                                         data-table="<?php echo $token['table']; ?>"
                                         data-key="<?php echo $key ?>" >
                                    </div>
                                    <div class="g_id_signin"
                                         data-theme="outline"
                                         data-type="standard"
                                         data-shape="pill"
                                         data-theme="filled_blue"
                                         data-text="Fazer login com o Google"
                                         data-size="large"
                                         data-="left"
                                         >
                                    </div>
                                    <div class="mt-5 mb-4 bg-color text-center">
                                        <span style="background: #fff; top: -0.9em; position: relative; padding: 5px">ou</span>
                                    </div>
                                    <form method="POST">
                                        <div class="form-group mt-3">
                                            <input name="userdata[user]" type="text" class="form-control mt-3" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="CPF ou E-mail">
                                        </div>
                                        <div class="form-group mt-1" >
                                            <input name="userdata[user_password]" type="password" class="form-control mt-3" id="exampleInputPassword1" placeholder="Senha">
                                        </div>
                                        <button type="submit" class="btn btn-erp w-100 py-3 mt-3">Acessar</button>
                                    </form>
                                    <p class="link-senha mt-4" style="display: none">	
                                        <a onclick="document.getElementById('login').style.display = 'none';document.getElementById('email').style.display = '';" href="#"class="link" data-toggle="modal">
                                            Recuperar senha
                                        </a>
                                    </p>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            <div class="container-fluid bg-footer">
                <div class="row d-flex justify-content-center">
                    <div class="col-md-6 pt-2 text-center">
                        <p class="text-copyright">
                            © Copyright - <?php echo date('Y'); ?> - <?= CLI_NOME ?> - Secretaria de Educação.
                            <br>
                            <?= SISTEMA_NOME ?> - ERP  Educacional - Versão 3.0 - Abril/2022
                        </p>
                    </div>
                    <div class="col-md-1 pt-2 px-0 mt-2 align-logo-footer">
                        <!-- <img src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/login/logotipos/logo-sie-white.png" alt="Logotipo <?= SISTEMA_NOME ?>-ERP" class="logo-sie-footer"> -->
                    </div>
                </div>
            </div>
        </footer>
        <!--/.Footer-->

    </body>
</html>