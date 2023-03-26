<?php
if (!defined('ABSPATH'))
    exit;
if (!empty(tool::id_inst()) && ((!empty($sim) && date("i") % 5 == 0) || !empty($ss))) {
    $sql = "SELECT "
            . " p.id_pessoa, p.n_pessoa "
            . " FROM ge_turmas t "
            . " JOIN ge_turma_aluno ta on ta.fk_id_turma = t.id_turma "
            . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
            . " WHERE t.fk_id_inst = " . tool::id_inst()
            . " and fk_id_pl in (24,25) "
            . " AND ( p.n_pessoa like '% rm %' or p.n_pessoa like '%-%' or p.n_pessoa like '%(%' ) ORDER BY `p`.`n_pessoa` DESC ";
    $query = $model->db->query($sql);
    $array = $query->fetchAll();
    if (!empty($array)) {
        foreach ($array as $v) {
            $corrigir[] = $v['id_pessoa'];
        }
        tool::modalInicio();
        ?>
        <div style="text-align: center; font-size: 25px">
            Atenção!! ESTA ESCOLA TEM <?php echo count($array) ?> ALUNOS COM NOMES ERRADOS.
            <br />
            CORRIJA IMEDIATAMENTE
            <br />
            RSE DOS ALUNOS:
            <br />
            <div style="text-align: left">
                <?php echo implode('; ', $corrigir) ?>
            </div>
        </div>
        <?php
        tool::modalFim();
    }
}
?>
<script>
    function mmenu() {
        if (document.getElementById('md').style.display == "none") {
            document.getElementById('md').style.display = "";
        } else {
            document.getElementById('md').style.display = 'none';
        }

    }
</script>

<div id="art-main">
    <div class="noprint">
        <?php
        if (file_exists(ABSPATH . '/template/' . TEMPLETE . '/header.html')) {
            include ABSPATH . '/template/' . TEMPLETE . '/header.html';
        }
        ?>
        <nav class="art-nav" style="min-height: 44px">
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
                //integração com sistema novo
                if (!empty($_GET['path'][0])) {
                    $sisFile = explode('/', $_GET['path'])[0];
                } else {
                    $sisFile = null;
                }
                $file = ABSPATH . '/module/' . $sisFile . '/menu.php';
                if (file_exists($file)) {
                    ##############################################################################
                    include $file;
                    foreach ($menu[@$_SESSION['userdata']['id_nivel']] as $k => $v) {
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
                    ##############################################################################
                } else {
                    //intens simples do menu
                    if (!empty($_SESSION['userdata']['user_permissions'])) {
                        foreach ($_SESSION['userdata']['user_permissions'] AS $k => $v) {
                            $mystring = $k;
                            $findme = '|';
                            $pos = strpos($mystring, $findme);

                            if ($pos === false && !is_numeric($k) && !empty($k)) {
                                if (strpos($k, "?target") > 0) {
                                    $target = 'target="_blank"';
                                    $k = substr($k, 0, -7);
                                } else {
                                    $target = NULL;
                                }
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
                                        <a  target="_blank" class="active text-uppercase" href="<?php echo substr($v, 5) ?>" >
                                            <?php echo $k ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                        }
                        //itens do menu dropdown
                        foreach ($_SESSION['userdata']['user_permissions'] AS $k => $v) {
                            $mystring = $k;
                            $findme = '||';
                            $pos = strpos($mystring, $findme);

                            if ($pos !== false) {
                                ?>
                                <li>
                                    <a class="text-uppercase" href="#">
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
                                                if (substr($vv, 0, 4) != 'Link') {
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
                                                        <a target="_blank" class="active text-uppercase" href="<?php echo substr($vv, 5) ?>" >
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
                            <a href="<?php echo HOME_URI ?>?logout=1" class="g_id_signout logout">
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
                                    <a href="<?php echo HOME_URI ?>?logout=1" class="g_id_signout logout">
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


