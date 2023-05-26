<!DOCTYPE html>
<html lang="pt-br" data-version="1">
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

        <title>Secretaria de Educação - <?= CLI_NOME ?> - <?= SISTEMA_NOME ?>-ERP</title>

        <!-- CSS -->
        <link href="custom-sie.css" rel="stylesheet">

        <script src="<?php echo HOME_URI; ?>/includes/js/jquery-3.6.0.min.js"></script>
        <script src="<?php echo HOME_URI; ?>/includes/js/popper.min.js"></script>
        <link href="<?php echo HOME_URI; ?>/includes/css/bootstrap5.min.css" rel="stylesheet">
        <script src="<?php echo HOME_URI; ?>/includes/js/bootstrap.min.js"></script>
        <script src="<?php echo HOME_URI; ?>/includes/js/bootstrap5.bundle.min.js"></script>      
        <link rel="stylesheet" href="<?php echo HOME_URI; ?>/includes/css/bootstrap-select.min.css">
        <script src="<?php echo HOME_URI; ?>/includes/js/bootstrap-select.min.js"></script>
        <style>
            .body-sie-erp {
                font-family: 'Roboto', sans-serif !important;
                background-color: #fff !important;
                position: absolute;
                width: 100%;
            }

            .bg-roxo {
                color: #FFF !important;
                background-color: #324b73 !important;
            }

            .logo-sie-footer{
                width: 100%;
            }

            .align-mudar-sie {
                justify-content: center;
            }

            .text-acesso-integrado{
                font-size: 1.1em;
                font-weight: bold;
                color: #87BA33;
                margin-top: 30px;
                text-align: right;
            }

            .text-usuario-sie{
                font-size: 1em;
                font-weight: 400;
                text-align: right;
            }

            .btn-sair-sie{
                font-size: 0.8em !important;
                color: #FFF !important;
                background-color: #324b73 !important;
                border-radius: 0px 0px 4px 4px  !important;
            }

            .bg-instancia-sie{
                background-color: #FFF;
                border-radius: 5px 0 0 5px;
                width: 100%;
                font-size: 1em;
                font-weight: bold;
                padding: 7px 28px;
            }

            .bg-instancia-select{
                border-radius: 0 5px 5px 0 !important;
                border-left: 1px solid #000 !important;
                padding: 8px !important;
            }

            .text-icones-geral-sie{
                font-size: 1.05em;
                font-weight: 500;
                color: #324b73;
                text-decoration: none;
                height: 89px !important;
            }

            .text-icones-geral-sie:hover{
                font-size: 1.05em;
                font-weight: 500;
                color: #87ba33 !important;
                text-decoration: none;
            }

            .img-icone-geral-sie{
                float: left;
                width: 60px;
                margin-right: 10px;
                margin-left: 40px;
            }

            .logo-sie-faixa{
                width: 50%;
                margin-left: 18px;
            }

            .img-logo-sie{
                margin-top: -26px;
                margin-bottom: 20px;
                width: 100%;
            }


            /*FOOTER*/

            .bg-footer {
                color: #FFF !important;
                background-color: #324b73 !important;
            }

            .footer-sie-erp {
                position: absolute;
                bottom: 0 !important;
                width: 100%;
            }


            /*RESPONSIVO*/
            @media screen and (max-width: 1199px){
            }

            @media screen and (max-width: 991px){
                .logo-sie-footer {
                    width: 149%;
                }
                .logo-sie-faixa {
                    width: 78%;
                }

                .bg-instancia-select {
                    border-radius: 0 5px 5px 0 !important;
                    border-left: 1px solid #000 !important;
                    padding: 7px !important;
                    font-size: 0.9em;
                }
                .bg-instancia-sie {
                    padding: 8px 0px;
                    font-size: 0.8em;
                }

                .text-icones-geral-sie {
                    font-size: 0.8em;
                    font-weight: 500;
                    color: #324b73;
                    text-decoration: none;
                    height: 89px !important;
                }
            }

            @media screen and (max-width: 767px){
            }

            @media screen and (max-width: 321px){

            }

            @media screen and (max-width: 576px){

                .logo-sie-footer {
                    width: 28%;
                    padding-bottom: 11px;
                    margin-right: 18px;
                }
                .align-logo-footer{
                    text-align: center;
                }

                .logo-sie-faixa{
                    width: 100% !important;
                    margin-bottom: 23px;
                }
                .bg-instancia-sie {
                    background-color: #FFF;
                    border-radius: 5px 5px 5px 5px;
                    width: 100%;
                    font-size: 1em;
                    font-weight: bold;
                    padding: 7px 5px;
                }
                .align-mudar-sie {
                    justify-content: flex-start !important;
                    width: 100% !important;
                }
                .form-select-sie {
                    font-size: 0.9rem !important;
                    text-align: center;
                }
                .bg-instancia-select {

                    padding: 4px !important;
                    width: 100% !important;
                }
                .text-icones-geral-sie {
                    font-size: 0.9em;
                }
                .align-logo-faixa{
                    text-align: center;
                }
                .logo-sie-faixa {
                    width: 50% !important;
                }
                .text-acesso-integrado {
                    font-size: 0.8em !important;
                    text-align: center;
                }

                .text-usuario-sie {
                    font-size: 0.8em !important;
                }
                .text-copyright-sie{
                    font-size: 0.9em !important;
                }
                .footer-space-sie{
                    margin-top: 120px;
                }

            }

        </style>
    </head>
    <body class="body-sie-erp">
        <?php
        if (!defined('ABSPATH'))
            exit();
        if (empty($_SESSION['userdata']['foto'])) {
            $foto = HOME_URI . '/includes/images/user-anonimo.jpg';
        } else {
            $foto = $_SESSION['userdata']['foto'];
        }
        ?>

        <div class="container mb-2 mt-5">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4 col-lg-5 col-xl-4">
                    <img src="<?= HOME_URI ?>/includes/images/logo_Educ_PNG.png" alt="Logotipo <?= CLI_NOME ?>" class="img-logo-sie">
                </div>
                <div class="col-8 col-sm-5 col-md-6 col-lg-5 col-xl-7 ">
                    <p class="text-acesso-integrado mb-0">Acesso Integrado Google For Education</p>
                    <p class="text-usuario-sie">Usuário:  <?php echo toolErp::n_pessoa() ?></p>
                </div>
                <div class="col-3 col-sm-1 col-md-2 col-lg-1 col-xl-1">
                    <img src="<?= $foto ?>" alt="Foto do Perfil de Usuário" class="w-100">
                    <a href="<?php echo HOME_URI ?>?logout=1" class="g_id_signout logout btn btn-sair-sie w-100" >
                        SAIR
                    </a>
                </div>
            </div>
        </div>

        <?php
