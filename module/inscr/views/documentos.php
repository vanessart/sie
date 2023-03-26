<?php
if (!defined('ABSPATH'))
    exit;
$sair = filter_input(INPUT_POST, 'sair', FILTER_SANITIZE_NUMBER_INT);
if ($sair) {
    unset($_SESSION['TMP']['CPF']);
}
$evento = sql::get('inscr_evento', '*', ['id_evento' => 3], 'fetch');
$cpf_ = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
$cpf = preg_replace("/[^0-9]/", "", $cpf_);
$pin = filter_input(INPUT_POST, 'pin', FILTER_SANITIZE_STRING);
if (!empty($_SESSION['TMP']['CPF'])) {
    $cpf = $_SESSION['TMP']['CPF'];
    $sql = "SELECT * FROM `inscr_incritos_3` WHERE `id_cpf` LIKE '$cpf' ";
    $query = pdoSis::getInstance()->query($sql);
    $dados = $query->fetch(PDO::FETCH_ASSOC);
    if ($dados) {
        $sql = "SELECT c.id_ec, ca.n_cate, fk_id_sit FROM inscr_evento_categoria c "
                . " JOIN inscr_categoria ca on ca.id_cate = c.fk_id_cate "
                . " WHERE  c.fk_id_cpf = $cpf ";
        $query = pdoSis::getInstance()->query($sql);
        $categoria = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!$categoria[0]) {
            unset($dados);
            $errCpf = true;
        } elseif ($categoria[0]['fk_id_sit'] != 3) {
            unset($dados);
            $errDef = true;
        }
    }
} elseif ($pin && $cpf) {
    $sql = "SELECT * FROM `inscr_incritos_3` WHERE `id_cpf` LIKE '$cpf' ";
    $query = pdoSis::getInstance()->query($sql);
    $dados = $query->fetch(PDO::FETCH_ASSOC);

    if (!empty($dados)) {
        if ($dados['pin'] != $pin) {
            unset($dados);
            $errCpf = true;
        } else {
            $sql = "SELECT c.id_ec, ca.n_cate, fk_id_sit FROM inscr_evento_categoria c "
                    . " JOIN inscr_categoria ca on ca.id_cate = c.fk_id_cate "
                    . " WHERE c.fk_id_cpf = $cpf ";
            $query = pdoSis::getInstance()->query($sql);
            $categoria = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($categoria[0]) {
                if ($categoria[0]['fk_id_sit'] != 3) {
                    unset($dados);
                    $errDef = true;
                } else {
                    $_SESSION['TMP']['CPF'] = $cpf;
                }
            } else {
                unset($dados);
                $errCpf = true;
            }
            ###################################################################
            /**
              $sql = "SELECT * FROM ge2.inscr_incritos_3 "
              . " where fk_id_vs in (1, 2, 3) "
              . " and id_cpf like '$cpf';";
              //$sql = "SELECT * FROM ge2.inscr_incritos_3 where migrado != 1";
              $query = pdoSis::getInstance()->query($sql);
              $prazo = $query->fetch(PDO::FETCH_ASSOC);
              if (!$prazo) {
              unset($dados);
              unset($_SESSION['TMP']['CPF']);
              $errPrazo = true;
              }
             * 
             */
            ##################################################################
        }
    } else {
        $errCpf = true;
    }
}
?>
<div class="body">
    <?php
    include ABSPATH . "/module/inscr/views/_inscr/3/topo.php";

    if (empty($categoria[0]) || empty($dados)) {
        if (!empty($errDef)) {
            ?>
            <div class="alert alert-danger text-center">
                Entrega de documentos ABERTA apenas para os candidatos DEFERIDOS;
            </div>
            <?php
        } elseif (!empty($errCpf)) {
            ?>
            <div class="alert alert-danger text-center">
                Cpf ou Senha inválida
            </div>
            <?php
        } elseif (!empty($errPrazo)) {
            ?>
            <div class="alert alert-danger text-center">
                Entrega de documentos ABERTA apenas para os remanescentes;
            </div>
            <?php
        }
        include ABSPATH . '/module/inscr/views/_documentos/cpf.php';
    } else {
        include ABSPATH . '/module/inscr/views/_documentos/main.php';
    }
    ?>
</div>