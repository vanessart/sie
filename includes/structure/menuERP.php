<?php
/**
 * Menu dinanmico gerado a partir do arquivo modole/<subsistema>/menu.php
 */
if (!defined('ABSPATH'))
    exit;

if (empty($_SESSION['userdata']['foto'])) {
    $foto = HOME_URI. '/includes/images/user-anonimo.jpg';
} else {
    $foto = $_SESSION['userdata']['foto'];
}

if (@$setPage != 'home') {
    if ($this->logged && @$setPage != 'access' && !empty($_SESSION['systemData'])) {

        if (file_exists(ABSPATH . '/includes/images/logosystem/' . @$_SESSION['systemData']['arquivo'] . '.png')) {
            $logo = HOME_URI . '/includes/images/logosystem/' . @$_SESSION['systemData']['arquivo'] . '.png';
        }elseif (!empty (main::dominioInst(tool::id_inst())['img'])) {
            $logo =main::dominioInst(tool::id_inst())['img'];
        } 
        
        else {
            $logo = LOGO;
        }
        ?>    
        <div class="row">
            <div class="col-sm-3">
                <table style="color: #004573; font-size: 20px; font-weight: bold;" >
                    <tr>
                        <td>
                            <img style="<?= LOGO_STYLE ?>" src="<?php echo $logo ?>"/>
                            <?php
                            if (!empty(NOME_LOGO)) {
                                ?> 
                                <span>
                                    <?php echo NOME_LOGO ?>
                                </span>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-3" class="medium">
                <div class="medium" style="text-align: center;font-size: 18px; font-weight: bold; padding-top: 10px; padding-left: 20px; padding-right: 20px">
                    <?php echo @$_SESSION['systemData']['n_sistema'] ?>
                </div>
            </div>
            <div class="col-sm-6" class="medium">
                <table style="color: #004573; text-align: left; padding-right: 30px; font-size: 12px; font-weight: bold; width: 100%" >
                    <tr>
                        <td class="medium">
                            Usuário:
                        </td>
                        <td class="medium">
                            &nbsp;
                        </td>
                        <td class="medium">
                            <?php echo @$_SESSION['userdata']['n_pessoa'] ?>
                        </td>
                        <td class="medium">
                            &nbsp; &nbsp; &nbsp;
                        </td>
                        <td class="medium">
                            Perfil: 
                        </td>
                        <td class="medium">
                            &nbsp;
                        </td>
                        <td class="medium">
                            <?php echo @$_SESSION['systemData']['n_nivel'] ?>
                        </td>
                        <td rowspan="2" class="medium">
                            <div style="padding: 10px; width: 100%; text-align: right">
                                <img style="width: 80px" id="user-photo" src="<?= $foto ?>">
                            </div>
                        </td>
                    </tr>
                    <tr class="medium">
                        <td>
                            Subsistema:
                        </td>
                        <td>
                            &nbsp;
                        </td>
                        <td>
                            <?php echo @$_SESSION['systemData']['n_sistema'] ?>
                        </td>
                        <td>
                            &nbsp; &nbsp; &nbsp;
                        </td>
                        <td>
                            Unidade:
                        </td>
                        <td>
                            &nbsp;
                        </td>
                        <td>
                            <?php echo @$_SESSION['systemData']['n_inst'] ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
    } elseif (@$setPage == 'access') {
        ?>
        <table style="width: 100%">
            <tr>
                <td>
                    <table style="color: #004573; font-size: 20px; font-weight: bold;" >
                        <tr>
                            <td>
                                <img style="<?= LOGO_STYLE ?>" src="<?php echo LOGO ?>"/>
                                <?php
                                if (!empty(NOME_LOGO)) {
                                    ?> 
                                    <span>
                                        <?php echo NOME_LOGO ?>
                                    </span>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="medium" style="font-size: 22px; padding: 20px">
                    Olá <?php echo tool::session('nome') ?>,
                    obrigado por utilizar nosso sistema

                </td>
                <td>
                    <table style="width: 100%">
                        <tr class="medium">
                            <td></td>
                            <td style="width: 1px">
                                <img style="width: 120px" id="user-photo" src="<?= $foto ?>">
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <?php
                                if (!empty(API_GOOGLE)) {
                                    ?>
                                    <a style="width: 100%" href="<?php echo HOME_URI ?>?logout=1" class="g_id_signout logout btn btn-outline-secondary">
                                        Sair
                                    </a>  
                                    <?php
                                } else {
                                    ?>
                                    <a style="width: 100%" href="#" onclick="$('#sair').submit()" class="btn btn-outline-secondary">
                                        Sair
                                    </a>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <?php
    }
}
if (!empty($_SESSION['systemData']) && @$setPage != 'access') {
    $file = ABSPATH . '/module/' . @$_SESSION['systemData']['arquivo'] . '/menu.php';
    if (file_exists($file)) {
        ?>
        <nav class="navbar navbar-expand-sm navbar-light bg-light sticky-top" style="width: 100%; height: 30px">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent" style="background-color: #DCDCDC; height: 23px; color: black">
                <ul class="navbar-nav mr-auto">
                    <?php
                    include $file;
                    $ct = 1;
                    if (!empty($menu[@$_SESSION['systemData']['id_nivel']])) {
                        foreach ($menu[@$_SESSION['systemData']['id_nivel']] as $k => $v) {
                            if (!is_numeric($k)) {
                                if (!empty($v['page'])) {
                                    ?>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?php echo @$k ?> 
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <?php
                                            foreach ($v['page'] as $kk => $vv) {
                                                ?>
                                                <a class="dropdown-item" title="<?php echo @$kk ?>" <?php echo @$vv['target'] == 1 ? 'target="_blank"' : '' ?> href="<?php echo substr(@$vv['url'], 0, 4) != 'http' ? HOME_URI . @$vv['url'] : @$vv['url']; ?>">
                                                    <?php echo @$kk ?>
                                                </a>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </li>
                                    <?php
                                } else {
                                    ?>
                                    <li class="nav-item active">
                                        <a class="nav-link"  title="<?php echo @$k ?>"  <?php echo @$v['target'] == 1 ? 'target="_blank"' : '' ?>  href="<?php echo substr(@$v['url'], 0, 4) != 'http' ? HOME_URI . @$v['url'] : @$vv['url']; ?>">
                                            <?php echo @$k ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                        }
                    }
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Mais 
                            <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" title="Configurações do Usuário" href="<?= HOME_URI ?>/home/user">
                                Configurações do Usuário
                            </a>
                            <a class="dropdown-item" title="Trocar de Subsistema" onclick="document.getElementById('trocar').submit();" href="#">
                                Trocar de Subsistema
                            </a>
                            <?php
                            if (!empty(API_GOOGLE)) {
                                ?>
                                <a href="<?php echo HOME_URI ?>?logout=1" class="g_id_signout logout dropdown-item">
                                    Sair
                                </a>
                                <?php
                            } else {
                                ?>
                                <a href="#" onclick="$('#sair').submit()" class="logout dropdown-item">
                                    Sair
                                </a>
                                <?php
                            }
                            ?>
                        </div>
                    </li>
                </ul>
            </div> 
        </nav>
        <?php
    }
}
?>

<form id="sair" action="<?php echo HOME_URI ?>/home" method="POST">
    <input type="hidden" value="1" name="exit" />
</form>
<form id="trocar" action="<?php echo HOME_URI ?>/home" method="POST">
    <input type="hidden" value="1" name="changeSystem" />
</form>






