<?php
if (!defined('ABSPATH'))
    exit;

$sql = "SELECT * FROM `inscr_evento_categoria` WHERE `fk_id_evento` = $form AND `fk_id_cpf` LIKE '" . $_SESSION['TMP']['CPF'] . "' AND `fk_id_cate` = " . $_SESSION['TMP']['CATE'];
$query = pdoSis::getInstance()->query($sql);
$inscrito = $query->fetch(PDO::FETCH_ASSOC);
if ($inscrito['fk_id_sit'] == 3) {
    $deferir = 'Deferida';
    $color = 'blue';
} elseif ($inscrito['fk_id_sit'] == 4) {
    $deferir = 'Indeferida';
    $color = 'red';
} else {
    $deferir = 'Indefinido';
    $color = 'red';
}
$sql = "SELECT cert.*, up.descr_up, up.obrigatorio FROM inscr_certificado_deferimento cert "
        . " JOIN inscr_upload up on up.id_up = cert.fk_id_up "
        . " WHERE cert.fk_id_ec = " . $inscrito['id_ec'];
$query = pdoSis::getInstance()->query($sql);
$cert = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM `inscr_inscr_upload` u "
        . " join inscr_motivo m on m.id_mot = u.fk_id_mot "
        . " WHERE `fk_id_cate` = " . $_SESSION['TMP']['CATE'] . " "
        . " AND `fk_id_evento` = $form "
        . " AND `cpf` LIKE '" . $_SESSION['TMP']['CPF'] . "' "
        . " AND deferido = 2 ";
$query = pdoSis::getInstance()->query($sql);
$up = $query->fetchAll(PDO::FETCH_ASSOC);
$event = sql::get('inscr_evento', 'recurso_ver', ['id_evento' => 3], 'fetch');
$rec = sql::get('inscr_recurso', '*', ['fk_id_ec' => $inscrito['id_ec']], 'fetch');
?>
<div class="body">
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <?php
            if (!empty($event['recurso_ver']) || (empty($event['recurso_ver']) && empty($rec['concluido']))) {
                ?>
                <td colspan="3" style="text-align: center;font-weight: bold; font-size: 25px; color: <?= $color ?>">
                    Inscrição <?= $deferir ?>
                </td>
                <?php
            } else {
                ?>
                <td colspan="3" style="text-align: center;font-weight: bold; font-size: 25px;">
                    Em Análise
                </td>
                <?php
            }
            ?>

        </tr>
        <tr>
            <td>
                Nº de Inscrição
            </td>
            <td>
                Nome
            </td>
            <td>
                Data de Nascimento
            </td>
        </tr>
        <tr>
            <td>
                <?= $inscrito['id_ec'] ?>
            </td>
            <td>
                <?= $dados['nome'] ?>
            </td>
            <td>
                <?= $dados['dt_nasc'] ?>
            </td>
        </tr>
        <?php
        if (!empty($inscrito['obs_ec'])) {
            ?>
            <tr>
                <td colspan="3" style="text-align: center;font-weight: bold">
                    <?= $inscrito['obs_ec'] ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <br /><br />
    <?php
    $abas[1] = ['nome' => "Diplomas/Certificados", 'ativo' => 1, 'hidden' => ['id_cate' => $id_cate]];
    $abas[2] = ['nome' => "Requerimento de Recurso ", 'ativo' => 1, 'hidden' => ['id_cate' => $id_cate]];
    $aba = report::abas($abas, null, 'rec');
    include ABSPATH . "/module/inscr/views/_inscr/3/_recurso/$aba.php";
    ?>
</div>
