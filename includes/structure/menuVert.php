<?php
if (empty($_SESSION['userdata']['foto'])) {
    $foto = HOME_URI. '/includes/images/user-anonimo.jpg';
} else {
    $foto = $_SESSION['userdata']['foto'];
}
$background = 'white';
$color = '#000000';
$button = 'btn-outline-dark';
?>
<style>
    #user-photo{
        border-radius: 50px;   
        width: 50px;
    }

    #top-off{
        display: none;  
    }
    #top{
        background-color: <?= $background ?>;
        color: <?= $color ?>;
        width: 100%; 
        height: 70px; 
        position: fixed; 
        z-index: 999;
        border-bottom: #cce5ff solid 2px;
        top: 0;
    }

    .titulo{
        font-weight: bold;
        text-align: center;
        padding-bottom: 10px;
    }

    .fotoGoogle{
        width: 36px;
        border-radius: 50%;
    }

    .tabela{
        width: 90%;
        height: 50px;
    }

    #menuToggle
    {
        display: block;
        position: relative;
        top: 3px;
        left: 10px;

        z-index: 1;

        -webkit-user-select: none;
        user-select: none;
    }

    #menuToggle a
    {
        text-decoration: none;
        color: #000000;
        transition: color 0.3s ease;
    }

    #menuToggle a:hover
    {
        color: tomato;
    }


    #menuToggle input
    {
        display: block;
        width: 40px;
        height: 32px;
        position: absolute;
        top: -7px;
        left: -5px;
        cursor: pointer;
        opacity: 0; /* hide this */
        z-index: 2; /* and place it over the hamburger */
        -webkit-touch-callout: none;
    }

    /*
     * Just a quick hamburger
     */
    #menuToggle span
    {
        display: block;
        width: 33px;
        height: 4px;
        margin-bottom: 5px;
        position: relative;

        background: #000000;
        border-radius: 3px;

        z-index: 1;

        transform-origin: 4px 0px;

        transition: transform 0.5s cubic-bezier(0.77,0.2,0.05,1.0),
            background 0.5s cubic-bezier(0.77,0.2,0.05,1.0),
            opacity 0.55s ease;
    }

    #menuToggle span:first-child
    {
        transform-origin: 0% 0%;
    }

    #menuToggle span:nth-last-child(2)
    {
        transform-origin: 0% 100%;
    }

    /* 
     * Transform all the slices of hamburger
     * into a crossmark.
     */
    #menuToggle input:checked ~ span
    {
        opacity: 1;
        transform: rotate(45deg) translate(-2px, -1px);
        background: #000000;
    }

    /*
     * But let's hide the middle one.
     */
    #menuToggle input:checked ~ span:nth-last-child(3)
    {
        opacity: 0;
        transform: rotate(0deg) scale(0.2, 0.2);
    }

    /*
     * Ohyeah and the last one should go the other direction
     */
    #menuToggle input:checked ~ span:nth-last-child(2)
    {
        transform: rotate(-45deg) translate(0, -1px);
    }

    /*
     * Make this absolute positioned
     * at the top left of the screen
     */
    #menu
    {
        position: absolute;
        width: 300px;
        height: 85vh;
        overflow: auto;
        margin: 20px 0 0 -50px;
        padding-left: 50px;
        background-color: rgba(245, 245, 245, 0.9);
        list-style-type: none;
        -webkit-font-smoothing: antialiased;
        /* to stop flickering of text in safari */

        transform-origin: 0% 0%;
        transform: translate(-100%, 0);

        transition: transform 0.5s cubic-bezier(0.77,0.2,0.05,1.0);
    }

    #menu li
    {
        padding: 10px 0;
        font-size: 18px;
    }

    /*
     * And let's slide it in from the left
     */
    #menuToggle input:checked ~ ul
    {
        transform: none;
    }
</style>
<div id="top">
    <table class="tabela" style="width: 100%;">
        <tr>
            <td style="width: 100px; text-align: left">
                <nav role="navigation">
                    <div id="menuToggle">

                        <input type="checkbox" />

                        <span></span>
                        <span></span>
                        <span></span>

                        <ul id="menu" class="border">
                            <?php
                            $sisFile = explode('/', @$_GET['path'])[0];
                            $file = ABSPATH . '/module/' . $sisFile . '/menu.php';
                            if (file_exists($file)) {
                                include $file;
                                if (!empty($menu[@$_SESSION['userdata']['id_nivel']])) {
                                    foreach ($menu[@$_SESSION['userdata']['id_nivel']] as $k => $v) {
                                        if (!is_numeric($k)) {
                                            if (empty($v['page'])) {
                                                ?>
                                                <a <?php echo!empty($v['target']) ? 'target="' . $v['target'] . '"' : '' ?>  href="<?php echo substr(@$v['url'], 0, 4) != 'http' ? HOME_URI . @$v['url'] : @$vv['url']; ?>">
                                                    <li>                                             
                                                        <?php echo @$k ?>
                                                    </li>
                                                </a>
                                                <?php
                                            } else {
                                                ?>
                                                <li class="dropdown">
                                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <?php echo @$k ?> 
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                        <?php
                                                        foreach ($v['page'] as $kk => $vv) {
                                                            ?>
                                                            <a class="dropdown-item" title="<?php echo @$kk ?>" <?php echo!empty($vv['target']) ? 'target="' . $vv['target'] . '"' : '' ?> href="<?php echo substr(@$vv['url'], 0, 4) != 'http' ? HOME_URI . @$vv['url'] : @$vv['url']; ?>">
                                                                <?php echo @$kk ?>
                                                            </a>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </li>
                                                <?php
                                            }
                                        }
                                    }
                                }
                            }
                            ?>
                            <!--
    <a href="<?= HOME_URI ?>/home/perfilVert">
        <li>
            Configurações do Usuário
        </li>
    </a>
                            -->
                            <a onclick="document.getElementById('trocar').submit();" href="#">
                                <li>
                                    Trocar de Subsistema
                                </li>
                            </a>
                             <!-- <a href="<?php //echo HOME_URI ?>?logout=1" class="g_id_signout logout">
                                 <li>
                                 Sair
                                 </li>
                            </a> -->
                        </ul>
                    </div>
                </nav>
            </td>
            <td style="text-align: center; font-weight: bold; color: #000000">
                <img src="<?= HOME_URI ?>/includes/images/brasao.png" style="height: 68px" alt="<?= NOME3 ?>"/>
            </td>
            <td style="width: 100px; text-align: right">
                <div style="padding: 10px; width: 100%; text-align: right">
                    <img id="user-photo" src="<?= $foto ?>">
                </div>
            </td>
        </tr>
    </table>

</div>
<form id="sair" action="<?php echo HOME_URI ?>/home" method="POST">
    <input type="hidden" value="1" name="exit" />
</form>
<form id="trocar" action="<?php echo HOME_URI ?>/home" method="POST">
    <input type="hidden" value="1" name="changeSystem" />
</form>
<div style="height: 80px"></div>
