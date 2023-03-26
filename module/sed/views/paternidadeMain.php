<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="body">
    <div class="fieldTop">
        Projeto Paternidade Responsável
    </div>
    <br /><br />
    <div class="row">
        <div class="col text-center">
            <form target="_blank" action="<?= HOME_URI ?>/sed/pdf/capaPaternidadepdf.php" method="POST">
                <button type="submit" class="btn btn-primary">
                    Capa
                </button>
            </form>
        </div>
          <div class="col text-center">
            <form target="_blank" action="<?= HOME_URI ?>/sed/pdf/paternidadePlan.php" method="POST">
                <button type="submit" class="btn btn-primary">
                    Planilha
                </button>
            </form>
        </div>
    </div>
    <br />

</div>