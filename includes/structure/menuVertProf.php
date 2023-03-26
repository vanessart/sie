<?php
if (empty($_SESSION['userdata']['foto'])) {
    $foto = HOME_URI . '/includes/images/user-anonimo.jpg';
} else {
    $foto = $_SESSION['userdata']['foto'];
}
$tp_prof = $_SESSION['userdata']['profTp'];
if ($tp_prof == 1 || $tp_prof == 2) {
    $msg = sql::get('profe_msg_prof', 'IF(dt_inicio = DATE(NOW()), "1", "0") AS novo,n_mp,descr_mp,tp_prof', 'WHERE at_mp = 1 AND DATE(NOW()) BETWEEN dt_inicio AND dt_fim AND tp_prof like "%'.$tp_prof.'%" ORDER BY dt_inicio DESC' );
}else{
    $msg = sql::get('profe_msg_prof', 'IF(dt_inicio = DATE(NOW()), "1", "0") AS novo,n_mp,descr_mp,tp_prof', 'WHERE at_mp = 1 AND DATE(NOW()) BETWEEN dt_inicio AND dt_fim ORDER BY dt_inicio DESC');
}

if (!empty($msg)) {
    foreach ($msg as $v) {
        if ($v['novo'] == 1) {
            $iconMsg = HOME_URI.'/includes/images/msgRed.png';
        }else{
            $iconMsg = HOME_URI.'/includes/images/msgGreen.png'; 
        } 
        break; 
    }  
}else{
    $iconMsg = HOME_URI.'/includes/images/msgGreen.png'; 
}

$background = 'white';
$color = '#000000';
$button = 'btn-outline-dark';
?>
<style>
    .card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
}
    .border-left-danger {
        border-left: 0.25rem solid #e74a3b!important;
    }
        .border-left-info {
        border-left: 0.25rem solid #36b9cc!important;
    }
        .border-left-success {
        border-left: 0.25rem solid #1cc88a!important;
    }
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
                <!--
                <div style="padding: 10px; width: 100%; text-align: right">
                    <a href="#" onclick=" $('#ajudaVidio').modal('show');$('.form-class').val('')">
                        <img src="<?= HOME_URI ?>/includes/images/video-help.png">
                    </a>
                </div>
                -->
            </td>
            <td style="width: 100px; text-align: right">
                <div style="padding: 10px; width: 100%; text-align: right">
                    <a href="#" onclick=" $('#msg').modal('show');$('.form-class').val('')">
                        <img style="height: 50px" src="<?= $iconMsg ?>">
                    </a>
                </div>
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

<!-- #################### MODAL MENSAGENS #######################-->
<?php
toolErp::modalInicio(null,null,'msg','Mensagens');
if (!empty($msg)) {
    foreach ($msg as $v) {
        $class = $v['novo']==1?'danger':'success';
        ?>
        <div class="card border-left-<?= $class ?> shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-<?= $class ?> mb-1">
                             <?= $v['n_mp'] ?>
                        </div>
                        <div class="text-xs font-weight-bold text-gray-800"><?= $v['descr_mp'] ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
        <br> 
        <?php
    }
}else{
    toolErp::divAlert('warning','Olá! Aqui você encontrará informações sobre as novidades do Sistema.');
}
toolErp::modalFim();
?>
<?php
/**
?>
<div class="modal fade" id="ajudaVidio" tabindex="-1" aria-labelledby="ajudaVidioLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ajudaVidioLabel">Ajuda  </h5>
                <button onclick="fechaModal877()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <table style="width: 100%">
                    <tr>
                        <td style="width: 60%">
                            <iframe id="ajvd726355" name="ajvd726355" style="width: 100%; height: 80vh" src="https://www.youtube.com/embed/A5Y3P99Ua7I" title="YouTube video player" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </td>
                        <td style="width: 40%; padding: 20px; ">
                            <div style="height: 80vh; overflow: auto; padding-right: 10px">
                                <a class="ajdvvd" target="ajvd726355" href="https://www.youtube.com/embed/A5Y3P99Ua7I">
                                    <p  class="border">
                                        Apresentação Geral Diário de Classe Digital
                                    </p>
                                </a>
                                <a class="ajdvvd" target="ajvd726355" href="https://www.youtube.com/embed/qsz5ZBnm6aI">
                                    <p  class="border">
                                        Educação Infantil - Diário de Classe apresentação geral
                                    </p>
                                </a>
                                <a target="ajvd726355" href="https://www.youtube.com/embed/7Oh4ZD7yvFs">
                                    <p  class="border">
                                        Lançamento de frequência
                                    </p>
                                </a>
                                <a class="ajdvvd" target="ajvd726355" href="https://www.youtube.com/embed/lhBn6Oiq6J8">
                                    <p  class="border">
                                        Como acessar minhas turmas
                                    </p>
                                </a>
                                <a class="ajdvvd" target="ajvd726355" href="https://www.youtube.com/embed/vHA_CQNf6pk">
                                    <p  class="border">
                                        Como realizar o registro da habilidade do dia
                                    </p>
                                </a>
                                <a class="ajdvvd" target="ajvd726355" href="https://www.youtube.com/embed/d02bWndZ5MA">
                                    <p  class="border">
                                        Acessando e criando planos de aulas
                                    </p>
                                </a>
                                <a class="ajdvvd" target="ajvd726355" href="https://www.youtube.com/embed/I-NyKbeTSww">
                                    <p  class="border">
                                        Acessando e criando a adaptação curricular
                                    </p>
                                </a>
                                <a class="ajdvvd" target="ajvd726355" href="https://www.youtube.com/embed/sr-JvgLS4HQ">
                                    <p  class="border">
                                        Instrumentos Avaliativos - Criando e lançando notas
                                    </p>
                                </a>
                                <a class="ajdvvd" target="ajvd726355" href="https://www.youtube.com/embed/8CDOPFr8rXs">
                                    <p  class="border">
                                        Educação Infantil - Como criar, registrar e avaliar um projeto.
                                    </p>
                                </a>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
toolErp::modalFim();
?>
<script>
    function fechaModal877() {
        document.getElementById('ajvd726355').src = "https://www.youtube.com/embed/A5Y3P99Ua7I";
    }
</script>
 <?php
 * 
 */