<div class="body">
    <?php
    $id_cate = filter_input(INPUT_POST, 'id_cate', FILTER_SANITIZE_NUMBER_INT);
    if (empty($id_cate)) {
        unset($_SESSION['TMP']['FORM']);
        unset($_SESSION['TMP']['CPF']);
        unset($_SESSION['TMP']['CATE']);
    } else {
        $cate = sql::get('inscr_categoria', '*', ['id_cate' => $id_cate], 'fetch');
    }
    $cpf_ = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
    $cpf = preg_replace("/[^0-9]/", "", $cpf_);
    $cpf = toolErp::cpfValida($cpf);
    $pin = filter_input(INPUT_POST, 'pin', FILTER_SANITIZE_NUMBER_INT);

    if (!empty($form)) {
        $_SESSION['TMP']['FORM'] = $form;
    } elseif (!empty($_SESSION['TMP']['FORM'])) {
        $form = $_SESSION['TMP']['FORM'];
    } else {
        $form = null;
    }
    $evento = sql::get('inscr_evento', '*', ['id_evento' => $form], 'fetch');
    if (!empty($evento['entrega_online'])) {
        header("Location: https://portal.educ.net.br/ge/inscr/documentos");
        exit();
    }
    if ($cpf && $pin && $id_cate) {
        $erroPin = userCate($cpf, $form, $id_cate, $pin, $evento);
    }

    if (verificaEvento($form, $evento)) {
        if (!empty($_SESSION['TMP']['CPF'])) {
            $sql = "SELECT * FROM `inscr_incritos_$form` WHERE `id_cpf` LIKE '" . $_SESSION['TMP']['CPF'] . "'";
            $dados = pdoSis::fetch($sql, 'fetch');
            $file = ABSPATH . '/module/inscr/views/_inscr/' . $form . '/main.php';
            if (file_exists($file)) {
                include ABSPATH . '/module/inscr/views/_inscr/' . $form . '/main.php';
            } else {
                include ABSPATH . '/module/inscr/views/_inscr/default.php';
            }
        } else {
            if (empty($id_cate)) {
                include ABSPATH . '/module/inscr/views/_inscr/' . $form . '/categorias.php';
            } elseif (empty($cpf)) {
                include ABSPATH . '/module/inscr/views/_inscr/' . $form . '/cpf.php';
            } else {
                $pin = vereficaUsuario($cpf, $form, $evento);
                if ($pin == 1) {
                    include ABSPATH . '/module/inscr/views/_inscr/' . $form . '/autentica.php';
                } elseif (empty($evento['entrega_online']) && empty($evento['recurso'])) {
                    include ABSPATH . '/module/inscr/views/_inscr/' . $form . '/pin.php';
                } else {
                    ?>
                    <div class="alert alert-danger" style="font-weight: bold;font-size: 1.5em; text-align: center">
                        Sistema fechado para novas inscrições
                    </div>
                    <?php
                }
            }
        }
    }

    function userCate($cpf, $form, $id_cate, $pin, $evento) {
        $sql = "SELECT pin FROM `inscr_incritos_$form` WHERE `id_cpf` LIKE '$cpf'";
        $insc = pdoSis::fetch($sql, 'fetch');
        if ($insc) {
            if ($insc['pin'] == $pin) {
                $sql = "SELECT * FROM `inscr_evento_categoria` "
                        . " where fk_id_evento = $form "
                        . " and fk_id_cpf = $cpf "
                        . " and fk_id_cate = $id_cate ";
                $result = pdoSis::fetch($sql, 'fetch');
                if (empty($result) && empty($evento['entrega_online']) && empty($evento['recurso'])) {
                    $sql = "INSERT INTO `inscr_evento_categoria` "
                            . " (`id_ec`, `fk_id_evento`, `fk_id_cpf`, `fk_id_cate`) VALUES ("
                            . " NULL, "
                            . " '$form', "
                            . " '$cpf', "
                            . " '$id_cate')";
                    $id = pdoSis::action($sql);
                    if (!$id) {
                        ?>
                        <div class="alert alert-danger text-center">
                            Algo deu Errado. Reinicio o processo.
                        </div>
                        <?php
                        return;
                    }
                    $_SESSION['TMP']['SIT'] = 1;
                } elseif (empty($result)) {
                    include ABSPATH . '/module/inscr/views/_inscr/' . $form . '/topo.php';
                    ?>
                    <div class="alert alert-danger" style="font-weight: bold;font-size: 1.5em; text-align: center">
                        Sistema fechado para inscrições em novas Fuções
                    </div>
                    <?php
                    exit();
                } else {
                    $_SESSION['TMP']['SIT'] = $result['fk_id_sit'];
                }
                $_SESSION['TMP']['CATE'] = $id_cate;
                $_SESSION['TMP']['CPF'] = $cpf;
                return;
            } else {
                return 1;
            }
        } else {
            $cpf = null;
        }
    }

    function vereficaUsuario($cpf, $form, $evento) {
        $sql = "SELECT pin FROM `inscr_incritos_$form` WHERE `id_cpf` LIKE '$cpf'";
        $insc = pdoSis::fetch($sql, 'fetch');
        if ($insc) {
            return 1;
        } elseif (empty($evento['entrega_online']) && empty($evento['recurso'])) {
            $pin = mt_rand(100000, 999999);
            $sql = "INSERT INTO `inscr_incritos_$form` (id_cpf, time_stamp, pin) VALUES ('$cpf', CURRENT_TIMESTAMP, '$pin')";
            pdoSis::action($sql);
            $sql = "SELECT pin FROM `inscr_incritos_$form` WHERE `id_cpf` = '$cpf'";
            $insc = pdoSis::fetch($sql, 'fetch');
            return $insc['pin'];
        }
    }

    function verificaEvento($form = null, $evento = null) {
        if (!$form) {
            ?>
            <div class=" alert alert-danger">
                Erro ao iniciar a inscrição
                <br />
                Reinicie o processo.
            </div>
            <?php
            return;
        }
        if (empty($evento)) {
            include ABSPATH . '/module/inscr/views/_inscr/erro.php';
            return;
        } else {
            include ABSPATH . '/module/inscr/views/_inscr/' . $form . '/topo.php';
            if ($evento['at_evento'] != 1) {
                ?>
                <div class="caixag alert alert-warning" style="text-align: center; font-size: 20px">
                    As inscrições não estão abertas
                </div>
                <?php
                return;
            } elseif ($evento['dt_inicio'] > date("Y-m-d")) {
                ?>
                <div class="caixag alert alert-warning" style="text-align: center; font-size: 20px">
                    As inscrições serão abertas no dia <?= data::porExtenso($evento['dt_inicio']) ?>
                </div>
                <?php
                return;
            } elseif ($evento['dt_fim'] < date("Y-m-d")) {
                if ($evento['public'] == 1) {
                    include ABSPATH . '/module/inscr/views/_inscr/' . $form . '/result.php';
                } elseif ($evento['recurso'] == 1) {
                    return 1;
                } else {
                    ?>
                    <div class="caixag alert alert-warning" style="text-align: center; font-size: 20px">
                        As inscrições estão encerradas.
                    </div>
                    <?php
                }
                return;
            }
            return 1;
        }
    }
    ?>
</div>
