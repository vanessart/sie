<?php
if (!defined('ABSPATH'))
    exit;
?>
<style>
    .fixo{
        position: fixed;
        top: 1px;
    }
</style>
<script>
    function mmenu() {
        if (document.getElementById('md').style.display == "none") {
            document.getElementById('md').style.display = "";
        } else {
            document.getElementById('md').style.display = 'none';
        }

    }
    $(document).on("scroll", function () {
        if ($(document).scrollTop() > 100) { //QUANDO O SCROLL PASSAR DOS 100px DO TOPO
            $('#nav').addClass('fixo');
        } else {
            $('#nav').removeClass('fixo');
        }
    });
</script>

<div id="art-main">
    <div class="noprint">
        <?php
        if (file_exists(ABSPATH . '/template/' . TEMPLETE . '/header.html')) {
            include ABSPATH . '/template/' . TEMPLETE . '/header.html';
        }
        ?>
        <nav id="nav" class="art-nav" style="height: 44px">
            <ul class="art-hmenu">
                <?php
                if (!empty($itemNenu)) {
                    if (is_array($itemNenu)) {
                        foreach ($itemNenu as $item) {
                            ?>
                            <li>
                                <?php echo $item ?>
                            </li>
                            <?php
                        }
                    } else {
                        ?>
                        <li>
                            <?php echo $itemNenu ?>
                        </li>
                        <?php
                    }
                }
                $sisFile = explode('/', @$_GET['path'])[0];
                $file = ABSPATH . '/module/' . $sisFile . '/menu.php';
                if (file_exists($file)) {
                    include $file;
                    if (!empty($menu[@$_SESSION['userdata']['id_nivel']])) {
                        foreach ($menu[@$_SESSION['userdata']['id_nivel']] as $k => $v) {
                            if (!is_numeric($k)) {
                                if (empty($v['page'])) {
                                    ?>
                                    <li>
                                        <a <?php echo @$v['target'] == 1 ? 'target="_blank"' : '' ?> class="active text-uppercase" href="<?php echo HOME_URI; ?>/<?php echo $v['url'] ?>/" >
                                            <?php echo $k ?>
                                        </a>
                                    </li>
                                    <?php
                                } else {
                                    ?>
                                    <li>
                                        <a class="text-uppercase" href="#" >
                                            <?php
                                            echo $k;
                                            ?>
                                        </a>
                                        <ul>
                                            <?php
                                            foreach ($v['page'] as $kk => $vv) {
                                                ?>
                                                <li>
                                                    <a <?php echo @$vv['target'] == 1 ? 'target="_blank"' : '' ?> class="active text-uppercase" href="<?php echo HOME_URI; ?>/<?php echo $vv['url'] ?>/" >
                                                        <?php echo $kk ?>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                    <?php
                                }
                            }
                        }
                    }
                }
                //itens fixo do menu
                if (!empty($_SESSION['userdata']['id_pessoa'])) {
                    if (empty($_SESSION['userdata']['user_permissions'])) {
                        ?>
                        <li>
                            <a href="<?php echo HOME_URI; ?>/geral/userconf"> 
                                CONFIGURAÇÕES DO USUÁRIO
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo HOME_URI; ?>">
                                TROCAR DE SISTEMA
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="logout">
                                SAIR
                            </a>
                        </li>
                        <?php
                    } else {
                        ?> 
                        <li>
                            <a href="#s">
                                MAIS
                            </a>
                            <ul>
                                <?php
                                if (NULL) {
                                    ?>
                                    <li>
                                        <a href="#">
                                            SUPORTE
                                        </a>
                                        <ul>
                                            <li>
                                                <a href="<?php echo HOME_URI; ?>/dttie/escolapesq">
                                                    PESQUISAR
                                                </a>
                                            </li> 
                                            <li>
                                                <a href="<?php echo HOME_URI; ?>/dttie/escola">    
                                                    NOVO PEDIDO
                                                </a>
                                            </li>                       
                                        </ul>
                                    </li>
                                    <?php
                                }
                                ?>
                                <li>
                                    <a href="<?php echo HOME_URI; ?>/geral/userconf">    
                                        CONFIGURAÇÕES DO USUÁRIO
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo HOME_URI; ?>">
                                        TROCAR DE SISTEMA
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="logout">
                                        SAIR
                                    </a>
                                </li> 
                            </ul>
                        </li>

                        <?php
                    }
                }
                ?>
            </ul>
            <?php
            if (!empty($_SESSION['userdata']['user_permissions'])) {
                ?>
                <div style="color: white;  text-align: center; padding-top: 10px;  font-size: 18px; font-weight: bold; float: left; width: 20%">
                    <?php echo @$_SESSION['userdata']['n_sistema'] ?>
                </div>
                <div style="color: white; text-align: right; padding-top: 3px; padding-right: 30px; font-size: 12px; font-weight: bold; float: right">
                    Usuário: <?php echo user::session('n_pessoa') ?>
                    <br />
                    <?php echo @$_SESSION['userdata']['n_inst'] ?>
                </div>
                <?php
            }
            ?>
            &nbsp;

        </nav>
    </div>
</header>
<div class="art-sheet clearfix">


