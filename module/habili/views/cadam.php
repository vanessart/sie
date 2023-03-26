<?php
if (!defined('ABSPATH'))
    exit;
$token = @$_REQUEST['token'];
if ($token) {
    $dados = sql::get('profe_diario_tmp', '*', ['token' => $token], 'fetch');
}
if (!empty($dados)) {
    ?>
    <div class="body">
        <?php
        include ABSPATH . '/module/habili/views/_cadam/topo.php';
        if ($dados['dt_dt'] < date("Y-m-d")) {
            ?>
            <div class="alert alert-danger">
                Este token expirou
            </div>
            <?php
        } else {
            include ABSPATH . '/module/habili/views/_cadam/chamada.php';
        }
        ?>
    </div>
    <?php
}