###########################################################################################################
        $e = sql::get(['instancia', 'ge_escolas'], 'n_inst, id_inst', ['instancia.ativo' => 1, '>' => 'n_inst'], null, 'left');
        foreach ($e as $v) {
            $escolas[$v['id_inst']] = $v['n_inst'] . ' - ' . $v['id_inst'];
        }
        $sql = "select fk_id_inst from `acesso_pessoa` "
                . " WHERE  fk_id_pessoa = " . user::session('id_pessoa') . " AND fk_id_gr in( 17,26)";
        $query = autenticador::getInstance()->query($sql);
        @$instAtual = $query->fetch(PDO::FETCH_ASSOC)['fk_id_inst'];
        ?>



        <div class="bg-roxo mt-4">
            <div class="container">
                <div class="row mb-0 py-4 ">
                    <div class="col-12 col-sm-3 col-md-2 col-lg-3 col-xl-3 align-logo-faixa  px-0 ">
                        <!-- <img src="<?= HOME_URI ?>/includes/images/logo-sie-white.png" alt="Logotipo sie-ERP" class="logo-sie-faixa"> -->
                    </div>
                    <?php
                    @$acesso = $model->listSistemas();
                    if (!empty(@$acesso['suporte'])) {
                        ?>
                        <div class="col-12 col-sm-3 col-md-6 col-lg-5 col-xl-4 px-0">
                            <div class="input-group">
                                <form method="POST" id="trInst">
                                    <?=
                                    formErp::hidden(['mudaInst' => 1, 'id_pessoa' => user::session('id_pessoa'), 'tabela' => 'mudaInst'])
                                    ?>
                                    <table>
                                        <tr>
                                            <td>
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="sel">Mudar Instância do Grupo Suporte</label>
                                                </div>
                                            </td>
                                            <td>
                                                <select data-width="fit"  onchange="trInst.submit()" name="fk_id_inst" data-live-search="true"    class="selectpicker form-select-sie bg-instancia-select">
                                                    <?php
                                                    foreach ($escolas as $k => $v) {
                                                        ?>
                                                        <option value="<?= $k ?>" <?= $instAtual == $k ? 'selected' : '' ?> ><?= $v ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>
                        <?php
                        $sql = "SELECT count(id_sup) as ct FROM `dttie_suporte_trab` WHERE `status_sup` LIKE 'Aberto' AND ultimo_lado != 'Suporte'  and tipo_sup != 76 ";
                        $query = pdoSis::getInstance()->query($sql);
                        $ctAberto = $query->fetch(PDO::FETCH_ASSOC)['ct'];
                        if ($ctAberto > 0) {
                            ?>
                            <div class="alert alert-danger text-center">
                                Existem <?php echo $ctAberto ?> chamados Abertos no Suporte Técnico.
                            </div>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <br />


        <br />
        <div class="container mb-5 mt-3">
            <div class="row">
                <?php
                if (!empty($acesso)) {
                    foreach ($acesso as $k => $v) {
                        if (is_array($v)) {
                            if (count($v) == 1) {
                                $y = current($v);
                                ?>
                                <div class="col-md-4 my-1 px-1">
                                    <a href="#" class="text-icones-geral-sie d-flex align-items-center border py-3"  onclick="document.getElementById('<?php echo $k ?>').submit()">
                                        <?php
                                        if (file_exists(ABSPATH . '/views/_images/icones/sistema/' . current($v)['arquivo'] . '.png')) {
                                            ?>
                                            <img src="<?php echo HOME_URI ?>/views/_images/icones/sistema/<?php echo current($v)['arquivo'] ?>.png" style="width: 50px"/>
                                            <?php
                                        } else {
                                            ?>
                                            <img src="<?php echo HOME_URI ?>/views/_images/icones/sistema/adm.png" style="width: 50px"/>
                                            <?php
                                        }
                                        ?>
                                        <?php echo current($v)['n_sistema'] ?>
                                    </a>

                                    <form id="<?php echo $k ?>" action="<?php echo $y['end_fr'] . '/' . $y['arquivo'] . '/'; ?>" method="POST">
                                        <input type="hidden" name="userdatasis[id_sistema]" value="<?php echo $y['id_fr'] == 1 ? $k : $y['fkid'] ?>" />
                                        <input type="hidden" name="userdatasis[id_nivel]" value="<?php echo $y['id_nivel'] ?>" />
                                        <input type="hidden" name="userdatasis[id_inst]" value="<?php echo $y['id_inst'] ?>" />
                                        <input type="hidden" name="userdatasis[n_sistema]" value="<?php echo $y['n_sistema'] ?>" />
                                        <input type="hidden" name="userdatasis[n_nivel]" value="<?php echo $y['n_nivel'] ?>" />
                                        <input type="hidden" name="userdatasis[n_inst]" value="<?php echo $y['n_inst'] ?>" />
                                        <input type="hidden" name="userdatasis[tipo]" value="<?php echo $y['tipo'] ?>" />
                                        <input type="hidden" name="userdatasis[arquivo]" value="<?php echo $y['arquivo'] ?>" />
                                        <input type="hidden" name="sessionNew" value="1" />
                                        <input type="hidden" name="idx" value="<?php echo $_SESSION['userdata']['id_pessoa'] ?>" />
                                        <input type="hidden" name="token" value="<?php echo rand(1000, 9999) . $_SESSION['userdata']['user_session_id'] ?>" />
                                    </form>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="col-md-4 my-1 px-1">
                                    <div class="dropdown">
                                        <a href="#" class="text-icones-geral-sie d-flex align-items-center border py-3 dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?php
                                            if (file_exists(ABSPATH . '/views/_images/icones/sistema/' . current($v)['arquivo'] . '.png')) {
                                                ?>
                                                <img src="<?php echo HOME_URI ?>/views/_images/icones/sistema/<?php echo current($v)['arquivo'] ?>.png" style="width: 50px"/>
                                                <?php
                                            } else {
                                                ?>
                                                <img src="<?php echo HOME_URI ?>/views/_images/icones/sistema/adm.png" style="width: 50px"/>
                                                <?php
                                            }
                                            ?>
                                            <?php echo current($v)['n_sistema'] ?>
                                        </a>
                                        <form id="<?php echo $k ?>" action="<?php echo $y['end_fr'] . '/' . $y['arquivo'] . '/'; ?>" method="POST">
                                            <input type="hidden" name="userdatasis[id_sistema]" value="<?php echo $y['id_fr'] == 1 ? $k : $y['fkid'] ?>" />
                                            <input type="hidden" name="userdatasis[id_nivel]" value="<?php echo $y['id_nivel'] ?>" />
                                            <input type="hidden" name="userdatasis[id_inst]" value="<?php echo $y['id_inst'] ?>" />
                                            <input type="hidden" name="userdatasis[n_sistema]" value="<?php echo $y['n_sistema'] ?>" />
                                            <input type="hidden" name="userdatasis[n_nivel]" value="<?php echo $y['n_nivel'] ?>" />
                                            <input type="hidden" name="userdatasis[n_inst]" value="<?php echo $y['n_inst'] ?>" />
                                            <input type="hidden" name="userdatasis[tipo]" value="<?php echo $y['tipo'] ?>" />
                                            <input type="hidden" name="userdatasis[arquivo]" value="<?php echo $y['arquivo'] ?>" />
                                            <input type="hidden" name="sessionNew" value="1" />
                                            <input type="hidden" name="idx" value="<?php echo $_SESSION['userdata']['id_pessoa'] ?>" />
                                            <input type="hidden" name="token" value="<?php echo rand(1000, 9999) . $_SESSION['userdata']['user_session_id'] ?>" />
                                        </form>
                                        <div class="dropdown-menu" style="width: 100%" aria-labelledby="dropdownMenuButton">
                                            <?php
                                            foreach ($v as $ky => $y) {

                                                if (is_numeric($ky)) {
                                                    $y['systemData'] = uniqid();
                                                    ?>
                                                    <a class="dropdown-item" href="#">
                                                        <div style="width: 100%" class="text-icones-geral-sie d-flex align-items-center border py-3 " onclick="document.getElementById('<?php echo ($y['id_fr'] == 1 ? $k : $y['fkid']) . '_' . $ky ?>').submit();" >
                                                            Perfil: 
                                                            <?php echo $y['n_nivel'] ?>
                                                            <br />
                                                            Instância: 
                                                            <?php echo substr($y['n_inst'], 0, 25) ?>
                                                        </div>
                                                    </a>
                                                    <form id="<?php echo ($y['id_fr'] == 1 ? $k : $y['fkid']) . '_' . $ky ?>" action="<?php echo $y['end_fr'] . '/' . $y['arquivo'] . '/'; ?>" method="POST">
                                                        <input type="hidden" name="userdatasis[id_sistema]" value="<?php echo $y['id_fr'] == 1 ? $k : $y['fkid'] ?>" />
                                                        <input type="hidden" name="userdatasis[id_nivel]" value="<?php echo $y['id_nivel'] ?>" />
                                                        <input type="hidden" name="userdatasis[id_inst]" value="<?php echo $y['id_inst'] ?>" />
                                                        <input type="hidden" name="userdatasis[n_sistema]" value="<?php echo $y['n_sistema'] ?>" />
                                                        <input type="hidden" name="userdatasis[n_nivel]" value="<?php echo $y['n_nivel'] ?>" />
                                                        <input type="hidden" name="userdatasis[n_inst]" value="<?php echo $y['n_inst'] ?>" />
                                                        <input type="hidden" name="userdatasis[tipo]" value="<?php echo $y['tipo'] ?>" />
                                                        <input type="hidden" name="userdatasis[arquivo]" value="<?php echo $y['arquivo'] ?>" />
                                                        <input type="hidden" name="sessionNew" value="1" />
                                                        <input type="hidden" name="idx" value="<?php echo $_SESSION['userdata']['id_pessoa'] ?>" />
                                                        <input type="hidden" name="token" value="<?php echo rand(1000, 9999) . $_SESSION['userdata']['user_session_id'] ?>" />
                                                    </form>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="nfr" value="<?php echo $y['n_fr'] ?>" />
                                <?php
                            }
                        }
                    }
                }
                ?>
            </div>
        </div>
        <script>
            function versis(k) {
                $('.sub').css('display', 'none');
                if (document.getElementById(k).style.display == 'none') {
                    document.getElementById(k).style.display = '';
                }

            }
        </script>
        <div class="mb-5 pb-2 footer-space-sie"></div>
        <footer class="footer-sie-erp">
            <div class="container-fluid bg-footer ">
                <div class="row d-flex justify-content-center">
                    <div class="col-12 col-sm-6 col-md-10 col-lg-8 col-xl-6 pt-2 text-center">
                        <p class="text-copyright-sie">
                            © Copyright - <?php echo date('Y'); ?> - <?= CLI_NOME ?> - Secretaria de Educação.
                            <br>
                            <?= SISTEMA_NOME ?> - ERP  Educacional - Versão 3.0 - Abril/2022
                        </p>
                    </div>
                    <div class="col-12 col-sm-2 col-md-1 col-lg-1 col-xl-1 pt-2 px-0 mt-2 align-logo-footer">
                        <!-- <img src="<?= HOME_URI ?>/includes/images/logo-sie-white.png" alt="Logotipo sie-ERP" class="logo-sie-footer"> -->
                    </div>
                </div>
            </div>
        </footer>