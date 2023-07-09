<?php
$file = ABSPATH . '/module/' . @$_SESSION['systemData']['arquivo'] . '/menu.php';
if (file_exists($file)) {
    ?>
    <nav id="access" class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

        </div>
        <div id="bs-example-navbar-collapse-1" class="collapse navbar-collapse">
            <ul id="menu-head-access" class="nav navbar-nav">
                <?php
                include $file;
                $ct = 1;
                foreach ($menu[@$_SESSION['systemData']['id_nivel']] as $k => $v) {
                    if (!is_numeric($k)) {
                        if (!empty($v['page'])) {
                            ?>
                            <li id="menu-item-22" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-22 dropdown">
                                <a title="Dropdown" href="#" data-toggle="dropdown" class="dropdown-toggle" aria-haspopup="true">
                                    <?php echo @$k ?> 
                                    <span class="caret"></span>
                                </a>
                                <ul role="menu" class=" dropdown-menu">
                                    <?php
                                    foreach ($v['page'] as $kk => $vv) {
                                        ?>
                                        <li id="menu-item-27" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-27">
                                            <a title="<?php echo @$kk ?>" <?php echo @$vv['target'] == 1 ? 'target="_blank"' : '' ?> href="<?php echo substr(@$vv['url'], 0, 4) != 'http' ? HOME_URI . @$vv['url'] : @$vv['url']; ?>">
                                                <?php echo @$kk ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                            <?php
                        } else {
                            ?>
                            <li id="menu-item-21" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-21">
                                <a title="<?php echo @$k ?>"  <?php echo @$v['target'] == 1 ? 'target="_blank"' : '' ?> style="color: #128812; font-weight: bold" href="<?php echo substr(@$v['url'], 0, 4) != 'http' ? HOME_URI . @$v['url'] : @$vv['url']; ?>">
                                    <?php echo @$k ?>
                                </a>
                            </li>
                            <?php
                        }
                    }
                }
                ?>
                <li id="menu-item-22" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-22 dropdown">
                    <a title="Mais" href="#" data-toggle="dropdown" class="dropdown-toggle" aria-haspopup="true">
                        Mais 
                        <span class="caret"></span>
                    </a>
                    <ul role="menu" class=" dropdown-menu">
                        <li id="menu-item-27" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-27">
                            <a title="Configurações do Usuário" href="Configurações do Usuário">
                                Configurações do Usuário
                            </a>
                        </li>
                        <li id="menu-item-27" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-27">
                            <a title="Trocar de Subsistema" onclick="document.getElementById('trocar').submit();" href="#">
                                Trocar de Subsistema
                            </a>
                        </li>
                        <li id="menu-item-27" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-27">
                            <a title="Sair" onclick="document.getElementById('sair').submit();" href="#">
                                Sair
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>    
    </nav> <!-- #access .navbar -->
    <?php
}
?>
