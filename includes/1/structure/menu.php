<?php
if (!defined('ABSPATH'))
    exit();
if (empty($_SESSION['userdata']['foto'])) {
    $foto = HOME_URI .'/'. INCLUDE_FOLDER .'/images/user-anonimo.jpg';
} else {
    $foto = $_SESSION['userdata']['foto'];
}
?>

<style>
    #dataTop{
        color: #004573; 
        text-align: left; 
        padding-right: 30px; 
        font-size: 15px; 
        font-weight: bold; 
        width: 100%;
    }
</style>
    <div class="small">
        <div style="float: left;">
            <table style="color: #004573; font-size: 20px; font-weight: bold;" >
                <tr>
                    <td>
                        <img src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/brasao.png"/>
                    </td>
                    <td>
                        <img src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/topo.png"/>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="medium">
        <div style="float: left; width: 35%">
            <table style="color: #004573; font-size: 20px; font-weight: bold;" >
                <tr>
                    <td>
                        <img src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/brasao.png"/>
                    </td>
                    <td>
                        <img src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/topo.png"/>
                    </td>
                </tr>
            </table>
        </div>
        <div style="float: left; width: 65%">
            <div style="text-align: center;font-size: 18px; font-weight: bold; padding-top: 10px; padding-left: 30px; padding-right: 10px">
                <?php echo @$_SESSION['userdata']['n_sistema'] ?>
            </div>
        </div>
    </div>
    <div class="big">
        <div style="float: left; width: 25%">
            <table style="color: #004573; font-size: 20px; font-weight: bold;" >
                <tr>
                    <td>
                        <img src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/brasao.png"/>
                    </td>
                    <td>
                        <img src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/topo.png"/>
                    </td>
                </tr>
            </table>
        </div>
        <div style="float: left; width: 25%">
            <div style="text-align: center;font-size: 18px; font-weight: bold; padding-top: 10px; padding-left: 30px; padding-right: 10px">
                <?php echo @$_SESSION['userdata']['n_sistema'] ?>
            </div>
        </div>
        <div style="float: left; width: 50%">
            <table id="dataTop" >
                <tr>
                    <td>
                        Usuário:
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        <?php echo @$_SESSION['userdata']['n_pessoa'] ?>
                    </td>
                    <td>
                        &nbsp; &nbsp; &nbsp;
                    </td>
                    <td>
                        Perfil: 
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        <?php echo @$_SESSION['userdata']['n_nivel'] ?>
                    </td>
                    <td rowspan="2" style="width: 65px">
                        <div style="padding: 10px; width: 100%; text-align: center">
                            <img style="width: 65px" id="user-photo" src="<?= $foto ?>">
                            <a href="<?= HOME_URI ?>/geral/userconfNew" class="btn btn-secondary btn-sm" style="padding: 0px 6px;margin-top: 0.1em; width: 65px">&#x2699;</a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        Subsistema:
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        <?php echo @$_SESSION['userdata']['n_sistema'] ?>
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
                        <?php echo @$_SESSION['userdata']['n_inst'] ?>
                    </td>
                </tr>
            </table>
        </div>
        <div style="clear: both"></div>
    </div>
    <nav class="navbar navbar-expand-sm navbar-light bg-light sticky-top" style="width: 100%; height: 30px">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent" style="background-color: #DCDCDC; height: 23px; color: black">
            <ul class="navbar-nav mr-auto">
                <?php
                $sisFile = explode('/', @$_GET['path'])[0];
                $file = ABSPATH . '/module/' . $sisFile . '/menu.php';
                if (file_exists($file)) {
                    include $file;
                }
                $ct = 1;
                if (!empty($menu[@$_SESSION['userdata']['id_nivel']])) {
                    foreach ($menu[@$_SESSION['userdata']['id_nivel']] as $k => $v) {
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
                        <!--
                        <a class="dropdown-item" title="Configurações do Usuário" href="<?= HOME_URI ?>/home/user">
                            Configurações do Usuário
                        </a>
                        -->
                        <a class="dropdown-item" title="Trocar de Subsistema" onclick="document.getElementById('trocar').submit();" href="#">
                            Trocar de Subsistema
                        </a>
                        <?php
                       // if (!empty(API_GOOGLE)) {
                            ?>
                            <!-- <a href="<?php //echo HOME_URI ?>?logout=1" class="g_id_signout logout dropdown-item">
                                Sair
                            </a> -->
                            <?php
                       // } else {
                            ?>
                           <!--  <a href="#" onclick="$('#sair').submit()" class="logout dropdown-item">
                                Sair
                            </a> -->
                            <?php
                       // }
                        ?>
                    </div>
                </li>
            </ul>
        </div> 
    </nav>

<form id="sair" action="<?php echo HOME_URI ?>/home" method="POST">
    <input type="hidden" value="1" name="exit" />
</form>
<form id="trocar" action="<?php echo HOME_URI ?>/home" method="POST">
    <input type="hidden" value="1" name="changeSystem" />
</form>

