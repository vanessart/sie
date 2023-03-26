<?php

if (!empty($_POST[1]['cpf'])) {
    $sql = "SELECT * FROM `pessoa` WHERE "
            . "(`cpf` = '" . $_POST[1]['cpf'] . "' "
            . "or email = '" . $_POST[1]['email'] . "') "
            . "and id_pessoa != '" . $_POST[1]['id_pessoa'] . "' ";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetch(PDO::FETCH_ASSOC);
    if ($array['cpf'] == $_POST[1]['cpf']) {
        tool::erro("CPF já registrado em nome de " . $array['n_pessoa']);
        $validar = 1;
    }
    if ($array['email'] == $_POST[1]['email']) {
        tool::erro("E-mail já registrado em nome de " . $array['n_pessoa']);
        $validar = 1;
    }
}
?>