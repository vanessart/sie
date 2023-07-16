<?php
if (!defined('ABSPATH'))
    exit;
$token = @$_REQUEST['token'];
if ($token) {
    $sql = "SELECT * FROM `pdf_autentica` WHERE `token` LIKE '$token'";
    $query = pdoSis::getInstance()->query($sql);
    $dados = $query->fetch(PDO::FETCH_ASSOC);
    if (!empty($dados)) {
        $post = json_decode($dados['post'], true);
    }
}
?>
<div class="body">
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
    <?php
    if (!empty($dados)) {
        ?>
        <div class="alert alert-primary" style="font-weight: bold; font-size: 1.2; text-align: center">
            Documento assinado digitalmente
        </div>
        <iframe name="frame" style="width: 100%; height: 80vh"></iframe>
        <form id="form" target="frame" action="<?= HOME_URI .'/'. $dados['path'] ?>?token=<?= $dados['token'] ?>" method="POST">
            <?= formErp::hidden($post) ?>
        </form>
        <?php
    } else {
        ?>
        <div class="alert alert-danger" style="font-weight: bold; text-align: center;">
            Documento Não Encontrado
        </div>
        <?php
    }
    ?>
</div>
<script>
    $(document).ready(function () {
        document.getElementById('form').submit();
    });
</script>