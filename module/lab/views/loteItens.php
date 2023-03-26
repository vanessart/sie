<?php
if (!defined('ABSPATH'))
    exit;
$substring = filter_input(INPUT_POST, 'substring', FILTER_SANITIZE_STRING);
$rangeMi = filter_input(INPUT_POST, 'rangeMi', FILTER_SANITIZE_STRING);
$rangeMa = filter_input(INPUT_POST, 'rangeMa', FILTER_SANITIZE_STRING);
$ins = @$_POST[1];
$id_inst = @$_REQUEST['id_inst'];
if (empty($id_inst)) {
    $id_inst = @$ins['fk_id_inst'];
}
if (!empty($_POST['fim'])) {
    $idsFim = @$_POST[1];
    foreach ($idsFim as $v) {
        if (!empty($v)) {
            $id[] = $v;
        }
    }
    if (!empty($id)) {
        $ids = implode(',', $id);
        $sql = "update lab_chrome set fk_id_modem = null "
                . " where id_ch in ($ids) ";
        $query = pdoSis::getInstance()->query($sql);
    } else {
        toolErp::alert("Selecione os equipamentos primeiro");
    }
}
?>
<div class="body">
    <?php
    if (!empty($ins['serial'])) {
        $ins['serial'] = trim($ins['serial']);
        $jaTem = sqlErp::get('lab_chrome', 'serial', ['serial' => $ins['serial']], 'fetch');
        $erro = null;
        if ($jaTem) {
            $erro = 1;
            ?>
            <div class="alert alert-danger">
                Erro: Chromebook com nº de Séria <span style="font-weight: bold"><?= $ins['serial'] ?></span> já cadastrado';
            </div>
            <?php
        }
        if (!empty($substring)) {
            $tan = strlen($substring);
            if (substr($ins['serial'], 0, $tan) != $substring) {
                $erro = 1;
                ?>
                <div class="alert alert-danger">
                    Erro: Prefixo - N/S: <?= $ins['serial'] ?>
                </div>
                <?php
            }
        }
        if (!empty($rangeMi)) {
            if (strlen($ins['serial']) < $rangeMi) {
                $erro = 1;
                ?>
                <div class="alert alert-danger">
                    Erro: Menor que <?= $rangeMi ?> - N/S: <?= $ins['serial'] ?>
                </div>
                <?php
            }
        }
        if (!empty($rangeMa)) {
            if (strlen($ins['serial']) > $rangeMa) {
                $erro = 1;
                ?>
                <div class="alert alert-danger">
                    Erro: Maior que <?= $rangeMa ?> - N/S: <?= $ins['serial'] ?>
                </div>
                <?php
            }
        }
        if (empty($erro)) {
            ?>
            <div class="alert alert-primary">
                cadastrando
                <br />
                <?php
                $id = $model->db->insert('lab_chrome', $ins, 1);
                if ($id) {
                    ?>
                    cadastrado com sucesso
                </div>
                <?php
            }
        }
    }
    if (empty($id_inst)) {
        $ch = sql::get(['lab_chrome', 'lab_chrome_modelo'], '*', ['fk_id_modem' => 1, 'fk_id_cd' => 5, '<' => 'id_ch']);
    } else {
        $ch = sql::get(['lab_chrome', 'lab_chrome_modelo'], '*', ['fk_id_modem' => 1, 'fk_id_inst' => $id_inst, '<' => 'id_ch']);
    }
    if ($ch) {
        $token = formErp::token('lab_chrome', 'delete');
        foreach ($ch as $k => $v) {
            $ch[$k]['del'] = formErp::submit('Excluir', $token, ['1[id_ch]' => $v['id_ch'], 'id_inst' => $id_inst]);
            $ch[$k]['ck'] = '<input id="' . $v['id_ch'] . '" type="checkbox" name="" value="' . $v['id_ch'] . '" />';
        }
        $form['array'] = $ch;
        $form['fields'] = [
            '<input type="checkbox" name="select-all" id="select-all" />' => 'ck',
            'ID' => 'id_ch',
            'Nº de Série' => 'serial',
            'Modelo' => 'n_cm',
            '||1' => 'del'
        ];
    }
    if (!empty($form)) {
        ##################            
?>
  <pre>   
    <?php
      print_r($_REQUEST);
    ?>
  </pre>
<?php
###################
        report::simple($form);
    }
    ?>

</div>
<?php
if (!empty($form)) {
    ?>
    <div class="row">
        <div class="col text-center">
            <form id="formPdf" target="_blank" action="<?= HOME_URI ?>/lab/pdf/lotePdf.php" method="POST">
                <?php
                foreach ($ch as $k => $v) {
                    ?>
                    <input id="<?= $v['id_ch'] ?>_" type="hidden" name="1[<?= $v['id_ch'] ?>]" value="" />

                    <?php
                }
                ?>
                <input type="hidden" name="id_inst" value="<?= $id_inst ?>" />
            </form>
            <div style="text-align: center">
                <button class="btn btn-info" onclick="pdf()">
                    Imprimir
                </button>
            </div>
        </div>
        <div class="col text-center">
            <button class="btn btn-warning" onclick="fim()">
                Enviar para Escola
            </button>
        </div>
    </div>
    <br />
    <form id="formFim" method="POST">
        <?php
        foreach ($ch as $k => $v) {
            ?>
            <input id="<?= $v['id_ch'] ?>x" type="hidden" name="1[<?= $v['id_ch'] ?>]" value="" />

            <?php
        }
        ?>
        <input type="hidden" name="id_inst" value="<?= $id_inst ?>" />
        <input type="hidden" name="fim" value="1" />
    </form>
    <?php
}
?>

<script>
    $('#select-all').click(function (event) {
        if (this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function () {
                this.checked = true;
            });
        } else {
            $(':checkbox').each(function () {
                this.checked = false;
            });
        }
    });
    function pdf() {
<?php
foreach ($ch as $k => $v) {
    ?>
            if (document.getElementById("<?= $v['id_ch'] ?>").checked) {
                document.getElementById("<?= $v['id_ch'] ?>_").value = document.getElementById("<?= $v['id_ch'] ?>").value;
            } else {
                document.getElementById("<?= $v['id_ch'] ?>_").value = '';
            }
    <?php
}
?>
        document.getElementById("formPdf").submit();
    }
    function fim() {
<?php
foreach ($ch as $k => $v) {
    ?>
            if (document.getElementById("<?= $v['id_ch'] ?>").checked) {
                document.getElementById("<?= $v['id_ch'] ?>x").value = document.getElementById("<?= $v['id_ch'] ?>").value;
            } else {
                document.getElementById("<?= $v['id_ch'] ?>x").value = '';
            }
    <?php
}
?>
        if (confirm("Esta ação irá tirar os equipamentos da lista e não será mais possível excluí-los. Continuar?")) {
            document.getElementById('formFim').submit();
        }
    }
</script>