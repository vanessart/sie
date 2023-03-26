<br /><br />
<div class="fieldTop">
    Subsistema:
    <?php echo $_SESSION['userdata']['n_sistema'] ?>
</div>
<br /><br /><br /><br />
<div class="field">

    <ul>
        <?php
        if ($_REQUEST['dropdow'] == "mais") {
            ?>
            <li>
                <a class="text-primary text-uppercase" style="font-size: 25px" href="#">
                    MAIS
                </a>
                <ul>
                    <li>
                        <a href="<?php echo AUT_HOME_URI; ?>/geral/userconf">    
                            CONFIGURAÇÕES DO USUÁRIO
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo AUT_HOME_URI; ?>">
                            TROCAR
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo AUT_HOME_URI; ?>/home?logout=1">
                            SAIR
                        </a>
                    </li> 
                </ul>
            </li>
            <?php
        } else {
            foreach ($_SESSION['userdata']['user_permissions'] AS $k => $v) {
                if ($_REQUEST['dropdow'] == substr($k, 0, -2)) {
                    $mystring = $k;
                    $findme = '||';
                    $pos = strpos($mystring, $findme);

                    if ($pos !== false) {
                        ?>
                        <li>
                            <a class="text-primary text-uppercase" style="font-size: 25px" href="#">
                                <?php echo substr($k, 0, -2) ?>
                            </a>
                            <br /><br />
                            <ul>
                                <?php
                                //submenu do dropdown
                                foreach ($_SESSION['userdata']['user_permissions'] AS $kk => $vv) {
                                    $mystring = $kk;
                                    $findme = substr($k, 0, -2) . '|>';
                                    $pos = strpos($mystring, $findme);

                                    if ($pos !== false) {
                                        $pagina = explode('|>', $kk)
                                        ?>
                                        <li>
                                            <a class="text-uppercase" href="<?php echo HOME_URI; ?>/<?php echo $vv ?>/" <?php echo (strpos($vv, "?target") > 0 ? "target='_blank'" : '') ?>>

                                                <?php echo $pagina[1] ?>
                                            </a>
                                            <br /><br />
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                    }
                }
            }
        }
        ?>
    </ul>
</div>