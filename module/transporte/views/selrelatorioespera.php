<?php
if (!defined('ABSPATH'))
    exit;

$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_STRING);
$a = dataErp::meses();

$valid = false;
if (!empty($id_inst)) {
    $valid = true;
}

if (!empty($mes)) {
    $valid = true;
}

?>
<div class="body">
    <div class="row form-control-plaintext">
        <div class="col-sm-12">
            <div class="fieldTop">
                Lista de Espera
             </div>
        </div>
    </div>

    <form method="POST">
        <div class="row form-control-sm">
            <div class="col-sm-5">
                <?php
                if (user::session('id_nivel') == 10) {
                    echo formErp::select('id_inst', escolas::idInst(), 'Escola', $id_inst);
                } else {
                    ?>
                    <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                    <?php
                }
                ?>
            </div>
            <div class="col-sm-3">
                <?php
                echo formErp::select('mes', $a, 'Selecionar Mês', $mes);
                ?>
            </div>
        </div>
        <div class="row form-control-sm">
            <div class="col-sm-3">
                <?php echo formErp::button('Continuar') ?>
            </div>
        </div>
    </form>
    <br /><br /><br /><br />
    <div class="row">
        <div class="col-sm-3">

        </div>
        <div class="col-sm-3">
            <?php
            if ($valid) {
                ?>
                <form target="_blank" action="<?php echo HOME_URI ?>/transporte/esperapdf" method = "POST">

                    <input type="hidden" name="mes" value="<?php echo $mes ?>" />
                    <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />

                    <button class="btn btn-info">
                        Visualizar PDF
                    </button>
                </form>
                <?php
            } else {
                ?>
                <input class="btn btn-outline-info" type="submit" value="Visualizar PDF" name="pdf" />
                <?php
            }
            ?>
        </div>
        <div class="col-sm-3">
        <?php
            if ($valid) {
                ?>
                <form target="_blank" action="<?php echo HOME_URI ?>/transporte/esperaexcel" method = "POST">

                    <input type="hidden" name="mes" value="<?php echo $mes ?>" />
                    <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />

                    <button class="btn btn-info">
                        Visualizar Excel
                    </button>
                </form>
                <?php
            } else {
                ?>
                <input class="btn btn-outline-info" type="submit" value="Visualizar Excel" name="pdf" />
                <?php
            }
            ?>
        </div>
        <div class="col-sm-3">
            <?php
            if ($valid) {
                ?>
                <form target="_blank" action="<?php echo HOME_URI ?>/transporte/esperaemail" method = "POST">

                    <input type="hidden" name="mes" value="<?php echo $mes ?>" />
                    <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />

                    <button class="btn btn-info">
                        Enviar por E-mail
                    </button>
                </form>
                <?php
            } else {
                ?>
                <input class="btn btn-outline-info" type="submit" value="Enviar por E-mail" name="email" />
                <?php
            }
            ?>
        </div>
        <div class="col-sm-3">

        </div>
    </div>
</div>