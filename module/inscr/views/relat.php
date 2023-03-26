<?php
if (!defined('ABSPATH'))
    exit;
$cate = sql::idNome('inscr_categoria', ['fk_id_evento' => $model->evento]);
?>
<div class="body">
    <div class="fieldTop">
        Relatórios
    </div>
    <!--
    <br /><br /><br />
    <div class="border alert alert-success" style="padding: 20px">
        <div class="fieldTop">
            Resultado do CADAMPE para publicação no jornal
        </div>
        <div class="row">
            <div class="col text-center">
                <form action="<?= HOME_URI ?>/inscr/pdf/final" target="_blank" method="POST">
                    <button class="btn btn-dark">
                        Todos os Deferidos
                    </button>
                </form>
            </div>
            <div class="col text-center">
                <form action="<?= HOME_URI ?>/inscr/pdf/finalIn" target="_blank" method="POST">
                    <button class="btn btn-dark">
                        Todos os Indeferidos
                    </button>
                </form>
            </div>
        </div>
    </div>
    -->
    <br /><br /><br />
    <div class="border alert alert-success" style="padding: 20px">
        <div class="fieldTop">
            Deferidos
        </div>
        <br />
        <div class="row">
            <?php
            $c = 1;
            foreach ($cate as $k => $v) {
                ?>
                <div class="col-4 text-center">
                    <form action="<?= HOME_URI ?>/inscr/pdf/deferidos" target="_blank" method="POST">
                        <input type="hidden" name="id_cate" value="<?= $k ?>" />
                        <input type="hidden" name="n_cate" value="<?= $v ?>" />
                        <button style="width: 100%; min-height: 100px" class="btn btn-success">
                            <?= $v ?>
                        </button>
                    </form>
                </div>
                <?php
                if ($c++ % 3 == 0) {
                    ?>
                </div>
                <br />
                <div class="row">
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <br /><br />
    <div class="border alert alert-danger" style=" padding: 20px">
        <div class="fieldTop">
            Indeferidos
        </div>
        <div class="row">
            <?php
            $c = 1;
            foreach ($cate as $k => $v) {
                ?>
                <div class="col-4 text-center">
                    <form action="<?= HOME_URI ?>/inscr/pdf/indeferidos" target="_blank" method="POST">
                        <input type="hidden" name="id_cate" value="<?= $k ?>" />
                        <input type="hidden" name="n_cate" value="<?= $v ?>" />
                        <button style="width: 100%; min-height: 100px" class="btn btn-danger">
                            <?= $v ?>
                        </button>
                    </form>
                </div>
                <?php
                if ($c++ % 3 == 0) {
                    ?>
                </div>
                <br />
                <div class="row">
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <br /><br />
    <div class="border alert-info" style=" padding: 20px">
        <div class="fieldTop">
            Deferidos - Dados completos
        </div>
        <div class="row">
            <?php
            $c = 1;
            foreach ($cate as $k => $v) {
                ?>
                <div class="col-4 text-center">
                    <form action="<?= HOME_URI ?>/inscr/pdf/deferidosDados" target="_blank" method="POST">
                        <input type="hidden" name="id_cate" value="<?= $k ?>" />
                        <input type="hidden" name="n_cate" value="<?= $v ?>" />
                        <button style="width: 100%; min-height: 100px" class="btn btn-info">
                            <?= $v ?>
                        </button>
                    </form>
                </div>
                <?php
                if ($c++ % 3 == 0) {
                    ?>
                </div>
                <br />
                <div class="row">
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <br />
</div>
