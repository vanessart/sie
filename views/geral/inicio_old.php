<div class="fieldBody">
    <div class="fieldTop">
        Subsistema:
        <?php echo $_SESSION['userdata']['n_sistema'] ?>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default" style="padding-left: 8px;padding-right: 8px">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="panel panel-default"  style="height: 75vh">
                            <div class="panel-heading">
                                Conteúdo deste subsistema:  
                            </div>
                            <div class="panel-body">
                                <ul>
                                    <?php
                                    foreach ($_SESSION['userdata']['user_permissions'] AS $k => $v) {
                                        if (strpos($k, "?target") > 0) {
                                            $target = 'target="_blank"';
                                            $k = substr($k, 0, -7);
                                        } else {
                                            $target = NULL;
                                        }
                                        $mystring = $k;
                                        $findme = '|';
                                        $pos = strpos($mystring, $findme);

                                        if ($pos === false && !is_numeric($k)) {
                                            if (substr($v, 0, 4) != 'Link') {
                                                ?>  
                                                <li>
                                                    <a <?php echo $target ?> class="active text-uppercase" href="<?php echo HOME_URI; ?>/<?php echo $v ?>/" >
                                                        <?php echo $k ?>
                                                    </a>
                                                </li>
                                                <?php
                                            } else {
                                                ?>  
                                                <li>
                                                    <a target="_blank" class="active text-uppercase" href="<?php echo substr($v, 5) ?>" >
                                                        <?php echo $k ?>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                        }
                                    }
                                    foreach ($_SESSION['userdata']['user_permissions'] AS $k => $v) {
                                        $mystring = $k;
                                        $findme = '||';
                                        $pos = strpos($mystring, $findme);

                                        if ($pos !== false) {
                                            ?>
                                            <li>
                                                <a class="text-uppercase" style="font-size: 18px" href="#">
                                                    <?php echo substr($k, 0, -2) ?>
                                                </a>
                                                <ul>
                                                    <?php
                                                    //submenu do dropdown
                                                    foreach ($_SESSION['userdata']['user_permissions'] AS $kk => $vv) {
                                                        if (strpos($kk, "?target") > 0) {
                                                            $target = 'target="_blank"';
                                                            $kk = substr($kk, 0, -7);
                                                        } else {
                                                            $target = NULL;
                                                        }
                                                        $mystring = $kk;
                                                        $findme = substr($k, 0, -2) . '|>';
                                                        $pos = strpos($mystring, $findme);

                                                        if ($pos !== false) {
                                                            $pagina = explode('|>', $kk);
                                                            if (substr($v, 0, 4) != 'Link') {
                                                                ?>  
                                                                <li>
                                                                    <a <?php echo $target ?> class="text-uppercase" href="<?php echo HOME_URI; ?>/<?php echo $vv ?>/" >
                                                                        <?php echo $pagina[1] ?>
                                                                    </a>
                                                                </li>
                                                                <?php
                                                            } else {
                                                                ?>  
                                                                <li>
                                                                    <a target="_blank" class="active text-uppercase" href="<?php echo substr($v, 5) ?>" >
                                                                        <?php echo $pagina[1] ?>
                                                                    </a>
                                                                </li>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                            </li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul> 
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="panel panel-default" style="height: 35vh;">
                            <div class="panel-heading">
                                Mensagens
                            </div>
                            <div class="panel-body" style=" overflow: auto">
                                <pre style="border: none; background-color: white; min-height: 22vh"><?php echo sistema::msg(); ?></pre>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-default" style="height: 75vh;">
                            <div class="panel-heading">
                                Informações do Subsistema e Usuário
                            </div>
                            <div class="panel-body" style=" overflow: auto">
                                <table class="table table-bordered table-responsive table-striped">
                                    <tr>
                                        <td style="min-width: 25%">
                                            Nome
                                        </td>
                                        <td>
                                            <?php echo user::session('nome') ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            E-mail
                                        </td>
                                        <td>
                                            <?php echo $_SESSION['userdata']['email'] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Subsistema 
                                        </td>
                                        <td>
                                            <?php echo $_SESSION['userdata']['n_sistema'] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Nível de Acesso 
                                        </td>
                                        <td>
                                            <?php echo $_SESSION['userdata']['n_nivel'] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Instância
                                        </td>
                                        <td>
                                            <?php echo $_SESSION['userdata']['n_inst'] ?>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



</div>